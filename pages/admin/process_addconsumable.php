<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $type_id = isset($_POST['cinv_type']) ? intval($_POST['cinv_type']) : 0;
    $brand = trim($_POST['cbrand'] ?? '');
    $model = trim($_POST['cmodel'] ?? '');
    $serialno = trim($_POST['cserialno'] ?? $_POST['cserialno1'] ?? '');
    $propertyno = trim($_POST['cpropertyno'] ?? '');
    $propertyname = trim($_POST['cpropertyname'] ?? '');
    $quantity = intval($_POST['cquantity'] ?? 1);

    $inv_bnm = $brand . ' ' . $model;
    $inv_status = 1; // Available
    $formatted_date = date("F d, Y h:i A");

    // Validate required fields
    if (empty($type_id) || empty($brand) || empty($model) || empty($serialno) || empty($propertyno) || empty($propertyname) || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields correctly.']);
        exit;
    }

    // Check if exact duplicate exists
    $checkQuery = "SELECT COUNT(*) FROM tbl_inv WHERE inv_serialno = ? AND inv_propno = ? AND inv_propname = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("sss", $serialno, $propertyno, $propertyname);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Exact duplicate found. Equipment was not added.']);
        $conn->close();
        exit;
    }

    // Check if at least 2 fields match (optional check, but we won't block new entries)
    $checkQueryTwoFields = "SELECT COUNT(*) FROM tbl_inv WHERE (inv_serialno = ? AND inv_propno = ?) OR (inv_serialno = ? AND inv_propname = ?) OR (inv_propno = ? AND inv_propname = ?)";
    $stmtCheckTwoFields = $conn->prepare($checkQueryTwoFields);
    $stmtCheckTwoFields->bind_param("ssssss", $serialno, $propertyno, $serialno, $propertyname, $propertyno, $propertyname);
    $stmtCheckTwoFields->execute();
    $stmtCheckTwoFields->bind_result($countTwoFields);
    $stmtCheckTwoFields->fetch();
    $stmtCheckTwoFields->close();

    // Insert the equipment no matter if $countTwoFields is > 0 or == 0
    $query = "INSERT INTO tbl_inv (type_id, inv_serialno, inv_propno, inv_propname, inv_status, inv_bnm, inv_date_added, inv_quantity) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("isssissi", $type_id, $serialno, $propertyno, $propertyname, $inv_status, $inv_bnm, $formatted_date, $quantity);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Equipment added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error in adding equipment.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing query.']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>