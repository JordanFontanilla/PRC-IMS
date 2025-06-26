<?php
require '../../function_connection.php';

$queryinv = "SELECT 
                tbl_inv.inv_id, 
                tbl_inv.type_id, 
                tbl_inv.inv_serialno, 
                tbl_inv.inv_propno,
                tbl_inv.inv_propname,
                tbl_inv.inv_status, 
                tbl_inv.inv_bnm, 
                tbl_type.type_name,
                tbl_inv.inv_date_added
                
             FROM tbl_inv
             LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id WHERE tbl_inv.inv_status = 6
             ORDER BY tbl_inv.inv_date_added ASC"; // Ordering by inv_date_added for FIFO (oldest first)

$result = $conn->query($queryinv);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Map the inv_status to its corresponding string
        switch ($row['inv_status']) {
            case 1:
                $status = 'Available';
                break;
            case 2:
                $status = 'Unavailable';
                break;
            case 3:
                $status = 'Pending For Approval';
                break;
            case 4:
                $status = 'Borrowed';
                break;
            case 5:
                $status = 'Returned';
                break;
            case 6:
                $status = 'Missing';
                break;
            default:
                $status = 'Unknown'; // Fallback for unexpected values
        }

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>"; // Changed to inv_type from tbl_type
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td>" . htmlspecialchars($status) . "</td>"; // Display the date equipment was added
        echo "<td>
            <button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-eye'></i></button>
            <button class='btn btn-success btn-sm' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-check'></i></button>
            <button class='btn btn-danger btn-sm' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-minus'></i></button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No Equipment found</td></tr>";
}

$conn->close();
?>
