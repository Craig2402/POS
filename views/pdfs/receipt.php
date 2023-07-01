<?php
// Include the Dompdf autoload file
require "../../vendor/autoload.php"; 

use Dompdf\Dompdf;

// Create a new Dompdf instance
$dompdf = new Dompdf();

// Load the HTML template
$html = file_get_contents('receipts.php');

// Load the HTML into Dompdf
$dompdf->loadHtml($html);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A7', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to the browser
$dompdf->stream('receipt.pdf', ['Attachment' => false]);
