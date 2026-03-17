<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/bd.php';

if (!isset($_GET['modelo_id']) || !is_numeric($_GET['modelo_id'])) {
    echo json_encode([]);
    exit;
}

$modelo_id = (int)$_GET['modelo_id'];

$stmt = $pdo->prepare("
    SELECT id, nombre, potencia, tipo_combustible 
    FROM motorizacion 
    WHERE modelo_id = ? 
    ORDER BY nombre
");
$stmt->execute([$modelo_id]);

$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mejoramos el texto que se muestra en el select
foreach ($datos as &$item) {
    $texto = $item['nombre'];
    if (!empty($item['potencia'])) $texto .= " ({$item['potencia']})";
    if (!empty($item['tipo_combustible'])) $texto .= " - {$item['tipo_combustible']}";
    $item['texto'] = $texto;
}

echo json_encode($datos);
exit;