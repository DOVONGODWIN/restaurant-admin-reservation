<?php
//capture paypal
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'get_paypal_token.php';

$user_id = $_SESSION['user_id'];
$total_amount = calculate_cart_total($_SESSION['cart']);

$input = json_decode(file_get_contents('php://input'), true);
$order_id = $input['orderID'];
$db_order_id = create_order($user_id, $total_amount);

try {
    $access_token = getPayPalAccessToken();

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/" . $order_id . "/capture");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer " . $access_token
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    if ($err) {
        throw new Exception('Error: ' . $err);
    } else {
        $result = json_decode($response, true);
        if ($result['status'] === 'COMPLETED') {
            // Mettre à jour la commande dans la base de données
            update_order_status($db_order_id, 'completed');
            
            // Enregistrer les articles de la commande
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $product = get_product_details($product_id);
                add_order_item($db_order_id, $product_id, $quantity, $product['price']);
            }
            
            // Vider le panier
            $_SESSION['cart'] = array();
            
            // Retourner l'URL de redirection vers la page de confirmation
            echo json_encode(['success' => true, 'id' => $db_order_id, 'redirect' => 'index.php?page=confirmation&order_id=' . $db_order_id]);
        } else {
            throw new Exception('Payment not completed');
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}