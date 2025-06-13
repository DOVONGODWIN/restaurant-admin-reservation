<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header('Location: index.php?page=contact');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "L'adresse e-mail n'est pas valide.";
        header('Location: index.php?page=contact');
        exit();
    }

    if (add_contact_message($name, $email, $message)) {
        $_SESSION['success'] = "Votre message a été envoyé avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de l'envoi du message.";
    }

    header('Location: index.php?page=contact');
    exit();
}