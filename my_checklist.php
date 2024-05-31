<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
	if ($_POST['action'] === 'remove_from_checklist' && isset($_POST['product_id'])) {
		$product_id = $_POST['product_id'];
		removeFromChecklist($product_id);
	} elseif ($_POST['action'] === 'add_to_cart' && isset($_POST['product_id'])) {
		$product_id = $_POST['product_id'];
		addToCart($product_id);
	}
}

function removeFromChecklist($product_id) {
	if (isset($_SESSION['checklist'])) {
		foreach ($_SESSION['checklist'] as $key => $item) {
			if ($item['product_id'] == $product_id) {
				unset($_SESSION['checklist'][$key]);
				$_SESSION['checklist'] = array_values($_SESSION['checklist']);
				return;
			}
		}
	}
}

function addToCart($product_id) {
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = [];
	}
	foreach ($_SESSION['checklist'] as $item) {
		if ($item['product_id'] == $product_id) {
			$_SESSION['cart'] [] = ['product_id' => $item['product_id'], 'quantity' => 1];
			return;
		}
	}
}
?>


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
								<form class="add-to-cart-form"  method="post">
									<input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
									<input type="hidden" name="action" value="add_to_cart">
									<p class="btn-area-cart">
										<i aria-hidden="true" class="fa fa-shopping-cart"></i> 
										<button type="submit" class="btn2">Add to Cart</button>
									</p>	
								</form>
								<form class="remove-from-checklist-form" action="my_checklist.php" method="post">
									<input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
									<input type="hidden" name="action" value="remove_from_checklist">
									<p class="btn-area">
										<i aria-hidden="true" class="fa fa-trash"></i> 
										<button type="submit" class="btn2">Remove</button>
									</p>
								</form>
								
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

