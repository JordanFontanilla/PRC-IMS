<?php
require '../../function_connection.php';
header('Content-Type: application/json');

$query = "SELECT DISTINCT month_year FROM tbl_consumable_monthly_balance ORDER BY month_year DESC";

$result = $conn->query($query);

$months = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $date = new DateTime($row['month_year']);
        $months[] = [
            'year' => $date->format('Y'),
            'month' => $date->format('n'),
            'month_name' => $date->format('F')
        ];
    }
}

$conn->close();
echo json_encode($months);
?>