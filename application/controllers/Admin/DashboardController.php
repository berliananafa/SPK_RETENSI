<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Dashboard Admin
 * 
 * Bertanggung jawab untuk menampilkan halaman dashboard admin
 * beserta ringkasan data dan peringkat Customer Service.
 */
class DashboardController extends Admin_Controller
{
    /**
     * Constructor
     * 
     * Memanggil constructor parent dan memuat
     * model-model yang dibutuhkan pada dashboard.
     */
    public function __construct()
    {
        parent::__construct();

        // Load model yang digunakan pada dashboard admin
        $this->load->model([
            'CustomerServiceModel' => 'CustomerService',
            'KriteriaModel'        => 'Kriteria',
            'RankingModel'         => 'Ranking',
            'ProdukModel'          => 'Produk',
            'KanalModel'           => 'Kanal',
            'TimModel'             => 'Tim',
            'PenggunaModel'
        ]);
    }

    /**
     * Halaman Dashboard Admin
     * 
     * Menampilkan statistik data master dan
     * ranking Customer Service berdasarkan periode terbaru.
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Dashboard Admin');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Admin']
        ]);

        /**
         * Mengambil data statistik jumlah data
         * dari masing-masing tabel
         */
        $data = $this->PenggunaModel->getAllTablesCount();

        // Mengambil periode ranking terbaru
        $currentPeriode = $this->Ranking->getLatestPeriode();
        $data['current_periode'] = $currentPeriode;

        /**
         * Mengambil data Top Customer Service
         * berdasarkan nilai akhir pada periode terbaru
         */
        if ($currentPeriode) {
            $data['top_cs'] = $this->Ranking->getTopRankingsForDashboard($currentPeriode, 5);
        } else {
            // Jika belum ada periode, data ranking dikosongkan
            $data['top_cs'] = [];
        }

        // Render halaman dashboard admin
        render_layout('admin/dashboard/index', $data);
    }
}
