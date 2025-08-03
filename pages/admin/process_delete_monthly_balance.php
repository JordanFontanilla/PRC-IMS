<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['month'])) {
    $month = $_POST['month']; // e.g., "2025-7"
    $month_date_for_balance = date('Y-m-01', strtotime($month)); // "2025-07-01"

    $conn->begin_transaction();

    try {
        // Delete from tbl_consumable_monthly_balance
        $query1 = "DELETE FROM tbl_consumable_monthly_balance WHERE month_year = ?";
        $stmt1 = $conn->prepare($query1);
        if (!$stmt1) throw new Exception('Prepare failed for monthly balance delete: ' . $conn->error);
        $stmt1->bind_param("s", $month_date_for_balance);
        if (!$stmt1->execute()) throw new Exception('Execute failed for monthly balance delete: ' . $stmt1->error);
        $stmt1->close();

        // Delete from tbl_inv_consumables_archive based on the archive_date
        $query2 = "DELETE FROM tbl_inv_consumables_archive WHERE DATE_FORMAT(archive_date, '%Y-%c') = ?";
        $stmt2 = $conn->prepare($query2);
        if (!$stmt2) throw new Exception('Prepare failed for archive delete: ' . $conn->error);
        $stmt2->bind_param("s", $month);
        if (!$stmt2->execute()) throw new Exception('Execute failed for archive delete: ' . $stmt2->error);
        $stmt2->close();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Successfully deleted records for the selected month from both balance and archive.']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

$conn->close();
?>