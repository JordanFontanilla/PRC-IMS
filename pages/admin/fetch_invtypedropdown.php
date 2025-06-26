<?php
require '../../function_connection.php'; // Include the database connection

// Fetch types from tbl_type
$typeQuery = "SELECT type_id, type_name FROM tbl_type ORDER BY type_name ASC";
$result = $conn->query($typeQuery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['type_name']) . '">' . htmlspecialchars($row['type_name']) . '</option>';
    }
}

$conn->close(); // Close connection after fetching data
?>
