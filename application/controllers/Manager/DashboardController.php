<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'PenggunaModel', 
            'TimModel', 
            'CustomerServiceModel', 
            'NilaiModel',
            'RankingModel',
            'KriteriaModel'
        ]);
    }

    public function index()
    {
        set_page_title('Dashboard Junior Manager');
        set_breadcrumb([
            ['title' => 'Dashboard']
        ]);
        
        enable_charts();
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        // Statistik (scoped ke junior manager)
        $data['total_supervisor'] = $this->PenggunaModel->countByManager($userId);
        $data['total_teams'] = $this->TimModel->countByManager($userId);
        $data['total_cs'] = $this->CustomerServiceModel->countByManager($userId);
        $data['total_penilaian'] = $this->NilaiModel->countByManager($userId);

        // Statistik global
        $data['total_criteria'] = $this->db->count_all('kriteria');

        // Total ranking (hanya yang dipublish dan di bawah manager ini)
        $data['total_rankings'] = $this->RankingModel->countByManager($userId);

        // Periode terbaru dan top CS untuk manager
        $currentPeriode = $this->RankingModel->getLatestPeriode();
        $data['current_periode'] = $currentPeriode;

        if ($currentPeriode) {
            $data['top_cs'] = $this->RankingModel->getTopCsByManager($userId, $currentPeriode, 5);
        } else {
            $data['top_cs'] = [];
        }

        // Data tambahan
        $data['supervisors'] = $this->PenggunaModel->getSupervisorsWithStats($userId);
        $data['recent_nilai'] = $this->NilaiModel->getRecentByManager($userId, 5);
        $data['cs_performance'] = $this->CustomerServiceModel->getPerformanceStatsByManager($userId);

        render_layout('manager/dashboard/index', $data);
    }
}
