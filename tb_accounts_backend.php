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

    function loadAccounts() {
        fetch('api/get_account.php', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const accounts = data.accounts;
                const tbody = document.querySelector('#accounts-table tbody');
                tbody.innerHTML = '';
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
                            <button onclick="editAccount(${account.uid})">Edit</button>
                            <form class="delete-account-form" data-id="${account.uid}" style="display:inline;">
                                <input type="hidden" name="action" value="delete_account">
                                <input type="hidden" name="uid" value="${account.uid}">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                alert('Failed to load accounts: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    
    window.onload = loadAccounts;

    
    document.getElementById('add-account-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('api/get_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                loadAccounts();
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

            fetch('api/get_account.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    loadAccounts();
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

        fetch('api/get_account.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                loadAccounts();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
</html>