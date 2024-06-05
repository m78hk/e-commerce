<?php
include 'stock.php'; 

$servername = "localhost";
$username = "abcshop_db_user";
$password = "abcshop_db";
$dbname = "abcshop_mydb";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("connect error: " . $conn->connect_error);
}

foreach ($products as $product) {
    $product_id = $product['product_id'];
    $product_name = $conn->real_escape_string($product['product_name']);
    $price = $product['price'];
    $image = $conn->real_escape_string($product['image']);
    $label = $conn->real_escape_string($product['label']);
    $rating = $product['rating'];
    $best_seller_label = $conn->real_escape_string($product['best_seller_label']);
    $quantity = $product['quantity'];

    $sql = "INSERT INTO products (product_id, product_name, price, image, label, rating, best_seller_label, quantity)
            VALUES ('$product_id', '$product_name', '$price', '$image', '$label', '$rating', '$best_seller_label', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        echo "update finish: $product_name\n";
    } else {
        echo "error: " . $sql . "\n" . $conn->error;
    }
}

$conn->close();
?>
