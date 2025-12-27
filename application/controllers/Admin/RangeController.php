<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk mengelola Range Nilai
 * Digunakan dalam penilaian berdasarkan sub kriteria
 */
class RangeController extends Admin_Controller
{
    /**
     * Constructor
     * Load semua model yang dibutuhkan
     */
    public function __construct()
    {
        parent::__construct();

        // Model utama untuk range nilai
        $this->load->model('RangeModel');

        // Model pendukung
        $this->load->model('SubKriteriaModel');
        $this->load->model('KriteriaModel');
    }

    /**
     * Halaman utama daftar range nilai
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Range Nilai');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Range Nilai']
        ]);
        
        // Aktifkan DataTables dan SweetAlert
        enable_datatables();
        enable_sweetalert();
        
        // Ambil seluruh data range beserta detailnya
        $data['ranges'] = $this->RangeModel->getAllWithDetails();

        // Ambil seluruh kriteria
        $data['all_kriteria'] = $this->KriteriaModel->getAllOrdered();

        // Ambil seluruh sub kriteria beserta relasinya
        $data['all_sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();
        
        // Render halaman index
        render_layout('admin/range/index', $data);
    }

    /**
     * Halaman form tambah range nilai
     */
    public function create()
    {
        set_page_title('Tambah Range Nilai');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Range Nilai', 'url' => base_url('admin/range')],
            ['title' => 'Tambah']
        ]);
        
        // Ambil semua sub kriteria untuk dropdown
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();

        render_layout('admin/range/create', $data);
    }

    /**
     * Proses simpan data range nilai
     */
    public function store()
    {
        // Load library validasi form
        $this->load->library('form_validation');
        
        // Aturan validasi input
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|numeric');
        $this->form_validation->set_rules('batas_bawah', 'Batas Bawah', 'trim|numeric');
        $this->form_validation->set_rules('batas_atas', 'Batas Atas', 'trim|numeric|callback_check_batas_valid');
        $this->form_validation->set_rules('nilai_range', 'Nilai Range', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        // Jika validasi gagal, kembali ke form create
        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            // Ambil batas bawah & atas (boleh null)
            $batas_bawah = $this->input->post('batas_bawah', true);
            $batas_atas  = $this->input->post('batas_atas', true);
            
            // Data yang akan disimpan
            $data = [
                'id_sub_kriteria' => $this->input->post('id_sub_kriteria', true),
                'batas_bawah'     => ($batas_bawah !== '' && $batas_bawah !== null) ? $batas_bawah : null,
                'batas_atas'      => ($batas_atas !== '' && $batas_atas !== null) ? $batas_atas : null,
                'nilai_range'     => $this->input->post('nilai_range', true),
                'keterangan'      => $this->input->post('keterangan', true)
            ];

            // Cek apakah range bertabrakan (overlap) dengan data lain
            if ($this->RangeModel->checkOverlap(
                $data['id_sub_kriteria'],
                $data['batas_bawah'],
                $data['batas_atas']
            )) {
                $this->session->set_flashdata(
                    'error',
                    'Range nilai tumpang tindih dengan range lain pada sub kriteria yang sama!'
                );
                redirect('admin/range/create');
                return;
            }

            // Simpan ke database
            if ($this->RangeModel->create($data)) {
                $this->session->set_flashdata('success', 'Range nilai berhasil ditambahkan!');
                redirect('admin/range');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan range nilai!');
                redirect('admin/range/create');
            }
        }
    }

    /**
     * Halaman edit range nilai
     */
    public function edit($id)
    {
        // Ambil data range berdasarkan ID
        $data['range'] = $this->RangeModel->getByIdWithDetails($id);
        
        // Jika data tidak ditemukan
        if (empty($data['range'])) {
            $this->session->set_flashdata('error', 'Data range tidak ditemukan!');
            redirect('admin/range');
        }
        
        set_page_title('Edit Range Nilai');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Range Nilai', 'url' => base_url('admin/range')],
            ['title' => 'Edit']
        ]);
        
        // Ambil semua sub kriteria
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();

        render_layout('admin/range/edit', $data);
    }

    /**
     * Proses update range nilai
     */
    public function update($id)
    {
        // Cek apakah data range ada
        $range = $this->RangeModel->find($id);
        if (empty($range)) {
            $this->session->set_flashdata('error', 'Data range tidak ditemukan!');
            redirect('admin/range');
        }

        $this->load->library('form_validation');
        
        // Aturan validasi
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|numeric');
        $this->form_validation->set_rules('batas_bawah', 'Batas Bawah', 'trim|numeric');
        $this->form_validation->set_rules('batas_atas', 'Batas Atas', 'trim|numeric|callback_check_batas_valid');
        $this->form_validation->set_rules('nilai_range', 'Nilai Range', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        // Jika validasi gagal
        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $batas_bawah = $this->input->post('batas_bawah', true);
            $batas_atas  = $this->input->post('batas_atas', true);
            
            $data = [
                'id_sub_kriteria' => $this->input->post('id_sub_kriteria', true),
                'batas_bawah'     => ($batas_bawah !== '' && $batas_bawah !== null) ? $batas_bawah : null,
                'batas_atas'      => ($batas_atas !== '' && $batas_atas !== null) ? $batas_atas : null,
                'nilai_range'     => $this->input->post('nilai_range', true),
                'keterangan'      => $this->input->post('keterangan', true)
            ];

            // Cek overlap (kecuali data yang sedang diedit)
            if ($this->RangeModel->checkOverlap(
                $data['id_sub_kriteria'],
                $data['batas_bawah'],
                $data['batas_atas'],
                $id
            )) {
                $this->session->set_flashdata(
                    'error',
                    'Range nilai tumpang tindih dengan range lain pada sub kriteria yang sama!'
                );
                redirect('admin/range/edit/'.$id);
                return;
            }

            // Update data
            if ($this->RangeModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Range nilai berhasil diupdate!');
                redirect('admin/range');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate range nilai!');
                redirect('admin/range/edit/'.$id);
            }
        }
    }

    /**
     * Hapus data range nilai
     */
    public function delete($id)
    {
        // Cek data range
        $range = $this->RangeModel->find($id);
        if (empty($range)) {
            $this->session->set_flashdata('error', 'Data range tidak ditemukan!');
            redirect('admin/range');
        }

        // Proses hapus
        if ($this->RangeModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Range nilai berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus range nilai!');
        }
        
        redirect('admin/range');
    }

    /**
     * Callback validasi batas bawah & batas atas
     * - Minimal salah satu harus diisi
     * - Jika dua-duanya diisi, batas atas >= batas bawah
     */
    public function check_batas_valid($batas_atas)
    {
        $batas_bawah = $this->input->post('batas_bawah');
        
        // Jika dua-duanya kosong
        if (($batas_bawah === '' || $batas_bawah === null) &&
            ($batas_atas === '' || $batas_atas === null)) {

            $this->form_validation->set_message(
                'check_batas_valid',
                'Minimal salah satu batas (bawah/atas) harus diisi!'
            );
            return FALSE;
        }
        
        // Jika dua-duanya diisi, batas atas tidak boleh < batas bawah
        if (($batas_bawah !== '' && $batas_bawah !== null) &&
            ($batas_atas !== '' && $batas_atas !== null)) {

            if ($batas_atas < $batas_bawah) {
                $this->form_validation->set_message(
                    'check_batas_valid',
                    'Batas atas tidak boleh lebih kecil dari batas bawah!'
                );
                return FALSE;
            }
        }
        
        return TRUE;
    }
}
