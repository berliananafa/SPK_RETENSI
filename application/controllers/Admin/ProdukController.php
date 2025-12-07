<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdukController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ProdukModel', 'Produk');
    }

    public function index()
    {
        set_page_title('Manajemen Produk');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Produk']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['products'] = $this->Produk->getAllOrdered();
        
        render_layout('admin/produk/index', $data);
    }

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

    public function store()
    {
        $this->form_validation->set_rules('sku_produk', 'SKU Produk', 'required|trim|is_unique[produk.sku_produk]');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'sku_produk'   => $this->input->post('sku_produk', true),
                'nama_produk'  => $this->input->post('nama_produk', true),
                'deskripsi'    => $this->input->post('deskripsi', true),
            ];

            // Handle file upload
            if (!empty($_FILES['gambar']['name'])) {
                $upload = $this->_upload_image();
                if ($upload['status']) {
                    $data['gambar'] = $upload['file_name'];
                } else {
                    $this->session->set_flashdata('error', $upload['message']);
                    redirect('admin/produk/create');
                }
            }

            if ($this->Produk->create($data)) {
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk!');
                redirect('admin/produk/create');
            }
        }
    }

    public function edit($id)
    {
        set_page_title('Edit Produk');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Produk', 'url' => base_url('admin/produk')],
            ['title' => 'Edit']
        ]);
        
        $data['product'] = $this->Produk->find($id);
        
        if (empty($data['product'])) {
            $this->session->set_flashdata('error', 'Data produk tidak ditemukan!');
            redirect('admin/produk');
        }
        
        render_layout('admin/produk/edit', $data);
    }

    public function update($id)
    {
        $product = $this->Produk->find($id);
        if (empty($product)) {
            $this->session->set_flashdata('error', 'Data produk tidak ditemukan!');
            redirect('admin/produk');
        }

        $this->form_validation->set_rules('sku_produk', 'SKU Produk', 'required|trim|callback_check_sku_unique['.$id.']');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'sku_produk'   => $this->input->post('sku_produk', true),
                'nama_produk'  => $this->input->post('nama_produk', true),
                'deskripsi'    => $this->input->post('deskripsi', true),
            ];

            // Handle file upload
            if (!empty($_FILES['gambar']['name'])) {
                $upload = $this->_upload_image();
                if ($upload['status']) {
                    // Delete old image
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

            if ($this->Produk->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Produk berhasil diupdate!');
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate produk!');
                redirect('admin/produk/edit/'.$id);
            }
        }
    }

    public function delete($id)
    {
        $product = $this->Produk->find($id);
        if (empty($product)) {
            $this->session->set_flashdata('error', 'Data produk tidak ditemukan!');
            redirect('admin/produk');
        }

        // Check if product is being used by customer service
        $this->db->where('id_produk', $id);
        $used_count = $this->db->count_all_results('customer_service');
        
        if ($used_count > 0) {
            $this->session->set_flashdata('error', 'Produk tidak dapat dihapus karena masih digunakan oleh ' . $used_count . ' Customer Service!');
            redirect('admin/produk');
        }

        // Delete image file if exists
        if (!empty($product->gambar)) {
            $file_path = FCPATH . 'uploads/produk/' . $product->gambar;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        if ($this->Produk->deleteById($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk!');
        }
        
        redirect('admin/produk');
    }

    public function check_sku_unique($sku, $id)
    {
        if ($this->Produk->skuExists($sku, $id)) {
            $this->form_validation->set_message('check_sku_unique', 'SKU sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }

    private function _upload_image()
    {
        $config['upload_path']   = FCPATH . 'uploads/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        // Create directory if not exists
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
