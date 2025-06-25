<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$prompt = $data['prompt'] ?? '';

if (!$prompt) {
  echo json_encode(["error" => "No prompt provided."]);
  exit;
}

$apiKey = 'gsk_...'; // Your GROQ API key

$payload = json_encode([
  "model" => "llama3-70b-8192",
  "messages" => [["role" => "user", "content" => $prompt]],
]);

$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
  ],
  CURLOPT_POSTFIELDS => $payload
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
