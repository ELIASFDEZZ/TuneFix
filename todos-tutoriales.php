<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=todos-tutoriales');
    exit;
}

require_once __DIR__ . '/controllers/TodosTutorialesController.php';

$controller = new TodosTutorialesController();
$controller->index();
