<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileMatching
{

	protected $CI;

	// Konstanta untuk bobot CF dan SF
	const BOBOT_CF = 0.6;
	const BOBOT_SF = 0.4;

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('RangeModel');
	}

	/**
	 * Mapping nilai gap ke bobot
	 */
	private function getGapWeight($gap)
	{
		$weights = [
			0 => 5,
			1 => 4.5,
			2 => 4,
			3 => 3.5,
			4 => 3,
			5 => 2.5,
			6 => 2,
			7 => 1.5,
			8 => 1
		];

		$absGap = abs(round($gap));
		return $weights[$absGap] ?? 1;
	}

	/**
	 * Hitung nilai konversi berdasarkan gap
	 */
	public function hitungNilaiKonversi($idSub, $nilaiAktual, $nilaiTarget)
	{
		// Cek apakah ada range khusus di database
		$rangeRow = $this->CI->RangeModel->getSubKriteriaByNilai($idSub, $nilaiAktual);

		if ($rangeRow) {
			return [
				'nilai_konversi' => (float)$rangeRow->nilai_range,
				'id_range' => $rangeRow->id_range
			];
		}

		// Gunakan mapping standar
		$gap = $nilaiTarget - $nilaiAktual;
		$nilaiKonversi = $this->getGapWeight($gap);

		return [
			'nilai_konversi' => $nilaiKonversi,
			'id_range' => null
		];
	}

	/**
	 * Hitung ranking dengan metode Profile Matching
	 * 
	 * @param array $dataPenilaian Data penilaian dari database
	 * @param string $periode Periode penilaian (YYYY-MM)
	 * @param bool $simpanKonversi Simpan data konversi atau tidak
	 * @return array Hasil ranking atau [rankings, konversi]
	 */
	public function hitungRanking($dataPenilaian, $periode, $simpanKonversi = false)
	{
		if (empty($dataPenilaian)) {
			return [];
		}

		// Ambil informasi tambahan (tim, produk, leader) sekali saja
		$infoTambahan = $this->ambilInfoTambahan($dataPenilaian);

		$dataPerCS = $this->grupkanDataPerCS($dataPenilaian, $periode, $infoTambahan);
		$hasilKonversi = [];

		// Proses setiap CS
		foreach ($dataPenilaian as $row) {
			$idCs = $row->id_cs;

			// Hitung nilai konversi
			$konversi = $this->hitungNilaiKonversi(
				$row->id_sub_kriteria,
				(float)$row->nilai,
				(float)$row->target
			);

			// Simpan untuk keperluan export konversi
			if ($simpanKonversi) {
				$hasilKonversi[] = [
					'id_cs' => $idCs,
					'id_sub_kriteria' => $row->id_sub_kriteria,
					'id_range' => $konversi['id_range'],
					'nilai_asli' => (float)$row->nilai,
					'nilai_konversi' => $konversi['nilai_konversi'],
				];
			}

			// Akumulasi nilai berdasarkan jenis kriteria
			$this->akumulasiNilai(
				$dataPerCS[$idCs],
				$konversi['nilai_konversi'],
				$row->jenis_kriteria,
				(float)$row->bobot_kriteria
			);
		}

		// Hitung skor akhir dan ranking
		$rankings = $this->hitungSkorAkhir($dataPerCS);

		// Return sesuai kebutuhan
		if ($simpanKonversi) {
			return [
				'rankings' => $rankings,
				'konversi' => $hasilKonversi
			];
		}

		return $rankings;
	}


	/**
	 * Ambil informasi tambahan (tim, produk, leader) dengan query yang optimal
	 */
	private function ambilInfoTambahan($dataPenilaian)
	{
		// Kumpulkan semua ID CS dan ID Tim yang unik
		$csIds = [];
		$timIds = [];

		foreach ($dataPenilaian as $row) {
			$csIds[] = $row->id_cs;
			if (!empty($row->id_tim)) {
				$timIds[] = $row->id_tim;
			}
		}

		$csIds = array_unique($csIds);
		$timIds = array_unique($timIds);

		$info = [
			'cs' => [],
			'tim' => []
		];

		// Batch query untuk data CS (produk dan tim)
		if (!empty($csIds)) {
			$this->CI->load->model('CustomerServiceModel');

			$csData = $this->CI->db
				->select('cs.id_cs, cs.id_tim, cs.id_produk, p.nama_produk, t.nama_tim')
				->from('customer_service cs')
				->join('produk p', 'p.id_produk = cs.id_produk', 'left')
				->join('tim t', 't.id_tim = cs.id_tim', 'left')
				->where_in('cs.id_cs', $csIds)
				->get()
				->result();

			foreach ($csData as $cs) {
				$info['cs'][$cs->id_cs] = [
					'nama_produk' => $cs->nama_produk,
					'nama_tim' => $cs->nama_tim,
					'id_tim' => $cs->id_tim,
					'id_produk' => $cs->id_produk
				];

				// Tambahkan id_tim ke array untuk batch query tim
				if (!empty($cs->id_tim) && !in_array($cs->id_tim, $timIds)) {
					$timIds[] = $cs->id_tim;
				}
			}
		}

		// Batch query untuk data Tim (leader)
		if (!empty($timIds)) {
			$timIds = array_unique($timIds);
			$this->CI->load->model('TimModel');

			$timData = $this->CI->db
				->select('t.id_tim, t.nama_tim, l.nama_pengguna as nama_leader')
				->from('tim t')
				->join('pengguna l', 'l.id_user = t.id_leader', 'left')
				->where_in('t.id_tim', $timIds)
				->get()
				->result();

			foreach ($timData as $tim) {
				$info['tim'][$tim->id_tim] = [
					'nama_tim' => $tim->nama_tim,
					'nama_leader' => $tim->nama_leader
				];
			}
		}

		return $info;
	}
	/**
	 * Grupkan data per CS dengan informasi tambahan
	 */
	private function grupkanDataPerCS($dataPenilaian, $periode, $infoTambahan)
	{
		$group = [];

		foreach ($dataPenilaian as $row) {
			$idCs = $row->id_cs;

			if (!isset($group[$idCs])) {
				$csInfo = $infoTambahan['cs'][$idCs] ?? null;
				$idTim = $csInfo['id_tim'] ?? $row->id_tim ?? null;
				$timInfo = $idTim ? ($infoTambahan['tim'][$idTim] ?? null) : null;

				$group[$idCs] = [
					'id_cs' => $idCs,
					'nama_cs' => $row->nama_cs ?? '-',
					'nik' => $row->nik ?? '-',
					'periode' => $periode,
					'total_cf' => 0,
					'bobot_cf' => 0,
					'total_sf' => 0,
					'bobot_sf' => 0,
					'nama_tim' => $timInfo['nama_tim'] ?? $csInfo['nama_tim'] ?? null,
					'nama_produk' => $csInfo['nama_produk'] ?? null,
					'nama_leader' => $timInfo['nama_leader'] ?? null,
					'id_produk' => $csInfo['id_produk'] ?? null,
				];
			}
		}

		return $group;
	}

	/**
	 * Akumulasi nilai CF atau SF
	 */
	private function akumulasiNilai(&$dataCS, $nilaiKonversi, $jenisKriteria, $bobot)
	{
		$jenis = strtolower(trim($jenisKriteria));

		if ($jenis === 'core_factor') {
			$dataCS['total_cf'] += $nilaiKonversi * $bobot;
			$dataCS['bobot_cf'] += $bobot;
		} else {
			$dataCS['total_sf'] += $nilaiKonversi * $bobot;
			$dataCS['bobot_sf'] += $bobot;
		}
	}

	/**
	 * Hitung skor akhir dan urutkan ranking
	 */
	private function hitungSkorAkhir($dataPerCS)
	{
		$rankings = [];

		foreach ($dataPerCS as $data) {
			// Hitung NCF dan NSF
			$ncf = $data['bobot_cf'] > 0
				? ($data['total_cf'] / $data['bobot_cf'])
				: 0;

			$nsf = $data['bobot_sf'] > 0
				? ($data['total_sf'] / $data['bobot_sf'])
				: 0;

			// Hitung skor akhir (60% CF + 40% SF)
			$skorAkhir = ($ncf * self::BOBOT_CF) + ($nsf * self::BOBOT_SF);

			$rankings[] = (object)[
				'id_cs' => $data['id_cs'],
				'nama_cs' => $data['nama_cs'],
				'nik' => $data['nik'],
				'ncf' => round($ncf, 4),
				'nsf' => round($nsf, 4),
				'skor_akhir' => round($skorAkhir, 6),
				'periode' => $data['periode'],
				'nama_tim' => $data['nama_tim'],
				'nama_produk' => $data['nama_produk'],
				'nama_leader' => $data['nama_leader'],
				'id_produk' => $data['id_produk'] ?? null, // Tambahkan id_produk
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

	/**
	 * Alias untuk backward compatibility
	 */
	public function compute($penilaian, $periode, $includeKonversi = false)
	{
		return $this->hitungRanking($penilaian, $periode, $includeKonversi);
	}

	public function mapGap($idSub, $actual, $target)
	{
		$result = $this->hitungNilaiKonversi($idSub, $actual, $target);
		return [$result['nilai_konversi'], $result['id_range']];
	}
}
