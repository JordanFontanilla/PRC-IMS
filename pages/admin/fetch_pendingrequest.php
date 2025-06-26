<?php
require '../../function_connection.php';

$querypending = "
    SELECT 
        tbl_borrow_request.breq_id,
        tbl_borrow_request.breq_token,
        tbl_borrow_request.emp_name,
        tbl_borrow_request.breq_status,
        tbl_borrow_request.breq_date,
        tbl_borrow_request.breq_decisiondate,
        COUNT(tbl_borrow_request_items.br_item_id) AS count_items
    FROM tbl_borrow_request
        LEFT JOIN tbl_borrow_request_items 
            ON tbl_borrow_request.breq_token = tbl_borrow_request_items.breq_token
    WHERE tbl_borrow_request.breq_status = 3  -- Only fetch requests with status 3
    GROUP BY tbl_borrow_request.breq_token;
";

$result = $conn->query($querypending);

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
                $statusText = 'Waiting for Approval';
                break;
            case 4:
                $statusText = 'Pending for Signature';
                break;
            case 5:
                $statusText = 'Borrowed';
                break;
            case 6:
                $statusText = 'Returned';
                break;
            default:
                $statusText = 'Unknown'; // Fallback for unexpected values
                break;
        }

        // Format the date to Month Day, Year - Time AM/PM
        $formattedDate = date("F j, Y - g:i A", strtotime($row['breq_date']));

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($formattedDate) . "</td>";  // Formatted Borrow request date
        echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";  // Employee name
        echo "<td>" . htmlspecialchars($row['count_items']) . "</td>";  // Count of related items
        echo "<td>" . htmlspecialchars($statusText) . "</td>";  // Human-readable status

        echo "<td>
         <button class='btn btn-info btn-sm info-pendingrequest' data-toggle='modal' data-target='#viewPendingItemListModal' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='far fa-eye'> </i> View Items</button>
        <button class='btn btn-primary btn-sm info-bulkapprove' data-toggle='modal' data-target='#' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='fas fa-check'></i> Approve</button>
        <button class='btn btn-danger btn-sm info-bulkdecline' data-toggle='modal' data-target='#' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='fas fa-minus'></i> Decline</button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No Pending Requests found</td></tr>";
}

$conn->close();
?>
