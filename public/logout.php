<?php
session_start();
include 'database.php';


function saveCartToDatabase($userId, $cart) {
    global $pdo;
    foreach ($cart as $item) {
        $stmt = $pdo->prepare('REPLACE INTO cart (user_id, product_id, item, quantity) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $item['product_id'], $item['product_name'], $item['quantity']]);
    }
}


if (isset($_SESSION['user']['uid'])) {
    $userId = $_SESSION['user']['uid'];
    if (isset($_SESSION['cart'])) {
        saveCartToDatabase($userId, $_SESSION['cart']);
    }
}


session_unset();
session_destroy();


header("Location: login.php");
exit();
?>
