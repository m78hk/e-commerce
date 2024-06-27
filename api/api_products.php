<?php
session_start();
include '../database.php';

header('Content-Type: application/json');


if (!isset($_SESSION['user']['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // add new product
    if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $image = file_get_contents($_FILES['image']['tmp_name']); 
        $label = $_POST['label'];
        $rating = $_POST['rating'];
        $best_seller_label = $_POST['best_seller_label'];
        $quantity = $_POST['quantity'];

        $stmt = $pdo->prepare('INSERT INTO products (product_name, price, image, label, rating, best_seller_label, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if ($stmt->execute([$product_name, $price, $image, $label, $rating, $best_seller_label, $quantity])) {
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
        }
        exit;
    }

    // delete product
    if (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $product_id = $_POST['product_id'];

        $stmt = $pdo->prepare('DELETE FROM products WHERE product_id = ?');
        if ($stmt->execute([$product_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
        }
        exit;
    }

    // edit product
    if (isset($_POST['action']) && $_POST['action'] === 'edit_product') {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $label = $_POST['label'];
        $rating = $_POST['rating'];
        $best_seller_label = $_POST['best_seller_label'];
        $quantity = $_POST['quantity'];

        if (!empty($_FILES['image']['tmp_name'])) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $stmt = $pdo->prepare('UPDATE products SET product_name = ?, price = ?, 
            image = ?, label = ?, rating = ?, best_seller_label = ?, quantity = ? WHERE product_id = ?');
            $params = [$product_name, $price, $image, $label, $rating, $best_seller_label, $quantity, $product_id];
        } else {
            $stmt = $pdo->prepare('UPDATE products SET product_name = ?, price = ?, label = ?, 
            rating = ?, best_seller_label = ?, quantity = ? WHERE product_id = ?');
            $params = [$product_name, $price, $label, $rating, $best_seller_label, $quantity, $product_id];
        }

        if ($stmt->execute($params)) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
        }
        exit;
    }

    
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    exit;
}


echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);

?>