<?php
require '../../function_connection.php';

$query = "SELECT ci.date_consumed, ic.item_description, ci.quantity, ci.consumed_by FROM tbl_consumed_items ci JOIN tbl_inv_consumables ic ON ci.inv_id = ic.inv_id ORDER BY ci.date_consumed DESC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['date_consumed']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['consumed_by']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No consumed items found</td></tr>";
}

$conn->close();
?>