<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingController extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('RankingModel');
		$this->load->model('NilaiModel');
		$this->load->model('KonversiModel');
		$this->load->model('TimModel');
		$this->load->model('ProdukModel');
		$this->load->library('ProfileMatching');
	}

	public function index()
	{
		set_page_title('Hasil Ranking');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Hasil Ranking']
		]);

		enable_datatables();
		enable_charts();

		// Ambil filter dari query string
		$periode = $this->input->get('periode') ?? date('Y-m');
		$idTim = $this->input->get('tim');
		$idProduk = $this->input->get('produk');

		// Build filter
		$filter = ['periode' => $periode];
		if (!empty($idTim)) {
			$filter['id_tim'] = $idTim;
		}

		if (!empty($idProduk)) {
			$filter['id_produk'] = $idProduk;
		}

		// Ambil data dan hitung ranking
		$penilaian = $this->NilaiModel->getAllWithDetails($filter);
		$rankings = $this->profilematching->hitungRanking($penilaian, $periode);

		$data = [
			'rankings' => $rankings,
			'filter_periode' => $periode,
			'filter_tim' => $idTim,
			'tim' => $this->TimModel->all(),
			'produk' => $this->ProdukModel->all()
		];

		render_layout('admin/ranking/index', $data);
	}

	/**
	 * Proses dan simpan ranking ke database
	 */
	public function process()
	{
		// Validasi input
		if (!$this->validasiInput()) {
			return;
		}

		$periode = $this->input->post('periode', true);
		$idTim = $this->input->post('tim') ?: null;

		// Ambil data penilaian
		$filter = ['periode' => $periode];
		if (!empty($idTim)) {
			$filter['id_tim'] = $idTim;
		}

		$penilaian = $this->NilaiModel->getAllWithDetails($filter);

		if (empty($penilaian)) {
			$this->session->set_flashdata('error', 'Tidak ada data penilaian pada periode ini!');
			redirect('admin/ranking');
			return;
		}

		// Hitung ranking dan konversi
		$result = $this->profilematching->hitungRanking($penilaian, $periode, true);

		if (empty($result['rankings'])) {
			$this->session->set_flashdata('error', 'Gagal memproses ranking.');
			redirect('admin/ranking');
			return;
		}

		// Simpan ke database dengan transaction
		if ($this->simpanHasilRanking($result, $periode, $idTim)) {
			$this->session->set_flashdata('success', 'Ranking berhasil diproses dan disimpan.');
		} else {
			$this->session->set_flashdata('error', 'Gagal menyimpan ranking. Silakan coba lagi.');
		}

		redirect('admin/ranking?periode=' . $periode);
	}

	/**
	 * Validasi input form
	 */
	private function validasiInput()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('periode', 'Periode', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin/ranking');
			return false;
		}

		return true;
	}

	/**
	 * Simpan hasil ranking ke database
	 */
	private function simpanHasilRanking($result, $periode, $idTim = null)
	{
		$this->db->trans_start();

		// 1. Simpan data konversi
		$this->simpanDataKonversi($result['konversi']);

		// 2. Hapus ranking lama
		$this->hapusRankingLama($periode, $idTim);

		// 3. Simpan ranking baru
		$this->simpanRankingBaru($result['rankings'], $periode);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	/**
	 * Simpan data konversi
	 */
	private function simpanDataKonversi($dataKonversi)
	{
		if (empty($dataKonversi)) {
			return;
		}

		// Hapus konversi lama untuk CS yang terkait
		$affectedCs = array_unique(array_column($dataKonversi, 'id_cs'));
		foreach ($affectedCs as $csId) {
			$this->db->where('id_cs', $csId)->delete('konversi');
		}

		// Validasi id_range
		$dataKonversi = $this->validasiIdRange($dataKonversi);

		// Insert data konversi baru
		if (method_exists($this->KonversiModel, 'bulkCreate')) {
			$this->KonversiModel->bulkCreate($dataKonversi);
		} else {
			$this->db->insert_batch('konversi', $dataKonversi);
		}
	}

	/**
	 * Validasi id_range agar tidak error foreign key
	 */
	private function validasiIdRange($dataKonversi)
	{
		$allRangeIds = array_filter(array_unique(array_column($dataKonversi, 'id_range')));

		if (empty($allRangeIds)) {
			return $dataKonversi;
		}

		// Ambil id_range yang valid dari database
		$existingIds = $this->db
			->select('id_range')
			->from('range')
			->where_in('id_range', $allRangeIds)
			->get()
			->result_array();

		$validIds = array_column($existingIds, 'id_range');

		// Set id_range = null jika tidak valid
		foreach ($dataKonversi as &$item) {
			if (!empty($item['id_range']) && !in_array($item['id_range'], $validIds)) {
				$item['id_range'] = null;
			}
		}

		return $dataKonversi;
	}

	/**
	 * Hapus ranking lama
	 */
	private function hapusRankingLama($periode, $idTim = null)
	{
		if (method_exists($this->RankingModel, 'deleteByPeriode')) {
			$this->RankingModel->deleteByPeriode($periode, $idTim);
		} else {
			$this->db->where('periode', $periode)->delete('ranking');
		}
	}

	/**
	 * Simpan ranking baru
	 */
	private function simpanRankingBaru($rankings, $periode)
	{
		$bulkData = [];
		$now = date('Y-m-d H:i:s');

		foreach ($rankings as $ranking) {
			$bulkData[] = [
				'id_cs' => $ranking->id_cs,
				'id_produk' => $ranking->id_produk ?? null,
				'nilai_akhir' => $ranking->skor_akhir,
				'peringkat' => $ranking->peringkat,
				'periode' => $periode,
				'status' => 'published',
				'created_at' => $now,
				'updated_at' => $now,
			];
		}

		if (method_exists($this->RankingModel, 'bulkCreate')) {
			$this->RankingModel->bulkCreate($bulkData);
		} else {
			$this->db->insert_batch('ranking', $bulkData);
		}
	}

	/**
	 * Detail ranking untuk modal AJAX
	 */
	public function detail()
	{
		$idCs = $this->input->get('id');
		$periode = $this->input->get('periode') ?? date('Y-m');

		if (empty($idCs)) {
			echo '<div class="p-4 text-center text-danger">Parameter ID tidak ditemukan.</div>';
			return;
		}

		// Ambil data nilai CS
		$nilaiAll = $this->NilaiModel->getByCustomerService($idCs);
		$nilai = array_filter($nilaiAll, fn($r) => ($r->periode ?? '') == $periode);

		if (empty($nilai)) {
			echo '<div class="p-4 text-center text-muted">Belum ada penilaian untuk periode ini.</div>';
			return;
		}

		// Hitung detail breakdown
		$detail = $this->hitungDetailCS($nilai);

		// Fetch CS and team/product/leader details
		$this->load->model('CustomerServiceModel');
		$csInfo = $this->CustomerServiceModel->getByIdWithDetails($idCs);

		$namaLeader = null;
		if (!empty($csInfo->id_tim)) {
			$this->load->model('TimModel');
			$timInfo = $this->TimModel->getByIdWithDetails($csInfo->id_tim);
			$namaLeader = $timInfo->nama_leader ?? null;
		}

		$data = array_merge($detail, [
			'periode' => $periode,
			'id_cs' => $idCs,
			'cs' => (object)[
				'nama_cs' => $csInfo->nama_cs ?? ($nilaiAll ? reset($nilaiAll)->nama_cs : '-'),
				'nik' => $csInfo->nik ?? ($nilaiAll ? reset($nilaiAll)->nik : '-'),
				'nama_tim' => $csInfo->nama_tim ?? ($nilaiAll ? reset($nilaiAll)->nama_tim : null),
				'nama_produk' => $csInfo->nama_produk ?? null,
				'nama_leader' => $namaLeader,
			],
		]);

		$this->load->view('admin/ranking/detail', $data);
	}

	/**
	 * Hitung detail breakdown per CS
	 */
	private function hitungDetailCS($dataNilai)
	{
		$rows = [];
		$totalCF = $bobotCF = $totalSF = $bobotSF = 0;

		foreach ($dataNilai as $row) {
			$nilaiAktual = (float)$row->nilai;
			$nilaiTarget = (float)($row->target ?? 0);
			$bobot = (float)($row->bobot_kriteria ?? 0);

			// Hitung nilai konversi
			$konversi = $this->profilematching->hitungNilaiKonversi(
				$row->id_sub_kriteria,
				$nilaiAktual,
				$nilaiTarget
			);

			$jenis = strtolower(trim($row->jenis_kriteria ?? ''));

			// Akumulasi berdasarkan jenis
			if ($jenis === 'core_factor') {
				$totalCF += $konversi['nilai_konversi'] * $bobot;
				$bobotCF += $bobot;
			} else {
				$totalSF += $konversi['nilai_konversi'] * $bobot;
				$bobotSF += $bobot;
			}

			$rows[] = [
				'kode_kriteria' => $row->kode_kriteria ?? '-',
				'nama_kriteria' => $row->nama_kriteria ?? '-',
				'nama_sub' => $row->nama_sub_kriteria ?? '-',
				'nilai_asli' => $nilaiAktual,
				'target' => $nilaiTarget,
				'gap' => $nilaiTarget - $nilaiAktual,
				'nilai_konversi' => $konversi['nilai_konversi'],
				'bobot' => $bobot,
				'jenis' => $jenis,
			];
		}

		$ncf = $bobotCF > 0 ? ($totalCF / $bobotCF) : 0;
		$nsf = $bobotSF > 0 ? ($totalSF / $bobotSF) : 0;
		$skorAkhir = ($ncf * 0.6) + ($nsf * 0.4);

		return [
			'rows' => $rows,
			'ncf' => $ncf,
			'nsf' => $nsf,
			'skor' => $skorAkhir,
		];
	}
}
