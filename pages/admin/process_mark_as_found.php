<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inv_id']) && isset($_POST['item_type'])) {
    $inv_id = intval($_POST['inv_id']);
    $item_type = $_POST['item_type'];
    $table_name = ($item_type === 'consumable') ? 'tbl_inv_consumables' : 'tbl_inv';
    
    $update_query = "UPDATE {$table_name} SET inv_status = 1 WHERE inv_id = ?";
    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("i", $inv_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Status updated to Available."]);
        } else {
            echo json_encode(["success" => false, "message" => "Execution error."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Preparation error."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?> 