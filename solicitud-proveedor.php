<?php
session_start();

// Si ya es proveedor aceptado, redirigir a su panel
if (($_SESSION['usuario_rol'] ?? '') === 'proveedor') {
    header('Location: proveedor.php');
    exit;
}

require_once __DIR__ . '/controllers/SolicitudProveedorController.php';

$controller = new SolicitudProveedorController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->process();
} else {
    $controller->show();
}
