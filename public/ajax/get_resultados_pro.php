<?php

require_once __DIR__ . '/../../models/DistribuidorModel.php';
require_once __DIR__ . '/../../models/ManualModel.php';

header('Content-Type: application/json; charset=utf-8');

$motId = isset($_GET['motorizacion_id']) && is_numeric($_GET['motorizacion_id'])
    ? (int) $_GET['motorizacion_id']
    : null;

if (!$motId) {
    echo json_encode(['distribuidores' => [], 'manuales' => []]);
    exit;
}

$distribuidorModel = new DistribuidorModel();
$manualModel = new ManualModel();

echo json_encode([
    'distribuidores' => $distribuidorModel->getByMotorizacion($motId),
    'manuales' => $manualModel->getByMotorizacion($motId),
]);
exit;