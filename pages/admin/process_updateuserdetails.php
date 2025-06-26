<?php
require_once '../../function_connection.php';
session_start(); // Start session to access current user

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $new_user_level = trim($_POST['user_level']);
    $new_user_status = trim($_POST['user_status']);
    $new_last_name = trim($_POST['last_name']);
    $new_first_name = trim($_POST['first_name']);
    $new_middle_initial = trim($_POST['middle_initial']);

    // Validate required fields
    if (empty($new_user_level) || empty($new_last_name) || empty($new_first_name)) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
        exit;
    }

    // Get the original data before updating
    $select = $conn->prepare("SELECT user_level, user_status, user_ln, user_fn, user_mi FROM tbl_user WHERE username = ?");
    $select->bind_param("s", $username);
    $select->execute();
    $select->bind_result($old_user_level, $old_user_status, $old_ln, $old_fn, $old_mi);
    $select->fetch();
    $select->close();

    // Update user details in the database
    $update = $conn->prepare("UPDATE tbl_user SET 
                                user_level = ?, 
                                user_status = ?, 
                                user_ln = ?, 
                                user_fn = ?, 
                                user_mi = ? 
                              WHERE username = ?");
    $update->bind_param("sissss", $new_user_level, $new_user_status, $new_last_name, $new_first_name, $new_middle_initial, $username);

    if ($update->execute()) {
        $doer = $_SESSION['userfullname'] ?? $_SESSION['username'] ?? 'Unknown User';
        $fullname = trim($new_first_name . ' ' . ($new_middle_initial ? strtoupper($new_middle_initial[0]) . '. ' : '') . $new_last_name);
        $logs = [];

        // Only log level change
        if ($new_user_level !== $old_user_level) {
            $logs[] = "Updated User Level of $fullname : to $new_user_level";
        }

        // Only log status change
        if ($new_user_status != $old_user_status) {
            $statusText = ($new_user_status == 1) ? "Active" : "Inactive";
            $logs[] = "Updated Status of $fullname : to $statusText";
        }

        // Always log name update (optional: only if name changed)
        if (
            $old_fn !== $new_first_name || 
            $old_ln !== $new_last_name || 
            $old_mi !== $new_middle_initial
        ) {
            $logs[] = "Updated Username: $username to $fullname ($new_user_level)";
        }

        // Insert audit logs
        foreach ($logs as $action) {
            $audit = $conn->prepare("INSERT INTO tbl_auditlog (auditlog_empname, auditlog_action, auditlog_dateandtime)
                                     VALUES (?, ?, NOW())");
            $audit->bind_param("ss", $doer, $action);
            $audit->execute();
            $audit->close();
        }

        echo json_encode(['success' => true, 'message' => 'User details updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error updating user details.']);
    }

    $update->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
