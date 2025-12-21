<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerServiceController extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'CustomerServiceModel' => 'CustomerService',
			'TimModel' => 'Tim',
			'ProdukModel' => 'Produk',
			'KanalModel' => 'Kanal'
		]);
		$this->load->library('CustomerServiceImport');
	}

	/**
	 * Display list of customer service
	 */
	public function index()
	{
		set_page_title('Manajemen Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service']
		]);
		enable_datatables();
		enable_sweetalert();

		$data['customer_services'] = $this->CustomerService->getAllWithDetails();
		render_layout('admin/customer_service/index', $data);
	}

	/**
	 * Show create customer service form
	 */
	public function create()
	{
		set_page_title('Tambah Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
			['title' => 'Tambah']
		]);

		$data['teams'] = $this->Tim->getAllWithDetails();
		$data['products'] = $this->Produk->all();
		$data['channels'] = $this->Kanal->all();

		render_layout('admin/customer_service/create', $data);
	}

	/**
	 * Store new customer service
	 */
	public function store()
	{
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique');
		$this->form_validation->set_rules('nama_cs', 'Nama Customer Service', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('id_tim', 'Tim', 'required|numeric');
		$this->form_validation->set_rules('id_produk', 'Produk', 'required|numeric');
		$this->form_validation->set_rules('id_kanal', 'Kanal', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			$this->create();
			return;
		}

		$data = [
			'nik' => $this->input->post('nik', true),
			'nama_cs' => $this->input->post('nama_cs', true),
			'id_tim' => $this->input->post('id_tim', true),
			'id_produk' => $this->input->post('id_produk', true),
			'id_kanal' => $this->input->post('id_kanal', true),
		];

		if ($this->CustomerService->create($data)) {
			$this->session->set_flashdata('success', 'Customer Service berhasil ditambahkan!');
			redirect('admin/customer-service');
		} else {
			$this->session->set_flashdata('error', 'Gagal menambahkan Customer Service!');
			$this->create();
		}
	}

	/**
	 * Show edit customer service form
	 */
	public function edit($id)
	{
		$cs = $this->CustomerService->getByIdWithDetails($id);

		if (!$cs) {
			$this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
			redirect('admin/customer-service');
		}

		set_page_title('Edit Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
			['title' => 'Edit']
		]);

		$data['cs'] = $cs;
		$data['teams'] = $this->Tim->getAllWithDetails();
		$data['products'] = $this->Produk->all();
		$data['channels'] = $this->Kanal->all();

		render_layout('admin/customer_service/edit', $data);
	}

	/**
	 * Update customer service
	 */
	public function update($id)
	{
		$cs = $this->CustomerService->find($id);

		if (!$cs) {
			$this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
			redirect('admin/customer-service');
		}

		$this->form_validation->set_rules('nik', 'NIK', 'required|trim');
		$this->form_validation->set_rules('nama_cs', 'Nama Customer Service', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('id_tim', 'Tim', 'required|numeric');
		$this->form_validation->set_rules('id_produk', 'Produk', 'required|numeric');
		$this->form_validation->set_rules('id_kanal', 'Kanal', 'required|numeric');

		// Check unique NIK if changed
		if ($this->input->post('nik') !== $cs->nik) {
			$this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique');
		}

		if ($this->form_validation->run() === FALSE) {
			$this->edit($id);
			return;
		}

		$data = [
			'nik' => $this->input->post('nik', true),
			'nama_cs' => $this->input->post('nama_cs', true),
			'id_tim' => $this->input->post('id_tim', true),
			'id_produk' => $this->input->post('id_produk', true),
			'id_kanal' => $this->input->post('id_kanal', true),
		];

		if ($this->CustomerService->updateById($id, $data)) {
			$this->session->set_flashdata('success', 'Customer Service berhasil diperbarui!');
			redirect('admin/customer-service');
		} else {
			$this->session->set_flashdata('error', 'Gagal memperbarui Customer Service!');
			$this->edit($id);
		}
	}

	/**
	 * Delete customer service
	 */
	public function delete($id)
	{
		$cs = $this->CustomerService->find($id);

		if (!$cs) {
			$this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
			redirect('admin/customer-service');
		}

		if ($this->CustomerService->deleteById($id)) {
			$this->session->set_flashdata('success', 'Customer Service berhasil dihapus!');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus Customer Service!');
		}

		redirect('admin/customer-service');
	}

	/**
	 * Show customer service detail
	 */
	public function detail($id)
	{
		$cs = $this->CustomerService->getByIdWithDetails($id);

		if (!$cs) {
			$this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
			redirect('admin/customer-service');
		}

		set_page_title('Detail Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
			['title' => 'Detail']
		]);

		$data['cs'] = $cs;

		render_layout('admin/customer_service/detail', $data);
	}

	/**
	 * Callback: Check NIK unique
	 */
	public function check_nik_unique($nik)
	{
		$id = $this->uri->segment(4); // Get ID from edit URL

		if ($this->CustomerService->nikExists($nik, $id)) {
			$this->form_validation->set_message('check_nik_unique', 'NIK sudah digunakan!');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Show import page
	 */
	public function import()
	{
		set_page_title('Import Customer Service');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
			['title' => 'Import']
		]);

		enable_sweetalert();
		render_layout('admin/customer_service/import');
	}

	/**
	 * Process import file
	 */
	public function process_import()
	{
		if (!$this->input->post('submit')) {
			redirect('admin/customer-service/import');
		}

		$config['upload_path'] = './uploads/temp/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = 2048; // 2MB
		$config['file_name'] = 'cs_import_' . time();

		// Ensure upload directory exists
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0777, true);
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$this->session->set_flashdata('error', $this->upload->display_errors('', ''));
			redirect('admin/customer-service/import');
		}

		$fileData = $this->upload->data();
		$filePath = $fileData['full_path'];

		try {
			$result = $this->customerserviceimport->processImport($filePath);

			// Delete uploaded file
			@unlink($filePath);

			if ($result['success'] > 0) {
				$message = $result['message'];
				if (!empty($result['errors'])) {
					$message .= '<br><strong>Error:</strong><br>' . implode('<br>', $result['errors']);
				}
				$this->session->set_flashdata('success', $message);
			} else {
				$error = 'Import gagal!';
				if (!empty($result['errors'])) {
					$error .= '<br>' . implode('<br>', $result['errors']);
				}
				$this->session->set_flashdata('error', $error);
			}
		} catch (Exception $e) {
			@unlink($filePath);
			$this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}

		redirect('admin/customer-service');
	}

	/**
	 * Download template Excel
	 */
	public function download_template()
	{
		$spreadsheet = $this->customerserviceimport->generateTemplate();
		$filename = 'Template_Import_CS_' . date('YmdHis') . '.xlsx';
		$this->customerserviceimport->download($spreadsheet, $filename);
	}

	/**
	 * Export all customer services to Excel
	 */
	public function export()
	{
		$spreadsheet = $this->customerserviceimport->generateExport();
		$filename = 'Export_Customer_Service_' . date('YmdHis') . '.xlsx';
		$this->customerserviceimport->download($spreadsheet, $filename);
	}
}
