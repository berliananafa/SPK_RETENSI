<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
	 * Supports NULL boundaries: NULL batas_bawah = ≤, NULL batas_atas = ≥
	 */
	public function getSubKriteriaByNilai($idSubKriteria, $nilai)
	{
		$this->db->where('id_sub_kriteria', $idSubKriteria);
		
		// Handle batas_bawah: NULL berarti ≤ batas_atas saja
		$this->db->group_start()
			->where('batas_bawah IS NULL', null, false)
			->or_where('batas_bawah <=', $nilai)
		->group_end();
		
		// Handle batas_atas: NULL berarti ≥ batas_bawah saja
		$this->db->group_start()
			->where('batas_atas IS NULL', null, false)
			->or_where('batas_atas >=', $nilai)
		->group_end();
		
		return $this->db->get($this->table)->row();
	}

	/**
	 * Check if range overlaps
	 * Supports open ranges (NULL for infinite bounds)
	 */
	public function checkOverlap($idSubKriteria, $batasBawah, $batasAtas, $excludeId = null)
	{
		$this->db->where('id_sub_kriteria', $idSubKriteria);

		if ($excludeId) {
			$this->db->where("{$this->primaryKey} !=", $excludeId);
		}

		$existingRanges = $this->db->get($this->table)->result();

		foreach ($existingRanges as $range) {
			if ($this->rangesOverlap($batasBawah, $batasAtas, $range->batas_bawah, $range->batas_atas)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if two ranges overlap
	 * NULL means infinity (open range)
	 */
	private function rangesOverlap($bawah1, $atas1, $bawah2, $atas2)
	{
		// Handle NULL as infinite boundaries
		$start1 = ($bawah1 === null || $bawah1 === '') ? -INF : (float)$bawah1;
		$end1 = ($atas1 === null || $atas1 === '') ? INF : (float)$atas1;
		$start2 = ($bawah2 === null || $bawah2 === '') ? -INF : (float)$bawah2;
		$end2 = ($atas2 === null || $atas2 === '') ? INF : (float)$atas2;

		// Ranges overlap if: start1 <= end2 AND start2 <= end1
		// Using <= to detect actual overlap (touching boundaries are allowed)
		return $start1 < $end2 && $start2 < $end1;
	}
}
