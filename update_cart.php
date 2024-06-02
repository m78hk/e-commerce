<?php
session_start();
include 'stock.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        $productFound = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                if (isset($_POST['remove'])) {
                    unset($item);
                } elseif (isset($_POST['quantity'])) {
                    $new_quantity = (int)$_POST['quantity'];
                    $item['quantity'] = $new_quantity;
                } else {
                    $item['quantity'] += 1;
                }
                $productFound = true;
                break;
            }
        }

        if (!$productFound) {
            include 'stock.php';
            foreach ($products as $product) {
                if ($product['product_id'] == $product_id) {
                    $_SESSION['cart'][] = [
                        'product_id' => $product_id,
                        'quantity' => 1,
                        'price' => $product['price']
                    ];
                    break;
                }
            }
        }

        $totalQuantity = array_sum(array_column($_SESSION['cart'], 'quantity'));

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

