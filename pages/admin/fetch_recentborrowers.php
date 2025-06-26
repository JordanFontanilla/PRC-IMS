<?php
include("../../function_connection.php");

header('Content-Type: application/json');

$query = "SELECT br.emp_name, COUNT(bri.inv_id) AS item_count
            FROM tbl_borrow_request br
            LEFT JOIN tbl_borrow_request_items bri ON br.breq_token = bri.breq_token
            WHERE bri.is_approved = 1
            GROUP BY br.emp_name
            ORDER BY COUNT(bri.inv_id) DESC
            LIMIT 5"; // Adjust limit as needed

$result = mysqli_query($conn, $query);

$borrowers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $borrowers[] = [
        "emp_name" => $row['emp_name'],
        "item_count" => $row['item_count']
    ];
}

echo json_encode($borrowers);

mysqli_close($conn);
?>
