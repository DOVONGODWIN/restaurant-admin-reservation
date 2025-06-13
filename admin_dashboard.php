<?php
//session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer les statistiques
$total_users = get_total_users();
$total_products = get_total_products();
$total_revenue = get_total_revenue();
$messages = get_contact_messages();

// Récupérer tous les témoignages
$testimonials = get_all_testimonials();

// Traitement de l'approbation des témoignages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_testimonial'])) {
    approve_testimonial($_POST['testimonial_id']);
    // Rafraîchir la page pour voir les changements
    header("Location: admin_dashboard.php");
    exit();
}
// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        delete_user($_POST['user_id']);
    } elseif (isset($_POST['add_promotion'])) {
        add_promotion($_POST['product_id'], $_POST['discount']);
    }
}

// Récupérer la liste des utilisateurs et des produits
$users = get_all_users();
$products = get_all_products();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="admin-dashboard">
        <h1>Tableau de bord Administrateur</h1>

        <section class="stats">
            <h2>Statistiques</h2>
            <div class="stat-item">
                <p>Nombre d'utilisateurs inscrits : <?php echo $total_users; ?></p>
            </div>
            <div class="stat-item">
                <p>Nombre total de produits : <?php echo $total_products; ?></p>
            </div>
            <div class="stat-item">
                <p>Chiffre d'affaires total : <?php echo number_format($total_revenue, 2); ?> $</p>
            </div>
        </section>

        <section class="messages">
            <h2>Messages de contact</h2>
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <p><strong>De :</strong> <?php echo htmlspecialchars($message['name']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                    <p><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                    <p><strong>Date :</strong> <?php echo $message['created_at']; ?></p>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="user-management">
            <h2>Gestion des utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>


<section class="testimonial-management">
    <h2>Gestion des témoignages</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Contenu</th>
                <th>Note</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($testimonials as $testimonial): ?>
                <tr>
                    <td><?php echo $testimonial['id']; ?></td>
                    <td><?php echo get_username($testimonial['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($testimonial['content']); ?></td>
                    <td><?php echo $testimonial['rating']; ?></td>
                    <td><?php echo $testimonial['is_approved'] ? 'Approuvé' : 'En attente'; ?></td>
                    <td>
                        <?php if (!$testimonial['is_approved']): ?>
                            <form method="post">
                                <input type="hidden" name="testimonial_id" value="<?php echo $testimonial['id']; ?>">
                                <button type="submit" name="approve_testimonial">Approuver</button>
                            </form>
                        <?php else: ?>
                            Déjà approuvé
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

        <section class="product-management">
            <h2>Gestion des promotions</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du produit</th>
                        <th>Prix actuel</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo number_format($product['price'], 2); ?> €</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="discount" min="0" max="100" placeholder="% de réduction">
                                    <button type="submit" name="add_promotion">Appliquer la promotion</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>