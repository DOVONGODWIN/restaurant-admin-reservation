<?php

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Ajouter un produit au panier
function addToCart($product_id, $quantity = 1) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Retirer un produit du panier
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Mettre à jour la quantité d'un produit dans le panier
function updateCartQuantity($product_id, $quantity) {
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        removeFromCart($product_id);
    }
}

// Obtenir le contenu du panier
function getCartContents() {
    return $_SESSION['cart'];
}

// Vider le panier
function emptyCart() {
    $_SESSION['cart'] = array();
}