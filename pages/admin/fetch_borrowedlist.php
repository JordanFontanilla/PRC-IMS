<?php
require '../../function_connection.php';

$queryReturned = " 
    SELECT 
        tbl_borrow_request.breq_id,
        tbl_borrow_request.breq_remarks,
        tbl_borrow_request.breq_token,
        tbl_borrow_request.emp_name,
        tbl_borrow_request.breq_status,
        tbl_borrow_request.breq_date,
        tbl_borrow_request.breq_decisiondate,
        COUNT(tbl_borrow_request_items.br_item_id) AS count_items
    FROM 
        tbl_borrow_request 
    LEFT JOIN 
        tbl_borrow_request_items ON tbl_borrow_request.breq_token = tbl_borrow_request_items.breq_token 
    LEFT JOIN 
        tbl_inv ON tbl_borrow_request_items.inv_id = tbl_inv.inv_id 
    WHERE 
        tbl_borrow_request.breq_status = 4  -- Only fetch 'Returned' requests
    GROUP BY 
        tbl_borrow_request.breq_id, 
        tbl_borrow_request.breq_remarks,
        tbl_borrow_request.breq_token, 
        tbl_borrow_request.emp_name, 
        tbl_borrow_request.breq_status, 
        tbl_borrow_request.breq_date, 
        tbl_borrow_request.breq_decisiondate;
";



$result = $conn->query($queryReturned);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Map breq_status to human-readable text
        switch ($row['breq_status']) {
            case 1:
                $statusText = 'Available';
                break;
            case 2:
                $statusText = 'Unavailable';
                break;
            case 3:
                $statusText = 'Pending for Approval';
                break;
            case 4:
                $statusText = 'Borrowed';
                break;
            case 5:
                $statusText = 'Returned';
                break;
            default:
                $statusText = 'Unknown'; // Fallback for unexpected values
                break;
        }

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['breq_decisiondate']) . "</td>";  // Borrow request date
         // Borrow request date
        echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";  // Employee name
        echo "<td>" . htmlspecialchars($row['count_items']) . "</td>";  // Human-readable status
        echo "<td>
         <button class='btn btn-info btn-sm info-pendingrequest' data-toggle='modal' data-target='#viewPendingItemListModal' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='far fa-eye'> </i> View Items</button>
         <button class='btn btn-primary btn-sm info-bulkreturn' data-toggle='modal' data-target='#' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='fas fa-check'></i> Return</button>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No equipment currently borrowed</td></tr>";
}

$conn->close();
?>