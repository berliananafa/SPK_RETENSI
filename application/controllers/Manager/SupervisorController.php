<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupervisorController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['PenggunaModel', 'TimModel', 'CustomerServiceModel', 'SupervisorScopeModel']);
    }

    public function index()
    {
        set_page_title('Supervisor');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Supervisor']
        ]);
        
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        
        // Get supervisors with stats using model
        $supervisors = $this->PenggunaModel->getSupervisorsWithStats($userId);
        
        // Add scope information for each supervisor
        foreach ($supervisors as $supervisor) {
            $supervisor->scopes = $this->SupervisorScopeModel->getBySupervisor($supervisor->id_user);
        }
        
        $data['supervisors'] = $supervisors;
        
        render_layout('manager/supervisor/index', $data);
    }

    public function detail($id)
    {
        $userId = $this->session->userdata('user_id');
        
        // Verify supervisor belongs to this manager using model
        $supervisor = $this->PenggunaModel->getSupervisorByManager($id, $userId);
        
        if (!$supervisor) {
            show_error('Supervisor tidak ditemukan atau bukan bagian dari tim Anda', 404);
            return;
        }
        
        set_page_title('Detail Supervisor - ' . $supervisor->nama_pengguna);
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Supervisor', 'url' => base_url('junior-manager/supervisor')],
            ['title' => 'Detail']
        ]);
        
        enable_datatables();
        
        $data['supervisor'] = $supervisor;
        $data['scopes'] = $this->SupervisorScopeModel->getBySupervisor($id);
        $data['teams'] = $this->TimModel->getBySupervisorWithDetails($id);
        $data['cs_list'] = $this->CustomerServiceModel->getBySupervisor($id);
        
        render_layout('manager/supervisor/detail', $data);
    }
}
