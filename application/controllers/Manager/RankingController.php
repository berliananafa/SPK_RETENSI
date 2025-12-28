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
        $this->load->model(['RankingModel', 'ProdukModel', 'KanalModel', 'TimModel', 'NilaiModel', 'CustomerServiceModel']);
        $this->load->library('ProfileMatching');
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
            // Get rankings dengan approval info
            $rankings = $this->RankingModel->getByPeriodeByManager($selectedPeriode, $managerId, $filter);

            // Enrich dengan NCF dan NSF
            $data['rankings'] = $this->_enrichRankingsWithNcfNsf($rankings, $selectedPeriode);
        }

        render_layout('manager/ranking/index', $data);
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
     * Helper: Tambahkan nilai NCF dan NSF ke ranking dari database
     * Menghitung ulang dari data nilai yang ada
     */
    private function _enrichRankingsWithNcfNsf($rankings, $periode)
    {
        foreach ($rankings as $rank) {
            // Ambil seluruh nilai CS untuk periode ini
            $nilaiAll = $this->NilaiModel->getByCustomerService($rank->id_cs);
            $nilai = array_filter($nilaiAll, fn($r) => ($r->periode ?? '') == $periode);

            // Hitung NCF dan NSF
            $totalCF = $totalSF = 0;
            $itemCF = $itemSF = 0;

            foreach ($nilai as $row) {
                $nilaiAktual = (float) $row->nilai;

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
            }

            // Hitung NCF dan NSF
            $ncf = $itemCF > 0 ? ($totalCF / $itemCF) : 0;
            $nsf = $itemSF > 0 ? ($totalSF / $itemSF) : 0;

            // Tambahkan ke objek ranking
            $rank->ncf = round($ncf, 4);
            $rank->nsf = round($nsf, 4);

            // Pastikan skor_akhir juga ada (copy dari nilai_akhir jika belum ada)
            if (!isset($rank->skor_akhir)) {
                $rank->skor_akhir = $rank->nilai_akhir ?? 0;
            }
        }

        return $rankings;
    }
}
