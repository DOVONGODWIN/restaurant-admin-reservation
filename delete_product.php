<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

/**
 * Fonction pour supprimer un produit
 *
 * @param int $product_id ID du produit à supprimer
 * @return bool Retourne true en cas de succès, sinon false
 */
function delete_product($product_id) {
    $connx = connexionDB();
    
    $sql = "DELETE FROM menu_items WHERE id = ?";
    $stmt = mysqli_prepare($connx, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    
    $result = mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
    mysqli_close($connx);
    
    return $result;
}

// Vérification de l'authentification et des droits d'administrateur
if (!isset($_SESSION['user_id']) || !is_admin($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

// Traitement de la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $product_id = $_POST['id'];
    
    if (delete_product($product_id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
}