<?php
require '../../function_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$month = isset($_GET['month']) ? $_GET['month'] : '';
$filter_column = isset($_GET['filter_column']) ? $_GET['filter_column'] : '';
$filter_value = isset($_GET['filter_value']) ? $_GET['filter_value'] : '';

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
             FROM tbl_inv_consumables";

$conditions = [];
$params = [];
$types = '';

if (!empty($month)) {
    $conditions[] = "DATE_FORMAT(acceptance_date, '%Y-%c') = ?";
    $params[] = $month;
    $types .= 's';
}

if (!empty($filter_column) && !empty($filter_value)) {
    $column_map = [
        '1' => 'stock_number',
        '2' => 'acceptance_date',
        '3' => 'ris_no'
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

$queryinv .= " ORDER BY acceptance_date DESC";

$stmt = $conn->prepare($queryinv);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $balance = $row['receipt'] - $row['issuance'];

        $actions = "<button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-eye'></i></button>
                    <button class='btn btn-primary btn-sm edit-inv' data-toggle='modal' data-target='#editEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-edit'></i></button>";
        if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'Admin') {
            $actions .= "<button class='btn btn-danger btn-sm delete-inv' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-trash'></i></button>
                         <button class='btn btn-warning btn-sm report-missing' title='Report as Missing' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='consumable'><i class='fas fa-search'></i></button>";
        }

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