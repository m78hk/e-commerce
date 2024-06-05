<?php
session_start();
include 'database.php'; 

$response = ['status' => 'error', 'checklistCount' => 0];

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    $stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
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

            $response['checklistCount'] = count($_SESSION['checklist']);

            $_SESSION['checklist'] = array_values($_SESSION['checklist']);
        } else {
            $response['status'] = 'already_in_checklist';
        }
    } else {
        $response['status'] = 'invalid_product_id';
    }
} else {
    $response['status'] = 'no_product_id';
}

error_log('Response status: ' . $response['status']);
error_log('Checklist count: ' . $response['checklistCount']);
error_log('Checklist items: ' . print_r($_SESSION['checklist'], true));

//var_dump($_SESSION['checklist']);

header('Content-Type: application/json');
echo json_encode($response);


?>
