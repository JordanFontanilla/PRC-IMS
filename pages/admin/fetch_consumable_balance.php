<?php
require '../../function_connection.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$month = isset($_GET['month']) ? $_GET['month'] : '';

$data = [];

if (empty($month)) {
    // LIVE DATA VIEW (Latest Month)
    $query = "
        SELECT 
            cb.id,
            cb.stock_number,
            cb.item_description,
            cb.unit,
            cb.beginning_balance,
            COALESCE(live_trans.total_receipt, 0) as total_receipt,
            COALESCE(live_trans.total_issuance, 0) as total_issuance
        FROM 
            tbl_consumable_balance cb
        LEFT JOIN (
            SELECT 
                stock_number, 
                SUM(receipt) as total_receipt, 
                SUM(issuance) as total_issuance 
            FROM 
                tbl_inv_consumables 
            GROUP BY 
                stock_number
        ) as live_trans ON cb.stock_number = live_trans.stock_number
        ORDER BY 
            cb.item_description ASC
    ";
    
    $result = $conn->query($query);
    if (!$result) {
        echo json_encode(['data' => [], 'error' => $conn->error]);
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $beginning_balance = $row['beginning_balance'];
        $receipts = $row['total_receipt'];
        $issuance = $row['total_issuance'];
        $ending_balance = ($beginning_balance + $receipts) - $issuance;

        $actions = "<button class='btn btn-primary btn-sm edit-consumable-balance' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i></button>
                    <button class='btn btn-danger btn-sm delete-consumable-balance' data-id='" . $row['id'] . "'><i class='fas fa-trash'></i></button>";

        $data[] = [
            htmlspecialchars($row['stock_number'] ?? 'N/A'),
            htmlspecialchars($row['item_description'] ?? 'N/A'),
            htmlspecialchars($row['unit'] ?? 'N/A'),
            htmlspecialchars($beginning_balance),
            htmlspecialchars($receipts),
            htmlspecialchars($issuance),
            htmlspecialchars($ending_balance),
            $actions
        ];
    }

} else {
    // ARCHIVED DATA VIEW
    $month_start_date = date('Y-m-01', strtotime($month));

    $query = "
        SELECT 
            cmb.id,
            cmb.stock_number,
            cb.item_description,
            cb.unit,
            cmb.beginning_balance,
            COALESCE(archive_trans.total_receipt, 0) as total_receipt,
            COALESCE(archive_trans.total_issuance, 0) as total_issuance
        FROM 
            tbl_consumable_monthly_balance cmb
        JOIN 
            tbl_consumable_balance cb ON cmb.stock_number = cb.stock_number
        LEFT JOIN (
            SELECT 
                stock_number, 
                SUM(receipt) as total_receipt, 
                SUM(issuance) as total_issuance 
            FROM 
                tbl_inv_consumables_archive 
            WHERE 
                DATE_FORMAT(archive_date, '%Y-%c') = ?
            GROUP BY 
                stock_number
        ) as archive_trans ON cmb.stock_number = archive_trans.stock_number
        WHERE 
            cmb.month_year = ?
        ORDER BY 
            cb.item_description ASC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $month, $month_start_date);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        echo json_encode(['data' => [], 'error' => $stmt->error]);
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $beginning_balance = $row['beginning_balance'];
        $receipts = $row['total_receipt'];
        $issuance = $row['total_issuance'];
        $ending_balance = ($beginning_balance + $receipts) - $issuance;

        $actions = "<button class='btn btn-primary btn-sm edit-consumable-balance' data-id='" . $row['id'] . "'><i class='fas fa-edit'></i></button>";

        $data[] = [
            htmlspecialchars($row['stock_number'] ?? 'N/A'),
            htmlspecialchars($row['item_description'] ?? 'N/A'),
            htmlspecialchars($row['unit'] ?? 'N/A'),
            htmlspecialchars($beginning_balance),
            htmlspecialchars($receipts),
            htmlspecialchars($issuance),
            htmlspecialchars($ending_balance),
            $actions
        ];
    }
    $stmt->close();
}

$conn->close();
echo json_encode(['data' => $data]);
?>