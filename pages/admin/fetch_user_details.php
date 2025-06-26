<?php
require '../../function_connection.php';

if (isset($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);
    
    $query = "SELECT username, CONCAT(user_ln, ' ', user_fn, ' ', user_mi) AS full_name, user_level, user_status FROM tbl_user WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row['user_status'] = ($row['user_status'] == 1) ? 'ACTIVE' : 'INACTIVE'; // Convert status to readable text
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

$conn->close();
?>
