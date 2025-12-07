<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KonversiModel extends MY_Model
{
    protected $table = 'konversi';
    protected $primaryKey = 'id_konversi';

    protected $fillable = [
        'id_cs',
        'id_sub_kriteria',
        'id_range',
        'nilai_asli',
        'nilai_konversi',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all konversi with related data
     */
    public function getAllWithDetails()
    {
        return $this->db->select(
            'konversi.*, 
            customer_service.nama_cs,
            sub_kriteria.nama_sub_kriteria,
            kriteria.nama_kriteria,
            kriteria.kode_kriteria,
            range.batas_bawah,
            range.batas_atas,
            range.nilai_range'
        )
        ->from($this->table)
        ->join('customer_service', 'konversi.id_cs = customer_service.id_cs', 'left')
        ->join('sub_kriteria', 'konversi.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
        ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
        ->join('range', 'konversi.id_range = range.id_range', 'left')
        ->order_by('konversi.created_at', 'DESC')
        ->get()
        ->result();
    }

    /**
     * Get konversi with details by id
     */
    public function getByIdWithDetails($id)
    {
        return $this->db->select(
            'konversi.*, 
            customer_service.nama_cs,
            sub_kriteria.nama_sub_kriteria,
            kriteria.nama_kriteria,
            range.nilai_range'
        )
        ->from($this->table)
        ->join('customer_service', 'konversi.id_cs = customer_service.id_cs', 'left')
        ->join('sub_kriteria', 'konversi.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
        ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
        ->join('range', 'konversi.id_range = range.id_range', 'left')
        ->where("konversi.{$this->primaryKey}", $id)
        ->get()
        ->row();
    }

    /**
     * Get konversi by CS
     */
    public function getByCs($idCs)
    {
        return $this->db->select(
            'konversi.*, 
            sub_kriteria.nama_sub_kriteria,
            kriteria.nama_kriteria,
            kriteria.kode_kriteria,
            range.nilai_range'
        )
        ->from($this->table)
        ->join('sub_kriteria', 'konversi.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
        ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
        ->join('range', 'konversi.id_range = range.id_range', 'left')
        ->where('konversi.id_cs', $idCs)
        ->order_by('kriteria.kode_kriteria', 'ASC')
        ->get()
        ->result();
    }

    /**
     * Delete konversi by CS
     */
    public function deleteByCs($idCs)
    {
        return $this->db->where('id_cs', $idCs)->delete($this->table);
    }

    /**
     * Bulk insert konversi
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
     * Get konversi for calculation
     */
    public function getForCalculation($filter = [])
    {
        $this->db->select(
            'konversi.*, 
            customer_service.id_cs,
            customer_service.nama_cs,
            customer_service.nik,
            sub_kriteria.id_kriteria,
            kriteria.kode_kriteria,
            kriteria.nama_kriteria,
            kriteria.jenis_kriteria,
            range.nilai_range'
        )
        ->from($this->table)
        ->join('customer_service', 'konversi.id_cs = customer_service.id_cs')
        ->join('sub_kriteria', 'konversi.id_sub_kriteria = sub_kriteria.id_sub_kriteria')
        ->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria')
        ->join('range', 'konversi.id_range = range.id_range');

        if (!empty($filter['id_produk'])) {
            $this->db->where('customer_service.id_produk', $filter['id_produk']);
        }

        if (!empty($filter['id_kanal'])) {
            $this->db->where('customer_service.id_kanal', $filter['id_kanal']);
        }

        if (!empty($filter['id_tim'])) {
            $this->db->where('customer_service.id_tim', $filter['id_tim']);
        }

        return $this->db->order_by('customer_service.nama_cs', 'ASC')
            ->order_by('kriteria.kode_kriteria', 'ASC')
            ->get()
            ->result();
    }
}
