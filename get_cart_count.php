<?php
session_start();
require_once 'includes/cart_functions.php';

$cart = getCartContents();
$count = array_sum($cart);

echo json_encode(['count' => $count]);