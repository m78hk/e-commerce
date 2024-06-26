<?php
session_start();
include '../database.php';

// 確認用戶已經登錄並且是管理員
if (!isset($_SESSION['user']['uid']) || $_SESSION['user']['is_admin'] != 1) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit();
}

// 確認請求方法並執行相應操作
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // 獲取所有帳戶
        try {
            $stmt = $pdo->query('SELECT * FROM tb_accounts');
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($accounts);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        break;
    
    case 'POST':
        // 添加或編輯帳戶
        $action = $_POST['action'];

        if ($action == 'add_account') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];
            $role = $_POST['role'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $payment_info = $_POST['payment_info'];
            $is_admin = isset($_POST['is_admin']) ? 1 : 0;

            $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, password, email, phone, address, payment_info, role, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            if ($stmt->execute([$username, $password, $email, $phone, $address, $payment_info, $role, $is_admin])) {
                echo json_encode(['status' => 'success', 'message' => 'Account added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add account']);
            }
        } elseif ($action == 'edit_account') {
            $uid = $_POST['uid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $payment_info = $_POST['payment_info'];
            $is_admin = isset($_POST['is_admin']) ? 1 : 0;

            $stmt = $pdo->prepare('UPDATE tb_accounts SET username = ?, email = ?, role = ?, phone = ?, address = ?, payment_info = ?, is_admin = ? WHERE uid = ?');
            $params = [$username, $email, $role, $phone, $address, $payment_info, $is_admin, $uid];

            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE tb_accounts SET username = ?, password = ?, email = ?, role = ?, phone = ?, address = ?, payment_info = ?, is_admin = ? WHERE uid = ?');
                $params = [$username, $password, $email, $role, $phone, $address, $payment_info, $is_admin, $uid];
            }

            if ($stmt->execute($params)) {
                echo json_encode(['status' => 'success', 'message' => 'Account updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update account']);
            }
        }
        break;

    case 'DELETE':
        // 刪除帳戶
        parse_str(file_get_contents("php://input"), $_DELETE);
        $uid = $_DELETE['uid'];

        $stmt = $pdo->prepare('DELETE FROM tb_accounts WHERE uid = ?');
        if ($stmt->execute([$uid])) {
            echo json_encode(['status' => 'success', 'message' => 'Account deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete account']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
        break;
}
?>