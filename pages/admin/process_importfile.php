<?php
ob_start(); // Start output buffering
header('Content-Type: application/json');

// Enable error reporting (for debugging)
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Processes the import of consumable items from a spreadsheet.
 *
 * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The worksheet containing the data.
 * @param mysqli $conn The database connection.
 * @return array An array with counts of inserted and redundant rows.
 */
function importConsumables($sheet, $conn) {
    $insertedCount = 0;
    $redundantCount = 0;
    $rowIterator = $sheet->getRowIterator(5); // Start from row 5

    foreach ($rowIterator as $row) {
        $cellIterator = $row->getCellIterator('A', 'H'); // Read columns A to H
        $cellIterator->setIterateOnlyExistingCells(false);
        $cells = [];
        foreach ($cellIterator as $cell) {
            $cells[] = $cell->getFormattedValue();
        }

        if (empty(array_filter($cells))) {
            continue; // Skip empty rows
        }

        // Assign cells to variables based on the new specified format
        $stock_number = $cells[0] ?? null;       // Column A
        $excelDate = $cells[1] ?? null;         // Column B (Acceptance Date)
        $ris_no = $cells[2] ?? null;            // Column C
        $item_description = $cells[3] ?? null;  // Column D
        $receipt = isset($cells[4]) ? intval($cells[4]) : 0; // Column E
        $issuance = isset($cells[5]) ? intval($cells[5]) : 0; // Column F
        $end_user_issuance = $cells[6] ?? null; // Column G
        $unit = $cells[7] ?? null;              // Column H

        if (empty($excelDate) || empty($ris_no) || empty($item_description)) {
            error_log("Skipping row due to missing essential data: Date='{$excelDate}', RIS='{$ris_no}', Desc='{$item_description}'");
            continue;
        }

        try {
            // Attempt to parse the date. Handle Excel date serials and other formats.
            if (is_numeric($excelDate) && $excelDate > 0) {
                $acceptance_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
            } else {
                $timestamp = strtotime($excelDate);
                if ($timestamp === false) {
                    throw new Exception("strtotime failed to parse date: {$excelDate}");
                }
                $acceptance_date = date('Y-m-d', $timestamp);
            }
        } catch (Exception $e) {
            error_log("Could not parse date '{$excelDate}'. Error: " . $e->getMessage() . ". Skipping row.");
            continue;
        }

        // Check for duplicates (using stock_number, acceptance_date, ris_no, item_description)
        $checkQuery = "SELECT COUNT(*) FROM tbl_inv_consumables WHERE stock_number = ? AND acceptance_date = ? AND ris_no = ? AND item_description = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("ssss", $stock_number, $acceptance_date, $ris_no, $item_description);
        $stmtCheck->execute();
        $stmtCheck->bind_result($rowCount);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($rowCount > 0) {
            $redundantCount++;
            continue;
        }

        // Insert new data
        $query = "INSERT INTO tbl_inv_consumables (stock_number, acceptance_date, ris_no, item_description, unit, receipt, issuance, end_user_issuance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssiis", $stock_number, $acceptance_date, $ris_no, $item_description, $unit, $receipt, $issuance, $end_user_issuance);
        
        if ($stmt->execute()) {
            $insertedCount++;
        } else {
            error_log("Consumable insert failed for RIS No. [$ris_no]: " . $stmt->error);
        }
        $stmt->close();
    }
    return ['inserted' => $insertedCount, 'redundant' => $redundantCount];
}

/**
 * Processes the import of non-consumable items from a spreadsheet.
 *
 * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The worksheet containing the data.
 * @param mysqli $conn The database connection.
 * @return array An array with counts of inserted and redundant rows.
 */
function importNonConsumables($sheet, $conn) {
    $rows = $sheet->toArray();
    array_shift($rows); // Skip header row

    $insertedCount = 0;
    $redundantCount = 0;

    foreach ($rows as $row) {
        if (count($row) < 7 || empty(array_filter($row))) {
            continue;
        }

        $type_id = trim($row[0]);
        $inv_serialno = trim($row[1]);
        $inv_propno = trim($row[2]);
        $inv_propname = trim($row[3]);
        $inv_bnm = trim($row[4]);
        $end_user = trim($row[5]);
        $accounted_to = trim($row[6]);

        if (empty($inv_serialno) && empty($inv_propno) && empty($inv_propname)) {
            continue;
        }

        $checkQuery = "SELECT COUNT(*) FROM tbl_inv WHERE inv_serialno = ? AND inv_propno = ? AND inv_propname = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("sss", $inv_serialno, $inv_propno, $inv_propname);
        $stmtCheck->execute();
        $stmtCheck->bind_result($rowCount);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($rowCount > 0) {
            $redundantCount++;
            continue;
        }

        $inv_status = 1;
        $inv_quantity = 1;
        $formattedDate = date('F j, Y h:i A');
        $query = "INSERT INTO tbl_inv (type_id, inv_serialno, inv_propno, inv_propname, inv_bnm, inv_status, inv_quantity, inv_date_added, end_user, accounted_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssiisss", $type_id, $inv_serialno, $inv_propno, $inv_propname, $inv_bnm, $inv_status, $inv_quantity, $formattedDate, $end_user, $accounted_to);
        
        if ($stmt->execute()) {
            $insertedCount++;
        } else {
            error_log("Non-consumable insert failed for Serial No. [$inv_serialno]: " . $stmt->error);
        }
        $stmt->close();
    }
    return ['inserted' => $insertedCount, 'redundant' => $redundantCount];
}

$response = [];

try {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("No file uploaded or an upload error occurred. Error code: " . ($_FILES['excelFile']['error'] ?? 'N/A'));
    }

    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['xlsx', 'xls'];

    if (!in_array($fileExt, $allowedExts)) {
        throw new Exception("Invalid file type. Please upload an Excel file (xlsx, xls).");
    }

    require '../../vendor/autoload.php';
    require '../../function_connection.php';

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
    $sheetName = $_POST['excelSheet'] ?? $spreadsheet->getSheetNames()[0];
    $sheet = $spreadsheet->getSheetByName($sheetName);

    if (!$sheet) {
        throw new Exception("Sheet '$sheetName' not found in the Excel file.");
    }

    $importType = $_POST['importItemType'] ?? 'Non-Consumable';

    if ($importType === 'Consumable') {
        $response = importConsumables($sheet, $conn);
    } else {
        $response = importNonConsumables($sheet, $conn);
    }

    $conn->close();

} catch (Exception $e) {
    error_log("Import script error: " . $e->getMessage());
    http_response_code(400); // Bad Request
    $response = ['error' => $e->getMessage()];
}

ob_end_clean();
echo json_encode($response);
exit;
?>