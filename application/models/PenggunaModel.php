<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * PenggunaModel
 * --------------------------------------------------
 * Model untuk mengelola data pengguna (user) sistem.
 * 
 * Fitur utama:
 * - CRUD pengguna
 * - Manajemen password (hashing)
 * - Autentikasi login
 * - Query berbasis level (admin, junior manager, supervisor, leader)
 * - Validasi relasi sebelum delete
 * - Statistik organisasi & dashboard
 */
class PenggunaModel extends MY_Model
{
    /** Nama tabel */
    protected $table = 'pengguna';

    /** Primary key tabel */
    protected $primaryKey = 'id_user';

    /** Field yang boleh diisi (mass assignment) */
    protected $fillable = [
        'nik',
        'nama_pengguna',
        'email',
        'password',
        'level',
        'id_atasan',
    ];

    /** ======================================================
     * KONSTANTA LEVEL PENGGUNA
     * ====================================================== */
    const LEVEL_ADMIN          = 'admin';
    const LEVEL_JUNIOR_MANAGER = 'junior_manager';
    const LEVEL_SUPERVISOR     = 'supervisor';
    const LEVEL_LEADER         = 'leader';

    /** ======================================================
     * PASSWORD HANDLING
     * ====================================================== */

    /**
     * Override create
     * - Hash password sebelum data disimpan
     */
    public function create(array $data)
    {
        $data = $this->processPasswordForCreate($data);
        return parent::create($data);
    }

    /**
     * Update data berdasarkan ID
     * - Hash password jika diubah
     */
    public function updateById($id, array $data)
    {
        $data = $this->processPasswordForUpdate($data);
        return parent::updateById($id, $data);
    }

    /**
     * Hash password saat create
     */
    private function processPasswordForCreate(array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Hash password saat update
     * - Jika password kosong, field dihapus agar tidak overwrite
     */
    private function processPasswordForUpdate(array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $data;
    }

    /** ======================================================
     * BASIC FINDERS
     * ====================================================== */

    /**
     * Cari user berdasarkan email
     */
    public function findByEmail($email)
    {
        return $this->db
            ->where('email', $email)
            ->get($this->table)
            ->row();
    }

    /**
     * Cari user berdasarkan NIK
     */
    public function findByNik($nik)
    {
        return $this->db
            ->where('nik', $nik)
            ->get($this->table)
            ->row();
    }

    /** ======================================================
     * AUTHENTICATION
     * ====================================================== */

    /**
     * Verifikasi login user
     * - Cocokkan email & password
     * - Return object user jika valid
     */
    public function verifyLogin($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    /** ======================================================
     * LEVEL-BASED QUERIES
     * ====================================================== */

    /**
     * Ambil semua user berdasarkan level
     */
    public function getByLevel($level)
    {
        return $this->db
            ->where('level', $level)
            ->order_by('nama_pengguna', 'ASC')
            ->get($this->table)
            ->result();
    }

    /** ======================================================
     * DUPLICATE CHECKERS
     * ====================================================== */

    /**
     * Cek email sudah digunakan atau belum
     */
    public function emailExists($email, $excludeId = null)
    {
        return $this->exists('email', $email, $excludeId);
    }

    /**
     * Cek NIK sudah digunakan atau belum
     */
    public function nikExists($nik, $excludeId = null)
    {
        return $this->exists('nik', $nik, $excludeId);
    }

    /**
     * Helper untuk cek data duplikat
     */
    private function exists($field, $value, $excludeId)
    {
        $this->db->where($field, $value);

        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    /** ======================================================
     * SUPERVISOR QUERIES
     * ====================================================== */

    /**
     * Ambil supervisor beserta:
     * - Jumlah tim
     * - Jumlah customer service
     */
    public function getSupervisorsWithStats($managerId)
    {
        return $this->db
            ->select('p.*, 
                      COUNT(DISTINCT t.id_tim) AS total_tim, 
                      COUNT(DISTINCT cs.id_cs) AS total_cs')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_supervisor', 'left')
            ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
            ->where('p.id_atasan', $managerId)
            ->where('p.level', self::LEVEL_SUPERVISOR)
            ->group_by('p.id_user')
            ->get()
            ->result();
    }

    /**
     * Ambil supervisor berdasarkan manager
     */
    public function getSupervisorByManager($supervisorId, $managerId)
    {
        return $this->db
            ->where('id_user', $supervisorId)
            ->where('id_atasan', $managerId)
            ->where('level', self::LEVEL_SUPERVISOR)
            ->get($this->table)
            ->row();
    }

    /**
     * Hitung jumlah supervisor di bawah manager
     */
    public function countByManager($managerId)
    {
        return $this->db
            ->where('id_atasan', $managerId)
            ->where('level', self::LEVEL_SUPERVISOR)
            ->count_all_results($this->table);
    }

    /** ======================================================
     * LEADER QUERIES
     * ====================================================== */

    /**
     * Ambil leader berdasarkan supervisor
     * + informasi tim & jumlah CS
     */
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

    /**
     * Hitung jumlah leader berdasarkan supervisor
     */
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

    /**
     * Ambil leader beserta statistik untuk supervisor
     * - Jumlah tim
     * - Jumlah customer service
     */
    public function getLeadersWithStats($supervisorId)
    {
        return $this->db
            ->select('p.*,
                      COUNT(DISTINCT t.id_tim) AS total_tim,
                      COUNT(DISTINCT cs.id_cs) AS total_cs')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_leader', 'left')
            ->join('customer_service cs', 't.id_tim = cs.id_tim', 'left')
            ->where('t.id_supervisor', $supervisorId)
            ->where('p.level', self::LEVEL_LEADER)
            ->group_by('p.id_user')
            ->get()
            ->result();
    }

    /**
     * Ambil detail leader berdasarkan ID dan verifikasi supervisor
     * - Digunakan untuk detail leader dengan validasi akses
     */
    public function getLeaderByIdAndSupervisor($leaderId, $supervisorId)
    {
        return $this->db
            ->select('p.*, t.nama_tim, t.id_tim')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_leader')
            ->where('p.id_user', $leaderId)
            ->where('t.id_supervisor', $supervisorId)
            ->where('p.level', self::LEVEL_LEADER)
            ->get()
            ->row();
    }

    /** ======================================================
     * JUNIOR MANAGER QUERIES
     * ====================================================== */

    /**
     * Ambil junior manager beserta jumlah supervisor
     */
    public function getJuniorManagersWithSupervisorCount()
    {
        return $this->db
            ->select('p.*, COUNT(s.id_user) as jumlah_supervisor')
            ->from("{$this->table} p")
            ->join(
                "{$this->table} s",
                's.id_atasan = p.id_user AND s.level = "' . self::LEVEL_SUPERVISOR . '"',
                'left'
            )
            ->where('p.level', self::LEVEL_JUNIOR_MANAGER)
            ->group_by('p.id_user')
            ->order_by('p.nama_pengguna', 'ASC')
            ->get()
            ->result();
    }

    /** ======================================================
     * RELATIONSHIP VALIDATION (UNTUK DELETE)
     * ====================================================== */

    /**
     * Hitung user yang menjadikan user ini sebagai atasan
     */
    public function countByAtasan($atasanId)
    {
        return $this->db
            ->where('id_atasan', $atasanId)
            ->count_all_results($this->table);
    }

    /**
     * Cek penggunaan supervisor pada supervisor_scope
     */
    public function countSupervisorScopeUsage($supervisorId)
    {
        return $this->db
            ->where('id_supervisor', $supervisorId)
            ->count_all_results('supervisor_scope');
    }

    /**
     * Cek penggunaan leader pada tabel tim
     */
    public function countTeamLeaderUsage($leaderId)
    {
        return $this->db
            ->where('id_leader', $leaderId)
            ->count_all_results('tim');
    }

    /**
     * Cek penggunaan supervisor pada tabel tim
     */
    public function countTeamSupervisorUsage($supervisorId)
    {
        return $this->db
            ->where('id_supervisor', $supervisorId)
            ->count_all_results('tim');
    }

    /** ======================================================
     * DASHBOARD & SIDEBAR DATA
     * ====================================================== */

    /**
     * Hitung total data untuk dashboard admin
     */
    public function getAllTablesCount()
    {
        return [
            'total_users'    => $this->db->count_all($this->table),
            'total_cs'       => $this->db->count_all('customer_service'),
            'total_criteria' => $this->db->count_all('kriteria'),
            'total_rankings' => $this->db->count_all('ranking'),
            'total_produk'   => $this->db->count_all('produk'),
            'total_kanal'    => $this->db->count_all('kanal'),
            'total_teams'    => $this->db->count_all('tim'),
        ];
    }

    /**
     * Hitung jumlah organisasi untuk sidebar
     */
    public function getOrganizationCounts()
    {
        return [
            'junior_manager' => $this->db->where('level', self::LEVEL_JUNIOR_MANAGER)
                ->count_all_results($this->table),
            'supervisor' => $this->db->where('level', self::LEVEL_SUPERVISOR)
                ->count_all_results($this->table),
            'leader' => $this->db->where('level', self::LEVEL_LEADER)
                ->count_all_results($this->table),
        ];
    }

    /**
     * Ambil supervisor beserta data atasan (Junior Manager)
     */
    public function getSupervisorsWithAtasan()
    {
        return $this->db
            ->select('pengguna.*, atasan.nama_pengguna as nama_atasan, atasan.nik as nik_atasan')
            ->from($this->table)
            ->join("{$this->table} as atasan", 'pengguna.id_atasan = atasan.id_user', 'left')
            ->where('pengguna.level', self::LEVEL_SUPERVISOR)
            ->order_by('pengguna.nama_pengguna', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Ambil leader beserta informasi tim
     * (menghindari N+1 query)
     */
    public function getLeadersWithTeamInfo()
    {
        return $this->db
            ->select('p.*, t.id_tim, t.nama_tim')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_leader', 'left')
            ->where('p.level', self::LEVEL_LEADER)
            ->order_by('p.nama_pengguna', 'ASC')
            ->get()
            ->result();
    }

    /** ======================================================
     * SCOPED METHODS FOR ORGANIZATION CONTROLLER
     * ====================================================== */

    /**
     * Count leaders by Junior Manager
     */
    public function countLeadersByJuniorManager($managerId)
    {
        $result = $this->db
            ->select('COUNT(DISTINCT p.id_user) AS total')
            ->from("{$this->table} p")
            ->join('tim t', 'p.id_user = t.id_leader')
            ->join('pengguna s', 's.id_user = t.id_supervisor')
            ->where('s.id_atasan', $managerId)
            ->where('p.level', self::LEVEL_LEADER)
            ->get()
            ->row();

        return $result->total ?? 0;
    }

    /**
     * Count leaders by Leader (always 0 - leader doesn't manage other leaders)
     */
    public function countLeadersByLeader($leaderId)
    {
        return 0;
    }

    /**
     * Count supervisors by Junior Manager
     */
    public function countSupervisorsByJuniorManager($managerId)
    {
        $result = $this->db
            ->select('COUNT(*) AS total')
            ->from($this->table)
            ->where('level', self::LEVEL_SUPERVISOR)
            ->where('id_atasan', $managerId)
            ->get()
            ->row();

        return $result->total ?? 0;
    }
}
