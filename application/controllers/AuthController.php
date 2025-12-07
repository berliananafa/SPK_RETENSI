<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Tampilkan halaman login
     */
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $this->_redirectByLevel($this->session->userdata('user_level'));
        }

        $this->load->view('auth/login');
    }

    /**
     * Proses login
     */
    public function login_process()
    {
        // Validasi input
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', [
            'required' => 'Email harus diisi.',
            'valid_email' => 'Format email tidak valid.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required', [
            'required' => 'Password harus diisi.'
        ]);

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/login');
        }

        // Verifikasi kredensial
        $email = $this->input->post('email', true);
        $password = $this->input->post('password', true);
        $user = $this->Pengguna->verifyLogin($email, $password);

        if (!$user) {
            $this->session->set_flashdata('error', 'Email atau password salah.');
            redirect('auth/login');
        }

        // Set session
        $this->session->set_userdata([
            'user_id'        => $user->id_user ?? null,
            'user_nik'       => $user->nik ?? null,
            'user_name'      => $user->nama_pengguna ?? null,
            'user_email'     => $user->email ?? null,
            'user_level'     => $user->level ?? null,
            'user_id_atasan' => $user->id_atasan ?? null,
            'logged_in'      => true,
        ]);

        // Redirect berdasarkan level
        $this->_redirectByLevel($user->level);
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    /**
     * Redirect berdasarkan user level
     */
    private function _redirectByLevel($level)
    {
        $routes = [
            'admin'          => 'admin/dashboard',
            'junior_manager' => 'junior-manager/dashboard',
            'supervisor'     => 'supervisor/dashboard',
            'leader'         => 'leader/dashboard',
        ];

        redirect($routes[$level] ?? 'dashboard');
    }
}
