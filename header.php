<?php
//session_start(); // Assurez-vous que cette ligne est au début de votre fichier
?>
<header>
    <a href="index.php" class="logo">Resto<span>.</span></a>
    <nav class="navbar">
        <ul>
            <li><a href="index.php?page=accueil">Accueil</a></li>
            <li><a href="index.php?page=a-propos">A propos</a></li>
            <li><a href="index.php?page=menu">Menu</a></li>
            <li><a href="index.php?page=temoignages">Témoignages</a></li>
            <li><a href="index.php?page=contact">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="index.php?page=panier">
                        <img src="./images/panier.png" alt="Panier" style="width: 20px; height: 20px; display: inline-block !important; vertical-align: middle;">
                        <span class="cart-count" id="cart-count">
                            <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        </span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="user-menu">
                <input type="checkbox" id="user-menu-toggle" class="user-menu-toggle">
                <label for="user-menu-toggle" class="user-icon">
                    <img src="./images/utilisateur.png" alt="User" style="width: 20px; height: 20px; display: inline-block !important; vertical-align: middle;">
                </label>
                <ul class="dropdown">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php?page=profile">Profil</a></li>
                        <li><a href="index.php?page=mes_commandes">Commandes</a></li>
                        <?php if (is_admin($_SESSION['user_id'])): ?>
                            <li><a href="index.php?page=ajouter_produit">
                                <img src="./images/bouton-ajouter.png" alt="Ajouter un produit" style="width: 20px; height: 20px; display: inline-block !important; vertical-align: middle;">
                               
                            </a></li>
                            <li><a href="index.php?page=admin_dashboard">
                                <img src="./images/dashboard.png" alt="Tableau de bord" style="width: 20px; height: 20px; display: inline-block !important; vertical-align: middle;">
                                
                            </a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">Connexion</a></li>
                        <li><a href="index.php?page=register">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>
    <a href="index.php?page=reservation" class="btn-reserve">Réservation</a>
    <div class="menu-toggle">
        <img src="./images/menu.png" alt="Menu" class="icon-open">
        <img src="./images/close.png" alt="Close" class="icon-close">
    </div>
</header>