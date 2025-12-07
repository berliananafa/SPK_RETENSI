<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'CustomerServiceModel' => 'CustomerService',
            'KriteriaModel' => 'Kriteria',
            'RankingModel' => 'Ranking',
            'ProdukModel' => 'Produk',
            'KanalModel' => 'Kanal',
            'TimModel' => 'Tim'
        ]);
    }

    /**
     * Halaman dashboard admin
     */
    public function index()
    {
        set_page_title('Dashboard Admin');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Admin']
        ]);

        // Get statistics data
        $data = [
            'total_users'        => $this->db->count_all('pengguna'),
            'total_cs'           => $this->db->count_all('customer_service'),
            'total_criteria'     => $this->db->count_all('kriteria'),
            'total_rankings'     => $this->db->count_all('ranking'),
            'total_produk'       => $this->db->count_all('produk'),
            'total_kanal'        => $this->db->count_all('kanal'),
            'total_teams'        => $this->db->count_all('tim'),
        ];

        // Get top 10 CS ranking data for chart
        $data['top_cs'] = $this->db->select('cs.nama_cs, r.nilai_akhir, r.periode')
            ->from('ranking r')
            ->join('customer_service cs', 'cs.id_cs = r.id_cs')
            ->order_by('r.nilai_akhir', 'DESC')
            ->limit(10)
            ->get()
            ->result();

        // Enable charts
        enable_charts();

        render_layout('admin/dashboard/index', $data);
    }

    /**
     * Halaman statistik untuk admin
     */
    public function statistics()
    {
        set_page_title('Statistik Sistem');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Statistik']
        ]);

        enable_charts();
        
        render_layout('admin/dashboard/statistics');
    }
}
