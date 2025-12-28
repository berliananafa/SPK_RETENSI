<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanController extends Leader_Controller
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
            ['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
            ['title' => 'Laporan Performa']
        ]);

        enable_charts();
        enable_datatables();

        $userId = $this->session->userdata('user_id');

        // Get tim yang dipimpin leader
        $teams = $this->TimModel->getByLeader($userId);
        $team = !empty($teams) ? $teams[0] : null;

        // Ambil filter dari query string
        $periode = $this->input->get('periode') ?? date('Y-m');
        $idProduk = $this->input->get('id_produk') ?: null;

        // Data untuk view
        $data = [
            'team' => $team,
            'filter_periode' => $periode,
            'filter_produk' => $idProduk,
            'produk' => $this->ProdukModel->all(),
            'statistik' => ['total_cs' => 0, 'avg_skor' => 0, 'excellent' => 0, 'poor' => 0],
            'chart_kategori' => ['excellent' => 0, 'good' => 0, 'average' => 0, 'poor' => 0],
            'chart_kriteria' => ['labels' => [], 'data' => []],
            'top_performers' => [],
            'bottom_performers' => []
        ];

        if ($team) {
            // Hitung statistik dengan scope tim leader
            $data['statistik'] = $this->hitungStatistik($periode, $team->id_tim, $idProduk);

            // Data chart
            $data['chart_kategori'] = $this->getDataKategori($periode, $team->id_tim, $idProduk);
            $data['chart_kriteria'] = $this->getDataKriteria($periode, $team->id_tim, $idProduk);

            // Top & Bottom performers
            $data['top_performers'] = $this->getTopPerformers($periode, $team->id_tim, $idProduk, 5);
            $data['bottom_performers'] = $this->getBottomPerformers($periode, $team->id_tim, $idProduk, 5);
        }

        render_layout('leader/laporan/index', $data);
    }

    /**
     * Hitung statistik ringkasan dengan team scope
     */
    private function hitungStatistik($periode, $teamId, $idProduk = null)
    {
        $this->db->select('
            COUNT(DISTINCT ranking.id_cs) as total_cs,
            ROUND(AVG(ranking.nilai_akhir), 2) as avg_skor,
            SUM(CASE WHEN ranking.nilai_akhir >= 4 THEN 1 ELSE 0 END) as excellent,
            SUM(CASE WHEN ranking.nilai_akhir < 2.5 THEN 1 ELSE 0 END) as poor
        ')
            ->from('ranking')
            ->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
            ->where('ranking.periode', $periode)
            ->where('cs.id_tim', $teamId)
            ->where_in('ranking.status', ['pending_leader', 'pending_supervisor', 'published']);

        // Filter produk
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
     * Data chart kategori (Doughnut) dengan team scope
     */
    private function getDataKategori($periode, $teamId, $idProduk = null)
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
            ->where('ranking.periode', $periode)
            ->where('cs.id_tim', $teamId)
            ->where_in('ranking.status', ['pending_leader', 'pending_supervisor', 'published']);

        if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

        $result = $this->db->get()->row();
        $total = (int)($result->total ?? 1);

        return [
            'excellent' => round((($result->excellent ?? 0) / $total) * 100, 1),
            'good' => round((($result->good ?? 0) / $total) * 100, 1),
            'average' => round((($result->average ?? 0) / $total) * 100, 1),
            'poor' => round((($result->poor ?? 0) / $total) * 100, 1),
        ];
    }

    /**
     * Data chart per kriteria (Bar) dengan team scope
     */
    private function getDataKriteria($periode, $teamId, $idProduk = null)
    {
        $filter = ['periode' => $periode, 'id_tim' => $teamId];
        if ($idProduk) $filter['id_produk'] = $idProduk;

        $nilaiRows = $this->NilaiModel->getAllWithDetails($filter);

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
     * Top performers dengan team scope
     */
    private function getTopPerformers($periode, $teamId, $idProduk = null, $limit = 5)
    {
        $this->db->select('ranking.*, cs.nama_cs, cs.nik, produk.nama_produk, tim.nama_tim, ranking.nilai_akhir as avg_skor')
            ->from('ranking')
            ->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
            ->join('produk produk', 'cs.id_produk = produk.id_produk', 'left')
            ->join('tim tim', 'cs.id_tim = tim.id_tim', 'left')
            ->where('ranking.periode', $periode)
            ->where('cs.id_tim', $teamId)
            ->where_in('ranking.status', ['pending_leader', 'pending_supervisor', 'published']);

        if ($idProduk) $this->db->where('cs.id_produk', $idProduk);

        return $this->db
            ->order_by('ranking.nilai_akhir', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Bottom performers dengan team scope
     */
    private function getBottomPerformers($periode, $teamId, $idProduk = null, $limit = 5)
    {
        $this->db->select('ranking.*, cs.nama_cs, cs.nik, produk.nama_produk, tim.nama_tim, ranking.nilai_akhir as avg_skor')
            ->from('ranking')
            ->join('customer_service cs', 'ranking.id_cs = cs.id_cs', 'left')
            ->join('produk produk', 'cs.id_produk = produk.id_produk', 'left')
            ->join('tim tim', 'cs.id_tim = tim.id_tim', 'left')
            ->where('ranking.periode', $periode)
            ->where('cs.id_tim', $teamId)
            ->where_in('ranking.status', ['pending_leader', 'pending_supervisor', 'published']);

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
        $userId = $this->session->userdata('user_id');

        // Get tim leader
        $teams = $this->TimModel->getByLeader($userId);
        $team = !empty($teams) ? $teams[0] : null;

        if (!$team) {
            $this->session->set_flashdata('error', 'Anda belum memimpin tim.');
            redirect('leader/laporan');
        }

        $periode = $this->input->get('periode') ?? date('Y-m');
        $idProduk = $this->input->get('id_produk') ?: null;

        // Load library
        $this->load->library('ExportLaporan');

        // Siapkan data
        $summary = $this->hitungStatistik($periode, $team->id_tim, $idProduk);
        $summary['periode'] = $periode;
        $summary['team'] = $team->nama_tim;

        $top = $this->getTopPerformers($periode, $team->id_tim, $idProduk, 10);
        $bottom = $this->getBottomPerformers($periode, $team->id_tim, $idProduk, 10);
        $kriteria = $this->getDataKriteria($periode, $team->id_tim, $idProduk);

        // Export
        $filename = 'Laporan_Performa_' . $team->nama_tim . '_' . $periode . '_' . date('YmdHis') . '.xlsx';
        $this->exportlaporan->exportExcel($summary, $top, $bottom, $kriteria, $filename);
    }

    // alias for route with hyphen or snake_case
    public function export_excel()
    {
        return $this->exportExcel();
    }
}
