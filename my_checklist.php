<?php 
session_start();
include 'database.php';

if (!isset($_SESSION['user'])) {
	header('Location: login.php');
	exit;
}

$user_id = $_SESSION['user']['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['item']) && !empty(trim($_POST['item']))) {
		$item = trim($_POST['item']);
		$stmt = $pdo->prepare('INSERT INTO checklist (user_id, item) VALUES (?, ?)');
		$stmt->execute([$user_id, $item]);
	} elseif (isset($_POST['completed_id'])) {
		$completed_id = $_POST['completed_id'];
		$stmt = $pdo->prepare('UPDATE checklist SET completed = NOT completed WHERE id = ? AND user_id = ?');
		$stmt->execute([$completed_id, $user_id]);
	} elseif (isset($_POST['product_id']) && $_POST['action'] === 'remove_from_checklist') {
		$product_id = $_POST['product_id'];
		$stmt = $pdo->prepare('DELETE FROM checklist WHERE id = ? AND user_id = ?');
		$stmt->execute([$product_id, $user_id]);
	}
}

$stmt = $pdo->prepare('SELECT * FROM checklist WHERE user_id = ?');
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$_SESSION['checklist'] = $items;

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
							<img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars ($item['product_name']); ?>">
							<div class="content">
								<h3><?php echo htmlspecialchars ($item['product_name']); ?></h3>
								<h4>Price: $<?php echo htmlspecialchars ($item['price']); ?></h4>
								<p class="unit">Quantity: <input name="" value="1"></p>
								<form class="add-to-cart-form"  method="post">
									<input type="hidden" name="product_id" value="<?php echo htmlspecialchars ($item['product_id']); ?>">
									<input type="hidden" name="action" value="add_to_cart">
									<p class="btn-area-cart">
										<i aria-hidden="true" class="fa fa-shopping-cart"></i> 
										<button type="submit" class="btn2">Add to Cart</button>
									</p>	
								</form>
								<form class="remove-from-checklist-form" action="my_checklist.php" method="post">
									<input type="hidden" name="product_id" value="<?php echo htmlspecialchars ($item['product_id']); ?>">
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

