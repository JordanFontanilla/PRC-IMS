<?php
require '../../function_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Query to fetch and group consumable items
$query = "SELECT 
            cb.stock_number,
            cb.item_description,
            cb.unit,
            cb.beginning_balance,
            COALESCE(SUM(ic.receipt), 0) as total_receipt,
            COALESCE(SUM(ic.issuance), 0) as total_issuance
         FROM tbl_consumable_balance cb
         LEFT JOIN tbl_inv_consumables ic ON cb.stock_number = ic.stock_number
         GROUP BY cb.stock_number, cb.item_description, cb.unit, cb.beginning_balance
         ORDER BY cb.item_description ASC";

$result = $conn->query($query);

if (!$result) {
    error_log("Query Failed: " . $conn->error);
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $beginning_balance = $row['beginning_balance'];
        $receipts = $row['total_receipt'];
        $issuance = $row['total_issuance'];
        $ending_balance = ($beginning_balance + $receipts) - $issuance;

        // Output table row with data
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['stock_number'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['item_description'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['unit'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($beginning_balance) . "</td>";
        echo "<td>" . htmlspecialchars($receipts) . "</td>";
        echo "<td>" . htmlspecialchars($issuance) . "</td>";
        echo "<td>" . htmlspecialchars($ending_balance) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No Consumable Balances found</td></tr>";
}

$conn->close();
?>