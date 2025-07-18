<?php
session_start();
require '../../function_connection.php';

if (isset($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);
    
    $columns = "username, CONCAT(user_ln, ' ', user_fn, ' ', user_mi) AS full_name, user_level, user_status";
    
    // Check if the logged-in user is an Admin
    $is_admin_viewer = isset($_SESSION['user_level']) && $_SESSION['user_level'] === 'Admin';

    // If the viewer is an admin, also fetch the password
    if ($is_admin_viewer) {
        $columns .= ", user_password";
    }

    $query = "SELECT $columns FROM tbl_user WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row['user_status'] = ($row['user_status'] == 1) ? 'ACTIVE' : 'INACTIVE';
        // Add a flag to the response indicating if the viewer is an admin
        $row['is_admin_viewer'] = $is_admin_viewer;
        
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

$conn->close();
?>
