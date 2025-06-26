<?php
require '../../function_connection.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Build the query
$query = "SELECT COUNT(*) as total FROM tbl_inv WHERE 1=1";

if (!empty($type)) {
    $query .= " AND type_id = '" . $conn->real_escape_string($type) . "'";
}
if (!empty($status)) {
    $query .= " AND inv_status = '" . $conn->real_escape_string($status) . "'";
}

$result = $conn->query($query);
$row = $result->fetch_assoc();

echo json_encode(['exists' => $row['total'] > 0]);

$conn->close();
?>
    