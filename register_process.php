<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Vérifiez si tous les champs sont remplis
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: index.php?page=register");
        exit();
    }

      // Vérification de la complexité du mot de passe
    if (!is_password_complex($password)) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        header("Location: index.php?page=register");
        exit();
    }
    
    // Vérifiez si les mots de passe correspondent
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: index.php?page=register");
        exit();
    }

     // Vérification de la validité de l'email
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "L'adresse email n'est pas valide.";
        header("Location: index.php?page=register");
        exit();
    }
    
    // Tentez d'enregistrer l'utilisateur
    $user_id = register_user($username, $email, $password);
    
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = false;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "L'inscription a échoué. L'utilisateur ou l'email existe peut-être déjà.";
        header("Location: index.php?page=register");
        exit();
    }
}