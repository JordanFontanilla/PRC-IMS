<?php
// Include database connection
require '../../function_connection.php';

$query = "SELECT 
                tbl_inv.inv_id, 
                tbl_inv.inv_serialno, 
                tbl_inv.inv_propno, 
                tbl_inv.inv_propname, 
                tbl_inv.inv_bnm, 
                tbl_type.type_name 
          FROM tbl_inv 
          LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id 
          WHERE inv_status = 1"; // Only available items

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Output the rows in table format
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>"; // Display type_name
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td class='text-center align-middle'>
        <div class='d-inline-flex justify-content-center align-items-center'>
            <i class='fa fa-plus fa-sm toggle-icon bg-success text-white rounded-circle p-2' data-id='" . htmlspecialchars($row['inv_id']) . "' data-state='plus'></i>
        </div>
      </td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No available items found</td></tr>";
}

$conn->close();
?>
