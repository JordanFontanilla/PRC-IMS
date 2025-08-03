<?php
require '../../function_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : '';
$filter_value = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

$table_name = !empty($month) ? 'tbl_inv_consumables_archive' : 'tbl_inv_consumables';

// Query to fetch consumables, joining with tbl_consumable_balance to get the correct unit
$queryinv = "SELECT 
                t1.inv_id,
                t1.stock_number,
                t1.acceptance_date,
                t1.ris_no,
                t1.item_description,
                COALESCE(cb.unit, t1.unit) as unit, -- Prioritize unit from the balance table
                t1.receipt,
                t1.issuance,
                t1.end_user_issuance
             FROM $table_name t1
             LEFT JOIN tbl_consumable_balance cb ON t1.stock_number = cb.stock_number";

$conditions = [];
$params = [];
$types = '';

if (!empty($month)) {
    $conditions[] = "DATE_FORMAT(t1.archive_date, '%Y-%c') = ?";
    $params[] = $month;
    $types .= 's';
}

if (!empty($filter_column) && !empty($filter_value)) {
    $column_map = [
        '1' => 't1.stock_number',
        '2' => 't1.acceptance_date',
        '3' => 't1.ris_no'
    ];
    if (array_key_exists($filter_column, $column_map)) {
        $conditions[] = $column_map[$filter_column] . " LIKE ?";
        $params[] = "%{$filter_value}%";
        $types .= 's';
    }
}

if (!empty($conditions)) {
    $queryinv .= " WHERE " . implode(' AND ', $conditions);
}

$queryinv .= " ORDER BY t1.acceptance_date DESC";

$stmt = $conn->prepare($queryinv);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $actions = "<button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-eye'></i></button>";
        $actions .= " <button class='btn btn-primary btn-sm edit-inv' data-toggle='modal' data-target='#editEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-edit'></i></button>
                     <button class='btn btn-danger btn-sm delete-inv' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-trash'></i></button>
                     <button class='btn btn-warning btn-sm report-missing' title='Report as Missing' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-search'></i></button>";

        $data[] = [
            htmlspecialchars($row['inv_id']),
            htmlspecialchars($row['stock_number'] ?? 'N/A'),
            htmlspecialchars($row['acceptance_date'] ?? 'N/A'),
            htmlspecialchars($row['ris_no'] ?? 'N/A'),
            htmlspecialchars($row['item_description'] ?? 'N/A'),
            htmlspecialchars($row['unit'] ?? 'N/A'),
            htmlspecialchars($row['receipt'] ?? '0'),
            htmlspecialchars($row['issuance'] ?? '0'),
            htmlspecialchars($row['end_user_issuance'] ?? 'N/A'),
            $actions
        ];
    }
}

$stmt->close();
$conn->close();

echo json_encode(['data' => $data]);
?>