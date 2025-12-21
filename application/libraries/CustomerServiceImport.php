<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\{Fill, Alignment, Border};

class CustomerServiceImport
{
    protected $CI;
    protected $errors = [];
    protected $success = 0;

    const HEADERS = [
        'NIK',
        'Nama Customer Service',
        'Nama Tim',
        'Nama Produk',
        'Nama Kanal',
    ];

    public function __construct()
    {
        require_once APPPATH . '../vendor/autoload.php';
        $this->CI =& get_instance();

        $this->CI->load->model([
            'CustomerServiceModel' => 'CS',
            'TimModel' => 'Tim',
            'ProdukModel' => 'Produk',
            'KanalModel' => 'Kanal'
        ]);
    }

    /* ================= TEMPLATE ================= */
    public function generateTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import');

        // Header
        foreach (self::HEADERS as $i => $title) {
            $sheet->setCellValueByColumnAndRow($i + 1, 1, $title);
            $sheet->getColumnDimensionByColumn($i + 1)->setAutoSize(true);
        }

        $this->styleHeader($sheet, 1);

        // Contoh data
        $sheet->fromArray(
            ['1234567890', 'John Doe', 'Tim A', 'Produk X', 'WhatsApp'],
            null,
            'A2'
        );

        // Get master data
        $teams = $this->CI->Tim->all();
        $products = $this->CI->Produk->all();
        $channels = $this->CI->Kanal->all();

        // Create master data sheet
        $masterSheet = $spreadsheet->createSheet(1);
        $masterSheet->setTitle('Data Master');
        
        // Tim column
        $masterSheet->setCellValue('A1', 'Daftar Tim');
        $this->styleHeader($masterSheet, 1, 'FF4CAF50');
        $row = 2;
        foreach ($teams as $team) {
            $masterSheet->setCellValue('A' . $row, $team->nama_tim);
            $row++;
        }
        
        // Produk column
        $masterSheet->setCellValue('B1', 'Daftar Produk');
        $row = 2;
        foreach ($products as $product) {
            $masterSheet->setCellValue('B' . $row, $product->nama_produk);
            $row++;
        }
        
        // Kanal column
        $masterSheet->setCellValue('C1', 'Daftar Kanal');
        $row = 2;
        foreach ($channels as $channel) {
            $masterSheet->setCellValue('C' . $row, $channel->nama_kanal);
            $row++;
        }

        // Auto size columns in master sheet
        $masterSheet->getColumnDimension('A')->setAutoSize(true);
        $masterSheet->getColumnDimension('B')->setAutoSize(true);
        $masterSheet->getColumnDimension('C')->setAutoSize(true);

        // Add data validation to main sheet (dropdown)
        if (!empty($teams)) {
            $teamList = implode(',', array_map(function($t) { return $t->nama_tim; }, $teams));
            $validation = $sheet->getCell('C2')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input Error');
            $validation->setError('Pilih tim dari daftar yang tersedia');
            $validation->setPromptTitle('Pilih Tim');
            $validation->setPrompt('Pilih tim dari dropdown atau lihat sheet Data Master');
            $validation->setFormula1('"' . $teamList . '"');
        }

        if (!empty($products)) {
            $productList = implode(',', array_map(function($p) { return $p->nama_produk; }, $products));
            $validation = $sheet->getCell('D2')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input Error');
            $validation->setError('Pilih produk dari daftar yang tersedia');
            $validation->setPromptTitle('Pilih Produk');
            $validation->setPrompt('Pilih produk dari dropdown atau lihat sheet Data Master');
            $validation->setFormula1('"' . $productList . '"');
        }

        if (!empty($channels)) {
            $channelList = implode(',', array_map(function($c) { return $c->nama_kanal; }, $channels));
            $validation = $sheet->getCell('E2')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input Error');
            $validation->setError('Pilih kanal dari daftar yang tersedia');
            $validation->setPromptTitle('Pilih Kanal');
            $validation->setPrompt('Pilih kanal dari dropdown atau lihat sheet Data Master');
            $validation->setFormula1('"' . $channelList . '"');
        }

        $sheet->freezePane('A2');
        $spreadsheet->setActiveSheetIndex(0);
        
        return $spreadsheet;
    }

    /* ================= EXPORT ================= */

    public function generateExport()
    {
        $data = $this->CI->CS->getAllWithDetails();
        $sheet = (new Spreadsheet())->getActiveSheet();

        $headers = array_merge(['No'], self::HEADERS, ['Tanggal Dibuat']);
        $sheet->fromArray($headers, null, 'A1');
        $this->styleHeader($sheet, 1, 'FF4CAF50');

        $row = 2;
        foreach ($data as $i => $cs) {
            $sheet->fromArray([
                $i + 1,
                $cs->nik,
                $cs->nama_cs,
                $cs->nama_tim,
                $cs->nama_produk,
                $cs->nama_kanal,
                $cs->created_at ?? '-'
            ], null, 'A' . $row);

            $row++;
        }

        $this->addBorder($sheet, "A1:G" . ($row - 1));
        $sheet->freezePane('A2');

        return $sheet->getParent();
    }

    /* ================= IMPORT ================= */

    public function processImport($path)
    {
        $this->reset();

        $rows = IOFactory::load($path)->getActiveSheet()->toArray();
        array_shift($rows); // hapus header

        $this->CI->db->trans_start();

        foreach ($rows as $i => $row) {
            $this->importRow($row, $i + 2);
        }

        $this->CI->db->trans_complete();

        return $this->result();
    }

    protected function importRow($row, $line)
    {
        if (!array_filter($row)) return;

        [$nik, $nama, $tim, $produk, $kanal] = array_map('trim', array_pad($row, 5, ''));

        if (!$nik || !$nama || !$tim || !$produk || !$kanal) {
            return $this->error($line, 'Semua kolom wajib diisi');
        }

        if ($this->CI->CS->nikExists($nik)) {
            return $this->error($line, "NIK $nik sudah terdaftar");
        }

        $tim = $this->CI->Tim->getByName($tim);
        $produk = $this->CI->Produk->getByName($produk);
        $kanal = $this->CI->Kanal->getByName($kanal);

        if (!$tim || !$produk || !$kanal) {
            return $this->error($line, 'Relasi Tim / Produk / Kanal tidak valid');
        }

        $this->CI->CS->create([
            'nik' => $nik,
            'nama_cs' => $nama,
            'id_tim' => $tim->id_tim,
            'id_produk' => $produk->id_produk,
            'id_kanal' => $kanal->id_kanal,
        ]);

        $this->success++;
    }

    /* ================= UTIL ================= */

    protected function styleHeader($sheet, $row, $color = 'FF2196F3')
    {
        $lastCol = chr(64 + count($sheet->getRowIterator()->current()->getCellIterator()));
        $sheet->getStyle("A$row:$lastCol$row")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $color]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
    }

    protected function addBorder($sheet, $range)
    {
        $sheet->getStyle($range)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    protected function error($line, $msg)
    {
        $this->errors[] = "Baris $line: $msg";
    }

    protected function reset()
    {
        $this->errors = [];
        $this->success = 0;
    }

    protected function result()
    {
        return [
            'success' => $this->success,
            'errors' => $this->errors,
            'message' => $this->success
                ? "Berhasil import $this->success data"
                : "Tidak ada data berhasil diimport"
        ];
    }

    /* ================= DOWNLOAD ================= */

    public function download($spreadsheet, $filename)
    {
        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
