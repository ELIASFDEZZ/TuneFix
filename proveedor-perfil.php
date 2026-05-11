<?php

session_start();

require_once __DIR__ . '/controllers/ProveedorPerfilController.php';

$controller = new ProveedorPerfilController();
$controller->index();
