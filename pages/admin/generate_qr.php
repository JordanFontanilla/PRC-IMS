<?php
require __DIR__ . '/../../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Check for all expected fields
$type = $_GET['type'] ?? '';
$brand = $_GET['brand'] ?? '';
$serial = $_GET['serial'] ?? '';
$propNo = $_GET['propno'] ?? '';
$propName = $_GET['propname'] ?? '';
$status = $_GET['status'] ?? '';

// Combine into one string
$qrText = 
"Type: $type\n
Brand/Model: $brand\n 
Serial No: $serial\n 
Property No: $propNo\n 
Property Name: $propName\n
Status: $status
";

// Generate QR code
$qrCode = new QrCode($qrText);
$writer = new PngWriter();
$result = $writer->write($qrCode);

// Output as PNG image
header('Content-Type: '.$result->getMimeType());
echo $result->getString();
exit;
