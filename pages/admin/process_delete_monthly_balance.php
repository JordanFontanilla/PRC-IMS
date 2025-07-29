<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['month'])) {
    $month = $_POST['month'];
    $month_date = date('Y-m-01', strtotime($month));

    $query = "DELETE FROM tbl_consumable_monthly_balance WHERE month_year = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $month_date);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete records.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Query preparation failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}

$conn->close();
?>