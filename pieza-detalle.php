<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/controllers/PiezaDetalleController.php';

$controller = new PiezaDetalleController();
$controller->index();
