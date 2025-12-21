<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubKriteriaController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SubKriteriaModel');
        $this->load->model('KriteriaModel');
    }

    public function index()
    {
        set_page_title('Sub Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Sub Kriteria']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();
        $data['all_kriteria'] = $this->KriteriaModel->getAllOrdered();
        
        render_layout('admin/sub_kriteria/index', $data);
    }

    public function create()
    {
        set_page_title('Tambah Sub Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Sub Kriteria', 'url' => base_url('admin/sub-kriteria')],
            ['title' => 'Tambah']
        ]);
        
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();
        render_layout('admin/sub_kriteria/create', $data);
    }

    public function store()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_kriteria', 'Kriteria', 'required|numeric');
        $this->form_validation->set_rules('nama_sub_kriteria', 'Nama Sub Kriteria', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('bobot_sub', 'Bobot Sub', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'id_kriteria' => $this->input->post('id_kriteria', true),
                'nama_sub_kriteria' => $this->input->post('nama_sub_kriteria', true),
                'bobot_sub' => $this->input->post('bobot_sub', true),
                'keterangan' => $this->input->post('keterangan', true)
            ];

            if ($this->SubKriteriaModel->create($data)) {
                $this->session->set_flashdata('success', 'Sub Kriteria berhasil ditambahkan!');
                redirect('admin/sub-kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan sub kriteria!');
                redirect('admin/sub-kriteria/create');
            }
        }
    }

    public function edit($id)
    {
        $data['sub_kriteria'] = $this->SubKriteriaModel->getByIdWithDetails($id);
        
        if (empty($data['sub_kriteria'])) {
            $this->session->set_flashdata('error', 'Data sub kriteria tidak ditemukan!');
            redirect('admin/sub-kriteria');
        }
        
        set_page_title('Edit Sub Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Sub Kriteria', 'url' => base_url('admin/sub-kriteria')],
            ['title' => 'Edit']
        ]);
        
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();
        render_layout('admin/sub_kriteria/edit', $data);
    }

    public function update($id)
    {
        $sub_kriteria = $this->SubKriteriaModel->find($id);
        if (empty($sub_kriteria)) {
            $this->session->set_flashdata('error', 'Data sub kriteria tidak ditemukan!');
            redirect('admin/sub-kriteria');
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_kriteria', 'Kriteria', 'required|numeric');
        $this->form_validation->set_rules('nama_sub_kriteria', 'Nama Sub Kriteria', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('bobot_sub', 'Bobot Sub', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'id_kriteria' => $this->input->post('id_kriteria', true),
                'nama_sub_kriteria' => $this->input->post('nama_sub_kriteria', true),
                'bobot_sub' => $this->input->post('bobot_sub', true),
                'keterangan' => $this->input->post('keterangan', true)
            ];

            if ($this->SubKriteriaModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Sub Kriteria berhasil diupdate!');
                redirect('admin/sub-kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate sub kriteria!');
                redirect('admin/sub-kriteria/edit/'.$id);
            }
        }
    }

    public function delete($id)
    {
        $sub_kriteria = $this->SubKriteriaModel->find($id);
        if (empty($sub_kriteria)) {
            $this->session->set_flashdata('error', 'Data sub kriteria tidak ditemukan!');
            redirect('admin/sub-kriteria');
        }

        if ($this->SubKriteriaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Sub Kriteria berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus sub kriteria!');
        }
        
        redirect('admin/sub-kriteria');
    }
}
