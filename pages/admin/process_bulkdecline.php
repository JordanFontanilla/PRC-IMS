<?php
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');
// Check if request_ids are passed
if (isset($_POST['request_ids'])) {
    // Get the array of request IDs
    $request_ids = $_POST['request_ids'];

    // Get the current date (for breq_decisiondate and borrowed_date)
    $current_date = date('Y-m-d H:i:s');

    // Start a transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Loop through each request and update the relevant tables
        foreach ($request_ids as $breq_token) {
            // Update the breq_decisiondate and breq_status in tbl_borrow_request
            $update_borrow_request = $conn->prepare("
                UPDATE tbl_borrow_request 
                SET breq_decisiondate = ?, breq_status = '6'  -- Status set to '6' (declined)
                WHERE breq_token = ?");
            $update_borrow_request->bind_param("ss", $current_date, $breq_token);
            $update_borrow_request->execute();

            // Update the borrowed_date in tbl_borrow_request_items
            $update_borrow_request_items = $conn->prepare("
                UPDATE tbl_borrow_request_items 
                SET borrowed_date = ?
                WHERE breq_token = ?");
            $update_borrow_request_items->bind_param("ss", $current_date, $breq_token);
            $update_borrow_request_items->execute();

            // Update the is_declined column in tbl_borrow_request_items (set to 1)
            $update_is_declined = $conn->prepare("
                UPDATE tbl_borrow_request_items 
                SET is_approved = 2 
                WHERE breq_token = ?");
            $update_is_declined->bind_param("s", $breq_token);
            $update_is_declined->execute();

            // Update the inv_status in tbl_inv (set to 4, assuming 4 is 'borrowed')
            $update_inv_status = $conn->prepare("
                UPDATE tbl_inv 
                SET inv_status = 1
                WHERE inv_id IN (
                    SELECT inv_id 
                    FROM tbl_borrow_request_items 
                    WHERE breq_token = ?
                )");
            $update_inv_status->bind_param("s", $breq_token);
            $update_inv_status->execute();
        }

        // Commit the transaction
        $conn->commit();

        // Respond with success
        echo 'success';
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo 'error';
    }
} else {
    echo 'error';
}

$conn->close();
?>
