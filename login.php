<div class="auth-container">
    <div class="login">
        <h2>Connexion</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="login_process.php" method="post">
            <div class="inputboite">
                <input type="text" name="username" required>
                <label>Nom d'utilisateur</label>
            </div>
            <div class="inputboite">
                <input type="password" name="password" required>
                <label>Mot de passe</label>
            </div>
            <input type="submit" value="Se connecter">
        </form>
        <div class="auth-switch">
            Pas encore de compte ? <a href="index.php?page=register">S'inscrire</a>
        </div>
    </div>
</div>