<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingController extends Leader_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['RankingModel', 'NilaiModel', 'CustomerServiceModel']);
		$this->load->library('ProfileMatching');
	}

	public function index()
	{
		set_page_title('Ranking Tim');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
			['title' => 'Ranking Tim']
		]);

		enable_datatables();
		enable_charts();
		enable_sweetalert();

		$userId = $this->session->userdata('user_id');

		// Get tim yang dipimpin leader
		$this->load->model(['TimModel', 'ProdukModel', 'KanalModel']);
		$teams = $this->TimModel->getByLeader($userId);
		$team = !empty($teams) ? $teams[0] : null;

		if (!$team) {
			$this->session->set_flashdata('warning', 'Anda belum memimpin tim');
			redirect('leader/dashboard');
			return;
		}

		// Get latest periode
		$latestPeriode = $this->RankingModel->getLatestPeriodeByTeam($team->id_tim);
		$selectedPeriode = $this->input->get('periode') ?: ($latestPeriode->periode ?? date('Y-m'));

		// Get filter options
		$data['periodes'] = $this->RankingModel->getPeriodsByTeam($team->id_tim);
		$data['team'] = $team;
		$data['selected_periode'] = $selectedPeriode;
		$data['rankings'] = [];

		// Ambil semua produk aktif
		$this->load->model('ProdukModel');
		$data['produk_list'] = $this->ProdukModel->getActive();
		$data['selected_produk'] = $this->input->get('id_produk') ?? '';

		// Query ranking dengan filter
		if ($selectedPeriode) {
			if (!empty($data['selected_produk'])) {
				$data['rankings'] = $this->RankingModel->getByPeriodeTeamAndProduk(
					$selectedPeriode,
					$team->id_tim,
					$data['selected_produk']
				);
			} else {
				$data['rankings'] = $this->RankingModel->getByPeriodeAndTeam(
					$selectedPeriode,
					$team->id_tim
				);
			}
		}

		render_layout('leader/ranking/index', $data);
	}

	/**
	 * Approve ranking oleh leader (AJAX POST)
	 * Leader approve first, then goes to supervisor
	 */
	public function approve($id = null)
	{
		if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
			show_error('Invalid request method', 405);
		}

		if (empty($id)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'ID ranking tidak diberikan']);
			return;
		}

		$leaderId = $this->session->userdata('user_id');
		$note = $this->input->post('note') ?? '';

		// Ambil ranking dengan detail
		$ranking = $this->RankingModel->getByIdWithDetails($id);
		if (!$ranking) {
			$this->output->set_status_header(404);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak ditemukan']);
			return;
		}

		// Validasi: ranking harus milik tim yang dipimpin leader ini
		$this->load->model('TimModel');
		$teams = $this->TimModel->getByLeader($leaderId);
		$team = !empty($teams) ? $teams[0] : null;

		if (!$team || $team->id_tim != $ranking->id_tim) {
			$this->output->set_status_header(403);
			echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke ranking ini']);
			return;
		}

		// Validasi status: harus pending_leader
		if ($ranking->status !== 'pending_leader') {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak dalam status pending leader']);
			return;
		}

		// Update: Leader approve -> goes to Supervisor
		$update = [
			'approved_by_leader' => $leaderId,
			'approved_at_leader' => date('Y-m-d H:i:s'),
			'leader_note' => $note,
			'status' => 'pending_supervisor'
		];

		$saved = $this->RankingModel->updateById($id, $update);
		if ($saved) {
			$this->output->set_content_type('application/json');
			echo json_encode(['status' => 'success', 'message' => 'Ranking berhasil disetujui dan diteruskan ke Supervisor']);
		} else {
			$this->output->set_status_header(500);
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
		}
	}

	/**
	 * Reject ranking oleh leader (AJAX POST)
	 */
	public function reject($id = null)
	{
		if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
			show_error('Invalid request method', 405);
		}

		if (empty($id)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'ID ranking tidak diberikan']);
			return;
		}

		$leaderId = $this->session->userdata('user_id');
		$note = $this->input->post('note') ?? '';

		if (empty($note)) {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Catatan penolakan harus diisi']);
			return;
		}

		// Ambil ranking dengan detail
		$ranking = $this->RankingModel->getByIdWithDetails($id);
		if (!$ranking) {
			$this->output->set_status_header(404);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak ditemukan']);
			return;
		}

		// Validasi: ranking harus milik tim yang dipimpin leader ini
		$this->load->model('TimModel');
		$teams = $this->TimModel->getByLeader($leaderId);
		$team = !empty($teams) ? $teams[0] : null;

		if (!$team || $team->id_tim != $ranking->id_tim) {
			$this->output->set_status_header(403);
			echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke ranking ini']);
			return;
		}

		// Validasi status
		if ($ranking->status !== 'pending_leader') {
			$this->output->set_status_header(400);
			echo json_encode(['status' => 'error', 'message' => 'Ranking tidak dalam status pending leader']);
			return;
		}

		// Update: Leader reject
		$update = [
			'approved_by_leader' => $leaderId,
			'approved_at_leader' => date('Y-m-d H:i:s'),
			'leader_note' => $note,
			'status' => 'rejected_leader'
		];

		$saved = $this->RankingModel->updateById($id, $update);
		if ($saved) {
			$this->output->set_content_type('application/json');
			echo json_encode(['status' => 'success', 'message' => 'Ranking berhasil ditolak']);
		} else {
			$this->output->set_status_header(500);
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
		}
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

		$leaderId = $this->session->userdata('user_id');

		// Validasi akses: CS harus dari tim yang dipimpin leader
		$this->load->model('TimModel');
		$teams = $this->TimModel->getByLeader($leaderId);
		$team = !empty($teams) ? $teams[0] : null;

		if (!$team) {
			echo '<div class="p-4 text-center text-danger">Anda belum memimpin tim.</div>';
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

		// Validasi: CS harus dari tim leader
		if ($csInfo->id_tim != $team->id_tim) {
			echo '<div class="p-4 text-center text-danger">CS tidak termasuk dalam tim Anda.</div>';
			return;
		}

		$namaLeader = $team->nama_leader ?? null;

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
}
