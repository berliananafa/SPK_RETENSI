<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SubKriteriaModel extends MY_Model
{
    protected $table = 'sub_kriteria';
    protected $primaryKey = 'id_sub_kriteria';

    protected $fillable = [
        'id_kriteria',
        'nama_sub_kriteria',
        'bobot_sub',
        'target',
        'keterangan',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all sub criteria with criteria info
     */
    public function getAllWithDetails()
    {
        return $this->db->select('sub_kriteria.*, kriteria.nama_kriteria, kriteria.kode_kriteria, kriteria.status_approval')
            ->from($this->table)
            ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
            ->order_by('kriteria.kode_kriteria', 'ASC')
            ->order_by('sub_kriteria.bobot_sub', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get sub criterion with details by id
     */
    public function getByIdWithDetails($id)
    {
        return $this->db->select('sub_kriteria.*, kriteria.nama_kriteria, kriteria.kode_kriteria, kriteria.status_approval')
            ->from($this->table)
            ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
            ->where("sub_kriteria.{$this->primaryKey}", $id)
            ->get()
            ->row();
    }

    /**
     * Get sub criteria by criteria id
     */
    public function getByKriteria($idKriteria)
    {
        return $this->db->where('id_kriteria', $idKriteria)
            ->order_by('bobot_sub', 'DESC')
            ->get($this->table)
            ->result();
    }

    /**
     * Get sub criterion for value range
     */
    public function getByRange($idKriteria, $nilai)
    {
        $subKriteria = $this->getByKriteria($idKriteria);
        
        foreach ($subKriteria as $sub) {
            // Jika nilai berada dalam range
            if ($nilai >= $sub->bobot_sub) {
                return $sub;
            }
        }
        
        // Return terakhir jika tidak ada yang sesuai
        return end($subKriteria);
    }

    /**
     * Check if sub criteria name exists within a criteria
     */
    public function nameExists($idKriteria, $namaSubKriteria, $excludeId = null)
    {
        $this->db->where('id_kriteria', $idKriteria);
        $this->db->where('nama_sub_kriteria', $namaSubKriteria);
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get sub criterion by criteria and code
     * For Excel import, code is the sequential number within a criteria
     */
    public function getByKriteriaAndCode($idKriteria, $kodeSubKriteria)
    {
        // Jika kode adalah angka, gunakan sebagai urutan
        if (is_numeric($kodeSubKriteria)) {
            $subKriteria = $this->getByKriteria($idKriteria);
            $index = intval($kodeSubKriteria) - 1; // Convert to 0-based index
            return isset($subKriteria[$index]) ? $subKriteria[$index] : null;
        }
        
        // Jika kode adalah string, coba match dengan nama
        return $this->db->where('id_kriteria', $idKriteria)
            ->where('nama_sub_kriteria', $kodeSubKriteria)
            ->get($this->table)
            ->row();
    }
}
