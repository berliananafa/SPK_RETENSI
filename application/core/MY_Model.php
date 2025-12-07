<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Model — Simple Base Model
 * Fitur:
 * - $table, $primaryKey, $fillable
 * - Otomatis created_at & updated_at
 * - CRUD dasar: all(), find(), create(), updateById(), deleteById()
 */
class MY_Model extends CI_Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    protected $timestamps = true;
    protected $createdAt = 'created_at';
    protected $updatedAt = 'updated_at';

    public function all()
    {
        return $this->db->get($this->table)->result();
    }

    public function find($id)
    {
        return $this->db->where($this->primaryKey, $id)
                        ->get($this->table)
                        ->row();
    }

    public function create(array $data)
    {
        $data = $this->filterFillable($data);

        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            $data[$this->createdAt] = $now;
            $data[$this->updatedAt] = $now;
        }

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function updateById($id, array $data)
    {
        $data = $this->filterFillable($data);

        if ($this->timestamps) {
            $data[$this->updatedAt] = date('Y-m-d H:i:s');
        }

        return $this->db->where($this->primaryKey, $id)
                        ->update($this->table, $data);
    }

    public function deleteById($id)
    {
        return $this->db->where($this->primaryKey, $id)
                        ->delete($this->table);
    }

    public function countAll()
    {
        return $this->db->count_all($this->table);
    }

    protected function filterFillable(array $data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }
}
