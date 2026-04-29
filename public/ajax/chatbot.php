<?php

session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input   = json_decode(file_get_contents('php://input'), true);
$mensaje = trim($input['mensaje'] ?? '');

if ($mensaje === '') {
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

$historial = $input['historial'] ?? [];

$sistemaTexto = 'Eres TuneBot, el asistente virtual de TuneFix, una plataforma web de tuning y mecánica de vehículos. Ayudas a los usuarios con: dudas sobre piezas de coches (compatibilidad, referencias, instalación, marcas), cómo usar la plataforma TuneFix (secciones: Principiante, Entusiasta, Profesional, tutoriales, distribuidores, manuales técnicos), consejos básicos de mantenimiento y tuning, y recomendaciones sobre qué nivel es más adecuado según el usuario. Responde siempre en español, de forma amigable, concisa y útil. No respondas preguntas que no tengan relación con coches, mecánica, tuning o la plataforma TuneFix.';

$contents = [];

// Sistema como primer turno user/model
$contents[] = ['role' => 'user',  'parts' => [['text' => $sistemaTexto]]];
$contents[] = ['role' => 'model', 'parts' => [['text' => 'Entendido, seré TuneBot y ayudaré con todo lo relacionado con TuneFix y mecánica de vehículos.']]];

foreach ($historial as $msg) {
    if (isset($msg['role'], $msg['content'])) {
        $role = $msg['role'] === 'assistant' ? 'model' : 'user';
        $contents[] = ['role' => $role, 'parts' => [['text' => $msg['content']]]];
    }
}
$contents[] = ['role' => 'user', 'parts' => [['text' => $mensaje]]];

$apiKey = 'AIzaSyApjX-9YU2JkbIM4lApV-lEhJLTuoAtWB0';

$payload = json_encode([
    'contents'         => $contents,
    'generationConfig' => [
        'maxOutputTokens' => 600,
        'temperature'     => 0.7,
    ],
]);

$ch = curl_init(
    'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash-lite:generateContent?key=' . $apiKey
);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['error' => 'HTTP ' . $httpCode . ': ' . $response]);
    exit;
}

$data  = json_decode($response, true);
$texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo obtener respuesta.';

echo json_encode(['respuesta' => $texto]);
exit;