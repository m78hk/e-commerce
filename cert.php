<?php
session_start();
include 'database.php';
include 'functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        if (isset($_POST['quantity'])) {
            $quantity = $_POST['quantity'];
            updateCart($product_id, $quantity);
        } elseif (isset($_POST['remove']) && $_POST['remove'] === 'true') {
            removeFromCart($product_id);
        } else {
            addToCart($product_id);
        }
    }
}

function updateCart($product_id, $quantity) {
    foreach ($_SESSION['cart'] as &$item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            $_SESSION['cart_quantity'] = getCartQuantity();
            echo json_encode(['status' => 'success', 'cartQuantity' => $_SESSION['cart_quantity']]);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
}

function removeFromCart($product_id) {
            foreach ($_SESSION['cart'] as $key => $item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart_quantity'] = getCartQuantity();
            echo json_encode(['status' => 'success', 'cartQuantity' => $_SESSION['cart_quantity']]);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
}


function addToCart($product_id) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['product_id']) && $item['product_id'] == $product_id) {
            echo json_encode(['status' => 'error', 'message' => 'Product already in cart']);
            return;
        }
    }


     
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
    
        if ($product) {
         
            $_SESSION['cart'][] = [
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
            $_SESSION['cart_quantity'] = getCartQuantity();
            echo json_encode(['status' => 'success', 'cartQuantity' => $_SESSION['cart_quantity']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        }
}

function getCartQuantity() {
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}

$subtotal = 0;
$products = [];

$stmt = $pdo->query('SELECT * FROM products');
while ($row = $stmt->fetch()) {
    $products[$row['product_id']] = $row;
}


foreach ($_SESSION['cart'] as $item) {
    if (isset($item['product_id']) && isset($products[$item['product_id']])) {
        $product = $products[$item['product_id']];
        $subtotal += $product['price'] * $item['quantity'];
    }
}

$tax = $subtotal * 0.05;
$shipping = 15;
$total = $subtotal + $tax + $shipping;

include 'header.php';
?>

<!--header-->
<body id="header" class="vh-100 carousel slide " data-bs-ride ="carousel" style="padding-top: 104px;">
    <div class="shopping-cart-wrapper">
        <h1>Shopping Cart</h1>
        <div class="cart-box">
            <div class="shop">
            <?php
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        if (isset($item['product_id']) && isset($products[$item['product_id']]))  {
                            $product = $products[$item['product_id']];
                ?>
                <div class="box">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>">
                    <div class="content">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <h4>Price: $<?php echo htmlspecialchars($product['price']); ?></h4>
                        <p class="unit">Quantity: 
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" onchange="updateCart('<?php echo $product['product_id']; ?>', this.value)">
                        </p>
                        <p class="btn-area">
                            <i aria-hidden="true" class="fa fa-trash"></i> 
                            <span class="btn2" onclick="removeFromCart('<?php echo $product['product_id']; ?>')">Remove</span>
                        </p>
                    </div>
                </div>
                <?php
                        }
                    }
                } else {
                    echo '<p>Your cart is empty.</p>';
                }
                ?>
            </div>
            <div class="right-bar">
                <p><span>Subtotal</span> <span id="cart-subtotal">$<?php echo number_format($subtotal, 2); ?></span></p>
                <hr>
                <p><span>Tax (5%)</span> <span id="cart-tax">$<?php echo number_format($tax, 2); ?></span></p>
                <hr>
                <p><span>Shipping</span> <span>$<?php echo number_format($shipping, 2); ?></span></p>
                <hr>
                <p><span>Total</span> <span id="cart-total">$<?php echo number_format($total, 2); ?></span></p>
                <a href="#"><i class="fa fa-shopping-cart"></i>Checkout</a>
            </div>
        </div>
    </div>

    <script>
    function updateCart(productId, quantity) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cert.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    location.reload();
                    //updateCartQuantity(response.cartQuantity);
                    updateCartIconQuantity(response.cartQuantity);
                } else {
                    alert('Failed to update cart');
                }
            }
        };
        xhr.send('product_id=' + productId + '&quantity=' + quantity);
    }

    function removeFromCart(productId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cert.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    location.reload();
                    //updateCartQuantity(response.cartQuantity);
                    updateCartIconQuantity(response.cartQuantity);
                } else {
                    alert('Failed to remove from cart');
                }
            }
        };
        xhr.send('product_id=' + productId + '&remove=true');
    }

    function updateCartQuantity(quantity) {
       
        var cartIconQuantityElement = document.querySelector('.nav-btns .badge.bg-primary');
   
        cartIconQuantityElement.textContent = quantity;
    }

    </script>
</body>
<!--end of header-->

<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->
