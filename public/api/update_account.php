<?php
header('Content-Type: application/json');
session_start();
include '../database.php';

$response = array('success' => false, 'message' => '');

if (!isset($_SESSION['user'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['uid'];

    $input = json_decode(file_get_contents('php://input'), true);
    error_log(print_r($input, true)); // log the input for debugging

    if (isset($input['email'], $input['phone'], $input['address'], $input['payment_info'])) {
        $email = trim($input['email']);
        $phone = trim($input['phone']);
        $address = trim($input['address']);
        $payment_info = trim($input['payment_info']);

        try {
            if (!empty($input['password'])) {
                $password = password_hash($input['password'], PASSWORD_DEFAULT); 
                $stmt = $pdo->prepare('UPDATE tb_accounts SET email = ?, password = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
                $stmt->execute([$email, $password, $phone, $address, $payment_info, $userId]);
                error_log('Password updated.');
            } else {
                $stmt = $pdo->prepare('UPDATE tb_accounts SET email = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
                $stmt->execute([$email, $phone, $address, $payment_info, $userId]);
                error_log('Password not updated.');
            }

            error_log('Row affected: ' . $stmt->rowCount()); // Add this line for debugging

            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Information saved successfully.';
            } else {
                $response['message'] = 'No information was updated.';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Failed to save information: ' . $e->getMessage();
            error_log($response['message']); // Add this line for error logging
        }

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            error_log('Database connection successful.'); // Add this line
        } catch (\PDOException $e) {
            error_log('Connection failed: ' . $e->getMessage()); // Add this line
            echo 'Connection failed: ' . $e->getMessage();
            exit();
        }

    } else {
        $response['message'] = 'Required fields are missing.';
    }
}

echo json_encode($response);
?>