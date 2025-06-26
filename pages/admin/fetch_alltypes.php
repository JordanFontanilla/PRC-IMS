<?php
require '../../function_connection.php';

$sql = "SELECT type_id, type_name FROM tbl_type ORDER BY type_name ASC";
$result = $conn->query($sql);

$types = [];
while ($row = $result->fetch_assoc()) {
    $types[] = $row;
}

echo json_encode($types);
?>