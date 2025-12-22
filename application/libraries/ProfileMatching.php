<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Profile Matching Library
 * 
 * Library untuk menghitung ranking Customer Service menggunakan metode Profile Matching
 * dengan bobot Core Factor 90% dan Secondary Factor 10%
 * 
 */
class ProfileMatching
{
	protected $CI;

	const BOBOT_CF = 0.9;  // 90%
	const BOBOT_SF = 0.1;  // 10%
	const DEFAULT_GAP = 3.0;

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('RangeModel', 'Range');
	}

	//  Hitung Nilai Konversi
	// Mapping nilai aktual LANGSUNG ke tabel range untuk mendapat nilai konversi (1-5)
	// Untuk SF (cost): nilai kecil = bagus, jadi hasil konversi akan lebih tinggi
	public function hitungGap($idSubKriteria, $nilaiAktual, $jenisKriteria = 'core_factor', $profilIdeal = null)
	{
		// Mapping nilai ASLI langsung ke tabel range
		$range = $this->CI->Range->getSubKriteriaByNilai($idSubKriteria, $nilaiAktual);

		return $range
			? [
				'gap'      => (float) $range->nilai_range,  // Nilai konversi 1-5
				'id_range' => (int) $range->id_range,
				'gap_asli' => $nilaiAktual  // Untuk debugging
			]
			: [
				'gap'      => self::DEFAULT_GAP,
				'id_range' => null,
				'gap_asli' => $nilaiAktual
			];
	}

	// Hitung Ranking Customer Service
	public function hitungRanking($dataPenilaian, $periode, $simpanKonversi = false)
	{
		if (empty($dataPenilaian)) {
			return $simpanKonversi ? ['rankings' => [], 'konversi' => []] : [];
		}

		$infoTambahan = $this->ambilInfoTambahan($dataPenilaian);
		$dataPerCS = $this->grupkanDataPerCS($dataPenilaian, $periode, $infoTambahan);
		$hasilKonversi = [];

		// Proses konversi dan akumulasi
		foreach ($dataPenilaian as $row) {
			// Mapping nilai ASLI langsung ke range untuk dapat nilai konversi (1-5)
			$gap = $this->hitungGap(
				$row->id_sub_kriteria, 
				(float) $row->nilai,
				$row->jenis_kriteria
			);

			// Simpan hasil konversi
			if ($simpanKonversi) {
				$hasilKonversi[] = [
					'id_cs'           => $row->id_cs,
					'id_sub_kriteria' => $row->id_sub_kriteria,
					'id_range'        => $gap['id_range'],
					'nilai_asli'      => (float) $row->nilai,
					'nilai_konversi'  => $gap['gap'],  // Nilai 1-5 dari tabel range
					'periode'         => $periode
				];
			}

			// Akumulasi nilai konversi
			$this->akumulasiNilai(
				$dataPerCS[$row->id_cs],
				$gap['gap'],  // Nilai konversi 1-5
				$row->jenis_kriteria,
				(float) $row->bobot_sub
			);
		}

		$rankings = $this->hitungSkorAkhir($dataPerCS);

		return $simpanKonversi
			? ['rankings' => $rankings, 'konversi' => $hasilKonversi]
			: $rankings;
	}


	// AMBIL INFO TAMBAHAN (CS & TIM)
	private function ambilInfoTambahan($dataPenilaian)
	{
		$csIds = array_unique(array_column($dataPenilaian, 'id_cs'));
		$info = ['cs' => [], 'tim' => []];

		if (empty($csIds)) return $info;

		// Batch query CS
		$csData = $this->CI->db
			->select('cs.id_cs, cs.id_tim, cs.id_produk, p.nama_produk, t.nama_tim')
			->from('customer_service cs')
			->join('produk p', 'p.id_produk = cs.id_produk', 'left')
			->join('tim t', 't.id_tim = cs.id_tim', 'left')
			->where_in('cs.id_cs', $csIds)
			->get()
			->result();

		$timIds = [];
		foreach ($csData as $cs) {
			$info['cs'][$cs->id_cs] = [
				'nama_produk' => $cs->nama_produk,
				'nama_tim'    => $cs->nama_tim,
				'id_tim'      => $cs->id_tim,
				'id_produk'   => $cs->id_produk
			];
			if ($cs->id_tim) $timIds[] = $cs->id_tim;
		}

		// Batch query Tim
		if (!empty($timIds)) {
			$timData = $this->CI->db
				->select('t.id_tim, t.nama_tim, l.nama_pengguna as nama_leader')
				->from('tim t')
				->join('pengguna l', 'l.id_user = t.id_leader', 'left')
				->where_in('t.id_tim', array_unique($timIds))
				->get()
				->result();

			foreach ($timData as $tim) {
				$info['tim'][$tim->id_tim] = [
					'nama_tim'    => $tim->nama_tim,
					'nama_leader' => $tim->nama_leader
				];
			}
		}

		return $info;
	}


	// Grupkan data penilaian per CS
	private function grupkanDataPerCS($dataPenilaian, $periode, $infoTambahan)
	{
		$group = [];

		foreach ($dataPenilaian as $row) {
			if (!isset($group[$row->id_cs])) {
				$csInfo = $infoTambahan['cs'][$row->id_cs] ?? null;
				$timInfo = $csInfo['id_tim']
					? ($infoTambahan['tim'][$csInfo['id_tim']] ?? null)
					: null;

				$group[$row->id_cs] = [
					'id_cs'       => $row->id_cs,
					'nama_cs'     => $row->nama_cs ?? '-',
					'nik'         => $row->nik ?? '-',
					'periode'     => $periode,
					'total_cf'    => 0,
					'item_cf'     => 0, // Count of CF items
					'total_sf'    => 0,
					'item_sf'     => 0, // Count of SF items
					'nama_tim'    => $timInfo['nama_tim'] ?? $csInfo['nama_tim'] ?? null,
					'nama_produk' => $csInfo['nama_produk'] ?? null,
					'nama_leader' => $timInfo['nama_leader'] ?? null,
					'id_produk'   => $csInfo['id_produk'] ?? null,
				];
			}
		}

		return $group;
	}


	// Akumulasi nilai per CS (Simple Average)
	private function akumulasiNilai(&$dataCS, $nilaiGap, $jenisKriteria, $bobotSub)
	{
		if (strtolower(trim($jenisKriteria)) === 'core_factor') {
			$dataCS['total_cf'] += $nilaiGap;
			$dataCS['item_cf'] += 1; // Count items
		} else {
			$dataCS['total_sf'] += $nilaiGap;
			$dataCS['item_sf'] += 1; // Count items
		}
	}

	// Hitung skor akhir dan peringkat
	private function hitungSkorAkhir($dataPerCS)
	{
		$rankings = [];

		foreach ($dataPerCS as $data) {
			$ncf = $data['item_cf'] > 0 ? ($data['total_cf'] / $data['item_cf']) : 0;
			$nsf = $data['item_sf'] > 0 ? ($data['total_sf'] / $data['item_sf']) : 0;
			$skorAkhir = ($ncf * self::BOBOT_CF) + ($nsf * self::BOBOT_SF);

			$rankings[] = (object) [
				'id_cs'       => $data['id_cs'],
				'nama_cs'     => $data['nama_cs'],
				'nik'         => $data['nik'],
				'ncf'         => round($ncf, 4),
				'nsf'         => round($nsf, 4),
				'skor_akhir'  => round($skorAkhir, 6),
				'periode'     => $data['periode'],
				'nama_tim'    => $data['nama_tim'],
				'nama_produk' => $data['nama_produk'],
				'nama_leader' => $data['nama_leader'],
				'id_produk'   => $data['id_produk'],
			];
		}

		// Urutkan berdasarkan skor tertinggi
		usort($rankings, fn($a, $b) => $b->skor_akhir <=> $a->skor_akhir);

		// Tambahkan peringkat
		foreach ($rankings as $i => $ranking) {
			$rankings[$i]->peringkat = $i + 1;
		}

		return $rankings;
	}


	// Fungsi publik untuk menghitung ranking
	public function compute($penilaian, $periode, $includeKonversi = false)
	{
		return $this->hitungRanking($penilaian, $periode, $includeKonversi);
	}

	public function mapGap($idSub, $actual, $jenisKriteria = 'core_factor', $profilIdeal = null)
	{
		$result = $this->hitungGap($idSub, $actual, $jenisKriteria, $profilIdeal);
		return [$result['gap'], $result['id_range']];
	}
}
