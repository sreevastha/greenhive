<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Gemini API Key (Replace with yours)
$API_KEY = "AIzaSyBH37GzxAOKxmbCn54dJdaOzkx2XqNS8dU";
$API_URL = "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=$API_KEY";

// Read user message from frontend
$data = json_decode(file_get_contents("php://input"), true);
$user_message = $data['message'] ?? '';

if (!$user_message) {
    echo json_encode(["response" => "Invalid request."]);
    exit;
}

// Prepare API request payload
$payload = json_encode([
    "contents" => [
        ["parts" => [["text" => $user_message]]]
    ]
]);

// Send request to Gemini API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $API_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    $response_data = json_decode($response, true);
    $bot_reply = $response_data["candidates"][0]["content"]["parts"][0]["text"] ?? "No response received.";
    echo json_encode(["response" => $bot_reply]);
} else {
    echo json_encode(["response" => "Error connecting to AI service."]);
}
?>
