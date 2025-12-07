<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankingController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['RankingModel', 'ProdukModel', 'KanalModel', 'TimModel']);
    }

    public function index()
    {
        set_page_title('Hasil Ranking');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Hasil Ranking']
        ]);
        
        enable_datatables();
        enable_charts();
        
        $userId = $this->session->userdata('user_id');
        
        // Get latest periode
        $latestPeriode = $this->RankingModel->getLatestPeriodeBySupervisor($userId);
        $selectedPeriode = $this->input->get('periode') ?: ($latestPeriode->periode ?? null);
        
        // Get filter options
        $data['periodes'] = $this->RankingModel->getPeriodsBySupervisor($userId);
        $data['produks'] = $this->ProdukModel->getAll();
        $data['kanals'] = $this->KanalModel->getAll();
        $data['teams'] = $this->TimModel->getBySupervisorWithDetails($userId);
        
        // Get rankings with filters
        $filter = [];
        if ($this->input->get('id_produk')) {
            $filter['id_produk'] = $this->input->get('id_produk');
        }
        if ($this->input->get('id_kanal')) {
            $filter['id_kanal'] = $this->input->get('id_kanal');
        }
        if ($this->input->get('id_tim')) {
            $filter['id_tim'] = $this->input->get('id_tim');
        }
        
        $data['selected_periode'] = $selectedPeriode;
        $data['rankings'] = [];
        
        if ($selectedPeriode) {
            $data['rankings'] = $this->RankingModel->getByPeriodeBySupervisor($selectedPeriode, $userId, $filter);
        }
        
        render_layout('supervisor/ranking/index', $data);
    }
}
