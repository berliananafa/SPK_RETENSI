<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenggunaController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenggunaModel');
        $this->load->library('form_validation');
    }

    public function index()
    {
        set_page_title('Daftar Pengguna');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Pengguna']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['users'] = $this->PenggunaModel->all();
        
        render_layout('admin/pengguna/index', $data);
    }

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

    public function store()
    {
        // Set validation rules
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|is_unique[pengguna.nik]');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[pengguna.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('level', 'Level', 'required|in_list[admin,junior_manager,supervisor,leader]');

        if ($this->form_validation->run() === FALSE) {
            // Validation failed, reload form
            $this->create();
        } else {
            // Prepare data
            $data = [
                'nik'            => $this->input->post('nik', true),
                'nama_pengguna'  => $this->input->post('nama_pengguna', true),
                'email'          => $this->input->post('email', true),
                'password'       => $this->input->post('password', true),
                'level'          => $this->input->post('level', true),
            ];

            // Insert data
            if ($this->PenggunaModel->create($data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan!');
                redirect('admin/pengguna');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan pengguna!');
                redirect('admin/pengguna/create');
            }
        }
    }

    public function edit($id)
    {
        set_page_title('Edit Pengguna');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Pengguna', 'url' => base_url('admin/pengguna')],
            ['title' => 'Edit']
        ]);
        
        $data['user'] = $this->PenggunaModel->find($id);
        
        if (empty($data['user'])) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan!');
            redirect('admin/pengguna');
        }
        
        render_layout('admin/pengguna/edit', $data);
    }

    public function update($id)
    {
        // Check if user exists
        $user = $this->PenggunaModel->find($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan!');
            redirect('admin/pengguna');
        }

        // Set validation rules
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique['.$id.']');
        $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_check_email_unique['.$id.']');
        $this->form_validation->set_rules('level', 'Level', 'required|in_list[admin,junior_manager,supervisor,leader]');
        
        // Password optional on edit
        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'matches[password]');
        }

        if ($this->form_validation->run() === FALSE) {
            // Validation failed, reload form
            $this->edit($id);
        } else {
            // Prepare data
            $data = [
                'nik'            => $this->input->post('nik', true),
                'nama_pengguna'  => $this->input->post('nama_pengguna', true),
                'email'          => $this->input->post('email', true),
                'level'          => $this->input->post('level', true),
            ];

            // Add password if provided
            if (!empty($this->input->post('password'))) {
                $data['password'] = $this->input->post('password', true);
            }

            // Update data
            if ($this->PenggunaModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Pengguna berhasil diupdate!');
                redirect('admin/pengguna');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate pengguna!');
                redirect('admin/pengguna/edit/'.$id);
            }
        }
    }

    public function delete($id)
    {
        // Check if user exists
        $user = $this->PenggunaModel->find($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan!');
            redirect('admin/pengguna');
        }

        // Prevent deleting own account
        if ($this->session->userdata('id_user') == $id) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus akun sendiri!');
            redirect('admin/pengguna');
        }

        // Check if user is being used as atasan (junior manager/supervisor relationship)
        $this->db->where('id_atasan', $id);
        $used_as_atasan = $this->db->count_all_results('pengguna');
        
        if ($used_as_atasan > 0) {
            $this->session->set_flashdata('error', 'Pengguna tidak dapat dihapus karena masih menjadi atasan dari ' . $used_as_atasan . ' pengguna lain!');
            redirect('admin/pengguna');
        }

        // Check if user is being used in supervisor_scope
        $this->db->where('id_supervisor', $id);
        $used_in_scope = $this->db->count_all_results('supervisor_scope');
        
        if ($used_in_scope > 0) {
            $this->session->set_flashdata('error', 'Pengguna tidak dapat dihapus karena masih memiliki ' . $used_in_scope . ' scope pekerjaan!');
            redirect('admin/pengguna');
        }

        // Check if user is being used as leader in tim
        $this->db->where('id_leader', $id);
        $used_as_leader = $this->db->count_all_results('tim');
        
        if ($used_as_leader > 0) {
            $this->session->set_flashdata('error', 'Pengguna tidak dapat dihapus karena masih menjadi leader dari ' . $used_as_leader . ' tim!');
            redirect('admin/pengguna');
        }

        // Check if user is being used as supervisor in tim
        $this->db->where('id_supervisor', $id);
        $used_as_supervisor = $this->db->count_all_results('tim');
        
        if ($used_as_supervisor > 0) {
            $this->session->set_flashdata('error', 'Pengguna tidak dapat dihapus karena masih menjadi supervisor dari ' . $used_as_supervisor . ' tim!');
            redirect('admin/pengguna');
        }

        // Delete user
        if ($this->PenggunaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Pengguna berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pengguna!');
        }
        
        redirect('admin/pengguna');
    }

    // Custom validation for unique NIK on edit
    public function check_nik_unique($nik, $id)
    {
        if ($this->PenggunaModel->nikExists($nik, $id)) {
            $this->form_validation->set_message('check_nik_unique', 'NIK sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }

    // Custom validation for unique email on edit
    public function check_email_unique($email, $id)
    {
        if ($this->PenggunaModel->emailExists($email, $id)) {
            $this->form_validation->set_message('check_email_unique', 'Email sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
}
