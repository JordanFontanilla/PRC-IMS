<?php
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request data.'];

if (isset($_POST['request_ids']) && isset($_POST['returner_name'])) {
    $request_ids = $_POST['request_ids'];
    $returner_name = $_POST['returner_name'];
    $current_date = date('Y-m-d H:i:s');

    // Start a transaction
    $conn->begin_transaction();

    try {
        foreach ($request_ids as $breq_token) {
            // Update `tbl_borrow_request` (Mark as returned)
            $update_borrow_request = $conn->prepare("
                UPDATE tbl_borrow_request 
                SET breq_decisiondate = ?, breq_status = '5'
                WHERE breq_token = ?");
            $update_borrow_request->bind_param("ss", $current_date, $breq_token);
            $update_borrow_request->execute();

            // Update `tbl_borrow_request_items` (Set returned_date and returner_name)
            $update_borrow_request_items = $conn->prepare("
                UPDATE tbl_borrow_request_items 
                SET returned_date = ?, returner_name = ?
                WHERE breq_token = ?");
            $update_borrow_request_items->bind_param("sss", $current_date, $returner_name, $breq_token);
            $update_borrow_request_items->execute();

            // Fetch item origin (consumable/non_consumable) for each inv_id
            $fetch_item_origin = $conn->prepare("
                SELECT 
                    bri.inv_id, 
                    CASE
                        WHEN ti.inv_id IS NOT NULL THEN 'non_consumable'
                        WHEN tic.inv_id IS NOT NULL THEN 'consumable'
                        ELSE 'unknown'
                    END as origin
                FROM 
                    tbl_borrow_request_items bri
                LEFT JOIN 
                    tbl_inv ti ON bri.inv_id = ti.inv_id
                LEFT JOIN 
                    tbl_inv_consumables tic ON bri.inv_id = tic.inv_id
                WHERE bri.breq_token = ?");
            $fetch_item_origin->bind_param("s", $breq_token);
            $fetch_item_origin->execute();
            $item_origins_result = $fetch_item_origin->get_result();

            while ($item_row = $item_origins_result->fetch_assoc()) {
                $inv_id = $item_row['inv_id'];
                $origin = $item_row['origin'];

                if ($origin === 'non_consumable') {
                    // Update `tbl_inv` (Set status to available)
                    $update_inv_status = $conn->prepare("
                        UPDATE tbl_inv 
                        SET inv_status = 1
                        WHERE inv_id = ?");
                    $update_inv_status->bind_param("i", $inv_id);
                    $update_inv_status->execute();
                } elseif ($origin === 'consumable') {
                    // For consumable items, update receipt and issuance (assuming return means adding to receipt)
                    // You might need to adjust this logic based on how you track consumable returns
                    $update_consumable_status = $conn->prepare("
                        UPDATE tbl_inv_consumables 
                        SET receipt = receipt + 1, issuance = issuance - 1 -- Example: add 1 to receipt, subtract 1 from issuance
                        WHERE inv_id = ?");
                    $update_consumable_status->bind_param("i", $inv_id);
                    $update_consumable_status->execute();
                }
            }
            $fetch_item_origin->close();
        }
        
        $conn->commit();
        $response = ['success' => true, 'message' => 'The selected request(s) have been returned.'];
    } catch (Exception $e) {
        $conn->rollback();
        $response = ['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request data. Missing request_ids or returner_name.'];
}

$conn->close();
echo json_encode($response);
exit;
?>