<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php';

// 確保用戶已登入
if (!isset($_SESSION['user']['uid'])) {
    header('Location: login_sk_tb.php');
    exit();
}

if ($_SESSION['user']['is_admin'] != 1) {
    header('Location: login_sk_tb.php');
    exit();
}

// 處理 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // add new account
    if (isset($_POST['action']) && $_POST['action'] === 'add_account') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $role = $_POST['role'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $payment_info = $_POST['payment_info'];
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        $stmt = $pdo->prepare('INSERT INTO tb_accounts (username, password, email, phone, address, payment_info, role, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        if ($stmt->execute([$username, $password, $email, $phone, $address, $payment_info, $role, $is_admin])) {
            echo json_encode(['status' => 'success', 'message' => 'Account added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add account']);
        }
        exit();
    }

    // delete account
    if (isset($_POST['action']) && $_POST['action'] === 'delete_account') {
        $uid = $_POST['uid'];

        $stmt = $pdo->prepare('DELETE FROM tb_accounts WHERE uid = ?');
        if ($stmt->execute([$uid])) {
            echo json_encode(['status' => 'success', 'message' => 'Account deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete account']);
        }
        exit();
    }

    // edit account
    if (isset($_POST['action']) && $_POST['action'] === 'edit_account') {
        $uid = $_POST['uid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $payment_info = $_POST['payment_info'];
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        $stmt = $pdo->prepare('UPDATE tb_accounts SET username = ?, email = ?, role = ?, phone = ?, address = ?, payment_info = ?, is_admin = ? WHERE uid = ?');
        $params = [$username, $email, $role, $phone, $address, $payment_info, $is_admin, $uid];

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE tb_accounts SET username = ?, password = ?, email = ?, role = ?, phone = ?, address = ?, payment_info = ?, is_admin = ? WHERE uid = ?');
            $params = [$username, $password, $email, $role, $phone, $address, $payment_info, $is_admin, $uid];
        }

        if ($stmt->execute($params)) {
            echo json_encode(['status' => 'success', 'message' => 'Account updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update account']);
        }
        exit();
    }
}

// get all accounts
try {
    $stmt = $pdo->query('SELECT * FROM tb_accounts');
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
</head>
<body>
    <h1>Account Management</h1>
    <a href="supermarket_backend.php">supermarket_backend</a>
    <br>
    <br>
    <div id="logout-container">
        <a href="login_sk_tb.php" class="logout-button">Logout</a>
    </div>
    <br>
    <br>
    <form id="add-account-form">
        <input type="hidden" name="action" value="add_account">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <br>
        <label>Phone:</label>
        <input type="text" name="phone"><br>
        <br>
        <label>Address:</label>
        <input type="text" name="address"><br>
        <br>
        <label>Payment Info:</label>
        <input type="text" name="payment_info"><br>
        <br>
        <label>Role:</label>
        <input type="text" name="role" required><br>
        <br>
        <label>Is Admin:</label>
        <input type="checkbox" name="is_admin"><br>
        <br>
        <button type="submit">Add Account</button>
    </form>

    <h2>Accounts List</h2>
    <table id="accounts-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Payment Info</th>
                <th>Role</th>
                <th>Is Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($accounts)) : ?>
                <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td><?= htmlspecialchars($account['uid']) ?></td>
                        <td><?= htmlspecialchars($account['username']) ?></td>
                        <td><?= htmlspecialchars($account['email']) ?></td>
                        <td><?= htmlspecialchars($account['phone']) ?></td>
                        <td><?= htmlspecialchars($account['address']) ?></td>
                        <td><?= htmlspecialchars($account['payment_info']) ?></td>
                        <td><?= htmlspecialchars($account['role']) ?></td>
                        <td><?= htmlspecialchars($account['is_admin'] ? 'Yes' : 'No') ?></td>
                        <td>
                            <button onclick="editAccount(<?= $account['uid'] ?>)">Edit</button>

                            <form class="delete-account-form" data-id="<?= $account['uid'] ?>" style="display:inline;">
                                <input type="hidden" name="action" value="delete_account">
                                <input type="hidden" name="uid" value="<?= $account['uid'] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div id="edit-account-modal">
        <form id="edit-account-form">
            <input type="hidden" name="action" value="edit_account">
            <input type="hidden" name="uid" id="edit-account-uid">
            <label>Username:</label>
            <input type="text" name="username" id="edit-account-username" required><br>
            <label>Password:</label>
            <input type="password" name="password" id="edit-account-password"><br>
            <label>Email:</label>
            <input type="email" name="email" id="edit-account-email" required><br>
            <label>Phone:</label>
            <input type="text" name="phone" id="edit-account-phone"><br>
            <label>Address:</label>
            <input type="text" name="address" id="edit-account-address"><br>
            <label>Payment Info:</label>
            <input type="text" name="payment_info" id="edit-account-payment-info"><br>
            <label>Role:</label>
            <input type="text" name="role" id="edit-account-role" required><br>
            <label>Is Admin:</label>
            <input type="checkbox" name="is_admin" id="edit-account-is-admin"><br>
            <button type="submit">Save Changes</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

</body>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }

    #edit-account-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .logout-button {
    color: #ffffff;
    background-color: #dc3545;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    }

    .logout-button:hover {
    background-color: #c82333;
    }

    #logout-container {
    text-align: left; 
    margin-top: 10px; 
    }
</style>
<script>
    document.getElementById('add-account-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('tb_accounts_backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    document.querySelectorAll('.delete-account-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('tb_accounts_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    function editAccount(uid) {
        var row = document.querySelector(`form[data-id='${uid}']`).closest('tr');
        document.getElementById('edit-account-uid').value = uid;
        document.getElementById('edit-account-username').value = row.children[1].innerText;
        document.getElementById('edit-account-email').value = row.children[2].innerText;
        document.getElementById('edit-account-phone').value = row.children[3].innerText;
        document.getElementById('edit-account-address').value = row.children[4].innerText;
        document.getElementById('edit-account-payment-info').value = row.children[5].innerText;
        document.getElementById('edit-account-role').value = row.children[6].innerText;
        document.getElementById('edit-account-is-admin').checked = row.children[7].innerText === 'Yes';

        document.getElementById('edit-account-modal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('edit-account-modal').style.display = 'none';
    }

    document.getElementById('edit-account-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('tb_accounts_backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
</html>
