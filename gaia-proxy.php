<?php
// Load environment variables (API key stored securely)
$apiKey = getenv("GROQ_API_KEY");

// Get the incoming POST request body
$data = json_decode(file_get_contents('php://input'), true);

// Validate prompt
if (!isset($data['prompt']) || empty(trim($data['prompt']))) {
    echo json_encode(["error" => "No prompt provided."]);
    exit;
}

// Send request to Groq API (Mistral-7B model)
$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "mixtral-8x7b-32768",
    "messages" => [
        ["role" => "system", "content" => "You are Gaia, a helpful, elegant AI assistant for LordAwakened."],
        ["role" => "user", "content" => $data['prompt']]
    ]
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Return API response to frontend
http_response_code($httpCode);
echo $response;
