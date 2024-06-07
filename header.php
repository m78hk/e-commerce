<?php 

include 'database.php';
include 'functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['checklist'])) {
    $_SESSION['checklist'] = [];
}



$cartQuantity = getCartQuantity();
$isLoggedIn = isset($_SESSION['user']);

if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header('Location: index.php');
  exit;
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--fontawesome cdn-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--bootstrap css-->    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/bootstrap-5.3.0-dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!--custom css-->
    <link rel="stylesheet" href="./css/style.css">
    <title>ABC SHOPPING MALL</title>
  </head>
  <body>
<!--navbar-->
    <nav class = "navbar navbar-expand-lg navbar-light bg-white py-4 fixed-top">
      <div class = "container">
          <a class = "navbar-brand d-flex justify-content-between align-items-center order-lg-0" href = "index.php">
              <img src = "./img/shopping_bag.png" alt = "site icon">
              <span class = "text-uppercase fw-lighter ms-1">ABC SHOPPING MALL</span>
          </a>

          <div class = "order-lg-2 nav-btns d-flex justify-content-end align-items-center">
            <?php if (!$isLoggedIn): ?>
              <button type="button" onclick="openAuthModal()" class="user-btn" style="background-color: rgba(0, 0, 0, 0); border: none;">
                <i class="fa fa-user user-icon" style="color: #2dd796;"></i>
              </button>
            <?php else: ?>
              <div class="dropdown">
                <a href="./auth.php" class="dropdown-toggle" id="userdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-user user-icon" style="color: #2dd796;"></i><?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                  <li><a class="dripdown-item" href="?logout=true">Logout</a></li>
                </ul>
              </div>
            <?php endif; ?> 

              <button type="button" class="btn position-relative">
                  <a href="./cert.php">
                    <i class="fa fa-shopping-cart" style="color: #2dd796;"></i>
                    <span id="cart-count" class = "position-absolute top-0 start-100 translate-middle badge bg-primary"><?php echo $cartQuantity;?></span>
                  </a>
              </button>
              <button type = "button" class = "btn position-relative">
                  <a href="./my_checklist.php">
                    <i class = "fa fa-heart" style="color: #2dd796;"></i>
                    <span id="checklist-count" class = "position-absolute top-0 start-100 translate-middle badge bg-primary">
                      <?php echo count($_SESSION['checklist']); ?>
                    </span>
                  </a>
              </button>
              <button type = "button" class = "btn position-relative">
                <a href="#">
                  <i class = "fa fa-search" style="color: #2dd796;"></i>
                </a>
             </button>
          </div>
          
          <button class = "navbar-toggler border-0" type = "button" data-bs-toggle = "collapse" data-bs-target = "#navMenu">
              <span class = "navbar-toggler-icon"></span>
          </button>

          <div class = "collapse navbar-collapse order-lg-1" id = "navMenu">
              <ul class = "navbar-nav mx-auto text-center">
                  <li class = "nav-item px-2 py-2">
                      <a class = "nav-link text-uppercase text-dark" href = "./index.php">home</a>
                  </li>
                  
                  <li class = "nav-item px-2 py-2">
                      <a class = "nav-link text-uppercase text-dark" href = "./login.php">login</a>
                  </li>
                  <li class = "nav-item px-2 py-2">
                      <a class = "nav-link text-uppercase text-dark" href = "./signup.php">SignUp</a>
                  </li>
                  
                  <li class="nav-item dropdown px-2 py-2 border-0">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      PRODUCT
                    </a>
                    <ul class="dropdown-menu product-dropdown-menu">
                      <li><a class="dropdown-item" href= "./product_1.php">Supermarket</a></li>
                      <!--<li><a class="dropdown-item" href= "./product_2.php">Electronic product</a></li>-->
                   </ul>
                 </li>
              </ul>
          </div>
      </div>
  </nav>
<!--end of navbar-->

<!-- Auth Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="authModalLabel">Login / Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Login Form -->
        <form id="loginForm" action="login.php" method="post">
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="loginEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <hr>
        <!-- Register Form -->
        <form id="registerForm" action="signup.php" method="post">
          <div class="mb-3">
            <label for="registerEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="registerEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="registerPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="registerPassword" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>



<!--jquery-->
<script src="js/code.jquery.com_jquery-3.7.0.js"></script>
<!--isotope js-->
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
<!-- Add Isotope library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
<!--bootstrap js-->

<!--custom js-->
<script src="./js/style.js"></script>

<script>
    function openAuthModal() {
        var authModal = new bootstrap.Modal(document.getElementById('authModal'));
        authModal.show();
    }



    </script>
  </body>
</html>