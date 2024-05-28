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
                $item['quantity'] += 1;
                $productFound = ture;
                break;
            }
        }

        if (!$productFound) {
            include 'stock.php';
            foreach ($product as $product) {
                if ($product['product_id'] == $product_id) {
                    $_SESSION['cart'] [] = [
                        'product_id' => $product_id,
                        'quantity' => 1,
                        'pricr' => $product['price']
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

        if (isset($_POST['remove'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['product_id'] == $product_id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        } elseif (isset($_POST['quantity'])) {
            $new_quantity = (int)$_POST['quantity'];
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] == $product_id) {
                    $item['quantity'] = $new_quantity;
                    break;
                }
            }
        }

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.05;
        $shipping = 15;
        $total = $subtotal + $tax + $shipping;

        $response = [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
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
    $totalQuantity = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo $totalQuantity;
    exit;

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

?>
