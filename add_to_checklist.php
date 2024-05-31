<?php
 session_start();

 include 'stock.php';

 if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    $productFound = false;
    foreach ($products as $product) {
        if ($product['product_id'] == $productId) {
            $productFound = true;
            break;
        }
    }

    if ($productFound) {
        if (!isset($_SESSION['checklist'])) {
            $_SESSION['checklist'] = [];
        }

        $_SESSION['checklist'][] = $product;

        echo json_encode(['status' => 'success', 'message' => 'Product added to checklist']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
    }
 } else {
    echo json_encode(['status' => 'error', 'message' => 'No product ID provided']);
 }
 ?>
