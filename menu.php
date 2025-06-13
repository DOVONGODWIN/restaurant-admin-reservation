
<?php 
require_once 'includes/cart_functions.php';
require_once 'includes/functions.php'; // Assurez-vous que ce fichier contient la fonction is_admin()

$search = isset($_GET['search']) ? $_GET['search'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$menu_items = get_menu_items($search, $min_price, $max_price, $sort, $category);
$is_admin = isset($_SESSION['user_id']) && is_admin($_SESSION['user_id']);
$categories = get_all_categories();
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
?>

<section id="menu" class="menu">
    <div class="titre">
        <h2 class="titre-text">Notre <span>M</span>enu</h2>
        <p>Découvrez nos délicieuses spécialités préparées avec soin.</p>
    </div>

    <div class="search-sort">
        <form action="index.php" method="GET">
            <input type="hidden" name="page" value="menu">
            <input type="text" name="search" placeholder="Rechercher un plat" value="<?php echo htmlspecialchars($search); ?>">
            <input type="number" name="min_price" placeholder="Prix min" value="<?php echo htmlspecialchars($min_price); ?>">
            <input type="number" name="max_price" placeholder="Prix max" value="<?php echo htmlspecialchars($max_price); ?>">
            <select name="category">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $selected_category === $cat ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="sort">
                <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Nom</option>
                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Prix croissant</option>
                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Prix décroissant</option>
                <option value="popularity" <?php echo $sort == 'popularity' ? 'selected' : ''; ?>>Popularité</option>
            </select>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div class="contenu">
        <?php foreach ($menu_items as $item): ?>
        <div class="box">
            <div class="imbox">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
            </div>
            <div class="text">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p><?php echo htmlspecialchars(substr($item['description'], 0, 100)) . '...'; ?></p>
                <span class="price"><?php echo number_format($item['price'], 2); ?> $</span>
            </div>
            <a href="index.php?page=detail_produit&id=<?php echo $item['id']; ?>" class="details-btn">Voir les détails</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="acheter" data-id="<?php echo $item['id']; ?>">Ajouter au panier</button>
            <?php endif; ?>
            <?php if ($is_admin): ?>
                <a href="index.php?page=modifier_produit&id=<?php echo $item['id']; ?>" class="admin-btn">Modifier</a>
                <button class="admin-btn delete-product" data-id="<?php echo $item['id']; ?>">Supprimer</button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-product');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                const productId = this.getAttribute('data-id');
                // Envoyer une requête AJAX pour supprimer le produit
                fetch('delete_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + productId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.box').remove();
                    } else {
                        alert('Erreur lors de la suppression du produit');
                    }
                });
            }
        });
    });
     // Ajouter au panier
     const addToCartButtons = document.querySelectorAll('.acheter');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
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
        });
    });

    // Mise à jour du compteur du panier
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