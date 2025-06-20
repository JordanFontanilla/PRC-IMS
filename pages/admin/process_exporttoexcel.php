<?php
require __DIR__ . '/../../vendor/autoload.php';    // PhpSpreadsheet
require __DIR__ . '/../../function_connection.php'; // DB connection

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Generate dynamic filename
$date = date('Ymd');
$filename = "EQUIPMENTLIST_{$date}.xlsx";
$exportDir = __DIR__ . '/../../exports/';
$exportPath = $exportDir . $filename;

// Ensure export directory exists
if (!is_dir($exportDir)) {
    if (!mkdir($exportDir, 0755, true) && !is_dir($exportDir)) {
        echo json_encode([
            'status' => 'error',
            'message' => "Unable to create directory: $exportDir"
        ]);
        exit;
    }
}

// Fetch data from database
$query = "SELECT type_id, inv_serialno, inv_propno, inv_propname, inv_bnm, inv_status, inv_date_added, end_user, accounted_to FROM tbl_inv";
$result = $conn->query($query);

if (!$result) {
    echo json_encode([
        'status' => 'error',
        'message' => 'DB query failed: ' . $conn->error
    ]);
    exit;
}

// Create and populate spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$columns = [
    'A' => 'Type ID',
    'B' => 'Serial No',
    'C' => 'Property No',
    'D' => 'Property Name',
    'E' => 'Brand Name',
    'F' => 'Status',
    'G' => 'Date Added',
    'H' => 'End User',
    'I' => 'Accounted To',
];
foreach ($columns as $col => $header) {
    $sheet->setCellValue("{$col}1", $header);
}

$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A{$rowIndex}", $row['type_id']);
    $sheet->setCellValue("B{$rowIndex}", $row['inv_serialno']);
    $sheet->setCellValue("C{$rowIndex}", $row['inv_propno']);
    $sheet->setCellValue("D{$rowIndex}", $row['inv_propname']);
    $sheet->setCellValue("E{$rowIndex}", $row['inv_bnm']);
    $sheet->setCellValue("F{$rowIndex}", $row['inv_status']);
    $sheet->setCellValue("G{$rowIndex}", $row['inv_date_added']);
    $sheet->setCellValue("H{$rowIndex}", $row['end_user']);
    $sheet->setCellValue("I{$rowIndex}", $row['accounted_to']);
    $rowIndex++;
}

// Write to file using IOFactory
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->save($exportPath);


// Return JSON response to frontend
echo json_encode([
    'status'   => 'success',
    'message'  => 'Export successful!',
    'filename' => $filename,
    'filepath' => $exportPath
]);
exit;
