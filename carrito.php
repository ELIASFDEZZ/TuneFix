<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=carrito');
    exit;
}

require_once __DIR__ . '/controllers/CarritoController.php';

$controller = new CarritoController();
$controller->index();
