<?php
require '../../function_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$month = isset($_GET['month']) ? $_GET['month'] : '';

$current_month_filter = isset($_GET['month']) ? $_GET['month'] : '';
$current_date_ym = date('Y-m');

// Determine the month to display
if (empty($current_month_filter)) {
    // If no month filter, try to get the latest month from monthly balance
    $latest_month_query = "SELECT MAX(month_year) as last_month FROM tbl_consumable_monthly_balance";
    $latest_month_result = $conn->query($latest_month_query);
    $latest_month_row = $latest_month_result->fetch_assoc();
    $display_month = $latest_month_row['last_month'];

    // If tbl_consumable_monthly_balance is empty, default to current month
    if (empty($display_month)) {
        $display_month = date('Y-m-01');
    }
} else {
    $display_month = date('Y-m-01', strtotime($current_month_filter));
}

// Query to fetch and group consumable items for the selected month
// This query will try to get data from tbl_consumable_monthly_balance first
$query = "SELECT 
            cmb.id,
            cmb.stock_number,
            cb.item_description,
            cb.unit,
            cmb.beginning_balance,
            COALESCE(SUM(ic.receipt), 0) as total_receipt,
            COALESCE(SUM(ic.issuance), 0) as total_issuance
         FROM tbl_consumable_monthly_balance cmb
         LEFT JOIN tbl_consumable_balance cb ON cmb.stock_number = cb.stock_number
         LEFT JOIN tbl_inv_consumables ic ON cmb.stock_number = ic.stock_number AND DATE_FORMAT(ic.acceptance_date, '%Y-%m') = DATE_FORMAT(cmb.month_year, '%Y-%m')
         WHERE cmb.month_year = ?
         GROUP BY cmb.id, cmb.stock_number, cb.item_description, cb.unit, cmb.beginning_balance
         ORDER BY cb.item_description ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $display_month);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

// If no data from tbl_consumable_monthly_balance for the display_month, and it's the current month,
// try to fetch directly from tbl_consumable_balance (for initial setup before archiving)
if ($result->num_rows === 0 && date('Y-m', strtotime($display_month)) === $current_date_ym) {
    $query_fallback = "SELECT 
                        id,
                        stock_number,
                        item_description,
                        unit,
                        beginning_balance,
                        0 as total_receipt,
                        0 as total_issuance
                       FROM tbl_consumable_balance
                       ORDER BY item_description ASC";
    $result_fallback = $conn->query($query_fallback);

    if ($result_fallback->num_rows > 0) {
        while ($row = $result_fallback->fetch_assoc()) {
            $beginning_balance = $row['beginning_balance'];
            $receipts = $row['total_receipt'];
            $issuance = $row['total_issuance'];
            $ending_balance = ($beginning_balance + $receipts) - $issuance;

            // For fallback, we don't update tbl_consumable_monthly_balance as it's not its data
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
    }
} else if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $beginning_balance = $row['beginning_balance'];
        $receipts = $row['total_receipt'];
        $issuance = $row['total_issuance'];
        $ending_balance = ($beginning_balance + $receipts) - $issuance;

        // Update the ending balance in the database only for the current month being displayed
        // and only if it's the actual current month (not a historical month filter)
        if (date('Y-m', strtotime($display_month)) === $current_date_ym) {
            $update_ending_balance_query = "UPDATE tbl_consumable_monthly_balance SET ending_balance = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_ending_balance_query);
            $update_stmt->bind_param("ii", $ending_balance, $row['id']);
            $update_stmt->execute();
        }

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
}

$stmt->close();
$conn->close();

echo json_encode(['data' => $data]);
?>