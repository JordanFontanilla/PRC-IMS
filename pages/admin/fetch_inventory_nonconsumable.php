<?php
require '../../function_connection.php';

// Add CSS to prevent wrapping
$queryinv = "SELECT 
                tbl_inv.inv_id, 
                tbl_inv.type_id, 
                tbl_inv.inv_serialno, 
                tbl_inv.inv_propno,
                tbl_inv.inv_propname,
                tbl_inv.inv_price,
                tbl_inv.inv_status, 
                tbl_inv.inv_bnm, 
                tbl_type.type_name,
                tbl_type.type_origin,
                tbl_inv.inv_date_added,
                tbl_inv.date_acquired,
                tbl_inv.price,
                tbl_inv.condition,
                tbl_inv.inv_quantity,
                tbl_inv.end_user,
                tbl_inv.accounted_to
             FROM tbl_inv 
             LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id 
             WHERE tbl_type.type_origin = 'Non-Consumable'
             ORDER BY tbl_inv.inv_date_added DESC"; // Ordering by inv_date_added for FIFO (oldest first)


$result = $conn->query($queryinv);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Map the inv_status to its corresponding string and badge color
        switch ($row['inv_status']) {
            case 1:
            case '1':
                $status = 'Available';
                $badgeColor = 'success'; // Green
                break;
            case 2:
            case '2':
                $status = 'Unavailable';
                $badgeColor = 'secondary'; // Gray
                break;
            case 3:
            case '3':
                $status = 'Pending For Approval';
                $badgeColor = 'warning'; // Yellow
                break;
            case 4:
            case '4':
                $status = 'Borrowed';
                $badgeColor = 'primary'; // Blue
                break;
            case 5:
            case '5':
                $status = 'Returned';
                $badgeColor = 'info'; // Light Blue
                break;
            case 6:
            case '6':
                $status = 'Missing';
                $badgeColor = 'danger'; // Red
                break;
            case 7:
            case '7':
                $status = 'In Use';
                $badgeColor = 'secondary'; // Red
                break;
            default:
                $status = htmlspecialchars($row['inv_status']);
                $badgeColor = 'dark'; // Dark gray for unexpected values
        }

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['inv_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['type_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_price']) . "</td>";
        echo "<td class='text-center'><span class='badge badge-$badgeColor'>" . htmlspecialchars($status) . "</span></td>";
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_date_added']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_acquired']) . "</td>";
        echo "<td>" . htmlspecialchars($row['price']) . "</td>";
        echo "<td>" . htmlspecialchars($row['condition']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['end_user']) . "</td>";
        echo "<td>" . htmlspecialchars($row['accounted_to']) . "</td>";
        echo "<td>\n"
            . "<button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-eye'></i></button>\n"
            . "<button class='btn btn-primary btn-sm edit-inv' data-toggle='modal' data-target='#editEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-edit'></i></button>\n";
        if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'Admin') {
            echo "<button class='btn btn-danger btn-sm delete-inv' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='non-consumable'><i class='fas fa-trash'></i></button>\n"
            . "<button class='btn btn-warning btn-sm report-missing' title='Report as Missing' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='non-consumable'><i class='fas fa-search'></i></button>\n";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='16'>No Equipment found</td></tr>";
}

$conn->close();
?>
