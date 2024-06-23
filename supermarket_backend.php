<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'database.php';



// 確保用戶已登入
if (!isset($_SESSION['user']['uid'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['user']) || !isAdmin($pdo, $_SESSION['user']['uid'])) {
    header('Location: index.php');
    exit;
}

// 處理 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // add new product
    if (isset($_POST['action']) && $_POST['action'] === 'add_product') {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $image = file_get_contents($_FILES['image']['tmp_name']); 
        $label = $_POST['label'];
        $rating = $_POST['rating'];
        $best_seller_label = $_POST['best_seller_label'];
        $quantity = $_POST['quantity'];

        $stmt = $pdo->prepare('INSERT INTO products (product_name, price, image, label, rating, best_seller_label, quantity) VALUES (?, ?, ?, ?, ?, ?, ?)');
        if ($stmt->execute([$product_name, $price, $image, $label, $rating, $best_seller_label, $quantity])) {
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
        }
        exit;
    }

    // delete product
    if (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $product_id = $_POST['product_id'];

        $stmt = $pdo->prepare('DELETE FROM products WHERE product_id = ?');
        if ($stmt->execute([$product_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
        }
        exit;
    }

    // edit product
    if (isset($_POST['action']) && $_POST['action'] === 'edit_product') {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $label = $_POST['label'];
        $rating = $_POST['rating'];
        $best_seller_label = $_POST['best_seller_label'];
        $quantity = $_POST['quantity'];

        if (!empty($_FILES['image']['tmp_name'])) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $stmt = $pdo->prepare('UPDATE products SET product_name = ?, price = ?, 
            image = ?, label = ?, rating = ?, best_seller_label = ?, quantity = ? WHERE product_id = ?');
            $params = [$product_name, $price, $image, $label, $rating, $best_seller_label, $quantity, $product_id];
        } else {
            $stmt = $pdo->prepare('UPDATE products SET product_name = ?, price = ?, label = ?, 
            rating = ?, best_seller_label = ?, quantity = ? WHERE product_id = ?');
            $params = [$product_name, $price, $label, $rating, $best_seller_label, $quantity, $product_id];
        }

        if ($stmt->execute($params)) {
            echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
        }
        exit;
    }
}

// get all products
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Backend</title>
    <body>
        <h1>Product Mangement</h1>
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
            <input type="text" name="label"><br>
            <br>
            <label>Rating:</label>
            <input type="number" name="rating" step="0.1" min="0" max="5"><br>
            <br>
            <label> Best Seller Label:</label>
            <input type="text" name="best_seller_label"><br>
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
                <?php foreach ($products as $product): ?>
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
        
        <div id="edit-product-modal">
            <form id="edit-product-form" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit_product">
                <input type="hidden" name="product_id" id="edit-product-id">
                <label>Product Name:</label>
                <input type="text" name="product_name" id="edit-product-name"required><br>
                <label>Price:</label>
                <input type="number" name="price" id="edit-product-price" step="0.01" required><br>
                <label>Image:</label>
                <input type="file" name="image" id="edit-product-image" accept="image/*"><br>
                <label>Label:</label>
                <input type="text" name="label" id="edit-product-label"><br>
                <label>Rating:</label>
                <input type="number" name="rating" id="edit-product-rating" step="0.1" min="0" max="5"><br>
                <label>Best Seller Label:</label>
                <input type="text" name="best_seller_label" id="edit-product-best-seller-label"><br>
                <label>Quantity:</label>
                <input type="number" name="quantity" id="edit-product-quantity" required><br>
                <button type="submit">Save Changes</button>
                <button type="botton" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>

    </body>
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


    </style>
    <script>
        document.getElementById('add-product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('supermarket_backend.php', {
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

                fetch('supermarket_backend.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                ithen(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                }).catch(error => console.error('Error:', error));
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

            fetch('supermarket_backend.php', {
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
</head>
</html>