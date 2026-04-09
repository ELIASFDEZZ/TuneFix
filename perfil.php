<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/controllers/PerfilController.php';

$controller = new PerfilController();
$controller->index();
