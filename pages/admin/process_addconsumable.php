<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';  // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_id = intval($_POST['cinv_type'] ?? 0);
    $brand = trim($_POST['cbrand'] ?? '');
    $model = trim($_POST['cmodel'] ?? '');
    $serialno = trim($_POST['cserialno1'] ?? '');
    $propertyno = trim($_POST['cpropertyno'] ?? '');
    $propertyname = trim($_POST['cpropertyname'] ?? '');
    $quantity = intval($_POST['cquantity'] ?? 1);
    $date_acquired = !empty($_POST['cdate_acquired']) ? $_POST['cdate_acquired'] : null;
    $price = !empty($_POST['cprice']) ? floatval($_POST['cprice']) : 0.00;
    $force_insert = isset($_POST['force_insert']);

    $inv_bnm = $brand . ' ' . $model;
    $inv_status = 1; // Available
    $formatted_date = date("F d, Y h:i A");

    if (empty($type_id) || empty($brand) || empty($model) || empty($propertyname)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Make serial number NULL if it is empty
    $serialno_db = !empty($serialno) ? $serialno : null;
    $propertyno_db = !empty($propertyno) ? $propertyno : null;

    $checks = [];
    $params = [];
    $types = "";

    if ($serialno_db) {
        $checks[] = "inv_serialno = ?";
        $params[] = $serialno_db;
        $types .= "s";
    }
    if ($propertyno_db) {
        $checks[] = "inv_propno = ?";
        $params[] = $propertyno_db;
        $types .= "s";
    }

    $duplicates = [];
    $exact_duplicate = false;
    
    if (!empty($checks)) {
        $checkQuery = "SELECT inv_serialno, inv_propno, inv_propname FROM tbl_inv_consumables WHERE " . implode(" OR ", $checks);
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $existing_records = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($existing_records as $record) {
                if ($record['inv_serialno'] === $serialno_db && $record['inv_propno'] === $propertyno_db && $record['inv_propname'] === $propertyname) {
                    $exact_duplicate = true;
                    break;
                }
                if ($serialno_db && $record['inv_serialno'] === $serialno_db) $duplicates[] = 'Serial Number';
                if ($propertyno_db && $record['inv_propno'] === $propertyno_db) $duplicates[] = 'Property Number';
            }
        }
    }

    if ($exact_duplicate) {
        echo json_encode(['status' => 'error', 'message' => 'An exact duplicate of this equipment already exists.']);
        exit;
    }
    
    if (!empty($duplicates) && !$force_insert) {
        echo json_encode(['status' => 'confirm_duplicate', 'duplicates' => array_unique($duplicates)]);
        exit;
    }

    $insertQuery = "INSERT INTO tbl_inv_consumables (type_id, inv_bnm, inv_serialno, inv_propno, inv_propname, inv_status, inv_date_added, inv_quantity, date_acquired, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isssisissd", $type_id, $inv_bnm, $serialno_db, $propertyno_db, $propertyname, $inv_status, $formatted_date, $quantity, $date_acquired, $price);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Equipment added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add equipment.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>