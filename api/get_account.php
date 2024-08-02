<?php
header('Content-Type: application/json');
include '../database.php';

try {
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'list_accounts':
        $stmt = $pdo->query('SELECT * FROM tb_accounts');
        $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['accounts' => $accounts]);
        break;

    case 'get_account':
        $uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
        $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE uid = ?');
        $stmt->execute([$uid]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['account' => $account]);
        break;

    case 'add_account':
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $payment_method = $_POST['payment_method'];
        $credit_card = $_POST['credit_card'];
        $role = $_POST['role'];
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, password, email, phone, address, payment_method, credit_card, role, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$username, $password, $email, $phone, $address, $payment_method, $credit_card, $role, $is_admin]);
        echo json_encode(['message' => 'Account added successfully']);
        break;

    case 'edit_account':
        $uid = $_POST['uid'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $payment_method = $_POST['payment_method'];
        $credit_card = $_POST['credit_card'];
        $role = $_POST['role'];
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        if ($password) {
            $stmt = $pdo->prepare('UPDATE tb_accounts SET username = ?, password = ?, email = ?, phone = ?, address = ?, payment_method = ?, credit_card = ?, role = ?, is_admin = ? WHERE uid = ?');
            $stmt->execute([$username, $password, $email, $phone, $address, $payment_method, $credit_card, $role, $is_admin, $uid]);
        } else {
            $stmt = $pdo->prepare('UPDATE tb_accounts SET username = ?, email = ?, phone = ?, address = ?, payment_method = ?, credit_card = ?, role = ?, is_admin = ? WHERE uid = ?');
            $stmt->execute([$username, $email, $phone, $address, $payment_method, $credit_card, $role, $is_admin, $uid]);
        }

        echo json_encode(['message' => 'Account updated successfully']);
        break;

    case 'delete_account':
        $uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
        $stmt = $pdo->prepare('DELETE FROM tb_accounts WHERE uid = ?');
        $stmt->execute([$uid]);
        echo json_encode(['message' => 'Account deleted successfully']);
        break;

    default:
        echo json_encode(['message' => 'Invalid action']);
        break;  }
} catch (Exception $e) {
    // error_log
    echo json_encode(['error' => $e->getMessage()]);
}
?>