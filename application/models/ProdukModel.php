<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdukModel extends MY_Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'sku_produk',
        'nama_produk',
        'deskripsi',
        'gambar',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all products ordered by name
     */
    public function getAllOrdered()
    {
        return $this->db->order_by('nama_produk', 'ASC')->get($this->table)->result();
    }

    /**
     * Get product by SKU
     */
    public function findBySku($sku)
    {
        return $this->db->where('sku_produk', $sku)->get($this->table)->row();
    }

    /**
     * Check if SKU exists
     */
    public function skuExists($sku, $excludeId = null)
    {
        $this->db->where('sku_produk', $sku);
        if ($excludeId) {
            $this->db->where("{$this->primaryKey} !=", $excludeId);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Get active products
     */
    public function getActive()
    {
        return $this->db->order_by('nama_produk', 'ASC')->get($this->table)->result();
    }
}
