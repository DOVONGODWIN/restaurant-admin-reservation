<?php
//session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: index.php?page=mes_commandes');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

$order = get_order_details($order_id, $user_id);

if (!$order) {
    header('Location: index.php?page=mes_commandes');
    exit();
}

$is_admin = is_admin($user_id);

// Traitement de la suppression de commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    if ($is_admin || $order['user_id'] == $user_id) {
        if (delete_order($order_id)) {
            $_SESSION['success_message'] = "La commande a été supprimée avec succès.";
            header('Location: index.php?page=mes_commandes');
            exit();
        } else {
            $error_message = "Une erreur est survenue lors de la suppression de la commande.";
        }
    } else {
        $error_message = "Vous n'avez pas les droits pour supprimer cette commande.";
    }
}
?>

<section class="detail-commande">
    <h2>Détails de la commande #<?php echo $order['id'] ?? 'N/A'; ?></h2>

    <div class="order-summary">
        <p>Date : <?php echo isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : 'N/A'; ?></p>
        <p>Statut : <span class="status <?php echo $order['status'] ?? ''; ?>"><?php echo get_status_fr($order['status'] ?? ''); ?></span></p>
        <p>Total : <?php echo number_format($order['total_amount'] ?? 0, 2); ?> €</p>
    </div>
    <h3>Articles commandés</h3>
    <table class="order-items">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php if (isset($order['items']) && is_array($order['items'])): ?>
            <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td><?php echo $item['name'] ?? 'N/A'; ?></td>
                    <td><?php echo $item['quantity'] ?? 0; ?></td>
                    <td><?php echo number_format($item['price'] ?? 0, 2); ?> €</td>
                    <td><?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2); ?> €</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Aucun article trouvé pour cette commande.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="order-actions">
        <a href="index.php?page=mes_commandes" class="btn-back">Retour aux commandes</a>
        <a href="index.php?page=menu" class="btn-order-again">Commander à nouveau</a>
        <?php if ($is_admin || $order['user_id'] == $user_id): ?>
            <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                <button type="submit" name="delete_order" class="btn-delete">Supprimer la commande</button>
            </form>
        <?php endif; ?>
    </div>
</section>