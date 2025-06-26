<?php
// process_customapprove.php
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');

if (isset($_POST['requests'])) {
    // Decode the received requests
    $requests = $_POST['requests'];

    // Start a transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Loop through each request and update the relevant tables
        foreach ($requests as $request) {
            $breq_token = $request['breq_token'];
            $inv_id = $request['inv_id'];
            $approved = $request['approved'];
            $borrowed_date = isset($request['borrowed_date']) ? $request['borrowed_date'] : null;

            // If approved, update the status and dates
            if ($approved) {
                // Update the borrow request item approval status
                $update_borrow_request_items = $conn->prepare("
                    UPDATE tbl_borrow_request_items
                    SET is_approved = 1, borrowed_date = ?
                    WHERE breq_token = ? AND inv_id = ?");
                $update_borrow_request_items->bind_param("sss", $borrowed_date, $breq_token, $inv_id);
                $update_borrow_request_items->execute();

                // Update the inventory status to "borrowed"
                $update_inv_status = $conn->prepare("
                    UPDATE tbl_inv 
                    SET inv_status = 4  -- 4 signifies 'borrowed'
                    WHERE inv_id = ?");
                $update_inv_status->bind_param("s", $inv_id);
                $update_inv_status->execute();
            } else {
                // For unchecked items, just mark them as available (you can adjust the logic here as needed)
                $update_inv_status = $conn->prepare("
                    UPDATE tbl_inv 
                    SET inv_status = 1  -- 1 signifies 'available'
                    WHERE inv_id = ?");
                $update_inv_status->bind_param("s", $inv_id);
                $update_inv_status->execute();
            }
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
