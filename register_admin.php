<?php
session_start();
require_once 'includes/db.php'; // Assurez-vous que ce fichier contient votre fonction connexionDB()

/**
 * Vérifie si l'utilisateur est un administrateur
 *
 * @param int $user_id ID de l'utilisateur
 * @return bool Retourne true si l'utilisateur est admin, sinon false
 */
function is_admin($user_id) {
    $connx = connexionDB();
    
    $sql = "SELECT role FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connx, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        $resultData = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($resultData);
        mysqli_stmt_close($stmt);
        mysqli_close($connx);
        return $user && $user['role'] === 'admin';
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($connx);
        return false;
    }
}

/**
 * Enregistre un nouvel administrateur
 *
 * @param string $username Nom d'utilisateur
 * @param string $email Adresse email
 * @param string $password Mot de passe
 * @return bool Retourne true en cas de succès, sinon false
 */
function registerAdmin($username, $email, $password) {
    $connx = connexionDB();
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')";
    $stmt = mysqli_prepare($connx, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);
    
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        error_log("Erreur lors de l'enregistrement de l'admin : " . mysqli_error($connx));
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($connx);
    
    return $result;
}

// Vérifiez si l'utilisateur actuel est déjà un administrateur
if (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id'])) {
    die("Accès non autorisé");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (registerAdmin($username, $email, $password)) {
        echo "Administrateur enregistré avec succès";
    } else {
        echo "Erreur lors de l'enregistrement de l'administrateur";
    }
}
?>