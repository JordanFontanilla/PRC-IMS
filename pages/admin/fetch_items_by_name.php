<?php
require '../../function_connection.php';

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $searchTerm = "%" . $name . "%";

    $query = "SELECT 
                i.inv_id, 
                i.inv_serialno, 
                i.inv_propno, 
                i.inv_propname, 
                i.inv_bnm, 
                t.type_name 
              FROM tbl_inv i
              LEFT JOIN tbl_type t ON i.type_id = t.type_id 
              WHERE (t.type_name LIKE ? OR i.inv_bnm LIKE ?) AND i.inv_status = 1"; // Only available items

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode($items);

    $stmt->close();
} else {
    echo json_encode([]);
}

$conn->close();
?> 