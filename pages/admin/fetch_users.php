<?php
require '../../function_connection.php';

$currentUsername = $_SESSION['username'] ?? '';

$queryusers = "
    SELECT username, user_ln, user_fn, user_mi, user_level, user_status, user_date_created 
    FROM tbl_user 
    WHERE username != '" . $conn->real_escape_string($currentUsername) . "'
    ORDER BY user_date_created DESC
";

$result = $conn->query($queryusers);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = ($row['user_status'] == 1) ? 'Active' : 'Inactive';
        $badgeClass = ($row['user_status'] == 1) ? 'badge-success' : 'badge-danger';
        $full_name = $row['user_ln'] . " " . $row['user_fn'] . " " . $row['user_mi'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($full_name) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_level']) . "</td>";
        echo "<td><span class='badge " . $badgeClass . "'>" . $status . "</span></td>";
        echo "<td>
                <button class='btn btn-info btn-sm view-user' data-toggle='modal' data-target='#viewUserModal' data-username='" . htmlspecialchars($row['username']) . "'>
                    <i class='fas fa-eye'></i>
                </button>
                <button class='btn btn-primary btn-sm edit-user' data-toggle='modal' data-target='#editUserModal' data-id='" . htmlspecialchars($row['username']) . "'>
                    <i class='fas fa-edit'></i>
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No users found</td></tr>";
}

$conn->close();
?>
