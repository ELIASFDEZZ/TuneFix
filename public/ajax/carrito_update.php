<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'no_auth']); exit;
}

require_once __DIR__ . '/../../models/CarritoModel.php';

$carritoId = (int) ($_POST['carrito_id'] ?? 0);
$cantidad  = (int) ($_POST['cantidad']   ?? 0);
$usuarioId = (int) $_SESSION['usuario_id'];

if ($carritoId <= 0) {
    echo json_encode(['error' => 'invalid']); exit;
}

$model = new CarritoModel();
$model->updateCantidad($carritoId, $usuarioId, $cantidad);

$items = $model->getItems($usuarioId);
$total = array_reduce($items, fn($c, $i) => $c + $i['cantidad'] * $i['precio_u'], 0.0);
$count = array_reduce($items, fn($c, $i) => $c + $i['cantidad'], 0);

echo json_encode([
    'ok'    => true,
    'total' => number_format($total, 2, '.', ''),
    'count' => $count,
]);
