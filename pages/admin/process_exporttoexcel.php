<?php
require __DIR__ . '/../../vendor/autoload.php';    // PhpSpreadsheet
require __DIR__ . '/../../function_connection.php'; // DB connection

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

try {
    $origin = isset($_GET['origin']) ? $_GET['origin'] : '';
    $type = isset($_GET['type']) ? trim($_GET['type']) : '';
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Inventory Report');

    // Add title
    $sheet->mergeCells('A1:O1');
    $sheet->setCellValue('A1', 'Inventory Report - Non-Consumable');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    if ($origin === 'nonconsumable') {
        // Headers for non-consumable
        $headers = [
            'ID', 'Type ID', 'Serial No.', 'Property No.', 'Property Name', 'Inventory Price', 'Status', 'Brand/Model', 'Date Added', 'Date Acquired', 'Price', 'Condition', 'Quantity', 'End User', 'Accounted To'
        ];
        $colCount = count($headers);
        // Set headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }
        // Style header
        $headerRange = 'A3:' . chr(ord('A') + $colCount - 1) . '3';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [ 'bold' => true, 'color' => ['rgb' => 'FFFFFF'] ],
            'fill' => [ 'fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '007bff'] ],
            'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER ],
            'borders' => [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000'] ] ]
        ]);
        foreach (range('A', chr(ord('A') + $colCount - 1)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Build query
        $sql = "SELECT i.inv_id, i.type_id, i.inv_serialno, i.inv_propno, i.inv_propname, i.inv_price, i.inv_status, i.inv_bnm, i.inv_date_added, i.date_acquired, i.price, i.condition, i.inv_quantity, i.end_user, i.accounted_to, t.type_name FROM tbl_inv i LEFT JOIN tbl_type t ON i.type_id = t.type_id WHERE t.type_origin = 'Non-Consumable'";
        $params = [];
        $types = '';
        if ($type !== '') {
            $sql .= " AND t.type_name = ?";
            $params[] = $type;
            $types .= 's';
        }
        $sql .= " ORDER BY i.inv_date_added DESC";
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = 4;
        while ($record = $result->fetch_assoc()) {
            // Map status
            switch ($record['inv_status']) {
                case 1:
                case '1': $status = 'Available'; break;
                case 2:
                case '2': $status = 'Unavailable'; break;
                case 3:
                case '3': $status = 'Pending For Approval'; break;
                case 4:
                case '4': $status = 'Borrowed'; break;
                case 5:
                case '5': $status = 'Returned'; break;
                case 6:
                case '6': $status = 'Missing'; break;
                case 7:
                case '7': $status = 'In Use'; break;
                default: $status = $record['inv_status']; break;
            }
            $sheet->setCellValue('A' . $row, $record['inv_id']);
            $sheet->setCellValue('B' . $row, $record['type_id']);
            $sheet->setCellValue('C' . $row, $record['inv_serialno']);
            $sheet->setCellValue('D' . $row, $record['inv_propno']);
            $sheet->setCellValue('E' . $row, $record['inv_propname']);
            $sheet->setCellValue('F' . $row, $record['inv_price']);
            $sheet->setCellValue('G' . $row, $status);
            $sheet->setCellValue('H' . $row, $record['inv_bnm']);
            $sheet->setCellValue('I' . $row, $record['inv_date_added']);
            $sheet->setCellValue('J' . $row, $record['date_acquired']);
            $sheet->setCellValue('K' . $row, $record['price']);
            $sheet->setCellValue('L' . $row, $record['condition']);
            $sheet->setCellValue('M' . $row, $record['inv_quantity']);
            $sheet->setCellValue('N' . $row, $record['end_user']);
            $sheet->setCellValue('O' . $row, $record['accounted_to']);
            $row++;
        }
        if ($row > 4) {
            $dataRange = 'A4:' . chr(ord('A') + $colCount - 1) . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000'] ] ]
            ]);
        }
    } elseif ($origin === 'consumable') {
        // Add title
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Inventory Report - Consumable');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Headers for consumable
        $headers = [
            'Stock No.', 'Acceptance Date', 'RIS No.', 'Item Description', 'Receipt', 'Issuance', 'End User', 'Unit'
        ];
        $colCount = count($headers);
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }
        $headerRange = 'A3:' . chr(ord('A') + $colCount - 1) . '3';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [ 'bold' => true, 'color' => ['rgb' => 'FFFFFF'] ],
            'fill' => [ 'fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '007bff'] ],
            'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER ],
            'borders' => [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000'] ] ]
        ]);
        foreach (range('A', chr(ord('A') + $colCount - 1)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sql = "SELECT c.stock_number, c.acceptance_date, c.ris_no, c.item_description, c.receipt, c.issuance, c.end_user_issuance, c.unit FROM tbl_inv_consumables c ORDER BY c.acceptance_date DESC";
        $result = $conn->query($sql);
        $row = 4;
        while ($record = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $row, $record['stock_number']);
            $sheet->setCellValue('B' . $row, $record['acceptance_date']);
            $sheet->setCellValue('C' . $row, $record['ris_no']);
            $sheet->setCellValue('D' . $row, $record['item_description']);
            $sheet->setCellValue('E' . $row, $record['receipt']);
            $sheet->setCellValue('F' . $row, $record['issuance']);
            $sheet->setCellValue('G' . $row, $record['end_user_issuance']);
            $sheet->setCellValue('H' . $row, $record['unit']);
            $row++;
        }
        if ($row > 4) {
            $dataRange = 'A4:' . chr(ord('A') + $colCount - 1) . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000'] ] ]
            ]);
        }
    } else {
        throw new Exception('Invalid export type.');
    }

    if (ob_get_length()) {
        ob_clean();
    }
    $filename = 'inventory_report_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);
} catch (Exception $e) {
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