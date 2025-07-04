<?php
require '../../function_connection.php';

$queryPendingReturn = " 
    SELECT 
        tbl_borrow_request.breq_id,
        tbl_borrow_request.breq_token,
        tbl_borrow_request.emp_name,
        tbl_borrow_request.breq_date,
        tbl_borrow_request_items.returner_name,
        COUNT(tbl_borrow_request_items.br_item_id) AS count_items
    FROM 
        tbl_borrow_request 
    LEFT JOIN 
        tbl_borrow_request_items ON tbl_borrow_request.breq_token = tbl_borrow_request_items.breq_token
    WHERE 
        tbl_borrow_request.breq_status = 8  -- Only fetch 'Pending Return' requests
    GROUP BY 
        tbl_borrow_request.breq_id, 
        tbl_borrow_request.breq_token, 
        tbl_borrow_request.emp_name, 
        tbl_borrow_request.breq_date,
        tbl_borrow_request_items.returner_name;
";

$result = $conn->query($queryPendingReturn);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars(date("F j, Y - g:i A", strtotime($row['breq_date']))) . "</td>";
        echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['count_items']) . "</td>";
        echo "<td>" . htmlspecialchars($row['returner_name']) . "</td>";
        echo "<td>
         <button class='btn btn-info btn-sm info-pendingrequest' data-toggle='modal' data-target='#viewPendingItemListModal' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='far fa-eye'> </i> View Items</button>
         <button class='btn btn-success btn-sm approve-return' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='fas fa-check'></i> Approve Return</button>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No items pending for return approval.</td></tr>";
}

$conn->close();
?> 