<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';  // Include database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log all POST data
    error_log("POST Data: " . print_r($_POST, true));
    
    // Get and sanitize form data
    $type_id = isset($_POST['cinv_type']) ? intval($_POST['cinv_type']) : 0;
    $brand = trim($_POST['cbrand'] ?? '');
    $model = trim($_POST['cmodel'] ?? '');
    $serialno = trim($_POST['cserialno1'] ?? $_POST['cserialno'] ?? '');
    $propertyno = trim($_POST['cpropertyno'] ?? '');
    $propertyname = trim($_POST['cpropertyname'] ?? '');
    $quantity = intval($_POST['cquantity'] ?? 1);
    $price = floatval($_POST['cprice'] ?? 0.00);
    $date_acquired = trim($_POST['cdate_acquired'] ?? '');
    $status = intval($_POST['cstatus'] ?? 1); // Convert to integer
    $end_user = trim($_POST['cend_user'] ?? '');
    $accounted_to = trim($_POST['caccounted_to'] ?? '');

    // Create brand/model combination
    $inv_bnm = trim($brand . ' ' . $model);
    if (empty($inv_bnm)) {
        $inv_bnm = 'N/A';
    }
    
    // Format current date for inv_date_added
    $formatted_date = date("Y-m-d H:i:s"); // Use standard MySQL datetime format
    
    // Format date_acquired if provided
    if (!empty($date_acquired)) {
        // Convert to proper MySQL date format if needed
        $date_acquired_formatted = date("Y-m-d", strtotime($date_acquired));
    } else {
        $date_acquired_formatted = null;
    }

    // Debug: Log processed data
    error_log("Processed Data - Type: $type_id, Brand: $brand, Model: $model, Serial: $serialno, PropNo: $propertyno, PropName: $propertyname, Quantity: $quantity, Status: $status");

    // Validate required fields
    if ($type_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Please select a valid equipment type.']);
        exit;
    }

    if (empty($propertyname)) {
        echo json_encode(['status' => 'error', 'message' => 'Property name is required.']);
        exit;
    }

    if ($quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Quantity must be greater than 0.']);
        exit;
    }

    if (empty($status) || $status <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Please select a valid status.']);
        exit;
    }

    // Check for duplicates if we have identifying information
    $duplicates = [];
    $duplicateFound = false;
    
    if (!empty($serialno) || !empty($propertyno)) {
        $checkConditions = [];
        $checkParams = [];
        $checkTypes = "";
        
        if (!empty($serialno)) {
            $checkConditions[] = "inv_serialno = ?";
            $checkParams[] = $serialno;
            $checkTypes .= "s";
        }
        
        if (!empty($propertyno)) {
            $checkConditions[] = "inv_propno = ?";
            $checkParams[] = $propertyno;
            $checkTypes .= "s";
        }
        
        if (!empty($checkConditions)) {
            $checkQuery = "SELECT COUNT(*) FROM tbl_inv WHERE " . implode(" OR ", $checkConditions);
            $stmtCheck = $conn->prepare($checkQuery);
            
            if (!$stmtCheck) {
                error_log("Prepare failed: " . $conn->error);
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
                exit;
            }
            
            $stmtCheck->bind_param($checkTypes, ...$checkParams);
            $stmtCheck->execute();
            $stmtCheck->bind_result($count);
            $stmtCheck->fetch();
            $stmtCheck->close();

            if ($count > 0) {
                $duplicateFound = true;
                if (!empty($serialno)) $duplicates[] = "Serial Number";
                if (!empty($propertyno)) $duplicates[] = "Property Number";
            }
        }
    }

    // Handle duplicates
    if ($duplicateFound && !isset($_POST['force_insert'])) {
        echo json_encode([
            'status' => 'confirm_duplicate', 
            'message' => 'Some fields already exist in the database.',
            'duplicates' => $duplicates
        ]);
        exit;
    }

    // Prepare insert query with ALL necessary columns
    $query = "INSERT INTO tbl_inv (
        type_id, 
        inv_serialno, 
        inv_propno, 
        inv_propname, 
        inv_status, 
        inv_bnm, 
        inv_date_added, 
        inv_quantity, 
        price, 
        date_acquired, 
        end_user, 
        accounted_to,
        is_consumable
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        // Mark as consumable (1 = true)
        $is_consumable = 1;
        
        $stmt->bind_param(
            "issisissdssi", 
            $type_id,           // i - integer
            $serialno,          // s - string
            $propertyno,        // s - string  
            $propertyname,      // s - string
            $status,            // i - integer
            $inv_bnm,           // s - string
            $formatted_date,    // s - string
            $quantity,          // i - integer
            $price,             // d - double
            $date_acquired_formatted, // s - string (can be null)
            $end_user,          // s - string
            $accounted_to,      // s - string
            $is_consumable      // i - integer
        );
        
        if ($stmt->execute()) {
            $insert_id = $conn->insert_id;
            error_log("Equipment added successfully. Insert ID: " . $insert_id);
            
            // Log the successful insertion with all details
            error_log("Added consumable equipment: ID=$insert_id, Type=$type_id, Name=$propertyname, Quantity=$quantity");
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Consumable equipment added successfully.',
                'insert_id' => $insert_id
            ]);
        } else {
            error_log("Execute failed: " . $stmt->error);
            error_log("Query was: " . $query);
            echo json_encode(['status' => 'error', 'message' => 'Error in adding equipment: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        error_log("Prepare failed: " . $conn->error);
        error_log("Query was: " . $query);
        echo json_encode(['status' => 'error', 'message' => 'Error preparing query: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>