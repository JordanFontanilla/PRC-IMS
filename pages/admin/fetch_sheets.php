<?php
header('Content-Type: application/json');

$fileTmpPath = null;

if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['excelFile']['tmp_name'];
} elseif (isset($_FILES['excelFileBalance']) && $_FILES['excelFileBalance']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['excelFileBalance']['tmp_name'];
}

if ($fileTmpPath) {
    require '../../vendor/autoload.php'; // PhpSpreadsheet

    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
        $sheetNames = $spreadsheet->getSheetNames();
        echo json_encode(['sheets' => $sheetNames]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error reading Excel file: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No file uploaded or an error occurred.']);
}
?>