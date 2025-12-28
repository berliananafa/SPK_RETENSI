<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingController extends Supervisor_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['RankingModel', 'ProdukModel', 'KanalModel', 'TimModel', 'NilaiModel', 'CustomerServiceModel']);
		$this->load->library('ProfileMatching');
	}

	public function index()
	{
		set_page_title('Hasil Ranking');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('supervisor/dashboard')],
			['title' => 'Hasil Ranking']
		]);

		enable_datatables();
		enable_charts();
		enable_sweetalert();

		$userId = $this->session->userdata('user_id');

		// Get latest periode
		$latestPeriode = $this->RankingModel->getLatestPeriodeBySupervisor($userId);
		$selectedPeriode = $this->input->get('periode') ?: ($latestPeriode->periode ?? null);

		// Get filter options
		$data['periodes'] = $this->RankingModel->getPeriodsBySupervisor($userId);
		$data['produks'] = $this->ProdukModel->all();
		$data['kanals'] = $this->KanalModel->all();
		$data['teams'] = $this->TimModel->getBySupervisorWithDetails($userId);

		// Get rankings with filters
		$filter = [];
		if ($this->input->get('id_produk')) {
			$filter['id_produk'] = $this->input->get('id_produk');
		}
		if ($this->input->get('id_kanal')) {
			$filter['id_kanal'] = $this->input->get('id_kanal');
		}
		if ($this->input->get('id_tim')) {
			$filter['id_tim'] = $this->input->get('id_tim');
		}

		$data['selected_periode'] = $selectedPeriode;
		$data['rankings'] = [];

		if ($selectedPeriode) {
			$data['rankings'] = $this->RankingModel->getByPeriodeBySupervisor($selectedPeriode, $userId, $filter);
		}

		render_layout('supervisor/ranking/index', $data);
	}

	/**
	 * Detail ranking per CS (AJAX load untuk modal)
	 */
	public function detail()
	{
		$idCs = $this->input->get('id');
		$periode = $this->input->get('periode') ?? date('Y-m');

		if (empty($idCs)) {
			echo '<div class="p-4 text-center text-danger">Parameter ID tidak ditemukan.</div>';
			return;
		}

		// Ambil seluruh nilai CS
		$nilaiAll = $this->NilaiModel->getByCustomerService($idCs);

		// Filter berdasarkan periode
		$nilai = array_filter($nilaiAll, fn($r) => ($r->periode ?? '') == $periode);

		if (empty($nilai)) {
			echo '<div class="p-4 text-center text-muted">Belum ada penilaian untuk periode ini.</div>';
			return;
		}

		// Hitung NCF, NSF, dan skor akhir
		$rows = [];
		$totalCF = $totalSF = 0;
		$itemCF = 0;
		$itemSF = 0;

		foreach ($nilai as $row) {
			$nilaiAktual = (float) $row->nilai;
			$bobotSub = (float) ($row->bobot_sub ?? 0);

			// Hitung GAP
			$gap = $this->profilematching->hitungGap(
				$row->id_sub_kriteria,
				$nilaiAktual
			);

			$jenis = strtolower(trim($row->jenis_kriteria ?? ''));

			// Akumulasi Core Factor & Secondary Factor
			if ($jenis === 'core_factor') {
				$totalCF += $gap['gap'];
				$itemCF++;
			} else {
				$totalSF += $gap['gap'];
				$itemSF++;
			}

			// Data detail tabel
			$rows[] = [
				'kode_kriteria' => $row->kode_kriteria ?? '-',
				'nama_kriteria' => $row->nama_kriteria ?? '-',
				'nama_sub'      => $row->nama_sub_kriteria ?? '-',
				'nilai_asli'    => $nilaiAktual,
				'nilai_gap'     => $gap['gap'],
				'bobot_sub'     => $bobotSub,
				'jenis'         => $jenis,
			];
		}

		// Hitung NCF, NSF, dan skor akhir
		$ncf = $itemCF > 0 ? ($totalCF / $itemCF) : 0;
		$nsf = $itemSF > 0 ? ($totalSF / $itemSF) : 0;
		$skorAkhir = ($ncf * 0.9) + ($nsf * 0.1);

		// Ambil data CS & tim
		$csInfo = $this->CustomerServiceModel->getByIdWithDetails($idCs);

		$namaLeader = null;
		if (!empty($csInfo->id_tim)) {
			$this->load->model('TimModel');
			$timInfo = $this->TimModel->getByIdWithDetails($csInfo->id_tim);
			$namaLeader = $timInfo->nama_leader ?? null;
		}

		// Ambil info ranking dari database (untuk approval info)
		$rankingInfo = $this->db->select('ranking.*,
				leader.nama_pengguna as approved_by_leader_name,
				supervisor.nama_pengguna as approved_by_supervisor_name')
			->from('ranking')
			->join('pengguna leader', 'ranking.approved_by_leader = leader.id_user', 'left')
			->join('pengguna supervisor', 'ranking.approved_by_supervisor = supervisor.id_user', 'left')
			->where('ranking.id_cs', $idCs)
			->where('ranking.periode', $periode)
			->get()
			->row();

		// Render view detail (reuse admin view)
		$this->load->view('admin/ranking/detail', [
			'rows'      => $rows,
			'ncf'       => round($ncf, 4),
			'nsf'       => round($nsf, 4),
			'skor'      => round($skorAkhir, 6),
			'total_cf'  => $totalCF,
			'item_cf'   => $itemCF,
			'total_sf'  => $totalSF,
			'item_sf'   => $itemSF,
			'periode'   => $periode,
			'id_cs'     => $idCs,
			'cs' => (object)[
				'nama_cs'     => $csInfo->nama_cs ?? '-',
				'nik'         => $csInfo->nik ?? '-',
				'nama_tim'    => $csInfo->nama_tim ?? null,
				'nama_produk' => $csInfo->nama_produk ?? null,
				'nama_leader' => $namaLeader,
			],
			'ranking_info' => $rankingInfo
		]);
	}

	/**
	 * Bulk approve semua ranking pending_supervisor untuk periode tertentu
	 */
	public function bulkApprove()
	{
		if (!$this->input->is_ajax_request() && $this->input->method() !== 'post') {
			show_error('Invalid request method', 405);
		}

		$supervisorId = $this->session->userdata('user_id');
		$periode = $this->input->post('periode');

		if (empty($periode)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Periode tidak diberikan']);
			return;
		}

		// Ambil semua ranking pending_supervisor untuk periode ini yang milik tim supervisor
		$rankings = $this->db->select('ranking.*')
			->from('ranking')
			->join('customer_service', 'ranking.id_cs = customer_service.id_cs')
			->join('tim', 'customer_service.id_tim = tim.id_tim')
			->where('tim.id_supervisor', $supervisorId)
			->where('ranking.periode', $periode)
			->where('ranking.status', 'pending_supervisor')
			->get()
			->result();

		if (empty($rankings)) {
			$this->output->set_status_header(404);
			echo json_encode(['status' => 'error', 'message' => 'Tidak ada ranking yang menunggu approval']);
			return;
		}

		// Update semua ranking sekaligus
		$now = date('Y-m-d H:i:s');
		$updated = 0;

		foreach ($rankings as $rank) {
			$update = [
				'approved_by_supervisor' => $supervisorId,
				'approved_at_supervisor' => $now,
				'supervisor_note' => 'Bulk approval',
				'status' => 'published'
			];

			if ($this->RankingModel->update($rank->id_ranking, $update)) {
				$updated++;
			}
		}

		$this->output->set_content_type('application/json');
		echo json_encode([
			'status' => 'success',
			'message' => "Berhasil menyetujui {$updated} ranking dan mempublikasikannya"
		]);
	}

	/**
	 * Approve ranking oleh supervisor (AJAX POST)
	 * Supervisor approve after leader, then publish
	 */
	public function approve($id = null)
	{
		if (!$this->input->is_ajax_request() && $this->input->method() !== 'post') {
			show_error('Invalid request method', 405);
		}

		if (empty($id)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'ID ranking tidak diberikan']);
			return;
		}

		$supervisorId = $this->session->userdata('user_id');
		$note = $this->input->post('note') ?? '';

		// Ambil ranking
		$ranking = $this->RankingModel->getByIdWithDetails($id);
		if (!$ranking) {
			$this->output->set_status_header(404);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak ditemukan']);
			return;
		}

		// Validasi: ranking harus milik tim di bawah supervisor ini
		$teamBelongsToSupervisor = $this->db->select('id_tim')
			->from('tim')
			->where('id_tim', $ranking->id_tim)
			->where('id_supervisor', $supervisorId)
			->get()
			->row();

		if (!$teamBelongsToSupervisor) {
			$this->output->set_status_header(403);
			echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke ranking ini']);
			return;
		}

		// Validasi status: harus pending_supervisor (sudah diapprove leader)
		if ($ranking->status !== 'pending_supervisor') {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak dalam status pending supervisor']);
			return;
		}

		// Update: Supervisor approve -> Published (final)
		$update = [
			'approved_by_supervisor' => $supervisorId,
			'approved_at_supervisor' => date('Y-m-d H:i:s'),
			'supervisor_note' => $note,
			'status' => 'published'
		];

		$saved = $this->RankingModel->update($id, $update);
		if ($saved) {
			$this->output->set_content_type('application/json');
			echo json_encode(['status' => 'success', 'message' => 'Ranking berhasil disetujui dan dipublikasikan']);
		} else {
			$this->output->set_status_header(500);
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
		}
	}

	/**
	 * Reject ranking oleh supervisor (AJAX POST)
	 */
	public function reject($id = null)
	{
		if (!$this->input->is_ajax_request() && $this->input->method() !== 'post') {
			show_error('Invalid request method', 405);
		}

		if (empty($id)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'ID ranking tidak diberikan']);
			return;
		}

		$supervisorId = $this->session->userdata('user_id');
		$note = $this->input->post('note') ?? '';

		if (empty($note)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Catatan penolakan harus diisi']);
			return;
		}

		// Ambil ranking
		$ranking = $this->RankingModel->getByIdWithDetails($id);
		if (!$ranking) {
			$this->output->set_status_header(404);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak ditemukan']);
			return;
		}

		// Validasi: ranking harus milik tim di bawah supervisor ini
		$teamBelongsToSupervisor = $this->db->select('id_tim')
			->from('tim')
			->where('id_tim', $ranking->id_tim)
			->where('id_supervisor', $supervisorId)
			->get()
			->row();

		if (!$teamBelongsToSupervisor) {
			$this->output->set_status_header(403);
			echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke ranking ini']);
			return;
		}

		// Validasi status
		if ($ranking->status !== 'pending_supervisor') {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak dalam status pending supervisor']);
			return;
		}

		// Update: Supervisor reject -> kembali ke Leader untuk revisi
		$update = [
			'approved_by_supervisor' => $supervisorId,
			'approved_at_supervisor' => date('Y-m-d H:i:s'),
			'supervisor_note' => $note,
			'status' => 'rejected_supervisor'
		];

		$saved = $this->RankingModel->update($id, $update);
		if ($saved) {
			$this->output->set_content_type('application/json');
			echo json_encode(['status' => 'success', 'message' => 'Ranking berhasil ditolak']);
		} else {
			$this->output->set_status_header(500);
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
		}
	}
}
