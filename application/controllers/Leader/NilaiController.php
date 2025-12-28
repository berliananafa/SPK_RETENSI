<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NilaiController extends Leader_Controller
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
     * Tampilkan nilai sub kriteria CS (read-only untuk leader)
     */
    public function index()
    {
        set_page_title('Nilai Sub Kriteria');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('leader/dashboard')],
            ['title' => 'Nilai Sub Kriteria']
        ]);

        enable_datatables();

        $userId = $this->session->userdata('user_id');

        // Get tim yang dipimpin leader
        $teams = $this->TimModel->getByLeader($userId);
        $team = !empty($teams) ? $teams[0] : null;

        // Default values
        $data['team'] = $team;
        $data['periodes'] = [];
        $data['cs_list'] = [];
        $data['kriteria_list'] = $this->KriteriaModel->all();
        $data['selected_periode'] = '';
        $data['selected_cs'] = '';
        $data['selected_kriteria'] = '';
        $data['nilai_data'] = [];
        $data['total_penilaian'] = 0;
        $data['total_cs'] = 0;
        $data['total_kriteria'] = count($data['kriteria_list']);
        $data['rata_rata'] = 0;

        if ($team) {
            // Get filter options
            $data['periodes'] = $this->NilaiModel->getDistinctPeriodesByTeam($team->id_tim);
            $data['cs_list'] = $this->CustomerServiceModel->getByTeam($team->id_tim);

            // Get nilai with filters
            $filter = ['id_tim' => $team->id_tim];

            if ($this->input->get('periode')) {
                $filter['periode'] = $this->input->get('periode');
                $data['selected_periode'] = $this->input->get('periode');
            }
            if ($this->input->get('id_cs')) {
                $filter['id_cs'] = $this->input->get('id_cs');
                $data['selected_cs'] = $this->input->get('id_cs');
            }
            if ($this->input->get('id_kriteria')) {
                $filter['id_kriteria'] = $this->input->get('id_kriteria');
                $data['selected_kriteria'] = $this->input->get('id_kriteria');
            }

            $data['nilai_data'] = $this->NilaiModel->getAllWithDetails($filter);

            // Calculate summary cards
            $data['total_penilaian'] = count($data['nilai_data']);
            $data['total_cs'] = count(array_unique(array_column($data['nilai_data'], 'id_cs')));

            // Calculate average value
            if (!empty($data['nilai_data'])) {
                $total_nilai = 0;
                foreach ($data['nilai_data'] as $nilai) {
                    $total_nilai += (float)($nilai->nilai ?? 0);
                }
                $data['rata_rata'] = $total_nilai / count($data['nilai_data']);
            }
        }

        render_layout('leader/nilai/index', $data);
    }
}
