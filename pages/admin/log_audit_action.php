<?php
date_default_timezone_set('Asia/Manila');
require '../../function_connection.php';
session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $empName = $_SESSION['username'];
    $action = $_POST['action'];

    if ($conn->connect_error) {
        http_response_code(500);
        echo "Database connection failed";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_auditlog (auditlog_empname, auditlog_action, auditlog_dateandtime) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $empName, $action);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>