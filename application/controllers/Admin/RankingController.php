<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RankingController extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('RankingModel');
		$this->load->model('NilaiModel');
		$this->load->model('KonversiModel');
		$this->load->model('TimModel');
		$this->load->model('ProdukModel');
		$this->load->library('ProfileMatching');
	}

	/* ======================================================
	 * INDEX - Tampilkan Hasil Ranking
	 * ====================================================== */
	public function index()
	{
		set_page_title('Hasil Ranking');
		set_breadcrumb([
			['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
			['title' => 'Hasil Ranking']
		]);

		enable_datatables();
		enable_charts();

		// Get filters
		$periode = $this->input->get('periode') ?? date('Y-m');
		$idTim = $this->input->get('tim');
		$idProduk = $this->input->get('produk');

		// Build filter
		$filter = ['periode' => $periode];
		if ($idTim) $filter['id_tim'] = $idTim;
		if ($idProduk) $filter['id_produk'] = $idProduk;

		// Get data & calculate ranking
		$penilaian = $this->NilaiModel->getAllWithDetails($filter);
		$rankings = $this->profilematching->hitungRanking($penilaian, $periode);

		render_layout('admin/ranking/index', [
			'rankings' => $rankings,
			'filter_periode' => $periode,
			'filter_tim' => $idTim,
			'filter_produk' => $idProduk,
			'tim' => $this->TimModel->all(),
			'produk' => $this->ProdukModel->all()
		]);
	}

	/* ======================================================
	 * PROCESS - Proses dan Simpan Ranking ke Database
	 * ====================================================== */
	public function process()
	{
		// Validasi input
		$this->load->library('form_validation');
		$this->form_validation->set_rules('periode', 'Periode', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('admin/ranking');
			return;
		}

		$periode = $this->input->post('periode', true);
		$idTim = $this->input->post('tim') ?: null;

		// Get data
		$filter = ['periode' => $periode];
		if ($idTim) $filter['id_tim'] = $idTim;

		$penilaian = $this->NilaiModel->getAllWithDetails($filter);

		if (empty($penilaian)) {
			$this->session->set_flashdata('error', 'Tidak ada data penilaian pada periode ini!');
			redirect('admin/ranking');
			return;
		}

		// Calculate ranking + konversi
		$result = $this->profilematching->hitungRanking($penilaian, $periode, true);

		if (empty($result['rankings'])) {
			$this->session->set_flashdata('error', 'Gagal memproses ranking.');
			redirect('admin/ranking');
			return;
		}

		// Save to database
		$this->db->trans_start();

		// 1. Save konversi
		if (!empty($result['konversi'])) {
			$csIds = array_unique(array_column($result['konversi'], 'id_cs'));
			$this->db->where_in('id_cs', $csIds)->delete('konversi');
			
			// Normalize data structure - ensure all records have same keys
			$cleanKonversi = [];
			foreach ($result['konversi'] as $item) {
				// Skip records without valid id_range (FK constraint)
				if (empty($item['id_range'])) {
					continue;
				}
				
				// Ensure consistent structure with explicit type casting
				$cleanKonversi[] = [
					'id_cs'           => (int) $item['id_cs'],
					'id_sub_kriteria' => (int) $item['id_sub_kriteria'],
					'id_range'        => (int) $item['id_range'],
					'nilai_asli'      => (float) $item['nilai_asli'],
					'nilai_konversi'  => (float) $item['nilai_konversi'],
				];
			}
			
			if (!empty($cleanKonversi)) {
				$this->db->insert_batch('konversi', $cleanKonversi);
			}
		}

		// 2. Delete old ranking
		$this->db->where('periode', $periode);
		if ($idTim) {
			$csIds = $this->db->select('id_cs')->from('customer_service')
				->where('id_tim', $idTim)->get()->result_array();
			if (!empty($csIds)) {
				$this->db->where_in('id_cs', array_column($csIds, 'id_cs'));
			}
		}
		$this->db->delete('ranking');

		// 3. Insert new ranking
		$bulkData = [];
		$now = date('Y-m-d H:i:s');
		foreach ($result['rankings'] as $r) {
			$bulkData[] = [
				'id_cs' => $r->id_cs,
				'id_produk' => $r->id_produk ?? null,
				'nilai_akhir' => $r->skor_akhir,
				'peringkat' => $r->peringkat,
				'periode' => $periode,
				'status' => 'published',
				'created_at' => $now,
				'updated_at' => $now,
			];
		}
		$this->db->insert_batch('ranking', $bulkData);

		$this->db->trans_complete();

		// Show result
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('success', 'Ranking berhasil diproses dan disimpan.');
		} else {
			$this->session->set_flashdata('error', 'Gagal menyimpan ranking.');
		}

		redirect('admin/ranking?periode=' . $periode);
	}

	/* ======================================================
	 * EXPORT - Export Ranking ke Excel
	 * ====================================================== */
	public function export()
	{
		// Get filters - sama seperti index
		$periode = $this->input->get('periode') ?? date('Y-m');
		$idTim = $this->input->get('tim');
		$idProduk = $this->input->get('produk');

		// Build filter
		$filter = ['periode' => $periode];
		if ($idTim) $filter['id_tim'] = $idTim;
		if ($idProduk) $filter['id_produk'] = $idProduk;

		// Get data & calculate ranking
		$penilaian = $this->NilaiModel->getAllWithDetails($filter);
		$rankings = $this->profilematching->hitungRanking($penilaian, $periode);

		if (empty($rankings)) {
			$this->session->set_flashdata('error', 'Tidak ada data ranking untuk periode ini.');
			redirect('admin/ranking');
			return;
		}

		// Prepare filter info untuk Excel
		$filterInfo = [];
		if ($idTim) {
			$timInfo = $this->TimModel->find($idTim);
			$filterInfo['tim'] = $timInfo->nama_tim ?? '-';
		}
		if ($idProduk) {
			$produkInfo = $this->ProdukModel->find($idProduk);
			$filterInfo['produk'] = $produkInfo->nama_produk ?? '-';
		}

		// Load library & export
		$this->load->library('ExportLaporan');
		$this->exportlaporan->exportRanking($rankings, $periode, $filterInfo);
	}

	
	public function detail()
	{
		$idCs = $this->input->get('id');
		$periode = $this->input->get('periode') ?? date('Y-m');

		if (empty($idCs)) {
			echo '<div class="p-4 text-center text-danger">Parameter ID tidak ditemukan.</div>';
			return;
		}

		// Get nilai data
		$nilaiAll = $this->NilaiModel->getByCustomerService($idCs);
		$nilai = array_filter($nilaiAll, fn($r) => ($r->periode ?? '') == $periode);

		if (empty($nilai)) {
			echo '<div class="p-4 text-center text-muted">Belum ada penilaian untuk periode ini.</div>';
			return;
		}

		// Calculate detail
		$rows = [];
		$totalCF = $totalSF = 0;
		$itemCF = 0;  // Jumlah item Core Factor
		$itemSF = 0;  // Jumlah item Secondary Factor

		foreach ($nilai as $row) {
			$nilaiAktual = (float) $row->nilai;
			$bobotSub = (float) ($row->bobot_sub ?? 0);
			
			$gap = $this->profilematching->hitungGap($row->id_sub_kriteria, $nilaiAktual);
			$jenis = strtolower(trim($row->jenis_kriteria ?? ''));

			// Accumulate - hanya nilai gap, tanpa dikalikan bobot
			if ($jenis === 'core_factor') {
				$totalCF += $gap['gap'];
				$itemCF++;
			} else {
				$totalSF += $gap['gap'];
				$itemSF++;
			}

			$rows[] = [
				'kode_kriteria' => $row->kode_kriteria ?? '-',
				'nama_kriteria' => $row->nama_kriteria ?? '-',
				'nama_sub' => $row->nama_sub_kriteria ?? '-',
				'nilai_asli' => $nilaiAktual,
				'nilai_gap' => $gap['gap'],
				'bobot_sub' => $bobotSub,
				'jenis' => $jenis,
			];
		}

		// Calculate scores - rata-rata nilai gap
		$ncf = $itemCF > 0 ? ($totalCF / $itemCF) : 0;
		$nsf = $itemSF > 0 ? ($totalSF / $itemSF) : 0;
		$skorAkhir = ($ncf * 0.9) + ($nsf * 0.1);

		// Get CS info
		$this->load->model('CustomerServiceModel');
		$csInfo = $this->CustomerServiceModel->getByIdWithDetails($idCs);

		$namaLeader = null;
		if (!empty($csInfo->id_tim)) {
			$timInfo = $this->TimModel->getByIdWithDetails($csInfo->id_tim);
			$namaLeader = $timInfo->nama_leader ?? null;
		}

		// Render view
		$this->load->view('admin/ranking/detail', [
			'rows' => $rows,
			'ncf' => round($ncf, 4),
			'nsf' => round($nsf, 4),
			'skor' => round($skorAkhir, 6),
			'total_cf' => $totalCF,
			'item_cf' => $itemCF,
			'total_sf' => $totalSF,
			'item_sf' => $itemSF,
			'periode' => $periode,
			'id_cs' => $idCs,
			'cs' => (object)[
				'nama_cs' => $csInfo->nama_cs ?? ($nilaiAll ? reset($nilaiAll)->nama_cs : '-'),
				'nik' => $csInfo->nik ?? ($nilaiAll ? reset($nilaiAll)->nik : '-'),
				'nama_tim' => $csInfo->nama_tim ?? null,
				'nama_produk' => $csInfo->nama_produk ?? null,
				'nama_leader' => $namaLeader,
			],
		]);
	}
}
