<?php
session_start();
include 'database.php';
require('vendor/autoload.php'); 

use FPDF\FPDF;

if (!isset($_SESSION['user'])) {
    echo 'User not logged in.';
    exit;
}

$userId = $_SESSION['user']['uid'];
$username = $_SESSION['user']['username'] ?? '';
$address = $_SESSION['user']['address'] ?? '';
$phone = $_SESSION['user']['phone'] ?? '';
$payment_info = $_SESSION['user']['payment_info'] ?? '';
$payment_method = $_SESSION['user']['payment_method'] ?? '';
$subtotal = $_SESSION['subtotal'] ?? 0;
$tax = $_SESSION['tax'] ?? 0;
$shipping = $_SESSION['shipping'] ?? 0;
$total = $_SESSION['total'] ?? 0;
$cart = $_SESSION['cart'] ?? [];

// retrieve product information from the database
$products = [];
$stmt = $pdo->query('SELECT * FROM products');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$row['product_id']] = $row;
}

// create a new PDF instance
$pdf = new \FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// create the header
$pdf->Cell(0, 10, 'ABC Shopping Mall Order Receipt', 0, 1, 'C');

// retrieve user information
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Username: ' . $username, 0, 1);
$pdf->Cell(0, 10, 'Phone: ' . $phone, 0, 1);
$pdf->Cell(0, 10, 'Address: ' . $address, 0, 1);
$pdf->Cell(0, 10, 'Payment Method: ' . $payment_method, 0, 1);

// retrieve order information
$pdf->Cell(0, 10, 'Subtotal: $' . $subtotal, 0, 1);
$pdf->Cell(0, 10, 'Tax: $' . $tax, 0, 1);
$pdf->Cell(0, 10, 'Shipping: $' . $shipping, 0, 1);
$pdf->Cell(0, 10, 'Total: $' . $total, 0, 1);

// retrieve cart items
$pdf->Cell(0, 10, 'Cart Items:', 0, 1);
foreach ($cart as $item) {
    // check if the product exists in the database
    if (isset($products[$item['product_id']])) {
        $product = $products[$item['product_id']];
        $pdf->Cell(0, 10, $product['product_name'] . ' - Quantity: ' . $item['quantity'] . ' - Price: $' . $product['price'], 0, 1);
    } else {
        $pdf->Cell(0, 10, 'Product details not found', 0, 1);
    }
}

$pdfFileName = 'ABC_Shopping_Mall_Order_Receipt.pdf';

// Output the PDF to the browser
$pdf->Output('I', $pdfFileName);

// Output the PDF to the new tab
echo "<script>window.open('$pdfFileName', '_blank');</script>";
?>