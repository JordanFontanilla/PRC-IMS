<?php
include("../../function_connection.php");

header('Content-Type: application/json');

$query = "
    SELECT t.type_name, COUNT(i.inv_id) AS count
    FROM tbl_type t
    LEFT JOIN tbl_inv i ON t.type_id = i.type_id
    GROUP BY t.type_name
    ORDER BY count DESC"; // Order by count (optional)

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        "type_name" => $row['type_name'],
        "count" => $row['count']
    ];
}

echo json_encode($data);
mysqli_close($conn);
?>
