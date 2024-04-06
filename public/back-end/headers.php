<?php
header("Content-Type: application/json"); // Informa ao servidor que a resposta será um JSON
header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Define o CORS com os métodos permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Define o CORS com cabeçalhos persoanlizados

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Responda apenas com os cabeçalhos CORS permitidos e um status 200 OK
    http_response_code(200);
    exit;
}