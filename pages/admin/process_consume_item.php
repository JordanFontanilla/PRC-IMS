<?php
require '../../function_connection.php';

if (isset($_POST['items'])) {
    $items = json_decode($_POST['items'], true);

    foreach ($items as $item) {
        if ($item['origin'] === 'consumable') {
            $inv_id = $item['invId'];
            $quantity = $item['quantity'];

            // Update the quantity of the consumable item in the inventory
            $update_query = "UPDATE tbl_inv_consumables SET receipt = receipt - ? WHERE inv_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $quantity, $inv_id);
            $stmt->execute();

            // Add the consumed item to the consumed items table
            $insert_query = "INSERT INTO tbl_consumed_items (inv_id, quantity, consumed_by, date_consumed) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iis", $inv_id, $quantity, $_SESSION['username']);
            $stmt->execute();
        } else {
            // Handle non-consumable items (existing logic)
            $inv_id = $item['invId'];
            $update_query = "UPDATE tbl_inv SET inv_status = 4 WHERE inv_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("i", $inv_id);
            $stmt->execute();
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No items selected.']);
}

$conn->close();
?>