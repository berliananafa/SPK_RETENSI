<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankingController extends Leader_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RankingModel');
    }

    public function index()
    {
        set_page_title('Ranking Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
            ['title' => 'Ranking Tim']
        ]);
        
        enable_datatables();
        enable_charts();
        
        // TODO: Implement team ranking view for leader
        render_layout('leader/ranking/index');
    }
}
