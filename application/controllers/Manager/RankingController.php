<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankingController extends Manager_Controller
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
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Hasil Ranking']
        ]);
        
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        
        // Get rankings and periods using model methods
        $data['rankings'] = $this->RankingModel->getByManager($userId);
        $data['periods'] = $this->RankingModel->getPeriodsByManager($userId);
        
        render_layout('manager/ranking/index', $data);
    }
}
