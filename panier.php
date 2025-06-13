<?php
//session_start();
require_once 'includes/cart_functions.php';
require_once 'includes/functions.php';

$cart = getCartContents();
$cart_items = [];

foreach ($cart as $product_id => $quantity) {
    $product = get_product_details($product_id);
    if ($product) {
        $cart_items[] = [
            'id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
}

$total = calculate_cart_total($cart_items);
?>

<section class="panier">
    <div class="panier-container">
        <h2>Votre Panier</h2>
        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Votre panier est vide.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td data-label="Produit" class="product-name"><?php echo $item['name']; ?></td>
                        <td data-label="Prix"><?php echo $item['price']; ?> $</td>
                        <td data-label="Quantité">
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" data-id="<?php echo $item['id']; ?>" class="quantity-input">
                        </td>
                        <td data-label="Total"><?php echo $item['price'] * $item['quantity']; ?> $</td>
                        <td data-label="Action">
                            <button class="remove-item" data-id="<?php echo $item['id']; ?>">Supprimer</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">
                Total: <span id="cart-total"><?php echo $total; ?></span> $
            </div>
            <button id="checkout-btn" class="btn-primary">Passer la commande</button>
            <div id="paypal-button-container" style="display: none;"></div>
        <?php endif; ?>
    </div>
</section>

<script src="https://www.paypal.com/sdk/js?client-id=AZQMtRuAFe8ouLGcp7N8gHzOp9y54gS4kHFkeGE_7GBXKxGVe9e13CF8S62pqtUbvhqLjbnsp_qawC10&currency=USD"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const removeButtons = document.querySelectorAll('.remove-item');
    const checkoutBtn = document.getElementById('checkout-btn');
    const paypalContainer = document.getElementById('paypal-button-container');

    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateCartItemQuantity(this.dataset.id, this.value);
        });
    });

    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            removeCartItem(this.dataset.id);
        });
    });

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            // Vérifier si l'utilisateur a une adresse et un numéro de téléphone
            fetch('check_user_info.php')
                .then(response => response.json())
                .then(data => {
                    if (data.hasInfo) {
                        // L'utilisateur a les informations nécessaires, continuer avec le paiement
                        checkoutBtn.style.display = 'none';
                        if (paypalContainer) {
                            paypalContainer.style.display = 'block';
                        }
                    } else {
                        // Rediriger l'utilisateur vers la page de profil
                        window.location.href = 'index.php?page=profile&message=complete_info';
                    }
                });
        });
    }

    function updateCartItemQuantity(productId, quantity) {
        fetch('update_cart_quantity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la mise à jour de la quantité');
            }
        });
    }

    function removeCartItem(productId) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression de l\'article');
            }
        });
    }

    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch('create_paypal_order.php', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    cart_items: <?php echo json_encode($cart_items); ?>
                })
            }).then(function(res) {
                return res.json();
            }).then(function(orderData) {
                return orderData.id;
            });
        },
        onApprove: function(data, actions) {
            return fetch('capture_paypal_order.php', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            }).then(function(res) {
                return res.json();
            }).then(function(orderData) {
                if (orderData.success) {
                    window.location.href = 'index.php?page=confirmation&order_id=' + orderData.id;
                } else {
                    alert('Une erreur est survenue lors du traitement de votre commande.');
                }
            });
        }
    }).render('#paypal-button-container');
});
</script>