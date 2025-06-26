<?php
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');

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

            // Update `tbl_inv` (Set status to available)
            $update_inv_status = $conn->prepare("
                UPDATE tbl_inv 
                SET inv_status = 1
                WHERE inv_id IN (
                    SELECT inv_id 
                    FROM tbl_borrow_request_items 
                    WHERE breq_token = ?)");
            $update_inv_status->bind_param("s", $breq_token);
            $update_inv_status->execute();
        }
        
        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollback();
        echo 'error';
    }
} else {
    echo 'error';
}

$conn->close();
?>
