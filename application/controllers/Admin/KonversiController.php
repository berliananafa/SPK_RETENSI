<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KonversiController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KonversiModel');
        $this->load->model('CustomerServiceModel');
        $this->load->model('SubKriteriaModel');
        $this->load->model('RangeModel');
    }

    public function index()
    {
        set_page_title('Konversi Nilai');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Konversi']
        ]);
        
        enable_datatables();
        enable_sweetalert();
        
        $data['konversi'] = $this->KonversiModel->getAllWithDetails();
        $data['all_cs'] = $this->CustomerServiceModel->all();
        
        render_layout('admin/konversi/index', $data);
    }

    public function create()
    {
        set_page_title('Tambah Konversi Nilai');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Konversi', 'url' => base_url('admin/konversi')],
            ['title' => 'Tambah']
        ]);
        
        $data['cs_list'] = $this->CustomerServiceModel->all();
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();
        
        render_layout('admin/konversi/create', $data);
    }

    public function store()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_cs', 'Customer Service', 'required|numeric');
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|numeric');
        $this->form_validation->set_rules('nilai_asli', 'Nilai Asli', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $id_sub_kriteria = $this->input->post('id_sub_kriteria', true);
            $nilai_asli = $this->input->post('nilai_asli', true);

            // Find matching range
            $range = $this->RangeModel->getSubKriteriaByNilai($id_sub_kriteria, $nilai_asli);
            
            if (empty($range)) {
                $this->session->set_flashdata('error', 'Tidak ada range yang sesuai untuk nilai tersebut!');
                redirect('admin/konversi/create');
                return;
            }

            $data = [
                'id_cs' => $this->input->post('id_cs', true),
                'id_sub_kriteria' => $id_sub_kriteria,
                'id_range' => $range->id_range,
                'nilai_asli' => $nilai_asli,
                'nilai_konversi' => $range->nilai_range
            ];

            if ($this->KonversiModel->create($data)) {
                $this->session->set_flashdata('success', 'Konversi nilai berhasil ditambahkan!');
                redirect('admin/konversi');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan konversi nilai!');
                redirect('admin/konversi/create');
            }
        }
    }

    public function edit($id)
    {
        $data['konversi'] = $this->KonversiModel->getByIdWithDetails($id);
        
        if (empty($data['konversi'])) {
            $this->session->set_flashdata('error', 'Data konversi tidak ditemukan!');
            redirect('admin/konversi');
        }
        
        set_page_title('Edit Konversi Nilai');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Konversi', 'url' => base_url('admin/konversi')],
            ['title' => 'Edit']
        ]);
        
        $data['cs_list'] = $this->CustomerServiceModel->all();
        $data['sub_kriteria'] = $this->SubKriteriaModel->getAllWithDetails();
        
        render_layout('admin/konversi/edit', $data);
    }

    public function update($id)
    {
        $konversi = $this->KonversiModel->find($id);
        if (empty($konversi)) {
            $this->session->set_flashdata('error', 'Data konversi tidak ditemukan!');
            redirect('admin/konversi');
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_cs', 'Customer Service', 'required|numeric');
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|numeric');
        $this->form_validation->set_rules('nilai_asli', 'Nilai Asli', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $id_sub_kriteria = $this->input->post('id_sub_kriteria', true);
            $nilai_asli = $this->input->post('nilai_asli', true);

            // Find matching range
            $range = $this->RangeModel->getSubKriteriaByNilai($id_sub_kriteria, $nilai_asli);
            
            if (empty($range)) {
                $this->session->set_flashdata('error', 'Tidak ada range yang sesuai untuk nilai tersebut!');
                redirect('admin/konversi/edit/'.$id);
                return;
            }

            $data = [
                'id_cs' => $this->input->post('id_cs', true),
                'id_sub_kriteria' => $id_sub_kriteria,
                'id_range' => $range->id_range,
                'nilai_asli' => $nilai_asli,
                'nilai_konversi' => $range->nilai_range
            ];

            if ($this->KonversiModel->updateById($id, $data)) {
                $this->session->set_flashdata('success', 'Konversi nilai berhasil diupdate!');
                redirect('admin/konversi');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate konversi nilai!');
                redirect('admin/konversi/edit/'.$id);
            }
        }
    }

    public function delete($id)
    {
        $konversi = $this->KonversiModel->find($id);
        if (empty($konversi)) {
            $this->session->set_flashdata('error', 'Data konversi tidak ditemukan!');
            redirect('admin/konversi');
        }

        if ($this->KonversiModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Konversi nilai berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus konversi nilai!');
        }
        
        redirect('admin/konversi');
    }

    /**
     * Get ranges by sub kriteria (for AJAX)
     */
    public function get_ranges($id_sub_kriteria)
    {
        $ranges = $this->RangeModel->getBySubKriteria($id_sub_kriteria);
        header('Content-Type: application/json');
        echo json_encode($ranges);
    }
}
