<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Manajemen Customer Service (Admin)
 *
 * Mengelola data Customer Service meliputi:
 * - CRUD (Create, Read, Update, Delete)
 * - Import & Export data Excel
 * - Validasi NIK unik
 */
class CustomerServiceController extends Admin_Controller
{
	/**
	 * Constructor
	 *
	 * Memanggil constructor parent dan memuat
	 * model serta library yang dibutuhkan.
	 */
	public function __construct()
	{
		parent::__construct();

		// Load model yang berhubungan dengan Customer Service
		$this->load->model([
			'CustomerServiceModel' => 'CustomerService',
			'TimModel'             => 'Tim',
			'ProdukModel'          => 'Produk',
			'KanalModel'           => 'Kanal'
		]);

		// Load library untuk import & export Customer Service
		$this->load->library('CustomerServiceImport');
	}

	/**
	 * Halaman daftar Customer Service
	 *
	 * Menampilkan tabel Customer Service beserta
	 * relasi tim, produk, dan kanal.
	 */
	public function index()
	{
		// Set judul halaman
		set_page_title('Manajemen Customer Service');

		// Set breadcrumb navigasi
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service']
		]);

		// Aktifkan DataTables dan SweetAlert
		enable_datatables();
		enable_sweetalert();

		// Ambil seluruh data Customer Service beserta detailnya
		$data['customer_services'] = $this->CustomerService->getAllWithDetails();

		// Render halaman index
		render_layout('admin/customer_service/index', $data);
	}

	/**
	 * Halaman form tambah Customer Service
	 */
	public function create()
	{
		set_page_title('Tambah Customer Service');

		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
			['title' => 'Tambah']
		]);

		// Data pendukung untuk dropdown
		$data['teams']    = $this->Tim->getAllWithDetails();
		$data['products'] = $this->Produk->all();
		$data['channels'] = $this->Kanal->all();

		render_layout('admin/customer_service/create', $data);
	}

	/**
	 * Proses penyimpanan Customer Service baru
	 */
	public function store()
	{
		// Validasi input form
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique');
		$this->form_validation->set_rules('nama_cs', 'Nama Customer Service', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('id_tim', 'Tim', 'required|numeric');
		$this->form_validation->set_rules('id_produk', 'Produk', 'required|numeric');
		$this->form_validation->set_rules('id_kanal', 'Kanal', 'required|numeric');

		// Jika validasi gagal, kembali ke form create
		if ($this->form_validation->run() === FALSE) {
			$this->create();
			return;
		}

		// Data yang akan disimpan
		$data = [
			'nik'      => $this->input->post('nik', true),
			'nama_cs'  => $this->input->post('nama_cs', true),
			'id_tim'   => $this->input->post('id_tim', true),
			'id_produk'=> $this->input->post('id_produk', true),
			'id_kanal' => $this->input->post('id_kanal', true),
		];

		// Simpan data ke database
		if ($this->CustomerService->create($data)) {
			$this->session->set_flashdata('success', 'Customer Service berhasil ditambahkan!');
			redirect('admin/customer-service');
		} else {
			$this->session->set_flashdata('error', 'Gagal menambahkan Customer Service!');
			$this->create();
		}
	}

	/**
	 * Halaman form edit Customer Service
	 */
	public function edit($id)
	{
		// Ambil data CS berdasarkan ID
		$cs = $this->CustomerService->getByIdWithDetails($id);

		// Jika data tidak ditemukan
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

		// Data untuk view
		$data['cs']       = $cs;
		$data['teams']    = $this->Tim->getAllWithDetails();
		$data['products'] = $this->Produk->all();
		$data['channels'] = $this->Kanal->all();

		render_layout('admin/customer_service/edit', $data);
	}

	/**
	 * Proses update Customer Service
	 */
	public function update($id)
	{
		$cs = $this->CustomerService->find($id);

		if (!$cs) {
			$this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
			redirect('admin/customer-service');
		}

		// Validasi input
		$this->form_validation->set_rules('nik', 'NIK', 'required|trim');
		$this->form_validation->set_rules('nama_cs', 'Nama Customer Service', 'required|trim|min_length[3]');
		$this->form_validation->set_rules('id_tim', 'Tim', 'required|numeric');
		$this->form_validation->set_rules('id_produk', 'Produk', 'required|numeric');
		$this->form_validation->set_rules('id_kanal', 'Kanal', 'required|numeric');

		// Validasi NIK unik hanya jika NIK diubah
		if ($this->input->post('nik') !== $cs->nik) {
			$this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique');
		}

		if ($this->form_validation->run() === FALSE) {
			$this->edit($id);
			return;
		}

		// Data update
		$data = [
			'nik'      => $this->input->post('nik', true),
			'nama_cs'  => $this->input->post('nama_cs', true),
			'id_tim'   => $this->input->post('id_tim', true),
			'id_produk'=> $this->input->post('id_produk', true),
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
	 * Hapus Customer Service
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
	 * Halaman detail Customer Service
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
	 * Callback validasi NIK unik
	 *
	 * Digunakan pada proses create dan update.
	 */
	public function check_nik_unique($nik)
	{
		// Ambil ID dari URL (khusus edit)
		$id = $this->uri->segment(4);

		if ($this->CustomerService->nikExists($nik, $id)) {
			$this->form_validation->set_message('check_nik_unique', 'NIK sudah digunakan!');
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Halaman import Customer Service
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
	 * Proses import file Excel Customer Service
	 */
	public function process_import()
	{
		// Pastikan request berasal dari form submit
		if (!$this->input->post('submit')) {
			redirect('admin/customer-service/import');
		}

		// Konfigurasi upload file
		$config['upload_path']   = './uploads/temp/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size']      = 2048; // Maksimal 2MB
		$config['file_name']     = 'cs_import_' . time();

		// Pastikan folder upload tersedia
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0777, true);
		}

		$this->load->library('upload', $config);

		// Proses upload file
		if (!$this->upload->do_upload('file')) {
			$this->session->set_flashdata('error', $this->upload->display_errors('', ''));
			redirect('admin/customer-service/import');
		}

		$fileData = $this->upload->data();
		$filePath = $fileData['full_path'];

		try {
			// Proses import Excel
			$result = $this->customerserviceimport->processImport($filePath);

			// Hapus file setelah diproses
			@unlink($filePath);

			if ($result['success'] > 0) {
				$message = $result['message'];

				// Tampilkan error baris jika ada
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
	 * Download template Excel import Customer Service
	 */
	public function download_template()
	{
		$spreadsheet = $this->customerserviceimport->generateTemplate();
		$filename = 'Template_Import_CS_' . date('YmdHis') . '.xlsx';

		$this->customerserviceimport->download($spreadsheet, $filename);
	}

	/**
	 * Export seluruh data Customer Service ke Excel
	 */
	public function export()
	{
		$spreadsheet = $this->customerserviceimport->generateExport();
		$filename = 'Export_Customer_Service_' . date('YmdHis') . '.xlsx';

		$this->customerserviceimport->download($spreadsheet, $filename);
	}
}
