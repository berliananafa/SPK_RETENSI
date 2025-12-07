<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankingModel extends MY_Model
{
    protected $table = 'ranking';
    protected $primaryKey = 'id_ranking';

    protected $fillable = [
        'id_produk',  // Note: Based on ERD this should be id_cs, but keeping original structure
        'id_cs',      // This seems to be the correct field name according to diagram  
        'nilai_akhir',
        'peringkat',
        'periode',
        'status',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all rankings with related data
     */
    public function getAllWithDetails()
    {
        return $this->db->select('ranking.*, customer_service.nama_cs, customer_service.nik')
            ->from($this->table)
            ->join('customer_service', 'ranking.id_cs = customer_service.id_cs', 'left') // Fixed join to use id_cs
            ->order_by('ranking.periode', 'DESC')
            ->order_by('ranking.peringkat', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Get ranking with details by id
     */
    public function getByIdWithDetails($id)
    {
        return $this->db->select('ranking.*, customer_service.nama_cs, customer_service.nik')
            ->from($this->table)
            ->join('customer_service', 'ranking.id_cs = customer_service.id_cs', 'left') // Fixed join to use id_cs
            ->where("ranking.{$this->primaryKey}", $id)
            ->get()
            ->row();
    }

    /**
     * Get rankings by periode
     */
    public function getByPeriode($periode, $filter = [])
    {
        $this->db->select(
            'ranking.*, 
            customer_service.nama_cs, 
            customer_service.nik,
            produk.nama_produk,
            kanal.nama_kanal,
            tim.nama_tim'
        )
        ->from($this->table)
        ->join('customer_service', 'ranking.id_cs = customer_service.id_cs', 'left') // Fixed join to use id_cs
        ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
        ->join('kanal', 'customer_service.id_kanal = kanal.id_kanal', 'left')
        ->join('tim', 'customer_service.id_tim = tim.id_tim', 'left')
        ->where('ranking.periode', $periode);

        if (!empty($filter['id_produk'])) {
            $this->db->where('customer_service.id_produk', $filter['id_produk']);
        }

        if (!empty($filter['id_kanal'])) {
            $this->db->where('customer_service.id_kanal', $filter['id_kanal']);
        }

        if (!empty($filter['id_tim'])) {
            $this->db->where('customer_service.id_tim', $filter['id_tim']);
        }

        return $this->db->order_by('ranking.peringkat', 'ASC')->get()->result();
    }

    /**
     * Get rankings by CS
     */
    public function getByCs($idCs)
    {
        return $this->db->where('id_cs', $idCs) // Fixed to use id_cs instead of id_produk
            ->order_by('periode', 'DESC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get latest periode
     */
    public function getLatestPeriode()
    {
        $result = $this->db->select('periode')
            ->order_by('periode', 'DESC')
            ->limit(1)
            ->get($this->table)
            ->row();
        return $result ? $result->periode : null;
    }

    /**
     * Get distinct periodes
     */
    public function getDistinctPeriodes()
    {
        return $this->db->distinct()
            ->select('periode')
            ->order_by('periode', 'DESC')
            ->get($this->table)
            ->result();
    }

    /**
     * Delete rankings by periode
     */
    public function deleteByPeriode($periode)
    {
        return $this->db->where('periode', $periode)->delete($this->table);
    }

    /**
     * Bulk insert rankings
     */
    public function bulkCreate($dataArray)
    {
        foreach ($dataArray as &$data) {
            $data = $this->filterFillable($data);
            if ($this->timestamps) {
                $data[$this->createdAt] = date('Y-m-d H:i:s');
                $data[$this->updatedAt] = date('Y-m-d H:i:s');
            }
        }
        return $this->db->insert_batch($this->table, $dataArray);
    }

    /**
     * Check if ranking exists for periode
     */
    public function existsForPeriode($periode)
    {
        return $this->db->where('periode', $periode)->count_all_results($this->table) > 0;
    }

    /**
     * Get top rankings by periode
     */
    public function getTopRankings($periode, $limit = 10, $filter = [])
    {
        $this->db->select(
            'ranking.*, 
            customer_service.nama_cs, 
            customer_service.nik'
        )
        ->from($this->table)
        ->join('customer_service', 'ranking.id_cs = customer_service.id_cs', 'left') // Fixed join to use id_cs
        ->where('ranking.periode', $periode);

        if (!empty($filter['id_produk'])) {
            $this->db->join('produk', 'customer_service.id_produk = produk.id_produk', 'left');
            $this->db->where('customer_service.id_produk', $filter['id_produk']);
        }

        if (!empty($filter['status'])) {
            $this->db->where('ranking.status', $filter['status']);
        }

        return $this->db->order_by('ranking.peringkat', 'ASC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Get rankings by manager
     */
    public function getByManager($managerId)
    {
        return $this->db->select('r.*, cs.nik, cs.nama_cs, t.nama_tim, p.nama_produk')
                        ->from("{$this->table} r")
                        ->join('customer_service cs', 'r.id_cs = cs.id_cs')
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('produk p', 'cs.id_produk = p.id_produk')
                        ->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
                        ->where('supervisor.id_atasan', $managerId)
                        ->where('r.status', 'published')
                        ->order_by('r.periode', 'DESC')
                        ->order_by('r.peringkat', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Get distinct periods by manager
     */
    public function getPeriodsByManager($managerId)
    {
        return $this->db->select('DISTINCT r.periode')
                        ->from("{$this->table} r")
                        ->join('customer_service cs', 'r.id_cs = cs.id_cs')
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
                        ->where('supervisor.id_atasan', $managerId)
                        ->where('r.status', 'published')
                        ->order_by('r.periode', 'DESC')
                        ->get()
                        ->result();
    }

    /**
     * Get rankings by periode for supervisor
     */
    public function getByPeriodeBySupervisor($periode, $supervisorId, $filter = [])
    {
        $this->db->select('r.*, cs.nik, cs.nama_cs, t.nama_tim, p.nama_produk, k.nama_kanal')
                 ->from("{$this->table} r")
                 ->join('customer_service cs', 'r.id_cs = cs.id_cs')
                 ->join('tim t', 'cs.id_tim = t.id_tim')
                 ->join('produk p', 'cs.id_produk = p.id_produk')
                 ->join('kanal k', 'cs.id_kanal = k.id_kanal')
                 ->where('t.id_supervisor', $supervisorId)
                 ->where('r.periode', $periode)
                 ->where('r.status', 'published');

        if (!empty($filter['id_produk'])) {
            $this->db->where('cs.id_produk', $filter['id_produk']);
        }

        if (!empty($filter['id_kanal'])) {
            $this->db->where('cs.id_kanal', $filter['id_kanal']);
        }

        if (!empty($filter['id_tim'])) {
            $this->db->where('cs.id_tim', $filter['id_tim']);
        }

        return $this->db->order_by('r.peringkat', 'ASC')
                        ->get()
                        ->result();
    }

    /**
     * Get latest periode for supervisor
     */
    public function getLatestPeriodeBySupervisor($supervisorId)
    {
        return $this->db->select('r.periode')
                        ->from("{$this->table} r")
                        ->join('customer_service cs', 'r.id_cs = cs.id_cs')
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->where('t.id_supervisor', $supervisorId)
                        ->where('r.status', 'published')
                        ->order_by('r.periode', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row();
    }

    /**
     * Get distinct periods by supervisor
     */
    public function getPeriodsBySupervisor($supervisorId)
    {
        return $this->db->select('DISTINCT r.periode')
                        ->from("{$this->table} r")
                        ->join('customer_service cs', 'r.id_cs = cs.id_cs')
                        ->join('tim t', 'cs.id_tim = t.id_tim')
                        ->where('t.id_supervisor', $supervisorId)
                        ->where('r.status', 'published')
                        ->order_by('r.periode', 'DESC')
                        ->get()
                        ->result();
    }
}
