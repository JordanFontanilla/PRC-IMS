<?php
require '../../function_connection.php';
header('Content-Type: application/json');

// Query to get distinct months from both the monthly balance and the archive table
$query = "
    SELECT DISTINCT month_year FROM (
        SELECT month_year FROM tbl_consumable_monthly_balance
        UNION
        SELECT DISTINCT DATE_FORMAT(acceptance_date, '%Y-%m-01') AS month_year FROM tbl_inv_consumables_archive
    ) AS all_months
    WHERE month_year IS NOT NULL
    ORDER BY month_year DESC
";

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