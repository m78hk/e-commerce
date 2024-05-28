<?php 
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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
              <span class = "text-uppercase fw-lighter ms-2">ABC SHOPPING MALL</span>
          </a>

          <div class = "order-lg-2 nav-btns">
              <button type = "button" class = "btn position-relative">
                  <a href="./cert.php">
                    <i class="fa fa-shopping-cart" style="color: #2dd796;"></i>
                    <span id="cart-count" class = "position-absolute top-0 start-100 translate-middle badge bg-primary"><?php if (!empty($_SESSION['cart'])) {
                      echo array_sum(array_column($_SESSION['cart'], 'quantity'));
                    } else {
                      echo 0;
                    }
                    ?></span>
                  </a>
              </button>
              <button type = "button" class = "btn position-relative">
                  <a href="./my checklist.php">
                    <i class = "fa fa-heart" style="color: #2dd796;"></i>
                    <span class = "position-absolute top-0 start-100 translate-middle badge bg-primary">1</span>
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
                      <a class = "nav-link text-uppercase text-dark" href = "./singup.php">SignUp</a>
                  </li>
                  <li class="nav-item dropdown px-2 py-2 border-0">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      PRODUCT
                    </a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href= "./product_1.php">Supermarket</a></li>
                      <li><a class="dropdown-item" href= "./product_2.php">Electronic product</a></li>
                   </ul>
                 </li>
              </ul>
          </div>
      </div>
  </nav>
<!--end of navbar-->




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
  </body>
</html>