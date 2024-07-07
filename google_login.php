<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $uid = $_POST['uid'];
  $displayName = $_POST['displayName'];
  $email = $_POST['email'];

  // 将用户信息存储在 session 中
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