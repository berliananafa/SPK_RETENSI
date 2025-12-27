<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KriteriaModel extends MY_Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot',
        'jenis_kriteria',
        'deskripsi',
        'status_approval',
        'approved_by',
        'approved_at',
        'rejection_note',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all criteria ordered by code
     */
    public function getAllOrdered()
    {
        return $this->db->order_by('kode_kriteria', 'ASC')->get($this->table)->result();
    }

    /**
     * Get criterion by code
     */
    public function findByCode($kode)
    {
        return $this->db->where('kode_kriteria', $kode)->get($this->table)->row();
    }

    /**
     * Alias for findByCode
     */
    public function getByCode($kode)
    {
        return $this->findByCode($kode);
    }

    /**
     * Check if code exists
     */
    public function codeExists($kode, $excludeId = null)
    {
        $this->db->where('kode_kriteria', $kode);
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get criteria with sub criteria
     */
    public function getWithSubCriteria()
    {
        $criteria = $this->getAllOrdered();
        
        foreach ($criteria as $key => $criterion) {
            $this->db->where('id_kriteria', $criterion->id_kriteria);
            $this->db->order_by('bobot_sub', 'DESC');
            $criteria[$key]->sub_kriteria = $this->db->get('sub_kriteria')->result();
        }
        
        return $criteria;
    }

    /**
     * Get criteria by type (Core Factor / Secondary Factor)
     */
    public function getByType($jenisKriteria)
    {
        return $this->db->where('jenis_kriteria', $jenisKriteria)
            ->order_by('kode_kriteria', 'ASC')
            ->get($this->table)
            ->result();
    }
}
