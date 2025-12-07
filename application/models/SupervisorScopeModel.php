<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SupervisorScopeModel extends MY_Model
{
    protected $table = 'supervisor_scope';
    protected $primaryKey = 'id_scope';

    protected $fillable = [
        'id_supervisor',
        'id_kanal',
        'id_produk',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all supervisor scopes with related data
     */
    public function getAllWithDetails()
    {
        return $this->db->select(
            'supervisor_scope.*, 
            pengguna.nama_pengguna as nama_supervisor,
            kanal.nama_kanal,
            produk.nama_produk'
        )
        ->from($this->table)
        ->join('pengguna', 'supervisor_scope.id_supervisor = pengguna.id_user', 'left')
        ->join('kanal', 'supervisor_scope.id_kanal = kanal.id_kanal', 'left')
        ->join('produk', 'supervisor_scope.id_produk = produk.id_produk', 'left')
        ->order_by('pengguna.nama_pengguna', 'ASC')
        ->get()
        ->result();
    }

    /**
     * Get scope by supervisor ID
     */
    public function getBySupervisor($supervisorId)
    {
        return $this->db->select(
            'supervisor_scope.*, 
            kanal.nama_kanal,
            produk.nama_produk'
        )
        ->from($this->table)
        ->join('kanal', 'supervisor_scope.id_kanal = kanal.id_kanal', 'left')
        ->join('produk', 'supervisor_scope.id_produk = produk.id_produk', 'left')
        ->where('supervisor_scope.id_supervisor', $supervisorId)
        ->get()
        ->result();
    }

    /**
     * Get supervisors by kanal
     */
    public function getSupervisorsByKanal($kanalId)
    {
        return $this->db->select(
            'supervisor_scope.*, 
            pengguna.nama_pengguna as nama_supervisor'
        )
        ->from($this->table)
        ->join('pengguna', 'supervisor_scope.id_supervisor = pengguna.id_user')
        ->where('supervisor_scope.id_kanal', $kanalId)
        ->get()
        ->result();
    }

    /**
     * Get supervisors by produk
     */
    public function getSupervisorsByProduk($produkId)
    {
        return $this->db->select(
            'supervisor_scope.*, 
            pengguna.nama_pengguna as nama_supervisor'
        )
        ->from($this->table)
        ->join('pengguna', 'supervisor_scope.id_supervisor = pengguna.id_user')
        ->where('supervisor_scope.id_produk', $produkId)
        ->get()
        ->result();
    }

    /**
     * Check if scope combination exists
     */
    public function scopeExists($supervisorId, $kanalId, $produkId, $excludeId = null)
    {
        $this->db->where('id_supervisor', $supervisorId);
        $this->db->where('id_kanal', $kanalId);
        $this->db->where('id_produk', $produkId);
        
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Delete scope by supervisor
     */
    public function deleteBySupervisor($supervisorId)
    {
        return $this->db->where('id_supervisor', $supervisorId)->delete($this->table);
    }
}
