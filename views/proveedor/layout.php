<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel Proveedor — TuneFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --red: rgb(164,4,46);
      --orange: #ff3c00;
      --sidebar-w: 240px;
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
    .sidebar-brand { padding: 22px 20px 16px; border-bottom: 1px solid var(--border); }
    .brand-logo { font-size: 1.4rem; font-weight: 800; letter-spacing: -1px; color: #fff; }
    .brand-logo em { color: var(--red); font-style: normal; }
    .brand-sub { font-size: .62rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.25); margin-top: 2px; }
    .verified-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(255,193,7,0.12); border: 1px solid rgba(255,193,7,0.3);
      color: #ffc107; font-size: .7rem; font-weight: 700; padding: 4px 10px;
      border-radius: 20px; margin-top: 10px;
    }
    .sidebar-nav { padding: 14px 10px; flex: 1; }
    .nav-section-label { font-size: .62rem; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.22); padding: 10px 10px 6px; }
    .nav-item a {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 12px; border-radius: 8px; text-decoration: none;
      color: rgba(255,255,255,0.55); font-size: .88rem; font-weight: 500;
      transition: all .2s ease;
    }
    .nav-item a:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .nav-item a.active { background: rgba(164,4,46,0.2); color: #fff; border: 1px solid rgba(164,4,46,0.35); }
    .nav-item a .nav-icon { width: 20px; text-align: center; font-size: .85rem; }
    .sidebar-footer { padding: 14px 10px; border-top: 1px solid var(--border); }
    .btn-logout {
      display: flex; align-items: center; gap: 8px;
      padding: 8px 12px; border-radius: 8px; text-decoration: none;
      color: rgba(255,100,100,.7); font-size: .85rem; font-weight: 500;
      transition: all .2s; width: 100%;
    }
    .btn-logout:hover { background: rgba(220,53,69,.12); color: #ff6b6b; }
    .btn-site { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; text-decoration: none; color: rgba(255,255,255,.3); font-size: .82rem; transition: all .2s; margin-bottom: 4px; }
    .btn-site:hover { background: rgba(255,255,255,.05); color: rgba(255,255,255,.7); }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
    .topbar { background: #111120; border-bottom: 1px solid var(--border); padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
    .topbar-title { font-size: 1rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; }
    .topbar-title i { color: var(--red); }
    .topbar-badge { background: rgba(255,193,7,.12); border: 1px solid rgba(255,193,7,.3); color: #ffc107; font-size: .7rem; padding: 2px 10px; border-radius: 20px; font-weight: 600; }
    .content { padding: 28px; flex: 1; }

    /* ── CARDS / STATS ── */
    .stat-card { background: #1c2033; border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1; }
    .stat-label { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: 3px; }

    /* ── TABLES ── */
    .admin-card { background: #1c2033; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    .admin-card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #1c2033; }
    .admin-card-title { font-size: .9rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; }
    .admin-card-title i { color: var(--red); }
    .table-prov { color: #ccc; margin: 0; }
    .table-prov thead th { background: #141727; color: rgba(255,255,255,.45); font-size: .72rem; text-transform: uppercase; letter-spacing: .8px; border-color: rgba(255,255,255,.06); padding: 10px 16px; }
    .table-prov tbody td { border-color: rgba(255,255,255,.05); padding: 11px 16px; vertical-align: middle; font-size: .87rem; background: #1c2033; }
    .table-prov tbody tr:nth-child(even) td { background: #202438; }
    .table-prov tbody tr:hover td { background: #252a42 !important; }

    /* ── BADGES ── */
    .badge-activa   { background: rgba(25,135,84,.15); color: #75b798; border: 1px solid rgba(25,135,84,.3); padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .badge-pausada  { background: rgba(255,193,7,.12); color: #ffc107; border: 1px solid rgba(255,193,7,.3); padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .badge-sinstock { background: rgba(220,53,69,.12); color: #ff6b6b; border: 1px solid rgba(220,53,69,.3); padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }

    /* ── BUTTONS ── */
    .btn-red { background: var(--red); color: #fff; border: none; border-radius: 8px; font-size: .82rem; font-weight: 600; padding: 7px 16px; }
    .btn-red:hover { background: #c0053a; color: #fff; }
    .btn-ghost { background: rgba(255,255,255,.06); color: rgba(255,255,255,.7); border: 1px solid rgba(255,255,255,.1); border-radius: 8px; font-size: .82rem; padding: 7px 16px; }
    .btn-ghost:hover { background: rgba(255,255,255,.1); color: #fff; }

    /* ── FORMS ── */
    .form-control-prov { background: #0d0d1a; border: 1px solid rgba(255,255,255,.1); color: #fff; border-radius: 8px; }
    .form-control-prov:focus { background: #0d0d1a; border-color: var(--red); color: #fff; box-shadow: 0 0 0 .2rem rgba(164,4,46,.2); }
    .form-control-prov::placeholder { color: rgba(255,255,255,.2); }
    .form-select-prov { background: #0d0d1a; border: 1px solid rgba(255,255,255,.1); color: #fff; border-radius: 8px; }
    .form-select-prov:focus { background: #0d0d1a; border-color: var(--red); color: #fff; box-shadow: 0 0 0 .2rem rgba(164,4,46,.2); }
    .form-select-prov option { background: #1c2033; }
    .form-label-prov { color: rgba(255,255,255,.55); font-size: .83rem; font-weight: 500; }
    .section-divider { font-size: .68rem; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,.25); padding: 10px 0 8px; border-bottom: 1px solid rgba(255,255,255,.06); margin: 24px 0 16px; }

    /* ── ALERTS ── */
    .alert-ok-prov  { background: rgba(25,135,84,.12); border: 1px solid rgba(25,135,84,.3); color: #75b798; border-radius: 8px; padding: 10px 16px; font-size: .87rem; margin-bottom: 20px; }
    .alert-err-prov { background: rgba(220,53,69,.12); border: 1px solid rgba(220,53,69,.3); color: #ff8888; border-radius: 8px; padding: 10px 16px; font-size: .87rem; margin-bottom: 20px; }

    /* ── VEHICULO BLOCK ── */
    .vehiculo-block { background: #141727; border: 1px solid rgba(255,255,255,.07); border-radius: 10px; padding: 16px; margin-bottom: 12px; position: relative; }
    .btn-remove-vehiculo { position: absolute; top: 10px; right: 10px; background: rgba(220,53,69,.15); border: none; color: #ff6b6b; border-radius: 6px; width: 28px; height: 28px; cursor: pointer; font-size: .8rem; display: flex; align-items: center; justify-content: center; }
    .btn-remove-vehiculo:hover { background: rgba(220,53,69,.3); }

    /* ── THUMB PIEZA ── */
    .pieza-thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }
  </style>
</head>
<body>

<?php
$page       = $_GET['page'] ?? 'dashboard';
$pageLabels = [
  'dashboard'     => ['Dashboard',           'fa-chart-bar'],
  'mis-piezas'    => ['Mis Piezas',          'fa-boxes'],
  'publicar-pieza'=> ['Publicar nueva pieza','fa-plus-circle'],
  'estadisticas'  => ['Estadísticas',        'fa-chart-line'],
  'perfil'        => ['Mi perfil',           'fa-building'],
];
[$pageLabel, $pageIcon] = $pageLabels[$page] ?? ['Dashboard', 'fa-chart-bar'];
$empresa = htmlspecialchars($_SESSION['proveedor_empresa'] ?? $_SESSION['usuario_nombre'] ?? '');
?>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">Tune<em>Fix</em></div>
    <div class="brand-sub">Panel Proveedor</div>
    <div class="verified-badge"><i class="fas fa-check-circle"></i> Proveedor Verificado</div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Mi panel</div>
    <?php foreach ([
      'dashboard'      => ['Dashboard',            'fa-chart-bar'],
      'mis-piezas'     => ['Mis Piezas',           'fa-boxes'],
      'publicar-pieza' => ['Publicar nueva pieza', 'fa-plus-circle'],
      'estadisticas'   => ['Estadísticas',         'fa-chart-line'],
      'perfil'         => ['Mi perfil de empresa', 'fa-building'],
    ] as $key => [$label, $icon]): ?>
      <div class="nav-item">
        <a href="proveedor.php?page=<?= $key ?>" class="<?= $page === $key ? 'active' : '' ?>">
          <span class="nav-icon"><i class="fas <?= $icon ?>"></i></span>
          <?= $label ?>
        </a>
      </div>
    <?php endforeach; ?>
  </nav>

  <div class="sidebar-footer">
    <a href="index.php" class="btn-site"><i class="fas fa-external-link-alt"></i> Ver sitio</a>
    <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>
</aside>

<!-- ══ MAIN ══ -->
<div class="main">
  <header class="topbar">
    <div class="topbar-title">
      <i class="fas <?= $pageIcon ?>"></i>
      <?= $pageLabel ?>
    </div>
    <span class="topbar-badge"><i class="fas fa-store me-1"></i><?= $empresa ?></span>
  </header>

  <main class="content">
    <?php require_once __DIR__ . '/' . $vista . '.php'; ?>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
