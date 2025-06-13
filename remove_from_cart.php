<?php
session_start();
require_once 'includes/cart_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}