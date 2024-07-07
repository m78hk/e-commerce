<?php 
session_start();
include 'database.php';

if (!isset($_SESSION['user']['uid'])) {
	header('Location: index.php');
	exit();
}

$user_id = $_SESSION['user']['uid'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$product_id = null;
	//$completed_id = null;
	$action = isset($_POST['action']) ? $_POST['action'] : '';

	error_log('POST data: ' . print_r($_POST, true));
    error_log('Action: ' . $action);


	if (isset($_POST['product_id']) && $action === 'add_to_checklist') {
        $product_id = trim($_POST['product_id']);
        $stmt = $pdo->prepare('INSERT INTO checklist (user_id, product_id) VALUES (?, ?)');
		if ($stmt->execute([$user_id, $product_id])) {
            error_log('Product added to checklist: ' . $product_id);
        } else {
            error_log('Failed to add product to checklist: ' . $product_id);
		}
    
   
    } elseif (isset($_POST['product_id']) && $action === 'remove_from_checklist') {
        $product_id = $_POST['product_id'];
        $stmt = $pdo->prepare('DELETE FROM checklist WHERE product_id = ? AND uid = ?');
		if ($stmt->execute([$product_id, $user_id])) {
			if (isset($_SESSION['checklist'])) {
                foreach ($_SESSION['checklist'] as $key => $item) {
                    if ($item['product_id'] == $product_id) {
                        unset($_SESSION['checklist'][$key]);
                    }
                }
                $_SESSION['checklist'] = array_values($_SESSION['checklist']);
            }
            error_log('Product removed from checklist: ' . $product_id);
        } else {
            error_log('Failed to remove product from checklist: ' . $product_id);
        }
    
   
    } elseif (isset($_POST['product_id']) && $action === 'add_to_cart') {
        $product_id = $_POST['product_id'];
        $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

		if ($product) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

           
            $_SESSION['cart'][] = $product;
            error_log('Product added to cart: ' . $product_id);

			echo json_encode(['status' => 'success', 'cartQuantity' => count($_SESSION['cart'])]);
        } else {
            error_log('Failed to add product to cart: ' . $product_id);
			echo json_encode(['status' => 'error']);
        }
		exit;
	}
}

		function getCartQuantity() {
    		if (!isset($_SESSION['cart'])) {
        		return 0;
    		}
    		return count($_SESSION['cart']);
		}

$stmt = $pdo->prepare('SELECT checklist.id, checklist.product_id, products.product_name, products.price, 
	products.image FROM checklist LEFT JOIN products ON checklist.product_id = products.product_id WHERE checklist.uid = ?');
$stmt->execute([$user_id]);
$items= $stmt->fetchAll();



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
						<img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>">
							<div class="content">
								<h3><?php echo htmlspecialchars ($item['product_name']); ?></h3>
								<h4>Price: $<?php echo htmlspecialchars ($item['price']); ?></h4>
								<p class="unit">Quantity: <input name="" value="1"></p>
								<form class="add-to-cart-form"  onsubmit="addToCart(event, '<?php echo htmlspecialchars($item['product_id']); ?>')">
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

<script>
    

function addToCart(event, productId) {
    event.preventDefault();
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'my_checklist.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                updateCartIconQuantity(response.cartQuantity);
                location.reload();
            } else {
                alert('Failed to add to cart');
            }
        }
    };
    xhr.send('product_id=' + productId + '&action=add_to_cart');
}

function updateCartIconQuantity(quantity) {
    var cartIconQuantityElement = document.querySelector('.nav-btns .badge.bg-primary');
    cartIconQuantityElement.textContent = quantity;
}

document.addEventListener('DOMContentLoaded', function() {
	updateCartIconQuantity(<?php echo getCartQuantity(); ?>);
});
</script>