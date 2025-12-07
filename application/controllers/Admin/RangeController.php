<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RangeController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RangeModel');
        $this->load->model('SubKriteriaModel');
        $this->load->model('KriteriaModel');
    }

    public function index()
    {
        set_page_title('Range Nilai');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Range Nilai']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['ranges'] = $this->RangeModel->getAllWithDetails();
        $data['all_kriteria'] = $this->KriteriaModel->getAllOrdered();
        
        render_layout('admin/range/index', $data);
    }

    public function create()
    {
        set_page_title('Tambah Range Nilai');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Range Nilai', 'url' => base_url('admin/range')],
            ['title' => 'Tambah']
        ]);
        
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();
        render_layout('admin/range/create', $data);
    }

    public function store()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|numeric');
        $this->form_validation->set_rules('batas_bawah', 'Batas Bawah', 'required|numeric');
        $this->form_validation->set_rules('batas_atas', 'Batas Atas', 'required|numeric|callback_check_batas_atas');
        $this->form_validation->set_rules('nilai_range', 'Nilai Range', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = [
                'id_sub_kriteria' => $this->input->post('id_sub_kriteria', true),
                'batas_bawah' => $this->input->post('batas_bawah', true),
                'batas_atas' => $this->input->post('batas_atas', true),
                'nilai_range' => $this->input->post('nilai_range', true),
                'keterangan' => $this->input->post('keterangan', true)
            ];

            // Check overlap
            if ($this->RangeModel->checkOverlap($data['id_sub_kriteria'], $data['batas_bawah'], $data['batas_atas'])) {
                $this->session->set_flashdata('error', 'Range nilai tumpang tindih dengan range lain pada sub kriteria yang sama!');
                redirect('admin/range/create');
                return;
            }

            if ($this->RangeModel->create($data)) {
                $this->session->set_flashdata('success', 'Range nilai berhasil ditambahkan!');
                redirect('admin/range');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan range nilai!');
                redirect('admin/range/create');
            }
        }
    }

    public function edit($id)
    {
        $data['range'] = $this->RangeModel->getByIdWithDetails($id);
        
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
        
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();
        render_layout('admin/range/edit', $data);
    }

    public function update($id)
    {
        $range = $this->RangeModel->find($id);
        if (empty($range)) {
            $this->session->set_flashdata('error', 'Data range tidak ditemukan!');
            redirect('admin/range');
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|numeric');
        $this->form_validation->set_rules('batas_bawah', 'Batas Bawah', 'required|numeric');
        $this->form_validation->set_rules('batas_atas', 'Batas Atas', 'required|numeric|callback_check_batas_atas');
        $this->form_validation->set_rules('nilai_range', 'Nilai Range', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'id_sub_kriteria' => $this->input->post('id_sub_kriteria', true),
                'batas_bawah' => $this->input->post('batas_bawah', true),
                'batas_atas' => $this->input->post('batas_atas', true),
                'nilai_range' => $this->input->post('nilai_range', true),
                'keterangan' => $this->input->post('keterangan', true)
            ];

            // Check overlap (exclude current id)
            if ($this->RangeModel->checkOverlap($data['id_sub_kriteria'], $data['batas_bawah'], $data['batas_atas'], $id)) {
                $this->session->set_flashdata('error', 'Range nilai tumpang tindih dengan range lain pada sub kriteria yang sama!');
                redirect('admin/range/edit/'.$id);
                return;
            }

            if ($this->RangeModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Range nilai berhasil diupdate!');
                redirect('admin/range');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate range nilai!');
                redirect('admin/range/edit/'.$id);
            }
        }
    }

    public function delete($id)
    {
        $range = $this->RangeModel->find($id);
        if (empty($range)) {
            $this->session->set_flashdata('error', 'Data range tidak ditemukan!');
            redirect('admin/range');
        }

        if ($this->RangeModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Range nilai berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus range nilai!');
        }
        
        redirect('admin/range');
    }

    public function check_batas_atas($batas_atas)
    {
        $batas_bawah = $this->input->post('batas_bawah');
        if ($batas_atas <= $batas_bawah) {
            $this->form_validation->set_message('check_batas_atas', 'Batas atas harus lebih besar dari batas bawah!');
            return FALSE;
        }
        return TRUE;
    }
}
