<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laporan Controller for Junior Manager
 * Read-only performance reports with manager scope
 */
class LaporanController extends Manager_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['RankingModel', 'NilaiModel', 'TimModel', 'ProdukModel']);
	}

	public function index()
	{
		set_page_title('Laporan Performa');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
			['title' => 'Laporan Performa']
		]);

		enable_charts();
		enable_datatables();

		$managerId = $this->session->userdata('user_id');

		// Ambil filter dari query string
		$periode = $this->input->get('periode') ?? date('Y-m');
		$idTim = $this->input->get('id_tim') ?: null;
		$idProduk = $this->input->get('id_produk') ?: null;

		// Data untuk view - hanya tim di bawah manager ini
		$data = [
			'filter_periode' => $periode,
			'filter_tim' => $idTim,
			'filter_produk' => $idProduk,
			'tim' => $this->TimModel->getByJuniorManager($managerId),
			'produk' => $this->ProdukModel->all(),
		];

		// Hitung statistik dengan scope Junior Manager
		$data['statistik'] = $this->hitungStatistik($periode, $managerId, $idTim, $idProduk);

		// Data chart
		$data['chart_kategori'] = $this->getDataKategori($periode, $managerId, $idTim, $idProduk);
		$data['chart_kriteria'] = $this->getDataKriteria($periode, $managerId, $idTim, $idProduk);

		// Top & Bottom performers
		$data['top_performers'] = $this->getTopPerformers($periode, $managerId, $idTim, $idProduk, 5);
		$data['bottom_performers'] = $this->getBottomPerformers($periode, $managerId, $idTim, $idProduk, 5);

		render_layout('manager/laporan/index', $data);
	}

	/**
	 * Hitung statistik ringkasan dengan Junior Manager scope
	 */
	private function hitungStatistik($periode, $managerId, $idTim = null, $idProduk = null)
	{
		$this->db->select('
            COUNT(DISTINCT ranking.id_cs) as total_cs,
            ROUND(AVG(ranking.nilai_akhir), 2) as avg_skor,
            SUM(CASE WHEN ranking.nilai_akhir >= 4 THEN 1 ELSE 0 END) as excellent,
            SUM(CASE WHEN ranking.nilai_akhir < 2.5 THEN 1 ELSE 0 END) as poor
        ')
			->from('ranking')
			->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
			->join('tim t', 'cs.id_tim = t.id_tim', 'left')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user', 'left')
			->where('ranking.periode', $periode)
			->where('supervisor.id_atasan', $managerId);

		// Filter tim & produk
		if ($idTim) $this->db->where('cs.id_tim', $idTim);
		if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

		$result = $this->db->get()->row();

		return [
			'total_cs' => (int)($result->total_cs ?? 0),
			'avg_skor' => (float)($result->avg_skor ?? 0),
			'excellent' => (int)($result->excellent ?? 0),
			'poor' => (int)($result->poor ?? 0),
		];
	}

	/**
	 * Data chart kategori (Doughnut) dengan Junior Manager scope
	 */
	private function getDataKategori($periode, $managerId, $idTim = null, $idProduk = null)
	{
		$this->db->select('
            SUM(CASE WHEN ranking.nilai_akhir >= 4 THEN 1 ELSE 0 END) as excellent,
            SUM(CASE WHEN ranking.nilai_akhir >= 3 AND ranking.nilai_akhir < 4 THEN 1 ELSE 0 END) as good,
            SUM(CASE WHEN ranking.nilai_akhir >= 2.5 AND ranking.nilai_akhir < 3 THEN 1 ELSE 0 END) as average,
            SUM(CASE WHEN ranking.nilai_akhir < 2.5 THEN 1 ELSE 0 END) as poor,
            COUNT(*) as total
        ')
			->from('ranking')
			->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
			->join('tim t', 'cs.id_tim = t.id_tim', 'left')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user', 'left')
			->where('ranking.periode', $periode)
			->where('supervisor.id_atasan', $managerId);

		if ($idTim) $this->db->where('cs.id_tim', $idTim);
		if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

		$result = $this->db->get()->row();
		$total = (int)($result->total ?? 0);

		if ($total === 0) {
			return [
				'excellent' => 0,
				'good'      => 0,
				'average'   => 0,
				'poor'      => 0,
			];
		}

		return [
			'excellent' => round((($result->excellent ?? 0) / $total) * 100, 1),
			'good' => round((($result->good ?? 0) / $total) * 100, 1),
			'average' => round((($result->average ?? 0) / $total) * 100, 1),
			'poor' => round((($result->poor ?? 0) / $total) * 100, 1),
		];
	}

	/**
	 * Data chart per kriteria (Bar) dengan Junior Manager scope
	 */
	private function getDataKriteria($periode, $managerId, $idTim = null, $idProduk = null)
	{
		$filter = ['periode' => $periode, 'manager_id' => $managerId];
		if ($idTim) $filter['id_tim'] = $idTim;
		if ($idProduk) $filter['id_produk'] = $idProduk;

		$nilaiRows = $this->NilaiModel->getNilaiWithDetailsByManager($managerId, $filter);

		$byKriteria = [];
		foreach ($nilaiRows as $n) {
			$kid = $n->id_kriteria;
			if (!isset($byKriteria[$kid])) {
				$byKriteria[$kid] = [
					'nama' => $n->nama_kriteria,
					'total' => 0,
					'count' => 0
				];
			}
			$byKriteria[$kid]['total'] += (float)($n->nilai ?? 0);
			$byKriteria[$kid]['count']++;
		}

		$labels = [];
		$data = [];
		foreach ($byKriteria as $k) {
			$labels[] = $k['nama'];
			$data[] = $k['count'] > 0 ? round($k['total'] / $k['count'], 2) : 0;
		}

		return [
			'labels' => $labels,
			'data' => $data
		];
	}

	/**
	 * Top performers dengan Junior Manager scope
	 */
	private function getTopPerformers($periode, $managerId, $idTim = null, $idProduk = null, $limit = 5)
	{
		$this->db->select('ranking.*, cs.nama_cs, cs.nik, produk.nama_produk, t.nama_tim, ranking.nilai_akhir as avg_skor')
			->from('ranking')
			->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
			->join('produk produk', 'cs.id_produk = produk.id_produk', 'left')
			->join('tim t', 'cs.id_tim = t.id_tim', 'left')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user', 'left')
			->where('ranking.periode', $periode)
			->where('supervisor.id_atasan', $managerId);

		if ($idTim) $this->db->where('cs.id_tim', $idTim);
		if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

		return $this->db
			->order_by('ranking.nilai_akhir', 'DESC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Bottom performers dengan Junior Manager scope
	 */
	private function getBottomPerformers($periode, $managerId, $idTim = null, $idProduk = null, $limit = 5)
	{
		$this->db->select('ranking.*, cs.nama_cs, cs.nik, produk.nama_produk, t.nama_tim, ranking.nilai_akhir as avg_skor')
			->from('ranking')
			->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
			->join('produk produk', 'cs.id_produk = produk.id_produk', 'left')
			->join('tim t', 'cs.id_tim = t.id_tim', 'left')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user', 'left')
			->where('ranking.periode', $periode)
			->where('supervisor.id_atasan', $managerId);

		if ($idTim) $this->db->where('cs.id_tim', $idTim);
		if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

		return $this->db
			->order_by('ranking.nilai_akhir', 'ASC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Export ke Excel
	 */
	public function exportExcel()
	{
		$managerId = $this->session->userdata('user_id');
		$periode = $this->input->get('periode') ?? date('Y-m');
		$idTim = $this->input->get('id_tim') ?: null;
		$idProduk = $this->input->get('id_produk') ?: null;

		// Load library
		$this->load->library('ExportLaporan');

		// Siapkan data
		$summary = $this->hitungStatistik($periode, $managerId, $idTim, $idProduk);
		$summary['periode'] = $periode;

		$top = $this->getTopPerformers($periode, $managerId, $idTim, $idProduk, 10);
		$bottom = $this->getBottomPerformers($periode, $managerId, $idTim, $idProduk, 10);
		$kriteria = $this->getDataKriteria($periode, $managerId, $idTim, $idProduk);

		// Export
		$filename = 'Laporan_Performa_JM_' . $periode . '_' . date('YmdHis') . '.xlsx';
		$this->exportlaporan->exportExcel($summary, $top, $bottom, $kriteria, $filename);
	}

	// alias for route with hyphen or snake_case
	public function export_excel()
	{
		return $this->exportExcel();
	}
}
