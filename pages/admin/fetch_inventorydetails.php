<?php
require '../../function_connection.php';

if (isset($_POST['inv_id'])) {
    $inv_id = $_POST['inv_id'];

    $query = "SELECT *
              FROM tbl_inv 
              LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id
              WHERE tbl_inv.inv_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $inv_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Map status codes to readable text
            $statusText = [
                1 => 'Available',
                2 => 'Unavailable',
                3 => 'Pending For Approval',
                4 => 'Borrowed',
                5 => 'Returned',
                6 => 'Missing',
                7 => 'In Service',
            ];
            $row['status'] = $statusText[$row['inv_status']] ?? 'Unknown';
            
            echo json_encode(["success" => true, "data" => $row]);
        } else {
            echo json_encode(["success" => false, "message" => "No record found."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Query failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
