<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['PenggunaModel', 'TimModel', 'CustomerServiceModel', 'NilaiModel']);
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
        
        // Get statistics using model methods
        $data['total_supervisor'] = $this->PenggunaModel->countByManager($userId);
        $data['total_tim'] = $this->TimModel->countByManager($userId);
        $data['total_cs'] = $this->CustomerServiceModel->countByManager($userId);
        $data['total_penilaian'] = $this->NilaiModel->countByManager($userId);
        
        // Get supervisor list with stats
        $data['supervisors'] = $this->PenggunaModel->getSupervisorsWithStats($userId);
        
        // Get recent evaluations
        $data['recent_nilai'] = $this->NilaiModel->getRecentByManager($userId, 10);
        
        // Get CS performance statistics
        $data['cs_performance'] = $this->CustomerServiceModel->getPerformanceStatsByManager($userId);
        
        render_layout('manager/dashboard/index', $data);
    }
}
