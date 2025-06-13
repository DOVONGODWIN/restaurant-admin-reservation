<?php
//session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Affichage des témoignages
$testimonials = get_approved_testimonials();
?>

<section id="temoignage" class="temoignage">
    <div class="titre blanc">
        <h2 class="titre-text">Que Disent Nos <span>C</span>lients</h2>
        <p>Découvrez les expériences de nos clients satisfaits.</p>
    </div>
    <div class="contenu">
        <?php foreach ($testimonials as $testimonial): ?>
        <div class="box">
            <div class="imbox">
                <img src="<?php echo get_user_image($testimonial['user_id']); ?>" alt="Photo de profil">
            </div>
            <div class="text">
                <p><?php echo htmlspecialchars($testimonial['content']); ?></p>
                <div class="rating">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $testimonial['rating'] ? '★' : '☆';
                    }
                    ?>
                </div>
                <h3><?php echo get_username($testimonial['user_id']); ?></h3>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Formulaire d'ajout de témoignage -->
    <div class="add-testimonial">
        <h3>Partagez votre expérience</h3>
        <form action="add_testimonial.php" method="post" enctype="multipart/form-data">
            <div class="inputboite">
                <textarea name="content" placeholder="Votre commentaire" required></textarea>
            </div>
            <div class="inputboite">
                <select name="rating" required>
                    <option value="">Sélectionnez une note</option>
                    <option value="1">1 étoile</option>
                    <option value="2">2 étoiles</option>
                    <option value="3">3 étoiles</option>
                    <option value="4">4 étoiles</option>
                    <option value="5">5 étoiles</option>
                </select>
            </div>
            <div class="inputboite">
                <input type="file" name="image" accept="image/*">
            </div>
            <div class="inputboite">
                <input type="submit" value="Ajouter votre témoignage">
            </div>
        </form>
    </div>
    <?php else: ?>
    <p>Connectez-vous pour laisser un témoignage.</p>
    <?php endif; ?>
</section>