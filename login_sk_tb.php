<?php
session_start();
include 'database.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_admin'] == 1) {
            $_SESSION['user'] = $user;
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'You are not an admin']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login sk & tb</title>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <form id="login-form">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="error" id="error-message"></div>
    </div>
</body>
<style>
    body { font-family: Arial, sans-serif; }
    .login-form {
        width: 300px;
        margin: 100px auto;
        padding: 30px;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-form h2 { text-align: center; }

    .login-form input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        box-sizing: border-box;
    }

    .logiin-form button {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    .login-form button: hover { 
        background-color: #45a049;
    }

    .error {
        color: red;
        text-align: center;
    }
</style>

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('login_sk_tb.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = 'tb_accounts_backend.php';
            } else {
                document.getElementById('error-message').textContent = data.message;
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
</head>