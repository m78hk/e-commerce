<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
</head>
<body>
    <h1>Product Management</h1>
    <div id="acc-management-container">
        <a href="tb_accounts_backend.php" class="acc-button">Accounts Management</a>
    </div>
    <br>
    <br>
    <div id="logout-container">
        <a href="login_sk_tb.php" class="logout-button">Logout</a>
    </div>
    <br>
    <br>
    <form id="add-product-form" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_product">
        <label>Product Name:</label>
        <input type="text" name="product_name" required><br>
        <br>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required><br>
        <br>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required><br>
        <br>
        <label>Label:</label>
        <select name="label" required>
            <option value="sale">Sale</option>
            <option value="new">New</option>
            <option value="dis">Dis</option>
        </select><br>
        <br>
        <label>Rating:</label>
        <select name="rating" required>
            <option value="0.5">0.5</option>
            <option value="1">1</option>
            <option value="1.5">1.5</option>
            <option value="2">2</option>
            <option value="2.5">2.5</option>
            <option value="3">3</option>
            <option value="3.5">3.5</option>
            <option value="4">4</option>
            <option value="4.5">4.5</option>
            <option value="5">5</option>
        </select><br>
        <br>
        <label>Best Seller or Product Label:</label>
        <select name="best_seller_label" required>
            <option value="all">All</option>
            <option value="best sellers">Best Sellers</option>
            <option value="featured">Featured</option>
            <option value="new arrival">New Arrival</option>
            <option value="Computer">Computer</option>
            <option value="TV">TV</option>
            <option value="Refrigerator">Refrigerator</option>
        </select><br>
        <br>
        <label>Quantity:</label>
        <input type="number" name="quantity" required><br>
        <br>
        <button type="submit">Add Product</button>
    </form>

    <h2>Products List</h2>
    <table id="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Label</th>
                <th>Rating</th>
                <th>Best Seller Label</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'database.php';

            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $items_per_page = 10;
            $offset = ($current_page - 1) * $items_per_page;

            $stmt = $pdo->query('SELECT COUNT(*) FROM products');
            $total_products = $stmt->fetchColumn();
            $total_pages = ceil($total_products / $items_per_page);

            $stmt = $pdo->prepare('SELECT * FROM products LIMIT :offset, :items_per_page');
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll();

            foreach ($products as $product):
            ?>
                <tr>
                    <td><?= htmlspecialchars($product['product_id']) ?></td>
                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['label']) ?></td>
                    <td><?= htmlspecialchars($product['rating']) ?></td>
                    <td><?= htmlspecialchars($product['best_seller_label']) ?></td>
                    <td><?= htmlspecialchars($product['quantity']) ?></td>
                    <td>
                        <button onclick="editProduct(<?= $product['product_id'] ?>)">Edit</button>
                        <form class="delete-product-form" data-id="<?= $product['product_id'] ?>" style="display:inline;">
                            <input type="hidden" name="action" value="delete_product">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
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
    
    <div id="edit-product-modal">
        <form id="edit-product-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_product">
            <input type="hidden" name="product_id" id="edit-product-id">
            <label>Product Name:</label>
            <input type="text" name="product_name" id="edit-product-name" required><br>
            <label>Price:</label>
            <input type="number" name="price" id="edit-product-price" step="0.01" required><br>
            <label>Image:</label>
            <input type="file" name="image" id="edit-product-image" accept="image/*"><br>
            <label>Label:</label>
            <select name="label" id="edit-product-label" required>
                <option value="sale">Sale</option>
                <option value="new">New</option>
                <option value="dis">Dis</option>
            </select><br>
            <label>Rating:</label>
            <select name="rating" id="edit-product-rating" required>
                <option value="0.5">0.5</option>
                <option value="1">1</option>
                <option value="1.5">1.5</option>
                <option value="2">2</option>
                <option value="2.5">2.5</option>
                <option value="3">3</option>
                <option value="3.5">3.5</option>
                <option value="4">4</option>
                <option value="4.5">4.5</option>
                <option value="5">5</option>
            </select><br>
            <label>Best Seller or Product Label:</label>
            <select name="best_seller_or_product_label" id="edit-product-best-seller-label" required>
                <option value="all">All</option>
                <option value="best sellers">Best Sellers</option>
                <option value="featured">Featured</option>
                <option value="new arrival">New Arrival</option>
                <option value="Computer">Computer</option>
                <option value="TV">TV</option>
                <option value="Refrigerator">Refrigerator</option>
            </select><br>
            <label>Quantity:</label>
            <input type="number" name="quantity" id="edit-product-quantity" required><br>
            <button type="submit">Save Changes</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }

        #edit-product-modal {
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

        .acc-button {
            color: #ffffff;
            background-color: #dc3545;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .acc-button:hover {
            background-color: #c82333;
        }

        #acc-management-container {
            text-align: left; 
            margin-top: 10px;
        }

        .pagination-link {
            margin: 0 5px;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #ccc;
        }
    </style>
    <script>
        document.getElementById('add-product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('api/api_products.php', {
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

        document.querySelectorAll('.delete-product-form').forEach(form => {
            form.addEventListener('submit', function(event){
                event.preventDefault();
                var formData = new FormData(this);

                fetch('api/api_products.php', {
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

        function editProduct(productId) {
            var row = document.querySelector(`form[data-id='${productId}']`).closest('tr');
            document.getElementById('edit-product-id').value = productId;
            document.getElementById('edit-product-name').value = row.cells[1].textContent;
            document.getElementById('edit-product-price').value = row.cells[2].textContent;
            document.getElementById('edit-product-label').value = row.cells[3].textContent;
            document.getElementById('edit-product-rating').value = row.cells[4].textContent;
            document.getElementById('edit-product-best-seller-label').value = row.cells[5].textContent;
            document.getElementById('edit-product-quantity').value = row.cells[6].textContent;
            document.getElementById('edit-product-modal').style.display = 'block';
        }

        document.getElementById('edit-product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('api/api_products.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload();
                } 
            })
            .catch(error => console.error('Error:', error));
        });

        function closeEditModal() {
            document.getElementById('edit-product-modal').style.display = 'none';
        }
    </script>
</body>
</html>