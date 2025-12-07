<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenggunaModel extends MY_Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nik',
        'nama_pengguna',
        'email',
        'password',
        'level',
        'id_atasan',
    ];

    // Konstanta level
    const LEVEL_ADMIN          = 'admin';
    const LEVEL_JUNIOR_MANAGER = 'junior_manager';
    const LEVEL_SUPERVISOR     = 'supervisor';
    const LEVEL_LEADER         = 'leader';

    /* ----------------------------------------------------------------------
     * Password Handling
     * --------------------------------------------------------------------*/

    /**
     * Hash password jika ada saat create
     */
    public function create(array $data)
    {
        $data = $this->processPasswordForCreate($data);
        return parent::create($data);
    }

    /**
     * Hash password jika ada saat update
     */
    public function updateById($id, array $data)
    {
        $data = $this->processPasswordForUpdate($data);
        return parent::updateById($id, $data);
    }

    private function processPasswordForCreate(array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    private function processPasswordForUpdate(array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $data;
    }

    /* ----------------------------------------------------------------------
     * Basic Finders
     * --------------------------------------------------------------------*/

    public function findByEmail($email)
    {
        return $this->db->where('email', $email)
                        ->get($this->table)
                        ->row();
    }

    public function findByNik($nik)
    {
        return $this->db->where('nik', $nik)
                        ->get($this->table)
                        ->row();
    }

    /* ----------------------------------------------------------------------
     * Authentication
     * --------------------------------------------------------------------*/

    public function verifyLogin($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    /* ----------------------------------------------------------------------
     * Level-based Queries
     * --------------------------------------------------------------------*/

    public function getByLevel($level)
    {
        return $this->db->where('level', $level)
                        ->order_by('nama_pengguna', 'ASC')
                        ->get($this->table)
                        ->result();
    }

    /* ----------------------------------------------------------------------
     * Duplicate Checkers
     * --------------------------------------------------------------------*/

    public function emailExists($email, $excludeId = null)
    {
        return $this->exists('email', $email, $excludeId);
    }

    public function nikExists($nik, $excludeId = null)
    {
        return $this->exists('nik', $nik, $excludeId);
    }

    private function exists($field, $value, $excludeId)
    {
        $this->db->where($field, $value);

        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    /* ----------------------------------------------------------------------
     * Supervisor Queries
     * --------------------------------------------------------------------*/

    /**
     * Supervisors + jumlah tim + jumlah cs
     */
    public function getSupervisorsWithStats($managerId)
    {
        return $this->db
            ->select('p.*, COUNT(DISTINCT t.id_tim) AS total_tim, COUNT(DISTINCT cs.id_cs) AS total_cs')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_supervisor', 'left')
            ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
            ->where('p.id_atasan', $managerId)
            ->where('p.level', self::LEVEL_SUPERVISOR)
            ->group_by('p.id_user')
            ->get()
            ->result();
    }

    public function getSupervisorByManager($supervisorId, $managerId)
    {
        return $this->db->where('id_user', $supervisorId)
                        ->where('id_atasan', $managerId)
                        ->where('level', self::LEVEL_SUPERVISOR)
                        ->get($this->table)
                        ->row();
    }

    public function countByManager($managerId)
    {
        return $this->db->where('id_atasan', $managerId)
                        ->where('level', self::LEVEL_SUPERVISOR)
                        ->count_all_results($this->table);
    }

    /* ----------------------------------------------------------------------
     * Leader Queries
     * --------------------------------------------------------------------*/

    public function getLeadersBySupervisor($supervisorId)
    {
        return $this->db
            ->select('p.*, t.nama_tim, t.id_tim, COUNT(DISTINCT cs.id_cs) AS total_cs')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_leader', 'left')
            ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
            ->where('t.id_supervisor', $supervisorId)
            ->where('p.level', self::LEVEL_LEADER)
            ->group_by('p.id_user, t.id_tim, t.nama_tim')
            ->get()
            ->result();
    }

    public function countLeadersBySupervisor($supervisorId)
    {
        $result = $this->db
            ->select('COUNT(DISTINCT p.id_user) AS total')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_leader')
            ->where('t.id_supervisor', $supervisorId)
            ->where('p.level', self::LEVEL_LEADER)
            ->get()
            ->row();

        return $result->total ?? 0;
    }
}
