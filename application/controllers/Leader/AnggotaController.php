<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnggotaController extends Leader_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Tim_model', 'CustomerServiceModel']);
    }

    public function index()
    {
        set_page_title('Anggota Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
            ['title' => 'Anggota Tim']
        ]);
        
        enable_datatables();
        
        // TODO: Implement team members list
        render_layout('leader/anggota/index');
    }
}
