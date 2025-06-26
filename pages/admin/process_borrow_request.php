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
            $query = "INSERT INTO tbl_borrow_request_items 
                        (inv_id, breq_token) 
                        VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $item['invId'], $breqToken);  // Removed 'br_item_id' from the statement
            $stmt->execute();
  
            
            // Update inventory status
            $query = "UPDATE tbl_inv SET inv_status = ? WHERE inv_id = ?";
            $stmt = $conn->prepare($query);
            $status = 3; // Assuming 3 means "borrowed"
            $stmt->bind_param("ii", $status, $item['invId']);
            $stmt->execute();
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Request processed successfully',
            'breqId' => $breqId,
            'breqToken' => $breqToken,
            'signatureFile' => $signatureFilePath
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        error_log('Error: ' . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Failed to process borrow request: ' . $e->getMessage()
        ]);
    }
} else {
    $missingFields = [];
    if (empty($_POST['borrowerName'])) $missingFields[] = 'borrowerName';
    if (empty($_POST['borrowerRemark'])) $missingFields[] = 'borrowerRemark';
    if (empty($_POST['signatureData'])) $missingFields[] = 'signatureData';
    if (empty($_POST['items'])) $missingFields[] = 'items';

    error_log('Missing fields: ' . implode(', ', $missingFields));

    echo json_encode([
        'success' => false,
        'message' => 'Missing required data: ' . implode(', ', $missingFields)
    ]);
}

$conn->close();
?>