<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase\Factory;

include 'database.php';
include 'functions.php';

// Firebase initialization
$factory = (new Factory)->withServiceAccount('/Applications/XAMPP/xamppfiles/htdocs/www/abcshop-web-firebase-adminsdk-g6owf-2bf2fb76ed.json')
                        ->withDatabaseUri('https://abcshop-web-default-rtdb.firebaseio.com/');

$auth = $factory->createAuth();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['uid']) && isset($data['email'])) {
    $uid = $data['uid'];
    $email = $data['email'];
    $displayName = $data['displayName'] ?? '';

    // Check if user exists in phpMyAdmin database
    $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE firebase_uid = ?');
    $stmt->execute([$uid]);
    $user = $stmt->fetch();

    if ($user) {
        // User exists, update session
        $_SESSION['user'] = [
            'uid' => $user['firebase_uid'],
            'username' => $user['username'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'address' => $user['address'],
            'payment_info' => $user['payment_info']
        ];
        $response = ['success' => true];
    } else {
        // User does not exist, create new user in database
        $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, email, firebase_uid) VALUES (?, ?, ?)');
        $stmt->execute([$displayName, $email, $uid]);

        $_SESSION['user'] = [
            'uid' => $uid,
            'username' => $displayName,
            'email' => $email
        ];
        $response = ['success' => true];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid data received.'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>