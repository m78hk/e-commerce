<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db = 'abcshop_mydb';
$user = 'abcshop_db_user';
$pass = 'abcshop_db';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options =[
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Database connection successful."; //
} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if (!function_exists('isAdmin')) {
    function isAdmin($pdo, $userId) {
        $stmt = $pdo->prepare('SELECT is_admin FROM tb_accounts WHERE uid = ?');
        $stmt->execute([$userId]);
        $isAdmin = $stmt->fetchColumn();
        if ($isAdmin === false) {
            // echo "Failed to fetch is_admin status for user $userId";
        } else {
            // echo "User ID $userId is_admin status is $isAdmin";
        }
        return $isAdmin == 1;
    }
}
?>
