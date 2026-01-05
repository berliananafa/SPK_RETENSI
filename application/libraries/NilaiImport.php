<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class NilaiImport
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('NilaiModel');
        $this->CI->load->model('CustomerServiceModel');
		$this->CI->load->model('KriteriaModel');
		$this->CI->load->model('SubKriteriaModel', 'SubKriteria');
        $this->CI->load->database();
    }

    public function importFromExcel($filePath, $periode, $replaceExisting = false)
    {
        try {
            $rows = $this->_readExcelFile($filePath);
            
            if (empty($rows)) {
                return [
                    'success' => false,
                    'message' => 'File Excel kosong atau tidak valid'
                ];
            }
            
            return $this->_processRows($rows, $periode, $replaceExisting);
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error membaca file Excel: ' . $e->getMessage()
            ];
        }
    }

    private function _readExcelFile($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        array_shift($rows); // Skip header
        
        return $rows;
    }

    private function _processRows($rows, $periode, $replaceExisting)
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        $this->CI->db->trans_begin();
        
        try {
            $subKriteriaList = $this->_getSubKriteriaMapping();
            
            if (empty($subKriteriaList)) {
                throw new Exception('Tidak ada sub kriteria yang tersedia di sistem');
            }
            
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                
                if ($this->_isEmptyRow($row)) {
                    continue;
                }
                
                $result = $this->_processRow($row, $rowNumber, $replaceExisting, $subKriteriaList, $periode);
                
                if ($result['success']) {
                    $successCount += $result['count'];
                } else {
                    $errorCount++;
                    $errors[] = $result['error'];
                }
            }
            
            if ($this->CI->db->trans_status() === FALSE) {
                throw new Exception('Database transaction failed');
            }
            
            $this->CI->db->trans_commit();
            
            return $this->_buildResult($successCount, $errorCount, $errors);
            
        } catch (Exception $e) {
            $this->CI->db->trans_rollback();
            
            return [
                'success' => false,
                'message' => 'Import gagal: ' . $e->getMessage()
            ];
        }
    }

    private function _getSubKriteriaMapping()
    {
        // Hanya ambil sub kriteria dari kriteria yang sudah approved
        $subKriteria = $this->CI->SubKriteria->getApprovedSubKriteria();
        
        $mapping = [];
        $columnIndex = 2; // C = 2 (A=0, B=1, C=2)
        
        foreach ($subKriteria as $sk) {
            $mapping[$sk->nama_sub_kriteria] = [
                'column_index' => $columnIndex,
                'id_sub_kriteria' => $sk->id_sub_kriteria,
                'kriteria' => $sk->nama_kriteria
            ];
            $columnIndex++;
        }
        
        return $mapping;
    }

    private function _processRow($row, $rowNumber, $replaceExisting, $subKriteriaList, $periode)
    {
        $nikCs = trim($row[0] ?? '');
        $namaCs = trim($row[1] ?? '');
        
        if (empty($nikCs)) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: NIK CS tidak boleh kosong"
            ];
        }
        
        // Get CS by NIK (gunakan CustomerServiceModel)
        $cs = $this->CI->CustomerServiceModel->findByNik($nikCs);
        
        if (!$cs) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: CS dengan NIK '{$nikCs}' tidak ditemukan"
            ];
        }
        
        // Validasi nama CS (opsional)
        if (!empty($namaCs) && strcasecmp(trim($cs->nama_cs), $namaCs) !== 0) {
            return [
                'success' => false,
                'error' => "Baris {$rowNumber}: Nama CS tidak sesuai (Sistem: {$cs->nama_cs}, Excel: {$namaCs})"
            ];
        }
        
        // Extract nilai dari Excel
        $nilaiDataArray = [];
        foreach ($subKriteriaList as $namaSubKriteria => $info) {
            $columnIndex = $info['column_index'];
            $cellValue = $row[$columnIndex] ?? null;
            
            // Skip jika cell kosong/null, tapi TERIMA nilai 0
            if ($cellValue === null || $cellValue === '') {
                continue;
            }
            
            $nilai = floatval($cellValue);
            
            // Terima nilai >= 0 (termasuk 0 untuk absensi/keterlambatan)
            if ($nilai >= 0) {
                $nilaiDataArray[] = [
                    'id_cs' => $cs->id_cs,
                    'id_sub_kriteria' => $info['id_sub_kriteria'],
                    'nilai' => $nilai,
                    'periode' => $periode
                ];
            }
        }
        
        // Save menggunakan NilaiModel
        $savedCount = $this->_saveNilaiData($cs->id_cs, $nilaiDataArray, $replaceExisting, $periode);
        
        return [
            'success' => true,
            'count' => $savedCount
        ];
    }

    private function _saveNilaiData($idCs, $nilaiDataArray, $replaceExisting, $periode)
    {
        if (empty($nilaiDataArray)) {
            return 0;
        }
        
        // Jika replace, hapus nilai existing untuk CS dan periode ini
        if ($replaceExisting) {
            $this->CI->db->where('id_cs', $idCs)
                         ->where('periode', $periode)
                         ->delete('nilai');
        } else {
            // Filter data yang belum ada
            $filteredData = [];
            foreach ($nilaiDataArray as $data) {
                if (!$this->_isNilaiExists($idCs, $data['id_sub_kriteria'], $periode)) {
                    $filteredData[] = $data;
                }
            }
            $nilaiDataArray = $filteredData;
        }
        
        if (empty($nilaiDataArray)) {
            return 0;
        }
        
        // Gunakan bulkCreate dari NilaiModel untuk insert batch
        $result = $this->CI->NilaiModel->bulkCreate($nilaiDataArray);
        
        return $result ? count($nilaiDataArray) : 0;
    }

    private function _isNilaiExists($idCs, $idSubKriteria, $periode)
    {
        // Cek apakah nilai sudah ada untuk CS, SubKriteria, dan Periode tertentu
        $existing = $this->CI->db->where('id_cs', $idCs)
                                 ->where('id_sub_kriteria', $idSubKriteria)
                                 ->where('periode', $periode)
                                 ->count_all_results('nilai');
        return $existing > 0;
    }

    private function _isEmptyRow($row)
    {
        return empty($row[0]) && empty($row[1]);
    }

    private function _buildResult($successCount, $errorCount, $errors)
    {
        $message = "Berhasil import {$successCount} data nilai";
        
        if ($errorCount > 0) {
            $message .= ", {$errorCount} data gagal/dilewati";
            if (!empty($errors)) {
                $message .= ": " . implode('; ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya";
                }
            }
        }
        
        return [
            'success' => $successCount > 0,
            'message' => $message
        ];
    }
}
