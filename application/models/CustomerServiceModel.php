<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerServiceModel extends MY_Model
{
    protected $table = 'customer_service';
    protected $primaryKey = 'id_cs';

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

    /**
     * Get all customer service with related data
     */
    public function getAllWithDetails()
    {
        return $this->db->select(
            'customer_service.*, 
            produk.nama_produk,
            kanal.nama_kanal,
            tim.nama_tim'
        )
        ->from($this->table)
        ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
        ->join('kanal', 'customer_service.id_kanal = kanal.id_kanal', 'left')  
        ->join('tim', 'customer_service.id_tim = tim.id_tim', 'left')
        ->order_by('customer_service.created_at', 'DESC')
        ->get()
        ->result();
    }

    /**
     * Get customer service with details by id
     */
    public function getByIdWithDetails($id)
    {
        return $this->db->select(
            'customer_service.*, 
            produk.nama_produk,
            kanal.nama_kanal,
            tim.nama_tim'
        )
        ->from($this->table)
        ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
        ->join('kanal', 'customer_service.id_kanal = kanal.id_kanal', 'left')
        ->join('tim', 'customer_service.id_tim = tim.id_tim', 'left')
        ->where("customer_service.{$this->primaryKey}", $id)
        ->get()
        ->row();
    }

    /**
     * Get customer service by NIK
     */
    public function findByNik($nik)
    {
        return $this->db->where('nik', $nik)->get($this->table)->row();
    }

    /**
     * Alias for findByNik
     */
    public function getByNik($nik)
    {
        return $this->findByNik($nik);
    }

    /**
     * Check if NIK exists
     */
    public function nikExists($nik, $excludeId = null)
    {
        $this->db->where('nik', $nik);
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get CS by product
     */
    public function getByProduk($idProduk)
    {
        return $this->db->where('id_produk', $idProduk)
            ->order_by('nama_cs', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get CS by kanal
     */
    public function getByKanal($idKanal)
    {
        return $this->db->where('id_kanal', $idKanal)
            ->order_by('nama_cs', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get CS by tim
     */
    public function getByTim($idTim)
    {
        return $this->db->where('id_tim', $idTim)
            ->order_by('nama_cs', 'ASC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get CS by team with full details
     */
    public function getByTeam($idTim)
    {
        return $this->db->select(
            'customer_service.*, 
            produk.nama_produk,
            kanal.nama_kanal'
        )
        ->from($this->table)
        ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
        ->join('kanal', 'customer_service.id_kanal = kanal.id_kanal', 'left')
        ->where('customer_service.id_tim', $idTim)
        ->order_by('customer_service.nama_cs', 'ASC')
        ->get()
        ->result();
    }

    /**
     * Get CS for ranking calculation
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
     * Get CS list under a manager
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
     * Count CS under a manager
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
     * Get CS by supervisor
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
     * Get CS by team with penilaian count
     */
    public function getByTeamWithStats($teamId)
    {
        return $this->db->select('cs.*, p.nama_produk, k.nama_kanal, 
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian')
                        ->from("{$this->table} cs")
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                        ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('cs.id_tim', $teamId)
                        ->group_by('cs.id_cs')
                        ->get()
                        ->result();
    }

    /**
     * Verify CS belongs to manager
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
     * Get CS performance statistics by manager
     */
    public function getPerformanceStatsByManager($managerId)
    {
        return $this->db->select('cs.id_cs, cs.nik, cs.nama_cs, 
                                 p.nama_produk, k.nama_kanal, t.nama_tim,
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian,
                                 COALESCE(AVG(n.nilai), 0) as rata_rata_nilai')
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
                        ->limit(10)
                        ->get()
                        ->result();
    }

    /**
     * Count CS under a supervisor
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
     * Get CS performance statistics by supervisor
     */
    public function getPerformanceStatsBySupervisor($supervisorId)
    {
        return $this->db->select('cs.id_cs, cs.nik, cs.nama_cs, 
                                 p.nama_produk, k.nama_kanal, t.nama_tim,
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian,
                                 COALESCE(AVG(n.nilai), 0) as rata_rata_nilai')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                        ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('t.id_supervisor', $supervisorId)
                        ->group_by('cs.id_cs')
                        ->having('total_penilaian >', 0)
                        ->order_by('rata_rata_nilai', 'DESC')
                        ->limit(10)
                        ->get()
                        ->result();
    }

    /**
     * Get single CS by supervisor with verification
     */
    public function getCsBySupervisor($csId, $supervisorId)
    {
        return $this->db->select('cs.*, t.id_tim, t.nama_tim, p.nama_produk, k.nama_kanal,
                                 COUNT(DISTINCT n.id_nilai) as total_penilaian,
                                 COALESCE(AVG(n.nilai), 0) as rata_rata_nilai')
                        ->from("{$this->table} cs")
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                        ->join('nilai n', 'cs.id_cs = n.id_cs', 'left')
                        ->where('cs.id_cs', $csId)
                        ->where('t.id_supervisor', $supervisorId)
                        ->group_by('cs.id_cs')
                        ->get()
                        ->row();
    }
}
