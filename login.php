<?php
ob_start();
session_start();
include 'database.php';


$error = '';
$email = '';
$password = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Email and Password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && md5($password) === $user['password']) {
            $_SESSION['user'] = $user;
            $redirect = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'index.php';
            unset($_SESSION['redirect_to']);
            header('Location: $redirect_to');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

?>


<!--header-->
<?php include 'header.php'; ?>
<!--end of header-->

<!--body-->
<body id="body" class="vh-100 carousel slide " data-bs-ride ="carousel" style="padding-top: 104px;">
   <div class=" login container d-flex justify-content-center align-items-center min-vh-100">
     <div class="row border rounded-5 p-3 bg-white shadow box-area">
        <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #103cbe;">
            <div class="featured-image mb-3">
                <img src="./img/1.png" class="img-fluid" style="width: 250px;">
            </div>
            <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">Be Verified</p>
            <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace; ">Join experienced Designers on this platform.</small>
        </div>
        <div class="col-md-6 right-box">
            <div class="row align-items-center">
                <div class="header-text mb-4">
                    <h2>Hello,Again</h2>
                    <p>We are happy to have you back.</p>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="login.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <div class="input-group mb-5 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="formCheck">
                            <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                        </div>
                        <div class="forgot">
                            <small><a href="./forgot_password.php">Forgot Password?</a></small>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                    </div>
                    <div class="row">
                        <small>Don't have account? <a href="signup.php">Sign Up</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
   </div>
</body>
<!--end of body-->

<!--contact information, new letter scbscription ,footer-->

<?php include 'footer.php'; ?>

<!--end of contact information, new letter scbscription ,footer-->

