<?php
//session_start(); // Décommentez cette ligne

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Débogage
//echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";
//echo "Is Admin: " . (is_admin($_SESSION['user_id'] ?? 0) ? 'Yes' : 'No') . "<br>";

/**
 * Fonction pour ajouter un nouveau produit
 *
 * @param string $name Nom du produit
 * @param string $description Description du produit
 * @param float $price Prix du produit
 * @param string $image Chemin de l'image du produit
 * @param string $category Catégorie du produit
 * @return bool Retourne true en cas de succès, sinon false
 */
function add_product($name, $description, $price, $image, $category) {
    $connx = connexionDB();
    
    $sql = "INSERT INTO menu_items (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connx, $sql);
    mysqli_stmt_bind_param($stmt, 'ssdss', $name, $description, $price, $image, $category);
    
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        $error = mysqli_stmt_error($stmt);
        error_log("Erreur SQL lors de l'ajout du produit : " . $error);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($connx);
    
    return $result;
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id'])) {
    echo "Redirection vers la page d'accueil..."; // Débogage
    header('Location: index.php');
    exit();
}

//echo "Accès administrateur confirmé"; // Débogage

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Formulaire soumis<br>";
    var_dump($_POST);
    var_dump($_FILES);

    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';

    // echo "Données du formulaire : $name, $price, $category<br>";

    // Validation simple
    if (empty($name) || empty($price)) {
        $message = "Le nom et le prix sont obligatoires.";
    } else {
        // Traitement de l'image
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
            $filename = $_FILES["image"]["name"];
            $filetype = $_FILES["image"]["type"];
            $filesize = $_FILES["image"]["size"];

            // Vérifier l'extension du fichier
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!array_key_exists($ext, $allowed)) {
                $message = "Erreur : Veuillez sélectionner un format de fichier valide.";
            }

            // Vérifier la taille du fichier - 5MB maximum
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $message = "Erreur : La taille du fichier est supérieure à la limite autorisée.";
            }

            // Vérifier le type MIME du fichier
            if (in_array($filetype, $allowed)) {
                // Vérifier si le fichier existe avant de le télécharger.
                if (file_exists("images/menu/" . $_FILES["image"]["name"])) {
                    $message = $_FILES["image"]["name"] . " existe déjà.";
                } else {
                    move_uploaded_file($_FILES["image"]["tmp_name"], "images/menu/" . $_FILES["image"]["name"]);
                    $image = "images/menu/" . $_FILES["image"]["name"];
                }
            } else {
                $message = "Erreur : Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer.";
            }
        }

        if (empty($message)) {
            echo "Tentative d'insertion dans la base de données...<br>";
            // Insérer le produit dans la base de données
            if (add_product($name, $description, $price, $image, $category)) {
                echo "Insertion réussie<br>";
                $message = "Le produit a été ajouté avec succès.";
            } else {
                echo "Échec de l'insertion<br>";
                $message = "Une erreur est survenue lors de l'ajout du produit. Veuillez réessayer.";
            }
        }
    }
}
?>

<style>
.add-product {
    background-color: #f9f9f9;
    padding: 100px 0;
}

.add-product-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    padding: 50px;
}

.add-product h2 {
    text-align: center;
    color: #333;
    font-size: 2.5em;
    margin-bottom: 40px;
    text-transform: capitalize;
}

.add-product .inputboite {
    position: relative;
    width: 100%;
    margin-bottom: 30px;
}

.add-product .inputboite input,
.add-product .inputboite textarea,
.add-product .inputboite select {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    color: #333;
    border: none;
    outline: none;
    background: #f5f5f5;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.add-product .inputboite input:focus,
.add-product .inputboite textarea:focus,
.add-product .inputboite select:focus {
    box-shadow: 0 0 10px rgba(251, 145, 31, 0.5);
}

.add-product .inputboite textarea {
    height: 150px;
    resize: none;
}

.add-product .inputboite label {
    position: absolute;
    left: 15px;
    top: 16px;
    font-size: 16px;
    color: #777;
    transition: all 0.3s ease;
    pointer-events: none;
}

.add-product .inputboite input:focus ~ label,
.add-product .inputboite textarea:focus ~ label,
.add-product .inputboite input:valid ~ label,
.add-product .inputboite textarea:valid ~ label,
.add-product .inputboite select:valid ~ label {
    top: -12px;
    left: 10px;
    font-size: 12px;
    color: #fb911f;
    background: #fff;
    padding: 2px 4px;
    border-radius: 3px;
}

.add-product .inputboite input[type="file"] {
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
}

.add-product button[type="submit"] {
    background: #fb911f;
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 15px;
    font-size: 18px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 2px;
    transition: 0.5s;
    border-radius: 50px;
    width: 100%;
}

.add-product button[type="submit"]:hover {
    background: #d87710;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(251, 145, 31, 0.4);
}

.message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    text-align: center;
    font-size: 16px;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .add-product-container {
        padding: 30px;
    }

    .add-product h2 {
        font-size: 2em;
    }
}
</style>

<section class="add-product">
    <div class="add-product-container">
        <h2>Ajouter un produit</h2>
        <?php if (!empty($message)) : ?>
            <div class="message <?php echo strpos($message, 'succès') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form action="index.php?page=ajouter_produit" method="post" enctype="multipart/form-data">
            <div class="inputboite">
                <input type="text" id="name" name="name" required>
                <label for="name">Nom du produit</label>
            </div>
            <div class="inputboite">
                <textarea id="description" name="description" required></textarea>
                <label for="description">Description</label>
            </div>
            <div class="inputboite">
                <input type="number" id="price" name="price" step="0.01" required>
                <label for="price">Prix</label>
            </div>
            <div class="inputboite">
                <input type="text" id="category" name="category" required>
                <label for="category">Catégorie</label>
            </div>
            <div class="inputboite">
                <input type="file" id="image" name="image" required>
                <label for="image">Image</label>
            </div>
            <button type="submit">Ajouter le produit</button>
        </form>
    </div>
</section>
  