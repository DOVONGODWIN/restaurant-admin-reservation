<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error_message = '';
$success_message = '';

// Vérifier si un ID de produit est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?page=menu');
    exit();
}

$product_id = $_GET['id'];
$product = get_product_details($product_id);

if (!$product) {
    header('Location: index.php?page=menu');
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Validation simple
    if (empty($name) || empty($price)) {
        $error_message = "Le nom et le prix sont obligatoires.";
    } else {
        // Traitement de l'image
        $image = $product['image']; // Garder l'ancienne image par défaut
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["image"]["name"];
            $filetype = $_FILES["image"]["type"];
            $filesize = $_FILES["image"]["size"];

            // Vérifier l'extension du fichier
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                $error_message = "Erreur : Veuillez sélectionner un format de fichier valide.";
            }

            // Vérifier la taille du fichier - 5MB maximum
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $error_message = "Erreur : La taille du fichier est supérieure à la limite autorisée.";
            }

            // Vérifier le type MIME du fichier
            if (in_array($filetype, $allowed)) {
                // Vérifier si le fichier existe avant de le télécharger.
                if (file_exists("images/menu/" . $_FILES["image"]["name"])) {
                    $error_message = $_FILES["image"]["name"] . " existe déjà.";
                } else {
                    move_uploaded_file($_FILES["image"]["tmp_name"], "images/menu/" . $_FILES["image"]["name"]);
                    $image = "images/menu/" . $_FILES["image"]["name"];
                }
            } else {
                $error_message = "Erreur : Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
            }
        }

        if (empty($error_message)) {
            // Mettre à jour le produit dans la base de données
            $result = update_product($product_id, $name, $description, $price, $image, $category);

            if ($result) {
                $success_message = "Le produit a été mis à jour avec succès.";
                $product = get_product_details($product_id); // Recharger les détails du produit
            } else {
                $error_message = "Une erreur est survenue lors de la mise à jour du produit.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le produit - Resto.</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="modifier-produit">
            <h2>Modifier le produit</h2>
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($success_message)) : ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <form action="index.php?page=modifier_produit&id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom du produit :</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Prix :</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="category">Catégorie :</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>">
                </div>
                <div class="form-group">
                    <label for="image">Image :</label>
                    <input type="file" id="image" name="image">
                    <?php if (!empty($product['image'])) : ?>
                        <p>Image actuelle : <?php echo $product['image']; ?></p>
                    <?php endif; ?>
                </div>
                <button type="submit">Mettre à jour le produit</button>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>