<?php
require '../../function_connection.php';

if (isset($_POST['inv_id']) && isset($_POST['item_type'])) {
    $inv_id = $_POST['inv_id'];
    $item_type = $_POST['item_type'];

    $table_name = ($item_type === 'consumable') ? 'tbl_inv_consumables' : 'tbl_inv';
    
    // For consumables, we need to fetch inv_quantity, otherwise it can be null
    $quantity_select = ($item_type === 'consumable') ? 'i.inv_quantity,' : '';
    $condition_select = ($item_type === 'non-consumable') ? 'i.condition,' : "NULL as `condition`,";
    $enduser_select = ($item_type === 'non-consumable') ? 'i.end_user, i.accounted_to,' : "'' as end_user, '' as accounted_to,";

    $query = "SELECT i.*, t.type_name, {$quantity_select} {$condition_select} {$enduser_select} i.inv_status, i.date_acquired, i.price
              FROM {$table_name} as i
              LEFT JOIN tbl_type as t ON i.type_id = t.type_id
              WHERE i.inv_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $inv_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Map status codes to readable text
            $statusText = [
                1 => 'Available',
                2 => 'Unavailable',
                3 => 'Pending For Approval',
                4 => 'Borrowed',
                5 => 'Returned',
                6 => 'Missing',
                7 => 'In Service',
            ];
            $row['status'] = $statusText[$row['inv_status']] ?? 'Unknown';
            
            // Ensure no NULL values which could break QR generation
            $row['inv_serialno'] = $row['inv_serialno'] ?? '';
            $row['inv_propno'] = $row['inv_propno'] ?? '';
            $row['inv_propname'] = $row['inv_propname'] ?? '';
            $row['inv_bnm'] = $row['inv_bnm'] ?? '';
            $row['type_name'] = $row['type_name'] ?? '';
            $row['date_acquired'] = $row['date_acquired'] ?? '';
            $row['price'] = $row['price'] ?? '0.00';
            $row['condition'] = $row['condition'] ?? '';
            $row['end_user'] = $row['end_user'] ?? '';
            $row['accounted_to'] = $row['accounted_to'] ?? '';
            
            echo json_encode(["success" => true, "data" => $row]);
        } else {
            echo json_encode(["success" => false, "message" => "No record found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Query failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
