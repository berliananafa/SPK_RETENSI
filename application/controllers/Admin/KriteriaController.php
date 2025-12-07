<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KriteriaController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KriteriaModel');
    }

    public function index()
    {
        set_page_title('Daftar Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kriteria']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();
        render_layout('admin/kriteria/index', $data);
    }

    public function create()
    {
        set_page_title('Tambah Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kriteria', 'url' => base_url('admin/kriteria')],
            ['title' => 'Tambah']
        ]);
        
        render_layout('admin/kriteria/create');
    }

    public function store()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('kode_kriteria', 'Kode Kriteria', 'required|trim|callback_check_kode_unique');
        $this->form_validation->set_rules('nama_kriteria', 'Nama Kriteria', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('jenis_kriteria', 'Jenis Kriteria', 'required|in_list[core_factor,secondary_factor]');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'kode_kriteria' => $this->input->post('kode_kriteria', true),
                'nama_kriteria' => $this->input->post('nama_kriteria', true),
                'jenis_kriteria' => $this->input->post('jenis_kriteria', true),
                'bobot' => $this->input->post('bobot', true),
                'deskripsi' => $this->input->post('deskripsi', true)
            ];

            if ($this->KriteriaModel->create($data)) {
                $this->session->set_flashdata('success', 'Kriteria berhasil ditambahkan!');
                redirect('admin/kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kriteria!');
                redirect('admin/kriteria/create');
            }
        }
    }

    public function edit($id)
    {
        $data['kriteria'] = $this->KriteriaModel->find($id);
        
        if (empty($data['kriteria'])) {
            $this->session->set_flashdata('error', 'Data kriteria tidak ditemukan!');
            redirect('admin/kriteria');
        }
        
        set_page_title('Edit Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kriteria', 'url' => base_url('admin/kriteria')],
            ['title' => 'Edit']
        ]);
        
        render_layout('admin/kriteria/edit', $data);
    }

    public function update($id)
    {
        $kriteria = $this->KriteriaModel->find($id);
        if (empty($kriteria)) {
            $this->session->set_flashdata('error', 'Data kriteria tidak ditemukan!');
            redirect('admin/kriteria');
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('kode_kriteria', 'Kode Kriteria', 'required|trim|callback_check_kode_unique['.$id.']');
        $this->form_validation->set_rules('nama_kriteria', 'Nama Kriteria', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('jenis_kriteria', 'Jenis Kriteria', 'required|in_list[core_factor,secondary_factor]');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'kode_kriteria' => $this->input->post('kode_kriteria', true),
                'nama_kriteria' => $this->input->post('nama_kriteria', true),
                'jenis_kriteria' => $this->input->post('jenis_kriteria', true),
                'bobot' => $this->input->post('bobot', true),
                'deskripsi' => $this->input->post('deskripsi', true)
            ];

            if ($this->KriteriaModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Kriteria berhasil diupdate!');
                redirect('admin/kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate kriteria!');
                redirect('admin/kriteria/edit/'.$id);
            }
        }
    }

    public function delete($id)
    {
        $kriteria = $this->KriteriaModel->find($id);
        if (empty($kriteria)) {
            $this->session->set_flashdata('error', 'Data kriteria tidak ditemukan!');
            redirect('admin/kriteria');
        }

        // Check if kriteria has sub_kriteria
        $this->db->where('id_kriteria', $id);
        $has_sub = $this->db->count_all_results('sub_kriteria') > 0;

        if ($has_sub) {
            $this->session->set_flashdata('error', 'Tidak dapat menghapus kriteria! Masih ada sub kriteria terkait.');
            redirect('admin/kriteria');
        }

        if ($this->KriteriaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Kriteria berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kriteria!');
        }
        
        redirect('admin/kriteria');
    }

    public function check_kode_unique($kode, $excludeId = null)
    {
        if ($this->KriteriaModel->codeExists($kode, $excludeId)) {
            $this->form_validation->set_message('check_kode_unique', 'Kode kriteria sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
}
