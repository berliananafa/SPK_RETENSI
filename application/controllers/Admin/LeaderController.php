<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Leader
 *
 * Mengelola data Leader dalam struktur organisasi.
 * Leader memimpin maksimal 1 Tim.
 */
class LeaderController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load model yang dibutuhkan
        $this->load->model('PenggunaModel');
        $this->load->model('TimModel');

        // Library validasi form
        $this->load->library('form_validation');
    }

    /**
     * Menampilkan daftar semua Leader
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
        
        // Ambil semua pengguna dengan level Leader beserta info tim (optimized)
        $data['leaders'] = $this->PenggunaModel->getLeadersWithTeamInfo();
        
        render_layout('admin/leader/index', $data);
    }

    /**
     * Menampilkan form tambah Leader
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
     * Menyimpan data Leader baru
     */
    public function store()
    {
        // Validasi input
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Leader', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        // Cek NIK duplikat
        if ($this->PenggunaModel->nikExists($this->input->post('nik'))) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/leader/create');
            return;
        }

        // Cek Email duplikat
        if ($this->PenggunaModel->emailExists($this->input->post('email'))) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/leader/create');
            return;
        }

        // Data Leader
        $data = [
            'nik'           => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email'         => $this->input->post('email'),
            'password'      => 'password', // Password default
            'level'         => PenggunaModel::LEVEL_LEADER
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
     * Menampilkan form edit Leader
     */
    public function edit($id)
    {
        $leader = $this->PenggunaModel->find($id);

        // Validasi Leader
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
     * Memperbarui data Leader
     */
    public function update($id)
    {
        $leader = $this->PenggunaModel->find($id);

        // Validasi Leader
        if (!$leader || $leader->level != PenggunaModel::LEVEL_LEADER) {
            $this->session->set_flashdata('error', 'Data Leader tidak ditemukan!');
            redirect('admin/leader');
            return;
        }

        // Validasi input
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Leader', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        // Cek NIK duplikat (exclude ID sendiri)
        if ($this->PenggunaModel->nikExists($this->input->post('nik'), $id)) {
            $this->session->set_flashdata('error', 'NIK sudah terdaftar di sistem!');
            redirect('admin/leader/edit/' . $id);
            return;
        }

        // Cek Email duplikat (exclude ID sendiri)
        if ($this->PenggunaModel->emailExists($this->input->post('email'), $id)) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar di sistem!');
            redirect('admin/leader/edit/' . $id);
            return;
        }

        // Data update
        $data = [
            'nik'           => $this->input->post('nik'),
            'nama_pengguna' => $this->input->post('nama_pengguna'),
            'email'         => $this->input->post('email')
        ];

        // Update password jika diisi
        if ($this->input->post('password')) {
            $data['password'] = $this->input->post('password');
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
     * Menghapus data Leader
     */
    public function delete($id)
    {
        $leader = $this->PenggunaModel->find($id);

        // Validasi Leader
        if (!$leader || $leader->level != PenggunaModel::LEVEL_LEADER) {
            $this->session->set_flashdata('error', 'Data Leader tidak ditemukan!');
            redirect('admin/leader');
            return;
        }

        // Cek apakah Leader masih memimpin tim
        $count_tim = $this->db->where('id_leader', $id)
                              ->count_all_results('tim');
        
        if ($count_tim > 0) {
            $this->session->set_flashdata(
                'error',
                "Leader tidak dapat dihapus karena masih memimpin {$count_tim} Tim!"
            );
            redirect('admin/leader');
            return;
        }

        // Hapus Leader
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Data Leader berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data Leader!');
        }

        redirect('admin/leader');
    }

    /**
     * Menampilkan detail Leader
     */
    public function detail($id)
    {
        $leader = $this->PenggunaModel->find($id);

        // Validasi Leader
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

        // Ambil tim yang dipimpin Leader
        $teams = $this->TimModel->getByLeader($id);
        $team  = !empty($teams) ? $teams[0] : null;

        // Ambil detail lengkap tim (leader, supervisor, anggota)
        if ($team) {
            $team = $this->TimModel->getByIdWithDetails($team->id_tim);
        }

        $data['leader'] = $leader;
        $data['team']   = $team;
        
        render_layout('admin/leader/detail', $data);
    }
}
