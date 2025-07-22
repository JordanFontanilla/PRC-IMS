<?php
ob_start(); // Start output buffering
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0); // Set unlimited execution time
ini_set('memory_limit', '512M'); // Increase memory limit
header('Content-Type: application/json');

require '../../vendor/autoload.php';
require '../../function_connection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

try {
    if (!isset($_FILES['excelFileBalance']) || $_FILES['excelFileBalance']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or an upload error occurred.');
    }

    $file = $_FILES['excelFileBalance']['tmp_name'];
    $spreadsheet = IOFactory::load($file);

    $sheetName = $_POST['excelSheet'] ?? null;
    if ($sheetName === null) {
        throw new Exception('No sheet name provided.');
    }

    $sheet = $spreadsheet->getSheetByName($sheetName);
    if ($sheet === null) {
        throw new Exception('Sheet "' . htmlspecialchars($sheetName) . '" not found.');
    }
    
    $data = $sheet->toArray();
    error_log('Total rows read from Excel: ' . count($data));

    $importedRowCount = 0; // Counter for successfully imported rows

    // Assuming data starts from the first row (index 0). Adjust if your file has headers.
    $startRow = 0; 

    for ($i = $startRow; $i < count($data); $i++) {
        error_log('Processing row ' . ($i + 1) . ' of ' . count($data) . ': ' . json_encode($data[$i]));
        $row = $data[$i];

        // Skip empty rows
        if (empty(array_filter($row))) {
            error_log('Skipping empty row ' . ($i + 1));
            continue;
        }

        // Ensure array keys exist before accessing and trim values
        $stock_number = trim($row[0] ?? '');
        $beginning_balance = trim($row[1] ?? '');
        $unit = trim($row[2] ?? '');
        $item_description = trim($row[3] ?? '');

        // Basic validation: only Stock Number is required for a row to be processed
        if (empty($stock_number)) {
            error_log("Skipping row " . ($i + 1) . " due to missing Stock Number: " . json_encode($row));
            continue; // Skip this row if Stock Number is missing
        }

        // Convert beginning_balance to integer, handle potential non-numeric values
        $beginning_balance = intval($beginning_balance);

        error_log("Attempting to insert/update: Stock No=" . $stock_number . ", Balance=" . $beginning_balance . ", Unit=" . $unit . ", Desc=" . $item_description);

        $query = "INSERT INTO tbl_consumable_balance (stock_number, item_description, unit, beginning_balance) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE beginning_balance = VALUES(beginning_balance), unit = VALUES(unit), item_description = VALUES(item_description)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            error_log('Failed to prepare SQL statement for row ' . ($i + 1) . ': ' . $conn->error);
            throw new Exception('Failed to prepare SQL statement: ' . $conn->error);
        }
        $stmt->bind_param('sssi', $stock_number, $item_description, $unit, $beginning_balance);
        
        if ($stmt->execute()) {
            error_log('Successfully processed row ' . ($i + 1));
            $importedRowCount++;
        } else {
            error_log('Failed to execute SQL statement for row ' . ($i + 1) . ': ' . $stmt->error);
            // Do not throw exception here, continue processing other rows if one fails
        }
        $stmt->close();
    }

    $response = ['success' => true, 'message' => 'Beginning balances updated successfully. Total imported: ' . $importedRowCount];

} catch (Exception $e) {
    $response['message'] = 'Import failed: ' . $e->getMessage();
    error_log('Import Beginning Balance Error: ' . $e->getMessage());
} finally {
    if ($conn) {
        $conn->close();
    }
}

ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
echo json_encode($response);
?>