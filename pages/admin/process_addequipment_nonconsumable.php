<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Clean and get form data from JS
    $type_id = intval($_POST['type_id']);
    $inv_bnm = trim($_POST['inv_bnm']);
    $serialno = trim($_POST['inv_serialno']);
    $propertyno = trim($_POST['inv_propno']);
    $propertyname = trim($_POST['inv_propname']);
    $inv_status = intval($_POST['inv_status']);
    $condition = trim($_POST['condition']);
    $inv_quantity = intval($_POST['inv_quantity']);
    $price = floatval($_POST['price']);
    $date_acquired = trim($_POST['date_acquired']);
    $end_user = trim($_POST['end_user']);
    $accounted_to = trim($_POST['accounted_to']);

    $formatted_date = date("F d, Y h:i A");

    // Required field validation
    if (empty($type_id) || empty($inv_bnm) || empty($propertyname)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Check for duplicates if force_insert is not set
    if (!isset($_POST['force_insert'])) {
        $checkQuery = "SELECT COUNT(*) FROM tbl_inv WHERE inv_serialno = ? AND inv_propno = ? AND inv_propname = ? AND type_id = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("sssi", $serialno, $propertyno, $propertyname, $type_id);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            echo json_encode([
                'status' => 'warning',
                'message' => 'A record with the same Serial No, Property No, Property Name, and Type already exists. Do you want to add it anyway?'
            ]);
            exit;
        }
    }

    // Proceed to insert
    $insertQuery = "INSERT INTO tbl_inv (type_id, inv_serialno, inv_propno, inv_propname, inv_status, inv_bnm, inv_date_added, `condition`, inv_quantity, price, date_acquired, end_user, accounted_to)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    if ($stmt) {
        $stmt->bind_param("isssisssissss", $type_id, $serialno, $propertyno, $propertyname, $inv_status, $inv_bnm, $formatted_date, $condition, $inv_quantity, $price, $date_acquired, $end_user, $accounted_to);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Equipment added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Insertion failed: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>