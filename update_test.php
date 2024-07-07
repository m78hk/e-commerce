<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user'])) {
    echo "No user session found.";
    exit();
}

$userId = $_SESSION['user']['uid'];
$email = 'cvb@cvb.com'; // 测试数据
$phone = '123456789012';
$address = '123 Test Street';
$payment_info = 'Credit Card';
$password = password_hash('newpassword', PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('UPDATE tb_accounts SET email = ?, password = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
    if ($stmt->execute([$email, $password, $phone, $address, $payment_info, $userId])) {
        echo 'Information updated successfully.';
    } else {
        echo 'Failed to update information.';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>