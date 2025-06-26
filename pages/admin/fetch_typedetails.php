<?php
require '../../function_connection.php';

if (isset($_POST['type_id'])) {
    $type_id = $_POST['type_id'];

    $query = "SELECT type_id, type_name, type_origin FROM tbl_type WHERE type_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $type_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(["success" => true, "data" => $row]);
        } else {
            echo json_encode(["success" => false, "message" => "No record found."]);
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
