<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['request_ids']) && isset($_POST['returner_name'])) {
    $request_ids = $_POST['request_ids'];
    $returner_name = $conn->real_escape_string($_POST['returner_name']);

    if (empty($request_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'No requests selected.']);
        exit;
    }

    $conn->begin_transaction();

    try {
        $all_successful = true;

        foreach ($request_ids as $breq_token) {
            $breq_token = $conn->real_escape_string($breq_token);

            // Update borrow request status to 'Pending Return' (8)
            $update_breq_sql = "UPDATE tbl_borrow_request SET breq_status = 8 WHERE breq_token = '$breq_token'";
            if (!$conn->query($update_breq_sql)) {
                $all_successful = false;
                break;
            }

            // Update returner's name in borrow request items
            $update_items_sql = "UPDATE tbl_borrow_request_items SET returner_name = '$returner_name' WHERE breq_token = '$breq_token'";
            if (!$conn->query($update_items_sql)) {
                $all_successful = false;
                break;
            }
        }

        if ($all_successful) {
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Return request submitted successfully.']);
        } else {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Failed to update all records.']);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'A database error occurred.']);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?> 