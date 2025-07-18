<?php
require '../../function_connection.php';
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

if (isset($_POST['items']) && isset($_POST['consumerName'])) {
    $items = json_decode($_POST['items'], true);
    $consumerName = $_POST['consumerName'];

    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'No items provided for consumption.']);
        exit;
    }

    $conn->begin_transaction();

    try {
        foreach ($items as $item) {
            if ($item['origin'] === 'consumable') {
                $inv_id = $item['invId'];
                $quantity = intval($item['quantity']);

                // Fetch current receipt quantity to ensure enough stock
                $check_stock_query = "SELECT receipt FROM tbl_inv_consumables WHERE inv_id = ?";
                $stmt_check = $conn->prepare($check_stock_query);
                $stmt_check->bind_param("i", $inv_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();
                $current_stock = 0;
                if ($row = $result_check->fetch_assoc()) {
                    $current_stock = $row['receipt'];
                }
                $stmt_check->close();

                if ($quantity > $current_stock) {
                    throw new Exception("Not enough stock for item ID " . $inv_id . ". Requested: " . $quantity . ", Available: " . $current_stock);
                }

                // Update the quantity of the consumable item in the inventory (subtract from receipt)
                $update_query = "UPDATE tbl_inv_consumables SET receipt = receipt - ? WHERE inv_id = ?";
                $stmt_update = $conn->prepare($update_query);
                $stmt_update->bind_param("ii", $quantity, $inv_id);
                $stmt_update->execute();
                $stmt_update->close();

                // Add the consumed item to the consumed items table
                $insert_query = "INSERT INTO tbl_consumed_items (inv_id, quantity, consumed_by, date_consumed) VALUES (?, ?, ?, NOW())";
                $stmt_insert = $conn->prepare($insert_query);
                $stmt_insert->bind_param("iis", $inv_id, $quantity, $consumerName);
                $stmt_insert->execute();
                $stmt_insert->close();
            } else {
                // This script is for consumables only. If non-consumable items are passed, it's an error.
                throw new Exception("Non-consumable item found in consumable consumption request (ID: " . $item['invId'] . ").");
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Items consumed successfully.']);

    } catch (Exception $e) {
        $conn->rollback();
        error_log('Consumption transaction failed: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Consumption failed: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
}

$conn->close();
?>