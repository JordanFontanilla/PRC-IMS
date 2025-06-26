<?php
require '../../function_connection.php';

$queryReturned = " 
    SELECT 
        tbl_borrow_request.breq_id,
        tbl_borrow_request.breq_token,
        tbl_borrow_request.emp_name,
        tbl_borrow_request.breq_status,
        tbl_borrow_request.breq_date,
        tbl_borrow_request_items.returner_name,
        tbl_borrow_request.breq_decisiondate,
        MAX(tbl_borrow_request_items.returned_date) AS returned_date, -- Get the latest returned_date
        COUNT(tbl_borrow_request_items.br_item_id) AS count_items
    FROM 
        tbl_borrow_request 
    LEFT JOIN 
        tbl_borrow_request_items ON tbl_borrow_request.breq_token = tbl_borrow_request_items.breq_token 
    LEFT JOIN 
        tbl_inv ON tbl_borrow_request_items.inv_id = tbl_inv.inv_id 
    WHERE 
        tbl_borrow_request.breq_status = 5  -- Only fetch 'Returned' requests
    GROUP BY 
        tbl_borrow_request.breq_id, 
        tbl_borrow_request.breq_token, 
        tbl_borrow_request.emp_name, 
        tbl_borrow_request.breq_status, 
        tbl_borrow_request.breq_date;
";

$result = $conn->query($queryReturned);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Map breq_status to human-readable text
        $statusText = ($row['breq_status'] == 5) ? 'Returned' : 'Unknown';

        // Format the dates to Month Day, Year - Time AM/PM
        $formattedBreqDate = date("F j, Y - g:i A", strtotime($row['breq_date']));
        $formattedReturnedDate = date("F j, Y - g:i A", strtotime($row['returned_date']));

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($formattedBreqDate) . "</td>";  // Formatted borrow request date
        echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";  // Employee name
        echo "<td>" . htmlspecialchars($row['count_items']) . "</td>";  // Number of items
        echo "<td>" . htmlspecialchars($formattedReturnedDate) . "</td>";  // Formatted returned date
        echo "<td>" . htmlspecialchars($row['returner_name']) . "</td>";  // Returner name
        
        echo "<td>
                 <button class='btn btn-info btn-sm info-pendingrequest' data-toggle='modal' data-target='#viewPendingItemListModal' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='far fa-eye'> </i> View Items</button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No returned equipment</td></tr>";
}

$conn->close();
?>
