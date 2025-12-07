<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RangeModel extends MY_Model
{
    protected $table = 'range';
    protected $primaryKey = 'id_range';

    protected $fillable = [
        'id_sub_kriteria',
        'batas_atas',
        'batas_bawah',
        'nilai_range',
        'keterangan',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all ranges with sub kriteria info
     */
    public function getAllWithDetails()
    {
        return $this->db->select(
            'range.*, 
            sub_kriteria.nama_sub_kriteria,
            kriteria.nama_kriteria,
            kriteria.kode_kriteria'
        )
        ->from($this->table)
        ->join('sub_kriteria', 'range.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
        ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
        ->order_by('kriteria.kode_kriteria', 'ASC')
        ->order_by('range.batas_atas', 'DESC')
        ->get()
        ->result();
    }

    /**
     * Get range with details by id
     */
    public function getByIdWithDetails($id)
    {
        return $this->db->select(
            'range.*, 
            sub_kriteria.nama_sub_kriteria,
            kriteria.nama_kriteria'
        )
        ->from($this->table)
        ->join('sub_kriteria', 'range.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
        ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
        ->where("range.{$this->primaryKey}", $id)
        ->get()
        ->row();
    }

    /**
     * Get ranges by sub kriteria
     */
    public function getBySubKriteria($idSubKriteria)
    {
        return $this->db->where('id_sub_kriteria', $idSubKriteria)
            ->order_by('batas_atas', 'DESC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get sub kriteria by nilai range
     */
    public function getSubKriteriaByNilai($idSubKriteria, $nilai)
    {
        return $this->db->where('id_sub_kriteria', $idSubKriteria)
            ->where('batas_bawah <=', $nilai)
            ->where('batas_atas >=', $nilai)
            ->get($this->table)
            ->row();
    }

    /**
     * Check if range overlaps
     */
    public function checkOverlap($idSubKriteria, $batasBawah, $batasAtas, $excludeId = null)
    {
        $this->db->where('id_sub_kriteria', $idSubKriteria);
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('batas_bawah <=', $batasBawah);
        $this->db->where('batas_atas >=', $batasBawah);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('batas_bawah <=', $batasAtas);
        $this->db->where('batas_atas >=', $batasAtas);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('batas_bawah >=', $batasBawah);
        $this->db->where('batas_atas <=', $batasAtas);
        $this->db->group_end();
        $this->db->group_end();

        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }

        return $this->db->count_all_results($this->table) > 0;
    }
}
