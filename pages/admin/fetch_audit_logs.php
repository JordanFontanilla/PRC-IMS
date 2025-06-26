<?php
require '../../function_connection.php';
$queryauditlog = "SELECT auditlog_id, auditlog_empname, auditlog_action, auditlog_dateandtime FROM tbl_auditlog ORDER BY auditlog_dateandtime ASC";
$result = $conn->query($queryauditlog);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['auditlog_id'] . "</td>";
        echo "<td>" . $row['auditlog_empname'] . "</td>";
        echo "<td>" . $row['auditlog_action'] . "</td>";
        echo "<td>" . $row['auditlog_dateandtime'] . "</td>";
        echo "</tr>";
    }
} else {echo "<tr><td colspan='5'>No Logs Found</td></tr>";}
$conn->close();
?>