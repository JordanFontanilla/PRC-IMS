<?php
require '../../function_connection.php';
$queryreq = "SELECT req_id, req_token FROM tbl_request";
$result = $conn->query($queryreq);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['type_id'] . "</td>";
        echo "<td>" . $row['type_name'] . "</td>";
        echo "<td>
            <button class='btn btn-primary btn-sm edit-user' data-id='" . $row['type_id'] . "'>Edit</button>    
              </td>";
        echo "</tr>";
    }
} else {echo "<tr><td colspan='5'>No requests found</td></tr>";}
$conn->close();
?>