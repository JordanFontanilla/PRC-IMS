<?php
include '../../function_connection.php'; // Include the database connection
require '../../sources.php';

// Set Content-Type to application/json
header('Content-Type: application/json');  // Ensure the server returns JSON

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs from AJAX request
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_level = trim($_POST['userLevel']);
    $user_ln = trim($_POST['lastName']);
    $user_mi = trim($_POST['middleInitial']);
    $user_fn = trim($_POST['firstName']);

    // Validate required fields
    if (empty($username) || empty($password) || empty($user_level) || empty($user_ln) || empty($user_fn)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit();
    }

    // Check if username already exists
    $checkQuery = $conn->prepare("SELECT username FROM tbl_user WHERE username = ?");
    $checkQuery->bind_param("s", $username);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already exists."]);
        exit();
    }
    $checkQuery->close();

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $insertQuery = $conn->prepare("INSERT INTO tbl_user (username, user_password, user_level, user_ln, user_mi, user_fn, user_status, user_date_created) VALUES (?, ?, ?, ?, ?, ?, 1, NOW())");
    $insertQuery->bind_param("ssssss", $username, $hashed_password, $user_level, $user_ln, $user_mi, $user_fn);

    if ($insertQuery->execute()) {
        echo json_encode(["status" => "success", "message" => "User account created successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error creating user: " . $insertQuery->error]);
    }

    $insertQuery->close();
}

$conn->close();
?>
