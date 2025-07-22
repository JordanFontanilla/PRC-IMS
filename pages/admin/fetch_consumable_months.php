<?php
require '../../function_connection.php';
header('Content-Type: application/json');

$query = "SELECT DISTINCT YEAR(acceptance_date) AS year, MONTH(acceptance_date) AS month 
          FROM tbl_inv_consumables 
          WHERE acceptance_date IS NOT NULL 
          ORDER BY year DESC, month DESC";

$result = $conn->query($query);

$months = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $months[] = [
            'year' => $row['year'],
            'month' => $row['month'],
            'month_name' => date('F', mktime(0, 0, 0, $row['month'], 10)) // 'F' for full month name
        ];
    }
}

$conn->close();
echo json_encode($months);
?>