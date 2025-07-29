<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stock_number = $_POST['stock_number'];
    $item_description = $_POST['item_description'];
    $unit = $_POST['unit'];
    $beginning_balance = $_POST['beginning_balance'];

    $query = "INSERT INTO tbl_consumable_balance (stock_number, item_description, unit, beginning_balance) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssi", $stock_number, $item_description, $unit, $beginning_balance);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Consumable balance added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add consumable balance.']);
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