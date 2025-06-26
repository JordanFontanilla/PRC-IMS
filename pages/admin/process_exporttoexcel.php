<?php
require __DIR__ . '/../../vendor/autoload.php';    // PhpSpreadsheet
require __DIR__ . '/../../function_connection.php'; // DB connection

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

try {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Inventory Report');

    // Section titles => corresponding DB columns
    $headers = [
        'Type',
        'Brand/Model', 
        'Serial No.',
        'Property No.',
        'Property Name',
        'Status'
    ];

    // Set headers in row 1
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }

    // Style the header row
    $headerRange = 'A1:F1';
    $sheet->getStyle($headerRange)->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '007bff'] // Bootstrap primary blue
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ]);

    // Auto-size columns
    foreach (range('A', 'F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Fetch data from database using mysqli with JOIN for type name
    // Replace table names and column names with your actual structure
    $query = "SELECT 
                t.type_name as type,
                i.inv_bnm,
                i.inv_serialno,
                i.inv_propno,
                i.inv_propname,
                i.inv_status,
                CASE
                    WHEN i.inv_status = 1 THEN 'Available'
                    WHEN i.inv_status = 2 THEN 'Unavailable'
                    WHEN i.inv_status = 3 THEN 'Pending for Approval'
                    WHEN i.inv_status = 4 THEN 'Borrowed'
                    WHEN i.inv_status = 5 THEN 'Returned'
                    WHEN i.inv_status = 6 THEN 'Missing'
                END as status
              FROM tbl_inv i
              LEFT JOIN tbl_type t ON i.type_id = t.type_id"; // Adjust table names and column names as needed
    
    $result = mysqli_query($conn, $query);
    $data = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_free_result($result);
    } else {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }
    
    // Populate data starting from row 2
    $row = 2;
    foreach ($data as $record) {
        $sheet->setCellValue('A' . $row, $record['type'] ?? '');
        $sheet->setCellValue('B' . $row, $record['inv_bnm'] ?? '');
        $sheet->setCellValue('C' . $row, $record['inv_serialno'] ?? '');
        $sheet->setCellValue('D' . $row, $record['inv_propno'] ?? '');
        $sheet->setCellValue('E' . $row, $record['inv_propname'] ?? '');
        $sheet->setCellValue('F' . $row, $record['status'] ?? '');
        $row++;
    }
    
    // Apply borders to data rows
    if ($row > 2) {
        $dataRange = 'A2:F' . ($row - 1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
    }

    // Clear any previous output
    if (ob_get_length()) {
        ob_clean();
    }

    // Set headers for Excel file download
    $filename = 'inventory_report_' . date('Y-m-d_H-i-s') . '.xlsx';
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    // Create writer and output file directly
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    // Clean up
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);
    
} catch (Exception $e) {
    // If there's an error, clear output and send JSON error
    if (ob_get_length()) {
        ob_clean();
    }
    
    header('Content-Type: application/json');   
    echo json_encode([
        'status' => 'error',
        'message' => 'Export failed: ' . $e->getMessage()
    ]);
}

exit;
?>