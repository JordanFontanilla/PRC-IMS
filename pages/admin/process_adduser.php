<?php
session_start(); // 🔥 Required to access session variables
include '../../function_connection.php'; // Include the database connection

// Get the POST data
$username = $_POST['username'];
$password = $_POST['password'];
$userlevel = $_POST['userLevel'];
$lastname = $_POST['lastName'];
$firstname = $_POST['firstName'];
$middleinitial = $_POST['middleInitial'];

// Check if the username already exists
$check_query = "SELECT * FROM tbl_user WHERE username = '$username'";
$result = $conn->query($check_query);

// If username exists, return an error
if ($result->num_rows > 0) {
    echo "Error: Username already exists!";
    exit();
}

// Hash the password before storing it in the database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Get the current date
$date_created = date("Y-m-d H:i:s");

// Insert query
$query = "INSERT INTO tbl_user (username, user_password, user_level, user_ln, user_mi, user_fn, user_status, user_date_created) 
          VALUES ('$username', '$hashed_password', '$userlevel', '$lastname', '$middleinitial', '$firstname', 1, '$date_created')";

// Execute the query
if ($conn->query($query) === TRUE) {
    // ✅ Insert into audit log
    $currentUser = $_SESSION['username'] ?? 'Unknown User'; // Default if session not set
    $action = "Created user: $username ($userlevel)";
    $auditQuery = "INSERT INTO tbl_auditlog (auditlog_empname, auditlog_action, auditlog_dateandtime)
                   VALUES ('$currentUser', '$action', NOW())";
    $conn->query($auditQuery);

    echo "success";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
