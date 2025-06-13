<?php
session_start();
require_once 'includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    updateCartQuantity($product_id, $quantity);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}