<?php
// Include database connection
require '../../function_connection.php';

header('Content-Type: application/json');

// If searching for recommendations (AJAX dropdown)
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchSql = "%" . $search . "%";
    
    $query = "
        SELECT 
            i.inv_id, 
            i.inv_serialno, 
            i.inv_propno, 
            i.inv_propname, 
            i.inv_bnm, 
            t.type_name,
            'non_consumable' as origin
        FROM tbl_inv i
        LEFT JOIN tbl_type t ON i.type_id = t.type_id 
        WHERE i.inv_status = 1
            AND (
                i.inv_serialno LIKE ? 
                OR i.inv_bnm LIKE ? 
                OR i.inv_propname LIKE ? 
                OR t.type_name LIKE ?
            )
        ORDER BY inv_bnm ASC
        LIMIT 15
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $searchSql, $searchSql, $searchSql, $searchSql);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    echo json_encode($items);
    $stmt->close();
    $conn->close();
    exit;
}

// If fetching by ID for dropdown selection
if (isset($_GET['inv_id']) && isset($_GET['origin'])) {
    $inv_id = intval($_GET['inv_id']);
    $origin = $_GET['origin'];
    $items = [];

    if ($origin === 'non_consumable') {
        $stmt = $conn->prepare("SELECT 
                    i.inv_id, 
                    i.inv_serialno, 
                    i.inv_propno, 
                    i.inv_propname, 
                    i.inv_bnm, 
                    t.type_name,
                    'non_consumable' as origin
                FROM tbl_inv i
                LEFT JOIN tbl_type t ON i.type_id = t.type_id 
                WHERE i.inv_id = ? 
                LIMIT 1");
        $stmt->bind_param('i', $inv_id);
    } else {
        echo json_encode([]);
        $conn->close();
        exit;
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    $stmt->close();
    
    echo json_encode($items);
    $conn->close();
    exit;
}

// Default: output as table rows for manual add modal
$query = "
    SELECT 
        i.inv_id, i.inv_serialno, i.inv_propno, i.inv_propname, i.inv_bnm, t.type_name, 'non_consumable' as origin
    FROM tbl_inv i
    LEFT JOIN tbl_type t ON i.type_id = t.type_id 
    WHERE i.inv_status = 1
";

$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['inv_bnm']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_serialno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propno']) . "</td>";
        echo "<td>" . htmlspecialchars($row['inv_propname']) . "</td>";
        echo "<td class='text-center align-middle'>
        <div class='d-inline-flex justify-content-center align-items-center'>
            <i class='fa fa-plus fa-sm toggle-icon bg-success text-white rounded-circle p-2' data-id='" . htmlspecialchars($row['inv_id']) . "' data-origin='" . htmlspecialchars($row['origin']) . "' data-state='plus'></i>
        </div>
      </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No available items found</td></tr>";
}

$conn->close();
?>