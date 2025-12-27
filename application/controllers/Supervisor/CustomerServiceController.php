<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerServiceController extends Supervisor_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['CustomerServiceModel', 'TimModel', 'NilaiModel', 'RankingModel']);
	}

	/**
	 * Tampilkan daftar Customer Service di bawah supervisor
	 */
	public function index()
	{
		set_page_title('Daftar Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
			['title' => 'Customer Service']
		]);

		enable_datatables();

		$supervisorId = $this->session->userdata('user_id');
		$data['teams'] = $this->TimModel->getBySupervisorWithDetails($supervisorId);
		$data['customer_services'] = $this->CustomerServiceModel->getBySupervisor($supervisorId);
		$data['total_cs'] = count($data['customer_services']);

		render_layout('supervisor/customer_service/index', $data);
	}

	/**
	 * Tampilkan detail Customer Service dengan performance history
	 */
	public function detail($id_cs = null)
	{
		if (!$id_cs) {
			show_404();
		}

		set_page_title('Detail Customer Service - ' . $cs->nama_cs);
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('supervisor/customer-service')],
			['title' => $cs->nama_cs]
		]);

		enable_datatables();

		$supervisorId = $this->session->userdata('user_id');
		$cs = $this->CustomerServiceModel->getCsBySupervisor($id_cs, $supervisorId);
		$latestPeriodeObj = $this->RankingModel->getLatestPeriodeBySupervisor($supervisorId);

		if (!$cs) {
			$this->session->set_flashdata('error', 'Data Customer Service tidak ditemukan atau Anda tidak memiliki akses.');
			redirect('supervisor/customer-service');
		}

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
				->where('r.status', 'published')
				->order_by('r.peringkat', 'ASC')
				->limit(1)
				->get()
				->row();
		}

		render_layout('supervisor/customer_service/detail', $data);
	}
}
