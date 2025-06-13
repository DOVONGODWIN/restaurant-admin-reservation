<?php
session_start();
include 'includes/db.php';
require_once 'includes/cart_functions.php';
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resto.</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
    <?php
    // Définition des pages autorisées avec leurs fichiers correspondants
    $allowed_pages = [
        'accueil' => 'accueil.php',
        'a-propos' => 'a-propos.php',
        'menu' => 'menu.php',
        'temoignages' => 'temoignages.php',
        'contact' => 'contact.php',
        'login' => 'login.php',
        'register' => 'register.php',
        'panier' => 'panier.php',
        'reservation' => 'reservation.php',
        'ajouter_produit' => 'ajouter_produit.php',
        'detail_produit' => 'detail_produit.php',
        'profile' => 'profile.php',
        'modifier_produit' => 'modifier_produit.php',
        'mes_commandes' => 'mes_commandes.php',
        'detail_commande' => 'detail_commande.php',
        'confirmation' => 'confirmation.php',
        'admin_dashboard' => 'admin_dashboard.php'
    ];

    // Récupération de la page demandée, avec 'accueil' comme valeur par défaut
    $page = $_GET['page'] ?? 'accueil';

    // Vérification de l'existence de la page et inclusion du fichier correspondant
    if (array_key_exists($page, $allowed_pages)) {
        $file_to_include = $allowed_pages[$page];
        if (file_exists($file_to_include)) {
            if ($page === 'ajouter_produit' && (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id']))) {
                include $allowed_pages['accueil']; // Redirection vers l'accueil si non admin
            } elseif ($page === 'detail_produit') {
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    include $file_to_include;
                } else {
                    include $allowed_pages['menu']; // Redirection vers le menu si pas d'ID valide
                }
            } elseif ($page === 'detail_commande') {
                if (!isset($_SESSION['user_id'])) {
                    include $allowed_pages['login'];
                } elseif (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                    include $allowed_pages['mes_commandes'];
                } else {
                    $order_id = $_GET['id'];
                    $user_id = $_SESSION['user_id'];
                    if (can_view_order($user_id, $order_id)) {
                        include $file_to_include;
                    } else {
                        include $allowed_pages['mes_commandes'];
                    }
                }
            } elseif ($page === 'confirmation') {
                if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
                    include $allowed_pages['accueil'];
                } else {
                    include $file_to_include;
                }
            } elseif ($page === 'admin_dashboard' && (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id']))) {
                include $allowed_pages['accueil']; // Redirection vers l'accueil si non admin
            } else {
                include $file_to_include;
            }
        } else {
            include '404.php'; // Assurez-vous d'avoir une page 404.php
        }
    } else {
        include $allowed_pages['accueil'];
    }
    ?>
    </main>

    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>
</html>