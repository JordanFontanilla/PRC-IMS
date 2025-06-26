<?php
require '../../function_connection.php';

// Handle AJAX POST to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inv_id'])) {
    $inv_id = intval($_POST['inv_id']);
    $update_query = "UPDATE tbl_inv SET inv_status = 1 WHERE inv_id = ?";
    $stmt = $conn->prepare($update_query);

    if ($stmt) {
        $stmt->bind_param("i", $inv_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Status updated to Available."]);
        } else {
            echo json_encode(["success" => false, "message" => "Execution error."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Preparation error."]);
    }
    $conn->close();
    exit;
}

// Normal GET request: render table
$queryinv = "SELECT 
                tbl_inv.inv_id, 
                tbl_inv.type_id, 
                tbl_inv.inv_serialno, 
                tbl_inv.inv_propno,
                tbl_inv.inv_propname,
                tbl_inv.inv_status, 
                tbl_inv.inv_bnm, 
                tbl_type.type_name,
                tbl_inv.inv_date_added
             FROM tbl_inv
             LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id
             WHERE tbl_inv.inv_status = 6
             ORDER BY tbl_inv.inv_date_added ASC";

$result = $conn->query($queryinv);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert status to string
        switch ($row['inv_status']) {
            case 6: $status = 'Missing'; break;
            default: $status = 'Unknown';
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td>" . htmlspecialchars($status) . "</td>";
        echo "<td>
            <button class='btn btn-info btn-sm info-inv' data-toggle='modal' data-target='#viewEquipModal' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-eye'></i></button>
            <button class='btn btn-success btn-sm update-status' data-id='" . htmlspecialchars($row['inv_id']) . "'><i class='fas fa-check'></i></button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No Equipment found</td></tr>";
}

$conn->close();
?>

<script>
$(document).on('click', '.update-status', function () {
    const invId = $(this).data('id');

    Swal.fire({
        title: 'Is this item already found?',
        text: "This item will be made Available after confirming.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'pages/admin/fetch_missinginventory.php',
                type: 'POST',
                data: { inv_id: invId },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Refresh the table
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'AJAX error occurred.',
                        timer: 2500,
                        timerProgressBar: true
                    });
                }
            });
        }
    });
});
</script>
