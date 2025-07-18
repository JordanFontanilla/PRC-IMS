<?php
require '../../function_connection.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $query = "DELETE FROM tbl_user WHERE username = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
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