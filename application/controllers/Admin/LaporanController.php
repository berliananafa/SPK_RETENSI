<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['RankingModel', 'NilaiModel']);
    }

    public function index()
    {
        set_page_title('Laporan Performa');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Laporan Performa']
        ]);
        
        enable_charts();
        
        // TODO: Implement performance report
        render_layout('admin/laporan/index');
    }
}
