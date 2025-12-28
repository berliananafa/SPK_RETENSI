<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Leader_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'TimModel',
            'CustomerServiceModel',
            'NilaiModel',
            'RankingModel'
        ]);
    }

    public function index()
    {
        set_page_title('Dashboard Leader');
        set_breadcrumb([
            ['title' => 'Dashboard']
        ]);

        enable_charts();
        enable_datatables();

        $userId = $this->session->userdata('user_id');

        // Get tim yang dipimpin leader
        $teams = $this->TimModel->getByLeader($userId);
        $team = !empty($teams) ? $teams[0] : null;

        // Default values
        $data['team'] = $team;
        $data['total_cs'] = 0;
        $data['total_penilaian'] = 0;
        $data['total_rankings'] = 0;
        $data['pending_approvals'] = 0;
        $data['current_periode'] = null;
        $data['top_cs'] = [];
        $data['team_members'] = [];
        $data['recent_nilai'] = [];

        if ($team) {
            // Get all statistics in one query
            $stats = $this->TimModel->getLeaderDashboardStats($team->id_tim);

            $data['total_cs'] = $stats->total_cs ?? 0;
            $data['total_penilaian'] = $stats->total_penilaian ?? 0;
            $data['total_rankings'] = $stats->total_rankings ?? 0;
            $data['pending_approvals'] = $stats->pending_approvals ?? 0;
            $data['current_periode'] = $stats->current_periode ?? null;

            // Top 5 CS untuk periode terbaru
            if ($data['current_periode']) {
                $data['top_cs'] = $this->RankingModel->getTopCsByTeam($team->id_tim, $data['current_periode'], 5);
            }

            // Anggota tim dengan statistik
            $data['team_members'] = $this->CustomerServiceModel->getByTeamWithStats($team->id_tim);

            // 5 penilaian terbaru untuk tim
            $data['recent_nilai'] = $this->NilaiModel->getRecentNilaiByTeam($team->id_tim, 5);
        }

        render_layout('leader/dashboard/index', $data);
    }
}
