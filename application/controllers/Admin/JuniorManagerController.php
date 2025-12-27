<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Junior Manager (Admin)
 *
 * Mengelola data Junior Manager dalam struktur organisasi, meliputi:
 * - CRUD Junior Manager
 * - Relasi Junior Manager dengan Supervisor
 * - Validasi data (NIK & Email)
 */
class JuniorManagerController extends Admin_Controller
{
    /**
     * Constructor
     *
     * Memanggil constructor parent serta memuat
     * model dan library yang dibutuhkan.
     */
    public function __construct()
    {
        parent::__construct();

        // Load model pengguna
        $this->load->model('PenggunaModel');

        // Load library validasi form
        $this->load->library('form_validation');
    }

    /**
     * List semua Junior Manager
     *
     * Menampilkan daftar Junior Manager beserta
     * jumlah Supervisor yang berada di bawahnya.
     */
    public function index()
    {
        set_page_title('Junior Manager');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Junior Manager']
        ]);
        
        // Aktifkan DataTables & SweetAlert
        enable_datatables();
        enable_sweetalert();
    
        // Ambil data pengguna dengan level Junior Manager dengan supervisor count (optimized)
        $data['junior_managers'] = $this->PenggunaModel->getJuniorManagersWithSupervisorCount();

        render_layout('admin/junior_manager/index', $data);
    }

    /**
     * Form tambah Junior Manager
     */
    public function create()
    {
        set_page_title('Tambah Junior Manager');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Junior Manager', 'url' => base_url('admin/junior-manager')],
            ['title' => 'Tambah']
        ]);
        
        render_layout('admin/junior_manager/create');
    }

    /**
     * Simpan data Junior Manager baru
     */
    public function store()
    {
        // Validasi input form
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Junior Manager', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        // Jika validasi gagal, kembali ke form
        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        // Cek duplikasi NIK
        if ($this->PenggunaModel->nikExists($this->input->post('nik'))) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/junior-manager/create');
            return;
        }

        // Cek duplikasi Email
        if ($this->PenggunaModel->emailExists($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/junior-manager/create');
            return;
        }

        // Data Junior Manager yang akan disimpan
        $data = [
            'nik'            => $this->input->post('nik'),
            'nama_pengguna'  => $this->input->post('nama_pengguna'),
            'email'          => $this->input->post('email'),
            'password'       => 'password', // Password default
            'level'          => PenggunaModel::LEVEL_JUNIOR_MANAGER
        ];

        // Simpan data ke database
        if ($this->PenggunaModel->create($data)) {
            $this->session->set_flashdata('success', 'Data Junior Manager berhasil ditambahkan!');
            redirect('admin/junior-manager');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data Junior Manager!');
            redirect('admin/junior-manager/create');
        }
    }

    /**
     * Form edit Junior Manager
     */
    public function edit($id)
    {
        // Ambil data pengguna berdasarkan ID
        $junior_manager = $this->PenggunaModel->find($id);

        // Validasi data dan level pengguna
        if (!$junior_manager || $junior_manager->level != PenggunaModel::LEVEL_JUNIOR_MANAGER) {
            $this->session->set_flashdata('error', 'Data Junior Manager tidak ditemukan!');
            redirect('admin/junior-manager');
            return;
        }

        set_page_title('Edit Junior Manager');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Junior Manager', 'url' => base_url('admin/junior-manager')],
            ['title' => 'Edit']
        ]);

        $data['junior_manager'] = $junior_manager;
        
        render_layout('admin/junior_manager/edit', $data);
    }

    /**
     * Update data Junior Manager
     */
    public function update($id)
    {
        $junior_manager = $this->PenggunaModel->find($id);

        // Validasi data dan level
        if (!$junior_manager || $junior_manager->level != PenggunaModel::LEVEL_JUNIOR_MANAGER) {
            $this->session->set_flashdata('error', 'Data Junior Manager tidak ditemukan!');
            redirect('admin/junior-manager');
            return;
        }

        // Validasi input
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Junior Manager', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        // Cek duplikasi NIK (kecuali ID saat ini)
        if ($this->PenggunaModel->nikExists($this->input->post('nik'), $id)) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/junior-manager/edit/' . $id);
            return;
        }

        // Cek duplikasi Email (kecuali ID saat ini)
        if ($this->PenggunaModel->emailExists($this->input->post('email'), $id)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/junior-manager/edit/' . $id);
            return;
        }

        // Data yang akan diupdate
        $data = [
            'nik'           => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email'         => $this->input->post('email')
        ];

        // Update password jika diisi
        $new_password = $this->input->post('password');
        if (!empty($new_password)) {
            $data['password'] = $new_password;
        }

        if ($this->PenggunaModel->updateById($id, $data)) {
            $this->session->set_flashdata('success', 'Data Junior Manager berhasil diperbarui!');
            redirect('admin/junior-manager');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data Junior Manager!');
            redirect('admin/junior-manager/edit/' . $id);
        }
    }

    /**
     * Hapus data Junior Manager
     *
     * Tidak dapat dihapus jika masih memiliki Supervisor bawahan.
     */
    public function delete($id)
    {
        $junior_manager = $this->PenggunaModel->find($id);

        if (!$junior_manager || $junior_manager->level != PenggunaModel::LEVEL_JUNIOR_MANAGER) {
            $this->session->set_flashdata('error', 'Data Junior Manager tidak ditemukan!');
            redirect('admin/junior-manager');
            return;
        }

        // Cek apakah masih menjadi atasan Supervisor
        $count_supervisor = $this->db
            ->where('id_atasan', $id)
            ->where('level', PenggunaModel::LEVEL_SUPERVISOR)
            ->count_all_results('pengguna');
        
        if ($count_supervisor > 0) {
            $this->session->set_flashdata(
                'error',
                "Junior Manager tidak dapat dihapus karena masih menjadi atasan dari {$count_supervisor} Supervisor!"
            );
            redirect('admin/junior-manager');
            return;
        }

        // Proses hapus data
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Data Junior Manager berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data Junior Manager!');
        }

        redirect('admin/junior-manager');
    }

    /**
     * Detail Junior Manager
     *
     * Menampilkan informasi Junior Manager
     * serta daftar Supervisor yang berada di bawahnya.
     */
    public function detail($id)
    {
        $junior_manager = $this->PenggunaModel->find($id);

        if (!$junior_manager || $junior_manager->level != PenggunaModel::LEVEL_JUNIOR_MANAGER) {
            $this->session->set_flashdata('error', 'Data Junior Manager tidak ditemukan!');
            redirect('admin/junior-manager');
            return;
        }

        set_page_title('Detail Junior Manager');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Junior Manager', 'url' => base_url('admin/junior-manager')],
            ['title' => 'Detail']
        ]);

        // Data Junior Manager
        $data['junior_manager'] = $junior_manager;
        
        // Ambil daftar Supervisor di bawah Junior Manager ini
        $data['supervisors'] = $this->db
            ->where('level', PenggunaModel::LEVEL_SUPERVISOR)
            ->where('id_atasan', $id)
            ->order_by('nama_pengguna', 'ASC')
            ->get('pengguna')
            ->result();
        
        render_layout('admin/junior_manager/detail', $data);
    }
}
