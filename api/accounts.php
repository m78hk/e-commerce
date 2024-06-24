<?php

session_start();
include 'database.php';

if (!isset($_SESSION['user']['uid']) || !isAdmin($pdo, $_SESSION['user']['uid'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_account') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_info = $_POST['payment_info'];
    $role = $_POST['role'];
    $is_admin = $role === 'admin' ? 1 : 0;

    $stmt = $pdo->prepare('INSERT INTO accounts (username, password, email, phone, address, 
    payment_info, role, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

    if ($stmt->execute([$username, $password, $email, $phone, $address, $payment_info, $role, $is_admin])) {
        echo json_encode(['status' => 'success', 'message' => 'Account added']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add account']);
    }
    exit();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete_account')
    {
        $uid = $_POST['uid'];

        $stmt = $pdo->prepare('DELETE FROM accounts WHERE uid = ?');
        
        if ($stmt->execute([$uid])) {
            echo json_encode(['status' => 'success', 'message' => 'Account deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete account']);
        }
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit_account') {
        $uid = $_POST['uid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $payment_info = $_POST['payment_info'];
        $role = $_POST['role'];
        $is_admin = $role === 'admin' ? 1 : 0;

        $password = '';
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYRT);
            $stmt = $pdo->prepare('UPDATE accounts SET username = ?, password = ?, email = ?, phone = ?, 
            address = ?, payment_info = ?, role = ?, is_admin = ? WHERE uid = ?');
            $params = [$username, $password, $email, $phone, $address, $payment_info, $role, $is_admin, $uid];
        } else {
            $stmt = $pdo->prepare('UPDATE accounts SET username = ?, email = ?, phone = ?, 
            address = ?, payement_info = ?, role = ?, is_admin = ? WHERE uid = ?');
            $params = [$username, $email, $phone, $address, $payment_info, $role, $is_admin, $uid];
        }

        if ($stmt->execute($params)) {
            echo json_encode(['status' => 'success', 'message' => 'Account updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update account']);
        }
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query('SELECT * FROM accounts');
        $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($accounts);
        exit;
    }
}

?>