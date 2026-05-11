<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/controllers/PerfilUsuarioController.php';

$controller = new PerfilUsuarioController();
$controller->index();
