<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Pengguna
 * Mengelola CRUD data pengguna (admin, junior manager, supervisor, leader)
 */
class PenggunaController extends Admin_Controller
{
    /**
     * Constructor
     * Load model dan library yang dibutuhkan
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenggunaModel');
        $this->load->library('form_validation');
    }

    /**
     * Halaman daftar pengguna
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Daftar Pengguna');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Pengguna']
        ]);
        
        // Aktifkan DataTables dan SweetAlert
        enable_datatables();
        enable_sweetalert();
        
        // Ambil seluruh data pengguna
        $data['users'] = $this->PenggunaModel->all();
        
        // Render halaman index pengguna
        render_layout('admin/pengguna/index', $data);
    }

    /**
     * Halaman form tambah pengguna
     */
    public function create()
    {
        set_page_title('Tambah Pengguna');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Pengguna', 'url' => base_url('admin/pengguna')],
            ['title' => 'Tambah']
        ]);
        
        render_layout('admin/pengguna/create');
    }

    /**
     * Proses simpan data pengguna baru
     */
    public function store()
    {
        /**
         * Aturan validasi input
         */
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|is_unique[pengguna.nik]');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[pengguna.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules(
            'level',
            'Level',
            'required|in_list[admin,junior_manager,supervisor,leader]'
        );

        // Jika validasi gagal
        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            // Data yang akan disimpan
            $data = [
                'nik'           => $this->input->post('nik', true),
                'nama_pengguna' => $this->input->post('nama_pengguna', true),
                'email'         => $this->input->post('email', true),
                'password'      => $this->input->post('password', true),
                'level'         => $this->input->post('level', true),
            ];

            // Simpan ke database
            if ($this->PenggunaModel->create($data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan!');
                redirect('admin/pengguna');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna!');
                redirect('admin/pengguna/create');
            }
        }
    }

    /**
     * Halaman form edit pengguna
     */
    public function edit($id)
    {
        set_page_title('Edit Pengguna');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Pengguna', 'url' => base_url('admin/pengguna')],
            ['title' => 'Edit']
        ]);
        
        // Ambil data pengguna berdasarkan ID
        $data['user'] = $this->PenggunaModel->find($id);
        
        // Jika data tidak ditemukan
        if (empty($data['user'])) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan!');
            redirect('admin/pengguna');
        }
        
        render_layout('admin/pengguna/edit', $data);
    }

    /**
     * Proses update data pengguna
     */
    public function update($id)
    {
        // Cek apakah pengguna ada
        $user = $this->PenggunaModel->find($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan!');
            redirect('admin/pengguna');
        }

        /**
         * Validasi input
         */
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique['.$id.']');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_check_email_unique['.$id.']');
        $this->form_validation->set_rules(
            'level',
            'Level',
            'required|in_list[admin,junior_manager,supervisor,leader]'
        );

        /**
         * Password bersifat opsional saat edit
         */
        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'matches[password]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            // Data update
            $data = [
                'nik'           => $this->input->post('nik', true),
                'nama_pengguna' => $this->input->post('nama_pengguna', true),
                'email'         => $this->input->post('email', true),
                'level'         => $this->input->post('level', true),
            ];

            // Update password jika diisi
            if (!empty($this->input->post('password'))) {
                $data['password'] = $this->input->post('password', true);
            }

            // Update ke database
            if ($this->PenggunaModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil diupdate!');
                redirect('admin/pengguna');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate pengguna!');
                redirect('admin/pengguna/edit/'.$id);
            }
        }
    }

    /**
     * Proses hapus pengguna
     */
    public function delete($id)
    {
        // Cek apakah pengguna ada
        $user = $this->PenggunaModel->find($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan!');
            redirect('admin/pengguna');
        }

        // Mencegah pengguna menghapus akun sendiri
        if ($this->session->userdata('id_user') == $id) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri!');
            redirect('admin/pengguna');
        }

        /**
         * Validasi relasi data sebelum hapus
         */

        // Cek sebagai atasan
        $used_as_atasan = $this->PenggunaModel->countByAtasan($id);
        if ($used_as_atasan > 0) {
            $this->session->set_flashdata(
                'error',
                'Pengguna tidak dapat dihapus karena masih menjadi atasan dari ' . $used_as_atasan . ' pengguna!'
            );
            redirect('admin/pengguna');
        }

        // Cek pada supervisor scope
        $used_in_scope = $this->PenggunaModel->countSupervisorScopeUsage($id);
        if ($used_in_scope > 0) {
            $this->session->set_flashdata(
                'error',
                'Pengguna tidak dapat dihapus karena masih memiliki ' . $used_in_scope . ' scope pekerjaan!'
            );
            redirect('admin/pengguna');
        }

        // Cek sebagai leader tim
        $used_as_leader = $this->PenggunaModel->countTeamLeaderUsage($id);
        if ($used_as_leader > 0) {
            $this->session->set_flashdata(
                'error',
                'Pengguna tidak dapat dihapus karena masih menjadi leader dari ' . $used_as_leader . ' tim!'
            );
            redirect('admin/pengguna');
        }

        // Cek sebagai supervisor tim
        $used_as_supervisor = $this->PenggunaModel->countTeamSupervisorUsage($id);
        if ($used_as_supervisor > 0) {
            $this->session->set_flashdata(
                'error',
                'Pengguna tidak dapat dihapus karena masih menjadi supervisor dari ' . $used_as_supervisor . ' tim!'
            );
            redirect('admin/pengguna');
        }

        // Hapus data pengguna
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna!');
        }
        
        redirect('admin/pengguna');
    }

    /**
     * Validasi NIK agar tetap unik saat edit
     */
    public function check_nik_unique($nik, $id)
    {
        if ($this->PenggunaModel->nikExists($nik, $id)) {
            $this->form_validation->set_message('check_nik_unique', 'NIK sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Validasi email agar tetap unik saat edit
     */
    public function check_email_unique($email, $id)
    {
        if ($this->PenggunaModel->emailExists($email, $id)) {
            $this->form_validation->set_message('check_email_unique', 'Email sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
}
