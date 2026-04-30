<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'profesional') {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/controllers/SeguimientoController.php';

$controller = new SeguimientoController();
$controller->misSeguidores();
