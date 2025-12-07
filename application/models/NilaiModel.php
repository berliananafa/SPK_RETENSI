<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NilaiModel extends MY_Model
{
	protected $table = 'nilai';
	protected $primaryKey = 'id_nilai';

	protected $fillable = [
		'id_cs',
		'id_sub_kriteria',
		'nilai',
		'periode',
	];

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get all nilai with related data
	 *
	 * @param array $filter Optional filter keys: 'periode', 'id_kriteria', 'id_tim'
	 * @return array
	 */
	public function getAllWithDetails($filter = [])
	{
		$this->db->select(
			'nilai.*, 
            customer_service.id_tim,
            customer_service.nama_cs,
            customer_service.nik,
			produk.nama_produk,
            kriteria.id_kriteria,
            kriteria.nama_kriteria,
            kriteria.kode_kriteria,
            kriteria.bobot as bobot_kriteria,
            kriteria.jenis_kriteria,
            sub_kriteria.id_sub_kriteria,
            sub_kriteria.nama_sub_kriteria,
            sub_kriteria.bobot_sub,
            sub_kriteria.target'
		)
			->from($this->table)
			->join('customer_service', 'nilai.id_cs = customer_service.id_cs', 'left')
			->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
			->join('sub_kriteria', 'nilai.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
			->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left');

		// Apply filters when provided
		if (!empty($filter['periode'])) {
			$this->db->where('nilai.periode', $filter['periode']);
		}

		if (!empty($filter['id_kriteria'])) {
			$this->db->where('kriteria.id_kriteria', $filter['id_kriteria']);
		}

		if (!empty($filter['id_tim'])) {
			$this->db->where('customer_service.id_tim', $filter['id_tim']);
		}

		if (!empty($filter['id_produk'])) {
			$this->db->where('customer_service.id_produk', $filter['id_produk']);
		}

		return $this->db->order_by('nilai.created_at', 'DESC')
			->get()
			->result();
	}

	/**
	 * Get nilai with details by id
	 */
	public function getByIdWithDetails($id)
	{
		return $this->db->select(
			'nilai.*, 
            customer_service.nama_cs,
            kriteria.nama_kriteria,
            sub_kriteria.nama_sub_kriteria'
		)
			->from($this->table)
			->join('customer_service', 'nilai.id_cs = customer_service.id_cs', 'left')
			->join('sub_kriteria', 'nilai.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
			->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
			->where("nilai.{$this->primaryKey}", $id)
			->get()
			->row();
	}

	/**
	 * Get nilai by CS
	 */
	public function getByCs($idCs)
	{
		return $this->db->select(
			'nilai.*, 
            kriteria.nama_kriteria,
            kriteria.kode_kriteria,
            kriteria.jenis_kriteria,
            sub_kriteria.nama_sub_kriteria,
            sub_kriteria.bobot_sub'
		)
			->from($this->table)
			->join('sub_kriteria', 'nilai.id_sub_kriteria = sub_kriteria.id_sub_kriteria', 'left')
			->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria', 'left')
			->where('nilai.id_cs', $idCs)
			->order_by('kriteria.kode_kriteria', 'ASC')
			->get()
			->result();
	}

	/**
	 * Delete nilai by CS
	 */
	public function deleteByCs($idCs)
	{
		return $this->db->where('id_cs', $idCs)->delete($this->table);
	}

	/**
	 * Check if nilai exists for CS and sub_kriteria
	 */
	public function nilaiExists($idCs, $idSubKriteria, $excludeId = null)
	{
		$this->db->where('id_cs', $idCs);
		$this->db->where('id_sub_kriteria', $idSubKriteria);
		if ($excludeId) {
			$this->db->where("{$this->primaryKey} !=", $excludeId);
		}
		return $this->db->count_all_results($this->table) > 0;
	}

	/**
	 * Bulk insert nilai for a CS
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
	 * Get nilai grouped by CS for calculation
	 */
	public function getForCalculation($filter = [])
	{
		$this->db->select(
			'nilai.*, 
            customer_service.id_cs,
            customer_service.nama_cs,
            customer_service.nik,
            kriteria.id_kriteria,
            kriteria.kode_kriteria,
            kriteria.nama_kriteria,
            kriteria.jenis_kriteria,
            sub_kriteria.bobot_sub,
            sub_kriteria.target'
		)
			->from($this->table)
			->join('customer_service', 'nilai.id_cs = customer_service.id_cs')
			->join('sub_kriteria', 'nilai.id_sub_kriteria = sub_kriteria.id_sub_kriteria')
			->join('kriteria', 'sub_kriteria.id_kriteria = kriteria.id_kriteria');

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

	/**
	 * Get nilai history by manager
	 */
	public function getByManager($managerId)
	{
		return $this->db->select('n.*, cs.nik, cs.nama_cs, k.nama_kriteria, sk.nama_sub_kriteria, t.nama_tim')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('sub_kriteria sk', 'n.id_sub_kriteria = sk.id_sub_kriteria')
			->join('kriteria k', 'sk.id_kriteria = k.id_kriteria')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna p', 't.id_supervisor = p.id_user')
			->where('p.id_atasan', $managerId)
			->order_by('n.created_at', 'DESC')
			->get()
			->result();
	}

	/**
	 * Count penilaian by manager
	 */
	public function countByManager($managerId)
	{
		return $this->db->select('COUNT(n.id_nilai) as total')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna p', 't.id_supervisor = p.id_user')
			->where('p.id_atasan', $managerId)
			->get()
			->row()->total ?? 0;
	}

	/**
	 * Get recent evaluations by manager
	 */
	public function getRecentByManager($managerId, $limit = 10)
	{
		return $this->db->select('n.*, cs.nama_cs, cs.nik, k.nama_kriteria, sk.nama_sub_kriteria')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('sub_kriteria sk', 'n.id_sub_kriteria = sk.id_sub_kriteria')
			->join('kriteria k', 'sk.id_kriteria = k.id_kriteria')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna p', 't.id_supervisor = p.id_user')
			->where('p.id_atasan', $managerId)
			->order_by('n.created_at', 'DESC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Verify nilai belongs to manager
	 */
	public function verifyByManager($nilaiId, $managerId)
	{
		return $this->db->select('n.id_nilai')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna p', 't.id_supervisor = p.id_user')
			->where('n.id_nilai', $nilaiId)
			->where('p.id_atasan', $managerId)
			->get()
			->row();
	}

	/**
	 * Count evaluations by supervisor
	 */
	public function countBySupervisor($supervisorId)
	{
		return $this->db->select('COUNT(n.id_nilai) as total')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->where('t.id_supervisor', $supervisorId)
			->get()
			->row()->total ?? 0;
	}

	/**
	 * Get recent evaluations by supervisor
	 */
	public function getRecentBySupervisor($supervisorId, $limit = 10)
	{
		return $this->db->select('n.*, cs.nama_cs, cs.nik, t.nama_tim, k.nama_kriteria, sk.nama_sub_kriteria')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('sub_kriteria sk', 'n.id_sub_kriteria = sk.id_sub_kriteria')
			->join('kriteria k', 'sk.id_kriteria = k.id_kriteria')
			->where('t.id_supervisor', $supervisorId)
			->order_by('n.created_at', 'DESC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Get all evaluations by supervisor with full details
	 */
	public function getBySupervisor($supervisorId)
	{
		return $this->db->select('n.*, cs.nama_cs, cs.nik, t.nama_tim, k.nama_kriteria, sk.nama_sub_kriteria, n.created_at as tanggal')
			->from("{$this->table} n")
			->join('customer_service cs', 'n.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('sub_kriteria sk', 'n.id_sub_kriteria = sk.id_sub_kriteria')
			->join('kriteria k', 'sk.id_kriteria = k.id_kriteria')
			->where('t.id_supervisor', $supervisorId)
			->order_by('n.created_at', 'DESC')
			->get()
			->result();
	}

	/**
	 * Get evaluations by customer service
	 */
	public function getByCustomerService($csId)
	{
		return $this->db->select(
			'n.*, 
				k.id_kriteria,
				k.kode_kriteria,
				k.nama_kriteria,
				k.bobot as bobot_kriteria,
				k.jenis_kriteria,
				sk.id_sub_kriteria,
				sk.nama_sub_kriteria,
				sk.bobot_sub as bobot_sub,
				sk.target as target,
				cs.nama_cs,
				cs.nik,
				n.created_at as tanggal'
		)
			->from("{$this->table} n")
			->join('sub_kriteria sk', 'n.id_sub_kriteria = sk.id_sub_kriteria', 'left')
			->join('kriteria k', 'sk.id_kriteria = k.id_kriteria', 'left')
			->join('customer_service cs', 'n.id_cs = cs.id_cs', 'left')
			->where('n.id_cs', $csId)
			->order_by('k.kode_kriteria', 'ASC')
			->get()
			->result();
	}

	/**
	 * Get statistics by customer service
	 */
	public function getStatsByCustomerService($csId)
	{
		return $this->db->select('COUNT(DISTINCT n.id_nilai) as total_penilaian,
                                 COALESCE(AVG(n.nilai), 0) as rata_rata_nilai,
                                 COALESCE(MIN(n.nilai), 0) as nilai_min,
                                 COALESCE(MAX(n.nilai), 0) as nilai_max')
			->from("{$this->table} n")
			->where('n.id_cs', $csId)
			->get()
			->row();
	}
}
