<?php

session_start();
include 'database.php';



if (!isset($_SESSION['user'])) {
    echo 'User not logged in.';
    exit;
}

$userId = $_SESSION['user']['uid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['address'], $_POST['phone'], $_POST['payment_info'])) {
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);
        $payment_info = trim($_POST['payment_info']);

        $stmt = $pdo->prepare('UPDATE tb_accounts SET address = ?, phone = ?, payment_info = ? WHERE uid = ?');
        if ($stmt->execute([$address, $phone, $payment_info, $userId])) {
            echo 'Information saved successfully.';
        } else {
            echo 'Failed to save information.';
        }

        $_SESSION['address'] = $address;
        $_SESSION['phone'] = $phone;
        $_SESSION['payment_info'] = $payment_info;
    }

    if (isset($_POST['subtotal'], $_POST['tax'], $_POST['shipping'], $_POST['total'], $_POST['cart'])) {
        $subtotal = $_POST['subtotal'];
        $tax = $_POST['tax'];
        $shipping = $_POST['shipping'];
        $total = $_POST['total'];
        $cart = json_decode($_POST['cart'], true);
        $_SESSION['cart'] = $cart;

        $_SESSION['subtotal'] = $subtotal;
        $_SESSION['tax'] = $tax;
        $_SESSION['shipping'] = $shipping;
        $_SESSION['total'] = $total;
    }
} else {
    
    $stmt = $pdo->prepare('SELECT address, phone, payment_info FROM tb_accounts WHERE uid = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['address'] = $user['address'] ?? '';
        $_SESSION['phone'] = $user['phone'] ?? '';
        $_SESSION['payment_info'] = $user['payment_info'] ?? '';
    } else {
        
        $_SESSION['address'] = '';
        $_SESSION['phone'] = '';
        $_SESSION['payment_info'] = '';
    }

    $_SESSION['subtotal'] = $_SESSION['subtotal'] ?? 0;
    $_SESSION['tax'] = $_SESSION['tax'] ?? 0;
    $_SESSION['shipping'] = $_SESSION['shipping'] ?? 0;
    $_SESSION['total'] = $_SESSION['total'] ?? 0;
}


    $address = $_SESSION['address'] ?? '';
    $phone = $_SESSION['phone'] ?? '';
    $payment_info = $_SESSION['payment_info'] ?? '';

    $subtotal = $_SESSION['subtotal'] ?? 0;
    $tax = $_SESSION['tax'] ?? 0;
    $shipping = $_SESSION['shipping'] ?? 0;
    $total = $_SESSION['total'] ?? 0;

    $products = [];
    $stmt = $pdo->query('SELECT * FROM products');
    while ($row = $stmt->fetch()) {
        $products[$row['product_id']] = $row;
    }





?>
<!--header-->
<?php include 'header.php'; ?>
<!--end of header-->

<body id="header" class="vh-100 carousel slide" data-bs-ride="carousel" style="padding-top: 104px;">
    <div class="container mt-5">
        <h1>Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Your Cart:</h3>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <?php if (isset($item['product_id']) && isset($products[$item['product_id']])): ?>
                        <?php $product = $products[$item['product_id']]; ?>
                        <div class="cart-item d-flex align-items-center">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" 
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 110px; height: 110px;">
                            <div class="ms-3">
                                <p>Product Name: <?php echo htmlspecialchars($product['product_name']); ?></p>
                                <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                <p>Price: <?php echo htmlspecialchars($item['price']); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>Product not found</p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="col-md-6">
                <h3>Enter Your Information:</h3>
                <form method="POST" class="right-bar" action="checkout.php">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Phone" class="form-label">Phone No</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_info" class="form-label">Payment Information</label>
                        <input type="text" class="form-control" id="payment_info" name="payment_info" value="<?php echo isset($payment_info) ? htmlspecialchars($payment_info) : ''; ?>"required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Information</button>
                </form>
                <br>
                <form method="POST" class="right-bar" action="pay.php">
                    <div class="mb-3">
                        <label for="subtotal" class="form-label">Subtotal:</label>
                        <input type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo htmlspecialchars($subtotal); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tax" class="form-label">Tax:</label>
                        <input type="text" class="form-control" id="tax" name="tax" value="<?php echo htmlspecialchars($tax); ?>" readonly>
                     </div>
                    <div class="mb-3">
                        <label for="shipping" class="form-label">Shipping:</label>
                        <input type="text" class="form-control" id="shipping" name="shipping" value="<?php echo htmlspecialchars($shipping); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total:</label>
                        <input type="text" class="form-control" id="total" name="total" value="<?php echo htmlspecialchars($total); ?>" readonly>
                    </div>
                    <button type="submit" class="btn btn-success">Buy</button>
                </form>
            </div>
        </div>
    </div>
</body>

<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->