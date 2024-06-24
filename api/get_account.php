<?php

include 'database.php';

if (!isset($_GET['uid'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing uid']);
    exit;
}

$uid = $_GET['uid'];

$stmt = $pdo->prepare('SELECT * FROM accounts WHERE uid = ?');
$stmt->execute(['uid']);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$account) {
    http_response_code(404);
    echo json_encode(['error' => 'Account not found']);
    exit;
}

echo json_encode($account);

?>