<?php

/**
 * Paystack Webhook Handler (Vercel Serverless Function)
 * This forwards Paystack webhook events to your PHPNuxBill installation
 */

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Paystack-Signature');
header('Content-Type: application/json');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get the payload
$input = file_get_contents('php://input');
$event = json_decode($input, true);

// Verify Paystack signature
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
$paystackSecret = getenv('PAYSTACK_SECRET');

if (!$paystackSecret) {
    error_log('PAYSTACK_SECRET environment variable not set');
    http_response_code(500);
    echo json_encode(['error' => 'Server configuration error']);
    exit;
}

$computedSignature = hash_hmac('sha512', $input, $paystackSecret);

if ($signature !== $computedSignature) {
    error_log('Invalid Paystack signature');
    http_response_code(401);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

// Log the event for debugging
error_log('Paystack Webhook Event: ' . json_encode($event));

// Forward to PHPNuxBill
$phpnuxbillUrl = getenv('PHPNUXBILL_URL') ?: 'http://localhost/phpnuxbill';
$webhookUrl = $phpnuxbillUrl . '/index.php?_route=plugin/hotspot_payment_gateway_paystack_webhook';

// Initialize cURL
$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Paystack-Signature: ' . $signature,
    'User-Agent: Paystack-Webhook-Forwarder/1.0'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Execute and get response
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Log the forwarding result
if ($error) {
    error_log('Failed to forward webhook: ' . $error);
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to forward webhook',
        'error' => $error
    ]);
} else {
    error_log('Webhook forwarded successfully. HTTP Code: ' . $httpCode);
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Webhook forwarded to PHPNuxBill',
        'http_code' => $httpCode,
        'event_type' => $event['event'] ?? 'unknown'
    ]);
}
