<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeamOverviewController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['TimModel', 'CustomerServiceModel']);
    }

    public function index()
    {
        set_page_title('Overview Tim');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Overview Tim']
        ]);
        
        enable_datatables();
        
        $userId = $this->session->userdata('user_id');
        
        // Get all teams under this manager's supervisors
        $data['teams'] = $this->db->select('t.*, ps.nama_pengguna as supervisor_name, pl.nama_pengguna as leader_name, 
                                           COUNT(DISTINCT cs.id_cs) as total_cs, 
                                           COUNT(DISTINCT n.id_nilai) as total_penilaian')
                                  ->from('tim t')
                                  ->join('pengguna ps', 't.id_supervisor = ps.id_user', 'left')
                                  ->join('pengguna pl', 't.id_leader = pl.id_user', 'left')
                                  ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
                                  ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                                  ->where('ps.id_atasan', $userId)
                                  ->group_by('t.id_tim')
                                  ->get()
                                  ->result();
        
        render_layout('manager/team_overview/index', $data);
    }

    public function detail($id)
    {
        $userId = $this->session->userdata('user_id');
        
        // Verify team belongs to this manager using model
        $team = $this->TimModel->getTeamByManager($id, $userId);
        
        if (!$team) {
            show_error('Tim tidak ditemukan atau bukan bagian dari tim Anda', 404);
            return;
        }
        
        set_page_title('Detail Tim - ' . $team->nama_tim);
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Overview Tim', 'url' => base_url('junior-manager/team-overview')],
            ['title' => 'Detail']
        ]);
        
        $data['team'] = $team;
        $data['cs_list'] = $this->CustomerServiceModel->getByTeamWithStats($id);
        
        render_layout('manager/team_overview/detail', $data);
    }
}
