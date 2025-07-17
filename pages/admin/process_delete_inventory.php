<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['inv_id']) && isset($_POST['origin'])) {
    $inv_id = $_POST['inv_id'];
    $origin = $_POST['origin'];

    if ($origin === 'consumable') {
        $query = "DELETE FROM tbl_inv_consumables WHERE inv_id = ?";
    } else {
        $query = "DELETE FROM tbl_inv WHERE inv_id = ?";
    }

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $inv_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record.']);
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