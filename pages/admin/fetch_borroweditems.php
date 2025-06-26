<?php
require '../../function_connection.php';

if (isset($_POST['breq_token'])) {
    $token = $conn->real_escape_string($_POST['breq_token']);

    $query = "
        SELECT 
            inpt_dev.inpt_dev AS type,
            tbl_inv.brnd_model,
            tbl_inv.property_num,
            tbl_inv.prop_name
        FROM 
            tbl_borrow_request_items
        LEFT JOIN 
            tbl_inv ON tbl_borrow_request_items.inv_id = tbl_inv.inv_id
        LEFT JOIN 
            inpt_dev ON tbl_inv.inpt_dev_id = inpt_dev.id
        WHERE 
            tbl_borrow_request_items.breq_token = '$token'
    ";

    $result = $conn->query($query);

    if ($result) {
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode(['status' => 'success', 'items' => $items]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No token provided']);
}
?>
