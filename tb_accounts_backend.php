<?php
session_start();
include 'database.php';


if (!isset($_SESSION['user']['uid'])) {
    header('Location: login_sk_tb.php');
    exit();
}

if ($_SESSION['user']['is_admin'] != 1) {
    header('Location: login_sk_tb.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

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
        <tbody id="account-list">
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

    #edit-account-form label {
        display: inline-block;
        width: 100px;
    }

    #edit-account-form input {
        margin-bottom: 10px;
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
        var formData = new FormData(event.target);
        fetch('api/accounts.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(data => {
            if (data.status === 'success') {
                loadAccounts();
                event.target.reset();
            } else {
                alert(data.message);
            }
        });
    });
    function loadAccounts() {
    fetch('api/accounts.php').then(response => response.json()).then(accounts => {
        const accountList = document.getElementById('account-list');
        accountList.innerHTML = '';
        accounts.forEach(account => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${account.uid}</td>
                <td>${account.username}</td>
                <td>${account.email}</td>
                <td>${account.phone}</td>
                <td>${account.address}</td>
                <td>${account.payment_info}</td>
                <td>${account.role}</td>
                <td>${account.is_admin ? 'Yes' : 'No'}</td>
                <td>
                    <button onclick="editAccount(${account.uid})">Eidt</button>
                    <form class="delete-account-form" style="display:inline;">
                        <input type="hidden" name="action" value="delete_account">
                        <input type="hidden" name="uid" value="${account.uid}">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            `;
            accountList.appendChild(row);
        });
        document.querySelectorAll('.delete-account-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this account?')) {
                    var formData = new FormData(event.target);
                    fetch('api/accounts.php', {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json()).then(data => {
                        if (data.status === 'success') {
                            loadAccounts();
                        } else {
                            alert(data.message);
                        }
                    });
                }
            });
        });
    });
}

function editAccount(uid) {
    fetch(`api/get_account.php?uid=${uid}`).then(response => response.json()).then(account => {
        document.getElementById('edit-account-uid').value = account.uid;
        document.getElementById('edit-account-username').value = account.username;
        document.getElementById('edit-account-email').value = account.email;
        document.getElementById('edit-account-phone').value = account.phone;
        document.getElementById('edit-account-address').value = account.address;
        document.getElementById('edit-account-payment-info').value = account.payment_info;
        document.getElementById('edit-account-role').value = account.role;
        document.getElementById('edit-account-is-admin').checked = account.is_admin;
        document.getElementById('edit-account-modal').style.display = 'block';
    });
}

document.getElementById('edit-account-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(event.target);
    fetch('api/accounts.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if (data.status === 'success') {
            closeEditModal();
            loadAccounts();
        } else {
            alert(data.message);
        }
    });
});

function closeEditModal() {
    document.getElementById('edit-account-modal').style.display = 'none';
}

// load accounts when the page is loaded
loadAccounts();
</script>
</html>
