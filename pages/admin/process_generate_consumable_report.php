<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../function_connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$spreadsheet->getProperties()->setCreator("PRC-IMS")
    ->setLastModifiedBy("PRC-IMS")
    ->setTitle("Supplies and Materials Issued Report")
    ->setSubject("Supplies and Materials Issued Report")
    ->setDescription("Report of supplies and materials issued.")
    ->setKeywords("office supplies materials report")
    ->setCategory("Report");

// Add logo
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath(__DIR__ . '/../../img/logo.png');
$drawing->setHeight(60);
$drawing->setCoordinates('A1');
$drawing->setWorksheet($spreadsheet->getActiveSheet());

// Add the main title
$sheet->mergeCells('B1:I1');
$sheet->setCellValue('B1', 'Professional Regulation Commission');
$sheet->getStyle('B1')->getFont()->setBold(true)->setSize(12);
$sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('B2:I2');
$sheet->setCellValue('B2', 'REPORT OF SUPPLIES AND MATERIALS ISSUED');
$sheet->getStyle('B2')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Add entity name and other details
$sheet->setCellValue('A4', 'Entity Name:');
$sheet->mergeCells('B4:D4');
$sheet->setCellValue('B4', 'Cordillera Administrative Region (Baguio)');
$sheet->setCellValue('A5', 'Fund Cluster:');
$sheet->setCellValue('G4', 'Serial No.:');
$sheet->setCellValue('G5', 'Date:');

// Add section headers
$sheet->mergeCells('A7:F7');
$sheet->setCellValue('A7', 'To be filled up by the Supply and/or Property Division/Unit');
$sheet->getStyle('A7:F7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('G7:I7');
$sheet->setCellValue('G7', 'To be filled up by the Accounting Division/Unit');
$sheet->getStyle('G7:I7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Apply bold outline to both sections
$styleArray = [
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
        ],
    ],
];
$sheet->getStyle('A7:F7')->applyFromArray($styleArray);
$sheet->getStyle('G7:I7')->applyFromArray($styleArray);

// Set column headers
$headers = [
    'RIS No.', 'Responsibility Center Code', 'Stock No.', 'Item', 'Unit', 'Quantity Issued', 'Unit Cost', 'Amount'
];
$sheet->fromArray($headers, NULL, 'A8');
$sheet->getStyle('A8:H8')->getFont()->setBold(true);
$sheet->getStyle('A8:H8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->mergeCells('H8:I8');

// Fetch data from the database
$query = "SELECT ris_no, end_user_issuance, stock_number, item_description, unit, issuance FROM tbl_inv_consumables ORDER BY acceptance_date DESC";
$result = $conn->query($query);

$row = 9;
if ($result->num_rows > 0) {
    while($data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $data['ris_no']);
        $sheet->setCellValue('B' . $row, $data['end_user_issuance']);
        $sheet->setCellValue('C' . $row, $data['stock_number']);
        $sheet->setCellValue('D' . $row, $data['item_description']);
        $sheet->setCellValue('E' . $row, $data['unit']);
        $sheet->setCellValue('F' . $row, $data['issuance']);
        $sheet->mergeCells('H' . $row . ':I' . $row);
        $row++;
    }
}

// Start recapitulation right after the data
$recap_start_row = $row + 2; // Add a little space

// Add recapitulation sections
$sheet->setCellValue('A' . $recap_start_row, 'Recapitulation:');
$sheet->getStyle('A' . $recap_start_row)->getFont()->setBold(true);
$sheet->setCellValue('A' . ($recap_start_row + 1), 'Stock No.');
$sheet->setCellValue('B' . ($recap_start_row + 1), 'Quantity');

$sheet->setCellValue('F' . $recap_start_row, 'Recapitulation:');
$sheet->getStyle('F' . $recap_start_row)->getFont()->setBold(true);
$sheet->setCellValue('F' . ($recap_start_row + 1), 'Unit Cost');
$sheet->setCellValue('G' . ($recap_start_row + 1), 'Total Cost');
$sheet->setCellValue('H' . ($recap_start_row + 1), 'UACS Object Code');

// Style recapitulation headers
$sheet->getStyle('A' . ($recap_start_row + 1) . ':B' . ($recap_start_row + 1))->getFont()->setBold(true);
$sheet->getStyle('F' . ($recap_start_row + 1) . ':I' . ($recap_start_row + 1))->getFont()->setBold(true);

// Define end of recap area for borders
$recap_end_row = $recap_start_row + 15; // As per screenshot, it has some height

// Add footer information, positioned after recapitulation
$footer_start_row = $recap_end_row + 2;
$sheet->mergeCells('A' . $footer_start_row . ':D' . $footer_start_row);
$sheet->setCellValue('A' . $footer_start_row, 'I hereby certify to the correctness of the above information.');
$sheet->mergeCells('A' . ($footer_start_row + 2) . ':D' . ($footer_start_row + 2));
$sheet->setCellValue('A' . ($footer_start_row + 2), 'Signature over Printed Name of Supply and/or Property Custodian');

$sheet->mergeCells('G' . $footer_start_row . ':I' . $footer_start_row);
$sheet->setCellValue('G' . $footer_start_row, 'Posted by:');
$sheet->mergeCells('G' . ($footer_start_row + 2) . ':I' . ($footer_start_row + 2));
$sheet->setCellValue('G' . ($footer_start_row + 2), 'Signature over Printed Name of Designated Accounting Staff');

// Set column widths
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);

// Set borders for the table
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];
if ($row > 9) {
    $sheet->getStyle('A8:I' . ($row - 1))->applyFromArray($styleArray);
}
$sheet->getStyle('A' . ($recap_start_row + 1) . ':B' . $recap_end_row)->applyFromArray($styleArray);
$sheet->getStyle('F' . ($recap_start_row + 1) . ':I' . $recap_end_row)->applyFromArray($styleArray);

// Apply thick outer border
$thickBorderStyle = [
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THICK,
        ],
    ],
];
$sheet->getStyle('A1:I' . ($footer_start_row + 3))->applyFromArray($thickBorderStyle);

// Output the file to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="consumable_report.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$conn->close();
exit;
?>