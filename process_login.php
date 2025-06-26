<?php
session_start();
require 'function_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['loginuser'];
    $password = $_POST['loginpass'];

    $stmt = $conn->prepare("SELECT user_id, username, user_password, user_level, user_fn, user_mi, user_ln, user_status 
                            FROM tbl_user WHERE BINARY username = ?");
    if (!$stmt) {
        error_log("SQL Error: " . $conn->error);
        echo "db_error";
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $hashed_password, $user_level, $user_fn, $user_mi, $user_ln, $user_status);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            if ($user_status == 0) {
                echo "inactive"; // ⚠️ signal to frontend that account is inactive
                exit;
            }

            $userfullname = trim($user_fn . ' ' . ($user_mi ? strtoupper(substr($user_mi, 0, 1)) . '. ' : '') . $user_ln);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['user_level'] = $user_level;
            $_SESSION['user_status'] = $user_status;
            $_SESSION['userfullname'] = $userfullname;
            $_SESSION['is_active'] = '1';

            if ($user_level === 'Admin' || $user_level === 'Employee') {
                echo "index.php"; // ✅ Successful login
            } else {
                echo "error.php"; // Redirect for unknown role
            }
        } else {
            echo "invalid"; // Invalid password
        }
    } else {
        echo "invalid"; // Invalid username
    }

    $stmt->close();
    $conn->close();
}
?>
