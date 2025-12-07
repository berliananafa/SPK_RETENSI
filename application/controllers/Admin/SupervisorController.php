<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupervisorController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenggunaModel');
        $this->load->model('SupervisorScopeModel');
        $this->load->model('KanalModel');
        $this->load->model('ProdukModel');
        $this->load->library('form_validation');
    }

    /**
     * List semua Supervisor
     */
    public function index()
    {
        set_page_title('Supervisor');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Supervisor']
        ]);
        
        enable_datatables();
		enable_sweetalert();
        
        // Ambil data supervisor dengan join ke junior manager (atasan)
        $this->db->select('pengguna.*, atasan.nama_pengguna as nama_atasan, atasan.nik as nik_atasan');
        $this->db->from('pengguna');
        $this->db->join('pengguna as atasan', 'pengguna.id_atasan = atasan.id_user', 'left');
        $this->db->where('pengguna.level', PenggunaModel::LEVEL_SUPERVISOR);
        $this->db->order_by('pengguna.nama_pengguna', 'ASC');
        $data['supervisors'] = $this->db->get()->result();
        
        render_layout('admin/supervisor/index', $data);
    }

    /**
     * Form tambah Supervisor
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
        
        // Ambil data junior manager untuk dropdown
        $data['junior_managers'] = $this->PenggunaModel->getByLevel(PenggunaModel::LEVEL_JUNIOR_MANAGER);
        
        // Ambil data kanal dan produk
        $data['kanals'] = $this->KanalModel->getAllOrdered();
        $data['produks'] = $this->ProdukModel->getAllOrdered();
        
        render_layout('admin/supervisor/create', $data);
    }

    /**
     * Simpan data Supervisor baru
     */
    public function store()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Supervisor', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('id_atasan', 'Junior Manager', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        // Cek duplikasi NIK
        if ($this->PenggunaModel->nikExists($this->input->post('nik'))) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/supervisor/create');
            return;
        }

        // Cek duplikasi Email
        if ($this->PenggunaModel->emailExists($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/supervisor/create');
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email' => $this->input->post('email'),
            'password' => 'password', // Password default
            'level' => PenggunaModel::LEVEL_SUPERVISOR,
            'id_atasan' => $this->input->post('id_atasan')
        ];

        $supervisorId = $this->PenggunaModel->create($data);
        
        if ($supervisorId) {
            // Simpan supervisor scope (kanal dan produk)
            $kanals = $this->input->post('id_kanal');
            $produks = $this->input->post('id_produk');
            
            if (!empty($kanals) && !empty($produks)) {
                foreach ($kanals as $kanalId) {
                    foreach ($produks as $produkId) {
                        // Cek apakah kombinasi sudah ada
                        if (!$this->SupervisorScopeModel->scopeExists($supervisorId, $kanalId, $produkId)) {
                            $this->SupervisorScopeModel->create([
                                'id_supervisor' => $supervisorId,
                                'id_kanal' => $kanalId,
                                'id_produk' => $produkId
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
     * Form edit Supervisor
     */
    public function edit($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

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

        $data['supervisor'] = $supervisor;
        $data['junior_managers'] = $this->PenggunaModel->getByLevel(PenggunaModel::LEVEL_JUNIOR_MANAGER);
        
        // Ambil data kanal dan produk
        $data['kanals'] = $this->KanalModel->getAllOrdered();
        $data['produks'] = $this->ProdukModel->getAllOrdered();
        
        // Ambil scope yang sudah dipilih
        $scopes = $this->SupervisorScopeModel->getBySupervisor($id);
        $data['selected_kanals'] = array_unique(array_column($scopes, 'id_kanal'));
        $data['selected_produks'] = array_unique(array_column($scopes, 'id_produk'));
        
        render_layout('admin/supervisor/edit', $data);
    }

    /**
     * Update data Supervisor
     */
    public function update($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

        if (!$supervisor || $supervisor->level != PenggunaModel::LEVEL_SUPERVISOR) {
            $this->session->set_flashdata('error', 'Data Supervisor tidak ditemukan!');
            redirect('admin/supervisor');
            return;
        }

        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Supervisor', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('id_atasan', 'Junior Manager', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        // Cek duplikasi NIK (exclude current ID)
        if ($this->PenggunaModel->nikExists($this->input->post('nik'), $id)) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/supervisor/edit/' . $id);
            return;
        }

        // Cek duplikasi Email (exclude current ID)
        if ($this->PenggunaModel->emailExists($this->input->post('email'), $id)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/supervisor/edit/' . $id);
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email' => $this->input->post('email'),
            'id_atasan' => $this->input->post('id_atasan')
        ];

        // Update password jika diisi
        $new_password = $this->input->post('password');
        if (!empty($new_password)) {
            $data['password'] = $new_password;
        }

        if ($this->PenggunaModel->updateById($id, $data)) {
            // Update supervisor scope
            // Hapus scope lama
            $this->SupervisorScopeModel->deleteBySupervisor($id);
            
            // Simpan scope baru
            $kanals = $this->input->post('id_kanal');
            $produks = $this->input->post('id_produk');
            
            if (!empty($kanals) && !empty($produks)) {
                foreach ($kanals as $kanalId) {
                    foreach ($produks as $produkId) {
                        $this->SupervisorScopeModel->create([
                            'id_supervisor' => $id,
                            'id_kanal' => $kanalId,
                            'id_produk' => $produkId
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
     * Hapus data Supervisor
     */
    public function delete($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

        if (!$supervisor || $supervisor->level != PenggunaModel::LEVEL_SUPERVISOR) {
            $this->session->set_flashdata('error', 'Data Supervisor tidak ditemukan!');
            redirect('admin/supervisor');
            return;
        }

        // Cek apakah masih menjadi supervisor di tim
        $count_tim = $this->db->where('id_supervisor', $id)
                              ->count_all_results('tim');
        
        if ($count_tim > 0) {
            $this->session->set_flashdata('error', "Supervisor tidak dapat dihapus karena masih menjadi supervisor dari {$count_tim} Tim!");
            redirect('admin/supervisor');
            return;
        }

        // Hapus supervisor scope terlebih dahulu
        $this->SupervisorScopeModel->deleteBySupervisor($id);
        
        // Hapus supervisor
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Data Supervisor berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data Supervisor!');
        }

        redirect('admin/supervisor');
    }

    /**
     * Detail Supervisor
     */
    public function detail($id)
    {
        $supervisor = $this->PenggunaModel->find($id);

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
        
        // Ambil scope supervisor (kanal dan produk yang ditangani)
        $data['scopes'] = $this->SupervisorScopeModel->getBySupervisor($id);
        
        // Ambil nama Junior Manager (atasan)
        if (!empty($supervisor->id_atasan)) {
            $atasan = $this->PenggunaModel->find($supervisor->id_atasan);
            $data['junior_manager'] = $atasan;
        } else {
            $data['junior_manager'] = null;
        }
        
        render_layout('admin/supervisor/detail', $data);
    }
}
