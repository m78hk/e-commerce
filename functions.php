<?php
if (!function_exists('getCartQuantity')) {
    function getCartQuantity() {
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}
}

?>
