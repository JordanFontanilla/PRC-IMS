<?php
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');

// Check if items data is passed
if (isset($_POST['items'])) {
    // Get the array of items (inv_id and approval status)
    $items = $_POST['items'];

    // Get the current date (for borrowed_date)
    $current_date = date('Y-m-d H:i:s');

    // Start a transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Loop through each item and update the relevant tables
        foreach ($items as $item) {
            $inv_id = $item['inv_id']; // Inventory ID
            $is_approved = $item['is_approved']; // 1 = Approved, 2 = Declined

            // Update the is_approved column in tbl_borrow_request_items
            $update_is_approved = $conn->prepare("
                UPDATE tbl_borrow_request_items 
                SET is_approved = ?
                WHERE inv_id = ?");
            $update_is_approved->bind_param("ii", $is_approved, $inv_id);
            $update_is_approved->execute();

            // Update the inv_status in tbl_inv (set to 4 if approved, 5 if declined)
            $new_inv_status = ($is_approved == 1) ? 4 : 5; // 4 = Borrowed, 5 = Declined
            $update_inv_status = $conn->prepare("
                UPDATE tbl_inv 
                SET inv_status = ?
                WHERE inv_id = ?");
            $update_inv_status->bind_param("ii", $new_inv_status, $inv_id);
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
