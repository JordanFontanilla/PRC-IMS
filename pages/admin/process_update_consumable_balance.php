<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stock_number = $_POST['stock_number'];
    $item_description = $_POST['item_description'];
    $unit = $_POST['unit'];
    $beginning_balance = $_POST['beginning_balance'];

    // If a record from an archived month is being edited, only update the beginning balance.
    $query = "UPDATE tbl_consumable_monthly_balance SET beginning_balance = ? WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $beginning_balance, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Consumable balance updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update consumable balance.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Query preparation failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>