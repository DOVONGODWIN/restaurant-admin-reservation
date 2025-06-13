<div class="auth-container">
    <div class="register">
        <h2>Inscription</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="register_process.php" method="post">
            <div class="inputboite">
                <input type="text" name="username" required>
                <label>Nom d'utilisateur</label>
            </div>
            <div class="inputboite">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="inputboite">
                <input type="password" name="password" required>
                <label>Mot de passe</label>
            </div>
            <div class="inputboite">
                <input type="password" name="confirm_password" required>
                <label>Confirmer le mot de passe</label>
            </div>
            <input type="submit" value="S'inscrire">
        </form>
        <div class="auth-switch">
            Déjà un compte ? <a href="index.php?page=login">Se connecter</a>
        </div>
    </div>
</div>
<script>

</script>