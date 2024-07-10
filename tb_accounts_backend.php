<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
</head>
<body>
    <h1>Account Management</h1>
    <div id="supermarket-container">
        <a href="supermarket_backend.php" class="supermarket-button">Products Management</a>
    </div>
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
        <label>Payment Method:</label>
        <select name="payment_method">
            <option value="visa">Visa</option>
            <option value="master">Master</option>
            <option value="paypal">Paypal</option>
        </select><br>
        <br>
        <label>Credit Card:</label>
        <input type="text" name="credit_card"><br>
        <br>
        <label>Role:</label>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br>
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
                <th>Payment Method</th>
                <th>Credit Card</th>
                <th>Role</th>
                <th>Is Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'database.php';

            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $items_per_page = 10;
            $offset = ($current_page - 1) * $items_per_page;

            $stmt = $pdo->query('SELECT COUNT(*) FROM tb_accounts');
            $total_products = $stmt->fetchColumn();
            $total_pages = ceil($total_products / $items_per_page);

            $stmt = $pdo->prepare('SELECT * FROM tb_accounts LIMIT :offset, :items_per_page');
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
            $stmt->execute();
            $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($accounts as $account): 
            ?>
                <tr>
                    <td><?= htmlspecialchars($account['uid']) ?></td>
                    <td><?= htmlspecialchars($account['username']) ?></td>
                    <td><?= htmlspecialchars($account['email']) ?></td>
                    <td><?= htmlspecialchars($account['phone']) ?></td>
                    <td><?= htmlspecialchars($account['address']) ?></td>
                    <td><?= htmlspecialchars($account['payment_method']) ?></td>
                    <td><?= htmlspecialchars($account['credit_card']) ?></td>
                    <td><?= htmlspecialchars($account['role']) ?></td>
                    <td><?= htmlspecialchars($account['is_admin'] ? 'Yes' : 'No') ?></td>
                    <td>
                        <button onclick="editAccount(<?= $account['uid'] ?>)">Edit</button>
                        <button onclick="deleteAccount(<?= $account['uid'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <div id="pagination"> 
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="pagination-link"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    
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
            <label>Payment Method:</label>
            <select name="payment_method" id="edit-account-payment-method">
                <option value="visa">Visa</option>
                <option value="master">Master</option>
                <option value="paypal">Paypal</option>
            </select><br>
            <label>Credit Card:</label>
            <input type="text" name="credit_card" id="edit-account-credit-card"><br>
            <label>Role:</label>
            <select name="role" id="edit-account-role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br>
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

    .supermarket-button {
        color: #ffffff;
        background-color: #dc3545;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }

    .supermarket-button:hover {
        background-color: #c82333;
    }

    #supermarket-container {
        text-align: left; 
        margin-top: 10px;
    }
</style>
<script>
    function loadAccounts() {
        fetch('api/get_account.php?action=list_accounts')
            .then(response => response.json())
            .then(data => {
                const accountsTable = document.getElementById('accounts-table').getElementsByTagName('tbody')[0];
                accountsTable.innerHTML = '';

                data.accounts.forEach(account => {
                    const row = accountsTable.insertRow();
                    row.insertCell(0).textContent = account.uid;
                    row.insertCell(1).textContent = account.username;
                    row.insertCell(2).textContent = account.email;
                    row.insertCell(3).textContent = account.phone;
                    row.insertCell(4).textContent = account.address;
                    row.insertCell(5).textContent = account.payment_method;
                    row.insertCell(6).textContent = account.credit_card;
                    row.insertCell(7).textContent = account.role;
                    row.insertCell(8).textContent = account.is_admin ? 'Yes' : 'No';
                    const actionsCell = row.insertCell(9);
                    actionsCell.innerHTML = `<button onclick='editAccount("${account.uid}")'>Edit</button> <button onclick='deleteAccount("${account.uid}")'>Delete</button>`;
                });
            })
            .catch(error => console.error

('Error loading accounts:', error));
    }

    document.getElementById('add-account-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('api/get_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
            alert(data.message);
        } else if (data.error) {
            alert('Error: ' + data.error);
        } else {
            alert('Unexpected response: ' + JSON.stringify(data));
        }
        loadAccounts();
        })
        .catch(error => console.error('Error adding account:', error));
    });

    function editAccount(uid) {
        fetch('api/get_account.php?action=get_account&uid=' + uid)
            .then(response => response.json())
            .then(data => {
                if (data.account) {
                document.getElementById('edit-account-uid').value = data.account.uid;
                document.getElementById('edit-account-username').value = data.account.username;
                document.getElementById('edit-account-email').value = data.account.email;
                document.getElementById('edit-account-phone').value = data.account.phone;
                document.getElementById('edit-account-address').value = data.account.address;
                document.getElementById('edit-account-payment-method').value = data.account.payment_method;
                document.getElementById('edit-account-credit-card').value = data.account.credit_card;
                document.getElementById('edit-account-role').value = data.account.role;
                document.getElementById('edit-account-is-admin').checked = data.account.is_admin;
                document.getElementById('edit-account-modal').style.display = 'block';
            } else {
                alert('Error: Account not found');
            }
            })
            .catch(error => console.error('Error fetching account:', error));
    }

    document.getElementById('edit-account-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('api/get_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
            alert(data.message);
        } else if (data.error) {
            alert('Error: ' + data.error);
        } else {
            alert('Unexpected response: ' + JSON.stringify(data));
        }
        document.getElementById('edit-account-modal').style.display = 'none';
        loadAccounts();
        })
        .catch(error => console.error('Error editing account:', error));
    });

    function deleteAccount(uid) {
        if (confirm('Are you sure you want to delete this account?')) {
            fetch('api/get_account.php?action=delete_account&uid=' + uid)
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                    alert(data.message);
                } else if (data.error) {
                    alert('Error: ' + data.error);
                } else {
                    alert('Unexpected response: ' + JSON.stringify(data));
                }
                loadAccounts();
                })
                .catch(error => console.error('Error deleting account:', error));
        }
    }

    function closeEditModal() {
        document.getElementById('edit-account-modal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadAccounts();
    });
</script>
</html>