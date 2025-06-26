<?php
require '../../function_connection.php';

if (isset($_POST['type_id'], $_POST['type_name'])) {
    $type_id = $_POST['type_id'];
    $type_name = trim($_POST['type_name']);
    $type_origin = $_POST['type_origin'];

    if (empty($type_name)) {
        echo json_encode(["success" => false, "message" => "Type name cannot be empty."]);
        exit;
    }

    $query = "UPDATE tbl_type SET type_name = ?, type_origin = ? WHERE type_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssi", $type_name, $type_origin, $type_id);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Type updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Update failed."]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Query preparation failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>