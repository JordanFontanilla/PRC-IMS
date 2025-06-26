<?php
require '../../function_connection.php';

$query = "SELECT * FROM tbl_type ORDER BY type_id DESC";
$result = $conn->query($query);

$data = [];

function getOriginBadge($origin) {
    switch ($origin) {
        case 'Consumable':
            return "<span class='badge bg-success' style='font-size: 1rem; color: white;'>Consumable</span>";
        case 'Non-Consumable':
            return "<span class='badge bg-warning' style='font-size: 1rem; color: white;'>Non-Consumable</span>";
        default:
            return "<span class='badge bg-secondary' style='font-size: 1rem; color: white;'>Unknown</span>";
    }
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['type_id'],
            htmlspecialchars($row['type_name']),
            getOriginBadge($row['type_origin']),
            "<button class='btn btn-primary btn-sm edit-type' data-id='{$row['type_id']}'>
                <i class='fas fa-edit'></i> Edit
            </button>"
        ];
    }
}

$conn->close();

echo json_encode(['data' => $data]);
