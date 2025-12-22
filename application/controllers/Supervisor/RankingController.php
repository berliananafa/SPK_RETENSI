<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingController extends Supervisor_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['RankingModel', 'ProdukModel', 'KanalModel', 'TimModel']);
	}

	public function index()
	{
		set_page_title('Hasil Ranking');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
			['title' => 'Hasil Ranking']
		]);

		enable_datatables();
		enable_charts();

		$userId = $this->session->userdata('user_id');

		// Get latest periode
		$latestPeriode = $this->RankingModel->getLatestPeriodeBySupervisor($userId);
		$selectedPeriode = $this->input->get('periode') ?: ($latestPeriode->periode ?? null);

		// Get filter options
		$data['periodes'] = $this->RankingModel->getPeriodsBySupervisor($userId);
		$data['produks'] = $this->ProdukModel->all();
		$data['kanals'] = $this->KanalModel->all();
		$data['teams'] = $this->TimModel->getBySupervisorWithDetails($userId);

		// Get rankings with filters
		$filter = [];
		if ($this->input->get('id_produk')) {
			$filter['id_produk'] = $this->input->get('id_produk');
		}
		if ($this->input->get('id_kanal')) {
			$filter['id_kanal'] = $this->input->get('id_kanal');
		}
		if ($this->input->get('id_tim')) {
			$filter['id_tim'] = $this->input->get('id_tim');
		}

		$data['selected_periode'] = $selectedPeriode;
		$data['rankings'] = [];

		if ($selectedPeriode) {
			$data['rankings'] = $this->RankingModel->getByPeriodeBySupervisor($selectedPeriode, $userId, $filter);
		}

		render_layout('supervisor/ranking/index', $data);
	}

	/**
	 * Setujui ranking oleh supervisor (AJAX POST)
	 */
	public function approve($id = null)
	{
		if (!$this->input->is_ajax_request() && $this->input->method() !== 'post') {
			show_error('Invalid request method', 405);
		}

		if (empty($id)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'ID ranking tidak diberikan']);
			return;
		}

		$supervisorId = $this->session->userdata('user_id');

		// Ambil ranking
		$ranking = $this->RankingModel->getByIdWithDetails($id);
		if (!$ranking) {
			$this->output->set_status_header(404);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak ditemukan']);
			return;
		}

		// Validasi: ranking harus milik tim di bawah supervisor ini
		$teamBelongsToSupervisor = $this->db->select('id_tim')
			->from('tim')
			->where('id_tim', $ranking->id_tim)
			->where('id_supervisor', $supervisorId)
			->get()
			->row();

		if (!$teamBelongsToSupervisor) {
			$this->output->set_status_header(403);
			echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke ranking ini']);
			return;
		}

		// Update status ranking menjadi "approved" (atau "validated" untuk supervisor)
		$update = [
			'status' => 'approved',
			'supervisor_approval' => 1,
			'approved_by_supervisor' => $supervisorId,
			'approved_at' => date('Y-m-d H:i:s')
		];

		$saved = $this->RankingModel->update($id, $update);
		if ($saved) {
			$this->output->set_content_type('application/json');
			echo json_encode(['status' => 'success', 'message' => 'Ranking berhasil disetujui oleh supervisor']);
		} else {
			$this->output->set_status_header(500);
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
		}
	}
}
