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
                tbl_type.type_name 
            FROM tbl_inv LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id WHERE tbl_inv.inv_status = '1'";

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
                $status = 'In Use';
                break;
            case 4:
                $status = 'In Maintenance';
                break;
            case 5:
                $status = 'Missing';
                break;
            case 6:
                $status = 'Returned';
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
        
        echo "<td>
        <div class='custom-control custom-checkbox'>
            <input type='checkbox' class='custom-control-input info-inv' data-id='" . htmlspecialchars($row['inv_id']) . "' id='checkbox-" . htmlspecialchars($row['inv_id']) . "'>
            <label class='custom-control-label' for='checkbox-" . htmlspecialchars($row['inv_id']) . "'></label>
        </div>
        
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No Equipment found</td></tr>";
}

$conn->close();
?>