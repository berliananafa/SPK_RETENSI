<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Kriteria (Junior Manager)
 * 
 * Junior Manager hanya memiliki akses untuk:
 * - Melihat daftar kriteria
 * - Melihat detail kriteria
 * - Menyetujui (approve) atau menolak (reject) kriteria
 */
class KriteriaController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load model utama Kriteria
        $this->load->model('KriteriaModel', 'kriteria');
    }

    /**
     * Menampilkan daftar seluruh kriteria
     */
    public function index()
    {
        // Set judul halaman
        set_page_title('Kriteria');

        // Set breadcrumb navigasi
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Kriteria']
        ]);

        // Aktifkan plugin DataTables dan SweetAlert
        enable_datatables();
        enable_sweetalert();

        // Ambil seluruh data kriteria (sudah diurutkan dari model)
        $data['kriteria'] = $this->kriteria->getAllOrdered();

        // Hitung statistik approval
        $data['total_kriteria'] = count($data['kriteria']);
        $data['total_pending'] = 0;
        $data['total_approved'] = 0;
        $data['total_rejected'] = 0;

        foreach ($data['kriteria'] as $k) {
            $status = $k->status_approval ?? 'pending';
            if ($status === 'pending') {
                $data['total_pending']++;
            } elseif ($status === 'approved') {
                $data['total_approved']++;
            } elseif ($status === 'rejected') {
                $data['total_rejected']++;
            }
        }

        // Render halaman index kriteria
        render_layout('manager/kriteria/index', $data);
    }

    /**
     * Menyetujui kriteria (Approve)
     * Request hanya boleh melalui AJAX dan metode POST
     */
    public function approve($id)
    {
        // Validasi request AJAX dan metode POST
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            show_error('Invalid request method', 405);
        }

        // Cari data kriteria berdasarkan ID
        $kriteria = $this->kriteria->find($id);
        if (!$kriteria) {
            $this->output->set_status_header(404);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Kriteria tidak ditemukan'
            ]);
            return;
        }

        // Ambil ID user (Junior Manager) yang melakukan approval
        $userId = $this->session->userdata('user_id');

        // Data yang akan diupdate
        $updateData = [
            'status_approval' => 'approved',
            'approved_by'     => $userId,
            'approved_at'     => date('Y-m-d H:i:s')
        ];

        // Proses update
        if ($this->kriteria->updateById($id, $updateData)) {
            $this->output->set_content_type('application/json');
            echo json_encode([
                'status'  => 'success',
                'message' => 'Kriteria berhasil disetujui'
            ]);
        } else {
            $this->output->set_status_header(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menyimpan perubahan'
            ]);
        }
    }

    /**
     * Menolak kriteria (Reject)
     * Request hanya boleh melalui AJAX dan metode POST
     */
    public function reject($id)
    {
        // Validasi request AJAX dan metode POST
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            show_error('Invalid request method', 405);
        }

        // Cari data kriteria berdasarkan ID
        $kriteria = $this->kriteria->find($id);
        if (!$kriteria) {
            $this->output->set_status_header(404);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Kriteria tidak ditemukan'
            ]);
            return;
        }

        // Ambil ID user yang melakukan penolakan
        $userId = $this->session->userdata('user_id');

        // Ambil alasan/keterangan penolakan
        $keterangan = $this->input->post('keterangan');

        // Data yang akan diupdate
        $updateData = [
            'status_approval' => 'rejected',
            'approved_by'     => $userId,
            'approved_at'     => date('Y-m-d H:i:s'),
            'rejection_note'  => $keterangan
        ];

        // Proses update
        if ($this->kriteria->updateById($id, $updateData)) {
            $this->output->set_content_type('application/json');
            echo json_encode([
                'status'  => 'success',
                'message' => 'Kriteria berhasil ditolak'
            ]);
        } else {
            $this->output->set_status_header(500);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menyimpan perubahan'
            ]);
        }
    }

    /**
     * Menampilkan detail kriteria beserta sub-kriteria
     */
    public function detail($id)
    {
        // Ambil data kriteria
        $kriteria = $this->kriteria->find($id);
        if (!$kriteria) {
            $this->session->set_flashdata('error', 'Kriteria tidak ditemukan!');
            redirect('junior-manager/kriteria');
            return;
        }

        // Set judul halaman
        set_page_title('Detail Kriteria');
		
        // Set breadcrumb navigasi
        set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Kriteria', 'url' => base_url('junior-manager/kriteria')],
            ['title' => 'Detail']
        ]);
		enable_sweetalert();
		
        // Load model Sub Kriteria
        $this->load->model('SubKriteriaModel');

        // Data yang dikirim ke view
        $data['kriteria']     = $kriteria;
        $data['sub_kriteria'] = $this->SubKriteriaModel->getByKriteria($id);

        // Hitung statistik sub kriteria
        $data['total_sub_kriteria'] = count($data['sub_kriteria']);

        // Jika kriteria sudah di-approve/reject, ambil data user yang menyetujui
        if ($kriteria->approved_by) {
            $this->load->model('PenggunaModel');
            $data['approved_by_user'] = $this->PenggunaModel->find($kriteria->approved_by);
        }

        // Render halaman detail kriteria
        render_layout('manager/kriteria/detail', $data);
    }
}
