<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerServiceController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'CustomerServiceModel' => 'CustomerService',
            'TimModel' => 'Tim',
            'ProdukModel' => 'Produk',
            'KanalModel' => 'Kanal'
        ]);
    }

    /**
     * Display list of customer service
     */
    public function index()
    {
        set_page_title('Manajemen Customer Service');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Customer Service']
        ]);
		enable_datatables();
        enable_sweetalert();

        $data['customer_services'] = $this->CustomerService->getAllWithDetails();
        render_layout('admin/customer_service/index', $data);
    }

    /**
     * Show create customer service form
     */
    public function create()
    {
        set_page_title('Tambah Customer Service');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
            ['title' => 'Tambah']
        ]);

        $data['teams'] = $this->Tim->getAllWithDetails();
        $data['products'] = $this->Produk->all();
        $data['channels'] = $this->Kanal->all();

        render_layout('admin/customer_service/create', $data);
    }

    /**
     * Store new customer service
     */
    public function store()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique');
        $this->form_validation->set_rules('nama_cs', 'Nama Customer Service', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('id_tim', 'Tim', 'required|numeric');
        $this->form_validation->set_rules('id_produk', 'Produk', 'required|numeric');
        $this->form_validation->set_rules('id_kanal', 'Kanal', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        $data = [
            'nik' => $this->input->post('nik', true),
            'nama_cs' => $this->input->post('nama_cs', true),
            'id_tim' => $this->input->post('id_tim', true),
            'id_produk' => $this->input->post('id_produk', true),
            'id_kanal' => $this->input->post('id_kanal', true),
        ];

        if ($this->CustomerService->create($data)) {
            $this->session->set_flashdata('success', 'Customer Service berhasil ditambahkan!');
            redirect('admin/customer-service');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan Customer Service!');
            $this->create();
        }
    }

    /**
     * Show edit customer service form
     */
    public function edit($id)
    {
        $cs = $this->CustomerService->getByIdWithDetails($id);
        
        if (!$cs) {
            $this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
            redirect('admin/customer-service');
        }

        set_page_title('Edit Customer Service');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
            ['title' => 'Edit']
        ]);

        $data['cs'] = $cs;
        $data['teams'] = $this->Tim->getAllWithDetails();
        $data['products'] = $this->Produk->all();
        $data['channels'] = $this->Kanal->all();

        render_layout('admin/customer_service/edit', $data);
    }

    /**
     * Update customer service
     */
    public function update($id)
    {
        $cs = $this->CustomerService->find($id);
        
        if (!$cs) {
            $this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
            redirect('admin/customer-service');
        }

        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('nama_cs', 'Nama Customer Service', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('id_tim', 'Tim', 'required|numeric');
        $this->form_validation->set_rules('id_produk', 'Produk', 'required|numeric');
        $this->form_validation->set_rules('id_kanal', 'Kanal', 'required|numeric');

        // Check unique NIK if changed
        if ($this->input->post('nik') !== $cs->nik) {
            $this->form_validation->set_rules('nik', 'NIK', 'required|trim|callback_check_nik_unique');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $data = [
            'nik' => $this->input->post('nik', true),
            'nama_cs' => $this->input->post('nama_cs', true),
            'id_tim' => $this->input->post('id_tim', true),
            'id_produk' => $this->input->post('id_produk', true),
            'id_kanal' => $this->input->post('id_kanal', true),
        ];

        if ($this->CustomerService->updateById($id, $data)) {
            $this->session->set_flashdata('success', 'Customer Service berhasil diperbarui!');
            redirect('admin/customer-service');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui Customer Service!');
            $this->edit($id);
        }
    }

    /**
     * Delete customer service
     */
    public function delete($id)
    {
        $cs = $this->CustomerService->find($id);
        
        if (!$cs) {
            $this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
            redirect('admin/customer-service');
        }

        if ($this->CustomerService->deleteById($id)) {
            $this->session->set_flashdata('success', 'Customer Service berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus Customer Service!');
        }

        redirect('admin/customer-service');
    }

    /**
     * Show customer service detail
     */
    public function detail($id)
    {
        $cs = $this->CustomerService->getByIdWithDetails($id);
        
        if (!$cs) {
            $this->session->set_flashdata('error', 'Customer Service tidak ditemukan!');
            redirect('admin/customer-service');
        }

        set_page_title('Detail Customer Service');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Customer Service', 'url' => base_url('admin/customer-service')],
            ['title' => 'Detail']
        ]);

        $data['cs'] = $cs;

        render_layout('admin/customer_service/detail', $data);
    }

    /**
     * Callback: Check NIK unique
     */
    public function check_nik_unique($nik)
    {
        $id = $this->uri->segment(4); // Get ID from edit URL
        
        if ($this->CustomerService->nikExists($nik, $id)) {
            $this->form_validation->set_message('check_nik_unique', 'NIK sudah digunakan!');
            return FALSE;
        }
        
        return TRUE;
    }
}
