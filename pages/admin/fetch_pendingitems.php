<?php
require '../../function_connection.php';

// Get the breq_token from the AJAX request
$breq_token = $_POST['breq_token'];

// Query to fetch inventory details based on breq_token, including the type_name from tbl_type
$query = "
    SELECT 
        tbl_inv.inv_bnm, 
        tbl_inv.inv_propno, 
        tbl_inv.inv_propname,
        tbl_borrow_request_items.inv_id,
        tbl_inv.type_id,
        tbl_type.type_name
    FROM tbl_borrow_request_items
    LEFT JOIN tbl_inv 
        ON tbl_borrow_request_items.inv_id = tbl_inv.inv_id
    LEFT JOIN tbl_type
        ON tbl_inv.type_id = tbl_type.type_id
    WHERE tbl_borrow_request_items.breq_token = ?";  // Use prepared statement to prevent SQL injection

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $breq_token);  // Bind breq_token parameter
$stmt->execute();
$result = $stmt->get_result();

// Check if results are found
if ($result->num_rows > 0) {
    // Output rows for the table
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>";  // Type Name
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";  // Brand/Model
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";  // Property Number
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";  // Property Name
        // echo "<td>
        // <div class='custom-control custom-checkbox'>
        //     <input type='checkbox' class='custom-control-input info-inv' data-id='" . htmlspecialchars($row['inv_id']) . "' id='checkbox-" . htmlspecialchars($row['inv_id']) . "' checked>
        //     <label class='custom-control-label' for='checkbox-" . htmlspecialchars($row['inv_id']) . "'></label>
        // </div>
        
        // </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No inventory found for this request.</td></tr>";
}

$stmt->close();
$conn->close();
?>
