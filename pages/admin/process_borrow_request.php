<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');

// Function to save the signature image with token-based filename
function saveSignature($signatureData, $token) {
    $signatureDir = '../../signatures/';
    if (!file_exists($signatureDir)) {
        mkdir($signatureDir, 0755, true);
    }
    
    $encodedImage = explode(',', $signatureData)[1];
    $decodedImage = base64_decode($encodedImage);
    
    // Use the token as the filename (e.g., PRC-000001.png)
    $filename = $token . '.png';
    $filepath = $signatureDir . $filename;
    
    file_put_contents($filepath, $decodedImage);
    
    return 'signatures/' . $filename;
}

// Function to generate the next PRC token
function generatePRCToken($conn) {
    // Get the highest existing PRC number
    $query = "SELECT MAX(CAST(SUBSTRING(breq_token, 5) AS UNSIGNED)) as max_num 
              FROM tbl_borrow_request 
              WHERE breq_token LIKE 'PRC-%'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $maxNum = ($row && $row['max_num']) ? $row['max_num'] : 0;
    
    // Generate next number with leading zeros
    $nextNum = $maxNum + 1;
    return 'PRC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
}

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

if (
    isset($_POST['borrowerName']) && !empty($_POST['borrowerName']) &&
    isset($_POST['borrowerRemark']) && !empty($_POST['borrowerRemark']) &&
    isset($_POST['signatureData']) && !empty($_POST['signatureData']) &&
    isset($_POST['items']) && !empty($_POST['items'])
) {
    $borrowerName = $_POST['borrowerName'];
    $borrowerRemark = $_POST['borrowerRemark'];
    $signatureData = $_POST['signatureData'];
    $items = json_decode($_POST['items'], true);

    if (empty($items)) {
        $response = ['success' => false, 'message' => 'No items selected.'];
    } else {
        // Generate token first so we can use it for the filename
        $breqToken = generatePRCToken($conn);
        
        // Save the signature image with token-based filename
        $signatureFilePath = saveSignature($signatureData, $breqToken);

        try {
            $conn->begin_transaction();

            $breqDate = date('Y-m-d H:i:s');
            $breqStatus = 3; // Assuming 3 means "pending"

            // Insert borrow request
            $query = "INSERT INTO tbl_borrow_request 
                      (emp_name, breq_date, breq_status, breq_token, breq_remarks, breq_signature) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssss", $borrowerName, $breqDate, $breqStatus, $breqToken, $borrowerRemark, $signatureFilePath);
            $stmt->execute();
            $breqId = $stmt->insert_id;

            // Insert borrowed items with the PRC token
            foreach ($items as $item) {
                $inv_id = $item['invId'];

                // Insert into tbl_borrow_request_items
                $insert_item_query = "INSERT INTO tbl_borrow_request_items (breq_token, inv_id, is_approved) VALUES (?, ?, 0)"; // 0 for pending
                $stmt_item = $conn->prepare($insert_item_query);
                $stmt_item->bind_param("si", $breqToken, $inv_id);
                $stmt_item->execute();

                // Update tbl_inv status to 'Pending For Approval' (status 3)
                $update_inv_query = "UPDATE tbl_inv SET inv_status = 3 WHERE inv_id = ?";
                $stmt_inv = $conn->prepare($update_inv_query);
                $stmt_inv->bind_param("i", $inv_id);
                $stmt_inv->execute();
            }

            $conn->commit();
            $response = ['success' => true, 'message' => 'Request submitted successfully.'];

        } catch (Exception $e) {
            $conn->rollback();
            $response = ['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()];
        }
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request data. Missing required POST parameters.'];
}

$conn->close();
ob_end_clean();
echo json_encode($response);
exit;