<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=entusiasta');
    exit;
}

require_once __DIR__ . '/controllers/EntusiastaController.php';

$controller = new EntusiastaController();
$controller->index();