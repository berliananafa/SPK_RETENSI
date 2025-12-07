<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MonitorController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('NilaiModel');
    }

    public function index()
    {
        set_page_title('Monitor Penilaian');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Monitor Penilaian']
        ]);
        
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        $data['nilai_list'] = $this->NilaiModel->getBySupervisor($userId);
        
        render_layout('supervisor/monitor/index', $data);
    }
}
