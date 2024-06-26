<?php
session_start();
include '../database.php';
include '../functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        if (isset($_POST['quantity'])) {
            $quantity = $_POST['quantity'];
            updateCart($product_id, $quantity);
        } elseif (isset($_POST['remove']) && $_POST['remove'] === 'true') {
            removeFromCart($product_id);
        } else {
            addToCart($product_id);
        }
    }
}

function updateCart($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            $_SESSION['cart_quantity'] = getCartQuantity();
            echo json_encode(['status' => 'success', 'cartQuantity' => $_SESSION['cart_quantity']]);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
}

function removeFromCart($product_id) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart_quantity'] = getCartQuantity();
            echo json_encode(['status' => 'success', 'cartQuantity' => $_SESSION['cart_quantity']]);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
}

function addToCart($product_id) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id) {
            echo json_encode(['status' => 'error', 'message' => 'Product already in cart']);
            return;
        }
    }

    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $_SESSION['cart'][] = [
            'product_id' => $product['product_id'],
            'product_name' => $product['product_name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];
        $_SESSION['cart_quantity'] = getCartQuantity();
        echo json_encode(['status' => 'success', 'cartQuantity' => $_SESSION['cart_quantity']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }
}
?>