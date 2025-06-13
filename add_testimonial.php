<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

/**
 * Fonction pour ajouter un témoignage
 *
 * @param int $user_id ID de l'utilisateur
 * @param string $content Contenu du témoignage
 * @param int $rating Note donnée
 * @param string|null $image_path Chemin de l'image de profil (optionnel)
 * @return bool Retourne true en cas de succès, sinon false
 */
function add_testimonial($user_id, $content, $rating, $image_path = null) {
    $connx = connexionDB();
    
    mysqli_begin_transaction($connx);
    
    try {
        if ($image_path) {
            $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
            $stmt = mysqli_prepare($connx, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $image_path, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        
        $sql = "INSERT INTO testimonials (user_id, content, rating, is_approved) VALUES (?, ?, ?, 0)";
        $stmt = mysqli_prepare($connx, $sql);
        mysqli_stmt_bind_param($stmt, 'isi', $user_id, $content, $rating);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        if ($result) {
            mysqli_commit($connx);
            mysqli_close($connx);
            return true;
        } else {
            throw new Exception("Erreur lors de l'ajout du témoignage");
        }
    } catch (Exception $e) {
        mysqli_rollback($connx);
        mysqli_close($connx);
        error_log("Erreur lors de l'ajout du témoignage : " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Vérification et nettoyage des entrées
    $content = isset($_POST['content']) ? trim($_POST['content']) : null;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    
    // Validation des données
    if (empty($content) || $rating === null || $rating < 1 || $rating > 5) {
        die("Erreur : Données invalides. Assurez-vous de remplir tous les champs correctement.");
    }
    
    // Traitement de l'image
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!array_key_exists($ext, $allowed)) {
            die("Erreur : Format de fichier invalide.");
        }

        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            die("Erreur : Taille de fichier trop grande.");
        }

        if (in_array($filetype, $allowed)) {
            $upload_dir = "upload/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $image_path = $upload_dir . uniqid() . "." . $ext;
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                die("Erreur : Problème lors du téléchargement de l'image.");
            }
        } else {
            die("Erreur : Il y a eu un problème de téléchargement de votre fichier.");
        }
    }

    // Ajout du témoignage
    if (add_testimonial($user_id, $content, $rating, $image_path)) {
        header("Location: index.php?page=temoignages&status=success");
        exit();
    } else {
        die("Erreur lors de l'ajout du témoignage.");
    }
} else {
    die("Accès non autorisé ou utilisateur non connecté.");
}
?>