<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeamController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['TimModel', 'CustomerServiceModel', 'RankingModel']);
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
        set_page_title('Detail Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
            ['title' => 'Tim & CS', 'url' => base_url('supervisor/team')],
            ['title' => 'Detail Tim']
        ]);
        enable_datatables();

		$userId = $this->session->userdata('user_id');
        $team = $this->TimModel->getTeamBySupervisor($id_tim, $userId);
        $latestPeriodeObj = $this->RankingModel->getLatestPeriodeBySupervisor($userId);

        if (!$team) {
            show_404();
        }
        
        $data['team'] = $team;
        $data['cs_list'] = $this->CustomerServiceModel->getByTeamWithStats($id_tim);
        $data['selected_periode'] = $latestPeriodeObj->periode ?? null;
        $data['rankings_by_cs'] = [];

        if (!empty($data['selected_periode'])) {
            $rankings = $this->RankingModel->getByPeriodeBySupervisor($data['selected_periode'], $userId, ['id_tim' => $id_tim]);
            foreach ($rankings as $r) {
                $data['rankings_by_cs'][$r->id_cs] = $r;
            }
        }

        render_layout('supervisor/team/detail', $data);
    }
}
