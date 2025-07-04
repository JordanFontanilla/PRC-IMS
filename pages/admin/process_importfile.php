<?php
require '../../vendor/autoload.php';
require '../../db_connection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] == 0) {
    $filePath = $_FILES['excelFile']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = 'J'; // Read up to column J

        $conn->begin_transaction();

        $insertedCount = 0;
        $updatedCount = 0;
        $redundantCount = 0;
        $errorCount = 0;
        $errorMessages = [];
        $current_date = date('Y-m-d H:i:s');

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];

            // Assign variables from rowData
            $type_name = trim($rowData[0]);
            $inv_bnm = trim($rowData[1]);
            $inv_serialno = trim($rowData[2]);
            $inv_propno = trim($rowData[3]);
            $inv_propname = trim($rowData[4]);
            $quantity = !empty($rowData[5]) ? intval($rowData[5]) : 1;
            $date_acquired = !empty($rowData[6]) ? date('Y-m-d', strtotime($rowData[6])) : null;
            $price = !empty($rowData[7]) ? (float)$rowData[7] : 0.00;
            $status_text = strtolower(trim($rowData[8]));
            $item_type = strtolower(trim($rowData[9]));

            // Map status or default to 'Available' (1)
            $status_map = ['available' => 1, 'unavailable' => 2, 'pending' => 3, 'borrowed' => 4, 'returned' => 5, 'missing' => 6];
            $inv_status = $status_map[$status_text] ?? 1;

            // Get type_id from tbl_type
            $type_id = null;
            $type_stmt = $conn->prepare("SELECT type_id FROM tbl_type WHERE type_name = ?");
            $type_stmt->bind_param("s", $type_name);
            $type_stmt->execute();
            $type_result = $type_stmt->get_result();
            if ($type_result->num_rows > 0) {
                $type_id = $type_result->fetch_assoc()['type_id'];
            }
            $type_stmt->close();

            if (!$type_id) {
                $errorCount++;
                $errorMessages[] = "Row $row: Type '$type_name' not found.";
                continue;
            }

            if ($item_type === 'non-consumable') {
                // Handle non-consumable items in tbl_inv
                $check_stmt = $conn->prepare("SELECT inv_id FROM tbl_inv WHERE inv_serialno = ? AND inv_propno = ?");
                $check_stmt->bind_param("ss", $inv_serialno, $inv_propno);
                $check_stmt->execute();
                if ($check_stmt->get_result()->num_rows == 0) {
                    $insert_stmt = $conn->prepare("INSERT INTO tbl_inv (type_id, inv_bnm, inv_serialno, inv_propno, inv_propname, date_acquired, price, `condition`, inv_status, inv_date_added) VALUES (?, ?, ?, ?, ?, ?, ?, 'New', ?, ?)");
                    $insert_stmt->bind_param("isssssdis", $type_id, $inv_bnm, $inv_serialno, $inv_propno, $inv_propname, $date_acquired, $price, $inv_status, $current_date);
                    if ($insert_stmt->execute()) {
                        $insertedCount++;
                    } else {
                        $errorCount++;
                        $errorMessages[] = "Row $row: DB error inserting non-consumable.";
                    }
                } else {
                    $redundantCount++;
                }
            } elseif ($item_type === 'consumable') {
                // Handle consumable items in tbl_inv_consumables
                $check_stmt = $conn->prepare("SELECT inv_id, inv_quantity FROM tbl_inv_consumables WHERE inv_bnm = ? AND type_id = ?");
                $check_stmt->bind_param("si", $inv_bnm, $type_id);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                if ($result->num_rows > 0) {
                    $existing_item = $result->fetch_assoc();
                    $new_quantity = $existing_item['inv_quantity'] + $quantity;
                    $update_stmt = $conn->prepare("UPDATE tbl_inv_consumables SET inv_quantity = ? WHERE inv_id = ?");
                    $update_stmt->bind_param("ii", $new_quantity, $existing_item['inv_id']);
                    if ($update_stmt->execute()) {
                        $updatedCount++;
                    } else {
                        $errorCount++;
                        $errorMessages[] = "Row $row: DB error updating consumable quantity.";
                    }
                } else {
                    $serialno_db = !empty($inv_serialno) ? $inv_serialno : null;
                    $propertyno_db = !empty($inv_propno) ? $inv_propno : null;
                    $insert_stmt = $conn->prepare("INSERT INTO tbl_inv_consumables (type_id, inv_bnm, inv_serialno, inv_propno, inv_propname, inv_status, inv_quantity, date_acquired, price, inv_date_added) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_stmt->bind_param("issssiisds", $type_id, $inv_bnm, $serialno_db, $propertyno_db, $inv_propname, $inv_status, $quantity, $date_acquired, $price, $current_date);
                    if ($insert_stmt->execute()) {
                        $insertedCount++;
                    } else {
                        $errorCount++;
                        $errorMessages[] = "Row $row: DB error inserting consumable.";
                    }
                }
            } else {
                $errorCount++;
                $errorMessages[] = "Row $row: Invalid item type '{$item_type}'. Use 'consumable' or 'non-consumable'.";
            }
        }

        $conn->commit();
        $response = [
            'status' => 'success', 
            'inserted' => $insertedCount, 
            'updated' => $updatedCount,
            'redundant' => $redundantCount, 
            'errors' => $errorCount,
            'error_messages' => $errorMessages
        ];

    } catch (Exception $e) {
        $conn->rollback();
        $response = ['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()];
    }
} else {
    $response = ['status' => 'error', 'message' => 'No file uploaded or an error occurred during upload.'];
}

echo json_encode($response);
$conn->close();
?>
