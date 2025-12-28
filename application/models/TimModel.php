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

    /** ======================================================
     * PRIVATE HELPER METHODS
     * ====================================================== */

    /**
     * Apply common JOINs for leader and supervisor
     */
    private function applyLeaderSupervisorJoin()
    {
        $this->db->join('pengguna leader', 'tim.id_leader = leader.id_user', 'left')
                 ->join('pengguna supervisor', 'tim.id_supervisor = supervisor.id_user', 'left');
        return $this;
    }

    /**
     * Apply JOIN with CS for statistics
     */
    private function applyCsStatsJoin()
    {
        $this->db->join('customer_service cs', 't.id_tim = cs.id_tim', 'left');
        return $this;
    }

    /**
     * Apply manager hierarchy WHERE clause
     */
    private function applyManagerScope($managerId)
    {
        $this->db->join('pengguna ps', 't.id_supervisor = ps.id_user')
                 ->where('ps.id_atasan', $managerId);
        return $this;
    }

    /**
     * Base SELECT for team with stats
     */
    private function getTeamWithStatsSelect()
    {
        return 't.*,
                ps.nama_pengguna as supervisor_name,
                pl.nama_pengguna as leader_name,
                COUNT(DISTINCT cs.id_cs) as total_cs,
                COUNT(DISTINCT n.id_nilai) as total_penilaian';
    }

    /** ======================================================
     * BASIC QUERIES
     * ====================================================== */

    /**
     * Get all teams with leader and supervisor info
     */
    public function getAllWithDetails()
    {
        $this->db->select(
            'tim.*,
            leader.nama_pengguna as nama_leader,
            supervisor.nama_pengguna as nama_supervisor'
        )
        ->from($this->table);

        $this->applyLeaderSupervisorJoin();

        return $this->db->order_by('tim.created_at', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get team with details by id
     */
    public function getByIdWithDetails($id)
    {
        $this->db->select(
            'tim.*,
            leader.nama_pengguna as nama_leader,
            leader.email as email_leader,
            supervisor.nama_pengguna as nama_supervisor,
            supervisor.email as email_supervisor'
        )
        ->from($this->table);

        $this->applyLeaderSupervisorJoin();

        return $this->db->where("tim.{$this->primaryKey}", $id)
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
     * Get teams by supervisor with leader and supervisor details
     */
    public function getBySupervisor($idSupervisor)
    {
        return $this->db->select('tim.*,
                                 leader.nama_pengguna as nama_leader,
                                 leader.email as email_leader,
                                 supervisor.nama_pengguna as nama_supervisor,
                                 supervisor.email as email_supervisor')
            ->from($this->table . ' tim')
            ->join('pengguna leader', 'tim.id_leader = leader.id_user', 'left')
            ->join('pengguna supervisor', 'tim.id_supervisor = supervisor.id_user', 'left')
            ->where('tim.id_supervisor', $idSupervisor)
            ->order_by('tim.nama_tim', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Get teams by leader with leader and supervisor details
     */
    public function getByLeader($idLeader)
    {
        return $this->db->select('tim.*,
                                 leader.nama_pengguna as nama_leader,
                                 leader.email as email_leader,
                                 supervisor.nama_pengguna as nama_supervisor,
                                 supervisor.email as email_supervisor')
            ->from($this->table . ' tim')
            ->join('pengguna leader', 'tim.id_leader = leader.id_user', 'left')
            ->join('pengguna supervisor', 'tim.id_supervisor = supervisor.id_user', 'left')
            ->where('tim.id_leader', $idLeader)
            ->order_by('tim.nama_tim', 'ASC')
            ->get()
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
        $this->db->select($this->getTeamWithStatsSelect())
                 ->from("{$this->table} t")
                 ->join('pengguna ps', 't.id_supervisor = ps.id_user', 'left')
                 ->join('pengguna pl', 't.id_leader = pl.id_user', 'left');

        $this->applyCsStatsJoin();

        return $this->db->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('ps.id_atasan', $managerId)
                        ->group_by('t.id_tim')
                        ->get()
                        ->result();
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

    /**
     * Get teams by Junior Manager (through supervisor hierarchy)
     */
    public function getByJuniorManager($managerId)
    {
        $this->db->select($this->getTeamWithStatsSelect())
                 ->from("{$this->table} t")
                 ->join('pengguna ps', 't.id_supervisor = ps.id_user', 'left')
                 ->join('pengguna pl', 't.id_leader = pl.id_user', 'left');

        $this->applyCsStatsJoin();

        return $this->db->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('ps.id_atasan', $managerId)
                        ->group_by('t.id_tim')
                        ->order_by('t.nama_tim', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Count teams by Junior Manager
     */
    public function countByJuniorManager($managerId)
    {
        $result = $this->db->select('COUNT(DISTINCT t.id_tim) as total')
                           ->from("{$this->table} t")
                           ->join('pengguna ps', 't.id_supervisor = ps.id_user')
                           ->where('ps.id_atasan', $managerId)
                           ->get()
                           ->row();

        return $result ? (int)$result->total : 0;
    }

    /**
     * Get team detail for Junior Manager with validation
     */
    public function getTeamByManager($teamId, $managerId)
    {
        return $this->db->select('t.*,
                                 ps.nama_pengguna as supervisor_name,
                                 ps.email as supervisor_email,
                                 pl.nama_pengguna as leader_name,
                                 pl.email as leader_email')
                        ->from("{$this->table} t")
                        ->join('pengguna ps', 't.id_supervisor = ps.id_user', 'left')
                        ->join('pengguna pl', 't.id_leader = pl.id_user', 'left')
                        ->where('t.id_tim', $teamId)
                        ->where('ps.id_atasan', $managerId)
                        ->get()
                        ->row();
    }

    /**
     * Get dashboard statistics for leader in one query
     */
    public function getLeaderDashboardStats($teamId)
    {
        return $this->db->query("
            SELECT
                (SELECT COUNT(*) FROM customer_service WHERE id_tim = ?) as total_cs,
                (SELECT COUNT(DISTINCT n.id_nilai)
                 FROM nilai n
                 JOIN customer_service cs ON n.id_cs = cs.id_cs
                 WHERE cs.id_tim = ?) as total_penilaian,
                (SELECT COUNT(*)
                 FROM ranking r
                 JOIN customer_service cs ON r.id_cs = cs.id_cs
                 WHERE cs.id_tim = ? AND r.status = 'published') as total_rankings,
                (SELECT COUNT(*)
                 FROM ranking r
                 JOIN customer_service cs ON r.id_cs = cs.id_cs
                 WHERE cs.id_tim = ? AND r.status = 'pending_leader') as pending_approvals,
                (SELECT r.periode
                 FROM ranking r
                 JOIN customer_service cs ON r.id_cs = cs.id_cs
                 WHERE cs.id_tim = ?
                 ORDER BY r.created_at DESC
                 LIMIT 1) as current_periode
        ", array($teamId, $teamId, $teamId, $teamId, $teamId))->row();
    }
}
