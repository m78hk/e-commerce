<?php 

session_start();
include 'database.php';

$query = "SELECT * FROM products";
$stmt = $pdo->query($query);

$products = $stmt->fetchAll();

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
        <button type="button" class="btn m-2 text-dark" data-filter=".best-sellers">Computer</button>
        <button type="button" class="btn m-2 text-dark" data-filter=".featured">Featured</button>
        <button type="button" class="btn m-2 text-dark" data-filter=".new-arrival">New Arrival</button>
      </div>

      <div class="collection-list mt-4 row gx-0 gy-3">
      <?php foreach ($products as $product): ?>
      <?php $filterClass = strtolower(str_replace(' ', '-', $product['best_seller_label'])); ?>
      <div class="col-md-6 col-lg-4 col-xl-3 p-2 d-flex justify-content-center <?php echo $filterClass; ?>">
      <div class="product-container">
          <div class="collection-img position-relative">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" class="small-img">
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
             <p class="text-capitalize my-1 product-name" style=" padding: 2px; border-radius: 4px; display: inline-block; width: auto;"><?php echo $product['product_name']; ?></p>
             <span class="fw-bold">$<?php echo $product['price']; ?></span>
             <div class="text-center">
               <form id="add-to-cart-form-<?php echo $product['product_id']; ?>" class="add-to-cart-form" method="post" action="update_cart.php">
                  <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                  <button type="button" class="btn m-2 text-bg-white" onclick="addToCart(<?php echo $product['product_id']; ?>)">Add to Cart</button>
               </form>
               <form id="add-to-checklist-form-<?php echo $product['product_id']; ?>" class="add-to-checklist-form" method="post" action="add_to_checklist.php">
                  <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                  <button type="button" class="btn m-2 text-bg-white" onclick="addToChecklist(<?php echo $product['product_id']; ?>)">Add to Checklist</button>
               </form>
             </div>
           </div>
         </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div id="cart-feedback"></div>
    <div id="checklist-feedback"></div>
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
  xhr.open('POST', 'update_cart.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.status === 'success') {
          document.getElementById('cart-feedback').innerHTML = '';
          updateCartCount(response.totalQuantity);
      } else {
          document.getElementById('cart-feedback').innerHTML = '';
        }
      }
    };
  xhr.send('product_id=' + productId + '&action=add_to_cart');
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
  var form = document.getElementById('add-to-checklist-form-' + productId);
  var formData = new FormData(form);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'add_to_checklist.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      console.log(xhr.responseText)
      var response = JSON.parse(xhr.responseText);
      if (response.status === 'success') {
        document.getElementById('checklist-feedback').innerHTML = 'Product added to checklist';
        updateChecklistCount(response.checklistCount);
      } else if (response.status === 'already_in_checklist') {
        document.getElementById('checklist-feedback').innerHTML = 'Product already in checklist';
      } else {
        document.getElementById('checklist-feedback').innerHTML = 'Failed to add product to checklist';
      }
    }
  };
  xhr.send('product_id=' + productId);
}

function updateChecklistCount(count) {
  document.getElementById('checklist-count').innerHTML = count;
}
</script>
