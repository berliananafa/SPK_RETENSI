<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller - Mengatur autentikasi dan autorisasi
 */
class MY_Controller extends CI_Controller
{
    protected $required_role = null;
    protected $user;
    
    public function __construct()
    {
        parent::__construct();
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Set user data
        $this->user = (object) [
            'id' => $this->session->userdata('user_id'),
            'nik' => $this->session->userdata('user_nik'),
            'nama' => $this->session->userdata('user_name'),
            'email' => $this->session->userdata('user_email'),
            'level' => $this->session->userdata('user_level'),
            'id_atasan' => $this->session->userdata('user_id_atasan'),
        ];
        
        // Cek role jika diperlukan
        if ($this->required_role && $this->user->level !== $this->required_role) {
            show_error('Anda tidak memiliki akses ke halaman ini.', 403);
        }
    }
}

// Role-based Controllers
class Admin_Controller extends MY_Controller { 
    protected $required_role = 'admin'; 
}

class Supervisor_Controller extends MY_Controller { 
    protected $required_role = 'supervisor'; 
}

class Manager_Controller extends MY_Controller { 
    protected $required_role = 'junior_manager'; 
}

class Leader_Controller extends MY_Controller { 
    protected $required_role = 'leader'; 
}
