<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ExportLaporan
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        // Ensure composer autoload is available for PhpSpreadsheet
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $autoload = FCPATH . 'vendor/autoload.php';
            if (file_exists($autoload)) {
                require_once $autoload;
            }
        }
    }

    /**
     * Export laporan to XLSX dengan styling profesional
     */
    public function exportExcel(array $summary, array $top, array $bottom, array $kriteria, $filename = 'laporan-performa.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        
        // Set metadata
        $spreadsheet->getProperties()
            ->setCreator("SPK Retensi System")
            ->setTitle("Laporan Performa CS")
            ->setSubject("Profile Matching Analysis")
            ->setDescription("Laporan performa customer service menggunakan metode Profile Matching");

        // Sheet 1: Summary dengan styling menarik
        $this->createSummarySheet($spreadsheet, $summary);
        
        // Sheet 2: Top Performers
        $this->createTopPerformersSheet($spreadsheet, $top);
        
        // Sheet 3: Bottom Performers  
        $this->createBottomPerformersSheet($spreadsheet, $bottom);
        
        // Sheet 4: Per Kriteria
        $this->createKriteriaSheet($spreadsheet, $kriteria);

        // Set active sheet ke Summary
        $spreadsheet->setActiveSheetIndex(0);

        // Prepare writer
        $writer = new Xlsx($spreadsheet);

        // Send headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Sheet 1: Summary dengan styling
     */
    private function createSummarySheet($spreadsheet, $summary)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Summary');

        // Header Title
        $sheet->setCellValue('A1', 'LAPORAN PERFORMA CUSTOMER SERVICE');
        $sheet->mergeCells('A1:D1');
        $this->styleHeader($sheet, 'A1:D1');

        // Tanggal export
        $row = 2;
        $sheet->setCellValue('A' . $row, 'Tanggal Export:');
        $sheet->setCellValue('B' . $row, date('d/m/Y H:i:s'));
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        // Info metode
        $row = 3;
        $sheet->setCellValue('A' . $row, 'Metode:');
        $sheet->setCellValue('B' . $row, 'Profile Matching (NCF 60% + NSF 40%)');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        $row = 5;
        $sheet->setCellValue('A' . $row, 'RINGKASAN STATISTIK');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $this->styleSubHeader($sheet, 'A' . $row . ':D' . $row);

        // Summary data dengan warna
        $row++;
        $summaryItems = [
            'total_cs' => ['label' => 'Total Customer Service', 'color' => '4472C4'],
            'avg_skor' => ['label' => 'Rata-rata Skor', 'color' => '70AD47'],
            'excellent' => ['label' => 'Excellent (≥4.0)', 'color' => 'FFC000'],
            'poor' => ['label' => 'Perlu Perbaikan (<2.5)', 'color' => 'E74C3C']
        ];

        foreach ($summaryItems as $key => $config) {
            $value = $summary[$key] ?? 0;
            
            $sheet->setCellValue('A' . $row, $config['label']);
            $sheet->setCellValue('B' . $row, $value);
            
            // Style dengan warna
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF' . $config['color']]
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ]
                ]
            ]);
            
            $row++;
        }

        // Auto width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
    }

    /**
     * Sheet 2: Top Performers dengan styling
     */
    private function createTopPerformersSheet($spreadsheet, $top)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Top Performers');

        // Header
        $sheet->setCellValue('A1', 'TOP PERFORMERS');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1:F1', '70AD47');

        // Column headers
        $row = 3;
        $headers = ['Rank', 'NIK', 'Nama CS', 'Tim', 'Produk', 'Skor Akhir'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        $this->styleTableHeader($sheet, 'A' . $row . ':F' . $row);

        // Data rows
        $row++;
        foreach ($top as $i => $p) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $p->nik ?? '-');
            $sheet->setCellValue('C' . $row, $p->nama_cs ?? '-');
            $sheet->setCellValue('D' . $row, $p->nama_tim ?? '-');
            $sheet->setCellValue('E' . $row, $p->nama_produk ?? '-');
            $sheet->setCellValue('F' . $row, $p->avg_skor ?? $p->nilai_akhir ?? 0);

            // Format skor
            $sheet->getStyle('F' . $row)->getNumberFormat()
                ->setFormatCode('#,##0.00');

            // Alternate row color
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF0F0F0']
                    ]
                ]);
            }

            // Highlight top 3
            if ($i < 3) {
                $colors = ['FFD700', 'C0C0C0', 'CD7F32']; // Gold, Silver, Bronze
                $sheet->getStyle('A' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => $colors[$i]]
                    ],
                    'font' => ['bold' => true]
                ]);
            }

            $row++;
        }

        // Borders for all data
        $lastRow = $row - 1;
        $this->applyTableBorders($sheet, 'A3:F' . $lastRow);

        // Auto width
        $this->autoSizeColumns($sheet, ['A', 'B', 'C', 'D', 'E', 'F']);
    }

    /**
     * Sheet 3: Bottom Performers
     */
    private function createBottomPerformersSheet($spreadsheet, $bottom)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Need Improvement');

        // Header
        $sheet->setCellValue('A1', 'PERLU PERBAIKAN');
        $sheet->mergeCells('A1:F1');
        $this->styleHeader($sheet, 'A1:F1', 'E74C3C');

        // Column headers
        $row = 3;
        $headers = ['No', 'NIK', 'Nama CS', 'Tim', 'Produk', 'Skor Akhir'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        $this->styleTableHeader($sheet, 'A' . $row . ':F' . $row);

        // Data rows
        $row++;
        foreach ($bottom as $i => $p) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $p->nik ?? '-');
            $sheet->setCellValue('C' . $row, $p->nama_cs ?? '-');
            $sheet->setCellValue('D' . $row, $p->nama_tim ?? '-');
            $sheet->setCellValue('E' . $row, $p->nama_produk ?? '-');
            $sheet->setCellValue('F' . $row, $p->avg_skor ?? $p->nilai_akhir ?? 0);

            // Format skor
            $sheet->getStyle('F' . $row)->getNumberFormat()
                ->setFormatCode('#,##0.00');

            // Alternate row color
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFFF0F0'] // Light red tint
                    ]
                ]);
            }

            $row++;
        }

        // Borders
        $lastRow = $row - 1;
        $this->applyTableBorders($sheet, 'A3:F' . $lastRow);

        // Auto width
        $this->autoSizeColumns($sheet, ['A', 'B', 'C', 'D', 'E', 'F']);
    }

    /**
     * Sheet 4: Per Kriteria dengan chart-like visualization
     */
    private function createKriteriaSheet($spreadsheet, $kriteria)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Per Kriteria');

        // Header
        $sheet->setCellValue('A1', 'PERFORMA PER KRITERIA');
        $sheet->mergeCells('A1:C1');
        $this->styleHeader($sheet, 'A1:C1', '4472C4');

        // Column headers
        $row = 3;
        $sheet->setCellValue('A' . $row, 'Kriteria');
        $sheet->setCellValue('B' . $row, 'Rata-rata');
        $sheet->setCellValue('C' . $row, 'Visualisasi');
        $this->styleTableHeader($sheet, 'A' . $row . ':C' . $row);

        // Data
        $labels = $kriteria['labels'] ?? [];
        $data = $kriteria['data'] ?? [];

        $row++;
        for ($i = 0; $i < count($labels); $i++) {
            $nilai = $data[$i] ?? 0;
            
            $sheet->setCellValue('A' . $row, $labels[$i]);
            $sheet->setCellValue('B' . $row, $nilai);
            
            // Visual bar (using repeated characters)
            $barLength = (int)(($nilai / 100) * 20);
            $bar = str_repeat('█', $barLength);
            $sheet->setCellValue('C' . $row, $bar . ' ' . number_format($nilai, 1) . '%');

            // Color based on value
            $color = $this->getScoreColor($nilai);
            $sheet->getStyle('B' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $color]
                ],
                'font' => ['bold' => true]
            ]);

            // Format number
            $sheet->getStyle('B' . $row)->getNumberFormat()
                ->setFormatCode('#,##0.00');

            $row++;
        }

        // Borders
        $lastRow = $row - 1;
        $this->applyTableBorders($sheet, 'A3:C' . $lastRow);

        // Auto width
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(35);
    }

    /**
     * Style untuk header utama
     */
    private function styleHeader($sheet, $range, $color = '4472C4')
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF' . $color]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension('1')->setRowHeight(25);
    }

    /**
     * Style untuk sub header
     */
    private function styleSubHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF5B9BD5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
    }

    /**
     * Style untuk table header
     */
    private function styleTableHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF5B9BD5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000']
                ]
            ]
        ]);
    }

    /**
     * Apply borders untuk tabel
     */
    private function applyTableBorders($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCCCCCC']
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => 'FF000000']
                ]
            ]
        ]);
    }

    /**
     * Auto size columns
     */
    private function autoSizeColumns($sheet, $columns)
    {
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Get color based on score
     */
    private function getScoreColor($score)
    {
        if ($score >= 85) return 'FF70AD47'; // Green
        if ($score >= 70) return 'FF4472C4'; // Blue
        if ($score >= 50) return 'FFFFC000'; // Yellow
        return 'FFE74C3C'; // Red
    }
}
