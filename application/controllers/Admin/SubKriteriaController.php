<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Sub Kriteria
 * 
 * Mengelola data sub kriteria yang berelasi dengan kriteria utama
 * (CRUD: tampil, tambah, edit, hapus)
 */
class SubKriteriaController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load model yang dibutuhkan
        $this->load->model('SubKriteriaModel');
        $this->load->model('KriteriaModel');
    }

    /**
     * Menampilkan halaman daftar sub kriteria
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Sub Kriteria');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Sub Kriteria']
        ]);
        
        // Aktifkan plugin DataTables dan SweetAlert
        enable_datatables();
        enable_sweetalert();
        
        // Ambil seluruh data sub kriteria beserta detail kriteria induknya
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();

        // Ambil seluruh data kriteria (digunakan untuk filter / dropdown)
        $data['all_kriteria'] = $this->KriteriaModel->getAllOrdered();
        
        // Render view index
        render_layout('admin/sub_kriteria/index', $data);
    }

    /**
     * Menampilkan form tambah sub kriteria
     */
    public function create()
    {
        set_page_title('Tambah Sub Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Sub Kriteria', 'url' => base_url('admin/sub-kriteria')],
            ['title' => 'Tambah']
        ]);
        
        // Ambil data kriteria untuk dropdown pilihan
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();

        render_layout('admin/sub_kriteria/create', $data);
    }

    /**
     * Proses penyimpanan data sub kriteria
     */
    public function store()
    {
        $this->load->library('form_validation');
        
        // Aturan validasi input
        $this->form_validation->set_rules('id_kriteria', 'Kriteria', 'required|numeric');
        $this->form_validation->set_rules('nama_sub_kriteria', 'Nama Sub Kriteria', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('bobot_sub', 'Bobot Sub', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === FALSE) {
            // Jika validasi gagal, kembali ke form tambah
            $this->create();
        } else {
            // Data yang akan disimpan
            $data = [
                'id_kriteria'        => $this->input->post('id_kriteria', true),
                'nama_sub_kriteria'  => $this->input->post('nama_sub_kriteria', true),
                'bobot_sub'          => $this->input->post('bobot_sub', true),
                'keterangan'         => $this->input->post('keterangan', true)
            ];

            // Simpan data ke database
            if ($this->SubKriteriaModel->create($data)) {
                $this->session->set_flashdata('success', 'Sub Kriteria berhasil ditambahkan!');
                redirect('admin/sub-kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan sub kriteria!');
                redirect('admin/sub-kriteria/create');
            }
        }
    }

    /**
     * Menampilkan form edit sub kriteria
     */
    public function edit($id)
    {
        // Ambil data sub kriteria berdasarkan ID
        $data['sub_kriteria'] = $this->SubKriteriaModel->getByIdWithDetails($id);
        
        // Jika data tidak ditemukan
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
        
        // Ambil data kriteria untuk dropdown
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();

        render_layout('admin/sub_kriteria/edit', $data);
    }

    /**
     * Proses update data sub kriteria
     */
    public function update($id)
    {
        // Cek apakah data sub kriteria ada
        $sub_kriteria = $this->SubKriteriaModel->find($id);
        if (empty($sub_kriteria)) {
            $this->session->set_flashdata('error', 'Data sub kriteria tidak ditemukan!');
            redirect('admin/sub-kriteria');
        }

        $this->load->library('form_validation');
        
        // Aturan validasi input
        $this->form_validation->set_rules('id_kriteria', 'Kriteria', 'required|numeric');
        $this->form_validation->set_rules('nama_sub_kriteria', 'Nama Sub Kriteria', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('bobot_sub', 'Bobot Sub', 'required|numeric|greater_than[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === FALSE) {
            // Jika validasi gagal, kembali ke halaman edit
            $this->edit($id);
        } else {
            // Data update
            $data = [
                'id_kriteria'        => $this->input->post('id_kriteria', true),
                'nama_sub_kriteria'  => $this->input->post('nama_sub_kriteria', true),
                'bobot_sub'          => $this->input->post('bobot_sub', true),
                'keterangan'         => $this->input->post('keterangan', true)
            ];

            // Update data ke database
            if ($this->SubKriteriaModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Sub Kriteria berhasil diupdate!');
                redirect('admin/sub-kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate sub kriteria!');
                redirect('admin/sub-kriteria/edit/'.$id);
            }
        }
    }

    /**
     * Menghapus data sub kriteria
     */
    public function delete($id)
    {
        // Cek apakah data sub kriteria ada
        $sub_kriteria = $this->SubKriteriaModel->find($id);
        if (empty($sub_kriteria)) {
            $this->session->set_flashdata('error', 'Data sub kriteria tidak ditemukan!');
            redirect('admin/sub-kriteria');
        }

        // Proses hapus data
        if ($this->SubKriteriaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Sub Kriteria berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus sub kriteria!');
        }
        
        redirect('admin/sub-kriteria');
    }
}
