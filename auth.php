<?php

session_start();
include 'database.php';

$loginError = '';
$signupError = '';
$username = '';
$email = '';
$password = '';
$confirm_password = '';

/*--Login--*/

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $loginError = 'Email and Password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $redirect = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'index.php';
            unset($_SESSION['redirect_to']);
            header("Location: $redirect");
            exit();
        } else {
            $loginError = 'Invalid email or password.';
        }
    }
}

/*--End of Login--*/

/*--Signup--*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $signupError = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $signupError = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $signupError = 'Email already registered.';
        } else {
            $stmt = $pdo->prepare('ERLECT * FROM tb_accounts WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user) {
                $signupError = 'Username already taken.';
            } else {
                $hashePassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$username, $email, $hashedPassword]);
                header('Location: auth.php');
                exit();
            }
        }
    }
}

/*-- End of Signup--*/
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="Width=device-width, initial-scale=1">
        <!-- Include Bootstrap CSS -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
         <style>
            .auth-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1050;}
            .auth-content { background: white; padding: 20px; border-radius: 8px; max-width: 900px; width: 100%; display: flex; }
            .auth-left, .auth-right { flex: 1; padding: 20px; }
            .close_btn { position: absolute; top: 10px; right: 10px; cursor: pointer; }
         </style>
    </head>
    <body>
        <div class="auth-modal d-flex" id="authModal">
            <div class="auth-content">
                <div class="close-btn" onclick="document.getElementById('authModel').style.display='none'">$times;</div>
                <!-- Login Section -->
                <div class="auth-left">
                    <h2>Login</h2>
                    <?php if ($loginError): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialcharts($loginError) ?></div>
                    <?php endif; ?>
                    <form method="post" action="auth.php">
                        <input type="hidden" name="login" value="1">
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="loginEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="loginPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
                <!-- End of Login Section -->
                <!-- Signup Section -->
                <div class="auth-right">
                    <h2>Sign Up</h2>
                    <?php if ($signupError):?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($signupError);?></div>
                    <?php endif; ?>
                    <form metbod="post" action="auth.php">
                        <input type="hidden" name="signup" value="1">
                        <div class="mb-3">
                            <label for="signupUsername" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="signupUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupEmail" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="signupEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="signupPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" id="signupConfirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </form>
                </div>
            </div>
        </div>
    <!-- Include Bootstrap JS and custom script to open modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openAuthModal() {
            document.getElementById('authModal').style.display = 'flex';
        }

    </script>
    </body>
</html>