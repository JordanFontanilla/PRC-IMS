<?php
require '../../function_connection.php';

header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$conn->begin_transaction();

try {
    // 1. Archive all current transactions from tbl_inv_consumables to tbl_inv_consumables_archive
    $archive_query = "
        INSERT INTO tbl_inv_consumables_archive (inv_id, stock_number, acceptance_date, ris_no, item_description, unit, receipt, issuance, end_user_issuance)
        SELECT inv_id, stock_number, acceptance_date, ris_no, item_description, unit, receipt, issuance, end_user_issuance
        FROM tbl_inv_consumables
    ";
    
    if (!$conn->query($archive_query)) {
        throw new Exception("Failed to archive consumable transactions: " . $conn->error);
    }
    $archived_rows = $conn->affected_rows;

    // 2. Forward the balances for the next month in tbl_consumable_monthly_balance
    $latest_month_query = "SELECT MAX(month_year) as last_month FROM tbl_consumable_monthly_balance";
    $latest_month_result = $conn->query($latest_month_query);
    $last_month_str = $latest_month_result->fetch_assoc()['last_month'];

    $next_month_date = new DateTime($last_month_str ? $last_month_str : 'first day of last month');
    $next_month_date->modify('+1 month');
    $next_month_str = $next_month_date->format('Y-m-d');

    $stock_numbers_query = "SELECT stock_number, beginning_balance FROM tbl_consumable_balance";
    $stock_result = $conn->query($stock_numbers_query);

    while ($balance_row = $stock_result->fetch_assoc()) {
        $stock_number = $balance_row['stock_number'];
        $beginning_balance = $balance_row['beginning_balance'];

        $insert_monthly_query = "INSERT INTO tbl_consumable_monthly_balance (stock_number, month_year, beginning_balance, ending_balance) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_monthly_query);
        $insert_stmt->bind_param("ssii", $stock_number, $next_month_str, $beginning_balance, $beginning_balance);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    // 3. After everything is successfully archived and forwarded, clear the live transactions table.
    if (!$conn->query("DELETE FROM tbl_inv_consumables")) {
        throw new Exception("Failed to clear live consumable transactions: " . $conn->error);
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => "Successfully archived $archived_rows transaction(s) and forwarded balances."]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>