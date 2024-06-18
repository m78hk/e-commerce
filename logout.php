<?php
session_start();
include 'database.php';

// 保存购物车数据到数据库
function saveCartToDatabase($userId, $cart) {
    global $pdo;
    foreach ($cart as $item) {
        $stmt = $pdo->prepare('REPLACE INTO cart (user_id, product_id, item, quantity) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $item['product_id'], $item['product_name'], $item['quantity']]);
    }
}

// 检查用户是否已登录
if (isset($_SESSION['user']['uid'])) {
    $userId = $_SESSION['user']['uid'];
    if (isset($_SESSION['cart'])) {
        saveCartToDatabase($userId, $_SESSION['cart']);
    }
}

// 清除会话数据
session_unset();
session_destroy();

// 重定向到登录页面或主页
header("Location: login.php");
exit();
?>
