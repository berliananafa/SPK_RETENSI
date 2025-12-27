<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Produk
 * Mengelola CRUD data produk pada halaman admin
 */
class ProdukController extends Admin_Controller
{
    /**
     * Constructor
     * Load model Produk
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProdukModel', 'Produk');
    }

    /**
     * Halaman daftar produk
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Manajemen Produk');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Produk']
        ]);
        
        // Aktifkan DataTables & SweetAlert
        enable_datatables();
        enable_sweetalert();
        
        // Ambil seluruh data produk yang sudah diurutkan
        $data['products'] = $this->Produk->getAllOrdered();
        
        // Render halaman index produk
        render_layout('admin/produk/index', $data);
    }

    /**
     * Halaman form tambah produk
     */
    public function create()
    {
        set_page_title('Tambah Produk');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Produk', 'url' => base_url('admin/produk')],
            ['title' => 'Tambah']
        ]);
        
        render_layout('admin/produk/create');
    }

    /**
     * Proses simpan data produk baru
     */
    public function store()
    {
        // Validasi input form
        $this->form_validation->set_rules('sku_produk', 'SKU Produk', 'required|trim|is_unique[produk.sku_produk]');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        // Jika validasi gagal, kembali ke form create
        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            // Data produk dari input user
            $data = [
                'sku_produk'  => $this->input->post('sku_produk', true),
                'nama_produk' => $this->input->post('nama_produk', true),
                'deskripsi'   => $this->input->post('deskripsi', true),
            ];

            /**
             * Proses upload gambar produk (jika ada)
             */
            if (!empty($_FILES['gambar']['name'])) {
                $upload = $this->_upload_image();
                if ($upload['status']) {
                    $data['gambar'] = $upload['file_name'];
                } else {
                    // Jika upload gagal
                    $this->session->set_flashdata('error', $upload['message']);
                    redirect('admin/produk/create');
                }
            }

            // Simpan ke database
            if ($this->Produk->create($data)) {
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk!');
                redirect('admin/produk/create');
            }
        }
    }

    /**
     * Halaman form edit produk
     */
    public function edit($id)
    {
        set_page_title('Edit Produk');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Produk', 'url' => base_url('admin/produk')],
            ['title' => 'Edit']
        ]);
        
        // Ambil data produk berdasarkan ID
        $data['product'] = $this->Produk->find($id);
        
        // Jika produk tidak ditemukan
        if (empty($data['product'])) {
            $this->session->set_flashdata('error', 'Data produk tidak ditemukan!');
            redirect('admin/produk');
        }
        
        render_layout('admin/produk/edit', $data);
    }

    /**
     * Proses update data produk
     */
    public function update($id)
    {
        // Ambil data produk lama
        $product = $this->Produk->find($id);
        if (empty($product)) {
            $this->session->set_flashdata('error', 'Data produk tidak ditemukan!');
            redirect('admin/produk');
        }

        // Validasi input
        $this->form_validation->set_rules('sku_produk', 'SKU Produk', 'required|trim|callback_check_sku_unique['.$id.']');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'sku_produk'  => $this->input->post('sku_produk', true),
                'nama_produk' => $this->input->post('nama_produk', true),
                'deskripsi'   => $this->input->post('deskripsi', true),
            ];

            /**
             * Upload gambar baru (jika diubah)
             */
            if (!empty($_FILES['gambar']['name'])) {
                $upload = $this->_upload_image();
                if ($upload['status']) {
                    // Hapus gambar lama
                    if (!empty($product->gambar)) {
                        $old_file = FCPATH . 'uploads/produk/' . $product->gambar;
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                    $data['gambar'] = $upload['file_name'];
                } else {
                    $this->session->set_flashdata('error', $upload['message']);
                    redirect('admin/produk/edit/'.$id);
                }
            }

            // Update ke database
            if ($this->Produk->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Produk berhasil diupdate!');
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate produk!');
                redirect('admin/produk/edit/'.$id);
            }
        }
    }

    /**
     * Hapus data produk
     */
    public function delete($id)
    {
        $product = $this->Produk->find($id);
        if (empty($product)) {
            $this->session->set_flashdata('error', 'Data produk tidak ditemukan!');
            redirect('admin/produk');
        }

        // Cek apakah produk masih digunakan oleh CS
        $this->db->where('id_produk', $id);
        $used_count = $this->db->count_all_results('customer_service');
        
        if ($used_count > 0) {
            $this->session->set_flashdata(
                'error',
                'Produk tidak dapat dihapus karena masih digunakan oleh ' . $used_count . ' Customer Service!'
            );
            redirect('admin/produk');
        }

        // Hapus file gambar produk
        if (!empty($product->gambar)) {
            $file_path = FCPATH . 'uploads/produk/' . $product->gambar;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data dari database
        if ($this->Produk->deleteById($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk!');
        }
        
        redirect('admin/produk');
    }

    /**
     * Validasi SKU agar tetap unik saat update
     */
    public function check_sku_unique($sku, $id)
    {
        if ($this->Produk->skuExists($sku, $id)) {
            $this->form_validation->set_message('check_sku_unique', 'SKU sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Fungsi private untuk upload gambar produk
     */
    private function _upload_image()
    {
        $config['upload_path']   = FCPATH . 'uploads/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048; // Maksimal 2MB
        $config['encrypt_name']  = TRUE;

        // Buat folder upload jika belum ada
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return [
                'status'    => TRUE,
                'file_name' => $this->upload->data('file_name')
            ];
        } else {
            return [
                'status'  => FALSE,
                'message' => $this->upload->display_errors('', '')
            ];
        }
    }
}
