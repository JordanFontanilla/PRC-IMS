<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';  // Include database connection

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data from JS
    $stock_number = trim($_POST['stock_number'] ?? '');
    $acceptance_date = trim($_POST['acceptance_date'] ?? '');
    $ris_no = trim($_POST['ris_no'] ?? '');
    $item_description = trim($_POST['item_description'] ?? '');
    $unit = trim($_POST['unit'] ?? '');
    $receipt = intval($_POST['receipt'] ?? 0);
    $issuance = intval($_POST['issuance'] ?? 0);
    $end_user_issuance = trim($_POST['end_user_issuance'] ?? null);

    // Format acceptance_date if provided
    $acceptance_date_formatted = !empty($acceptance_date) ? date("Y-m-d", strtotime($acceptance_date)) : null;

    // Validate required fields
    if (empty($acceptance_date_formatted)) {
        echo json_encode(['status' => 'error', 'message' => 'Acceptance Date is required.']);
        exit;
    }
    if (empty($ris_no)) {
        echo json_encode(['status' => 'error', 'message' => 'RIS No. is required.']);
        exit;
    }
    if (empty($item_description)) {
        echo json_encode(['status' => 'error', 'message' => 'Item Description is required.']);
        exit;
    }

    // Prepare insert query for tbl_inv_consumables
    $query = "INSERT INTO tbl_inv_consumables (
        stock_number, acceptance_date, ris_no, item_description, unit, receipt, issuance, end_user_issuance
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param(
            "sssssiis",
            $stock_number, $acceptance_date_formatted, $ris_no, $item_description, $unit, $receipt, $issuance, $end_user_issuance
        );
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Consumable equipment added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error in adding equipment: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing query: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>