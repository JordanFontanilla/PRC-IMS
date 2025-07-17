<?php
header('Content-Type: application/json');
require '../../function_connection.php'; // Adjust path as needed

try {
    $origin = isset($_GET['origin']) ? $_GET['origin'] : '';

    // Base query
    $query = "SELECT type_id, type_name FROM tbl_type";

    // Add origin filter if provided
    if ($origin === 'Consumable') {
        $query .= " WHERE type_origin = 'Consumable'";
    } elseif ($origin === 'Non-Consumable') {
        $query .= " WHERE type_origin = 'Non-Consumable'";
    }

    $query .= " ORDER BY type_name ASC";
    $result = $conn->query($query);
    
    if ($result) {
        $types = [];
        while ($row = $result->fetch_assoc()) {
            $types[] = [
                'type_id' => $row['type_id'],
                'type_name' => $row['type_name']
            ];
        }
        
        echo json_encode([
            'status' => 'success',
            'types' => $types
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch equipment types: ' . $conn->error
        ]);
    }
    
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>