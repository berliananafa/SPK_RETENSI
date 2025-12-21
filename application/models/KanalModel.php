<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KanalModel extends MY_Model
{
    protected $table = 'kanal';
    protected $primaryKey = 'id_kanal';

    protected $fillable = [
        'nama_kanal',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if channel name exists
     */
    public function nameExists($namaKanal, $excludeId = null)
    {
        $this->db->where('nama_kanal', $namaKanal);
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get all channels ordered by name
     */
    public function getAllOrdered()
    {
        return $this->db->order_by('nama_kanal', 'ASC')->get($this->table)->result();
    }

    /**
     * Get channel by name
     */
    public function getByName($nama)
    {
        return $this->db->where('nama_kanal', $nama)
            ->get($this->table)
            ->row();
    }
}
