<?php
include '../../function_connection.php'; // adjust path as needed

if (isset($_POST['breq_token'])) {
    $breq_token = $_POST['breq_token'];

    $stmt = $conn->prepare("SELECT breq_remarks FROM tbl_borrow_request WHERE breq_token = ?");
    $stmt->bind_param("s", $breq_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo $row['breq_remarks'];
    } else {
        echo "Reason not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No request token.";
}
?>
