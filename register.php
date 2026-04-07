<?php

session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/controllers/AuthController.php';

$controller = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processRegister();
} else {
    $controller->showRegister();
}
