<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
	 * Get all sub criteria with criteria info
	 */
	public function getAllWithDetails()
	{
		return $this->db->select('sub_kriteria.*, 
                              kriteria.nama_kriteria, 
                              kriteria.kode_kriteria, 
                              kriteria.status_approval as kriteria_status_approval,
                              sub_kriteria.status_approval as sub_kriteria_status_approval')
			->from($this->table)
			->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
			->order_by('kriteria.kode_kriteria', 'ASC')
			->order_by('sub_kriteria.bobot_sub', 'DESC')
			->get()
			->result();
	}

	/**
	 * Get only approved sub criteria with approved criteria info
	 */
	public function getApprovedSubKriteria()
	{
		return $this->db->select('sub_kriteria.*, 
                              kriteria.nama_kriteria, 
                              kriteria.kode_kriteria')
			->from($this->table)
			->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
			->where('kriteria.status_approval', 'approved')
			->where('sub_kriteria.status_approval', 'approved')
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
	 * Get approved sub criteria by criteria id
	 * @param int $idKriteria
	 * @return array
	 */
	public function getApprovedByKriteria($idKriteria)
	{
		return $this->db->where('id_kriteria', $idKriteria)
			->where('status_approval', 'approved')
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
	 * Get approved sub criterion for value range
	 * @param int $idKriteria
	 * @param float $nilai
	 * @return object|null
	 */
	public function getApprovedByRange($idKriteria, $nilai)
	{
		$subKriteria = $this->getApprovedByKriteria($idKriteria);

		foreach ($subKriteria as $sub) {
			// Jika nilai berada dalam range
			if ($nilai >= $sub->bobot_sub) {
				return $sub;
			}
		}

		// Return terakhir jika tidak ada yang sesuai
		return !empty($subKriteria) ? end($subKriteria) : null;
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

	/**
	 * Approve sub kriteria by id
	 * @param int $id
	 * @param int $userId
	 * @return bool
	 */
	public function approveById($id, $userId)
	{
		$data = [
			'status_approval' => 'approved',
			'approved_by' => $userId,
			'approved_at' => date('Y-m-d H:i:s'),
			'rejection_note' => null
		];
		return $this->updateById($id, $data);
	}

	/**
	 * Reject sub kriteria by id
	 * @param int $id
	 * @param int $userId
	 * @param string|null $note
	 * @return bool
	 */
	public function rejectById($id, $userId, $note = null)
	{
		$data = [
			'status_approval' => 'rejected',
			'approved_by' => $userId,
			'approved_at' => date('Y-m-d H:i:s'),
			'rejection_note' => $note
		];
		return $this->updateById($id, $data);
	}
}
