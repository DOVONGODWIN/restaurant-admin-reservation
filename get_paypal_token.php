<?php
//get token
function getPayPalAccessToken() {
    $clientId = 'AZQMtRuAFe8ouLGcp7N8gHzOp9y54gS4kHFkeGE_7GBXKxGVe9e13CF8S62pqtUbvhqLjbnsp_qawC10';
    $secret = 'EJ6MaOTWyPxO5hd2lYZQt27elOv7UYPUPWEUQ9OnQyzz0c_D95gLt8iuVzM-E2wCAD0QKInxFpxVHs-5';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);

    $headers = array();
    $headers[] = "Accept: application/json";
    $headers[] = "Accept-Language: en_US";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception('Error:' . curl_error($ch));
    }
    curl_close($ch);

    $result = json_decode($result, true);
    
    if (isset($result['access_token'])) {
        return $result['access_token'];
    } else {
        throw new Exception('Failed to get access token');
    }
}