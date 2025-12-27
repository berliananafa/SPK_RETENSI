<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Kriteria
 * Mengelola data kriteria untuk proses penilaian / ranking
 */
class KriteriaController extends Admin_Controller
{
    /**
     * Constructor
     * Load model yang dibutuhkan
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KriteriaModel');
    }

    /**
     * Halaman daftar kriteria
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Daftar Kriteria');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kriteria']
        ]);
        
        // Aktifkan DataTables dan SweetAlert
        enable_datatables();
        enable_sweetalert();
        
        // Ambil seluruh data kriteria (sudah terurut)
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();

        // Render halaman index kriteria
        render_layout('admin/kriteria/index', $data);
    }

    /**
     * Halaman form tambah kriteria
     */
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

    /**
     * Proses simpan data kriteria baru
     */
    public function store()
    {
        // Load library validasi
        $this->load->library('form_validation');
        
        /**
         * Aturan validasi input
         */
        $this->form_validation->set_rules(
            'kode_kriteria',
            'Kode Kriteria',
            'required|trim|callback_check_kode_unique'
        );
        $this->form_validation->set_rules(
            'nama_kriteria',
            'Nama Kriteria',
            'required|trim|min_length[3]'
        );
        $this->form_validation->set_rules(
            'jenis_kriteria',
            'Jenis Kriteria',
            'required|in_list[core_factor,secondary_factor]'
        );
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        // Jika validasi gagal
        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            /**
             * Penentuan bobot otomatis
             * Core Factor  = 90
             * Secondary Factor = 10
             */
            $bobot = ($this->input->post('jenis_kriteria', true) === 'core_factor')
                ? 90
                : 10;
            
            // Data yang akan disimpan
            $data = [
                'kode_kriteria'  => $this->input->post('kode_kriteria', true),
                'nama_kriteria'  => $this->input->post('nama_kriteria', true),
                'jenis_kriteria' => $this->input->post('jenis_kriteria', true),
                'bobot'          => $bobot,
                'deskripsi'      => $this->input->post('deskripsi', true)
            ];

            // Simpan ke database
            if ($this->KriteriaModel->create($data)) {
                $this->session->set_flashdata('success', 'Kriteria berhasil ditambahkan!');
                redirect('admin/kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kriteria!');
                redirect('admin/kriteria/create');
            }
        }
    }

    /**
     * Halaman form edit kriteria
     */
    public function edit($id)
    {
        // Ambil data kriteria berdasarkan ID
        $data['kriteria'] = $this->KriteriaModel->find($id);
        
        // Jika data tidak ditemukan
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

    /**
     * Proses update data kriteria
     */
    public function update($id)
    {
        // Cek apakah data kriteria ada
        $kriteria = $this->KriteriaModel->find($id);
        if (empty($kriteria)) {
            $this->session->set_flashdata('error', 'Data kriteria tidak ditemukan!');
            redirect('admin/kriteria');
        }

        // Load library validasi
        $this->load->library('form_validation');
        
        /**
         * Aturan validasi input
         */
        $this->form_validation->set_rules(
            'kode_kriteria',
            'Kode Kriteria',
            'required|trim|callback_check_kode_unique['.$id.']'
        );
        $this->form_validation->set_rules(
            'nama_kriteria',
            'Nama Kriteria',
            'required|trim|min_length[3]'
        );
        $this->form_validation->set_rules(
            'jenis_kriteria',
            'Jenis Kriteria',
            'required|in_list[core_factor,secondary_factor]'
        );
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        // Jika validasi gagal
        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            /**
             * Penentuan bobot otomatis berdasarkan jenis kriteria
             */
            $bobot = ($this->input->post('jenis_kriteria', true) === 'core_factor')
                ? 90
                : 10;
            
            // Data update
            $data = [
                'kode_kriteria'  => $this->input->post('kode_kriteria', true),
                'nama_kriteria'  => $this->input->post('nama_kriteria', true),
                'jenis_kriteria' => $this->input->post('jenis_kriteria', true),
                'bobot'          => $bobot,
                'deskripsi'      => $this->input->post('deskripsi', true)
            ];

            // Update ke database
            if ($this->KriteriaModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Kriteria berhasil diupdate!');
                redirect('admin/kriteria');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate kriteria!');
                redirect('admin/kriteria/edit/'.$id);
            }
        }
    }

    /**
     * Proses hapus kriteria
     */
    public function delete($id)
    {
        // Cek apakah data kriteria ada
        $kriteria = $this->KriteriaModel->find($id);
        if (empty($kriteria)) {
            $this->session->set_flashdata('error', 'Data kriteria tidak ditemukan!');
            redirect('admin/kriteria');
        }

        /**
         * Cek apakah kriteria masih memiliki sub kriteria
         * Jika masih ada, maka kriteria tidak boleh dihapus
         */
        $this->db->where('id_kriteria', $id);
        $has_sub = $this->db->count_all_results('sub_kriteria') > 0;

        if ($has_sub) {
            $this->session->set_flashdata(
                'error',
                'Tidak dapat menghapus kriteria! Masih ada sub kriteria terkait.'
            );
            redirect('admin/kriteria');
        }

        // Hapus data kriteria
        if ($this->KriteriaModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Kriteria berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kriteria!');
        }
        
        redirect('admin/kriteria');
    }

    /**
     * Validasi custom untuk memastikan kode kriteria tetap unik
     * Digunakan saat tambah & edit
     */
    public function check_kode_unique($kode, $excludeId = null)
    {
        if ($this->KriteriaModel->codeExists($kode, $excludeId)) {
            $this->form_validation->set_message(
                'check_kode_unique',
                'Kode kriteria sudah digunakan!'
            );
            return FALSE;
        }
        return TRUE;
    }
}
