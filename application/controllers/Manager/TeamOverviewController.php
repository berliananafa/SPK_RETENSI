<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Team Overview Controller for Junior Manager
 * Similar to TeamController for Supervisor but with Manager scope
 */
class TeamOverviewController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['TimModel', 'CustomerServiceModel', 'RankingModel']);
    }

    public function index()
    {
        set_page_title('Overview Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Overview Tim']
        ]);

        enable_datatables();

        $userId = $this->session->userdata('user_id');

        // Get all teams under this manager's supervisors (optimized - use model)
        $data['teams'] = $this->TimModel->getByJuniorManager($userId);

        render_layout('manager/team_overview/index', $data);
    }

    public function detail($id_tim)
    {
        set_page_title('Detail Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Overview Tim', 'url' => base_url('junior-manager/team-overview')],
            ['title' => 'Detail Tim']
        ]);

        enable_datatables();

        $userId = $this->session->userdata('user_id');

        // Verify team belongs to this manager
        $team = $this->TimModel->getTeamByManager($id_tim, $userId);

        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan atau Anda tidak memiliki akses.');
            redirect('junior-manager/team-overview');
            return;
        }

        // Get latest periode for this manager
        $latestPeriodeObj = $this->RankingModel->getLatestPeriodeByManager($userId);

        $data['team'] = $team;
        $data['cs_list'] = $this->CustomerServiceModel->getByTeamWithStats($id_tim);
        $data['selected_periode'] = $latestPeriodeObj->periode ?? null;
        $data['rankings_by_cs'] = [];

        // Get ranking data for each CS in this team
        if (!empty($data['selected_periode'])) {
            $rankings = $this->RankingModel->getByPeriodeByManager(
                $data['selected_periode'],
                $userId,
                ['id_tim' => $id_tim]
            );

            foreach ($rankings as $r) {
                $data['rankings_by_cs'][$r->id_cs] = $r;
            }
        }

        render_layout('manager/team_overview/detail', $data);
    }
}
