<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class NilaiTemplate
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    public function generate()
    {
        $spreadsheet = new Spreadsheet();
        
        $subKriteriaList = $this->_getSubKriteriaList();
        
        $this->_createMainSheet($spreadsheet->getActiveSheet(), $subKriteriaList);
        
        $spreadsheet->createSheet();
        $this->_outputFile($spreadsheet);
    }

    private function _getSubKriteriaList()
    {
        // Hanya ambil sub kriteria dari kriteria yang sudah approved
        return $this->CI->db->select('sk.nama_sub_kriteria, sk.bobot_sub, sk.target, k.nama_kriteria')
                            ->from('sub_kriteria sk')
                            ->join('kriteria k', 'sk.id_kriteria = k.id_kriteria')
                            ->where('k.status_approval', 'approved')
                            ->order_by('k.id_kriteria, sk.id_sub_kriteria', 'ASC')
                            ->get()
                            ->result();
    }

    private function _createMainSheet($sheet, $subKriteriaList)
    {
        // Headers
        $headers = ['NIK CS', 'Nama CS'];
        
        foreach ($subKriteriaList as $sk) {
            $headers[] = $sk->nama_sub_kriteria;
        }
        
        $headers[] = 'Produk';
        $headers[] = 'Leader';
        $headers[] = 'Tim';
        $headers[] = 'SPV';
        
        // Set headers
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }
        
        // Style header
        $lastCol = count($headers);
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle("A1:" . $this->_getColumnLetter($lastCol - 1) . "1")->applyFromArray($headerStyle);
        
        // Add sample data
        $this->_addSampleData($sheet, $subKriteriaList);
        
        // Auto size columns
        for ($i = 1; $i <= $lastCol; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    private function _addSampleData($sheet, $subKriteriaList)
    {
        // Query dengan raw SQL untuk menghindari masalah alias
        $query = "
            SELECT 
                cs.nik,
                cs.nama_cs,
                p.nama_produk,
                t.nama_tim,
                leader.nama_pengguna as nama_leader,
                spv.nama_pengguna as nama_spv
            FROM customer_service cs
            LEFT JOIN produk p ON cs.id_produk = p.id_produk
            LEFT JOIN tim t ON cs.id_tim = t.id_tim
            LEFT JOIN pengguna leader ON t.id_leader = leader.id_user AND leader.level = 'leader'
            LEFT JOIN pengguna spv ON t.id_supervisor = spv.id_user AND spv.level = 'supervisor'
            LIMIT 3
        ";
        
        $sampleCs = $this->CI->db->query($query)->result();
        
        $row = 2;
        foreach ($sampleCs as $cs) {
            $col = 1;
            
            // NIK CS
            $sheet->setCellValueByColumnAndRow($col++, $row, $cs->nik ?? '');
            
            // Nama CS
            $sheet->setCellValueByColumnAndRow($col++, $row, $cs->nama_cs ?? '');
            
            // Sub kriteria values (sample)
            foreach ($subKriteriaList as $sk) {
                $sheet->setCellValueByColumnAndRow($col++, $row, rand(75, 100));
            }
            
            // Additional info
            $sheet->setCellValueByColumnAndRow($col++, $row, $cs->nama_produk ?? '');
            $sheet->setCellValueByColumnAndRow($col++, $row, $cs->nama_leader ?? '');
            $sheet->setCellValueByColumnAndRow($col++, $row, $cs->nama_tim ?? '');
            $sheet->setCellValueByColumnAndRow($col++, $row, $cs->nama_spv ?? '');
            
            $row++;
        }
    }

    private function _getColumnLetter($index)
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr($index % 26 + 65) . $letter;
            $index = floor($index / 26) - 1;
        }
        return $letter;
    }

    private function _outputFile($spreadsheet)
    {
        $filename = 'template_penilaian_cs_' . date('Ymd') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
