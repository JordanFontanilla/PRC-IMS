<?php
require '../../function_connection.php';

header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$conn->begin_transaction();

try {
    $current_month_start = date('Y-m-01');

    // Get all unique stock numbers from both the balance and live transaction tables
    $stock_query = "
        SELECT stock_number FROM tbl_consumable_balance
        UNION
        SELECT DISTINCT stock_number FROM tbl_inv_consumables
    ";
    $stock_result = $conn->query($stock_query);
    if (!$stock_result) {
        throw new Exception("Failed to gather stock numbers: " . $conn->error);
    }

    while ($stock_row = $stock_result->fetch_assoc()) {
        $stock_number = $stock_row['stock_number'];
        if (empty($stock_number)) {
            continue;
        }

        // Get current beginning balance, defaulting to 0 if it's a new item
        $beginning_balance = 0;
        $balance_stmt = $conn->prepare("SELECT beginning_balance FROM tbl_consumable_balance WHERE stock_number = ?");
        $balance_stmt->bind_param('s', $stock_number);
        $balance_stmt->execute();
        $balance_res = $balance_stmt->get_result();
        if ($balance_row = $balance_res->fetch_assoc()) {
            $beginning_balance = $balance_row['beginning_balance'];
        }
        $balance_stmt->close();

        // Get total receipts and issuances for the current period from the live table
        $total_receipt = 0;
        $total_issuance = 0;
        $trans_stmt = $conn->prepare("SELECT COALESCE(SUM(receipt), 0) as total_receipt, COALESCE(SUM(issuance), 0) as total_issuance FROM tbl_inv_consumables WHERE stock_number = ?");
        $trans_stmt->bind_param('s', $stock_number);
        $trans_stmt->execute();
        $trans_result = $trans_stmt->get_result()->fetch_assoc();
        $total_receipt = $trans_result['total_receipt'];
        $total_issuance = $trans_result['total_issuance'];
        $trans_stmt->close();

        // Calculate the ending balance for the current period
        $ending_balance = ($beginning_balance + $total_receipt) - $total_issuance;

        // Archive the monthly balance record
        $archive_balance_stmt = $conn->prepare("INSERT INTO tbl_consumable_monthly_balance (stock_number, month_year, beginning_balance, ending_balance) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE beginning_balance = VALUES(beginning_balance), ending_balance = VALUES(ending_balance)");
        $archive_balance_stmt->bind_param('ssii', $stock_number, $current_month_start, $beginning_balance, $ending_balance);
        $archive_balance_stmt->execute();
        $archive_balance_stmt->close();

        // Update the main balance table to set the new beginning_balance for the next period
        $update_main_balance_stmt = $conn->prepare("UPDATE tbl_consumable_balance SET beginning_balance = ? WHERE stock_number = ?");
        $update_main_balance_stmt->bind_param('is', $ending_balance, $stock_number);
        $update_main_balance_stmt->execute();
        $update_main_balance_stmt->close();
    }

    // Archive all live transactions
    $archive_trans_query = "
        INSERT INTO tbl_inv_consumables_archive (inv_id, stock_number, acceptance_date, ris_no, item_description, unit, receipt, issuance, end_user_issuance, archive_date)
        SELECT inv_id, stock_number, acceptance_date, ris_no, item_description, unit, receipt, issuance, end_user_issuance, NOW()
        FROM tbl_inv_consumables
    ";
    if (!$conn->query($archive_trans_query)) {
        throw new Exception("Failed to archive transactions: " . $conn->error);
    }
    $archived_rows = $conn->affected_rows;

    // Clear the live transactions table
    if ($archived_rows > 0) {
        if (!$conn->query("DELETE FROM tbl_inv_consumables")) {
            throw new Exception("Failed to clear live consumable transactions: " . $conn->error);
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => "Successfully archived $archived_rows transaction(s) and forwarded balances to the next month."]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>