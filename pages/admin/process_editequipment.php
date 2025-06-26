<?php
require '../../function_connection.php';

if (isset($_POST['inv_id']) && isset($_POST['equipSerial']) && isset($_POST['equipPropNo']) && isset($_POST['equipStatus'])) {
    $inv_id = $_POST['inv_id'];
    $equipSerial = $_POST['equipSerial'];
    $equipPropNo = $_POST['equipPropNo'];
    $equipStatus = $_POST['equipStatus'];

    // Prepare the update query
    $query = "UPDATE tbl_inv 
              SET inv_serialno = ?, inv_propno = ?, inv_status = ? 
              WHERE inv_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssii", $equipSerial, $equipPropNo, $equipStatus, $inv_id);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Equipment updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update equipment."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Query preparation failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>
