<?php
if (!function_exists('getCartQuantity')) {
    function getCartQuantity() {
        return array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
}

if (!function_exists('saveCartToDatabase')) {
    function saveCartToDatabase($userId, $cart) {
        global $pdo;
        foreach ($cart as $item) {
            $stmt = $pdo->prepare('REPLACE INTO cart (user_id, product_id, item, quantity) VALUES (?, ?, ?, ?)');
            $stmt->execute([$userId, $item['product_id'], $item['product_name'], $item['quantity']]);
        }
    }
}
?>
