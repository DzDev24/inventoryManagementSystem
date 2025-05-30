<?php
// gemini_chat.php
header('Content-Type: application/json');

$config = include 'config.php';
$API_KEY = $config['GEMINI_API_KEY'] ?? '';

$input = json_decode(file_get_contents('php://input'), true);
// $userMessage = $input['message'] ?? '';
$userMessage = $input['contents'][0]['parts'][0]['text'] ?? '';


if (!$API_KEY || !$userMessage) {
    echo json_encode([
        'error' => 'Missing API key or message ',
    ]);
    exit;
}
$body = json_encode([
    'contents' => [
        [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ]
    ]
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
curl_close($ch);

if ($httpcode !== 200) {
    echo json_encode(['error' => 'Gemini API call failed']);
    exit;
}

echo $response;
