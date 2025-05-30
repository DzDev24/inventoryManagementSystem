<?php
// chatbot_api.php (Corrected)
header('Content-Type: application/json');

$config = include 'config.php'; // Ensure this file correctly returns an array with 'GEMINI_API_KEY'
$API_KEY = $config['GEMINI_API_KEY'] ?? '';

$input = json_decode(file_get_contents('php://input'), true);

// Get the entire conversation history sent by the client
$conversationHistory = $input['contents'] ?? [];

if (empty($API_KEY)) {
    echo json_encode(['error' => 'Missing API key']);
    exit;
}

// Check if the conversation history is empty or not provided
if (empty($conversationHistory)) {
    echo json_encode(['error' => 'Missing conversation history (contents) in the request']);
    exit;
}

// Prepare the body for the Gemini API using the full conversation history
$body = json_encode([
    'contents' => $conversationHistory // Pass the received history directly
]);

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$API_KEY";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => $body
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch); // Get cURL error if any
curl_close($ch);

if ($curlError) {
    echo json_encode(['error' => 'cURL Error: ' . $curlError]);
    exit;
}

if ($httpcode !== 200) {
    // Attempt to decode the response to get more detailed error from Gemini
    $errorDetails = json_decode($response, true);
    $errorMessage = 'Gemini API call failed';
    if (isset($errorDetails['error']['message'])) {
        $errorMessage .= ': ' . $errorDetails['error']['message'];
    }
    echo json_encode(['error' => $errorMessage, 'httpcode' => $httpcode, 'response' => $response]);
    exit;
}

echo $response; // Forward Gemini's response to the client