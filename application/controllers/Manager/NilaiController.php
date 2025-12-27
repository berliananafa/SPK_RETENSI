<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Nilai Controller for Junior Manager
 * Read-only view of nilai/scores under manager's scope
 * No input/edit/delete actions - monitoring only
 */
class NilaiController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'NilaiModel',
            'KriteriaModel',
            'SubKriteriaModel',
            'CustomerServiceModel',
            'TimModel'
        ]);
    }

    /**
     * Tampilkan nilai sub kriteria CS (read-only untuk Junior Manager)
     * Hanya melihat hasil penilaian, tidak ada aksi input/edit/delete
     */
    public function index()
    {
        set_page_title('Monitoring Nilai Sub Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Monitoring Nilai']
        ]);

        enable_datatables();

        $managerId = $this->session->userdata('user_id');

        // Get filter options (scoped to Junior Manager)
        $data['periodes'] = $this->NilaiModel->getDistinctPeriodesByManager($managerId);
        $data['teams'] = $this->TimModel->getByJuniorManager($managerId);
        $data['cs_list'] = $this->CustomerServiceModel->getByJuniorManager($managerId);
        $data['kriteria_list'] = $this->KriteriaModel->all();

        // Get nilai with filters
        $filter = ['manager_id' => $managerId];

        if ($this->input->get('periode')) {
            $filter['periode'] = $this->input->get('periode');
        }
        if ($this->input->get('id_tim')) {
            $filter['id_tim'] = $this->input->get('id_tim');
        }
        if ($this->input->get('id_cs')) {
            $filter['id_cs'] = $this->input->get('id_cs');
        }
        if ($this->input->get('id_kriteria')) {
            $filter['id_kriteria'] = $this->input->get('id_kriteria');
        }

        $data['selected_periode'] = $this->input->get('periode') ?? '';
        $data['selected_tim'] = $this->input->get('id_tim') ?? '';
        $data['selected_cs'] = $this->input->get('id_cs') ?? '';
        $data['selected_kriteria'] = $this->input->get('id_kriteria') ?? '';
        $data['nilai_data'] = $this->NilaiModel->getNilaiWithDetailsByManager($managerId, $filter);

        // Calculate summary cards
        $data['total_penilaian'] = count($data['nilai_data']);
        $data['total_cs'] = count(array_unique(array_column($data['nilai_data'], 'id_cs')));
        $data['total_kriteria'] = count($data['kriteria_list']);

        // Calculate average value
        $data['rata_rata'] = 0;
        if (!empty($data['nilai_data'])) {
            $total_nilai = 0;
            foreach ($data['nilai_data'] as $nilai) {
                $total_nilai += (float)($nilai->nilai ?? 0);
            }
            $data['rata_rata'] = $total_nilai / count($data['nilai_data']);
        }

        render_layout('manager/nilai/index', $data);
    }
}
