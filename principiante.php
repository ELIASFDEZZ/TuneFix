<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=principiante');
    exit;
}

require_once __DIR__ . '/controllers/PrincipianteController.php';

$controller = new PrincipianteController();
$controller->index();
