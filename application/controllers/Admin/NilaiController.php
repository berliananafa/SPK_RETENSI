<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NilaiController extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('NilaiModel');
		$this->load->model('CustomerServiceModel');
		$this->load->model('KriteriaModel');
		$this->load->model('SubKriteriaModel');
		$this->load->model('TimModel');
		$this->load->library('form_validation');
		$this->load->library('NilaiImport');
		$this->load->library('NilaiTemplate');
	}

	/**
	 * Halaman monitoring penilaian
	 */
	public function index()
	{
		set_page_title('Monitoring Penilaian');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Monitoring Penilaian']
		]);

		enable_datatables();
		enable_sweetalert();

		// Get filter parameters
		$periode = $this->input->get('periode') ?? date('Y-m');
		$idKriteria = $this->input->get('kriteria');
		$idTim = $this->input->get('tim');

		// Get penilaian data with details (apply filters)
		$filter = [
			'periode' => $periode,
			'id_kriteria' => $idKriteria,
			'id_tim' => $idTim,
		];

		$data['penilaian'] = $this->NilaiModel->getAllWithDetails($filter);

		// Get filter data
		$data['kriteria'] = $this->KriteriaModel->getAllOrdered();
		$data['tim'] = $this->TimModel->all();

		// Calculate statistics from filtered data
		$totalPenilaian = count($data['penilaian']);
		$data['total_penilaian'] = $totalPenilaian;

		$csIds = [];
		$subIds = [];
		$sumNilai = 0;

		foreach ($data['penilaian'] as $row) {
			if (isset($row->id_cs)) {
				$csIds[] = $row->id_cs;
			}
			if (isset($row->id_sub_kriteria)) {
				$subIds[] = $row->id_sub_kriteria;
			}
			$sumNilai += is_numeric($row->nilai) ? $row->nilai : 0;
		}

		$data['total_cs'] = count(array_unique($csIds));
		$data['total_kriteria'] = count(array_unique($subIds));
		$data['rata_rata'] = $totalPenilaian ? round($sumNilai / $totalPenilaian, 2) : 0;

		// Pass filter values to view
		$data['filter_periode'] = $periode;
		$data['filter_kriteria'] = $idKriteria;
		$data['filter_tim'] = $idTim;

		render_layout('admin/nilai/index', $data);
	}

	/**
	 * Halaman input penilaian via Excel
	 */
	public function input()
	{
		set_page_title('Input Penilaian');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Input Penilaian']
		]);

		$data['kriteria'] = $this->KriteriaModel->getAllOrdered();

		render_layout('admin/nilai/input', $data);
	}

	/**
	 * Process import Excel
	 */
	public function store()
	{
		$this->form_validation->set_rules('periode', 'Periode', 'required|trim', [
			'required' => 'Periode penilaian wajib diisi'
		]);
		$this->form_validation->set_rules('file_excel', 'File Excel', 'callback_check_excel_upload');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin/nilai/input');
			return;
		}

		try {
			$filePath = $this->_handleFileUpload();

			if (!$filePath) {
				$this->session->set_flashdata('error', 'Gagal upload file');
				redirect('admin/nilai/input');
				return;
			}

			$periode = $this->input->post('periode', true);
			$replaceExisting = $this->input->post('replace_existing') == '1';

			$result = $this->nilaiimport->importFromExcel($filePath, $periode, $replaceExisting);

			@unlink($filePath);

			if ($result['success']) {
				$this->session->set_flashdata('success', $result['message']);
			} else {
				$this->session->set_flashdata('error', $result['message']);
			}
		} catch (Exception $e) {
			$this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
			log_message('error', 'Import Nilai Error: ' . $e->getMessage());
		}

		redirect('admin/nilai');
	}

	/**
	 * Download template Excel
	 */
	public function download_template()
	{
		try {
			$this->nilaitemplate->generate();
		} catch (Exception $e) {
			$this->session->set_flashdata('error', 'Gagal download template: ' . $e->getMessage());
			redirect('admin/nilai/input');
		}
	}

	/**
	 * Delete nilai by ID
	 */
	public function delete($id)
	{
		$nilai = $this->NilaiModel->find($id);

		if (!$nilai) {
			$this->session->set_flashdata('error', 'Data tidak ditemukan');
			redirect('admin/nilai');
			return;
		}

		if ($this->NilaiModel->deleteById($id)) {
			$this->session->set_flashdata('success', 'Data berhasil dihapus');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus data');
		}

		redirect('admin/nilai');
	}

	/**
	 * Custom validation for Excel upload
	 */
	public function check_excel_upload($str)
	{
		if (empty($_FILES['file_excel']['name'])) {
			$this->form_validation->set_message('check_excel_upload', 'File Excel wajib diupload');
			return FALSE;
		}

		$ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));

		if (!in_array($ext, ['xlsx', 'xls'])) {
			$this->form_validation->set_message('check_excel_upload', 'File harus Excel (.xlsx atau .xls)');
			return FALSE;
		}

		if ($_FILES['file_excel']['size'] > 5242880) {
			$this->form_validation->set_message('check_excel_upload', 'Ukuran file maksimal 5MB');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Handle file upload
	 */
	private function _handleFileUpload()
	{
		$config['upload_path'] = './uploads/temp/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = 5120;
		$config['file_name'] = 'nilai_' . date('Ymd_His');

		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file_excel')) {
			return false;
		}

		return $this->upload->data('full_path');
	}
}
