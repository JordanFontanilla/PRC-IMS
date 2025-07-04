<?php
// Enable robust error handling to diagnose the issue
error_reporting(E_ALL);
ini_set('display_errors', 0); // Do not display errors directly to keep the output clean
ini_set('log_errors', 1);

// Set a custom error handler to catch fatal errors
set_exception_handler(function ($exception) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'An unexpected error occurred.',
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    exit;
});

// Include required files
require '../../vendor/autoload.php';
require '../../function_connection.php'; // Use the same connection as the working import script

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Check if we're exporting consumables
    $isConsumable = isset($_GET['consumable']) && $_GET['consumable'] === 'true';

    // Create new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Define header styles
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4285F4'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    // Set headers and query based on type
    if ($isConsumable) {
        $sheet->setTitle('Consumable Items');
        $headers = ['Type', 'Brand/Model', 'Serial No.', 'Property No.', 'Property Name', 'Quantity', 'Date Acquired', 'Price', 'Status'];
        $sheet->fromArray($headers, NULL, 'A1');
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        
        $sql = "SELECT 
                    t.type_name, 
                    c.inv_bnm, 
                    c.inv_serialno, 
                    c.inv_propno, 
                    c.inv_propname, 
                    c.inv_quantity,
                    c.date_acquired, 
                    c.price,
                    CASE c.inv_status 
                        WHEN 1 THEN 'Available' 
                        WHEN 2 THEN 'Unavailable' 
                        WHEN 3 THEN 'Pending' 
                        ELSE 'Unknown' 
                    END as status
                FROM tbl_inv_consumables c
                LEFT JOIN tbl_type t ON c.type_id = t.type_id
                ORDER BY t.type_name, c.inv_bnm";
    } else {
        $sheet->setTitle('Non-Consumable Items');
        $headers = ['Type', 'Brand/Model', 'Serial No.', 'Property No.', 'Property Name', 'Date Acquired', 'Price', 'Condition', 'Status'];
        $sheet->fromArray($headers, NULL, 'A1');
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        $sql = "SELECT 
                    t.type_name, 
                    i.inv_bnm, 
                    i.inv_serialno, 
                    i.inv_propno, 
                    i.inv_propname, 
                    i.date_acquired, 
                    i.price, 
                    i.condition,
                    CASE i.inv_status 
                        WHEN 1 THEN 'Available' 
                        WHEN 2 THEN 'Unavailable' 
                        WHEN 3 THEN 'Pending' 
                        WHEN 4 THEN 'Borrowed' 
                        WHEN 5 THEN 'Returned' 
                        WHEN 6 THEN 'Missing' 
                        ELSE 'Unknown' 
                    END as status
                FROM tbl_inv i 
                LEFT JOIN tbl_type t ON i.type_id = t.type_id 
                ORDER BY t.type_name, i.inv_bnm";
    }

    // Execute query
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    // Add data to spreadsheet
    if ($result->num_rows > 0) {
        $row = 2;
        while ($data = $result->fetch_assoc()) {
            $rowData = array_values($data);
            $sheet->fromArray($rowData, NULL, 'A' . $row);
            $row++;
        }
    }

    // Auto-size all columns
    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Set filename and headers for download
    $filename = $isConsumable ? 'Consumable_Inventory_' : 'NonConsumable_Inventory_';
    $filename .= date('Ymd') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Output the file
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    $conn->close();
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'A caught exception occurred.',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    exit;
} 