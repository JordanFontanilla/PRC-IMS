<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize data
    $type_id = intval($_POST['type_id'] ?? 0);
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $serialno = trim($_POST['serialno'] ?? '');
    $propertyno = trim($_POST['propertyno'] ?? '');
    $propertyname = trim($_POST['propertyname'] ?? '');
    $date_acquired = !empty($_POST['date_acquired']) ? $_POST['date_acquired'] : null;
    $price = !empty($_POST['price']) ? floatval($_POST['price']) : 0.00;
    $condition = trim($_POST['condition'] ?? 'New');
    $enduser = trim($_POST['enduser'] ?? '');
    $accountedto = trim($_POST['accountedto'] ?? '');

    // Combine brand and model
    $inv_bnm = trim("$brand $model");
    $inv_status = 1; // Default to Available
    $formatted_date = date("F d, Y h:i A");

    if (empty($type_id) || empty($propertyno) || empty($propertyname)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    $checkQuery = "SELECT * FROM tbl_inv WHERE type_id = ? AND inv_serialno = ? AND inv_propno = ? AND inv_propname = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("isss", $type_id, $serialno, $propertyno, $propertyname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'An exact duplicate already exists.']);
        exit;
    }

    // More specific partial duplicate check
    $partial_checks = [];
    $partial_params = [];
    $partial_types = "";
    if (!empty($serialno)) {
        $partial_checks[] = "inv_serialno = ?";
        $partial_params[] = $serialno;
        $partial_types .= "s";
    }
    if (!empty($propertyno)) {
        $partial_checks[] = "inv_propno = ?";
        $partial_params[] = $propertyno;
        $partial_types .= "s";
    }
    
    $has_partial_duplicates = false;
    if (!empty($partial_checks)) {
        $checkPartialQuery = "SELECT COUNT(*) FROM tbl_inv WHERE " . implode(' OR ', $partial_checks);
        $stmt = $conn->prepare($checkPartialQuery);
        $stmt->bind_param($partial_types, ...$partial_params);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if ($count > 0) {
            $has_partial_duplicates = true;
        }
        $stmt->close();
    }

    if ($has_partial_duplicates && !isset($_POST['force_add'])) {
        echo json_encode(['status' => 'warning', 'message' => 'One or more fields (Serial No, Property No) already exist. Add anyway?']);
        exit;
    }

    $query = "INSERT INTO tbl_inv (type_id, inv_serialno, inv_propno, inv_propname, inv_status, inv_bnm, inv_date_added, end_user, accounted_to, date_acquired, price, `condition`) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("isssisssssds", $type_id, $serialno, $propertyno, $propertyname, $inv_status, $inv_bnm, $formatted_date, $enduser, $accountedto, $date_acquired, $price, $condition);
        
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
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
