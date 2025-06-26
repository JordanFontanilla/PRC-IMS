<?php
// Include your database connection here
require '../../function_connection.php';

if (isset($_GET['inv_status'])) {
    $inv_status = $_GET['inv_status'];
    
    $sql = "SELECT * FROM tbl_inv LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id WHERE inv_status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $inv_status);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    // Return items as JSON
    echo json_encode($items);
}
?>
