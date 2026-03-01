<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/bd.php';

if (!isset($_GET['marca_id']) || !is_numeric($_GET['marca_id'])) {
    echo json_encode([]);
    exit;
}

$marca_id = (int)$_GET['marca_id'];

$stmt = $pdo->prepare("SELECT id, nombre FROM modelo WHERE marca_id = ? ORDER BY nombre");
$stmt->execute([$marca_id]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
exit;