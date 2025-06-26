<?php
// Include the TCPDF library
require_once __DIR__ . '/../../vendor/autoload.php';
require '../../function_connection.php';
class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font for page number and other texts
        $this->SetFont('times', '', 8);

        // Get the current page number and total pages
        $currentPage = $this->getAliasNumPage();
        $totalPages = $this->getAliasNbPages();
        
        // Calculate the width of the page to place the footer elements
        $pageWidth = $this->getPageWidth();
        
        // Add the page number in format "1/4, 2/4, ..." aligned to the right
        $this->Cell(0, 10, $currentPage . '/' . $totalPages, 0, false, 'R');
        
        // Add the "Inventory Management System '© 2025" text to the left
        $this->SetX(10); // Position at 10 mm from the left
        $this->Cell(0, 10, "Inventory Management System ©2025 - " . date("Y"), 0, 0, 'L');

        
        // Check if it's the last page or only one page
        if ($this->getPage() == $this->getNumPages()) {
            // Add the special footer message, centered
            // Set font for the message
            $this->SetFont('Helvetica', 'B', 8);
            
            // Calculate the width of the message
            $footerMessage = "This is a document of the PRC-CAR and must not be spread.";
            $messageWidth = $this->GetStringWidth($footerMessage); // Get width of the message
            $xPosition = ($pageWidth - $messageWidth) / 2; // Center the message
            
            // Set the X position to center the message
            $this->SetX($xPosition);
            
            // Add the custom footer message centered
            $this->Cell($messageWidth, 10, $footerMessage, 0, 1, 'C');
        }
    }
}






// Create instance of TCPDF class
$pdf = new MYPDF();

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

// Set margins: 0.25 inches (6.35 mm) on all sides
$pdf->SetMargins(6.35, 12.75, 6.35); // Left, Top, Right margins

$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

// Set page orientation to landscape
$pdf->SetPageOrientation('L'); // 'L' for landscape

// Add a page
$pdf->AddPage();

// Add logo image above the title
$logoPath = __DIR__ . '/../../img/logo.png'; // Path to the logo image
$logoWidth = 25; // Set logo width
$logoHeight = 0; // Auto scale height

// Center the logo horizontally on the page
$logoX = ($pdf->getPageWidth() - $logoWidth) / 2; // Calculate the X position to center the logo
$pdf->Image($logoPath, $logoX, 6, $logoWidth, $logoHeight, '', '', '', true, 150, '', false, false, 0);

// Move down after the logo to avoid overlap with the text
$pdf->Ln(20); // Move down by 30 mm (adjust as needed for spacing)

// Set title
$typeName = 'Inventory'; // Default title

// Check if "All Types" or "All Statuses" is selected and update title accordingly
if (empty($typeFilter)) {
    $typeName = 'Inventory'; // If no type is selected, use "Inventory"
} else {
    // If a type filter is provided, fetch the corresponding type name
    
    $stmt = $conn->prepare("SELECT type_name FROM tbl_type WHERE type_id = ?");
    $stmt->bind_param("i", $typeFilter);
    $stmt->execute();
    $stmt->bind_result($fetchedTypeName);
    if ($stmt->fetch()) {
        $typeName = $fetchedTypeName;
    }
    $stmt->close();
}

// Determine the date format
$dateStr = date('F j, Y'); // Example: April 7, 2025

// Set title
$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0, 5, 'Professional Regulation Commission - Cordillera Administrative Region', 0, 1, 'C'); // Centered title

// Set subheading font
$pdf->SetFont('Times', '', 12);
$pdf->Cell(0, 10, $typeName . ' List Report of ' . $dateStr, 0, 1, 'C');
$pdf->Ln(5); // Line break

// Adjusted table column widths to fill the available space
$pdf->SetFont('Times', 'B', 12);

// Set border color for table header to black
$pdf->SetDrawColor(0, 0, 0); // Black color for the table header borders

// Table header centered without vertical lines between, only outer borders
$pdf->Cell(40, 5, 'Type', 'LTB', 0, 'C');
$pdf->Cell(60, 5, 'Brand/Model', 'TB', 0, 'C');
$pdf->Cell(55, 5, 'Serial No.', 'TB', 0, 'C');
$pdf->Cell(70, 5, 'Property No.', 'TB', 0, 'C');
$pdf->Cell(60, 5, 'Division/Section', 'RTB', 1, 'C'); // Close row

// $pdf->Cell(25, 5, '', 0, 1, 'C'); // Removes the Status column from header
//       // Right, Top, Bottom only

// Fetch inventory data (same query as in fetch_inventory.php)
$queryinv = "SELECT 
                tbl_inv.inv_id, 
                tbl_inv.type_id, 
                tbl_inv.inv_serialno, 
                tbl_inv.inv_propno,
                tbl_inv.inv_propname,
                tbl_inv.inv_status, 
                tbl_inv.inv_bnm, 
                tbl_type.type_name,
                tbl_inv.inv_date_added
             FROM tbl_inv 
             LEFT JOIN tbl_type ON tbl_inv.type_id = tbl_type.type_id
             WHERE 1=1"; // Safe base

// Append filters if provided
if (!empty($typeFilter)) {
    $queryinv .= " AND tbl_inv.type_id = '" . $conn->real_escape_string($typeFilter) . "'";
}
if (!empty($statusFilter)) {
    $queryinv .= " AND tbl_inv.inv_status = '" . $conn->real_escape_string($statusFilter) . "'";
}

$queryinv .= " ORDER BY tbl_type.type_name ASC, tbl_inv.inv_bnm ASC, tbl_inv.inv_propname ASC";

$result = $conn->query($queryinv);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

// Set border color to gray for data rows
$pdf->SetDrawColor(169, 169, 169); // RGB for grayish color

// Add table data with adjusted widths, centered
$pdf->SetFont('Times', '', 10);



// Set border color to gray for data rows
$pdf->SetDrawColor(169, 169, 169); // RGB for grayish color

// Add table data with adjusted widths, centered
$pdf->SetFont('Helvetica', '', 10);

// Initialize a counter to alternate row colors
$rowCount = 0;

// Loop through the data rows
while ($row = $result->fetch_assoc()) {
    // Map the inv_status to its corresponding string
    switch ($row['inv_status']) {
        case 1:
            $status = 'Available';
            break;
        case 2:
            $status = 'Unavailable';
            break;
        case 3:
            $status = 'Pending';
            break;
        case 4:
            $status = 'Borrowed';
            break;
        case 5:
            $status = 'Returned';
            break;
        case 6:
            $status = 'Missing';
            break;
        default:
            $status = 'Unknown';
    }

    // Alternate row background color (light gray for even rows, white for odd rows)
    if ($rowCount % 2 == 0) {
        // Light gray color for even rows
        $pdf->SetFillColor(240, 240, 240); // RGB for light gray
    } else {
        // White color for odd rows
        $pdf->SetFillColor(255, 255, 255); // White color
    }

    // Add data rows to the table with left and right borders removed
    // Set 'LR' for no left and right borders (top and bottom remain)
    $pdf->Cell(40, 6, htmlspecialchars($row['type_name']), 'LTB', 0, 'C', true);
    $pdf->Cell(60, 6, htmlspecialchars($row['inv_bnm']), 'TB', 0, 'C', true);
    $pdf->Cell(55, 6, htmlspecialchars($row['inv_serialno']), 'TB', 0, 'C', true);
    $pdf->Cell(70, 6, htmlspecialchars($row['inv_propno']), 'TB', 0, 'C', true);
    $pdf->Cell(60, 6, htmlspecialchars($row['inv_propname']), 'RTB', 1, 'C', true); // End of row
    
    // $pdf->Cell(25, 6, htmlspecialchars($status), 'RTB', 1, 'C', true); // Top border only

    // Increment row count to alternate the color for the next row
    $rowCount++;
}


$conn->close();

// Output PDF (view in browser)
$pdf->Output('inventory_report.pdf', 'I'); // 'I' means display in browser, 'D' to download
?>
