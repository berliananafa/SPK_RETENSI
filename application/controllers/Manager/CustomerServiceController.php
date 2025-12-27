<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Customer Service untuk Junior Manager
 * Junior Manager melihat semua CS di bawah supervisor-supervisor yang dia supervisi
 */
class CustomerServiceController extends Manager_Controller
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
	 * Tampilkan daftar Customer Service di bawah Junior Manager
	 * (CS dari semua tim yang supervisornya di bawah Junior Manager)
	 */
	public function index()
	{
		set_page_title('Daftar Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
			['title' => 'Customer Service']
		]);
		enable_datatables();

		$managerId = $this->session->userdata('user_id');
		// Get teams dan CS berdasarkan scope Junior Manager
		$data['teams'] = $this->TimModel->getByJuniorManager($managerId);
		$data['customer_services'] = $this->CustomerServiceModel->getByJuniorManager($managerId);
		$data['total_cs'] = count($data['customer_services']);

		render_layout('manager/customer_service/index', $data);
	}

	/**
	 * Tampilkan detail Customer Service dengan performance history
	 */
	public function detail($id_cs = null)
	{
		if (!$id_cs) {
			show_404();
		}

		$managerId = $this->session->userdata('user_id');
		// Validasi akses: CS harus berada di bawah supervisor yang di-manage oleh Junior Manager ini
		$cs = $this->CustomerServiceModel->getCsByJuniorManager($id_cs, $managerId);
		if (!$cs) {
			$this->session->set_flashdata('error', 'Data Customer Service tidak ditemukan atau Anda tidak memiliki akses.');
			redirect('junior-manager/customer-service');
		}

		set_page_title('Detail Customer Service - ' . $cs->nama_cs);
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('junior-manager/customer-service')],
			['title' => $cs->nama_cs]
		]);

		enable_datatables();
		enable_charts();

		// Get latest periode for Junior Manager scope
		$latestPeriodeObj = $this->RankingModel->getLatestPeriodeByManager($managerId);

		$data['cs'] = $cs;
		$data['evaluations'] = $this->NilaiModel->getByCustomerService($id_cs);
		$data['stats'] = $this->NilaiModel->getStatsByCustomerService($id_cs);
		$data['selected_periode'] = $latestPeriodeObj->periode ?? null;
		$data['ranking'] = null;

		// Get ranking info jika ada periode
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

		render_layout('manager/customer_service/detail', $data);
	}
}
