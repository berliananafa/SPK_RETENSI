<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeamController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['TimModel', 'CustomerServiceModel']);
    }

    public function index()
    {
        set_page_title('Tim & CS');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Tim & CS']
        ]);
        
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        $data['teams'] = $this->TimModel->getBySupervisorWithDetails($userId);
        
        render_layout('supervisor/team/index', $data);
    }

    public function detail($id_tim)
    {
        $userId = $this->session->userdata('user_id');
        $team = $this->TimModel->getTeamBySupervisor($id_tim, $userId);
        
        if (!$team) {
            show_404();
        }
        
        set_page_title('Detail Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Tim & CS', 'url' => base_url('supervisor/team')],
            ['title' => 'Detail Tim']
        ]);
        
        enable_datatables();
        
        $data['team'] = $team;
        $data['cs_list'] = $this->CustomerServiceModel->getByTeamWithStats($id_tim);
        
        render_layout('supervisor/team/detail', $data);
    }
}
