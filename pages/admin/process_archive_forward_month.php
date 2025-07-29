<?php
require '../../function_connection.php';

header('Content-Type: application/json');

$conn->begin_transaction();

try {
    // Get the latest month from the monthly balance table
    $latest_month_query = "SELECT MAX(month_year) as last_month FROM tbl_consumable_monthly_balance";
    $latest_month_result = $conn->query($latest_month_query);
    $latest_month_row = $latest_month_result->fetch_assoc();
    $last_month_str = $latest_month_row['last_month'];

    if (!$last_month_str) {
        throw new Exception("No existing monthly records found to archive from.");
    }

    $last_month = new DateTime($last_month_str);

    // Calculate the next month
    $next_month = clone $last_month;
    $next_month->modify('+1 month');
    $next_month_str = $next_month->format('Y-m-d');

    // Check if the next month already exists
    $check_next_month_query = "SELECT COUNT(*) as count FROM tbl_consumable_monthly_balance WHERE month_year = ?";
    $check_stmt = $conn->prepare($check_next_month_query);
    $check_stmt->bind_param("s", $next_month_str);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result()->fetch_assoc();
    if ($check_result['count'] > 0) {
        throw new Exception("The next month already exists. Cannot forward balances again.");
    }

    // Get all records from the last month
    $last_month_records_query = "SELECT stock_number, ending_balance FROM tbl_consumable_monthly_balance WHERE month_year = ?";
    $stmt = $conn->prepare($last_month_records_query);
    $stmt->bind_param("s", $last_month_str);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("No records found for the latest month to carry over.");
    }

    while ($row = $result->fetch_assoc()) {
        $stock_number = $row['stock_number'];
        $beginning_balance_for_next_month = $row['ending_balance'];

        // Check if stock_number exists in tbl_consumable_balance
        $check_cb_query = "SELECT COUNT(*) FROM tbl_consumable_balance WHERE stock_number = ?";
        $check_cb_stmt = $conn->prepare($check_cb_query);
        $check_cb_stmt->bind_param("s", $stock_number);
        $check_cb_stmt->execute();
        $cb_count = $check_cb_stmt->get_result()->fetch_row()[0];
        $check_cb_stmt->close();

        if ($cb_count == 0) {
            // If not exists, insert a new record with placeholder values
            $insert_cb_query = "INSERT INTO tbl_consumable_balance (stock_number, item_description, unit, beginning_balance) VALUES (?, ?, ?, ?)";
            $insert_cb_stmt = $conn->prepare($insert_cb_query);
            $placeholder_desc = "Auto-generated for " . $stock_number;
            $placeholder_unit = "pcs"; // Default unit
            $insert_cb_stmt->bind_param("sssi", $stock_number, $placeholder_desc, $placeholder_unit, $beginning_balance_for_next_month);
            $insert_cb_stmt->execute();
            $insert_cb_stmt->close();
        }

        // Insert the new record for the next month
        $insert_query = "INSERT INTO tbl_consumable_monthly_balance (stock_number, month_year, beginning_balance, ending_balance) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        // The new ending_balance is the same as beginning_balance initially, as there are no transactions yet for the new month.
        $insert_stmt->bind_param("ssii", $stock_number, $next_month_str, $beginning_balance_for_next_month, $beginning_balance_for_next_month);
        $insert_stmt->execute();
    }

    // Delete records from tbl_inv_consumables for the archived month
    $delete_consumables_query = "DELETE FROM tbl_inv_consumables WHERE DATE_FORMAT(acceptance_date, '%Y-%m') = DATE_FORMAT(?, '%Y-%m')";
    $delete_stmt = $conn->prepare($delete_consumables_query);
    $delete_stmt->bind_param("s", $last_month_str);
    $delete_stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Successfully archived and forwarded balances to the next month.']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$conn->close();
?>