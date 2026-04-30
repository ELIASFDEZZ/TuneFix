<?php
session_start();

require_once __DIR__ . '/controllers/SeguimientoController.php';

$controller = new SeguimientoController();
$accion     = $_GET['accion'] ?? $_POST['accion'] ?? '';

if ($accion === 'dejar') {
    $controller->dejarDeSeguir();
} else {
    $controller->seguir();
}
