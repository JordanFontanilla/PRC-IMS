<?php
require '../../function_connection.php';

// Base query for non-consumable items
$query_non_consumable = "SELECT 
    inv_id, 'non-consumable' as item_type, type_name, inv_bnm, inv_serialno, inv_propno, inv_propname, inv_status
    FROM tbl_inv LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id
    WHERE inv_status = 6";

// Base query for consumable items
$query_consumable = "SELECT 
    inv_id, 'consumable' as item_type, type_name, inv_bnm, inv_serialno, inv_propno, inv_propname, inv_status
    FROM tbl_inv_consumables LEFT JOIN tbl_type ON tbl_inv_consumables.type_id = tbl_type.type_id
    WHERE inv_status = 6";

// Union the two queries
$query_union = "($query_non_consumable) UNION ALL ($query_consumable) ORDER BY type_name ASC";

$result = $conn->query($query_union);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = 'Missing';

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['type_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td>" . htmlspecialchars($status) . "</td>";
        echo "<td>
            <button class='btn btn-success btn-sm update-status' data-id='" . htmlspecialchars($row['inv_id']) . "' data-item-type='" . htmlspecialchars($row['item_type']) . "' title='Mark as Found'><i class='fas fa-check'></i></button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No Missing Equipment found</td></tr>";
}

$conn->close();
?>

<script>
$(document).on('click', '.update-status', function () {
    const invId = $(this).data('id');
    const itemType = $(this).data('item-type');

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
                data: { inv_id: invId, item_type: itemType },
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
