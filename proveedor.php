<?php
session_start();

if (($_SESSION['usuario_rol'] ?? '') !== 'proveedor') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/controllers/ProveedorController.php';

$controller = new ProveedorController();
$page       = $_GET['page'] ?? 'dashboard';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'guardar_pieza') {
        $controller->guardarPieza();
    } elseif ($action === 'toggle_pieza') {
        $controller->togglePieza();
    } elseif ($action === 'guardar_perfil') {
        $controller->guardarPerfil();
    } else {
        header('Location: proveedor.php');
    }
    exit;
}

if ($page === 'mis-piezas') {
    $controller->misPiezas();
} elseif ($page === 'publicar-pieza') {
    $controller->publicarPieza();
} elseif ($page === 'estadisticas') {
    $controller->estadisticas();
} elseif ($page === 'perfil') {
    $controller->perfil();
} else {
    $controller->dashboard();
}
