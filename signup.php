<?php
session_start();
include 'database.php';

$error = '';
$username = '';
$email = '';
$password = '';
$confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $error = 'Email already registered.';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user) {
                $error = 'Username already taken.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$username, $email, $hashedPassword]);
                header('Location: login.php');
                exit();
            }
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
                       <h2>SignUp</h2>
                       <p>We are happy to you have join.</p>
                   </div>
                   <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                   <?php endif; ?>
                   <form method="post" action="signup.php">
                       <div class="input-group mb-3">
                           <input type="text" name="username" class="form-control form-control-lg bg-light fs-6" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                       </div>
                       <div class="input-group mb-3">
                           <input type="text" name="email"class="form-control form-control-lg bg-light fs-6" placeholder="Email address" value="<?php echo htmlspecialchars($email); ?>" required>
                       </div>
                       <div class="input-group mb-1">
                           <input type="password" name="password"class="form-control form-control-lg bg-light fs-6" placeholder="Password" required>
                       </div>
                       <div class="input-group mb-1">
                        <input type="password"  name="confirm_password"class="form-control form-control-lg bg-light fs-6" placeholder="Confirm Password" required>
                       </div>
                       <div class="input-group mb-5 d-flex justify-content-between">
                           <div class="forgot">
                               <small><a href="./forgot_password.php">Forgot Password?</a></small>
                           </div>
                       </div>
                       <div class="input-group mb-3">
                           <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">SignUp</button>
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