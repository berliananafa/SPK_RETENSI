<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeaderController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenggunaModel');
        $this->load->model('TimModel');
        $this->load->library('form_validation');
    }

    /**
     * List semua Leader
     */
    public function index()
    {
        set_page_title('Leader');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Leader']
        ]);
        
        enable_datatables();
		enable_sweetalert();
        
        // Ambil data leader dengan info tim
        $leaders = $this->PenggunaModel->getByLevel(PenggunaModel::LEVEL_LEADER);
        
        // Tambahkan info tim untuk setiap leader
        foreach ($leaders as $leader) {
            $team = $this->TimModel->getByLeader($leader->id_user);
            $leader->tim = !empty($team) ? $team[0] : null; // Leader hanya pegang 1 tim
        }
        
        $data['leaders'] = $leaders;
        
        render_layout('admin/leader/index', $data);
    }

    /**
     * Form tambah Leader
     */
    public function create()
    {
        set_page_title('Tambah Leader');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Leader', 'url' => base_url('admin/leader')],
            ['title' => 'Tambah']
        ]);
        
        render_layout('admin/leader/create');
    }

    /**
     * Simpan data Leader baru
     */
    public function store()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Leader', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        // Cek duplikasi NIK
        if ($this->PenggunaModel->nikExists($this->input->post('nik'))) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/leader/create');
            return;
        }

        // Cek duplikasi Email
        if ($this->PenggunaModel->emailExists($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/leader/create');
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email' => $this->input->post('email'),
            'password' => 'password', // Password default
            'level' => PenggunaModel::LEVEL_LEADER
        ];

        if ($this->PenggunaModel->create($data)) {
            $this->session->set_flashdata('success', 'Data Leader berhasil ditambahkan!');
            redirect('admin/leader');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data Leader!');
            redirect('admin/leader/create');
        }
    }

    /**
     * Form edit Leader
     */
    public function edit($id)
    {
        $leader = $this->PenggunaModel->find($id);

        if (!$leader || $leader->level != PenggunaModel::LEVEL_LEADER) {
            $this->session->set_flashdata('error', 'Data Leader tidak ditemukan!');
            redirect('admin/leader');
            return;
        }

        set_page_title('Edit Leader');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Leader', 'url' => base_url('admin/leader')],
            ['title' => 'Edit']
        ]);

        $data['leader'] = $leader;
        
        render_layout('admin/leader/edit', $data);
    }

    /**
     * Update data Leader
     */
    public function update($id)
    {
        $leader = $this->PenggunaModel->find($id);

        if (!$leader || $leader->level != PenggunaModel::LEVEL_LEADER) {
            $this->session->set_flashdata('error', 'Data Leader tidak ditemukan!');
            redirect('admin/leader');
            return;
        }

        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Leader', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        // Cek duplikasi NIK (exclude current ID)
        if ($this->PenggunaModel->nikExists($this->input->post('nik'), $id)) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/leader/edit/' . $id);
            return;
        }

        // Cek duplikasi Email (exclude current ID)
        if ($this->PenggunaModel->emailExists($this->input->post('email'), $id)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/leader/edit/' . $id);
            return;
        }

        $data = [
            'nik' => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email' => $this->input->post('email')
        ];

        // Update password jika diisi
        $new_password = $this->input->post('password');
        if (!empty($new_password)) {
            $data['password'] = $new_password;
        }

        if ($this->PenggunaModel->updateById($id, $data)) {
            $this->session->set_flashdata('success', 'Data Leader berhasil diperbarui!');
            redirect('admin/leader');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data Leader!');
            redirect('admin/leader/edit/' . $id);
        }
    }

    /**
     * Hapus data Leader
     */
    public function delete($id)
    {
        $leader = $this->PenggunaModel->find($id);

        if (!$leader || $leader->level != PenggunaModel::LEVEL_LEADER) {
            $this->session->set_flashdata('error', 'Data Leader tidak ditemukan!');
            redirect('admin/leader');
            return;
        }

        // Cek apakah masih memimpin tim
        $count_tim = $this->db->where('id_leader', $id)
                              ->count_all_results('tim');
        
        if ($count_tim > 0) {
            $this->session->set_flashdata('error', "Leader tidak dapat dihapus karena masih memimpin {$count_tim} Tim!");
            redirect('admin/leader');
            return;
        }

        // Hapus leader
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Data Leader berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data Leader!');
        }

        redirect('admin/leader');
    }

    /**
     * Detail Leader
     */
    public function detail($id)
    {
        $leader = $this->PenggunaModel->find($id);

        if (!$leader || $leader->level != PenggunaModel::LEVEL_LEADER) {
            $this->session->set_flashdata('error', 'Data Leader tidak ditemukan!');
            redirect('admin/leader');
            return;
        }

        set_page_title('Detail Leader');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Organisasi'],
            ['title' => 'Leader', 'url' => base_url('admin/leader')],
            ['title' => 'Detail']
        ]);

        // Ambil tim yang dipimpin leader ini
        $teams = $this->TimModel->getByLeader($id);
        $team = !empty($teams) ? $teams[0] : null; // Leader hanya pegang 1 tim
        
        // Jika ada tim, ambil detail lengkap
        if ($team) {
            $team = $this->TimModel->getByIdWithDetails($team->id_tim);
        }
        
        $data['leader'] = $leader;
        $data['team'] = $team;
        
        render_layout('admin/leader/detail', $data);
    }
}
