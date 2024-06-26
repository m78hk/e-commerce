<?php
session_start();
include '../database.php';


if (!isset($_SESSION['user']['uid']) || !isAdmin($pdo, $_SESSION['user']['uid'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $image = file_get_contents($_FILES['image']['tmp_name']); 
        $label = $_POST['label'];
        $rating = $_POST['rating'];
        $best_seller_label = $_POST['best_seller_label'] ?? '';
        $quantity = $_POST['quantity'];

        $stmt = $pdo->prepare('INSERT INTO products (product_name, price, image, label, rating, best_seller_label, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if ($stmt->execute([$product_name, $price, $image, $label, $rating, $best_seller_label, $quantity])) {
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
        }
        exit;
    }

    
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

    
    if (isset($_POST['action']) && $_POST['action'] === 'edit_product') {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $label = $_POST['label'];
        $rating = $_POST['rating'];
        $best_seller_label = $_POST['best_seller_label'] ?? '';
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
}


$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['status' => 'success', 'products' => $products]);
?>