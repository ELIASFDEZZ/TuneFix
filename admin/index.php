<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../config/Database.php';

// Guard
if (!isset($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: login.php');
    exit;
}

$pdo = Database::getConnection();

// ── Routing ──────────────────────────────────────────────────────────────────
$pages = ['dashboard', 'usuarios', 'piezas', 'tutoriales', 'manuales', 'vehiculos', 'distribuidores', 'proveedores', 'proveedor-detalle'];
$page  = $_GET['page'] ?? 'dashboard';
if (!in_array($page, $pages, true)) $page = 'dashboard';

// ── Actions (POST) ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ── Usuarios ──
    if ($action === 'update_rol') {
        $id  = (int)($_POST['id'] ?? 0);
        $rol = $_POST['rol'] ?? '';
        if ($id > 0 && in_array($rol, ['principiante','entusiasta','profesional'], true)) {
            $pdo->prepare("UPDATE usuario SET rol=? WHERE id=?")->execute([$rol, $id]);
        }
        header("Location: index.php?page=usuarios&ok=rol"); exit;
    }
    if ($action === 'delete_usuario') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare("DELETE FROM usuario_motorizacion WHERE usuario_id=?")->execute([$id]);
            $pdo->prepare("DELETE FROM usuario WHERE id=?")->execute([$id]);
        }
        header("Location: index.php?page=usuarios&ok=del"); exit;
    }

    // ── Piezas ──
    if ($action === 'save_pieza') {
        $id    = (int)($_POST['id'] ?? 0);
        $ref   = trim($_POST['referencia'] ?? '');
        $nom   = trim($_POST['nombre'] ?? '');
        $desc  = trim($_POST['descripcion'] ?? '');
        $img   = trim($_POST['imagen'] ?? '');
        $motId = (int)($_POST['motorizacion_id'] ?? 0) ?: null;
        if ($nom !== '' && $ref !== '') {
            if ($id > 0) {
                $pdo->prepare("UPDATE pieza SET referencia=?,nombre=?,descripcion=?,imagen=? WHERE id=?")
                    ->execute([$ref,$nom,$desc,$img,$id]);
            } else {
                $pdo->prepare("INSERT INTO pieza (referencia,nombre,descripcion,imagen) VALUES (?,?,?,?)")
                    ->execute([$ref,$nom,$desc,$img]);
                $id = (int)$pdo->lastInsertId();
            }
            // Actualizar compatibilidad con vehículo
            $pdo->prepare("DELETE FROM compatibilidad_pieza WHERE pieza_id=?")->execute([$id]);
            if ($motId) {
                $pdo->prepare("INSERT INTO compatibilidad_pieza (pieza_id, motorizacion_id) VALUES (?,?)")
                    ->execute([$id, $motId]);
            }
        }
        header("Location: index.php?page=piezas&ok=saved"); exit;
    }
    if ($action === 'delete_pieza') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pdo->prepare("DELETE FROM compatibilidad_pieza WHERE pieza_id=?")->execute([$id]);
            $pdo->prepare("DELETE FROM distribuidor_pieza WHERE pieza_id=?")->execute([$id]);
            $pdo->prepare("DELETE FROM pieza WHERE id=?")->execute([$id]);
        }
        header("Location: index.php?page=piezas&ok=del"); exit;
    }

    // ── Tutoriales ──
    if ($action === 'save_tutorial') {
        $id     = (int)($_POST['id']              ?? 0);
        $titulo = trim($_POST['titulo']           ?? '');
        $ytId   = trim($_POST['youtube_id']       ?? '');
        $imagen = trim($_POST['imagen']           ?? '');
        $piezaId = (int)($_POST['pieza_id']       ?? 0) ?: null;
        $motId   = (int)($_POST['motorizacion_id']?? 0) ?: null;
        $ytId   = preg_replace('/[^a-zA-Z0-9_-]/', '', $ytId);
        if ($titulo !== '') {
            if ($id > 0) {
                $pdo->prepare("UPDATE tutorial SET titulo=?,youtube_id=?,imagen=?,pieza_id=?,motorizacion_id=? WHERE id=?")
                    ->execute([$titulo, $ytId ?: null, $imagen ?: null, $piezaId, $motId, $id]);
            } else {
                $pdo->prepare("INSERT INTO tutorial (titulo,youtube_id,imagen,pieza_id,motorizacion_id) VALUES (?,?,?,?,?)")
                    ->execute([$titulo, $ytId ?: null, $imagen ?: null, $piezaId, $motId]);
            }
        }
        header("Location: index.php?page=tutoriales&ok=saved"); exit;
    }
    if ($action === 'delete_tutorial') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $pdo->prepare("DELETE FROM tutorial WHERE id=?")->execute([$id]);
        header("Location: index.php?page=tutoriales&ok=del"); exit;
    }

    // ── Manuales ──
    if ($action === 'save_manual') {
        $id      = (int)($_POST['id']              ?? 0);
        $titulo  = trim($_POST['titulo']           ?? '');
        $fuente  = trim($_POST['fuente']           ?? '');
        $motId   = (int)($_POST['motorizacion_id'] ?? 0) ?: null;
        $piezaId = (int)($_POST['pieza_id']        ?? 0) ?: null;

        if ($titulo !== '') {
            $archiveUrl = trim($_POST['archivo_url_actual'] ?? '');

            if (!empty($_FILES['pdf']['name'])) {
                $ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
                if ($ext === 'pdf' && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../uploads/manuales/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                    $htaccess = $uploadDir . '.htaccess';
                    if (!file_exists($htaccess)) {
                        file_put_contents($htaccess, "Options -Indexes\nAddType application/octet-stream .pdf\n");
                    }

                    $filename = 'manual_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
                    $destPath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $destPath)) {
                        if ($archiveUrl && str_starts_with($archiveUrl, 'uploads/manuales/')) {
                            $oldFile = __DIR__ . '/../' . $archiveUrl;
                            if (file_exists($oldFile)) unlink($oldFile);
                        }
                        $archiveUrl = 'uploads/manuales/' . $filename;
                    }
                }
            }

            if ($id > 0) {
                $pdo->prepare(
                    "UPDATE manual SET titulo=?, fuente=?, archivo_url=?, motorizacion_id=?, pieza_id=? WHERE id=?"
                )->execute([$titulo, $fuente ?: null, $archiveUrl ?: null, $motId, $piezaId, $id]);
            } else {
                $pdo->prepare(
                    "INSERT INTO manual (titulo, fuente, archivo_url, motorizacion_id, pieza_id) VALUES (?,?,?,?,?)"
                )->execute([$titulo, $fuente ?: null, $archiveUrl ?: null, $motId, $piezaId]);
            }
        }
        header("Location: index.php?page=manuales&ok=saved"); exit;
    }
    if ($action === 'delete_manual') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $row = $pdo->prepare("SELECT archivo_url FROM manual WHERE id=?");
            $row->execute([$id]);
            $manual = $row->fetch();
            if ($manual && $manual['archivo_url'] && str_starts_with($manual['archivo_url'], 'uploads/manuales/')) {
                $oldFile = __DIR__ . '/../' . $manual['archivo_url'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $pdo->prepare("DELETE FROM manual WHERE id=?")->execute([$id]);
        }
        header("Location: index.php?page=manuales&ok=del"); exit;
    }
    
    // ── Distribuidores ──
if ($action === 'save_distribuidor') {
    $id     = (int)($_POST['id']      ?? 0);
    $nombre = trim($_POST['nombre']   ?? '');
    $url    = trim($_POST['url_base'] ?? '') ?: null;
    if ($nombre !== '') {
        if ($id > 0) {
            $pdo->prepare("UPDATE distribuidor SET nombre=?, url_base=? WHERE id=?")
                ->execute([$nombre, $url, $id]);
        } else {
            $pdo->prepare("INSERT INTO distribuidor (nombre, url_base) VALUES (?,?)")
                ->execute([$nombre, $url]);
        }
    }
    header("Location: index.php?page=distribuidores&tab=tiendas&ok=saved"); exit;
}
if ($action === 'delete_distribuidor') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $pdo->prepare("DELETE FROM distribuidor_pieza WHERE distribuidor_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM distribuidor WHERE id=?")->execute([$id]);
    }
    header("Location: index.php?page=distribuidores&tab=tiendas&ok=del"); exit;
}
if ($action === 'save_distribuidor_pieza') {
    $id             = (int)($_POST['id']              ?? 0);
    $distribuidorId = (int)($_POST['distribuidor_id'] ?? 0);
    $piezaId        = (int)($_POST['pieza_id']        ?? 0);
    $nombre         = trim($_POST['nombre']           ?? '') ?: null;
    $urlDirecta     = trim($_POST['url_directa']      ?? '') ?: null;
    if ($distribuidorId > 0 && $piezaId > 0) {
        if ($id > 0) {
            $pdo->prepare(
                "UPDATE distribuidor_pieza SET distribuidor_id=?, pieza_id=?, nombre=?, url_directa=? WHERE id=?"
            )->execute([$distribuidorId, $piezaId, $nombre, $urlDirecta, $id]);
        } else {
            $pdo->prepare(
                "INSERT INTO distribuidor_pieza (distribuidor_id, pieza_id, nombre, url_directa) VALUES (?,?,?,?)"
            )->execute([$distribuidorId, $piezaId, $nombre, $urlDirecta]);
        }
    }
    header("Location: index.php?page=distribuidores&tab=links&ok=saved"); exit;
}
if ($action === 'delete_distribuidor_pieza') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) $pdo->prepare("DELETE FROM distribuidor_pieza WHERE id=?")->execute([$id]);
    header("Location: index.php?page=distribuidores&tab=links&ok=del"); exit;
}

    // ── Vehículos: Marcas ──
    if ($action === 'save_marca') {
        $id     = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        if ($nombre !== '') {
            if ($id > 0) {
                $pdo->prepare("UPDATE marca SET nombre=? WHERE id=?")->execute([$nombre, $id]);
            } else {
                $pdo->prepare("INSERT INTO marca (nombre) VALUES (?)")->execute([$nombre]);
            }
        }
        header("Location: index.php?page=vehiculos&tab=marcas&ok=saved"); exit;
    }
    if ($action === 'delete_marca') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $pdo->prepare("DELETE FROM marca WHERE id=?")->execute([$id]);
        header("Location: index.php?page=vehiculos&tab=marcas&ok=del"); exit;
    }

    // ── Vehículos: Modelos ──
    if ($action === 'save_modelo') {
        $id         = (int)($_POST['id']          ?? 0);
        $nombre     = trim($_POST['nombre']       ?? '');
        $marcaId    = (int)($_POST['marca_id']    ?? 0);
        $anioInicio = (int)($_POST['anio_inicio'] ?? 0) ?: null;
        $anioFin    = (int)($_POST['anio_fin']    ?? 0) ?: null;
        if ($nombre !== '' && $marcaId > 0) {
            if ($id > 0) {
                $pdo->prepare("UPDATE modelo SET nombre=?, marca_id=?, anio_inicio=?, anio_fin=? WHERE id=?")
                    ->execute([$nombre, $marcaId, $anioInicio, $anioFin, $id]);
            } else {
                $pdo->prepare("INSERT INTO modelo (nombre, marca_id, anio_inicio, anio_fin) VALUES (?,?,?,?)")
                    ->execute([$nombre, $marcaId, $anioInicio, $anioFin]);
            }
        }
        header("Location: index.php?page=vehiculos&tab=modelos&ok=saved"); exit;
    }
    if ($action === 'delete_modelo') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $pdo->prepare("DELETE FROM modelo WHERE id=?")->execute([$id]);
        header("Location: index.php?page=vehiculos&tab=modelos&ok=del"); exit;
    }

    // ── Vehículos: Motorizaciones ──
    if ($action === 'save_motorizacion') {
        $id          = (int)($_POST['id']              ?? 0);
        $nombre      = trim($_POST['nombre']           ?? '');
        $modeloId    = (int)($_POST['modelo_id']       ?? 0);
        $potencia    = trim($_POST['potencia']         ?? '') ?: null;
        $combustible = trim($_POST['tipo_combustible'] ?? '') ?: null;
        $tipoMotor   = trim($_POST['tipo_motor']       ?? '') ?: null;
        $codigo      = trim($_POST['codigo_motor']     ?? '') ?: null;
        if ($nombre !== '' && $modeloId > 0) {
            if ($id > 0) {
                $pdo->prepare(
                    "UPDATE motorizacion SET nombre=?, modelo_id=?, potencia=?,
                     tipo_combustible=?, tipo_motor=?, codigo_motor=? WHERE id=?"
                )->execute([$nombre, $modeloId, $potencia, $combustible, $tipoMotor, $codigo, $id]);
            } else {
                $pdo->prepare(
                    "INSERT INTO motorizacion (nombre, modelo_id, potencia, tipo_combustible, tipo_motor, codigo_motor)
                     VALUES (?,?,?,?,?,?)"
                )->execute([$nombre, $modeloId, $potencia, $combustible, $tipoMotor, $codigo]);
            }
        }
        header("Location: index.php?page=vehiculos&tab=motorizaciones&ok=saved"); exit;
    }
    if ($action === 'delete_motorizacion') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $pdo->prepare("DELETE FROM motorizacion WHERE id=?")->execute([$id]);
        header("Location: index.php?page=vehiculos&tab=motorizaciones&ok=del"); exit;
    }

    // ── Proveedores ──
    if ($action === 'aceptar_proveedor') {
        require_once __DIR__ . '/../models/ProveedorModel.php';
        require_once __DIR__ . '/../config/mailer.php';
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $pm = new ProveedorModel();
            $prov = $pm->getPorId($id);
            $pm->aceptar($id);
            if ($prov) {
                enviarEmailProveedorAceptado($prov['email'], $prov['nombre_empresa'], $prov['nombre_responsable']);
            }
        }
        header("Location: index.php?page=proveedores&ok=aceptado"); exit;
    }
    if ($action === 'rechazar_proveedor') {
        require_once __DIR__ . '/../models/ProveedorModel.php';
        require_once __DIR__ . '/../config/mailer.php';
        $id     = (int)($_POST['id'] ?? 0);
        $motivo = trim($_POST['motivo'] ?? '');
        if ($id > 0 && $motivo !== '') {
            $pm = new ProveedorModel();
            $prov = $pm->getPorId($id);
            $pm->rechazar($id, $motivo);
            if ($prov) {
                enviarEmailProveedorRechazado($prov['email'], $prov['nombre_empresa'], $prov['nombre_responsable'], $motivo);
            }
        }
        header("Location: index.php?page=proveedores&ok=rechazado"); exit;
    }
}

// ── Labels ───────────────────────────────────────────────────────────────────
$pageTitles = [
    'dashboard'        => 'Dashboard',
    'usuarios'         => 'Gestión de Usuarios',
    'piezas'           => 'Gestión de Piezas',
    'tutoriales'       => 'Tutoriales',
    'manuales'         => 'Manuales',
    'vehiculos'        => 'Vehículos',
    'distribuidores'   => 'Distribuidores',
    'proveedores'      => 'Proveedores',
    'proveedor-detalle'=> 'Detalle de Solicitud',
];
$pageIcons = [
    'dashboard'        => 'fa-chart-bar',
    'usuarios'         => 'fa-users',
    'piezas'           => 'fa-cog',
    'tutoriales'       => 'fa-play-circle',
    'manuales'         => 'fa-file-pdf',
    'vehiculos'        => 'fa-car',
    'distribuidores'   => 'fa-truck',
    'proveedores'      => 'fa-store',
    'proveedor-detalle'=> 'fa-store',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TuneFix Admin — <?= $pageTitles[$page] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --accent:   rgb(164, 4, 46);
      --sw:       245px;
    }

    /* ─── RESET ─── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      font-size: 13.5px;
      line-height: 1.55;
      background: #f0f2f5;
      color: #1c1e21;
    }

    /* ════════════════════════════════
       SIDEBAR — blanco, limpio
    ════════════════════════════════ */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: var(--sw); height: 100vh;
      background: #fff;
      border-right: 1px solid #e4e6ea;
      display: flex; flex-direction: column;
      z-index: 200;
      overflow-y: auto;
    }

    .sidebar-brand {
      padding: 0 20px;
      height: 60px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid #e4e6ea;
      flex-shrink: 0;
    }
    .brand-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: .75rem; flex-shrink: 0;
    }
    .brand-text { font-size: 1rem; font-weight: 800; color: #1c1e21; letter-spacing: -.4px; }
    .brand-text em { color: var(--accent); font-style: normal; }
    .brand-tag {
      margin-left: auto;
      font-size: .58rem; font-weight: 700; letter-spacing: 1.2px;
      text-transform: uppercase; color: #fff;
      background: var(--accent);
      padding: 2px 7px; border-radius: 4px;
    }

    .sidebar-nav { padding: 10px 12px; flex: 1; }

    .nav-group-label {
      font-size: .6rem; font-weight: 700; letter-spacing: 1.5px;
      text-transform: uppercase; color: #9ea3ab;
      padding: 14px 8px 5px; display: block;
    }

    .nav-link-item {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: 7px;
      text-decoration: none; color: #4b5563;
      font-size: .845rem; font-weight: 500;
      transition: background .13s, color .13s;
      margin-bottom: 1px;
      position: relative;
    }
    .nav-link-item:hover { background: #f3f4f6; color: #111; }
    .nav-link-item.active {
      background: #fff0f2;
      color: var(--accent);
      font-weight: 600;
    }
    .nav-link-item.active::before {
      content: '';
      position: absolute; left: 0; top: 20%; bottom: 20%;
      width: 3px; border-radius: 0 3px 3px 0;
      background: var(--accent);
    }
    .nav-link-item .ni {
      width: 16px; text-align: center; font-size: .8rem;
      color: #9ca3af; flex-shrink: 0;
      transition: color .13s;
    }
    .nav-link-item.active .ni,
    .nav-link-item:hover .ni { color: inherit; }

    .sidebar-footer {
      padding: 12px;
      border-top: 1px solid #e4e6ea;
      display: flex; flex-direction: column; gap: 2px;
    }
    .sf-link {
      display: flex; align-items: center; gap: 8px;
      padding: 7px 10px; border-radius: 7px;
      text-decoration: none; font-size: .82rem; font-weight: 500;
      transition: background .13s;
    }
    .sf-link.site  { color: #6b7280; }
    .sf-link.site:hover  { background: #f3f4f6; color: #111; }
    .sf-link.out   { color: #dc2626; }
    .sf-link.out:hover   { background: #fef2f2; }

    /* ════════════════════════════════
       MAIN
    ════════════════════════════════ */
    .main {
      margin-left: var(--sw);
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* TOPBAR */
    .topbar {
      height: 60px;
      background: #fff;
      border-bottom: 1px solid #e4e6ea;
      padding: 0 28px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .topbar-title {
      display: flex; align-items: center; gap: 9px;
      font-size: .95rem; font-weight: 700; color: #1c1e21;
    }
    .topbar-title .ti {
      width: 30px; height: 30px; border-radius: 8px;
      background: #fff0f2; color: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: .78rem;
    }
    .topbar-right { display: flex; align-items: center; gap: 8px; }
    .admin-chip {
      display: flex; align-items: center; gap: 6px;
      background: #f3f4f6; border: 1px solid #e4e6ea;
      border-radius: 20px; padding: 4px 12px 4px 6px;
      font-size: .76rem; font-weight: 600; color: #374151;
    }
    .admin-chip-avatar {
      width: 22px; height: 22px; border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: .62rem; font-weight: 800;
    }

    /* CONTENT */
    .content { padding: 28px; flex: 1; }

    /* ════════════════════════════════
       STAT CARDS
    ════════════════════════════════ */
    .stat-card {
      background: #fff;
      border: 1px solid #e4e6ea;
      border-radius: 12px;
      padding: 20px;
      display: flex; align-items: center; gap: 16px;
      transition: box-shadow .2s, border-color .2s;
    }
    .stat-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,.08);
      border-color: #d1d5db;
    }
    .stat-icon {
      width: 44px; height: 44px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem; flex-shrink: 0;
    }
    .stat-value { font-size: 1.7rem; font-weight: 800; color: #111827; line-height: 1; }
    .stat-label { font-size: .74rem; color: #6b7280; margin-top: 3px; font-weight: 500; }

    /* ════════════════════════════════
       ADMIN CARDS
    ════════════════════════════════ */
    .admin-card {
      background: #fff;
      border: 1px solid #e4e6ea;
      border-radius: 12px;
      overflow: hidden;
    }
    .admin-card-header {
      padding: 14px 20px;
      border-bottom: 1px solid #f0f0f0;
      display: flex; align-items: center; justify-content: space-between;
      background: #fafafa;
    }
    .admin-card-title {
      font-size: .875rem; font-weight: 700; color: #111827;
      display: flex; align-items: center; gap: 7px;
    }
    .admin-card-title i { color: var(--accent); }

    /* ════════════════════════════════
       TABLAS
    ════════════════════════════════ */
    .table-admin { color: #374151; margin: 0; font-size: .855rem; }
    .table-admin thead th {
      background: #f9fafb;
      color: #6b7280;
      font-size: .67rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .9px;
      border-color: #f0f0f0;
      padding: 10px 18px;
    }
    .table-admin tbody td {
      border-color: #f0f0f0;
      padding: 11px 18px;
      vertical-align: middle;
      background: #fff;
      color: #374151;
    }
    .table-admin tbody tr:nth-child(even) td { background: #fafafa; }
    .table-admin tbody tr:hover td { background: #fff5f6 !important; }

    /* ════════════════════════════════
       BADGES
    ════════════════════════════════ */
    .rol-badge {
      display: inline-flex; align-items: center;
      padding: 3px 9px; border-radius: 5px;
      font-size: .69rem; font-weight: 700; letter-spacing: .2px;
    }
    .rol-principiante { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .rol-entusiasta   { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .rol-profesional  { background: #fff0f2; color: var(--accent); border: 1px solid #fecdd3; }
    .rol-proveedor    { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }

    .estado-pendiente {
      display: inline-flex; align-items: center;
      background: #fefce8; color: #854d0e; border: 1px solid #fde68a;
      padding: 3px 9px; border-radius: 5px; font-size: .69rem; font-weight: 700;
    }
    .estado-aceptado {
      display: inline-flex; align-items: center;
      background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;
      padding: 3px 9px; border-radius: 5px; font-size: .69rem; font-weight: 700;
    }
    .estado-rechazado {
      display: inline-flex; align-items: center;
      background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3;
      padding: 3px 9px; border-radius: 5px; font-size: .69rem; font-weight: 700;
    }

    /* ════════════════════════════════
       BOTONES
    ════════════════════════════════ */
    .btn-red {
      background: var(--accent); color: #fff; border: none;
      border-radius: 7px; font-size: .82rem; font-weight: 600;
      padding: 7px 15px; cursor: pointer;
      display: inline-flex; align-items: center; gap: 6px;
      transition: filter .15s, transform .15s;
    }
    .btn-red:hover { filter: brightness(1.1); color: #fff; transform: translateY(-1px); }

    .btn-ghost {
      background: #fff; color: #374151;
      border: 1px solid #d1d5db; border-radius: 7px;
      font-size: .82rem; font-weight: 500; padding: 7px 15px; cursor: pointer;
      display: inline-flex; align-items: center; gap: 6px;
      transition: background .13s, border-color .13s;
    }
    .btn-ghost:hover { background: #f9fafb; border-color: #9ca3af; color: #111; }

    .btn-danger-sm {
      background: #fff1f2; color: #be123c;
      border: 1px solid #fecdd3; border-radius: 6px;
      font-size: .75rem; font-weight: 600; padding: 4px 10px; cursor: pointer;
      display: inline-flex; align-items: center; gap: 4px;
      transition: background .13s;
    }
    .btn-danger-sm:hover { background: #ffe4e6; }

    .btn-edit-sm {
      background: #fff7ed; color: #c2410c;
      border: 1px solid #fed7aa; border-radius: 6px;
      font-size: .75rem; font-weight: 600; padding: 4px 10px; cursor: pointer;
      display: inline-flex; align-items: center; gap: 4px;
      transition: background .13s;
    }
    .btn-edit-sm:hover { background: #ffedd5; }

    /* ════════════════════════════════
       FORMULARIOS / MODALES
    ════════════════════════════════ */
    .modal-content {
      background: #fff;
      border: 1px solid #e4e6ea;
      border-radius: 14px;
      box-shadow: 0 20px 50px rgba(0,0,0,.12);
    }
    .modal-header {
      border-bottom: 1px solid #f0f0f0;
      padding: 16px 20px;
      background: #fafafa;
      border-radius: 14px 14px 0 0;
    }
    .modal-title { font-size: .92rem; font-weight: 700; color: #111827; }
    .modal-footer { border-top: 1px solid #f0f0f0; background: #fafafa; border-radius: 0 0 14px 14px; }

    .form-control-admin {
      background: #fff; border: 1px solid #d1d5db;
      color: #111827; border-radius: 7px;
      font-size: .855rem; padding: 8px 11px;
      transition: border-color .15s, box-shadow .15s;
    }
    .form-control-admin:focus {
      border-color: var(--accent); outline: none;
      box-shadow: 0 0 0 3px rgba(164,4,46,.1);
      color: #111827; background: #fff;
    }
    .form-control-admin::placeholder { color: #9ca3af; }

    .form-select-admin {
      background: #fff; border: 1px solid #d1d5db;
      color: #111827; border-radius: 7px;
      font-size: .855rem; padding: 8px 11px;
      transition: border-color .15s, box-shadow .15s;
    }
    .form-select-admin:focus {
      border-color: var(--accent); outline: none;
      box-shadow: 0 0 0 3px rgba(164,4,46,.1);
      color: #111827; background: #fff;
    }

    .form-label-admin {
      color: #374151; font-size: .78rem; font-weight: 600;
      margin-bottom: 5px; display: block;
    }

    /* ════════════════════════════════
       ALERTAS
    ════════════════════════════════ */
    .alert-success-admin {
      background: #f0fdf4; border: 1px solid #bbf7d0;
      color: #166534; border-radius: 8px; padding: 10px 16px;
      font-size: .84rem; margin-bottom: 18px;
      display: flex; align-items: center; gap: 8px;
    }

    /* ════════════════════════════════
       MISC
    ════════════════════════════════ */
    .pieza-thumb {
      width: 42px; height: 42px; object-fit: cover;
      border-radius: 8px; border: 1px solid #e4e6ea;
      background: #f9fafb;
    }
    .search-box {
      background: #fff; border: 1px solid #d1d5db;
      color: #111827; border-radius: 7px; padding: 7px 13px; font-size: .85rem;
      transition: border-color .15s, box-shadow .15s;
    }
    .search-box:focus {
      border-color: var(--accent); outline: none;
      box-shadow: 0 0 0 3px rgba(164,4,46,.1);
    }
    .search-box::placeholder { color: #9ca3af; }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }
  </style>
</head>
<body>

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar">

  <div class="sidebar-brand">
    <div class="brand-icon"><i class="fas fa-wrench"></i></div>
    <span class="brand-text">Tune<em>Fix</em></span>
    <span class="brand-tag">Admin</span>
  </div>

  <nav class="sidebar-nav">
    <span class="nav-group-label">General</span>

    <?php foreach ([
      'dashboard'      => ['Dashboard',       'fa-chart-pie'],
      'usuarios'       => ['Usuarios',        'fa-users'],
      'piezas'         => ['Piezas',          'fa-cog'],
      'tutoriales'     => ['Tutoriales',      'fa-play-circle'],
      'manuales'       => ['Manuales',        'fa-file-pdf'],
      'vehiculos'      => ['Vehículos',       'fa-car'],
      'distribuidores' => ['Distribuidores',  'fa-truck'],
    ] as $key => [$label, $icon]): ?>
      <a href="index.php?page=<?= $key ?>"
         class="nav-link-item <?= $page === $key ? 'active' : '' ?>">
        <i class="fas <?= $icon ?> ni"></i>
        <?= $label ?>
      </a>
    <?php endforeach; ?>

    <span class="nav-group-label">Proveedores</span>
    <?php
      $pendientesCount = 0;
      try {
          $stmtP = $pdo->query("SELECT COUNT(*) FROM proveedores WHERE estado='pendiente'");
          $pendientesCount = (int)$stmtP->fetchColumn();
      } catch (\Exception $e) { /* tabla aún no existe */ }
    ?>
    <a href="index.php?page=proveedores"
       class="nav-link-item <?= in_array($page, ['proveedores','proveedor-detalle']) ? 'active' : '' ?>"
       style="justify-content:space-between;">
      <span style="display:flex;align-items:center;gap:9px;">
        <i class="fas fa-store ni"></i> Proveedores
      </span>
      <?php if ($pendientesCount > 0): ?>
        <span style="background:var(--accent);color:#fff;font-size:.6rem;font-weight:800;
                     padding:1px 6px;border-radius:4px;">
          <?= $pendientesCount ?>
        </span>
      <?php endif; ?>
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="../index.php" class="sf-link site">
      <i class="fas fa-external-link-alt" style="font-size:.75rem;"></i> Ver sitio web
    </a>
    <a href="logout.php" class="sf-link out">
      <i class="fas fa-sign-out-alt" style="font-size:.75rem;"></i> Cerrar sesión
    </a>
  </div>

</aside>

<!-- ════════ MAIN ════════ -->
<div class="main">

  <header class="topbar">
    <div class="topbar-title">
      <div class="ti"><i class="fas <?= $pageIcons[$page] ?>"></i></div>
      <?= $pageTitles[$page] ?>
    </div>
    <div class="topbar-right">
      <div class="admin-chip">
        <div class="admin-chip-avatar">A</div>
        Administrador
      </div>
    </div>
  </header>

  <main class="content">
    <?php require_once __DIR__ . '/pages/' . $page . '.php'; ?>
  </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
