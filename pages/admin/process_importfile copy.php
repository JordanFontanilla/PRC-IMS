<?php
header('Content-Type: application/json');

// Check if file is uploaded
if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === 0) {
    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];
    $fileSize = $_FILES['excelFile']['size'];
    $fileType = $_FILES['excelFile']['type'];

    // Validate file extension (Excel file)
    $allowedExts = ['xlsx', 'xls'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (in_array($fileExt, $allowedExts)) {
        require '../../vendor/autoload.php'; // Include PhpSpreadsheet library
        require '../../function_connection.php'; // Database connection file

        // Load the Excel file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Initialize counters
        $insertedCount = 0;
        $redundantCount = 0;

        try {
            // Skip the first row (header)
            array_shift($rows);

            foreach ($rows as $row) {
                if (count($row) < 7) {
                    continue; // Skip if not enough columns
                }

                // Retrieve and sanitize data
                $type_id = trim($row[0]);
                $inv_serialno = trim($row[1]);
                $inv_propno = trim($row[2]);
                $inv_propname = trim($row[3]);
                $inv_bnm = trim($row[4]);
                $end_user = trim($row[5]);
                $accounted_to = trim($row[6]);

                $inv_status = 1;
                $inv_quantity = 1;
                $formattedDate = date('F j, Y h:i A');

                // Skip rows with missing required fields
                if (empty($inv_serialno) || empty($inv_propno) || empty($inv_propname)) {
                    continue;
                }

                // Check for duplicates
                $checkQuery = "SELECT COUNT(*) FROM tbl_inv WHERE inv_serialno = ? AND inv_propno = ? AND inv_propname = ?";
                $stmtCheck = $conn->prepare($checkQuery);
                $stmtCheck->bind_param("sss", $inv_serialno, $inv_propno, $inv_propname);
                $stmtCheck->execute();
                $stmtCheck->bind_result($rowCount);
                $stmtCheck->fetch();    
                $stmtCheck->close();

                if ($rowCount > 0) {
                    $redundantCount++;
                    continue;
                }

                // Insert new data including end_user and accounted_to
                $query = "INSERT INTO tbl_inv 
                          (type_id, inv_serialno, inv_propno, inv_propname, inv_bnm, inv_status, inv_quantity, inv_date_added, end_user, accounted_to)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssssiisss", $type_id, $inv_serialno, $inv_propno, $inv_propname, $inv_bnm, $inv_status, $inv_quantity, $formattedDate, $end_user, $accounted_to);
                $stmt->execute();
                $stmt->close();

                $insertedCount++;
            }

            $conn->close();
            echo json_encode(['inserted' => $insertedCount, 'redundant' => $redundantCount]);
        } catch (Exception $e) {
            echo json_encode(['error' => "Error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => "Invalid file type. Please upload an Excel file."]);
    }
} else {
    echo json_encode(['error' => "No file uploaded or error uploading file."]);
}
?>
