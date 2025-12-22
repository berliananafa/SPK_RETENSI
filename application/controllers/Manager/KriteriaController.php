<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KriteriaController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('KriteriaModel');
    }

    /**
     * Tampilkan daftar kriteria (view-only untuk manager) dengan aksi setujui
     */
    public function index()
    {
        set_page_title('Kriteria - Junior Manager');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager')],
            ['title' => 'Kriteria']
        ]);

        // Ambil semua kriteria (atau bisa difilter jika perlu scope)
        $data['kriteria'] = $this->KriteriaModel->getAll();

        render_layout('manager/kriteria/index', $data);
    }

    /**
     * Setujui kriteria (POST) oleh junior manager
     */
    public function approve($id = null)
    {
        if (!$this->input->is_ajax_request() && $this->input->method() !== 'post') {
            show_error('Invalid request method', 405);
        }

        if (empty($id)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'ID kriteria tidak diberikan']);
            return;
        }

        // Ambil kriteria
        $k = $this->KriteriaModel->getById($id);
        if (!$k) {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'error', 'message' => 'Kriteria tidak ditemukan']);
            return;
        }

        // Update status/approved flag. Sesuaikan field sesuai model/database.
        $update = [
            'status' => 'approved',
            'approved_by' => $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ];

        $saved = $this->KriteriaModel->update($id, $update);
        if ($saved) {
            $this->output->set_content_type('application/json');
            echo json_encode(['status' => 'success', 'message' => 'Kriteria berhasil disetujui']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan']);
        }
    }
}
