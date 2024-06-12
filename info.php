<?php
session_start();
include 'database.php';

// 檢查用戶是否已登入，如果未登入則導向登入頁面
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// 提交表單後的處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['uid'];

    // 更新用戶信息
    if (isset($_POST['email'], $_POST['phone'], $_POST['address'], $_POST['payment_info'])) {
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $payment_info = trim($_POST['payment_info']);

        // 檢查是否提供了新密碼
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // 哈希加密新密碼
            $stmt = $pdo->prepare('UPDATE tb_accounts SET email = ?, password = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
            if ($stmt->execute([$email, $password, $phone, $address, $payment_info, $userId])) {
                echo 'Information updated successfully.';
            } else {
                echo 'Failed to update information.';
            }
        } else {
            // 沒有提供新密碼，只更新其他信息
            $stmt = $pdo->prepare('UPDATE tb_accounts SET email = ?, phone = ?, address = ?, payment_info = ? WHERE uid = ?');
            if ($stmt->execute([$email, $phone, $address, $payment_info, $userId])) {
                echo 'Information updated successfully.';
            } else {
                echo 'Failed to update information.';
            }
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
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($_SESSION['user']['address']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="payment_info" class="form-label">Payment Information:</label>
            <input type="text" class="form-control" id="payment_info" name="payment_info" value="<?php echo htmlspecialchars($_SESSION['user']['payment_info']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<!--contact information, newsletter subscription, footer-->
<?php include 'footer.php'; ?>
<!--end of contact information, newsletter subscription, footer-->

