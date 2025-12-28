<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model CustomerService
 * 
 * Mengelola data Customer Service (CS) beserta relasinya
 * dengan produk, kanal, tim, supervisor, manager, dan penilaian.
 */
class CustomerServiceModel extends MY_Model
{
    // Nama tabel utama
    protected $table = 'customer_service';

    // Primary key tabel
    protected $primaryKey = 'id_cs';

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'id_produk',
        'id_kanal', 
        'id_tim',
        'nik',
        'nama_cs',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /** ======================================================
     * PRIVATE HELPER METHODS
     * ====================================================== */

    /**
     * Apply common JOINs for produk, kanal, tim
     */
    private function applyBasicDetailsJoin($alias = 'customer_service')
    {
        $this->db->join('produk', "{$alias}.id_produk = produk.id_produk", 'left')
                 ->join('kanal', "{$alias}.id_kanal = kanal.id_kanal", 'left')
                 ->join('tim', "{$alias}.id_tim = tim.id_tim", 'left');
        return $this;
    }

    /**
     * Apply common SELECT for CS basic details
     */
    private function getCsBasicSelect($alias = 'customer_service')
    {
        return "{$alias}.*,
                produk.nama_produk,
                kanal.nama_kanal,
                tim.nama_tim";
    }

    /**
     * Apply supervisor hierarchy JOIN and WHERE
     */
    private function applySupervisorScope($supervisorId, $alias = 'cs')
    {
        $this->db->join('tim t', "{$alias}.id_tim = t.id_tim")
                 ->where('t.id_supervisor', $supervisorId);
        return $this;
    }

    /**
     * Apply manager hierarchy JOIN and WHERE
     */
    private function applyManagerScope($managerId, $alias = 'cs')
    {
        $this->db->join('tim t', "{$alias}.id_tim = t.id_tim")
                 ->join('pengguna p', 't.id_supervisor = p.id_user')
                 ->where('p.id_atasan', $managerId);
        return $this;
    }

    /**
     * Base SELECT for CS with stats
     */
    private function getCsWithStatsSelect($alias = 'cs')
    {
        return "{$alias}.*, t.id_tim, t.nama_tim,
                p.nama_produk, k.nama_kanal,
                COUNT(DISTINCT n.id_nilai) as total_penilaian,
                COALESCE(AVG(n.nilai), 0) as rata_rata_nilai";
    }

    /** ======================================================
     * BASIC QUERIES
     * ====================================================== */

    /**
     * Mengambil seluruh data CS beserta detail produk, kanal, dan tim
     */
    public function getAllWithDetails()
    {
        $this->db->select($this->getCsBasicSelect())
                 ->from($this->table);

        $this->applyBasicDetailsJoin();

        return $this->db->order_by('customer_service.created_at', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Mengambil data CS berdasarkan ID beserta detail relasi
     */
    public function getByIdWithDetails($id)
    {
        $this->db->select($this->getCsBasicSelect())
                 ->from($this->table);

        $this->applyBasicDetailsJoin();

        return $this->db->where("customer_service.{$this->primaryKey}", $id)
            ->get()
            ->row();
    }

    /**
     * Mengambil data CS berdasarkan NIK
     */
    public function findByNik($nik)
    {
        return $this->db->where('nik', $nik)->get($this->table)->row();
    }

    /**
     * Alias dari findByNik (penamaan alternatif)
     */
    public function getByNik($nik)
    {
        return $this->findByNik($nik);
    }

    /**
     * Mengecek apakah NIK sudah terdaftar
     * Digunakan untuk validasi create/update
     */
    public function nikExists($nik, $excludeId = null)
    {
        $this->db->where('nik', $nik);

        // Mengecualikan ID tertentu (saat update)
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Mengambil CS berdasarkan produk
     */
    public function getByProduk($idProduk)
    {
        return $this->db->where('id_produk', $idProduk)
            ->order_by('nama_cs', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Mengambil CS berdasarkan kanal
     */
    public function getByKanal($idKanal)
    {
        return $this->db->where('id_kanal', $idKanal)
            ->order_by('nama_cs', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Mengambil CS berdasarkan tim
     */
    public function getByTim($idTim)
    {
        return $this->db->where('id_tim', $idTim)
            ->order_by('nama_cs', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Mengambil CS berdasarkan tim dengan detail produk & kanal
     */
    public function getByTeam($idTim)
    {
        $this->db->select('customer_service.*,
                          produk.nama_produk,
                          kanal.nama_kanal')
                 ->from($this->table)
                 ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
                 ->join('kanal', 'customer_service.id_kanal = kanal.id_kanal', 'left');

        return $this->db->where('customer_service.id_tim', $idTim)
            ->order_by('customer_service.nama_cs', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Mengambil CS untuk proses perhitungan ranking
     * Filter bersifat opsional
     */
    public function getForRanking($filter = [])
    {
        $this->db->select('customer_service.*')->from($this->table);

        if (!empty($filter['id_produk'])) {
            $this->db->where('id_produk', $filter['id_produk']);
        }

        if (!empty($filter['id_kanal'])) {
            $this->db->where('id_kanal', $filter['id_kanal']);
        }

        if (!empty($filter['id_tim'])) {
            $this->db->where('id_tim', $filter['id_tim']);
        }

        return $this->db->order_by('nama_cs', 'ASC')->get()->result();
    }

    /**
     * Mengambil CS yang berada di bawah seorang manager
     */
    public function getByManager($managerId)
    {
        return $this->db->select('cs.*, t.nama_tim')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('pengguna p', 't.id_supervisor = p.id_user')
                        ->where('p.id_atasan', $managerId)
                        ->order_by('cs.nama_cs', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Menghitung jumlah CS di bawah manager
     */
    public function countByManager($managerId)
    {
        return $this->db->select('COUNT(DISTINCT cs.id_cs) as total')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('pengguna p', 't.id_supervisor = p.id_user')
                        ->where('p.id_atasan', $managerId)
                        ->get()
                        ->row()->total ?? 0;
    }

    /**
     * Mengambil CS berdasarkan supervisor
     */
    public function getBySupervisor($supervisorId)
    {
        return $this->db->select('cs.*, t.nama_tim, p.nama_produk, k.nama_kanal')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                        ->where('t.id_supervisor', $supervisorId)
                        ->order_by('cs.nama_cs', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Mengambil CS per tim beserta total penilaian
     */
    public function getByTeamWithStats($teamId)
    {
        // Get latest periode
        $latestPeriode = $this->db->select('periode')
                                  ->order_by('periode', 'DESC')
                                  ->limit(1)
                                  ->get('ranking')
                                  ->row();

        $periodeCondition = $latestPeriode ? "AND r.periode = '{$latestPeriode->periode}'" : "AND 1=0";

        return $this->db->select('cs.*,
                                 p.nama_produk,
                                 k.nama_kanal,
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian,
                                 r.nilai_akhir,
                                 r.peringkat,
                                 r.periode as ranking_periode')
                        ->from("{$this->table} cs")
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                        ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->join("ranking r", "cs.id_cs = r.id_cs {$periodeCondition}", 'left', false)
                        ->where('cs.id_tim', $teamId)
                        ->group_by('cs.id_cs, r.nilai_akhir, r.peringkat, r.periode')
                        ->order_by('r.peringkat', 'ASC')
                        ->order_by('cs.nama_cs', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Verifikasi apakah CS berada di bawah manager tertentu
     */
    public function verifyByManager($csId, $managerId)
    {
        return $this->db->select('cs.id_cs')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('pengguna p', 't.id_supervisor = p.id_user')
                        ->where('cs.id_cs', $csId)
                        ->where('p.id_atasan', $managerId)
                        ->get()
                        ->row();
    }

    /**
     * Statistik performa CS berdasarkan manager
     * (Top 10 berdasarkan rata-rata nilai)
     */
    public function getPerformanceStatsByManager($managerId)
    {
        $this->db->select($this->getCsWithStatsSelect())
                 ->from("{$this->table} cs")
                 ->join('tim t', 'cs.id_tim = t.id_tim')
                 ->join('produk p', 'cs.id_produk = p.id_produk')
                 ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                 ->join('pengguna spv', 't.id_supervisor = spv.id_user')
                 ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                 ->where('spv.id_atasan', $managerId)
                 ->group_by('cs.id_cs')
                 ->having('total_penilaian >', 0)
                 ->order_by('rata_rata_nilai', 'DESC')
                 ->limit(10);

        return $this->db->get()->result();
    }

    /**
     * Menghitung jumlah CS di bawah supervisor
     */
    public function countBySupervisor($supervisorId)
    {
        return $this->db->select('COUNT(DISTINCT cs.id_cs) as total')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->where('t.id_supervisor', $supervisorId)
                        ->get()
                        ->row()->total ?? 0;
    }

    /**
     * Statistik performa CS berdasarkan supervisor
     */
    public function getPerformanceStatsBySupervisor($supervisorId)
    {
        $this->db->select($this->getCsWithStatsSelect())
                 ->from("{$this->table} cs")
                 ->join('tim t', 'cs.id_tim = t.id_tim')
                 ->join('produk p', 'cs.id_produk = p.id_produk')
                 ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                 ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                 ->where('t.id_supervisor', $supervisorId)
                 ->group_by('cs.id_cs')
                 ->having('total_penilaian >', 0)
                 ->order_by('rata_rata_nilai', 'DESC')
                 ->limit(10);

        return $this->db->get()->result();
    }

    /**
     * Mengambil detail CS tertentu dengan validasi supervisor
     */
    public function getCsBySupervisor($csId, $supervisorId)
    {
        $this->db->select($this->getCsWithStatsSelect())
                 ->from("{$this->table} cs")
                 ->join('tim t', 'cs.id_tim = t.id_tim')
                 ->join('produk p', 'cs.id_produk = p.id_produk')
                 ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                 ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                 ->where('cs.id_cs', $csId)
                 ->where('t.id_supervisor', $supervisorId)
                 ->group_by('cs.id_cs');

        return $this->db->get()->row();
    }

    /**
     * Get all CS by Junior Manager (through supervisor hierarchy)
     */
    public function getByJuniorManager($managerId)
    {
        return $this->db->select('cs.*, t.id_tim, t.nama_tim, t.id_supervisor,
                                 p.nama_produk, k.nama_kanal,
                                 supervisor.nama_pengguna as nama_supervisor,
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian,
                                 COALESCE(AVG(n.nilai), 0) as rata_rata_nilai')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                        ->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
                        ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('supervisor.id_atasan', $managerId)
                        ->group_by('cs.id_cs')
                        ->order_by('cs.nama_cs', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Get specific CS by Junior Manager with validation
     */
    public function getCsByJuniorManager($csId, $managerId)
    {
        $this->db->select('cs.*, t.id_tim, t.nama_tim, t.id_supervisor,
                          p.nama_produk, k.nama_kanal,
                          supervisor.nama_pengguna as nama_supervisor,
                          COUNT(DISTINCT n.id_nilai) as total_penilaian,
                          COALESCE(AVG(n.nilai), 0) as rata_rata_nilai')
                 ->from("{$this->table} cs")
                 ->join('tim t', 'cs.id_tim = t.id_tim')
                 ->join('produk p', 'cs.id_produk = p.id_produk')
                 ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                 ->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
                 ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                 ->where('cs.id_cs', $csId)
                 ->where('supervisor.id_atasan', $managerId)
                 ->group_by('cs.id_cs');

        return $this->db->get()->row();
    }

    /**
     * Count CS by Junior Manager
     */
    public function countByJuniorManager($managerId)
    {
        $result = $this->db->select('COUNT(DISTINCT cs.id_cs) as total')
                           ->from("{$this->table} cs")
                           ->join('tim t', 'cs.id_tim = t.id_tim')
                           ->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
                           ->where('supervisor.id_atasan', $managerId)
                           ->get()
                           ->row();

        return $result ? (int)$result->total : 0;
    }
}
