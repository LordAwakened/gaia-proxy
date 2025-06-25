<?php
// ✅ CORS HEADERS - MUST come first before anything else
header("Access-Control-Allow-Origin: https://projectgaia.great-site.net");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// ✅ Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ✅ Handle missing POST body
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['prompt']) || empty(trim($input['prompt']))) {
    echo json_encode(["error" => "No prompt provided."]);
    exit();
}

$prompt = $input['prompt'];

// ✅ Prepare request to Groq (or OpenAI)
$apiKey = "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"; // 🔐 Replace with your real key
$payload = [
    "messages" => [
        ["role" => "system", "content" => "You are Gaia, a smart, friendly assistant."],
        ["role" => "user", "content" => $prompt]
    ],
    "model" => "llama3-8b-8192",
    "temperature" => 0.8
];

$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpcode);
header("Content-Type: application/json");
echo $response;
