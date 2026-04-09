<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=todas-piezas');
    exit;
}

require_once __DIR__ . '/controllers/TodasPiezasController.php';

$controller = new TodasPiezasController();
$controller->index();
