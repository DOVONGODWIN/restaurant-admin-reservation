<?php
session_start();
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['hasInfo' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$hasInfo = user_has_address_and_phone($user_id);

echo json_encode(['hasInfo' => $hasInfo]);
?>