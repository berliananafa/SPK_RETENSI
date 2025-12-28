<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingModel extends MY_Model
{
	protected $table = 'ranking';
	protected $primaryKey = 'id_ranking';

	protected $fillable = [
		'id_produk',
		'id_cs',
		'nilai_akhir',
		'peringkat',
		'periode',
		'status',
		'approved_by_leader',
		'approved_at_leader',
		'leader_note',
		'approved_by_supervisor',
		'approved_at_supervisor',
		'supervisor_note',
	];

	public function __construct()
	{
		parent::__construct();
	}

	/** ======================================================
	 * PRIVATE HELPER METHODS
	 * ====================================================== */

	/**
	 * Apply common JOINs for ranking with CS and approvers
	 */
	private function applyBasicRankingJoin($alias = 'ranking')
	{
		$this->db->join('customer_service', "{$alias}.id_cs = customer_service.id_cs", 'left')
				 ->join('pengguna leader', "{$alias}.approved_by_leader = leader.id_user", 'left')
				 ->join('pengguna supervisor', "{$alias}.approved_by_supervisor = supervisor.id_user", 'left');
		return $this;
	}

	/**
	 * Apply full details JOIN (CS, produk, kanal, tim, approvers)
	 */
	private function applyFullRankingJoin($alias = 'ranking')
	{
		$this->db->join('customer_service', "{$alias}.id_cs = customer_service.id_cs", 'left')
				 ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
				 ->join('kanal', 'customer_service.id_kanal = kanal.id_kanal', 'left')
				 ->join('tim', 'customer_service.id_tim = tim.id_tim', 'left')
				 ->join('pengguna leader', "{$alias}.approved_by_leader = leader.id_user", 'left')
				 ->join('pengguna supervisor', "{$alias}.approved_by_supervisor = supervisor.id_user", 'left');
		return $this;
	}

	/**
	 * Base SELECT for ranking with basic details
	 */
	private function getRankingBasicSelect($alias = 'ranking')
	{
		return "{$alias}.*,
				customer_service.nama_cs,
				customer_service.nik,
				customer_service.id_tim,
				leader.nama_pengguna as approved_by_leader_name,
				supervisor.nama_pengguna as approved_by_supervisor_name";
	}

	/**
	 * Base SELECT for ranking with full details
	 */
	private function getRankingFullSelect($alias = 'ranking')
	{
		return "{$alias}.*,
				customer_service.nama_cs,
				customer_service.nik,
				produk.nama_produk,
				kanal.nama_kanal,
				tim.nama_tim,
				leader.nama_pengguna as approved_by_leader_name,
				supervisor.nama_pengguna as approved_by_supervisor_name";
	}

	/**
	 * Apply common filters for ranking
	 */
	private function applyRankingFilters($filter = [])
	{
		if (!empty($filter['id_produk'])) {
			$this->db->where('customer_service.id_produk', $filter['id_produk']);
		}
		if (!empty($filter['id_kanal'])) {
			$this->db->where('customer_service.id_kanal', $filter['id_kanal']);
		}
		if (!empty($filter['id_tim'])) {
			$this->db->where('customer_service.id_tim', $filter['id_tim']);
		}
		if (!empty($filter['status'])) {
			$this->db->where('ranking.status', $filter['status']);
		}
		return $this;
	}

	/** ======================================================
	 * BASIC QUERIES
	 * ====================================================== */

	/**
	 * Get all rankings with related data
	 */
	public function getAllWithDetails()
	{
		$this->db->select($this->getRankingBasicSelect())
				 ->from($this->table);

		$this->applyBasicRankingJoin();

		return $this->db->order_by('ranking.periode', 'DESC')
			->order_by('ranking.peringkat', 'ASC')
			->get()
			->result();
	}

	/**
	 * Get ranking with details by id
	 */
	public function getByIdWithDetails($id)
	{
		$this->db->select($this->getRankingBasicSelect())
				 ->from($this->table);

		$this->applyBasicRankingJoin();

		return $this->db->where("ranking.{$this->primaryKey}", $id)
			->get()
			->row();
	}

	/**
	 * Get rankings by periode
	 */
	public function getByPeriode($periode, $filter = [])
	{
		$this->db->select($this->getRankingFullSelect())
				 ->from($this->table);

		$this->applyFullRankingJoin();

		$this->db->where('ranking.periode', $periode);

		$this->applyRankingFilters($filter);

		return $this->db->order_by('ranking.peringkat', 'ASC')->get()->result();
	}

	/**
	 * Get rankings by CS
	 */
	public function getByCs($idCs)
	{
		return $this->db->where('id_cs', $idCs) // Fixed to use id_cs instead of id_produk
			->order_by('periode', 'DESC')
			->get($this->table)
			->result();
	}

	/**
	 * Get latest periode
	 */
	public function getLatestPeriode()
	{
		$result = $this->db->select('periode')
			->order_by('periode', 'DESC')
			->limit(1)
			->get($this->table)
			->row();
		return $result ? $result->periode : null;
	}

	/**
	 * Get distinct periodes
	 */
	public function getDistinctPeriodes()
	{
		return $this->db->distinct()
			->select('periode')
			->order_by('periode', 'DESC')
			->get($this->table)
			->result();
	}

	/**
	 * Delete rankings by periode
	 */
	public function deleteByPeriode($periode)
	{
		return $this->db->where('periode', $periode)->delete($this->table);
	}

	/**
	 * Bulk insert rankings
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
	 * Check if ranking exists for periode
	 */
	public function existsForPeriode($periode)
	{
		return $this->db->where('periode', $periode)->count_all_results($this->table) > 0;
	}

	/**
	 * Get top rankings by periode
	 */
	public function getTopRankings($periode, $limit = 10, $filter = [])
	{
		$this->db->select('ranking.*,
						  customer_service.nama_cs,
						  customer_service.nik,
						  tim.nama_tim,
						  produk.nama_produk')
				 ->from($this->table)
				 ->join('customer_service', 'ranking.id_cs = customer_service.id_cs', 'left')
				 ->join('tim', 'customer_service.id_tim = tim.id_tim', 'left')
				 ->join('produk', 'customer_service.id_produk = produk.id_produk', 'left')
				 ->where('ranking.periode', $periode);

		$this->applyRankingFilters($filter);

		return $this->db->order_by('ranking.peringkat', 'ASC')
			->limit($limit)
			->get()
			->result();
	}

	/**
	 * Get rankings by manager
	 */
	public function getByManager($managerId)
	{
		return $this->db->select('r.*, cs.nik, cs.nama_cs, t.nama_tim, p.nama_produk')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('produk p', 'cs.id_produk = p.id_produk')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
			->where('supervisor.id_atasan', $managerId)
			->where('r.status', 'published')
			->order_by('r.periode', 'DESC')
			->order_by('r.peringkat', 'ASC')
			->get()
			->result();
	}

	/**
	 * Get distinct periods by manager
	 */
	public function getPeriodsByManager($managerId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
			->distinct()
			->where('supervisor.id_atasan', $managerId)
			->where('r.status', 'published')
			->order_by('r.periode', 'DESC')
			->get()
			->result();
	}

	/**
	 * Get rankings by periode for supervisor
	 * Shows: pending_supervisor, published
	 */
	public function getByPeriodeBySupervisor($periode, $supervisorId, $filter = [])
	{
		$this->db->select('r.*, cs.nik, cs.nama_cs, t.nama_tim, p.nama_produk, k.nama_kanal')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('produk p', 'cs.id_produk = p.id_produk')
			->join('kanal k', 'cs.id_kanal = k.id_kanal')
			->where('t.id_supervisor', $supervisorId)
			->where('r.periode', $periode)
			->where_in('r.status', ['pending_supervisor', 'published']);

		if (!empty($filter['id_produk'])) {
			$this->db->where('cs.id_produk', $filter['id_produk']);
		}

		if (!empty($filter['id_kanal'])) {
			$this->db->where('cs.id_kanal', $filter['id_kanal']);
		}

		if (!empty($filter['id_tim'])) {
			$this->db->where('cs.id_tim', $filter['id_tim']);
		}

		return $this->db->order_by('r.peringkat', 'ASC')
			->get()
			->result();
	}

	/**
	 * Get latest periode for supervisor
	 * Shows all ranking stages
	 */
	public function getLatestPeriodeBySupervisor($supervisorId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->where('t.id_supervisor', $supervisorId)
			->where_in('r.status', ['pending_supervisor', 'published'])
			->order_by('r.periode', 'DESC')
			->limit(1)
			->get()
			->row();
	}

	/**
	 * Get distinct periods by supervisor
	 * Shows all ranking stages
	 */
	public function getPeriodsBySupervisor($supervisorId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->distinct()
			->where('t.id_supervisor', $supervisorId)
			->where_in('r.status', ['pending_supervisor', 'published'])
			->order_by('r.periode', 'DESC')
			->get()
			->result();
	}

	public function countByManager($managerId)
	{
		$this->db->select('COUNT(DISTINCT r.id_ranking) as total');
		$this->db->from('ranking r');
		$this->db->join('customer_service cs', 'cs.id_cs = r.id_cs');
		$this->db->join('tim t', 't.id_tim = cs.id_tim');
		$this->db->join('pengguna supervisor', 'supervisor.id_user = t.id_supervisor');
		// gunakan field id_atasan untuk konsistensi hirarki manager
		$this->db->where('supervisor.id_atasan', $managerId);
		$this->db->where('r.status', 'published');

		$result = $this->db->get()->row();
		return $result ? (int)$result->total : 0;
	}

	/**
	 * Get top CS rankings filtered by manager and periode
	 *
	 * @param int $managerId
	 * @param string $periode
	 * @param int $limit
	 * @return array
	 */
	public function getTopCsByManager($managerId, $periode, $limit = 5)
	{
		$this->db->select('r.*, cs.nama_cs, cs.nik, t.nama_tim')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
			->where('supervisor.id_atasan', $managerId)
			->where('r.periode', $periode)
			->where('r.status', 'published')
			->order_by('r.peringkat', 'ASC')
			->limit($limit);

		return $this->db->get()->result();
	}

	/**
	 * Get rankings by periode for specific team (Leader scope)
	 * Shows: pending_leader, pending_supervisor, published
	 */
	public function getByPeriodeAndTeam($periode, $teamId)
	{
		$this->db->select('r.*, cs.nik, cs.nama_cs, p.nama_produk, k.nama_kanal')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('produk p', 'cs.id_produk = p.id_produk')
			->join('kanal k', 'cs.id_kanal = k.id_kanal')
			->where('cs.id_tim', $teamId)
			->where('r.periode', $periode)
			->order_by('r.peringkat', 'ASC');

		return $this->db->get()->result();
	}

	/**
	 * Get latest periode for specific team
	 * Shows all status (leader can see all ranking stages)
	 */
	public function getLatestPeriodeByTeam($teamId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->where('cs.id_tim', $teamId)
			->where_in('r.status', ['pending_leader', 'pending_supervisor', 'published'])
			->order_by('r.periode', 'DESC')
			->limit(1)
			->get()
			->row();
	}

	/**
	 * Get distinct periods for specific team
	 * Shows all ranking stages
	 */
	public function getPeriodsByTeam($teamId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->distinct()
			->where('cs.id_tim', $teamId)
			->where_in('r.status', ['pending_leader', 'pending_supervisor', 'published'])
			->order_by('r.periode', 'DESC')
			->get()
			->result();
	}

	/**
	 * Get top CS for specific team and periode
	 * Used by Leader dashboard
	 */
	public function getTopCsByTeam($teamId, $periode, $limit = 5)
	{
		$this->db->select('r.*, cs.nama_cs, cs.nik, p.nama_produk, k.nama_kanal')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('produk p', 'cs.id_produk = p.id_produk', 'left')
			->join('kanal k', 'cs.id_kanal = k.id_kanal', 'left')
			->where('cs.id_tim', $teamId)
			->where('r.periode', $periode)
			->where('r.status', 'published')
			->order_by('r.peringkat', 'ASC')
			->limit($limit);

		return $this->db->get()->result();
	}

	/**
	 * Count rankings by supervisor
	 */
	public function countBySupervisor($supervisorId)
	{
		$this->db->select('COUNT(DISTINCT r.id_ranking) as total');
		$this->db->from('ranking r');
		$this->db->join('customer_service cs', 'cs.id_cs = r.id_cs');
		$this->db->join('tim t', 't.id_tim = cs.id_tim');
		$this->db->where('t.id_supervisor', $supervisorId);
		$this->db->where('r.status', 'published');

		$result = $this->db->get()->row();
		return $result ? (int)$result->total : 0;
	}

	/**
	 * Get top CS rankings filtered by supervisor and periode
	 */
	public function getTopCsBySupervisor($supervisorId, $periode, $limit = 5)
	{
		$this->db->select('r.*, cs.nama_cs, cs.nik, t.nama_tim')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->where('t.id_supervisor', $supervisorId)
			->where('r.periode', $periode)
			->where('r.status', 'published')
			->order_by('r.peringkat', 'ASC')
			->limit($limit);

		return $this->db->get()->result();
	}

	/**
	 * Get latest periode for Junior Manager (through supervisor hierarchy)
	 */
	public function getLatestPeriodeByManager($managerId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
			->where('supervisor.id_atasan', $managerId)
			->where('r.status', 'published')
			->order_by('r.periode', 'DESC')
			->limit(1)
			->get()
			->row();
	}

	/**
	 * Get rankings by periode for Junior Manager with filter
	 */
	public function getByPeriodeByManager($periode, $managerId, $filter = [])
	{
		// Include all rankings (including rejected) with approval info
		$this->db->select('r.*,
			cs.nik, cs.nama_cs,
			t.nama_tim,
			p.nama_produk,
			k.nama_kanal,
			leader.nama_pengguna as approved_by_leader_name,
			supervisor_user.nama_pengguna as approved_by_supervisor_name')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('produk p', 'cs.id_produk = p.id_produk')
			->join('kanal k', 'cs.id_kanal = k.id_kanal')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
			->join('pengguna leader', 'r.approved_by_leader = leader.id_user', 'left')
			->join('pengguna supervisor_user', 'r.approved_by_supervisor = supervisor_user.id_user', 'left')
			->where('supervisor.id_atasan', $managerId)
			->where('r.periode', $periode);
		// Remove status filter to show all rankings including rejected

		if (!empty($filter['id_produk'])) {
			$this->db->where('cs.id_produk', $filter['id_produk']);
		}

		if (!empty($filter['id_kanal'])) {
			$this->db->where('cs.id_kanal', $filter['id_kanal']);
		}

		if (!empty($filter['id_tim'])) {
			$this->db->where('cs.id_tim', $filter['id_tim']);
		}

		return $this->db->order_by('r.peringkat', 'ASC')
			->get()
			->result();
	}

	/**
	 * Get distinct periods for Junior Manager
	 */
	public function getPeriodesByManager($managerId)
	{
		return $this->db->select('r.periode')
			->from("{$this->table} r")
			->join('customer_service cs', 'r.id_cs = cs.id_cs')
			->join('tim t', 'cs.id_tim = t.id_tim')
			->join('pengguna supervisor', 't.id_supervisor = supervisor.id_user')
			->distinct()
			->where('supervisor.id_atasan', $managerId)
			->where('r.status', 'published')
			->order_by('r.periode', 'DESC')
			->get()
			->result();
	}

	/**
	 * Get top rankings for dashboard (alias for getTopRankings)
	 * Used by Admin Dashboard
	 */
	public function getTopRankingsForDashboard($periode, $limit = 5)
	{
		return $this->getTopRankings($periode, $limit, ['status' => 'published']);
	}
}
