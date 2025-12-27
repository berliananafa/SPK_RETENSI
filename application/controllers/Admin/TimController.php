<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk manajemen Tim
 * 
 * Fitur:
 * - CRUD Tim
 * - Validasi leader (1 leader = 1 tim)
 * - Validasi nama tim unik
 * - Detail tim dan anggota
 */
class TimController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model Tim dengan alias $this->Tim
        $this->load->model('TimModel', 'Tim');
    }

    /**
     * Menampilkan daftar seluruh tim
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Manajemen Tim');

        // Breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Tim']
        ]);

        // Aktifkan DataTables & SweetAlert
        enable_datatables();
        enable_sweetalert();

        // Ambil seluruh data tim beserta leader & supervisor
        $data['teams'] = $this->Tim->getAllWithDetails();

        // Render halaman index tim
        render_layout('admin/tim/index', $data);
    }

    /**
     * Menampilkan form tambah tim
     */
    public function create()
    {
        set_page_title('Tambah Tim');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Tim', 'url' => base_url('admin/tim')],
            ['title' => 'Tambah']
        ]);

        // Ambil data leader & supervisor yang tersedia
        $data['leaders'] = $this->Tim->getAllLeaders();
        $data['supervisors'] = $this->Tim->getAllSupervisors();

        render_layout('admin/tim/create', $data);
    }

    /**
     * Proses simpan tim baru
     */
    public function store()
    {
        // Validasi input
        $this->form_validation->set_rules(
            'nama_tim',
            'Nama Tim',
            'required|trim|min_length[3]|callback_check_team_name_unique'
        );
        $this->form_validation->set_rules(
            'id_leader',
            'Leader',
            'required|numeric|callback_check_leader_available'
        );
        $this->form_validation->set_rules(
            'id_supervisor',
            'Supervisor',
            'required|numeric'
        );

        // Jika validasi gagal, kembali ke form
        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        // Data yang akan disimpan
        $data = [
            'nama_tim'      => $this->input->post('nama_tim', true),
            'id_leader'     => $this->input->post('id_leader', true),
            'id_supervisor' => $this->input->post('id_supervisor', true),
        ];

        // Simpan ke database
        if ($this->Tim->create($data)) {
            $this->session->set_flashdata('success', 'Tim berhasil ditambahkan!');
            redirect('admin/tim');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan tim!');
            $this->create();
        }
    }

    /**
     * Menampilkan form edit tim
     */
    public function edit($id)
    {
        // Ambil data tim lengkap
        $team = $this->Tim->getByIdWithDetails($id);

        // Jika tim tidak ditemukan
        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan!');
            redirect('admin/tim');
        }

        set_page_title('Edit Tim');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Tim', 'url' => base_url('admin/tim')],
            ['title' => 'Edit']
        ]);

        $data['team'] = $team;
        $data['leaders'] = $this->Tim->getAllLeaders();
        $data['supervisors'] = $this->Tim->getAllSupervisors();

        render_layout('admin/tim/edit', $data);
    }

    /**
     * Proses update data tim
     */
    public function update($id)
    {
        // Ambil data tim lama
        $team = $this->Tim->find($id);

        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan!');
            redirect('admin/tim');
        }

        // Validasi dasar
        $this->form_validation->set_rules('nama_tim', 'Nama Tim', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('id_leader', 'Leader', 'required|numeric');
        $this->form_validation->set_rules('id_supervisor', 'Supervisor', 'required|numeric');

        // Jika nama tim berubah, cek keunikan
        if ($this->input->post('nama_tim') !== $team->nama_tim) {
            $this->form_validation->set_rules(
                'nama_tim',
                'Nama Tim',
                'required|trim|min_length[3]|callback_check_team_name_unique'
            );
        }

        // Jika leader berubah, cek apakah leader tersedia
        if ($this->input->post('id_leader') != $team->id_leader) {
            $this->form_validation->set_rules(
                'id_leader',
                'Leader',
                'required|numeric|callback_check_leader_available_for_update['.$id.']'
            );
        }

        // Jika validasi gagal
        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        // Data update
        $data = [
            'nama_tim'      => $this->input->post('nama_tim', true),
            'id_leader'     => $this->input->post('id_leader', true),
            'id_supervisor' => $this->input->post('id_supervisor', true),
        ];

        if ($this->Tim->updateById($id, $data)) {
            $this->session->set_flashdata('success', 'Tim berhasil diperbarui!');
            redirect('admin/tim');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui tim!');
            $this->edit($id);
        }
    }

    /**
     * Menghapus tim
     */
    public function delete($id)
    {
        $team = $this->Tim->find($id);

        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan!');
            redirect('admin/tim');
        }

        // Cek apakah tim masih memiliki anggota
        $membersCount = $this->Tim->getMembersCount($id);
        if ($membersCount > 0) {
            $this->session->set_flashdata(
                'error',
                "Tim tidak dapat dihapus karena memiliki {$membersCount} anggota Customer Service!"
            );
            redirect('admin/tim');
        }

        // Hapus tim
        if ($this->Tim->deleteById($id)) {
            $this->session->set_flashdata('success', 'Tim berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus tim!');
        }

        redirect('admin/tim');
    }

    /**
     * Menampilkan detail tim
     */
    public function detail($id)
    {
        $team = $this->Tim->getByIdWithDetails($id);

        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan!');
            redirect('admin/tim');
        }

        set_page_title('Detail Tim');

        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Tim', 'url' => base_url('admin/tim')],
            ['title' => 'Detail']
        ]);

        // Load model CS untuk anggota tim
        $this->load->model('CustomerServiceModel');

        $data['team'] = $team;
        $data['members'] = $this->CustomerServiceModel->getByTeam($id);
        $data['members_count'] = $this->Tim->getMembersCount($id);

        render_layout('admin/tim/detail', $data);
    }

    /**
     * Callback: Validasi nama tim unik
     */
    public function check_team_name_unique($nama)
    {
        // Ambil ID tim dari URL (saat edit)
        $id = $this->uri->segment(4);

        if ($this->Tim->nameExists($nama, $id)) {
            $this->form_validation->set_message(
                'check_team_name_unique',
                'Nama tim sudah digunakan!'
            );
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Callback: Validasi leader belum memimpin tim lain
     */
    public function check_leader_available($idLeader)
    {
        if ($this->Tim->leaderHasTeam($idLeader)) {
            $this->form_validation->set_message(
                'check_leader_available',
                'Leader sudah memimpin tim lain. Satu leader hanya bisa memimpin 1 tim!'
            );
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Callback: Validasi leader saat update tim
     */
    public function check_leader_available_for_update($idLeader, $teamId)
    {
        if ($this->Tim->leaderHasTeam($idLeader, $teamId)) {
            $this->form_validation->set_message(
                'check_leader_available_for_update',
                'Leader sudah memimpin tim lain. Satu leader hanya bisa memimpin 1 tim!'
            );
            return FALSE;
        }

        return TRUE;
    }
}
