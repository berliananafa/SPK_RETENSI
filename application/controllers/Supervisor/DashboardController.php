<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends Supervisor_Controller
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
		set_page_title('Dashboard Supervisor');
		set_breadcrumb([
			['title' => 'Dashboard']
		]);

		enable_charts();
		enable_datatables();

		$userId = $this->session->userdata('user_id');

		// Statistik (scoped ke supervisor)
		$data['total_leader'] = $this->PenggunaModel->countLeadersBySupervisor($userId);
		$data['total_teams'] = $this->TimModel->countBySupervisor($userId);
		$data['total_cs'] = $this->CustomerServiceModel->countBySupervisor($userId);
		$data['total_penilaian'] = $this->NilaiModel->countBySupervisor($userId);

		// Statistik global
		$data['total_criteria'] = $this->db->count_all('kriteria');

		// Total ranking (hanya yang dipublish dan di bawah supervisor ini)
		$data['total_rankings'] = $this->RankingModel->countBySupervisor($userId);

		// Periode terbaru dan top CS untuk supervisor
		$currentPeriode = $this->RankingModel->getLatestPeriode();
		$data['current_periode'] = $currentPeriode;

		if ($currentPeriode) {
			$data['top_cs'] = $this->RankingModel->getTopCsBySupervisor($userId, $currentPeriode, 5);
		} else {
			$data['top_cs'] = [];
		}

		// Data tambahan
		$data['leaders'] = $this->PenggunaModel->getLeadersWithStats($userId);
		$data['recent_nilai'] = $this->NilaiModel->getRecentBySupervisor($userId, 5);
		$data['cs_performance'] = $this->CustomerServiceModel->getPerformanceStatsBySupervisor($userId);

		render_layout('supervisor/dashboard/index', $data);
	}
}
