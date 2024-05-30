<?php
ob_start();

/*--header--*/
include 'header.php'; 
/*--end of header--*/



$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $users = [
        ['email' => 'test@example.com', 'password' => password_hash('password123', PASSWORD_DEFAULT)],

    ];

    $userFound = false;

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = 1;
            $_SESSION['email'] = $user['email'];
            $userFound = true;

            error_log('User found, redirecting to index.php...');
            ob_end_clean();
            header('Location: index.php');
            exit();
        }
    }

    if (!$userFound) {
        error_log('User not found or invalid password.');
        $error = 'Invalid email or password';
    }
}

ob_end_flush();

?>

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
                <div class="input-group mb-3">
                    <input type="text" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email address" required>
                </div>
                <div class="input-group mb-1">
                    <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" required>
                </div>
                <div class="input-group mb-5 d-flex justify-content-between">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="formCheck">
                        <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                    </div>
                    <div class="forgot">
                        <small><a href="./forgot password.php">Forgot Password?</a></small>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                </div>
                <div class="row">
                    <small>Don't have account? <a href="singup.php">Sign Up</a></small>
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

