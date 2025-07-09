<?php
require '../../function_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Updated query to fetch from tbl_inv (assuming consumables and non-consumables are in the same table)
// If they're in separate tables, you might need to adjust this
$queryinv = "SELECT 
                c.inv_id, 
                c.type_id, 
                c.inv_bnm, 
                c.inv_serialno, 
                c.inv_propno, 
                c.inv_propname, 
                c.inv_status, 
                c.inv_date_added, 
                c.date_acquired, 
                c.price, 
                c.inv_quantity, 
                c.end_user, 
                c.accounted_to, 
                t.type_name
             FROM tbl_inv c
             LEFT JOIN tbl_type t ON c.type_id = t.type_id
             WHERE c.is_consumable = 1 OR c.type_id IN (SELECT type_id FROM tbl_type WHERE type_category = 'consumable')
             ORDER BY c.inv_date_added DESC";

// If consumables are in a separate table, use this query instead:
// $queryinv = "SELECT 
//                 c.inv_id, 
//                 c.type_id, 
//                 c.inv_bnm, 
//                 c.inv_serialno, 
//                 c.inv_propno, 
//                 c.inv_propname, 
//                 c.inv_status, 
//                 c.inv_date_added, 
//                 c.date_acquired, 
//                 c.price, 
//                 c.inv_quantity, 
//                 c.end_user, 
//                 c.accounted_to, 
//                 t.type_name
//              FROM tbl_inv_consumables c
//              LEFT JOIN tbl_type t ON c.type_id = t.type_id
//              ORDER BY c.inv_date_added DESC";

$result = $conn->query($queryinv);

if (!$result) {
    error_log("Query Failed: " . $conn->error);
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
                $badgeColor = 'dark'; // Changed from secondary to dark for better distinction
                break;
            default:
                $status = htmlspecialchars($row['inv_status']);
                $badgeColor = 'dark'; // Dark gray for unexpected values
        }

        // Handle null or empty values with fallbacks
        $inv_id = htmlspecialchars($row['inv_id'] ?? '');
        $type_id = htmlspecialchars($row['type_id'] ?? '');
        $type_name = htmlspecialchars($row['type_name'] ?? 'Unknown Type');
        $inv_bnm = htmlspecialchars($row['inv_bnm'] ?? '');
        $inv_serialno = htmlspecialchars($row['inv_serialno'] ?? 'N/A');
        $inv_propno = htmlspecialchars($row['inv_propno'] ?? 'N/A');
        $inv_propname = htmlspecialchars($row['inv_propname'] ?? '');
        $inv_date_added = htmlspecialchars($row['inv_date_added'] ?? '');
        $date_acquired = htmlspecialchars($row['date_acquired'] ?? 'N/A');
        $price = htmlspecialchars($row['price'] ?? '0.00');
        $inv_quantity = htmlspecialchars($row['inv_quantity'] ?? '1');
        $end_user = htmlspecialchars($row['end_user'] ?? 'N/A');
        $accounted_to = htmlspecialchars($row['accounted_to'] ?? 'N/A');

        // Format price if it's a valid number
        if (is_numeric($price)) {
            $formatted_price = '₱' . number_format((float)$price, 2);
        } else {
            $formatted_price = $price;
        }

        // Output table row with data
        echo "<tr>";
        echo "<td>" . $inv_id . "</td>";
        echo "<td>" . $type_name . "</td>"; // Show type name instead of type_id
        echo "<td>" . $inv_bnm . "</td>";
        echo "<td>" . $inv_serialno . "</td>";
        echo "<td>" . $inv_propno . "</td>";
        echo "<td>" . $inv_propname . "</td>";
        echo "<td class='text-center'><span class='badge badge-$badgeColor'>" . $status . "</span></td>";
        echo "<td>" . $inv_date_added . "</td>";
        echo "<td>" . $date_acquired . "</td>";
        echo "<td>" . $formatted_price . "</td>";
        echo "<td class='text-center'>" . $inv_quantity . "</td>";
        echo "<td>" . $end_user . "</td>";
        echo "<td>" . $accounted_to . "</td>";
        // Add action buttons (view, edit, report as missing)
        echo "<td>\n"
            . "<button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . $inv_id . "'><i class='fas fa-eye'></i></button>\n"
            . "<button class='btn btn-primary btn-sm edit-inv' data-toggle='modal' data-target='#editEquipModal' data-id='" . $inv_id . "'><i class='fas fa-edit'></i></button>\n"
            . "<button class='btn btn-warning btn-sm report-missing' title='Report as Missing' data-id='" . $inv_id . "'><i class='fas fa-search'></i></button>\n"
            . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='14' class='text-center'>No Consumable Equipment found</td></tr>";
}

$conn->close();
?>