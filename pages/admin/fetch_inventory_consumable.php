<?php
require '../../function_connection.php';

$queryinv = "SELECT 
                tbl_inv_consumables.inv_id, 
                tbl_inv_consumables.type_id, 
                tbl_inv_consumables.inv_serialno, 
                tbl_inv_consumables.inv_propno,
                tbl_inv_consumables.inv_propname,
                tbl_inv_consumables.inv_status, 
                tbl_inv_consumables.inv_bnm, 
                tbl_type.type_name,
                tbl_type.type_origin,
                tbl_inv_consumables.inv_date_added,
                tbl_inv_consumables.inv_quantity,
                tbl_inv_consumables.date_acquired,
                tbl_inv_consumables.price
             FROM tbl_inv_consumables 
             LEFT JOIN tbl_type ON tbl_inv_consumables.type_id = tbl_type.type_id 
             ORDER BY tbl_inv_consumables.inv_date_added DESC";


$result = $conn->query($queryinv);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Map the inv_status to its corresponding string and badge color
        switch ($row['inv_status']) {
            case 1:
                $status = 'Available';
                $badgeColor = 'success'; // Green
                break;
            case 2:
                $status = 'Unavailable';
                $badgeColor = 'secondary'; // Gray
                break;
            case 3:
                $status = 'Pending For Approval';
                $badgeColor = 'warning'; // Yellow
                break;
            case 4:
                $status = 'Borrowed';
                $badgeColor = 'primary'; // Blue
                break;
            case 5:
                $status = 'Returned';
                $badgeColor = 'info'; // Light Blue
                break;
            case 6:
                $status = 'Missing';
                $badgeColor = 'danger'; // Red
                break;
            case 7:
                $status = 'In Use';
                $badgeColor = 'secondary'; // Red
                break;
            default:
                $status = 'Unknown';
                $badgeColor = 'dark'; // Dark gray for unexpected values
        }

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_acquired']) . "</td>";
        echo "<td>" . htmlspecialchars(number_format($row['price'], 2)) . "</td>";
        echo "<td class='text-center'><span class='badge badge-$badgeColor'>" . htmlspecialchars($status) . "</span></td>";
        
        $action_buttons = "<button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-item-type='consumable'><i class='fas fa-eye'></i></button>";
        if ($is_admin) {
            $action_buttons .= " <button class='btn btn-primary btn-sm edit-inv' data-toggle='modal' data-target='#editEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-item-type='consumable'><i class='fas fa-edit'></i></button>";
            $action_buttons .= " <button class='btn btn-danger btn-sm report-missing' data-id='" . htmlspecialchars($row['inv_id']) . "' data-item-type='consumable' title='Report as Missing'><i class='fas fa-exclamation-triangle'></i></button>";
        }

        echo "<td>" . $action_buttons . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>No Equipment found</td></tr>";
}

$conn->close();
?>
