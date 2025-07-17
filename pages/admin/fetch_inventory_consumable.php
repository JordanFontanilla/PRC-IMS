<?php
require '../../function_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Query to fetch all consumables from tbl_inv_consumables
$queryinv = "SELECT 
                inv_id,
                stock_number,
                acceptance_date,
                ris_no,
                item_description,
                unit,
                receipt,
                issuance,
                end_user_issuance
             FROM tbl_inv_consumables
             ORDER BY acceptance_date DESC";

$result = $conn->query($queryinv);

if (!$result) {
    error_log("Query Failed: " . $conn->error);
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $balance = $row['receipt'] - $row['issuance'];

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['inv_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['stock_number'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['acceptance_date'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['ris_no'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['item_description'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['unit'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['receipt'] ?? '0') . "</td>";
        echo "<td>" . htmlspecialchars($row['issuance'] ?? '0') . "</td>";
        
        
        echo "<td>" . htmlspecialchars($row['end_user_issuance'] ?? 'N/A') . "</td>";
        
        // Add action buttons (view, edit, report as missing)
        echo "<td>\n"            . "<button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-eye'></i></button>\n"            . "<button class='btn btn-primary btn-sm edit-inv' data-toggle='modal' data-target='#editEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-edit'></i></button>\n"            . "<button class='btn btn-danger btn-sm delete-inv' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-trash'></i></button>\n"            . "<button class='btn btn-warning btn-sm report-missing' title='Report as Missing' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-search'></i></button>\n"            . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No Consumable Equipment found</td></tr>";
}

$conn->close();
?>