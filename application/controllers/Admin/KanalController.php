<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KanalController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KanalModel', 'Kanal');
    }

    public function index()
    {
        set_page_title('Manajemen Kanal');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kanal']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['channels'] = $this->Kanal->getAllOrdered();
        
        render_layout('admin/kanal/index', $data);
    }

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

    public function store()
    {
        $this->form_validation->set_rules('nama_kanal', 'Nama Kanal', 'required|trim|min_length[3]|is_unique[kanal.nama_kanal]');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'nama_kanal' => $this->input->post('nama_kanal', true),
            ];

            if ($this->Kanal->create($data)) {
                $this->session->set_flashdata('success', 'Kanal berhasil ditambahkan!');
                redirect('admin/kanal');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kanal!');
                redirect('admin/kanal/create');
            }
        }
    }

    public function edit($id)
    {
        set_page_title('Edit Kanal');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Kanal', 'url' => base_url('admin/kanal')],
            ['title' => 'Edit']
        ]);
        
        $data['channel'] = $this->Kanal->find($id);
        
        if (empty($data['channel'])) {
            $this->session->set_flashdata('error', 'Data kanal tidak ditemukan!');
            redirect('admin/kanal');
        }
        
        render_layout('admin/kanal/edit', $data);
    }

    public function update($id)
    {
        $channel = $this->Kanal_model->find($id);
        if (empty($channel)) {
            $this->session->set_flashdata('error', 'Data kanal tidak ditemukan!');
            redirect('admin/kanal');
        }

        $this->form_validation->set_rules('nama_kanal', 'Nama Kanal', 'required|trim|min_length[3]|callback_check_name_unique['.$id.']');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
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

    public function delete($id)
    {
        $channel = $this->Kanal->find($id);
        if (empty($channel)) {
            $this->session->set_flashdata('error', 'Data kanal tidak ditemukan!');
            redirect('admin/kanal');
        }

        // Check if kanal is being used by customer service
        $this->db->where('id_kanal', $id);
        $used_in_cs = $this->db->count_all_results('customer_service');
        
        if ($used_in_cs > 0) {
            $this->session->set_flashdata('error', 'Kanal tidak dapat dihapus karena masih digunakan oleh ' . $used_in_cs . ' Customer Service!');
            redirect('admin/kanal');
        }

        // Check if kanal is being used in supervisor_scope
        $this->db->where('id_kanal', $id);
        $used_in_scope = $this->db->count_all_results('supervisor_scope');
        
        if ($used_in_scope > 0) {
            $this->session->set_flashdata('error', 'Kanal tidak dapat dihapus karena masih digunakan dalam ' . $used_in_scope . ' scope supervisor!');
            redirect('admin/kanal');
        }

        if ($this->Kanal->deleteById($id)) {
            $this->session->set_flashdata('success', 'Kanal berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kanal!');
        }
        
        redirect('admin/kanal');
    }

    public function check_name_unique($namaKanal, $id)
    {
        if ($this->Kanal->nameExists($namaKanal, $id)) {
            $this->form_validation->set_message('check_name_unique', 'Nama kanal sudah digunakan!');
            return FALSE;
        }
        return TRUE;
    }
}
