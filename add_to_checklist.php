<?php
session_start();
include 'stock.php';

$response = ['status' => 'error', 'checklistCount' => 0];

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    $productFound = false;
    foreach ($products as $product) {
        if ($product['product_id'] == $productId) {
            $productFound = true;
            break;
        }
    }

    if ($productFound) {
        if (!isset($_SESSION['checklist'])) {
            $_SESSION['checklist'] = [];
        }

        
        $alreadyInChecklist = false;
        foreach ($_SESSION['checklist'] as $item) {
            if ($item['product_id'] == $productId) {
                $alreadyInChecklist = true;
                break;
            }
        }

        if (!$alreadyInChecklist) {
            $_SESSION['checklist'][] = $product;
            $response['status'] = 'success';
        } else {
            $response['status'] = 'already_in_checklist';
            
        }
        
        $response['checklistCount'] = count($_SESSION['checklist']);
    } else {
        $response['status'] = 'invalid_product_id';
    }
} else {
    $response['status'] = 'no_product_id';
}

echo json_encode($response);
?>
