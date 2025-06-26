<?php
ob_start(); // Start output buffering
header('Content-Type: application/json');

// Enable error reporting (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Response structure
$response = [];

try {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== 0) {
        throw new Exception("No file uploaded or upload error.");
    }

    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExts = ['xlsx', 'xls'];

    if (!in_array($fileExt, $allowedExts)) {
        throw new Exception("Invalid file type. Please upload an Excel file.");
    }

    require '../../vendor/autoload.php';         // PhpSpreadsheet
    require '../../function_connection.php';     // DB connection

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $insertedCount = 0;
    $redundantCount = 0;

    array_shift($rows); // Skip header row

    foreach ($rows as $row) {
        if (count($row) < 9) continue;

        $type_id = trim($row[0]);
        $inv_serialno = trim($row[1]);
        $inv_propno = trim($row[2]);
        $inv_propname = trim($row[3]);
        $inv_bnm = trim($row[4]);
        $end_user = trim($row[7]);
        $accounted_to = trim($row[8]);

        if (empty($inv_propname)) continue;


        $inv_date_added = date('F j, Y h:i A');
        $inv_status = 1;
        $inv_quantity = 1;

        // Check for duplicates
        // NEW: Check all 5 fields for exact match
        $checkQuery = "SELECT COUNT(*) FROM tbl_inv 
        WHERE type_id = ? AND inv_serialno = ? AND inv_propno = ? AND inv_propname = ? AND inv_bnm = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("issss", $type_id, $inv_serialno, $inv_propno, $inv_propname, $inv_bnm);

        $stmtCheck->execute();
        $stmtCheck->bind_result($rowCount);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($rowCount > 0) {
            $redundantCount++;
            continue;
        }

        // Insert record
        $query = "INSERT INTO tbl_inv 
            (type_id, inv_serialno, inv_propno, inv_propname, inv_bnm, inv_date_added, inv_status, inv_quantity, end_user, accounted_to)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            continue;
        }

        $stmt->bind_param("isssssisss", $type_id, $inv_serialno, $inv_propno, $inv_propname, $inv_bnm, $inv_date_added, $inv_status, $inv_quantity, $end_user, $accounted_to);

        if (!$stmt->execute()) {
            error_log("Execute failed for serial [$inv_serialno]: " . $stmt->error);
            $stmt->close();
            continue;
        }

        $stmt->close();
        $insertedCount++;
    }

    $conn->close();

    $response = [
        'inserted' => $insertedCount,
        'redundant' => $redundantCount
    ];

} catch (Exception $e) {
    error_log("Import error: " . $e->getMessage());
    $response = ['error' => $e->getMessage()];
}

// Discard any stray output and return JSON only
ob_end_clean();
echo json_encode($response);
exit;
?>
