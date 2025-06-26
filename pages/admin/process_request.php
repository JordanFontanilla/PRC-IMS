<?php
// Include the database connection file
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';

// Check if the POST request contains selected items
if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
    // Get the selected items from the AJAX request
    $selectedItems = json_decode($_POST['selected_items'], true);
    
    // Process each selected item
    foreach ($selectedItems as $item) {
        $inv_id = $item['id'];
        
        // Update the status of the equipment (you can modify this based on your database logic)
        // For example, you can update the status of the item to "Requested" or any status you want
        $query = "UPDATE tbl_inv SET inv_status = '2' WHERE inv_id = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $inv_id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update item status.']);
            exit;
        }
    }

    // Return success response
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No items selected.']);
}

$conn->close();
?>
