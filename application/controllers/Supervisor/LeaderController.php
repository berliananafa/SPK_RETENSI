<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeaderController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'PenggunaModel',
            'CustomerServiceModel'
        ]);
    }

    public function index()
    {
        set_page_title('Leader');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Leader']
        ]);
        enable_datatables();

        $userId = $this->session->userdata('user_id');
        $data['leaders'] = $this->PenggunaModel->getLeadersBySupervisor($userId);

        render_layout('supervisor/leader/index', $data);
    }

    public function detail($id_leader)
    {
        $userId = $this->session->userdata('user_id');

        // Get leader with verification using model method
        $leader = $this->PenggunaModel->getLeaderByIdAndSupervisor($id_leader, $userId);

        if (!$leader) {
            show_404();
        }

        set_page_title('Detail Leader');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Leader', 'url' => base_url('supervisor/leader')],
            ['title' => 'Detail Leader']
        ]);

        enable_datatables();

        $data['leader'] = $leader;
        $data['cs_list'] = $this->CustomerServiceModel->getByTeamWithStats($leader->id_tim);

        render_layout('supervisor/leader/detail', $data);
    }
}
