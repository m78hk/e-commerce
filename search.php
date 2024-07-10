<?php
include 'database.php';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT product_id, product_name, price, image FROM products WHERE product_name LIKE ?");
    $stmt->execute(['%' . $search . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        if (!isset($result['price'])) {
            $result['price'] = 'N/A';
        }
        
        echo '<a href="product_1.php?product_id="' . htmlspecialchars($result['product_id']) . '">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($result['image']) . '" alt="' 
                . htmlspecialchars($result['product_name']) . '" width="50" height="100">';
        echo '<div>';
        echo '<div>' . htmlspecialchars($result['product_name']) . '</div>';
        echo '<div>' . htmlspecialchars($result['price']) . ' HKD</div>';
        echo '</div>';
        echo '</a>';
    }
}
?>