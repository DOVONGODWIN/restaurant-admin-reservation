<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

if (isset($_GET['message']) && $_GET['message'] == 'complete_info') {
    $info_message = "Veuillez compléter votre adresse et votre numéro de téléphone pour pouvoir passer une commande.";
}

$user_id = $_SESSION['user_id'];
$user = get_user_info($user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $result = update_user_info($user_id, $username, $email, $phone, $address);

    if ($result) {
        $success_message = "Profil mis à jour avec succès !";
        $user = get_user_info($user_id); // Recharger les infos utilisateur
    } else {
        $error_message = "Une erreur est survenue lors de la mise à jour du profil.";
    }
}
?>

<section class="profile">
    <div class="profile-container">
    <h2>Profil de <?php echo htmlspecialchars($user['username']); ?></h2>
        
        <?php if (isset($info_message)): ?>
            <div class="info-message"><?php echo $info_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="index.php?page=profile" method="post">
            <div class="inputboite">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <label for="username">Nom d'utilisateur</label>
            </div>
            <div class="inputboite">
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <label for="email">Email</label>
            </div>
            <div class="inputboite">
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                <label for="phone">Téléphone</label>
            </div>
            <div class="inputboite">
                <textarea id="address" name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                <label for="address">Adresse</label>
            </div>
            <button type="submit">Mettre à jour le profil</button>
        </form>
    </div>
</section>