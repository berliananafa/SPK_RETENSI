<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * LaporanController
 * --------------------------------------------------
 * Controller untuk menangani halaman laporan performa
 * customer service berdasarkan hasil ranking.
 * 
 * Fitur utama:
 * - Menampilkan ringkasan statistik performa
 * - Menampilkan grafik kategori & kriteria
 * - Menampilkan top & bottom performer
 * - Export laporan ke Excel
 */
class LaporanController extends Admin_Controller
{
	/**
	 * Constructor
	 * - Memanggil parent controller
	 * - Load model yang dibutuhkan untuk laporan
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['RankingModel', 'NilaiModel', 'TimModel', 'ProdukModel']);
	}

	/**
	 * Halaman utama laporan performa
	 * - Menampilkan filter
	 * - Statistik ringkasan
	 * - Grafik
	 * - Top & bottom performer
	 */
	public function index()
	{
		// Set judul halaman & breadcrumb
		set_page_title('Laporan Performa');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Laporan Performa']
		]);

		// Aktifkan library chart
		enable_charts();

		// Ambil filter dari query string (GET)
		$periode   = $this->input->get('periode') ?? date('Y-m');
		$idTim     = $this->input->get('id_tim') ?: null;
		$idProduk  = $this->input->get('id_produk') ?: null;

		// Data awal yang dikirim ke view
		$data = [
			'filter_periode' => $periode,
			'filter_tim'     => $idTim,
			'filter_produk'  => $idProduk,
			'tim'            => $this->TimModel->all(),
			'produk'         => $this->ProdukModel->all(),
		];

		// Hitung statistik ringkasan
		$data['statistik'] = $this->hitungStatistik($periode, $idTim, $idProduk);

		// Data untuk chart
		$data['chart_kategori'] = $this->getDataKategori($periode, $idTim, $idProduk);
		$data['chart_kriteria'] = $this->getDataKriteria($periode, $idTim, $idProduk);

		// Ambil top & bottom performer
		$data['top_performers']    = $this->getTopPerformers($periode, $idTim, $idProduk, 5);
		$data['bottom_performers'] = $this->getBottomPerformers($periode, $idTim, $idProduk, 5);

		// Render halaman laporan
		render_layout('admin/laporan/index', $data);
	}

	/**
	 * Hitung statistik ringkasan laporan
	 * - Total CS
	 * - Rata-rata skor
	 * - Jumlah CS kategori excellent
	 * - Jumlah CS kategori poor
	 */
	private function hitungStatistik($periode, $idTim = null, $idProduk = null)
	{
		$this->db->select('
            COUNT(DISTINCT ranking.id_cs) as total_cs,
            ROUND(AVG(ranking.nilai_akhir), 2) as avg_skor,
            SUM(CASE WHEN ranking.nilai_akhir >= 4 THEN 1 ELSE 0 END) as excellent,
            SUM(CASE WHEN ranking.nilai_akhir < 2.5 THEN 1 ELSE 0 END) as poor
        ')
			->from('ranking')
			->where('ranking.periode', $periode);

		// Tambahkan filter tim dan produk jika ada
		if ($idTim || $idProduk) {
			$this->db->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left');
			if ($idTim)    $this->db->where('cs.id_tim', $idTim);
			if ($idProduk) $this->db->where('cs.id_produk', $idProduk);
		}

		$result = $this->db->get()->row();

		return [
			'total_cs'  => (int)($result->total_cs ?? 0),
			'avg_skor'  => (float)($result->avg_skor ?? 0),
			'excellent' => (int)($result->excellent ?? 0),
			'poor'      => (int)($result->poor ?? 0),
		];
	}

	/**
	 * Data chart kategori performa (Doughnut Chart)
	 * - Excellent
	 * - Good
	 * - Average
	 * - Poor
	 * 
	 * Output berupa persentase (%)
	 */
	private function getDataKategori($periode, $idTim = null, $idProduk = null)
	{
		$this->db->select('
            SUM(CASE WHEN nilai_akhir >= 4 THEN 1 ELSE 0 END) as excellent,
            SUM(CASE WHEN nilai_akhir >= 3 AND nilai_akhir < 4 THEN 1 ELSE 0 END) as good,
            SUM(CASE WHEN nilai_akhir >= 2.5 AND nilai_akhir < 3 THEN 1 ELSE 0 END) as average,
            SUM(CASE WHEN nilai_akhir < 2.5 THEN 1 ELSE 0 END) as poor,
            COUNT(*) as total
        ')->from('ranking')->where('periode', $periode);

		// Filter tim & produk
		if ($idTim || $idProduk) {
			$this->db->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left');
			if ($idTim)    $this->db->where('cs.id_tim', $idTim);
			if ($idProduk) $this->db->where('cs.id_produk', $idProduk);
		}

		$result = $this->db->get()->row();
		$total  = (int)($result->total ?? 1); // hindari pembagian 0

		return [
			'excellent' => round((($result->excellent ?? 0) / $total) * 100, 1),
			'good'      => round((($result->good ?? 0) / $total) * 100, 1),
			'average'   => round((($result->average ?? 0) / $total) * 100, 1),
			'poor'      => round((($result->poor ?? 0) / $total) * 100, 1),
		];
	}

	/**
	 * Data chart per kriteria (Bar Chart)
	 * Menghitung rata-rata nilai tiap kriteria
	 */
	private function getDataKriteria($periode, $idTim = null, $idProduk = null)
	{
		// Filter untuk model Nilai
		$filter = ['periode' => $periode];
		if ($idTim)    $filter['id_tim'] = $idTim;
		if ($idProduk) $filter['id_produk'] = $idProduk;

		$nilaiRows = $this->NilaiModel->getAllWithDetails($filter);

		// Kelompokkan nilai berdasarkan kriteria
		$byKriteria = [];
		foreach ($nilaiRows as $n) {
			$kid = $n->id_kriteria;
			if (!isset($byKriteria[$kid])) {
				$byKriteria[$kid] = [
					'nama'  => $n->nama_kriteria,
					'total' => 0,
					'count' => 0
				];
			}
			$byKriteria[$kid]['total'] += (float)($n->nilai ?? 0);
			$byKriteria[$kid]['count']++;
		}

		// Siapkan data chart
		$labels = [];
		$data   = [];
		foreach ($byKriteria as $k) {
			$labels[] = $k['nama'];
			$data[]   = $k['count'] > 0 ? round($k['total'] / $k['count'], 2) : 0;
		}

		return [
			'labels' => $labels,
			'data'   => $data
		];
	}

	/**
	 * Ambil data top performer
	 * Diurutkan berdasarkan nilai tertinggi
	 */
	private function getTopPerformers($periode, $idTim = null, $idProduk = null, $limit = 5)
	{
		$this->db->select('ranking.*, cs.nama_cs, cs.nik, produk.nama_produk, t.nama_tim, ranking.nilai_akhir as avg_skor')
			->from('ranking')
			->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
			->join('produk produk', 'cs.id_produk = produk.id_produk', 'left')
			->join('tim t', 'cs.id_tim = t.id_tim', 'left')
			->where('ranking.periode', $periode);

		if ($idTim)    $this->db->where('cs.id_tim', $idTim);
		if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

		return $this->db
			->order_by('ranking.nilai_akhir', 'DESC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Ambil data bottom performer
	 * Diurutkan berdasarkan nilai terendah
	 */
	private function getBottomPerformers($periode, $idTim = null, $idProduk = null, $limit = 5)
	{
		$this->db->select('ranking.*, cs.nama_cs, cs.nik, produk.nama_produk, t.nama_tim, ranking.nilai_akhir as avg_skor')
			->from('ranking')
			->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
			->join('produk produk', 'cs.id_produk = produk.id_produk', 'left')
			->join('tim t', 'cs.id_tim = t.id_tim', 'left')
			->where('ranking.periode', $periode);

		if ($idTim)    $this->db->where('cs.id_tim', $idTim);
		if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

		return $this->db
			->order_by('ranking.nilai_akhir', 'ASC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Export laporan performa ke Excel
	 * Menggunakan library ExportLaporan
	 */
	public function exportExcel()
	{
		$periode  = $this->input->get('periode') ?? date('Y-m');
		$idTim    = $this->input->get('id_tim') ?: null;
		$idProduk = $this->input->get('id_produk') ?: null;

		// Load library export
		$this->load->library('ExportLaporan');

		// Data ringkasan
		$summary = $this->hitungStatistik($periode, $idTim, $idProduk);
		$summary['periode'] = $periode;

		// Data detail
		$top      = $this->getTopPerformers($periode, $idTim, $idProduk, 10);
		$bottom   = $this->getBottomPerformers($periode, $idTim, $idProduk, 10);
		$kriteria = $this->getDataKriteria($periode, $idTim, $idProduk);

		// Nama file export
		$filename = 'Laporan_Performa_' . $periode . '_' . date('YmdHis') . '.xlsx';

		// Proses export
		$this->exportlaporan->exportExcel($summary, $top, $bottom, $kriteria, $filename);
	}

	/**
	 * Alias method untuk kebutuhan routing
	 */
	public function export_excel()
	{
		return $this->exportExcel();
	}
}
