<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['PenggunaModel', 'TimModel', 'CustomerServiceModel', 'NilaiModel']);
    }

    public function index()
    {
        set_page_title('Dashboard Supervisor');
        set_breadcrumb([
            ['title' => 'Dashboard']
        ]);
        
        enable_charts();
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        
        // Get statistics
        $data['total_leader'] = $this->PenggunaModel->countLeadersBySupervisor($userId);
        $data['total_tim'] = $this->TimModel->countBySupervisor($userId);
        $data['total_cs'] = $this->CustomerServiceModel->countBySupervisor($userId);
        $data['total_penilaian'] = $this->NilaiModel->countBySupervisor($userId);
        
        // Get teams list with stats
        $data['teams'] = $this->TimModel->getBySupervisorWithDetails($userId);
        
        // Get recent evaluations
        $data['recent_nilai'] = $this->NilaiModel->getRecentBySupervisor($userId, 10);
        
        // Get CS performance statistics
        $data['cs_performance'] = $this->CustomerServiceModel->getPerformanceStatsBySupervisor($userId);
        
        render_layout('supervisor/dashboard/index', $data);
    }
}
