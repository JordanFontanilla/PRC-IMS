<?php
require '../../function_connection.php';

header('Content-Type: application/json');

$response = ['success' => false, 'unit' => ''];

if (isset($_GET['stock_number'])) {
    $stock_number = $_GET['stock_number'];

    $query = "SELECT unit FROM tbl_consumable_balance WHERE stock_number = ? LIMIT 1";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $stock_number);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response['success'] = true;
            $response['unit'] = $row['unit'];
        }
        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>