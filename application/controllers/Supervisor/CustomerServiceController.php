<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerServiceController extends Supervisor_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CustomerServiceModel');
        $this->load->model('TimModel');
        $this->load->model('NilaiModel');
    }

    public function index()
    {
        $userId = $this->session->userdata('user_id');
        
        // Get all customer service under this supervisor
        $data['customer_services'] = $this->CustomerServiceModel->getBySupervisor($userId);
        $data['total_cs'] = $this->CustomerServiceModel->countBySupervisor($userId);
        
        $data['title'] = 'Daftar Customer Service';
        $data['content'] = 'supervisor/customer_service/index';
        $this->load->view('layouts/app', $data);
    }

    public function detail($id_cs)
    {
        $userId = $this->session->userdata('user_id');
        
        // Get CS detail with verification
        $cs = $this->CustomerServiceModel->getCsBySupervisor($id_cs, $userId);
        
        if (!$cs) {
            $this->session->set_flashdata('error', 'Data Customer Service tidak ditemukan atau Anda tidak memiliki akses.');
            redirect('supervisor/customer-service');
        }
        
        $data['cs'] = $cs;
        
        // Get evaluation history
        $data['evaluations'] = $this->NilaiModel->getByCustomerService($id_cs);
        
        // Get performance statistics
        $data['stats'] = $this->NilaiModel->getStatsByCustomerService($id_cs);
        
        $data['title'] = 'Detail Customer Service - ' . $cs->nama_cs;
        $data['content'] = 'supervisor/customer_service/detail';
        $this->load->view('layouts/app', $data);
    }
}
