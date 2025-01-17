<?php
session_start();
include 'database.php';


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['uid'];

    
    if (isset($_POST['email'], $_POST['phone'], $_POST['address'], $_POST['payment_method'], $_POST['credit_card'])) {
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $payment_info = trim($_POST['payment_method']);
        $credit_card = trim($_POST['credit_card']);

        
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE tb_accounts SET email = ?, password = ?, phone = ?, address = ?, payment_method = ?, credit_card =? WHERE uid = ?');
            $success = [$email, $password, $phone, $address, $payment_method, $credit_card, $userId];
        } else {
            $stmt = $pdo->prepare('UPDATE tb_accounts  SET email = ?, password = ?, phone = ?, address = ?, payment_method = ?, credit_card =? WHERE uid = ?');
            $success = [$email, $password, $phone, $address, $payment_method, $credit_card, $userId];
        }

        if ($success) {
            echo "<script>alert('Information updated successfully');</script>";
            echo 'Information updated successfully';
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['payment_method'] = $payment_method;
            $_SESSION['user']['credit_card'] = $credit_card;
            if (!empty($_POST['password'])) {
                $_SESSION['user']['password'] = $password;
            }
        } else {
            echo 'Failed to update information';
        }
    }
}
?>

<!--header-->
<?php include 'header.php'; ?>
<!--end of header-->

<!--form -->
<div class="container mt-5">
    <br>
    <br>
    <br>
    <br>
    <h1>Update Information</h1>
    <form method="POST" action="info.php">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone No:</label>
            <input type="text" class="form-control" id="phone" name="phone" 
            value="<?php echo htmlspecialchars(isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" id="address" name="address" 
            value="<?php echo htmlspecialchars(isset($_SESSION['user']['address']) ? $_SESSION['user']['address'] : ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="visa" name="payment_method" 
                value="Visa" <?php echo (isset($_SESSION['user']['payment_method']) && $_SESSION['user']['payment_method'] === 'Visa') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="visa">Visa</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="master" name="payment_method" 
                value="Master" <?php echo (isset($_SESSION['user']['payment_method']) && $_SESSION['user']['payment_method'] === 'Master') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="master">MasterCard</label>
            </div>
            <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="paypal" name="payment_method" 
            value="PayPal" <?php echo (isset($_SESSION['user']['payment_method']) && $_SESSION['user']['payment_method'] === 'PayPal') ? 'checked' : ''; ?>>
            <label class="form-check-label" for="paypal">PayPal</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="payment_info" class="form-label">Credit Card Information:</label>
            <input type="text" class="form-control" id="payment_method" 
            name="payment_method" value="<?php echo htmlspecialchars(isset($_SESSION['user']['payment_method']) ? $_SESSION['user']['payment_method'] : ''); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->

