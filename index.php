<!--header-->
<?php include 'header.php'; ?>
<!--end of header-->

<!--body-->
<body id="body" class="vh-100 carousel slide " data-bs-ride ="carousel" style="padding-top: 104px;">
  <video autoplay loop muted plays-inline class="back-video">
    <source src="./img/Welcome To Your Walmart _ Walmart.mp4">
  </video>
  <div class = "container h-100 d-flex align-items-center carousel-inner">
    <div class = "text-center carousel-item active">
        <h2 class = "text-capitalize text-white">best collection</h2>
        <h1 class = "text-uppercase py-2 fw-bold text-white">new arrivals</h1>
        <a href = "./product_1.html" class = "btn mt-3 text-uppercase text-white">shop now</a>
    </div>
  <div class = "text-center carousel-item">
      <h2 class = "text-capitalize text-white">best price & offer</h2>
      <h1 class = "text-uppercase py-2 fw-bold text-white">new season</h1>
      <a href = "./product_2.html" class = "btn mt-3 text-uppercase text-white">buy now</a>
   </div>
  </div>
  <button class = "carousel-control-prev" type = "button" data-bs-target="#header" data-bs-slide = "prev">
    <span class = "carousel-control-prev-icon"></span>
  </button>
  <button class = "carousel-control-next" type = "button" data-bs-target="#header" data-bs-slide = "next">
    <span class = "carousel-control-next-icon"></span>
  </button>
</body>
<!--end of body-->

<!--contact information, new letter scbscription ,footer-->

<?php include 'footer.php'; ?>

<!--end of contact information, new letter scbscription ,footer-->
