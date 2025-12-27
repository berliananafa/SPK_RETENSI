<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ranking Controller for Junior Manager
 * Read-only view of ranking results under manager's scope
 * No approval actions - monitoring only
 */
class RankingController extends Manager_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['RankingModel', 'ProdukModel', 'KanalModel', 'TimModel']);
    }

    public function index()
    {
        set_page_title('Hasil Ranking');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('junior-manager/dashboard')],
            ['title' => 'Hasil Ranking']
        ]);

        enable_datatables();
        enable_charts();

        $managerId = $this->session->userdata('user_id');

        // Get latest periode
        $latestPeriode = $this->RankingModel->getLatestPeriodeByManager($managerId);
        $selectedPeriode = $this->input->get('periode') ?: ($latestPeriode->periode ?? null);

        // Get filter options
        $data['periodes'] = $this->RankingModel->getPeriodesByManager($managerId);
        $data['produks'] = $this->ProdukModel->all();
        $data['kanals'] = $this->KanalModel->all();
        $data['teams'] = $this->TimModel->getByJuniorManager($managerId);

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
        $data['selected_produk'] = $this->input->get('id_produk') ?? '';
        $data['selected_kanal'] = $this->input->get('id_kanal') ?? '';
        $data['selected_tim'] = $this->input->get('id_tim') ?? '';
        $data['rankings'] = [];

        if ($selectedPeriode) {
            $data['rankings'] = $this->RankingModel->getByPeriodeByManager($selectedPeriode, $managerId, $filter);
        }

        render_layout('manager/ranking/index', $data);
    }
}
