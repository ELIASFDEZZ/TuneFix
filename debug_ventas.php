<?php
require_once __DIR__ . '/config/Database.php';
$pdo = Database::getConnection();
echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:13px;'>";

echo "=== TABLA pedido (todos) ===\n";
foreach ($pdo->query('SELECT * FROM pedido ORDER BY id DESC')->fetchAll() as $r)
    echo "  id={$r['id']} usuario_id={$r['usuario_id']} total={$r['total']} estado={$r['estado']} created={$r['created_at']}\n  stripe_session={$r['stripe_session_id']}\n\n";

echo "=== TABLA pedido_item + pieza + proveedor ===\n";
$q = $pdo->query('SELECT pi.*, p.nombre AS pieza_nombre, p.proveedor_id, p.subido_por FROM pedido_item pi JOIN pieza p ON p.id=pi.pieza_id ORDER BY pi.id DESC');
foreach ($q->fetchAll() as $r)
    echo "  pedido_item.id={$r['id']} pedido_id={$r['pedido_id']} pieza_id={$r['pieza_id']} pieza_nombre={$r['pieza_nombre']}\n  proveedor_id={$r['proveedor_id']} subido_por={$r['subido_por']} cantidad={$r['cantidad']} precio_u={$r['precio_u']}\n\n";

echo "=== PIEZAS con proveedor_id ===\n";
foreach ($pdo->query('SELECT id, nombre, proveedor_id, subido_por FROM pieza WHERE proveedor_id IS NOT NULL ORDER BY id')->fetchAll() as $r)
    echo "  pieza.id={$r['id']} proveedor_id={$r['proveedor_id']} subido_por={$r['subido_por']} nombre={$r['nombre']}\n";

echo "\n=== PROVEEDORES activos ===\n";
foreach ($pdo->query("SELECT id, nombre_empresa, email, estado FROM proveedores WHERE estado='aceptado'")->fetchAll() as $r)
    echo "  proveedor.id={$r['id']} empresa={$r['nombre_empresa']} email={$r['email']}\n";

echo "</pre>";
