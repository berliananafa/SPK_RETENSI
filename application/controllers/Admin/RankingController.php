<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankingController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RankingModel');
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
        
        // TODO: Implement ranking view
        render_layout('admin/ranking/index');
    }

    public function export()
    {
        set_page_title('Export Data Ranking');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Export Data']
        ]);
        
        // TODO: Implement export ranking
        render_layout('admin/ranking/export');
    }

    public function download()
    {
        // TODO: Implement download ranking
        redirect('admin/ranking');
    }
}
