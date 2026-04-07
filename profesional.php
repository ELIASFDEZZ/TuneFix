<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=profesional');
    exit;
}

require_once __DIR__ . '/controllers/ProfesionalController.php';

$controller = new ProfesionalController();
$controller->index();