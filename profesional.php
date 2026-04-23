<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=profesional');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'profesional') {
    $titulo  = 'Acceso restringido - TuneFix';
    $seccion = 'Profesional';
    require_once __DIR__ . '/views/layouts/header.php';
    require_once __DIR__ . '/views/errors/acceso-denegado.php';
    require_once __DIR__ . '/views/layouts/footer.php';
    exit;
}

require_once __DIR__ . '/controllers/ProfesionalController.php';

$controller = new ProfesionalController();
$controller->index();