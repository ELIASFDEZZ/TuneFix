<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION[ADMIN_SESSION_KEY])) {
    http_response_code(403);
    exit('Acceso denegado');
}

$file    = $_GET['file'] ?? '';
$docsDir = __DIR__ . '/../uploads/proveedores/docs/';

// Validar que no haya path traversal
$file    = basename($file);
$ruta    = $docsDir . $file;

if (!$file || !file_exists($ruta)) {
    http_response_code(404);
    exit('Archivo no encontrado');
}

$ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mime = match ($ext) {
    'pdf'  => 'application/pdf',
    'jpg', 'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    default => 'application/octet-stream',
};

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . $file . '"');
header('Content-Length: ' . filesize($ruta));
readfile($ruta);
