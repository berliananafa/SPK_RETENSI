<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RankingController extends Leader_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RankingModel');
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
        $selectedPeriode = $this->input->get('periode') ?: ($latestPeriode->periode ?? null);

        // Get filter options
        $data['periodes'] = $this->RankingModel->getPeriodsByTeam($team->id_tim);
        $data['team'] = $team;
        $data['selected_periode'] = $selectedPeriode;
        $data['rankings'] = [];

        if ($selectedPeriode) {
            $data['rankings'] = $this->RankingModel->getByPeriodeAndTeam($selectedPeriode, $team->id_tim);
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

        $saved = $this->RankingModel->update($id, $update);
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
