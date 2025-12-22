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

        // Get current periode
        $currentPeriode = $this->Ranking->getLatestPeriode();
        $data['current_periode'] = $currentPeriode;

        // Get top 10 CS ranking
        if ($currentPeriode) {
            $data['top_cs'] = $this->db
                ->select('cs.nama_cs, cs.nik, r.nilai_akhir, r.peringkat, t.nama_tim')
                ->from('ranking r')
                ->join('customer_service cs', 'cs.id_cs = r.id_cs', 'left')
                ->join('tim t', 'cs.id_tim = t.id_tim', 'left')
                ->where('r.periode', $currentPeriode)
                ->order_by('r.nilai_akhir', 'DESC')
                ->limit(5)
                ->get()
                ->result();
        } else {
            $data['top_cs'] = [];
        }

        render_layout('admin/dashboard/index', $data);
    }
}
