<?php
// require_once('tcpdf/tcpdf.php');
require_once "../../controllers/payments.controller.php";
require_once "../../models/payment.model.php";
require_once "../../controllers/product.controller.php";
require_once "../../models/product.model.php";
$pdo = connection::connect();

// Get the payment ID from the URL parameter
if (isset($_GET['receipt'])) {
    $paymentId = $_GET['receipt'];
} else {
    die("Payment ID not provided");
}

$select = $pdo->prepare('SELECT * FROM payments WHERE paymentid = :paymentid');
$select->bindParam(':paymentid', $paymentId, PDO::PARAM_STR);
$select->execute();
$paymentResult = $select->fetch(PDO::FETCH_ASSOC);

if (!$paymentResult) {
    die("Payment not found");
}

require "../../vendor/autoload.php";
// Create a new TCPDF instance

// Create a new TCPDF instance
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Set document information
$pdf->SetCreator('Your Company');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Payment Receipt');
$pdf->SetSubject('Payment Receipt');
$pdf->SetKeywords('Payment Receipt, TCPDF, PHP');

// Set default header data
$pdf->SetHeaderData('', 0, 'Payment Receipt', 'Payment Receipt', array(0, 0, 0), array(255, 255, 255));
$pdf->setHeaderFont(array('helvetica', '', 12));
$pdf->setFooterFont(array('helvetica', '', 10));
$pdf->setPrintFooter(false); // Disable printing footer

// Set default monospaced font
$pdf->SetDefaultMonospacedFont('helvetica');

// Set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(true, 10);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font styles
$pdf->SetFont('helvetica', 'B', 14);

// Add the title
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');

// Invoice details
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 8, 'Number: ' . $paymentResult["paymentid"], 0, 1, 'R');
$pdf->Cell(0, 8, 'Date: ' . $paymentResult["date"], 0, 1, 'R');
$pdf->Ln(10);

// Set font styles for addresses
$pdf->SetFont('helvetica', '', 10);

// Company Address
$companyAddress = '
Your Company
123 Company Street
City, State, ZIP
Country
Phone: 123-456-7890
Email: info@company.com
';
$pdf->MultiCell(0, 10, $companyAddress, 0, 'L');

// Customer Address
// $customerAddress = '
// '.$invoices['customername'].'
// '.$invoices['phone'].'
// City, State, ZIP
// Country
// ';
// $pdf->MultiCell(0, 10, $customerAddress, 0, 'R');

// Set font styles for table header
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetFillColor(2, 117, 216);
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(5);
// Set table header row
$pdf->Cell(10, 10, 'N.o', 0, 0, 'C', 1);
$pdf->Cell(40, 10, 'Payment Date', 0, 0, 'C', 1);
$pdf->Cell(30, 10, 'Total', 0, 1, 'C', 1);

// Set font styles for table data
$pdf->SetFont('helvetica', '', 10);

// Add table rows
$pdf->Cell(10, 10, '1', 0, 0, 'C', 1);
$pdf->Cell(40, 10, $paymentResult["date"], 0, 0, 'C', 1);
$pdf->Cell(30, 10, 'Ksh ' . $paymentResult["amount"], 0, 1, 'C', 1);

// Footer
$pdf->SetY(-20); // Move to the bottom of the page
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Thank you for your business', 0, 0, 'C');

// Output the PDF as a file (force download)
$pdf->Output('invoice.pdf', 'I');
?>
