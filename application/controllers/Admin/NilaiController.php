<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Nilai
 * Mengelola monitoring penilaian, import nilai via Excel,
 * download template, dan penghapusan data nilai
 */
class NilaiController extends Admin_Controller
{
	/**
	 * Constructor
	 * Load model dan library yang dibutuhkan
	 */
	public function __construct()
	{
		parent::__construct();

		// Load model
		$this->load->model('NilaiModel');
		$this->load->model('CustomerServiceModel');
		$this->load->model('KriteriaModel');
		$this->load->model('SubKriteriaModel');
		$this->load->model('TimModel');

		// Load library
		$this->load->library('form_validation');
		$this->load->library('NilaiImport');
		$this->load->library('NilaiTemplate');
	}

	/**
	 * Halaman monitoring penilaian
	 * Menampilkan daftar nilai beserta filter dan statistik
	 */
	public function index()
	{
		// Set judul halaman
		set_page_title('Monitoring Penilaian');

		// Set breadcrumb navigasi
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Monitoring Penilaian']
		]);

		// Aktifkan DataTables dan SweetAlert
		enable_datatables();
		enable_sweetalert();

		// Ambil parameter filter dari URL
		$periode   = $this->input->get('periode') ?? date('Y-m');
		$idKriteria = $this->input->get('kriteria');
		$idTim      = $this->input->get('tim');

		// Siapkan filter untuk query data penilaian
		$filter = [
			'periode'      => $periode,
			'id_kriteria'  => $idKriteria,
			'id_tim'       => $idTim,
		];

		// Ambil data penilaian beserta relasinya
		$data['penilaian'] = $this->NilaiModel->getAllWithDetails($filter);

		// Data untuk dropdown filter (hanya kriteria yang approved)
		$data['kriteria'] = $this->KriteriaModel->getAllApproved();
		$data['tim']      = $this->TimModel->all();

		// Hitung statistik dari data yang sudah difilter
		$totalPenilaian = count($data['penilaian']);
		$data['total_penilaian'] = $totalPenilaian;

		$csIds   = [];
		$subIds  = [];
		$sumNilai = 0;

		foreach ($data['penilaian'] as $row) {
			// Kumpulkan ID CS
			if (isset($row->id_cs)) {
				$csIds[] = $row->id_cs;
			}

			// Kumpulkan ID sub kriteria
			if (isset($row->id_sub_kriteria)) {
				$subIds[] = $row->id_sub_kriteria;
			}

			// Hitung total nilai
			$sumNilai += is_numeric($row->nilai) ? $row->nilai : 0;
		}

		// Total CS unik
		$data['total_cs'] = count(array_unique($csIds));

		// Total sub kriteria unik
		$data['total_kriteria'] = count(array_unique($subIds));

		// Rata-rata nilai
		$data['rata_rata'] = $totalPenilaian ? round($sumNilai / $totalPenilaian, 2) : 0;

		// Kirim nilai filter ke view
		$data['filter_periode']  = $periode;
		$data['filter_kriteria'] = $idKriteria;
		$data['filter_tim']      = $idTim;

		// Render halaman
		render_layout('admin/nilai/index', $data);
	}

	/**
	 * Halaman input penilaian (upload Excel)
	 */
	public function input()
	{
		set_page_title('Input Penilaian');

		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Input Penilaian']
		]);

		// Ambil daftar kriteria (hanya yang sudah approved)
		$data['kriteria'] = $this->KriteriaModel->getAllApproved();

		render_layout('admin/nilai/input', $data);
	}

	/**
	 * Proses import data nilai dari file Excel
	 */
	public function store()
	{
		// Validasi periode
		$this->form_validation->set_rules(
			'periode',
			'Periode',
			'required|trim',
			['required' => 'Periode penilaian wajib diisi']
		);

		// Validasi upload file Excel
		$this->form_validation->set_rules(
			'file_excel',
			'File Excel',
			'callback_check_excel_upload'
		);

		// Jika validasi gagal
		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin/nilai/input');
			return;
		}

		try {
			// Upload file Excel
			$filePath = $this->_handleFileUpload();

			if (!$filePath) {
				$this->session->set_flashdata('error', 'Gagal upload file');
				redirect('admin/nilai/input');
				return;
			}

			// Ambil data dari form
			$periode = $this->input->post('periode', true);
			$replaceExisting = $this->input->post('replace_existing') == '1';

			// Proses import Excel
			$result = $this->nilaiimport->importFromExcel(
				$filePath,
				$periode,
				$replaceExisting
			);

			// Hapus file sementara
			@unlink($filePath);

			// Set pesan hasil import
			if ($result['success']) {
				$this->session->set_flashdata('success', $result['message']);
			} else {
				$this->session->set_flashdata('error', $result['message']);
			}
		} catch (Exception $e) {
			// Tangani error tak terduga
			$this->session->set_flashdata(
				'error',
				'Terjadi kesalahan: ' . $e->getMessage()
			);
			log_message('error', 'Import Nilai Error: ' . $e->getMessage());
		}

		redirect('admin/nilai');
	}

	/**
	 * Download template Excel penilaian
	 */
	public function download_template()
	{
		try {
			$this->nilaitemplate->generate();
		} catch (Exception $e) {
			$this->session->set_flashdata(
				'error',
				'Gagal download template: ' . $e->getMessage()
			);
			redirect('admin/nilai/input');
		}
	}

	/**
	 * Hapus data nilai berdasarkan ID
	 */
	public function delete($id)
	{
		// Cek apakah data nilai ada
		$nilai = $this->NilaiModel->find($id);

		if (!$nilai) {
			$this->session->set_flashdata('error', 'Data tidak ditemukan');
			redirect('admin/nilai');
			return;
		}

		// Proses hapus data
		if ($this->NilaiModel->deleteById($id)) {
			$this->session->set_flashdata('success', 'Data berhasil dihapus');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus data');
		}

		redirect('admin/nilai');
	}

	/**
	 * Validasi khusus upload file Excel
	 */
	public function check_excel_upload($str)
	{
		// Cek apakah file diupload
		if (empty($_FILES['file_excel']['name'])) {
			$this->form_validation->set_message(
				'check_excel_upload',
				'File Excel wajib diupload'
			);
			return FALSE;
		}

		// Cek ekstensi file
		$ext = strtolower(pathinfo(
			$_FILES['file_excel']['name'],
			PATHINFO_EXTENSION
		));

		if (!in_array($ext, ['xlsx', 'xls'])) {
			$this->form_validation->set_message(
				'check_excel_upload',
				'File harus Excel (.xlsx atau .xls)'
			);
			return FALSE;
		}

		// Cek ukuran file (maksimal 5MB)
		if ($_FILES['file_excel']['size'] > 5242880) {
			$this->form_validation->set_message(
				'check_excel_upload',
				'Ukuran file maksimal 5MB'
			);
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Handle proses upload file Excel
	 * @return string|false
	 */
	private function _handleFileUpload()
	{
		$config['upload_path']   = './uploads/temp/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size']      = 5120;
		$config['file_name']     = 'nilai_' . date('Ymd_His');

		// Buat folder jika belum ada
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}

		$this->load->library('upload', $config);

		// Proses upload
		if (!$this->upload->do_upload('file_excel')) {
			return false;
		}

		// Kembalikan path file
		return $this->upload->data('full_path');
	}
}
