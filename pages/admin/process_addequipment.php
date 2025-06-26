<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';  // Include database connection

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $type_id = $_POST['type_id']; // Type ID from dropdown
    $brand = $_POST['brand'];     // Brand input
    $model = $_POST['model'];     // Model input
    $serialno = $_POST['serialno']; // Serial Number input
    $propertyno = $_POST['propertyno']; // Property Number input
    $propertyname = $_POST['propertyname'];
    // Combine brand and model to form inv_bnm
    $inv_bnm = $brand . ' ' . $model;

    // Set default inv_status (you can change this based on your logic, e.g., 'Available' = 1)
    $inv_status = 1;  // Assuming 1 is 'Available'


    $formatted_date = date("F d, Y h:i A");
    // Prepare SQL query to insert data into tbl_inv
    $query = "INSERT INTO tbl_inv (type_id, inv_serialno, inv_propno, inv_propname, inv_status, inv_bnm, inv_date_added) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters to the query
        $stmt->bind_param("issssss", $type_id, $serialno, $propertyno, $propertyname, $inv_status, $inv_bnm, $formatted_date);
        
        // Execute the query
        if ($stmt->execute()) {
            // If insertion is successful, return success response
            echo json_encode(['status' => 'success', 'message' => 'Equipment added successfully.']);
        } else {
            // If execution fails, return an error message
            echo json_encode(['status' => 'error', 'message' => 'Error in adding equipment.']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // If query preparation fails, return an error message
        echo json_encode(['status' => 'error', 'message' => 'Error preparing query.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // If the request method is not POST, return an error message
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
