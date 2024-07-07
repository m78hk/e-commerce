<?php
session_start();
include '../database.php';



if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $productFound = false;

        foreach ($_SESSION['cart'] as $key => &$item) {
            if ($item['product_id'] == $product_id) {

                    $item['quantity'] += 1;
                
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            if ($product) {
                $_SESSION['cart'] [] = [
                    'product_id' => $product_id,
                    'quantity' => 1,
                    'price' => $product['price'],
                ];
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Product not found']);
                exit;
            }
        }

        $totalQuantity = array_sum(array_column($_SESSION['cart'], 'quantity'));
        $_SESSION['cart_quantity'] = $totalQuantity;

        $response = [
            'status' => 'success',
            'totalQuantity' => $totalQuantity
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo array_sum(array_column($_SESSION['cart'], 'quantity'));
    exit;

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}
?>
