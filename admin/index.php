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
$pages = ['dashboard', 'usuarios', 'piezas', 'tutoriales', 'manuales'];
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
    if ($action === 'delete_manual') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $pdo->prepare("DELETE FROM manual WHERE id=?")->execute([$id]);
        header("Location: index.php?page=manuales&ok=del"); exit;
    }
}

// ── Labels ───────────────────────────────────────────────────────────────────
$pageTitles = [
    'dashboard'  => 'Dashboard',
    'usuarios'   => 'Gestión de Usuarios',
    'piezas'     => 'Gestión de Piezas',
    'tutoriales' => 'Tutoriales',
    'manuales'   => 'Manuales',
];
$pageIcons = [
    'dashboard'  => 'fa-chart-bar',
    'usuarios'   => 'fa-users',
    'piezas'     => 'fa-cog',
    'tutoriales' => 'fa-play-circle',
    'manuales'   => 'fa-file-pdf',
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
      --red: rgb(164,4,46);
      --sidebar-w: 230px;
      --bg: #0d0d1a;
      --card-bg: #161625;
      --border: rgba(255,60,0,0.15);
    }
    * { box-sizing: border-box; }
    body { background: var(--bg); color: #e0e0e0; font-family: 'Segoe UI', sans-serif; margin: 0; }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed; top: 0; left: 0; width: var(--sidebar-w); height: 100vh;
      background: #111120; border-right: 1px solid var(--border);
      display: flex; flex-direction: column; z-index: 100; overflow-y: auto;
    }
    .sidebar-brand {
      padding: 22px 20px 16px;
      border-bottom: 1px solid var(--border);
    }
    .brand-logo { font-size: 1.5rem; font-weight: 800; letter-spacing: -1px; color: #fff; }
    .brand-logo em { color: var(--red); font-style: normal; }
    .brand-sub { font-size: .65rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.3); margin-top: 2px; }
    .sidebar-nav { padding: 14px 10px; flex: 1; }
    .nav-section-label {
      font-size: .65rem; letter-spacing: 1.5px; text-transform: uppercase;
      color: rgba(255,255,255,0.25); padding: 10px 10px 6px;
    }
    .nav-item a {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 12px; border-radius: 8px; text-decoration: none;
      color: rgba(255,255,255,0.55); font-size: .88rem; font-weight: 500;
      transition: all .2s ease;
    }
    .nav-item a:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .nav-item a.active { background: rgba(164,4,46,0.2); color: #fff; border: 1px solid rgba(164,4,46,0.35); }
    .nav-item a .nav-icon { width: 20px; text-align: center; font-size: .85rem; }
    .sidebar-footer {
      padding: 14px 10px;
      border-top: 1px solid var(--border);
    }
    .btn-logout {
      display: flex; align-items: center; gap: 8px;
      padding: 8px 12px; border-radius: 8px; text-decoration: none;
      color: rgba(255,100,100,.7); font-size: .85rem; font-weight: 500;
      transition: all .2s; width: 100%;
    }
    .btn-logout:hover { background: rgba(220,53,69,.12); color: #ff6b6b; }
    .btn-site {
      display: flex; align-items: center; gap: 8px;
      padding: 8px 12px; border-radius: 8px; text-decoration: none;
      color: rgba(255,255,255,.3); font-size: .82rem;
      transition: all .2s; margin-bottom: 4px;
    }
    .btn-site:hover { background: rgba(255,255,255,.05); color: rgba(255,255,255,.7); }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
    .topbar {
      background: #111120; border-bottom: 1px solid var(--border);
      padding: 14px 28px; display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 1rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; }
    .topbar-title i { color: var(--red); }
    .topbar-badge { background: rgba(164,4,46,.15); border: 1px solid rgba(164,4,46,.3); color: rgba(255,255,255,.5); font-size: .7rem; padding: 2px 8px; border-radius: 20px; }
    .content { padding: 28px; flex: 1; }

    /* ── CARDS ── */
    .stat-card {
      background: #1c2033; border: 1px solid var(--border); border-radius: 12px;
      padding: 20px; display: flex; align-items: center; gap: 16px;
    }
    .stat-icon {
      width: 48px; height: 48px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1; }
    .stat-label { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: 3px; }

    /* ── TABLES ── */
    .admin-card {
      background: #1c2033; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;
    }
    .admin-card-header {
      padding: 16px 20px; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      background: #1c2033;
    }
    .admin-card-title { font-size: .9rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; }
    .admin-card-title i { color: var(--red); }
    .table-admin { color: #ccc; margin: 0; }
    .table-admin thead th { background: #141727; color: rgba(255,255,255,.45); font-size: .72rem; text-transform: uppercase; letter-spacing: .8px; border-color: rgba(255,255,255,.06); padding: 10px 16px; }
    .table-admin tbody td { border-color: rgba(255,255,255,.05); padding: 11px 16px; vertical-align: middle; font-size: .87rem; background: #1c2033; }
    .table-admin tbody tr:nth-child(even) td { background: #202438; }
    .table-admin tbody tr:hover td { background: #252a42 !important; }

    /* ── BADGES ── */
    .rol-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .rol-principiante { background: rgba(13,110,253,.15); color: #6ea8fe; border: 1px solid rgba(13,110,253,.3); }
    .rol-entusiasta   { background: rgba(255,136,0,.15);  color: #ffaa44; border: 1px solid rgba(255,136,0,.3); }
    .rol-profesional  { background: rgba(164,4,46,.15);   color: #ff6b6b; border: 1px solid rgba(164,4,46,.3); }

    /* ── BUTTONS ── */
    .btn-red { background: var(--red); color: #fff; border: none; border-radius: 8px; font-size: .82rem; font-weight: 600; padding: 6px 14px; }
    .btn-red:hover { background: #c0053a; color: #fff; }
    .btn-ghost { background: rgba(255,255,255,.06); color: rgba(255,255,255,.7); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; font-size: .82rem; padding: 6px 14px; }
    .btn-ghost:hover { background: rgba(255,255,255,.1); color: #fff; }
    .btn-danger-sm { background: rgba(220,53,69,.15); color: #ff6b6b; border: 1px solid rgba(220,53,69,.25); border-radius: 6px; font-size: .78rem; padding: 4px 10px; }
    .btn-danger-sm:hover { background: rgba(220,53,69,.3); }
    .btn-edit-sm { background: rgba(255,193,7,.12); color: #ffc107; border: 1px solid rgba(255,193,7,.25); border-radius: 6px; font-size: .78rem; padding: 4px 10px; }
    .btn-edit-sm:hover { background: rgba(255,193,7,.25); }

    /* ── FORMS / MODALS ── */
    .modal-content { background: #1c2033; border: 1px solid var(--border); border-radius: 14px; }
    .modal-header { border-bottom: 1px solid var(--border); }
    .modal-footer { border-top: 1px solid var(--border); }
    .form-control-admin { background: #0d0d1a; border: 1px solid rgba(255,255,255,.1); color: #fff; border-radius: 8px; }
    .form-control-admin:focus { background: #0d0d1a; border-color: var(--red); color: #fff; box-shadow: 0 0 0 .2rem rgba(164,4,46,.2); }
    .form-control-admin::placeholder { color: rgba(255,255,255,.2); }
    .form-label-admin { color: rgba(255,255,255,.55); font-size: .82rem; font-weight: 500; }
    .form-select-admin { background: #0d0d1a; border: 1px solid rgba(255,255,255,.1); color: #fff; border-radius: 8px; }
    .form-select-admin:focus { background: #0d0d1a; border-color: var(--red); color: #fff; box-shadow: 0 0 0 .2rem rgba(164,4,46,.2); }

    /* ── ALERT ── */
    .alert-success-admin { background: rgba(25,135,84,.15); border: 1px solid rgba(25,135,84,.3); color: #75b798; border-radius: 8px; padding: 10px 16px; font-size: .85rem; margin-bottom: 20px; }

    /* ── IMG THUMB ── */
    .pieza-thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }

    /* ── SEARCH ── */
    .search-box { background: #0d0d1a; border: 1px solid rgba(255,255,255,.1); color: #fff; border-radius: 8px; padding: 7px 14px; font-size: .85rem; }
    .search-box:focus { border-color: var(--red); outline: none; box-shadow: 0 0 0 .2rem rgba(164,4,46,.15); }
    .search-box::placeholder { color: rgba(255,255,255,.2); }
  </style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════════════════════ -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">Tune<em>Fix</em></div>
    <div class="brand-sub">Admin Panel</div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Principal</div>
    <?php foreach ([
      'dashboard'  => ['Dashboard',    'fa-chart-bar'],
      'usuarios'   => ['Usuarios',     'fa-users'],
      'piezas'     => ['Piezas',       'fa-cog'],
      'tutoriales' => ['Tutoriales',   'fa-play-circle'],
      'manuales'   => ['Manuales',     'fa-file-pdf'],
    ] as $key => [$label, $icon]): ?>
      <div class="nav-item">
        <a href="index.php?page=<?= $key ?>" class="<?= $page === $key ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fas <?= $icon ?>"></i></span>
          <?= $label ?>
        </a>
      </div>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="../index.php" class="btn-site">
      <i class="fas fa-external-link-alt"></i> Ver sitio
    </a>
    <a href="logout.php" class="btn-logout">
      <i class="fas fa-sign-out-alt"></i> Cerrar sesión
    </a>
  </div>
</aside>

<!-- ══ MAIN ═════════════════════════════════════════════════ -->
<div class="main">
  <header class="topbar">
    <div class="topbar-title">
      <i class="fas <?= $pageIcons[$page] ?>"></i>
      <?= $pageTitles[$page] ?>
    </div>
    <span class="topbar-badge"><i class="fas fa-user me-1"></i>Admin</span>
  </header>

  <main class="content">
    <?php require_once __DIR__ . '/pages/' . $page . '.php'; ?>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
