<?php
session_start();
include 'database.php';


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['uid'];

    
    if (isset($_POST['email'], $_POST['phone'], $_POST['address'], $_POST['payment_info'])) {
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $payment_info = trim($_POST['payment_info']);

        
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE general_user SET email = ?, password = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
            $success = [$email, $password, $phone, $address, $payment_info, $userId];
        } else {
            $stmt = $pdo->prepare('UPDATE general_user SET email = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
            $success = [$email, $phone, $address, $payment_info, $userId];
        }

        if ($success) {
            echo 'Information updated successfully';
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['payment_info'] = $payment_info;
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

<!-- 表單部分 -->
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
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars(isset($_SESSION['user']['phone']) ? $_SESSION['user']['phone'] : ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars(isset($_SESSION['user']['address']) ? $_SESSION['user']['address'] : ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_info" class="form-label">Payment Information:</label>
            <input type="text" class="form-control" id="payment_info" name="payment_info" value="<?php echo htmlspecialchars(isset($_SESSION['user']['payment_info']) ? $_SESSION['user']['payment_info'] : ''); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->

