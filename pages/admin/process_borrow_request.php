<?php
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
    $maxNum = $row['max_num'] ? $row['max_num'] : 0;
    
    // Generate next number with leading zeros
    $nextNum = $maxNum + 1;
    return 'PRC-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
}

error_log('POST Data: ' . print_r($_POST, true));

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

    error_log('Borrower Name: ' . $borrowerName);
    error_log('Borrower Remark: ' . $borrowerRemark);
    error_log('Items Count: ' . count($items));

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
            if ($item['origin'] === 'consumable') {
                // Handle consumable items
                $inv_id = $item['invId'];
                $quantity = $item['quantity'];

                // Update the quantity of the consumable item in the inventory
                $update_query = "UPDATE tbl_inv_consumables SET receipt = receipt - ? WHERE inv_id = ?";
                $stmt_update = $conn->prepare($update_query);
                $stmt_update->bind_param("ii", $quantity, $inv_id);
                $stmt_update->execute();

                // Add the consumed item to the consumed items table
                $insert_query = "INSERT INTO tbl_consumed_items (inv_id, quantity, consumed_by, date_consumed) VALUES (?, ?, ?, NOW())";
                $stmt_insert = $conn->prepare($insert_query);
                $stmt_insert->bind_param("iis", $inv_id, $quantity, $borrowerName);
                $stmt_insert->execute();
            } else {