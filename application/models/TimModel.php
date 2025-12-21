<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TimModel extends MY_Model
{
    protected $table = 'tim';
    protected $primaryKey = 'id_tim';

    protected $fillable = [
        'id_leader',
        'id_supervisor',
        'nama_tim',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all teams with leader and supervisor info
     */
    public function getAllWithDetails()
    {
        return $this->db->select(
            'tim.*, 
            leader.nama_pengguna as nama_leader,
            supervisor.nama_pengguna as nama_supervisor'
        )
        ->from($this->table)
        ->join('pengguna leader', 'tim.id_leader = leader.id_user', 'left')
        ->join('pengguna supervisor', 'tim.id_supervisor = supervisor.id_user', 'left')
        ->order_by('tim.created_at', 'DESC')
        ->get()
        ->result();
    }

    /**
     * Get team with details by id
     */
    public function getByIdWithDetails($id)
    {
        return $this->db->select(
            'tim.*, 
            leader.nama_pengguna as nama_leader,
            leader.email as email_leader,
            supervisor.nama_pengguna as nama_supervisor,
            supervisor.email as email_supervisor'
        )
        ->from($this->table)
        ->join('pengguna leader', 'tim.id_leader = leader.id_user', 'left')
        ->join('pengguna supervisor', 'tim.id_supervisor = supervisor.id_user', 'left')
        ->where("tim.{$this->primaryKey}", $id)
        ->get()
        ->row();
    }

    /**
     * Check if team name exists
     */
    public function nameExists($nama, $excludeId = null)
    {
        $this->db->where('nama_tim', $nama);
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get team members count
     */
    public function getMembersCount($idTim)
    {
        return $this->db->where('id_tim', $idTim)
            ->count_all_results('customer_service');
    }

    /**
     * Get all leaders for dropdown
     */
    public function getAllLeaders()
    {
        return $this->db->where('level', 'leader')
            ->order_by('nama_pengguna', 'ASC')
            ->get('pengguna')
            ->result();
    }

    /**
     * Get all supervisors for dropdown
     */
    public function getAllSupervisors()
    {
        return $this->db->where('level', 'supervisor')
            ->order_by('nama_pengguna', 'ASC')
            ->get('pengguna')
            ->result();
    }

    /**
     * Get team by name
     */
    public function getByName($nama)
    {
        return $this->db->where('nama_tim', $nama)
            ->get($this->table)
            ->row();
    }

    /**
     * Get teams by supervisor
     */
    public function getBySupervisor($idSupervisor)
    {
        return $this->db->where('id_supervisor', $idSupervisor)
            ->order_by('nama_tim', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get teams by leader
     */
    public function getByLeader($idLeader)
    {
        return $this->db->where('id_leader', $idLeader)
            ->order_by('nama_tim', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Check if leader already has a team
     * Leader can only manage ONE team
     */
    public function leaderHasTeam($idLeader, $excludeTeamId = null)
    {
        $this->db->where('id_leader', $idLeader);
        if ($excludeTeamId) {
            $this->db->where("{$this->primaryKey} !=", $excludeTeamId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get teams under a manager with statistics
     */
    public function getTeamsByManager($managerId)
    {
        return $this->db->select('t.*, ps.nama_pengguna as supervisor_name, pl.nama_pengguna as leader_name, 
                                 COUNT(DISTINCT cs.id_cs) as total_cs, 
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian')
                        ->from("{$this->table} t")
                        ->join('pengguna ps', 't.id_supervisor = ps.id_user', 'left')
                        ->join('pengguna pl', 't.id_leader = pl.id_user', 'left')
                        ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
                        ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('ps.id_atasan', $managerId)
                        ->group_by('t.id_tim')
                        ->get()
                        ->result();
    }

    /**
     * Get team detail by manager
     */
    public function getTeamByManager($teamId, $managerId)
    {
        return $this->db->select('t.*, ps.nama_pengguna as supervisor_name, pl.nama_pengguna as leader_name')
                        ->from("{$this->table} t")
                        ->join('pengguna ps', 't.id_supervisor = ps.id_user', 'left')
                        ->join('pengguna pl', 't.id_leader = pl.id_user', 'left')
                        ->where('t.id_tim', $teamId)
                        ->where('ps.id_atasan', $managerId)
                        ->get()
                        ->row();
    }

    /**
     * Count teams under a manager
     */
    public function countByManager($managerId)
    {
        return $this->db->select('COUNT(DISTINCT t.id_tim) as total')
                        ->from("{$this->table} t")
                        ->join('pengguna p', 't.id_supervisor = p.id_user')
                        ->where('p.id_atasan', $managerId)
                        ->get()
                        ->row()->total ?? 0;
    }

    /**
     * Get teams by supervisor with CS details
     */
    public function getBySupervisorWithDetails($supervisorId)
    {
        return $this->db->select('t.*, pl.nama_pengguna as leader_name, COUNT(DISTINCT cs.id_cs) as total_cs')
                        ->from("{$this->table} t")
                        ->join('pengguna pl', 't.id_leader = pl.id_user', 'left')
                        ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
                        ->where('t.id_supervisor', $supervisorId)
                        ->group_by('t.id_tim')
                        ->get()
                        ->result();
    }

    /**
     * Count teams under a supervisor
     */
    public function countBySupervisor($supervisorId)
    {
        return $this->db->where('id_supervisor', $supervisorId)
                        ->count_all_results($this->table);
    }

    /**
     * Get team detail for supervisor
     */
    public function getTeamBySupervisor($teamId, $supervisorId)
    {
        return $this->db->select('t.*, pl.nama_pengguna as leader_name')
                        ->from("{$this->table} t")
                        ->join('pengguna pl', 't.id_leader = pl.id_user', 'left')
                        ->where('t.id_tim', $teamId)
                        ->where('t.id_supervisor', $supervisorId)
                        ->get()
                        ->row();
    }
}
