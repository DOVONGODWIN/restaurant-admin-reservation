<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Vérifier si un ID de produit est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?page=menu');
    exit();
}

$product_id = $_GET['id'];

// Récupérer les détails du produit
$product = get_product_details($product_id);

if (!$product) {
    header('Location: index.php?page=menu');
    exit();
}

// Le reste du code HTML suit...
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit - <?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .product-details {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            padding: 50px;
        }

        .product-details h2 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .product-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .product-info p {
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .product-price {
            font-size: 1.5em;
            color: #fb911f;
            font-weight: bold;
            margin-top: 20px;
        }

        .add-to-cart {
            display: inline-block;
            background-color: #fb911f;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #d87710;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="product-details">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        <div class="product-info">
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <p>Catégorie : <?php echo htmlspecialchars($product['category']); ?></p>
            <p class="product-price">Prix : <?php echo number_format($product['price'], 2); ?> $</p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Ajouter au panier</button>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartButton = document.querySelector('.add-to-cart');
            if (addToCartButton) {
                addToCartButton.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    addToCart(productId);
                });
            }

            function addToCart(productId) {
                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Produit ajouté au panier');
                        updateCartCount();
                    } else {
                        alert('Erreur lors de l\'ajout au panier');
                    }
                });
            }

            function updateCartCount() {
                fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.count;
                    }
                });
            }
        });
</script>
</body>
</html>