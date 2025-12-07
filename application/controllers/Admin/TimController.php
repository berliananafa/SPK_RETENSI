<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TimModel', 'Tim');
    }

    /**
     * Display list of teams
     */
    public function index()
    {
        set_page_title('Manajemen Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Tim']
        ]);
		enable_datatables();
		enable_sweetalert();

        $data['teams'] = $this->Tim->getAllWithDetails();
        render_layout('admin/tim/index', $data);
    }

    /**
     * Show create team form
     */
    public function create()
    {
        set_page_title('Tambah Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Tim', 'url' => base_url('admin/tim')],
            ['title' => 'Tambah']
        ]);

        $data['leaders'] = $this->Tim->getAllLeaders();
        $data['supervisors'] = $this->Tim->getAllSupervisors();

        render_layout('admin/tim/create', $data);
    }

    /**
     * Store new team
     */
    public function store()
    {
        $this->form_validation->set_rules('nama_tim', 'Nama Tim', 'required|trim|min_length[3]|callback_check_team_name_unique');
        $this->form_validation->set_rules('id_leader', 'Leader', 'required|numeric|callback_check_leader_available');
        $this->form_validation->set_rules('id_supervisor', 'Supervisor', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
            return;
        }

        $data = [
            'nama_tim' => $this->input->post('nama_tim', true),
            'id_leader' => $this->input->post('id_leader', true),
            'id_supervisor' => $this->input->post('id_supervisor', true),
        ];

        if ($this->Tim->create($data)) {
            $this->session->set_flashdata('success', 'Tim berhasil ditambahkan!');
            redirect('admin/tim');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan tim!');
            $this->create();
        }
    }

    /**
     * Show edit team form
     */
    public function edit($id)
    {
        $team = $this->Tim->getByIdWithDetails($id);
        
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
     * Update team
     */
    public function update($id)
    {
        $team = $this->Tim->find($id);
        
        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan!');
            redirect('admin/tim');
        }

        $this->form_validation->set_rules('nama_tim', 'Nama Tim', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('id_leader', 'Leader', 'required|numeric');
        $this->form_validation->set_rules('id_supervisor', 'Supervisor', 'required|numeric');

        // Check unique name if changed
        if ($this->input->post('nama_tim') !== $team->nama_tim) {
            $this->form_validation->set_rules('nama_tim', 'Nama Tim', 'required|trim|min_length[3]|callback_check_team_name_unique');
        }

        // Check if leader can be assigned (jika leader berubah)
        if ($this->input->post('id_leader') != $team->id_leader) {
            $this->form_validation->set_rules('id_leader', 'Leader', 'required|numeric|callback_check_leader_available_for_update[' . $id . ']');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $data = [
            'nama_tim' => $this->input->post('nama_tim', true),
            'id_leader' => $this->input->post('id_leader', true),
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
     * Delete team
     */
    public function delete($id)
    {
        $team = $this->Tim->find($id);
        
        if (!$team) {
            $this->session->set_flashdata('error', 'Tim tidak ditemukan!');
            redirect('admin/tim');
        }

        // Check if team has members
        $membersCount = $this->Tim->getMembersCount($id);
        if ($membersCount > 0) {
            $this->session->set_flashdata('error', "Tim tidak dapat dihapus karena memiliki {$membersCount} anggota Customer Service!");
            redirect('admin/tim');
        }

        if ($this->Tim->deleteById($id)) {
            $this->session->set_flashdata('success', 'Tim berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus tim!');
        }

        redirect('admin/tim');
    }

    /**
     * Show team detail
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

        // Load CustomerServiceModel to get team members
        $this->load->model('CustomerServiceModel');
        
        $data['team'] = $team;
        $data['members'] = $this->CustomerServiceModel->getByTeam($id);
        $data['members_count'] = $this->Tim->getMembersCount($id);

        render_layout('admin/tim/detail', $data);
    }

    /**
     * Callback: Check team name unique
     */
    public function check_team_name_unique($nama)
    {
        $id = $this->uri->segment(4); // Get ID from edit URL
        
        if ($this->Tim->nameExists($nama, $id)) {
            $this->form_validation->set_message('check_team_name_unique', 'Nama tim sudah digunakan!');
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Callback: Check if leader is available (not managing another team)
     */
    public function check_leader_available($idLeader)
    {
        if ($this->Tim->leaderHasTeam($idLeader)) {
            $this->form_validation->set_message('check_leader_available', 'Leader sudah memimpin tim lain. Satu leader hanya bisa memimpin 1 tim!');
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Callback: Check if leader is available for update
     */
    public function check_leader_available_for_update($idLeader, $teamId)
    {
        if ($this->Tim->leaderHasTeam($idLeader, $teamId)) {
            $this->form_validation->set_message('check_leader_available_for_update', 'Leader sudah memimpin tim lain. Satu leader hanya bisa memimpin 1 tim!');
            return FALSE;
        }
        
        return TRUE;
    }
}
