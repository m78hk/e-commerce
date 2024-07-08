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
                <?php            
                include 'database.php';

                $total_pages = 0;
                    
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $items_per_page = 10;
                $offset = ($current_page - 1) * $items_per_page;
                    
                $stmt = $pdo->query('SELECT COUNT(*) FROM tb_accounts');
                $total_products = $stmt->fetchColumn();
                $total_pages = ceil($total_products / $items_per_page);
                    
                $stmt = $pdo->prepare('SELECT * FROM products LIMIT :offset, :items_per_page');
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
                $stmt->execute();
                $products = $stmt->fetchAll();
                foreach ($accounts as $account): 
                ?>
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
                                <input type="hidden" name="uid" value="<?= $account['uid'] ?>">
                                <button type="button" onclick="deleteAccount(<?= $account['uid']?>)">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
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

document.getElementById('add-account-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('api/get_account.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                loadAccounts();
                this.reset();
            }
        })
        .catch(error => console.error('Error:', error));
    });

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
                    row.insertCell(5).textContent = account.payment_info;
                    row.insertCell(6).textContent = account.role;
                    row.insertCell(7).textContent = account.is_admin ? 'Yes' : 'No';
                    const actionsCell = row.insertCell(8);
                    actionsCell.innerHTML = `<button onclick="editAccount(${account.uid})">Edit</button> <button onclick="deleteAccount(${account.uid})">Delete</button>`;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    function editAccount(uid) {
        fetch(`api/get_account.php?action=get_account&uid=${uid}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const account = data.account;
                    document.getElementById('edit-account-uid').value = account.uid;
                    document.getElementById('edit-account-username').value = account.username;
                    document.getElementById('edit-account-password').value = '';
                    document.getElementById('edit-account-email').value = account.email;
                    document.getElementById('edit-account-phone').value = account.phone;
                    document.getElementById('edit-account-address').value = account.address;
                    document.getElementById('edit-account-payment-info').value = account.payment_info;
                    document.getElementById('edit-account-role').value = account.role;
                    document.getElementById('edit-account-is-admin').checked = account.is_admin;
                    document.getElementById('edit-account-modal').style.display = 'block';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function deleteAccount(uid) {
        if (confirm('Are you sure you want to delete this account?')) {
            fetch('api/get_account.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `uid=${uid}`,
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    loadAccounts();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function closeEditModal() {
        document.getElementById('edit-account-modal').style.display = 'none';
    }

    document.getElementById('edit-account-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('api/get_account.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                loadAccounts();
                closeEditModal();
            }
        })
        .catch(error => console.error('Error:', error));
    });

    window.onload = function () {
        loadAccounts();
    }

</script>
</html>