<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'profesional') {
    $titulo  = 'Acceso restringido - TuneFix';
    $seccion = 'Subir vídeo';
    require_once __DIR__ . '/views/layouts/header.php';
    require_once __DIR__ . '/views/errors/acceso-denegado.php';
    require_once __DIR__ . '/views/layouts/footer.php';
    exit;
}

require_once __DIR__ . '/controllers/SubirVideoController.php';

$controller = new SubirVideoController();
$controller->index();
