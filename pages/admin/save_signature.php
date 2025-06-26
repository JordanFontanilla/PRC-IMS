<?php
if (isset($_POST['signature_data'])) {
    // Get the base64-encoded signature data
    $signatureData = $_POST['signature_data'];

    // Remove the prefix 'data:image/png;base64,' from the base64 string
    $signatureData = str_replace('data:image/png;base64,', '', $signatureData);

    // Decode the base64 string to binary data
    $signatureImage = base64_decode($signatureData);

    // Define the directory where you want to store the signature images
    $signatureDirectory = 'signatures/';
    if (!is_dir($signatureDirectory)) {
        mkdir($signatureDirectory, 0777, true);
    }

    // Create a unique file name for the signature
    $fileName = 'signature_' . time() . '.png';
    $filePath = $signatureDirectory . $fileName;

    // Save the binary data to a file
    file_put_contents($filePath, $signatureImage);

    // Return a response indicating success
    echo json_encode(['success' => true, 'message' => 'Signature saved successfully.', 'file' => $filePath]);
} else {
    echo json_encode(['success' => false, 'message' => 'No signature data received.']);
}
?>
