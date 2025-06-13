<?php
//creat paypal
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'get_paypal_token.php';

$cart_items = json_decode(file_get_contents('php://input'), true)['cart_items'];

$total = calculate_cart_total($cart_items);

try {
    $access_token = getPayPalAccessToken();

    // Créer une commande en attente dans votre base de données
    $user_id = $_SESSION['user_id'];
    $db_order_id = create_order($user_id, $total, 'pending');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $payload = json_encode([
        "intent" => "CAPTURE",
        "purchase_units" => [
            [
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $total
                ],
                "reference_id" => strval($db_order_id)
            ]
        ]
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

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
        if (isset($result['id'])) {
            echo json_encode(['id' => $result['id'], 'db_order_id' => $db_order_id]);
        } else {
            throw new Exception('Failed to create PayPal order');
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}