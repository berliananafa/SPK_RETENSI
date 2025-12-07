<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NilaiController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('NilaiModel');
        $this->load->model('CustomerServiceModel');
        $this->load->model('KriteriaModel');
        $this->load->model('SubKriteriaModel');
        $this->load->model('TimModel');
        $this->load->library('form_validation');
    }

    public function index()
    {
        set_page_title('Monitoring Penilaian');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Monitoring Penilaian']
        ]);
        
        enable_datatables();
        
        // Get filter data
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();
        $data['tim'] = $this->TimModel->getAllWithDetails();
        
        // Get penilaian data with details
        $data['penilaian'] = $this->NilaiModel->getAllWithDetails();
        
        // Calculate statistics
        $data['total_penilaian'] = count($data['penilaian']);
        $data['total_cs'] = $this->db->select('DISTINCT id_cs')->from('nilai')->count_all_results();
        $data['total_kriteria'] = $this->db->select('DISTINCT id_sub_kriteria')->from('nilai')->count_all_results();
        
        // Calculate average
        $avg = $this->db->select_avg('nilai')->get('nilai')->row();
        $data['rata_rata'] = $avg && $avg->nilai ? round($avg->nilai, 2) : 0;
        
        render_layout('admin/nilai/index', $data);
    }

    public function input()
    {
        set_page_title('Input Penilaian');
        set_breadcrumb([
            ['title' => 'Dashboard', 'url' => base_url('admin/dashboard')],
            ['title' => 'Input Penilaian']
        ]);
        
        $data['kriteria'] = $this->KriteriaModel->getAllOrdered();
        
        render_layout('admin/nilai/input', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('periode', 'Periode', 'required');
        $this->form_validation->set_rules('file_excel', 'File Excel', 'callback_check_excel_upload');

        if ($this->form_validation->run() === FALSE) {
            $this->input();
            return;
        }

        try {
            $periode = $this->input->post('periode');
            $replace_existing = $this->input->post('replace_existing') == '1';
            
            // Handle file upload
            $config['upload_path'] = './uploads/temp/';
            $config['allowed_types'] = 'xlsx|xls';
            $config['max_size'] = 5120; // 5MB
            $config['file_name'] = 'nilai_' . time();
            
            // Create directory if not exists
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('file_excel')) {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('admin/nilai/input');
                return;
            }
            
            $uploadData = $this->upload->data();
            $filePath = $uploadData['full_path'];
            
            // Process Excel file
            $result = $this->processExcelFile($filePath, $periode, $replace_existing);
            
            // Delete uploaded file
            @unlink($filePath);
            
            if ($result['success']) {
                $this->session->set_flashdata('success', $result['message']);
            } else {
                $this->session->set_flashdata('error', $result['message']);
            }
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
        redirect('admin/nilai');
    }

    private function processExcelFile($filePath, $periode, $replaceExisting = false)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            // Mapping nama kolom ke sub kriteria
            $subKriteriaMapping = [
                'kpi' => 'KPI',
                'rasio' => 'Rasio Ketercapaian Target',
                'absensi' => 'Absensi',
                'keterlambatan' => 'Keterlambatan'
            ];
            
            $this->db->trans_start();
            
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index 0 dan skip header
                
                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }
                
                // Validate row data
                // Format: ID CS | Nama CS | KPI | Rasio | Absensi | Keterlambatan | Produk | Leader | Tim | SPV
                $idCs = trim($row[0] ?? '');
                $namaCs = trim($row[1] ?? '');
                $nilaiKpi = floatval($row[2] ?? 0);
                $nilaiRasio = floatval($row[3] ?? 0);
                $nilaiAbsensi = floatval($row[4] ?? 0);
                $nilaiKeterlambatan = floatval($row[5] ?? 0);
                
                if (empty($idCs)) {
                    $errors[] = "Baris {$rowNumber}: ID CS tidak boleh kosong";
                    $errorCount++;
                    continue;
                }
                
                // Get CS by ID
                $cs = $this->CustomerServiceModel->find($idCs);
                if (!$cs) {
                    $errors[] = "Baris {$rowNumber}: CS dengan ID {$idCs} tidak ditemukan";
                    $errorCount++;
                    continue;
                }
                
                // Validasi nama CS (opsional, untuk memastikan data benar)
                if (!empty($namaCs) && strcasecmp(trim($cs->nama_cs), $namaCs) !== 0) {
                    $errors[] = "Baris {$rowNumber}: Nama CS tidak sesuai (sistem: {$cs->nama_cs}, excel: {$namaCs})";
                }
                
                // Array untuk menyimpan nilai yang akan diinsert
                $nilaiData = [
                    'KPI' => $nilaiKpi,
                    'Rasio Ketercapaian Target' => $nilaiRasio,
                    'Absensi' => $nilaiAbsensi,
                    'Keterlambatan' => $nilaiKeterlambatan
                ];
                
                // Process each sub kriteria
                foreach ($nilaiData as $namaSubKriteria => $nilai) {
                    // Skip if nilai is 0 (assuming 0 means no data)
                    if ($nilai <= 0) {
                        continue;
                    }
                    
                    // Find sub kriteria by name
                    $subKriteria = $this->db->select('sk.*, k.id_kriteria, k.nama_kriteria')
                                            ->from('sub_kriteria sk')
                                            ->join('kriteria k', 'sk.id_kriteria = k.id_kriteria')
                                            ->where('sk.nama_sub_kriteria', $namaSubKriteria)
                                            ->get()
                                            ->row();
                    
                    if (!$subKriteria) {
                        $errors[] = "Baris {$rowNumber}: Sub kriteria '{$namaSubKriteria}' tidak ditemukan di sistem";
                        $errorCount++;
                        continue;
                    }
                    
                    // Check if nilai already exists
                    if ($replaceExisting) {
                        $this->db->where('id_cs', $cs->id_cs)
                                 ->where('id_sub_kriteria', $subKriteria->id_sub_kriteria)
                                 ->delete('nilai');
                    } else {
                        // Check for duplicate
                        $existing = $this->db->where('id_cs', $cs->id_cs)
                                            ->where('id_sub_kriteria', $subKriteria->id_sub_kriteria)
                                            ->get('nilai')
                                            ->row();
                        if ($existing) {
                            continue; // Skip if already exists
                        }
                    }
                    
                    // Insert nilai
                    $dataNilai = [
                        'id_cs' => $cs->id_cs,
                        'id_sub_kriteria' => $subKriteria->id_sub_kriteria,
                        'nilai' => $nilai
                    ];
                    
                    if ($this->NilaiModel->create($dataNilai)) {
                        $successCount++;
                    } else {
                        $errors[] = "Baris {$rowNumber}: Gagal menyimpan {$namaSubKriteria}";
                        $errorCount++;
                    }
                }
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                return [
                    'success' => false,
                    'message' => 'Transaksi database gagal'
                ];
            }
            
            $message = "Berhasil import {$successCount} data penilaian";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} data gagal/dilewati. " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " dan " . (count($errors) - 5) . " error lainnya";
                }
            }
            
            return [
                'success' => $successCount > 0,
                'message' => $message
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error membaca file Excel: ' . $e->getMessage()
            ];
        }
    }

    private function calculateConversion($gap)
    {
        // Profile Matching conversion table
        $conversionTable = [
            0 => 5,    // Tidak ada selisih (kompetensi sesuai)
            1 => 4.5,  // Kompetensi 1 tingkat di atas
            -1 => 4,   // Kompetensi 1 tingkat di bawah
            2 => 3.5,  // Kompetensi 2 tingkat di atas
            -2 => 3,   // Kompetensi 2 tingkat di bawah
            3 => 2.5,  // Kompetensi 3 tingkat di atas
            -3 => 2,   // Kompetensi 3 tingkat di bawah
            4 => 1.5,  // Kompetensi 4 tingkat di atas
            -4 => 1    // Kompetensi 4 tingkat di bawah
        ];
        
        return $conversionTable[$gap] ?? 1; // Default 1 jika gap > 4 atau < -4
    }

    public function check_excel_upload($str)
    {
        if (empty($_FILES['file_excel']['name'])) {
            $this->form_validation->set_message('check_excel_upload', 'File Excel wajib diupload');
            return FALSE;
        }
        
        $allowed = ['xlsx', 'xls'];
        $filename = $_FILES['file_excel']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $this->form_validation->set_message('check_excel_upload', 'File harus berformat Excel (.xlsx atau .xls)');
            return FALSE;
        }
        
        if ($_FILES['file_excel']['size'] > 5242880) { // 5MB
            $this->form_validation->set_message('check_excel_upload', 'Ukuran file maksimal 5MB');
            return FALSE;
        }
        
        return TRUE;
    }

    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header dengan format baru
        $sheet->setCellValue('A1', 'ID CS');
        $sheet->setCellValue('B1', 'Nama CS');
        $sheet->setCellValue('C1', 'KPI');
        $sheet->setCellValue('D1', 'Rasio Ketercapaian Target');
        $sheet->setCellValue('E1', 'Absensi');
        $sheet->setCellValue('F1', 'Keterlambatan');
        $sheet->setCellValue('G1', 'Produk');
        $sheet->setCellValue('H1', 'Leader');
        $sheet->setCellValue('I1', 'Tim');
        $sheet->setCellValue('J1', 'SPV');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        // Get sample data dari database untuk referensi
        $sampleCs = $this->db->select('cs.id_cs, cs.nama_cs, p.nama_produk, pen_leader.nama as nama_leader, 
                                      t.nama_tim, pen_spv.nama as nama_spv')
                             ->from('customer_service cs')
                             ->join('produk p', 'cs.id_produk = p.id_produk', 'left')
                             ->join('tim t', 'cs.id_tim = t.id_tim', 'left')
                             ->join('pengguna pen_leader', 't.id_leader = pen_leader.id_user', 'left')
                             ->join('pengguna pen_spv', 't.id_supervisor = pen_spv.id_user', 'left')
                             ->limit(3)
                             ->get()
                             ->result();
        
        // Add sample data
        $row = 2;
        foreach ($sampleCs as $cs) {
            $sheet->setCellValue('A' . $row, $cs->id_cs);
            $sheet->setCellValue('B' . $row, $cs->nama_cs);
            $sheet->setCellValue('C' . $row, 85); // Sample KPI
            $sheet->setCellValue('D' . $row, 90); // Sample Rasio
            $sheet->setCellValue('E' . $row, 95); // Sample Absensi
            $sheet->setCellValue('F' . $row, 80); // Sample Keterlambatan
            $sheet->setCellValue('G' . $row, $cs->nama_produk ?? '');
            $sheet->setCellValue('H' . $row, $cs->nama_leader ?? '');
            $sheet->setCellValue('I' . $row, $cs->nama_tim ?? '');
            $sheet->setCellValue('J' . $row, $cs->nama_spv ?? '');
            $row++;
        }
        
        // Auto size columns
        foreach(range('A','J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add notes sheet
        $spreadsheet->createSheet();
        $notesSheet = $spreadsheet->getSheet(1);
        $notesSheet->setTitle('Panduan');
        $notesSheet->setCellValue('A1', 'PANDUAN PENGISIAN TEMPLATE PENILAIAN CS');
        $notesSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $notesSheet->setCellValue('A3', 'KOLOM YANG HARUS DIISI:');
        $notesSheet->getStyle('A3')->getFont()->setBold(true);
        $notesSheet->setCellValue('A4', '1. ID CS (WAJIB)');
        $notesSheet->setCellValue('A5', '   - ID Customer Service dari sistem (angka)');
        $notesSheet->setCellValue('A6', '   - Harus sudah terdaftar di database');
        $notesSheet->setCellValue('A7', '   - Contoh: 1, 2, 3, dst');
        
        $notesSheet->setCellValue('A9', '2. Nama CS (OPSIONAL)');
        $notesSheet->setCellValue('A10', '   - Nama lengkap CS untuk verifikasi');
        $notesSheet->setCellValue('A11', '   - Akan divalidasi dengan data di sistem');
        
        $notesSheet->setCellValue('A13', '3. KPI (WAJIB)');
        $notesSheet->setCellValue('A14', '   - Nilai Key Performance Indicator');
        $notesSheet->setCellValue('A15', '   - Sub kriteria dari Performa');
        $notesSheet->setCellValue('A16', '   - Format: angka (contoh: 85, 90.5)');
        
        $notesSheet->setCellValue('A18', '4. Rasio Ketercapaian Target (WAJIB)');
        $notesSheet->setCellValue('A19', '   - Persentase pencapaian target');
        $notesSheet->setCellValue('A20', '   - Sub kriteria dari Performa');
        $notesSheet->setCellValue('A21', '   - Format: angka (contoh: 95, 87.5)');
        
        $notesSheet->setCellValue('A23', '5. Absensi (WAJIB)');
        $notesSheet->setCellValue('A24', '   - Nilai kehadiran');
        $notesSheet->setCellValue('A25', '   - Sub kriteria dari Kedisiplinan');
        $notesSheet->setCellValue('A26', '   - Format: angka (contoh: 100, 95)');
        
        $notesSheet->setCellValue('A28', '6. Keterlambatan (WAJIB)');
        $notesSheet->setCellValue('A29', '   - Nilai kedisiplinan waktu');
        $notesSheet->setCellValue('A30', '   - Sub kriteria dari Kedisiplinan');
        $notesSheet->setCellValue('A31', '   - Format: angka (contoh: 90, 85.5)');
        
        $notesSheet->setCellValue('A33', '7-10. Produk, Leader, Tim, SPV (OPSIONAL)');
        $notesSheet->setCellValue('A34', '   - Informasi tambahan untuk referensi');
        $notesSheet->setCellValue('A35', '   - Tidak diproses saat import');
        $notesSheet->setCellValue('A36', '   - Berguna untuk verifikasi manual');
        
        $notesSheet->setCellValue('A38', 'CATATAN PENTING:');
        $notesSheet->getStyle('A38')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000'));
        $notesSheet->setCellValue('A39', '✓ ID CS harus valid dan terdaftar di sistem');
        $notesSheet->setCellValue('A40', '✓ Sub kriteria harus sudah dibuat dengan nama yang sama persis:');
        $notesSheet->setCellValue('A41', '  - KPI');
        $notesSheet->setCellValue('A42', '  - Rasio Ketercapaian Target');
        $notesSheet->setCellValue('A43', '  - Absensi');
        $notesSheet->setCellValue('A44', '  - Keterlambatan');
        $notesSheet->setCellValue('A45', '✓ Nilai harus berupa angka, bisa desimal');
        $notesSheet->setCellValue('A46', '✓ Jika nilai = 0 atau kosong, data akan dilewati');
        $notesSheet->setCellValue('A47', '✓ Periode penilaian diisi di form upload');
        $notesSheet->setCellValue('A48', '✓ Gunakan opsi "Replace Existing" untuk update data lama');
        
        $notesSheet->getColumnDimension('A')->setWidth(90);
        
        // Set filename and headers
        $filename = 'template_penilaian_cs_' . date('Ymd') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function delete($id)
    {
        $nilai = $this->NilaiModel->find($id);
        
        if (!$nilai) {
            $this->session->set_flashdata('error', 'Data penilaian tidak ditemukan!');
            redirect('admin/nilai');
            return;
        }
        
        if ($this->NilaiModel->deleteById($id)) {
            $this->session->set_flashdata('success', 'Data penilaian berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data penilaian!');
        }
        
        redirect('admin/nilai');
    }
}
