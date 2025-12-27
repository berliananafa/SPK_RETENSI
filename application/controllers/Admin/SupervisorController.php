<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Supervisor
 *
 * Mengelola data Supervisor dalam struktur organisasi
 * Supervisor berada di bawah Junior Manager dan memiliki scope Kanal & Produk
 */
class SupervisorController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load model yang dibutuhkan
        $this->load->model('PenggunaModel');
        $this->load->model('SupervisorScopeModel');
        $this->load->model('KanalModel');
        $this->load->model('ProdukModel');

        // Load library validasi form
        $this->load->library('form_validation');
    }

    /**
     * Menampilkan daftar semua Supervisor
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Supervisor');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Supervisor']
        ]);
        
        // Aktifkan DataTables dan SweetAlert
        enable_datatables();
		enable_sweetalert();
        
        // Ambil data supervisor beserta data atasannya (Junior Manager) - optimized
        $data['supervisors'] = $this->PenggunaModel->getSupervisorsWithAtasan();
        
        // Render halaman index supervisor
        render_layout('admin/supervisor/index', $data);
    }

    /**
     * Menampilkan form tambah Supervisor
     */
    public function create()
    {
        set_page_title('Tambah Supervisor');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Supervisor', 'url' => base_url('admin/supervisor')],
            ['title' => 'Tambah']
        ]);
        
        // Ambil data Junior Manager untuk dropdown atasan
        $data['junior_managers'] = $this->PenggunaModel
            ->getByLevel(PenggunaModel::LEVEL_JUNIOR_MANAGER);
        
        // Ambil data kanal dan produk untuk scope supervisor
        $data['kanals']  = $this->KanalModel->getAllOrdered();
        $data['produks'] = $this->ProdukModel->getAllOrdered();
        
        render_layout('admin/supervisor/create', $data);
    }

    /**
     * Menyimpan data Supervisor baru
     */
    public function store()
    {
        // Validasi input form
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Supervisor', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('id_atasan', 'Junior Manager', 'required');

        // Jika validasi gagal, kembali ke form create
        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        // Cek NIK sudah terdaftar atau belum
        if ($this->PenggunaModel->nikExists($this->input->post('nik'))) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/supervisor/create');
            return;
        }

        // Cek Email sudah terdaftar atau belum
        if ($this->PenggunaModel->emailExists($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/supervisor/create');
            return;
        }

        // Data supervisor yang akan disimpan
        $data = [
            'nik'           => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email'         => $this->input->post('email'),
            'password'      => 'password', // Password default
            'level'         => PenggunaModel::LEVEL_SUPERVISOR,
            'id_atasan'     => $this->input->post('id_atasan')
        ];

        // Simpan data supervisor
        $supervisorId = $this->PenggunaModel->create($data);
        
        if ($supervisorId) {
            // Simpan scope supervisor (kombinasi kanal & produk)
            $kanals  = $this->input->post('id_kanal');
            $produks = $this->input->post('id_produk');
            
            if (!empty($kanals) && !empty($produks)) {
                foreach ($kanals as $kanalId) {
                    foreach ($produks as $produkId) {

                        // Hindari duplikasi scope
                        if (!$this->SupervisorScopeModel
                            ->scopeExists($supervisorId, $kanalId, $produkId)) {

                            $this->SupervisorScopeModel->create([
                                'id_supervisor' => $supervisorId,
                                'id_kanal'      => $kanalId,
                                'id_produk'     => $produkId
                            ]);
                        }
                    }
                }
            }
            
            $this->session->set_flashdata('success', 'Data Supervisor berhasil ditambahkan!');
            redirect('admin/supervisor');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data Supervisor!');
            redirect('admin/supervisor/create');
        }
    }

    /**
     * Menampilkan form edit Supervisor
     */
    public function edit($id)
    {
        // Ambil data supervisor berdasarkan ID
        $supervisor = $this->PenggunaModel->find($id);

        // Validasi data supervisor
        if (!$supervisor || $supervisor->level != PenggunaModel::LEVEL_SUPERVISOR) {
            $this->session->set_flashdata('error', 'Data Supervisor tidak ditemukan!');
            redirect('admin/supervisor');
            return;
        }

        set_page_title('Edit Supervisor');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Supervisor', 'url' => base_url('admin/supervisor')],
            ['title' => 'Edit']
        ]);

        $data['supervisor']       = $supervisor;
        $data['junior_managers']  = $this->PenggunaModel->getByLevel(PenggunaModel::LEVEL_JUNIOR_MANAGER);
        
        // Ambil data kanal dan produk
        $data['kanals']  = $this->KanalModel->getAllOrdered();
        $data['produks'] = $this->ProdukModel->getAllOrdered();
        
        // Ambil scope yang sudah dipilih
        $scopes = $this->SupervisorScopeModel->getBySupervisor($id);
        $data['selected_kanals']  = array_unique(array_column($scopes, 'id_kanal'));
        $data['selected_produks'] = array_unique(array_column($scopes, 'id_produk'));
        
        render_layout('admin/supervisor/edit', $data);
    }

    /**
     * Memperbarui data Supervisor
     */
    public function update($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

        // Validasi supervisor
        if (!$supervisor || $supervisor->level != PenggunaModel::LEVEL_SUPERVISOR) {
            $this->session->set_flashdata('error', 'Data Supervisor tidak ditemukan!');
            redirect('admin/supervisor');
            return;
        }

        // Validasi input
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Supervisor', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('id_atasan', 'Junior Manager', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        // Cek NIK & Email duplikat (exclude ID saat ini)
        if ($this->PenggunaModel->nikExists($this->input->post('nik'), $id)) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/supervisor/edit/' . $id);
            return;
        }

        if ($this->PenggunaModel->emailExists($this->input->post('email'), $id)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/supervisor/edit/' . $id);
            return;
        }

        // Data yang akan diupdate
        $data = [
            'nik'           => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email'         => $this->input->post('email'),
            'id_atasan'     => $this->input->post('id_atasan')
        ];

        // Update password jika diisi
        if ($this->input->post('password')) {
            $data['password'] = $this->input->post('password');
        }

        if ($this->PenggunaModel->updateById($id, $data)) {

            // Reset scope lama
            $this->SupervisorScopeModel->deleteBySupervisor($id);

            // Simpan scope baru
            $kanals  = $this->input->post('id_kanal');
            $produks = $this->input->post('id_produk');

            if (!empty($kanals) && !empty($produks)) {
                foreach ($kanals as $kanalId) {
                    foreach ($produks as $produkId) {
                        $this->SupervisorScopeModel->create([
                            'id_supervisor' => $id,
                            'id_kanal'      => $kanalId,
                            'id_produk'     => $produkId
                        ]);
                    }
                }
            }

            $this->session->set_flashdata('success', 'Data Supervisor berhasil diperbarui!');
            redirect('admin/supervisor');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data Supervisor!');
            redirect('admin/supervisor/edit/' . $id);
        }
    }

    /**
     * Menghapus data Supervisor
     */
    public function delete($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

        // Validasi supervisor
        if (!$supervisor || $supervisor->level != PenggunaModel::LEVEL_SUPERVISOR) {
            $this->session->set_flashdata('error', 'Data Supervisor tidak ditemukan!');
            redirect('admin/supervisor');
            return;
        }

        // Cek apakah masih menjadi supervisor di tim
        $count_tim = $this->PenggunaModel->countTeamSupervisorUsage($id);
        
        if ($count_tim > 0) {
            $this->session->set_flashdata(
                'error',
                "Supervisor tidak dapat dihapus karena masih menangani {$count_tim} Tim!"
            );
            redirect('admin/supervisor');
            return;
        }

        // Hapus scope supervisor terlebih dahulu
        $this->SupervisorScopeModel->deleteBySupervisor($id);
        
        // Hapus data supervisor
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Data Supervisor berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data Supervisor!');
        }

        redirect('admin/supervisor');
    }

    /**
     * Menampilkan detail Supervisor
     */
    public function detail($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

        // Validasi supervisor
        if (!$supervisor || $supervisor->level != PenggunaModel::LEVEL_SUPERVISOR) {
            $this->session->set_flashdata('error', 'Data Supervisor tidak ditemukan!');
            redirect('admin/supervisor');
            return;
        }

        set_page_title('Detail Supervisor');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Supervisor', 'url' => base_url('admin/supervisor')],
            ['title' => 'Detail']
        ]);

        $data['supervisor'] = $supervisor;

        // Ambil scope supervisor (kanal & produk)
        $data['scopes'] = $this->SupervisorScopeModel->getBySupervisor($id);
        
        // Ambil data Junior Manager (atasan)
        $data['junior_manager'] = $supervisor->id_atasan
            ? $this->PenggunaModel->find($supervisor->id_atasan)
            : null;
        
        render_layout('admin/supervisor/detail', $data);
    }
}
