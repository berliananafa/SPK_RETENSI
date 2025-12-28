<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerServiceController extends Leader_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'CustomerServiceModel',
			'TimModel',
			'NilaiModel',
			'RankingModel'
		]);
	}

	/**
	 * Tampilkan daftar Customer Service di tim leader
	 */
	public function index()
	{
		set_page_title('Daftar Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
			['title' => 'Customer Service']
		]);

		enable_datatables();

		$userId = $this->session->userdata('user_id');

		// Get tim yang dipimpin leader
		$teams = $this->TimModel->getByLeader($userId);
		$team = !empty($teams) ? $teams[0] : null;

		$data['team'] = $team;
		$data['customer_services'] = [];
		$data['total_cs'] = 0;

		if ($team) {
			$data['customer_services'] = $this->CustomerServiceModel->getByTeam($team->id_tim);
			$data['total_cs'] = count($data['customer_services']);
		}

		render_layout('leader/customer_service/index', $data);
	}

	/**
	 * Tampilkan detail Customer Service dengan performance history
	 */
	public function detail($id_cs = null)
	{
		if (!$id_cs) {
			show_404();
		}

		$userId = $this->session->userdata('user_id');

		// Get tim leader
		$teams = $this->TimModel->getByLeader($userId);
		$team = !empty($teams) ? $teams[0] : null;

		if (!$team) {
			$this->session->set_flashdata('error', 'Anda belum memimpin tim.');
			redirect('leader/customer-service');
		}

		// Ambil CS dan validasi apakah di tim leader
		$cs = $this->CustomerServiceModel->getByIdWithDetails($id_cs);

		if (!$cs || $cs->id_tim != $team->id_tim) {
			$this->session->set_flashdata('error', 'Data Customer Service tidak ditemukan atau Anda tidak memiliki akses.');
			redirect('leader/customer-service');
		}

		set_page_title('Detail Customer Service - ' . $cs->nama_cs);
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('leader/customer-service')],
			['title' => $cs->nama_cs]
		]);

		enable_datatables();
		enable_charts();

		$latestPeriodeObj = $this->RankingModel->getLatestPeriodeByTeam($team->id_tim);

		$data['cs'] = $cs;
		$data['evaluations'] = $this->NilaiModel->getByCustomerService($id_cs);
		$data['stats'] = $this->NilaiModel->getStatsByCustomerService($id_cs);
		$data['selected_periode'] = $latestPeriodeObj->periode ?? null;
		$data['ranking'] = null;

		if (!empty($data['selected_periode'])) {
			$data['ranking'] = $this->db->select('r.*')
				->from('ranking r')
				->where('r.id_cs', $id_cs)
				->where('r.periode', $data['selected_periode'])
				->where_in('r.status', ['pending_leader', 'pending_supervisor', 'published'])
				->order_by('r.peringkat', 'ASC')
				->limit(1)
				->get()
				->row();
		}

		render_layout('leader/customer_service/detail', $data);
	}
}
