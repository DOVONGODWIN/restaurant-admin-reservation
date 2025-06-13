<?php
/**
 * Fonction pour établir une connexion à la base de données
 *
 * @return mysqli|false Retourne un objet mysqli en cas de succès, sinon false
 */
function connexionDB() {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'resto_db';

    $connx = mysqli_connect($host, $user, $password, $database);
    
    if (!$connx) {
        die("Échec de la connexion : " . mysqli_connect_error());
    }
    
    return $connx;
}