<?php
require '../../function_connection.php';

if (isset($_POST['breq_token'])) {
    $token = $conn->real_escape_string($_POST['breq_token']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update borrow request status to 'Returned' (5)
        $update_breq_stmt = $conn->prepare("UPDATE tbl_borrow_request SET breq_status = 5, breq_decisiondate = NOW() WHERE breq_token = ?");
        $update_breq_stmt->bind_param("s", $token);
        $update_breq_stmt->execute();
        $update_breq_stmt->close();

        // Get all inventory items associated with this request
        $get_items_stmt = $conn->prepare("SELECT inv_id FROM tbl_borrow_request_items WHERE breq_token = ?");
        $get_items_stmt->bind_param("s", $token);
        $get_items_stmt->execute();
        $result = $get_items_stmt->get_result();
        $inv_ids = [];
        while ($row = $result->fetch_assoc()) {
            $inv_ids[] = $row['inv_id'];
        }
        $get_items_stmt->close();

        if (!empty($inv_ids)) {
            $inv_ids_placeholder = implode(',', array_fill(0, count($inv_ids), '?'));
            $types = str_repeat('i', count($inv_ids));

            // Update non-consumable inventory status to 'Available' (1)
            $update_inv_stmt = $conn->prepare("UPDATE tbl_inv SET inv_status = 1 WHERE inv_id IN ($inv_ids_placeholder)");
            $update_inv_stmt->bind_param($types, ...$inv_ids);
            $update_inv_stmt->execute();
            $update_inv_stmt->close();

            // Update consumable inventory status to 'Available' (1)
            $update_inv_consumable_stmt = $conn->prepare("UPDATE tbl_inv_consumables SET inv_status = 1 WHERE inv_id IN ($inv_ids_placeholder)");
            $update_inv_consumable_stmt->bind_param($types, ...$inv_ids);
            $update_inv_consumable_stmt->execute();
            $update_inv_consumable_stmt->close();
        }
        
        // Update returned_date in tbl_borrow_request_items
        $update_items_stmt = $conn->prepare("UPDATE tbl_borrow_request_items SET returned_date = NOW() WHERE breq_token = ?");
        $update_items_stmt->bind_param("s", $token);
        $update_items_stmt->execute();
        $update_items_stmt->close();


        // Commit transaction
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Return approved successfully.']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No token provided']);
}
?> 