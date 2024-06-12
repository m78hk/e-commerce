<?php
session_start();
include 'database.php';



if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}



if (!is_array($_SESSION['user']) || !isset($_SESSION['user']['uid'])) {
    echo 'Invalid session data. Please log in again.';
    exit;
}

$userId = $_SESSION['user']['uid'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $payment_info = trim($_POST['payment_info'] ?? '');

    $stmt = $pdo->prepare('UPDATE tb_accounts SET address = ?, phone = ?, payment_info = ? WHERE uid = ?');
    if ($stmt->execute([$address, $phone, $payment_info, $userId])) {
        $_SESSION['user']['address'] = $address;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['payment_info'] = $payment_info;

        header('Location: info.php');
        exit;
    } else {
        $error = 'Failed to update account information';

        var_dump($stmt->errorInfo());
    }
}

$stmt = $pdo->prepare('SELECT username, email, address, phone, payment_info FROM tb_accounts WHERE uid = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);






?>



<!--header-->
<?php include 'header.php'; ?>
<!--end of header-->

<!--显示用户信息部分-->
<div class="container mt-5">
    <h1>User Information</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-6">
            <h3>Personal Information:</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <div class="col-md-6">
            <h3>Payment Information:</h3>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>Phone No:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Payment Information:</strong> <?php echo htmlspecialchars($user['payment_info']); ?></p>
        </div>
    </div>

    <h2>Update Information</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
        </div>
        <div class="mb-3">
            <label for="payment_info" class="form-label">Payment Information</label>
            <input type="text" class="form-control" id="payment_info" name="payment_info" value="<?php echo htmlspecialchars($user['payment_info']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<!--end of 用户信息部分-->


<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->
