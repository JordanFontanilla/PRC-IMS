<?php
// Include database connection
require '../../function_connection.php';

if (isset($_GET['serial'])) {
    $serial = $_GET['serial'];

    // Query to check if the serial number exists and is available (inv_status = 1)
    $query = "SELECT * FROM tbl_inv 
              LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id 
              WHERE inv_serialno = '$serial' AND inv_status = 1"; // Only available items

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Output matching rows
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows); // Return data as JSON
    } else {
        echo json_encode([]); // No matching or available item
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>
