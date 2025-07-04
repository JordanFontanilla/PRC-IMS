<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['inv_id']) && isset($_POST['item_type'])) {
    $inv_id = $_POST['inv_id'];
    $item_type = $_POST['item_type'];

    $table_name = ($item_type === 'consumable') ? 'tbl_inv_consumables' : 'tbl_inv';

    $stmt = $conn->prepare("DELETE FROM {$table_name} WHERE inv_id = ?");
    $stmt->bind_param("i", $inv_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Item not found or already deleted.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete item. It might be referenced in borrow requests.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?> 