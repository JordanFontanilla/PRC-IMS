<?php
require __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check for all expected fields
    $type = $_GET['type'] ?? '';
    $brand = $_GET['brand'] ?? '';
    $serial = $_GET['serial'] ?? '';
    $propNo = $_GET['propno'] ?? '';
    $propName = $_GET['propname'] ?? '';
    $status = $_GET['status'] ?? '';
    $date_acquired = $_GET['date_acquired'] ?? '';
    $price = $_GET['price'] ?? '';
    $condition = $_GET['condition'] ?? '';

    // Combine into one string
    $qrText = "Type: $type\n" .
            "Brand/Model: $brand\n" .
            "Serial No: $serial\n" .
            "Property No: $propNo\n" .
            "Property Name: $propName\n" .
            "Status: $status\n" .
            "Date Acquired: $date_acquired\n" .
            "Price: $price\n";

    if (!empty($condition)) {
        $qrText .= "Condition: $condition\n";
    }

    // Generate QR code with improved configuration
    $qrCode = new QrCode($qrText);
    $qrCode->setSize(300);
    $qrCode->setMargin(10);
    $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // Output as PNG image
    header('Content-Type: '.$result->getMimeType());
    echo $result->getString();

} catch (Exception $e) {
    // In case of error, return a simple error image or message
    header('Content-Type: image/png');
    $errorImg = imagecreate(300, 300);
    $bgColor = imagecolorallocate($errorImg, 255, 255, 255);
    $textColor = imagecolorallocate($errorImg, 255, 0, 0);
    imagestring($errorImg, 5, 10, 140, 'QR Error: ' . $e->getMessage(), $textColor);
    imagepng($errorImg);
    imagedestroy($errorImg);
}
exit;
