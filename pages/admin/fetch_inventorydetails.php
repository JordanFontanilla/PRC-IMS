<?php
require '../../function_connection.php';

if (isset($_POST['inv_id'])) {
    $inv_id = $_POST['inv_id'];
    $item_origin = $_POST['item_origin'] ?? null; // Get item_origin
    $item_data = null;

    // Initialize all possible fields to null or default values
    $default_fields = [
        'inv_id' => null,
        'type_id' => null,
        'type_name' => null,
        'inv_serialno' => null,
        'inv_propno' => null,
        'inv_propname' => null,
        'inv_price' => null,
        'inv_status' => null, // Will be mapped later
        'inv_bnm' => null,
        'inv_date_added' => null,
        'date_acquired' => null,
        'price' => null,
        'condition' => null,
        'inv_quantity' => null,
        'end_user' => null,
        'accounted_to' => null,
        'stock_number' => null,
        'acceptance_date' => null,
        'ris_no' => null,
        'item_description' => null,
        'unit' => null,
        'receipt' => null,
        'issuance' => null,
        'end_user_issuance' => null,
        'item_origin' => null,
        'status' => null // Mapped from inv_status
    ];

    if ($item_origin === 'non_consumable') {
        $query_non_consumable = "SELECT i.*, t.type_name, 'non_consumable' as item_origin FROM tbl_inv i LEFT JOIN tbl_type t ON i.type_id = t.type_id WHERE i.inv_id = ?";
        if ($stmt = $conn->prepare($query_non_consumable)) {
            $stmt->bind_param("i", $inv_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $item_data = array_merge($default_fields, $result->fetch_assoc());
            }
            $stmt->close();
        }
    } elseif ($item_origin === 'consumable') {
        $query_consumable = "SELECT c.*, 'consumable' as item_origin FROM tbl_inv_consumables c WHERE c.inv_id = ?";
        if ($stmt = $conn->prepare($query_consumable)) {
            $stmt->bind_param("i", $inv_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $item_data = array_merge($default_fields, $result->fetch_assoc());
                // Consumables don't have inv_status in their table, set a default for mapping
                $item_data['inv_status'] = 0; // Placeholder for status mapping
            }
            $stmt->close();
        }
    } else {
        // Fallback if origin is not specified or invalid (current behavior)
        // Try non-consumable first
        $query_non_consumable = "SELECT i.*, t.type_name, 'non_consumable' as item_origin FROM tbl_inv i LEFT JOIN tbl_type t ON i.type_id = t.type_id WHERE i.inv_id = ?";
        if ($stmt = $conn->prepare($query_non_consumable)) {
            $stmt->bind_param("i", $inv_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $item_data = array_merge($default_fields, $result->fetch_assoc());
            }
            $stmt->close();
        }

        // If not found in tbl_inv, try consumable
        if ($item_data === null) {
                $query_consumable = "SELECT c.*, t.type_name, 'consumable' as item_origin FROM tbl_inv_consumables c LEFT JOIN tbl_type t ON c.type_id = t.type_id WHERE c.inv_id = ?";
            if ($stmt = $conn->prepare($query_consumable)) {
                $stmt->bind_param("i", $inv_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $item_data = array_merge($default_fields, $result->fetch_assoc());
                    $item_data['inv_status'] = 0; // Placeholder for status mapping
                }
                $stmt->close();
            }
        }
    }

    if ($item_data !== null) {
        // Map status codes to readable text
        $statusText = [
            0 => 'N/A (Consumable)', // Custom status for consumables
            1 => 'Available',
            2 => 'Unavailable',
            3 => 'Pending For Approval',
            4 => 'Borrowed',
            5 => 'Returned',
            6 => 'Missing',
            7 => 'In Service',
        ];
        $item_data['status'] = $statusText[$item_data['inv_status']] ?? 'Unknown';
        
        echo json_encode(["success" => true, "data" => $item_data]);
    } else {
        echo json_encode(["success" => false, "message" => "No record found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
