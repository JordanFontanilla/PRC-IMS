<?php
require '../../function_connection.php';

$queryReturned = "
    SELECT
        br.breq_id,
        br.breq_remarks,
        br.breq_token,
        br.emp_name,
        br.breq_status,
        br.breq_date,
        br.breq_decisiondate,
        COUNT(bri.br_item_id) AS count_items,
        GROUP_CONCAT(
            CASE
                WHEN ti.inv_id IS NOT NULL THEN ti.inv_bnm
                WHEN tic.inv_id IS NOT NULL THEN tic.item_description
                ELSE 'N/A'
            END
            SEPARATOR ', '
        ) AS item_names,
        GROUP_CONCAT(
            CASE
                WHEN ti.inv_id IS NOT NULL THEN ti.inv_propname
                WHEN tic.inv_id IS NOT NULL THEN 'Consumable'
                ELSE 'N/A'
            END
            SEPARATOR ', '
        ) AS item_descriptions
    FROM
        tbl_borrow_request br
    LEFT JOIN
        tbl_borrow_request_items bri ON br.breq_token = bri.breq_token
    LEFT JOIN
        tbl_inv ti ON bri.inv_id = ti.inv_id
    LEFT JOIN
        tbl_inv_consumables tic ON bri.inv_id = tic.inv_id
    WHERE
        br.breq_status = 4  -- Only fetch 'Borrowed' requests
    GROUP BY
        br.breq_id,
        br.breq_remarks,
        br.breq_token,
        br.emp_name,
        br.breq_status,
        br.breq_date,
        br.breq_decisiondate
    ORDER BY
        br.breq_decisiondate DESC;
";

$result = $conn->query($queryReturned);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['breq_decisiondate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['emp_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['count_items']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_names']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_descriptions']) . "</td>";
        echo "<td>
         <button class='btn btn-info btn-sm info-pendingrequest' data-toggle='modal' data-target='#viewPendingItemListModal' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='far fa-eye'> </i> View Items</button>
         <button class='btn btn-primary btn-sm info-bulkreturn' data-toggle='modal' data-target='#' data-id='" . htmlspecialchars($row['breq_token']) . "'><i class='fas fa-check'></i> Return</button>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No equipment currently borrowed</td></tr>";
}

$conn->close();
?>