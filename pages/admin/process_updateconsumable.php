<?php
require '../../function_connection.php';

header('Content-Type: application/json');

if (isset($_POST['updateConsumable'])) {
    $inv_id = $_POST['inv_id'];
    $stock_number = $_POST['stock_number'];
    $acceptance_date = $_POST['acceptance_date'];
    $ris_no = $_POST['ris_no'];
    $item_description = $_POST['item_description'];
    $unit = $_POST['unit'];
    $receipt = $_POST['receipt'];
    $issuance = $_POST['issuance'];
    $end_user_issuance = $_POST['end_user_issuance'];

    $query = "UPDATE tbl_inv_consumables SET 
                stock_number = ?,
                acceptance_date = ?, 
                ris_no = ?, 
                item_description = ?, 
                unit = ?, 
                receipt = ?, 
                issuance = ?, 
                end_user_issuance = ? 
              WHERE inv_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssiisi", $stock_number, $acceptance_date, $ris_no, $item_description, $unit, $receipt, $issuance, $end_user_issuance, $inv_id);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Consumable equipment updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update consumable equipment."]);
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