<?php
include '../../function_connection.php'; // Include the database connection

header('Content-Type: application/json');

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typeName = trim($_POST['typeName']);
    $typeOrigin = trim($_POST['typeOrigin']);

    if (!empty($typeName)) {
        // Check if the type name already exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_type WHERE type_name = ?");
        $checkStmt->bind_param("s", $typeName);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $response['status'] = 'exists';
        } else {
            // Insert new type name and origin
            $stmt = $conn->prepare("INSERT INTO tbl_type (type_name, type_origin) VALUES (?, ?)");
            $stmt->bind_param("ss", $typeName, $typeOrigin);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['id'] = $stmt->insert_id;
                $response['name'] = $typeName;
                $response['origin'] = $typeOrigin;
            } else {
                $response['status'] = 'error';
            }

            $stmt->close();
        }
    } else {
        $response['status'] = 'empty';
    }
} else {
    $response['status'] = 'invalid_request';
}

$conn->close();
echo json_encode($response);
?>
