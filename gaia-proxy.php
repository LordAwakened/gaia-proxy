<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["prompt"])) {
  echo json_encode(["error" => "No prompt provided."]);
  exit;
}

$prompt = $data["prompt"];
$apiKey = getenv("GROQ_API_KEY"); // 👈 This pulls from Render's environment variables

$ch = curl_init("https://api.groq.com/openai/v1/chat/completions");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
  ],
  CURLOPT_POSTFIELDS => json_encode([
    "messages" => [["role" => "user", "content" => $prompt]],
    "model" => "mixtral-8x7b-32768"
  ])
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
