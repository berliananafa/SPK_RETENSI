<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Manajemen Kanal (Admin)
 *
 * Digunakan untuk mengelola data kanal, meliputi:
 * - Menampilkan daftar kanal
 * - Menambah, mengubah, dan menghapus kanal
 * - Validasi penggunaan kanal pada entitas lain
 */
class KanalController extends Admin_Controller
{
    /**
     * Constructor
     *
     * Memanggil constructor parent dan memuat model Kanal.
     */
    public function __construct()
    {
        parent::__construct();

        // Load model Kanal dengan alias
        $this->load->model('KanalModel', 'Kanal');
    }

    /**
     * Halaman daftar Kanal
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Manajemen Kanal');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kanal']
        ]);
        
        // Aktifkan DataTables dan SweetAlert
        enable_datatables();
        enable_sweetalert();
        
        // Ambil seluruh data kanal (diurutkan)
        $data['channels'] = $this->Kanal->getAllOrdered();
        
        // Render halaman index
        render_layout('admin/kanal/index', $data);
    }

    /**
     * Halaman form tambah Kanal
     */
    public function create()
    {
        set_page_title('Tambah Kanal');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kanal', 'url' => base_url('admin/kanal')],
            ['title' => 'Tambah']
        ]);
        
        render_layout('admin/kanal/create');
    }

    /**
     * Proses penyimpanan Kanal baru
     */
    public function store()
    {
        // Validasi input nama kanal
        $this->form_validation->set_rules(
            'nama_kanal',
            'Nama Kanal',
            'required|trim|min_length[3]|is_unique[kanal.nama_kanal]'
        );

        // Jika validasi gagal, kembali ke form create
        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            // Data yang akan disimpan
            $data = [
                'nama_kanal' => $this->input->post('nama_kanal', true),
            ];

            // Simpan ke database
            if ($this->Kanal->create($data)) {
                $this->session->set_flashdata('success', 'Kanal berhasil ditambahkan!');
                redirect('admin/kanal');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kanal!');
                redirect('admin/kanal/create');
            }
        }
    }

    /**
     * Halaman form edit Kanal
     */
    public function edit($id)
    {
        set_page_title('Edit Kanal');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kanal', 'url' => base_url('admin/kanal')],
            ['title' => 'Edit']
        ]);
        
        // Ambil data kanal berdasarkan ID
        $data['channel'] = $this->Kanal->find($id);
        
        // Jika data tidak ditemukan
        if (empty($data['channel'])) {
            $this->session->set_flashdata('error', 'Data kanal tidak ditemukan!');
            redirect('admin/kanal');
        }
        
        render_layout('admin/kanal/edit', $data);
    }

    /**
     * Proses update data Kanal
     */
    public function update($id)
    {
        // Ambil data kanal lama
        $channel = $this->Kanal->find($id);

        if (empty($channel)) {
            $this->session->set_flashdata('error', 'Data kanal tidak ditemukan!');
            redirect('admin/kanal');
        }

        // Validasi nama kanal (unik, kecuali ID saat ini)
        $this->form_validation->set_rules(
            'nama_kanal',
            'Nama Kanal',
            'required|trim|min_length[3]|callback_check_name_unique['.$id.']'
        );

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            // Data update
            $data = [
                'nama_kanal' => $this->input->post('nama_kanal', true),
            ];

            if ($this->Kanal->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Kanal berhasil diupdate!');
                redirect('admin/kanal');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate kanal!');
                redirect('admin/kanal/edit/'.$id);
            }
        }
    }

    /**
     * Hapus data Kanal
     *
     * Kanal tidak dapat dihapus jika masih digunakan
     * oleh Customer Service atau Supervisor Scope.
     */
    public function delete($id)
    {
        $channel = $this->Kanal->find($id);

        if (empty($channel)) {
            $this->session->set_flashdata('error', 'Data kanal tidak ditemukan!');
            redirect('admin/kanal');
        }

        /**
         * Cek apakah kanal masih digunakan
         * oleh tabel customer_service
         */
        $this->db->where('id_kanal', $id);
        $used_in_cs = $this->db->count_all_results('customer_service');
        
        if ($used_in_cs > 0) {
            $this->session->set_flashdata(
                'error',
                'Kanal tidak dapat dihapus karena masih digunakan oleh ' . $used_in_cs . ' Customer Service!'
            );
            redirect('admin/kanal');
        }

        /**
         * Cek apakah kanal masih digunakan
         * pada tabel supervisor_scope
         */
        $this->db->where('id_kanal', $id);
        $used_in_scope = $this->db->count_all_results('supervisor_scope');
        
        if ($used_in_scope > 0) {
            $this->session->set_flashdata(
                'error',
                'Kanal tidak dapat dihapus karena masih digunakan dalam ' . $used_in_scope . ' scope supervisor!'
            );
            redirect('admin/kanal');
        }

        // Proses hapus data kanal
        if ($this->Kanal->deleteById($id)) {
            $this->session->set_flashdata('success', 'Kanal berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kanal!');
        }
        
        redirect('admin/kanal');
    }

    /**
     * Callback validasi nama kanal unik
     *
     * Digunakan saat proses update data.
     */
    public function check_name_unique($namaKanal, $id)
    {
        if ($this->Kanal->nameExists($namaKanal, $id)) {
            $this->form_validation->set_message(
                'check_name_unique',
                'Nama kanal sudah digunakan!'
            );
            return FALSE;
        }
        return TRUE;
    }
}
