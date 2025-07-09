<?php
require '../../function_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inv_id = isset($_POST['inv_id']) ? intval($_POST['inv_id']) : 0;
    if ($inv_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid equipment ID.']);
        exit;
    }

    // Fetch the equipment row
    $query = "SELECT * FROM tbl_inv WHERE inv_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $inv_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $equipment = $result->fetch_assoc();
    $stmt->close();

    if (!$equipment) {
        echo json_encode(['status' => 'error', 'message' => 'Equipment not found.']);
        exit;
    }

    // Insert into missing table (if you have a separate table, e.g., tbl_missing_inv)
    // If not, you can skip this and just update the status
    // $insert = $conn->prepare("INSERT INTO tbl_missing_inv (...) VALUES (...)");
    // $insert->execute();
    // $insert->close();

    // Update status to missing (status = 6)
    $update = $conn->prepare("UPDATE tbl_inv SET inv_status = 6 WHERE inv_id = ?");
    $update->bind_param('i', $inv_id);
    if ($update->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Equipment reported as missing.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
    }
    $update->close();
    $conn->close();
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
?>
