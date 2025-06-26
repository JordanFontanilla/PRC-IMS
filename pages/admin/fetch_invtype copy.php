<?php
require '../../function_connection.php';

$querytypes = "SELECT * FROM tbl_type ORDER BY type_id DESC;";
$result = $conn->query($querytypes);

function getOriginBadge($origin) {
    switch ($origin) {
        case 'Consumable':
            return "<span class='badge bg-success' style='font-size: 1rem;'>Consumable</span>";
        case 'Non-Consumable':
            return "<span class='badge bg-warning' style='font-size: 1rem;'>Non-Consumable</span>";
        default:
            return "<span class='badge bg-secondary' style='font-size: 1rem;'>Unknown</span>";
    }
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['type_id'] . "</td>";
        echo "<td>" . $row['type_name'] . "</td>";
        echo "<td class='text-center'>" . getOriginBadge($row['type_origin']) . "</td>";
        echo "<td>
            <button class='btn btn-primary btn-sm edit-type' data-id='" . $row['type_id'] . "'>
                <i class='fas fa-edit'></i> Edit
            </button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No inventory types found</td></tr>";
}

$conn->close();
?>
