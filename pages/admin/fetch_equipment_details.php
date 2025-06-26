<?php
// fetch_equipment_details.php
include("../../function_connection.php");

if (isset($_POST['inv_id'])) {
    $inv_id = $_POST['inv_id'];

    // Prepare and execute your query to fetch equipment details
    $query = "SELECT * FROM tbl_inv WHERE inv_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $inv_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode([
                'type_name' => $row['type_name'], // Ensure this matches your data
                'inv_bnm' => $row['inv_bnm'],
                'inv_serialno' => $row['inv_serialno'],
                'inv_propno' => $row['inv_propno'],
                'inv_propname' => $row['inv_propname'],
                'inv_status' => $row['inv_status']
            ]);
        } else {
            echo json_encode(['error' => 'No equipment found.']);
        }
    } else {
        echo json_encode(['error' => 'Failed to execute query.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
