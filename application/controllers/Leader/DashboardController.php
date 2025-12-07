<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Leader_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['CustomerServiceModel', 'RankingModel']);
    }

    public function index()
    {
        set_page_title('Dashboard Leader');
        set_breadcrumb([
            ['title' => 'Dashboard']
        ]);
        
        enable_charts();
        
        // TODO: Implement leader dashboard
        render_layout('leader/dashboard/index');
    }
}
