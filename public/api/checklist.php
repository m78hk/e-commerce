<?php
session_start();
include '../database.php';
include '../functions.php';

if (!isset($_SESSION['user']['uid'])) {
    http_response_code(403);    
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user']['uid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? trim($_POST['product_id']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($product_id && $action === 'add_to_checklist') {
        $stmt = $pdo->prepare('INSERT INTO checklist (user_id, product_id) VALUES (?, ?)');
        if ($stmt->execute([$user_id, $product_id])) {
            http_response_code(200);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product to checklist']);
        }
    } elseif ($product_id && $action === 'remove_from_checklist') {
        $stmt = $pdo->prepare('DELETE FROM checklist WHERE product_id = ? AND user_id = ?');
        if ($stmt->execute([$product_id, $user_id])) {
            http_response_code(200);
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove product from checklist']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
}
?>