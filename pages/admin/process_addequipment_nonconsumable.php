<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Clean and get form data
    $type_id = intval($_POST['type_id']);
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $serialno = trim($_POST['serialno']);
    $propertyno = trim($_POST['propertyno']);
    $propertyname = trim($_POST['propertyname']);
    $inv_bnm = $brand . ' ' . $model;
    $inv_status = 1;
    $formatted_date = date("F d, Y h:i A");
    $enduser = trim($_POST['enduser']);
    $accountedto = trim($_POST['accountedto']);

    // Required field validation
    if (empty($type_id) || empty($propertyno) || empty($propertyname)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Main duplicate check: type + propno + propname
    $checkQuery = "SELECT COUNT(*) FROM tbl_inv WHERE type_id = ? AND inv_propno = ? AND inv_propname = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("iss", $type_id, $propertyno, $propertyname);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Equipment with the same type, property number, and division/section already exists.'
        ]);
        $conn->close();
        exit;
    }

    // Optional: check for potential duplicates (e.g., type + serial)
    if (!empty($serialno)) {
        $partialQuery = "SELECT COUNT(*) FROM tbl_inv WHERE type_id = ? AND inv_serialno = ?";
        $stmtPartial = $conn->prepare($partialQuery);
        $stmtPartial->bind_param("is", $type_id, $serialno);
        $stmtPartial->execute();
        $stmtPartial->bind_result($partialCount);
        $stmtPartial->fetch();
        $stmtPartial->close();

        if ($partialCount > 0) {
            echo json_encode([
                'status' => 'warning',
                'message' => 'This serial number already exists for the same type. Proceed with caution.'
            ]);
            $conn->close();
            exit;
        }
    }

    // Proceed to insert
    $insertQuery = "INSERT INTO tbl_inv (type_id, inv_serialno, inv_propno, inv_propname, inv_status, inv_bnm, inv_date_added, inv_quantity, end_user, accounted_to)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    if ($stmt) {
        $stmt->bind_param("isssissss", $type_id, $serialno, $propertyno, $propertyname, $inv_status, $inv_bnm, $formatted_date, $enduser, $accountedto);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Equipment added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Insertion failed. Please try again.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
