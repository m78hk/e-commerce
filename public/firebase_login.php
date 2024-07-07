<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase\Factory;

include 'database.php';

$factory = (new Factory)
    ->withServiceAccount('/Applications/XAMPP/xamppfiles/htdocs/www/abcshop-web-firebase-adminsdk-g6owf-2bf2fb76ed.json')
    ->withDatabaseUri('https://abcshop-web-default-rtdb.firebaseio.com/');

$auth = $factory->createAuth();

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['uid'], $data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$uid = $data['uid'];
$email = $data['email'];
$displayName = $data['displayName'] ?? '';

// Check if user already exists in the database
$stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE firebase_uid = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch();

if (!$user) {
    // Insert new user into the database
    $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, email, firebase_uid) VALUES (?, ?, ?)');
    $stmt->execute([$displayName, $email, $uid]);
    $user = [
        'firebase_uid' => $uid,
        'username' => $displayName,
        'email' => $email
    ];
}

$_SESSION['user'] = [
    'uid' => $user['firebase_uid'],
    'username' => $user['username'],
    'email' => $user['email']
];

echo json_encode(['success' => true]);
?>