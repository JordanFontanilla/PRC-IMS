<?php
require '../../function_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inv_id = isset($_POST['inv_id']) ? intval($_POST['inv_id']) : 0;
    $origin = isset($_POST['origin']) ? $_POST['origin'] : '';

    if ($inv_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid equipment ID.']);
        exit;
    }

    if ($origin === 'consumable') {
        // For consumables, we can't mark them as missing in the same way.
        // You might want to log this event or handle it differently.
        echo json_encode(['status' => 'error', 'message' => 'Consumable items cannot be reported as missing through this process.']);
        exit;
    } else {
        // For non-consumables, update the status to missing (6)
        $update = $conn->prepare("UPDATE tbl_inv SET inv_status = 6 WHERE inv_id = ?");
        $update->bind_param('i', $inv_id);
        if ($update->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Equipment reported as missing.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
        }
        $update->close();
    }

    $conn->close();
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
?>
