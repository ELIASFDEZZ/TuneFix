<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'no_auth']); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'method']); exit;
}

require_once __DIR__ . '/../../models/CarritoModel.php';
require_once __DIR__ . '/../../models/PiezaModel.php';

$piezaId  = (int) ($_POST['pieza_id']  ?? 0);
$cantidad = max(1, (int) ($_POST['cantidad'] ?? 1));

if ($piezaId <= 0) {
    echo json_encode(['error' => 'invalid']); exit;
}

$piezaModel = new PiezaModel();
$pieza      = $piezaModel->getById($piezaId);

if (!$pieza || (float)$pieza['precio'] <= 0) {
    echo json_encode(['error' => 'pieza_no_disponible']); exit;
}

if ((int)$pieza['stock'] <= 0) {
    echo json_encode(['error' => 'sin_stock']); exit;
}

$carritoModel = new CarritoModel();
$carritoModel->addOrIncrement(
    (int) $_SESSION['usuario_id'],
    $piezaId,
    (float) $pieza['precio'],
    $cantidad
);

$totalItems = $carritoModel->countItems((int) $_SESSION['usuario_id']);
echo json_encode(['ok' => true, 'total_items' => $totalItems]);
