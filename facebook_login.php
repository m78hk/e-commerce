<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $uid = $_POST['uid'];
  $displayName = $_POST['displayName'];
  $email = $_POST['email'];

  // 將用戶資訊存儲在 session 中
  $_SESSION['user'] = [
    'uid' => $uid,
    'username' => $displayName,
    'email' => $email
  ];

  echo json_encode(['status' => 'success']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>