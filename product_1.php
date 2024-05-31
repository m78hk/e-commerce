<?php 
  session_start();
  include 'stock.php';

  if (isset($_POST['add_to_checklist']) && !empty($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    $productFound = false;
    foreach ($products as $product) {
      if ($product['product_id'] == $productId) {
        $_SESSION['checklist'][] = $product;
        $productFound = true;
        break;
      }
    }  
    if (!$proudctFound) {
      echo "Product not found in stock";
    }
  }
?>
<!--header-->
<?php include 'header.php'; ?>
<!--end of header-->

<!--body-->
<!--product 1 page-->


<body id="header" class="vh-100 carousel slide" data-bs-ride="carousel" style="padding-top: 104px;">
  <section id="collection" class="py-5">
    <div class="container">
      <div class="title text-center">
        <h2 class="position-relative d-inline-block">Supermarket</h2>
      </div>
    </div>

    <div class="row g-0">
      <div class="d-flex flex-wrap justify-content-center mt-5 filter-button-group">
        <button type="button" class="btn m-2 text-dark active-filter-btn" data-filter="*">All</button>
        <button type="button" class="btn m-2 text-dark" data-filter=".best-sellers">Best Sellers</button>
        <button type="button" class="btn m-2 text-dark" data-filter=".featured">Featured</button>
        <button type="button" class="btn m-2 text-dark" data-filter=".new-arrival">New Arrival</button>
      </div>

      <div class="collection-list mt-4 row gx-0 gy-3">
      <?php foreach ($products as $product): ?>
      <?php $filterClass = strtolower(str_replace(' ', '-', $product['best_seller_label'])); ?>
      <div class="col-md-6 col-lg-4 col-xl-3 p-2 <?php echo $filterClass; ?>">
      <div class="collection-img position-relative">
             <img src="<?php echo $product['image']; ?>" class="small-img">
             <?php if (!empty($product['label'])): ?>
                <span class="position-absolute bg-primary text-white d-flex align-items-center justify-content-center">
                   <?php echo $product['label']; ?>
                </span>
             <?php endif; ?>
      </div>
    <div class="text-center">
      <div class="rating mt-3">
        <br>
        <?php
          $rating = $product['rating'];
          for ($i = 0; $i < 5; $i++) {
            if ($rating >= 1) {
              echo '<span class="text-primary"><i class="fas fa-star"></i></span>';
            } elseif ($rating > 0) {
              echo '<span class="text-primary"><i class="fas fa-star-half-alt"></i></span>';
            } else {
              echo '<span class="text-primary"><i class="far fa-star"></i></span>';
            }
            $rating--;
          }
        ?>
    </div>
             <p class="text-capitalize my-1"><?php echo $product['product_name']; ?></p>
             <span class="fw-bold">$<?php echo $product['price']; ?></span>
             <div class="text-center">
               <form id="add-to-cart-form-<?php echo $product['product_id']; ?>" class="add-to-cart-form">
                  <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                  <button type="button" class="btn m-2 text-bg-white" onclick="addToCart(<?php echo $product['product_id']; ?>)">Add to Cart</button>
                  <button type="button" class="btn m-2 text-bg-white" onclick="addToChecklist(<?php echo $product['product_id']; ?>)">Add to Checklist</button>
               </form>
             </div>
           </div>
         </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div id="cart-feedback"></div>
  </section>
</body>

<!--end of body-->
<!--end of product 1 page-->

<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->

<!-- Add Isotope library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>

<script>
$(document).ready(function(){
    // Initialize Isotope
    var $grid = $('.collection-list').isotope({
        itemSelector: '.col-md-6',
        layoutMode: 'fitRows'
    });

    // Filter items on button click
    $('.filter-button-group').on('click', 'button', function(){
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue });
    });
});

function addToCart(productId) {
  var form = document.getElementById('add-to-cart-form-' + productId);
  var formData = new FormData(form);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'cert.php', true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.status === 'success') {
          document.getElementById('cart-feedback').innerHTML = 'Product added to cart';
          updateCartCount(response.totalQuantity);
      } else {
          document.getElementById('cart-feedback').innerHTML = 'Failed to add product to cart';
        }
      }
    };
  xhr.send(formData);
}

function updateCartCount(totalQuantity) {
    document.querySelector('.nav-btns .badge.bg-primary').innerHTML = totalQuantity;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'update_cart.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.querySelector('.nav-btns .badge.bg-primary').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
}

function addToChecklist(productId) {

  var formData = new FormData();
  formData.append('add_to_checklist', '1');
  formData.append('product_id', productId);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'add_to_checklist.php', true);
  //xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      alert(response.message);
    }
  };
  xhr.send(formData);
}

</script>
