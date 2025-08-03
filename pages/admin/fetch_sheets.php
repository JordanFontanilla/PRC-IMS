<?php
// This function will catch fatal errors and format them as JSON.
function handle_shutdown() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
        if (ob_get_length()) {
            ob_end_clean(); // Clean any partial output
        }
        header('Content-Type: application/json');
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'error' => 'A fatal error occurred on the server.',
            'details' => [
                'type'    => $error['type'],
                'message' => $error['message'],
                'file'    => $error['file'],
                'line'    => $error['line']
            ]
        ]);
    }
}

register_shutdown_function('handle_shutdown');

ob_start(); // Start output buffering to catch any stray output

// Increase memory limit for processing large files
ini_set('memory_limit', '512M');

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$response = []; // Initialize response

try {
    if (empty($_FILES)) {
        throw new Exception('No files were uploaded. The request may have exceeded server limits (post_max_size).');
    }

    $fileTmpPath = null;
    $uploadError = UPLOAD_ERR_OK;

    if (isset($_FILES['excelFile'])) {
        $fileTmpPath = $_FILES['excelFile']['tmp_name'];
        $uploadError = $_FILES['excelFile']['error'];
    } elseif (isset($_FILES['excelFileBalance'])) {
        $fileTmpPath = $_FILES['excelFileBalance']['tmp_name'];
        $uploadError = $_FILES['excelFileBalance']['error'];
    } else {
        throw new Exception('File key (excelFile or excelFileBalance) not found in request.');
    }

    if ($uploadError !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on the server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk on the server.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        ];
        $message = $errorMessages[$uploadError] ?? 'An unknown upload error occurred.';
        throw new Exception($message);
    }

    if ($fileTmpPath && is_uploaded_file($fileTmpPath)) {
        $autoloader = __DIR__ . '/../../vendor/autoload.php';
        if (!file_exists($autoloader)) {
            throw new Exception('Server configuration error: Autoloader not found.');
        }
        require_once $autoloader;

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileTmpPath);
        $sheetNames = $reader->listWorksheetNames($fileTmpPath);
        $response['sheets'] = $sheetNames;
    } else {
        throw new Exception('No valid file path could be determined or file was not uploaded via HTTP POST.');
    }
} catch (Throwable $e) { // Catch any error or exception
    http_response_code(400);
    $response['error'] = 'Error: ' . $e->getMessage();
}

ob_end_clean(); // Clean the buffer
echo json_encode($response); // Echo the final JSON response
exit;
?>