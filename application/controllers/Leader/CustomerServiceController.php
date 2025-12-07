<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerServiceController extends Leader_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CustomerServiceModel');
    }

    public function index()
    {
        set_page_title('Customer Service');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
            ['title' => 'Customer Service']
        ]);
        
        enable_datatables();
        
        // TODO: Implement CS list under leader
        render_layout('leader/customer_service/index');
    }
}
