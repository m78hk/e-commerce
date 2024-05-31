<!--header-->
 <?php include 'header.php'; ?>
<!--end of header-->

<!--body-->
<body id="body" class="vh-100 carousel slide " data-bs-ride ="carousel" style="padding-top: 104px;">
    <div class="shopping-cart-wrapper">
		<h1>My Checklist</h1>
		<div class="cart-box">
			<div class="shop">
				<?php if (isset($_SESSION['checklist']) && !empty($_SESSION['checklist'])): ?>
					<?php foreach ($_SESSION['checklist'] as $item): ?>
						<div class="box">
							<img src="<?php echo $item['image']; ?>" alt="<?php echo $item['product_name']; ?>">
							<div class="content">
								<h3><?php echo $item['product_name']; ?></h3>
								<h4>Price: $<?php echo $item['price']; ?></h4>
								<p class="unit">Quantity: <input name="" value="1"></p>
								<p class="btn-area-cart"><i aria-hidden="true" class="fa fa-shopping-cart"></i> <span class="btn2">Add to Cart</span></p>
								<p class="btn-area"><i aria-hidden="true" class="fa fa-trash"></i> <span class="btn2">Remove</span></p>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p>No items in your checklist</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
<!--end of body-->
<!--contact information, new letter scbscription ,footer-->

 <?php include 'footer.php'; ?>

<!--end of contact information, new letter scbscription ,footer-->