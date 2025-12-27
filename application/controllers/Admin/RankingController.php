<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * RankingController
 * 
 * Controller untuk:
 * - Menampilkan hasil ranking Customer Service
 * - Memproses perhitungan ranking (Profile Matching)
 * - Menyimpan hasil ranking & konversi ke database
 * - Export ranking ke Excel
 * - Menampilkan detail perhitungan ranking per CS
 */
class RankingController extends Admin_Controller
{
	/**
	 * Constructor
	 * Load seluruh model dan library yang dibutuhkan
	 */
	public function __construct()
	{
		parent::__construct();

		// Load model utama
		$this->load->model('RankingModel');
		$this->load->model('NilaiModel');
		$this->load->model('KonversiModel');
		$this->load->model('TimModel');
		$this->load->model('ProdukModel');

		// Library untuk metode Profile Matching
		$this->load->library('ProfileMatching');
	}

	/**  
	 * Menampilkan hasil ranking (tanpa menyimpan ke DB)
	 * */
	public function index()
	{
		// Set judul halaman
		set_page_title('Hasil Ranking');

		// Breadcrumb navigasi
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Hasil Ranking']
		]);

		// Aktifkan DataTables dan Chart
		enable_datatables();
		enable_charts();

		// Ambil filter dari URL
		$periode   = $this->input->get('periode') ?? date('Y-m');
		$idTim     = $this->input->get('tim');
		$idProduk  = $this->input->get('produk');

		// Susun filter query
		$filter = ['periode' => $periode];
		if ($idTim)    $filter['id_tim']    = $idTim;
		if ($idProduk) $filter['id_produk'] = $idProduk;

		// Ambil data penilaian sesuai filter
		$penilaian = $this->NilaiModel->getAllWithDetails($filter);

		// Hitung ranking menggunakan metode Profile Matching
		$rankings = $this->profilematching->hitungRanking($penilaian, $periode);

		// Render halaman hasil ranking
		render_layout('admin/ranking/index', [
			'rankings'        => $rankings,
			'filter_periode'  => $periode,
			'filter_tim'      => $idTim,
			'filter_produk'   => $idProduk,
			'tim'             => $this->TimModel->all(),
			'produk'          => $this->ProdukModel->all()
		]);
	}

	/**
	 * PROCESS
	 * Proses perhitungan ranking & simpan ke database
	 * */
	public function process()
	{
		// Validasi input periode
		$this->load->library('form_validation');
		$this->form_validation->set_rules('periode', 'Periode', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin/ranking');
			return;
		}

		// Ambil input
		$periode = $this->input->post('periode', true);
		$idTim   = $this->input->post('tim') ?: null;

		// Susun filter data penilaian
		$filter = ['periode' => $periode];
		if ($idTim) $filter['id_tim'] = $idTim;

		// Ambil data penilaian
		$penilaian = $this->NilaiModel->getAllWithDetails($filter);

		// Validasi jika tidak ada data
		if (empty($penilaian)) {
			$this->session->set_flashdata('error', 'Tidak ada data penilaian pada periode ini!');
			redirect('admin/ranking');
			return;
		}

		// Hitung ranking + data konversi
		$result = $this->profilematching->hitungRanking($penilaian, $periode, true);

		if (empty($result['rankings'])) {
			$this->session->set_flashdata('error', 'Gagal memproses ranking.');
			redirect('admin/ranking');
			return;
		}

		// Mulai transaksi database
		$this->db->trans_start();

		/* ===========================
		 * 1. SIMPAN DATA KONVERSI
		 * =========================== */
		if (!empty($result['konversi'])) {

			// Ambil ID CS unik
			$csIds = array_unique(array_column($result['konversi'], 'id_cs'));

			// Hapus konversi lama CS terkait
			$this->db->where_in('id_cs', $csIds)->delete('konversi');

			// Normalisasi data konversi
			$cleanKonversi = [];
			foreach ($result['konversi'] as $item) {

				// Lewati data tanpa id_range (FK constraint)
				if (empty($item['id_range'])) {
					continue;
				}

				$cleanKonversi[] = [
					'id_cs'           => (int) $item['id_cs'],
					'id_sub_kriteria' => (int) $item['id_sub_kriteria'],
					'id_range'        => (int) $item['id_range'],
					'nilai_asli'      => (float) $item['nilai_asli'],
					'nilai_konversi'  => (float) $item['nilai_konversi'],
				];
			}

			// Simpan batch konversi
			if (!empty($cleanKonversi)) {
				$this->db->insert_batch('konversi', $cleanKonversi);
			}
		}

		/* ===========================
		 * 2. HAPUS RANKING LAMA
		 * =========================== */
		$this->db->where('periode', $periode);

		if ($idTim) {
			$csIds = $this->db->select('id_cs')
				->from('customer_service')
				->where('id_tim', $idTim)
				->get()
				->result_array();

			if (!empty($csIds)) {
				$this->db->where_in('id_cs', array_column($csIds, 'id_cs'));
			}
		}

		$this->db->delete('ranking');

		/* ===========================
		 * 3. SIMPAN RANKING BARU
		 * =========================== */
		$bulkData = [];
		$now = date('Y-m-d H:i:s');

		foreach ($result['rankings'] as $r) {
			$bulkData[] = [
				'id_cs'        => $r->id_cs,
				'id_produk'   => $r->id_produk ?? null,
				'nilai_akhir' => $r->skor_akhir,
				'peringkat'   => $r->peringkat,
				'periode'     => $periode,
				'status'      => 'published',
				'created_at'  => $now,
				'updated_at'  => $now,
			];
		}

		$this->db->insert_batch('ranking', $bulkData);

		// Selesaikan transaksi
		$this->db->trans_complete();

		// Status transaksi
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('success', 'Ranking berhasil diproses dan disimpan.');
		} else {
			$this->session->set_flashdata('error', 'Gagal menyimpan ranking.');
		}

		redirect('admin/ranking?periode=' . $periode);
	}

	/* ======================================================
	 * Export ranking ke file Excel
	 * ====================================================== */
	public function export()
	{
		// Ambil filter
		$periode  = $this->input->get('periode') ?? date('Y-m');
		$idTim    = $this->input->get('tim');
		$idProduk = $this->input->get('produk');

		// Susun filter
		$filter = ['periode' => $periode];
		if ($idTim)    $filter['id_tim']    = $idTim;
		if ($idProduk) $filter['id_produk'] = $idProduk;

		// Ambil data & hitung ranking
		$penilaian = $this->NilaiModel->getAllWithDetails($filter);
		$rankings  = $this->profilematching->hitungRanking($penilaian, $periode);

		if (empty($rankings)) {
			$this->session->set_flashdata('error', 'Tidak ada data ranking untuk periode ini.');
			redirect('admin/ranking');
			return;
		}

		// Informasi filter untuk header Excel
		$filterInfo = [];
		if ($idTim) {
			$timInfo = $this->TimModel->find($idTim);
			$filterInfo['tim'] = $timInfo->nama_tim ?? '-';
		}
		if ($idProduk) {
			$produkInfo = $this->ProdukModel->find($idProduk);
			$filterInfo['produk'] = $produkInfo->nama_produk ?? '-';
		}

		// Export ke Excel
		$this->load->library('ExportLaporan');
		$this->exportlaporan->exportRanking($rankings, $periode, $filterInfo);
	}

	/* ======================================================
	 * DETAIL
	 * Menampilkan detail perhitungan ranking per CS
	 * ====================================================== */
	public function detail()
	{
		$idCs   = $this->input->get('id');
		$periode = $this->input->get('periode') ?? date('Y-m');

		// Validasi parameter
		if (empty($idCs)) {
			echo '<div class="p-4 text-center text-danger">Parameter ID tidak ditemukan.</div>';
			return;
		}

		// Ambil seluruh nilai CS
		$nilaiAll = $this->NilaiModel->getByCustomerService($idCs);

		// Filter berdasarkan periode
		$nilai = array_filter($nilaiAll, fn($r) => ($r->periode ?? '') == $periode);

		if (empty($nilai)) {
			echo '<div class="p-4 text-center text-muted">Belum ada penilaian untuk periode ini.</div>';
			return;
		}

		// Inisialisasi perhitungan
		$rows = [];
		$totalCF = $totalSF = 0;
		$itemCF = 0;
		$itemSF = 0;

		foreach ($nilai as $row) {
			$nilaiAktual = (float) $row->nilai;
			$bobotSub = (float) ($row->bobot_sub ?? 0);

			// Hitung GAP
			$gap = $this->profilematching->hitungGap(
				$row->id_sub_kriteria,
				$nilaiAktual
			);

			$jenis = strtolower(trim($row->jenis_kriteria ?? ''));

			// Akumulasi Core Factor & Secondary Factor
			if ($jenis === 'core_factor') {
				$totalCF += $gap['gap'];
				$itemCF++;
			} else {
				$totalSF += $gap['gap'];
				$itemSF++;
			}

			// Data detail tabel
			$rows[] = [
				'kode_kriteria' => $row->kode_kriteria ?? '-',
				'nama_kriteria' => $row->nama_kriteria ?? '-',
				'nama_sub'      => $row->nama_sub_kriteria ?? '-',
				'nilai_asli'    => $nilaiAktual,
				'nilai_gap'     => $gap['gap'],
				'bobot_sub'     => $bobotSub,
				'jenis'         => $jenis,
			];
		}

		// Hitung NCF, NSF, dan skor akhir
		$ncf = $itemCF > 0 ? ($totalCF / $itemCF) : 0;
		$nsf = $itemSF > 0 ? ($totalSF / $itemSF) : 0;
		$skorAkhir = ($ncf * 0.9) + ($nsf * 0.1);

		// Ambil data CS & tim
		$this->load->model('CustomerServiceModel');
		$csInfo = $this->CustomerServiceModel->getByIdWithDetails($idCs);

		$namaLeader = null;
		if (!empty($csInfo->id_tim)) {
			$timInfo = $this->TimModel->getByIdWithDetails($csInfo->id_tim);
			$namaLeader = $timInfo->nama_leader ?? null;
		}

		// Render view detail
		$this->load->view('admin/ranking/detail', [
			'rows'      => $rows,
			'ncf'       => round($ncf, 4),
			'nsf'       => round($nsf, 4),
			'skor'      => round($skorAkhir, 6),
			'total_cf'  => $totalCF,
			'item_cf'   => $itemCF,
			'total_sf'  => $totalSF,
			'item_sf'   => $itemSF,
			'periode'   => $periode,
			'id_cs'     => $idCs,
			'cs' => (object)[
				'nama_cs'     => $csInfo->nama_cs ?? '-',
				'nik'         => $csInfo->nik ?? '-',
				'nama_tim'    => $csInfo->nama_tim ?? null,
				'nama_produk' => $csInfo->nama_produk ?? null,
				'nama_leader' => $namaLeader,
			],
		]);
	}
}
