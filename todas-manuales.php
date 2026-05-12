<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=todas-manuales');
    exit;
}

require_once __DIR__ . '/controllers/TodasManualesController.php';

$controller = new TodasManualesController();
$controller->index();
