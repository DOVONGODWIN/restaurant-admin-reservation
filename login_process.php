<?php

session_start(); /**
 * pour importer la base de donner sur cette page 
 */
require_once 'includes/db.php';
require_once 'includes/functions.php';

/**
 * Traite la demande de connexion de l'utilisateur
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: index.php?page=login");
        exit();
    }

    $user = verify_user($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['role'] == 'admin';
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: index.php?page=login");
        exit();
    }
}

/**
 * Vérifie les informations d'identification de l'utilisateur
 *
 * @param string $username Nom d'utilisateur
 * @param string $password Mot de passe
 * @return array|false Retourne les informations de l'utilisateur si la vérification réussit, sinon false
 */
function verify_user($username, $password) {
    $connx = connexionDB();
    
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connx, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($connx);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}
?>