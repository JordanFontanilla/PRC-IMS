<?php
// Database configuration
$host = 'localhost';     // Hostname of the MySQL server (e.g., localhost)
$username = 'root';      // Database username
$password = '';          // Database password
$dbname = 'db_ims'; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, you can set the charset to avoid issues with special characters
$conn->set_charset("utf8");

// Connection successful, you can use $conn to interact with the database
?>
