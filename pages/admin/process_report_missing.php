<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['inv_id']) && isset($_POST['item_type'])) {
    $inv_id = $_POST['inv_id'];
    $item_type = $_POST['item_type'];
    $missing_status = 6; // Status for 'Missing'

    $table_name = ($item_type === 'consumable') ? 'tbl_inv_consumables' : 'tbl_inv';

    $stmt = $conn->prepare("UPDATE {$table_name} SET inv_status = ? WHERE inv_id = ?");
    $stmt->bind_param("ii", $missing_status, $inv_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update item status.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?> 