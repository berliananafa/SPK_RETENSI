<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NilaiController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['NilaiModel', 'CustomerServiceModel', 'KriteriaModel', 'SubKriteriaModel']);
    }

    public function index()
    {
        redirect('junior-manager/nilai/history');
    }

    public function input()
    {
        set_page_title('Input Penilaian');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Input Penilaian']
        ]);
        
        $userId = $this->session->userdata('user_id');
        
        // Get CS using model method
        $data['cs_list'] = $this->CustomerServiceModel->getByManager($userId);
        
        // Get all kriteria
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();
        
        render_layout('manager/nilai/input', $data);
    }

    public function history()
    {
        set_page_title('History Penilaian');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'History Penilaian']
        ]);
        
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        
        // Get history using model method
        $data['nilai_list'] = $this->NilaiModel->getByManager($userId);
        
        render_layout('manager/nilai/history', $data);
    }

    public function get_sub_kriteria($id_kriteria)
    {
        $subKriteria = $this->SubKriteriaModel->getByKriteria($id_kriteria);
        echo json_encode($subKriteria);
    }

    public function save()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_cs', 'Customer Service', 'required|integer');
        $this->form_validation->set_rules('id_kriteria', 'Kriteria', 'required|integer');
        $this->form_validation->set_rules('id_sub_kriteria', 'Sub Kriteria', 'required|integer');
        $this->form_validation->set_rules('nilai', 'Nilai', 'required|numeric');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('junior-manager/nilai/input');
            return;
        }
        
        $userId = $this->session->userdata('user_id');
        
        // Verify CS belongs to this manager using model
        $cs = $this->CustomerServiceModel->verifyByManager($this->input->post('id_cs'), $userId);
        
        if (!$cs) {
            $this->session->set_flashdata('error', 'Customer Service tidak ditemukan atau bukan bagian dari tim Anda');
            redirect('junior-manager/nilai/input');
            return;
        }
        
        $data = [
            'id_cs' => $this->input->post('id_cs'),
            'id_sub_kriteria' => $this->input->post('id_sub_kriteria'),
            'nilai' => $this->input->post('nilai'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->NilaiModel->create($data)) {
            $this->session->set_flashdata('success', 'Data penilaian berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data penilaian');
        }
        
        redirect('junior-manager/nilai/history');
    }

    public function delete($id)
    {
        $userId = $this->session->userdata('user_id');
        
        // Verify nilai belongs to this manager using model
        $nilai = $this->NilaiModel->verifyByManager($id, $userId);
        
        if (!$nilai) {
            $this->session->set_flashdata('error', 'Data penilaian tidak ditemukan atau bukan bagian dari tim Anda');
        } else {
            if ($this->NilaiModel->delete($id)) {
                $this->session->set_flashdata('success', 'Data penilaian berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data penilaian');
            }
        }
        
        redirect('junior-manager/nilai/history');
    }
}
