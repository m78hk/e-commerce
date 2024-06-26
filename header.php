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
$isAdmin = false;



if ($isLoggedIn) {
  echo "User is logged in, UID: " . $_SESSION['user']['uid'];
  $isAdmin = isAdmin($pdo, $_SESSION['user']['uid']); // Update isAdmin variable
  echo "User Id" . $_SESSION['user']['uid'] . "is admin: " . $isAdmin;
} else {
  echo "User is not logged in";
}

if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header('Location: index.php');
  exit;
}

$error = '';
$username = '';
$email = '';
$password = '';
$confirm_password = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); 
    $email = trim($_POST['email'] ?? ''); 
    $password = trim($_POST['password'] ?? ''); 
    $confirm_password = trim($_POST['confirm_password'] ?? ''); 

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
                header('Location: index.php');
                exit();
            }
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? ''); 
  $password = trim($_POST['password'] ?? ''); 

  if (empty($email) || empty($password)) {
      $error = 'Email and Password are required.';
  } else {
      $stmt = $pdo->prepare('SELECT * FROM tb_accounts WHERE email = ?');
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify ($password, $user['password'])) {
          $_SESSION['user'] = $user;
          $redirect = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'index.php';
          unset($_SESSION['redirect_to']);
          header("Location: $redirect");
          exit();
      } else {
          $error = 'Invalid email or password.';
      }
  }
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
    <!-- Firebase JavaScript SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.6.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.6/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.6/firebase-firestore.js"></script>
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
                <a href="#" class="dropdown-toggle" id="userdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-user user-icon" style="color: #2dd796;"></i><?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                  <li><a class="dropdown-item" href="info.php">Info</a></li>
                  <li><a class="dropdown-item" href="?logout=true">Logout</a></li>
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
                  <?php if (!$isLoggedIn): ?>
                    <i class = "fa fa-heart" style="color: #2dd796;"></i>
                  <?php else: ?>
                    <i class = "fa fa-heart" style="color: #2dd796;"></i>
                  <?php endif; ?>
                    <span id="checklist-count" class = "position-absolute top-0 start-100 translate-middle badge bg-primary">
                      <?php echo count($_SESSION['checklist']); ?>
                    </span>
                  </a>
              </button>
              <button type="button" class="btn position-relative" id="searchButton">
                    <a href="#">
                        <i class="fa fa-search" style="color: #2dd796;"></i>
                    </a>
                </button>
                <div class="search-container">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search products">
                    <div class="dropdown-menu custom-dropdown" id="searchResults" style="display: none;"></div>
                </div>
          </div>
          <button class = "navbar-toggler border-0" type = "button" data-bs-toggle = "collapse" data-bs-target = "#navMenu">
              <span class = "navbar-toggler-icon"></span>
          </button>

          <div class = "collapse navbar-collapse order-lg-1" id = "navMenu">
              <ul class = "navbar-nav mx-auto text-center">
                  <li class = "nav-item px-2 py-2">
                      <a class = "nav-link text-uppercase text-dark" href = "./index.php">home</a>
                  </li>
                  <!--
                  <li class = "nav-item px-2 py-2">
                      <a class = "nav-link text-uppercase text-dark" href = "./login.php">login</a>
                  </li>
                  <li class = "nav-item px-2 py-2">
                      <a class = "nav-link text-uppercase text-dark" href = "./signup.php">SignUp</a>
                  </li>
                  -->
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
  <div class="modal-dialog modal-dialog-centered modal-xl"> 
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="authModalLabel">Login / Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex justify-content-between"> 
        <!-- Login Form -->
        <form id="loginForm" action="#" method="post" class="flex-grow-1 me-3"> 
        <p>Login</p>  
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
        <!-- Register Form -->
        <form id="registerForm" action="#" method="post" class="flex-grow-1 ms-3"> 
          <p>Register</p>  
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control form-control-lg bg-light fs-6" placeholder="Username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
          </div>
          <div class="input-group mb-3">
              <input type="text" name="email"class="form-control form-control-lg bg-light fs-6" placeholder="Email address" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
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
<!-- End of Auth Modal -->



<!--jquery-->
<script src="js/code.jquery.com_jquery-3.7.0.js"></script>
<!--isotope js-->
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.js"></script>
<!-- Add Isotope library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
<!--custom js-->
<script src="./js/style.js"></script>

    <script>
        $(document).ready(function() {
        function openAuthModal() {
          var authModal = new bootstrap.Modal(document.getElementById('authModal'), {
            backdrop: 'static',
            keyboard: false
          });
          authModal.show();
        }

        $(document).on('click', '.fa-user', function(event) {
            event.preventDefault(); 

            
            if (!<?php echo $isLoggedIn ? 'true' : 'false'; ?>) {
                
                openAuthModal();
            } else {
                
                window.location.href = 'index.php';
            }
        });

        
        $(document).on('click', '.fa-heart', function(event) {
            event.preventDefault(); 

            
            if (!<?php echo $isLoggedIn ? 'true' : 'false'; ?>) {
                
                openAuthModal();
            } else {
                
                window.location.href = 'my_checklist.php';
            }
        });

            $('#searchButton').on('click', function() {
                $('.search-container').toggle();
                $('#searchInput').focus();
            });

            $('#searchInput').on('input', function() {
                var query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: 'search.php',
                        method: 'GET',
                        data: { search: query },
                        success: function(data) {
                            $('#searchResults').html(data).show();
                        }
                    });
                } else {
                    $('#searchResults').hide();
                }
            });

            $(document).on('click', '.search-result-item', function() {
                var productId = $(this).data('id');
                window.location.href = 'product.php?id=' + productId;
            });

            $(document).click(function(event) {
                if (!$(event.target).closest('.search-container, #searchButton').length) {
                    $('#searchResults').hide();
                    $('.search-container').hide();
                }
            });
        });

        const firebaseConfig = {
          apiKey: "AIzaSyAaHo422K_N-JmZ6Ziq8ur-6a2sZ3_OFRQ",
          authDomain: "abcshop-web.firebaseapp.com",
          projectId: "abcshop-web",
          storageBucket: "abcshop-web.appspot.com",
          messagingSenderId: "293629346772",
          appId: "1:293629346772:web:30e0219f215ab1e2bdcc41",
          measurementId: "G-RNF3QDCT8F"
        };

        firebase.initializeApp(firebaseConfig);
    </script>
  </body>
</html>