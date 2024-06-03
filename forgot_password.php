 <?php
/*--header--*/
 include 'header.php'; 
/*--end of header--*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (filter_Var("$email", FILTER_VALIDATE_EMAIL)) {

        echo "<script>alart('If this email is registered, you will receive a password reset link.');</script>";
    } else {
        echo "<script>alert('Please enter a valid email address.');</script>";
    }
}

?>
<!--php-->
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
                    <h2>Forgot Password</h2>
                </div>
                <form action="forgot_password.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Email address">
                    </div>
                    <div class="input-group mb-5 d-flex justify-content-between">
                    </div>
                    <div class="input-group mb-3">
                        <button class="btn btn-lg btn-primary w-100 fs-6">submit</button>
                    </div>
                </form>
                <div class="row">
                    <small>Don't have account? <a href="./signup.php">Sign Up</a></small>
                </div>
            </div>
        </div>
    </div>
   </div>
</body>
<!--end of php-->

<!--contact information, new letter scbscription ,footer-->

 <?php include 'footer.php'; ?>

<!--end of contact information, new letter scbscription ,footer--> 

