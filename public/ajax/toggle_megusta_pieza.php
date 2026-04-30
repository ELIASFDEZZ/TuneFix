<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'no_auth']);
    exit;
}

$piezaId   = (int) ($_POST['pieza_id'] ?? 0);
$usuarioId = (int) $_SESSION['usuario_id'];

if ($piezaId <= 0) {
    echo json_encode(['error' => 'invalid']);
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/MeGustaPiezaModel.php';

$model   = new MeGustaPiezaModel();
$liked   = $model->toggle($usuarioId, $piezaId);
$total   = $model->getCount($piezaId);

echo json_encode(['liked' => $liked, 'total' => $total]);
