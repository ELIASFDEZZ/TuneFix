<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel Proveedor — TuneFix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* ══ VARIABLES ══════════════════════════════════════════════════════════ */
    :root {
      --accent:      #9a1230;
      --accent-dim:  rgba(154, 18, 48, 0.14);
      --accent-glow: rgba(154, 18, 48, 0.28);

      /* Paleta oscura unificada */
      --bg:          #111827;
      --surface:     #1a1f2e;
      --card:        #1a1f2e;
      --card-hover:  #1f263a;
      --border:      rgba(255,255,255,0.08);
      --border-soft: rgba(255,255,255,0.05);
      --text:        #94a3b8;
      --text-muted:  rgba(255,255,255,0.42);
      --text-faint:  rgba(255,255,255,0.22);
      --text-dark:   #e2e8f0;

      /* Sidebar */
      --sb-bg:       #1a1f2e;
      --sb-surface:  rgba(255,255,255,0.05);
      --sb-border:   rgba(255,255,255,0.07);
      --sb-text:     rgba(255,255,255,0.50);
      --sb-text-hi:  #ffffff;
      --sb-hover:    rgba(255,255,255,0.06);
      --sb-faint:    rgba(255,255,255,0.20);

      --sw: 252px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      font-size: 13.5px;
      line-height: 1.6;
      background: var(--bg);
      color: var(--text);
    }

    /* ══ SIDEBAR ════════════════════════════════════════════════════════════ */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: var(--sw); height: 100vh;
      background: var(--sb-bg);
      border-right: 1px solid var(--sb-border);
      display: flex; flex-direction: column;
      z-index: 200;
      overflow-y: auto; overflow-x: hidden;
    }

    /* Brand */
    .sb-brand {
      padding: 22px 20px 18px;
      border-bottom: 1px solid var(--sb-border);
      flex-shrink: 0;
    }
    .sb-logo {
      display: flex; align-items: center; gap: 10px; margin-bottom: 14px;
    }
    .sb-logo-icon {
      width: 34px; height: 34px; border-radius: 9px;
      background: linear-gradient(135deg, #6b0120, var(--accent));
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: .78rem; flex-shrink: 0;
      box-shadow: 0 4px 12px var(--accent-glow);
    }
    .sb-logo-text {
      font-size: 1.05rem; font-weight: 800;
      color: var(--sb-text-hi); letter-spacing: -.4px;
    }
    .sb-logo-text em { color: #f87171; font-style: normal; }
    .sb-tag {
      font-size: .58rem; font-weight: 700; letter-spacing: 1.5px;
      text-transform: uppercase; color: #fff;
      background: var(--accent); padding: 2px 8px; border-radius: 4px;
      margin-left: auto;
    }

    /* Company chip */
    .sb-company {
      display: flex; align-items: center; gap: 10px;
      background: var(--sb-surface);
      border: 1px solid var(--sb-border);
      border-radius: 10px; padding: 10px 12px;
    }
    .sb-company-avatar {
      width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
      background: linear-gradient(135deg, #6b0120, var(--accent));
      display: flex; align-items: center; justify-content: center;
      font-size: .9rem; font-weight: 800; color: #fff;
      box-shadow: 0 2px 8px var(--accent-glow);
    }
    .sb-company-name {
      font-size: .8rem; font-weight: 700; color: var(--sb-text-hi);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sb-company-badge {
      display: inline-flex; align-items: center; gap: 3px;
      font-size: .6rem; font-weight: 700; letter-spacing: .3px;
      color: #4ade80; margin-top: 2px;
    }

    /* Nav */
    .sb-nav { padding: 16px 12px; flex: 1; }
    .sb-group-label {
      font-size: .6rem; font-weight: 700; letter-spacing: 1.8px;
      text-transform: uppercase; color: var(--sb-faint);
      padding: 12px 8px 6px; display: block;
    }
    .sb-link {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 11px; border-radius: 8px; margin-bottom: 2px;
      text-decoration: none; color: var(--sb-text);
      font-size: .845rem; font-weight: 500;
      transition: background .15s, color .15s;
      position: relative;
    }
    .sb-link:hover { background: var(--sb-hover); color: var(--sb-text-hi); }
    .sb-link.active {
      background: var(--accent-dim);
      color: #f87171; font-weight: 600;
      border: 1px solid rgba(154,18,48,0.28);
    }
    .sb-link.active::before {
      content: '';
      position: absolute; left: -1px; top: 22%; bottom: 22%;
      width: 3px; border-radius: 0 3px 3px 0;
      background: var(--accent);
    }
    .sb-link .sbi {
      width: 18px; text-align: center; font-size: .82rem;
      color: var(--sb-faint); flex-shrink: 0;
      transition: color .15s;
    }
    .sb-link:hover .sbi, .sb-link.active .sbi { color: inherit; }
    .sb-badge {
      margin-left: auto; background: var(--accent);
      color: #fff; font-size: .58rem; font-weight: 800;
      padding: 1px 6px; border-radius: 4px; line-height: 1.4;
    }

    /* Footer */
    .sb-footer {
      padding: 12px;
      border-top: 1px solid var(--sb-border);
      display: flex; flex-direction: column; gap: 2px;
      flex-shrink: 0;
    }
    .sb-footer-link {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: 7px;
      text-decoration: none; font-size: .82rem; font-weight: 500;
      transition: background .15s;
    }
    .sb-footer-link.site { color: var(--sb-text); }
    .sb-footer-link.site:hover { background: var(--sb-hover); color: var(--sb-text-hi); }
    .sb-footer-link.out { color: rgba(248,113,113,.65); }
    .sb-footer-link.out:hover { background: rgba(220,38,38,.12); color: #f87171; }

    /* ══ MAIN ═══════════════════════════════════════════════════════════════ */
    .main {
      margin-left: var(--sw);
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* Topbar */
    .topbar {
      height: 58px;
      background: rgba(26,31,46,0.97);
      backdrop-filter: blur(14px);
      border-bottom: 1px solid var(--border-soft);
      box-shadow: 0 1px 0 var(--border);
      padding: 0 28px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .topbar-left { display: flex; align-items: center; gap: 10px; }
    .topbar-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: var(--accent-dim); color: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: .8rem;
    }
    .topbar-title { font-size: .95rem; font-weight: 700; color: #fff; }
    .topbar-right { display: flex; align-items: center; gap: 8px; }
    .topbar-chip {
      display: flex; align-items: center; gap: 7px;
      background: rgba(255,255,255,0.05);
      border: 1px solid var(--border);
      border-radius: 20px; padding: 5px 14px 5px 7px;
      font-size: .76rem; font-weight: 600; color: rgba(255,255,255,0.65);
    }
    .topbar-chip-dot {
      width: 7px; height: 7px; border-radius: 50%;
      background: #22c55e;
      box-shadow: 0 0 6px rgba(34,197,94,0.4);
    }

    .content { padding: 28px; flex: 1; }

    /* ══ STAT CARDS ══════════════════════════════════════════════════════════ */
    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px; padding: 20px;
      display: flex; align-items: center; gap: 16px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.25);
      transition: border-color .2s, transform .2s, box-shadow .2s;
    }
    .stat-card:hover {
      border-color: rgba(154,18,48,0.30);
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    }
    .stat-icon {
      width: 48px; height: 48px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.15rem; flex-shrink: 0;
    }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: #e2e8f0; line-height: 1; }
    .stat-label { font-size: .74rem; color: var(--text-muted); margin-top: 4px; font-weight: 500; }

    /* ══ CARDS ═══════════════════════════════════════════════════════════════ */
    .admin-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px; overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }
    .admin-card-header {
      padding: 15px 20px;
      border-bottom: 1px solid var(--border-soft);
      display: flex; align-items: center; justify-content: space-between;
      background: rgba(255,255,255,0.02);
    }
    .admin-card-title {
      font-size: .875rem; font-weight: 700; color: #fff;
      display: flex; align-items: center; gap: 8px;
    }
    .admin-card-title i { color: var(--accent); font-size: .82rem; }

    /* ══ TABLE ═══════════════════════════════════════════════════════════════ */
    .table-prov { color: var(--text); margin: 0; font-size: .855rem; }
    .table-prov thead th {
      background: rgba(255,255,255,0.02);
      color: var(--text-faint);
      font-size: .66rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 1px;
      border-color: var(--border-soft); padding: 10px 18px;
    }
    .table-prov tbody td {
      border-color: var(--border-soft);
      padding: 12px 18px; vertical-align: middle;
      background: transparent;
    }
    .table-prov tbody tr:hover td { background: rgba(255,255,255,0.025); }

    /* ══ BADGES ══════════════════════════════════════════════════════════════ */
    .badge-activa {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(22,163,74,0.12); color: #4ade80;
      border: 1px solid rgba(22,163,74,0.28);
      padding: 3px 10px; border-radius: 20px;
      font-size: .7rem; font-weight: 700;
    }
    .badge-activa::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #4ade80; }
    .badge-pausada {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(234,179,8,0.12); color: #fbbf24;
      border: 1px solid rgba(234,179,8,0.28);
      padding: 3px 10px; border-radius: 20px;
      font-size: .7rem; font-weight: 700;
    }
    .badge-pausada::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #fbbf24; }
    .badge-sinstock {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(239,68,68,0.12); color: #f87171;
      border: 1px solid rgba(239,68,68,0.28);
      padding: 3px 10px; border-radius: 20px;
      font-size: .7rem; font-weight: 700;
    }
    .badge-sinstock::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #f87171; }

    /* ══ BUTTONS ═════════════════════════════════════════════════════════════ */
    .btn-red {
      background: var(--accent); color: #fff;
      border: none; border-radius: 8px;
      font-size: .82rem; font-weight: 700;
      padding: 8px 16px; cursor: pointer;
      display: inline-flex; align-items: center; gap: 6px;
      transition: filter .15s, transform .15s, box-shadow .15s;
      text-decoration: none;
    }
    .btn-red:hover {
      filter: brightness(1.15); color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 4px 16px var(--accent-glow);
    }
    .btn-ghost {
      background: rgba(255,255,255,0.06);
      color: rgba(255,255,255,0.65);
      border: 1px solid rgba(255,255,255,0.10);
      border-radius: 8px; font-size: .82rem; font-weight: 500;
      padding: 8px 16px; cursor: pointer;
      display: inline-flex; align-items: center; gap: 6px;
      transition: background .15s, color .15s;
      text-decoration: none;
    }
    .btn-ghost:hover { background: rgba(255,255,255,0.10); color: #fff; }

    /* ══ FORMS ═══════════════════════════════════════════════════════════════ */
    .form-control-prov, .form-control {
      background: rgba(255,255,255,0.04) !important;
      border: 1px solid rgba(255,255,255,0.10) !important;
      color: #fff !important; border-radius: 9px !important;
      font-family: inherit; font-size: .86rem;
      padding: 9px 13px;
      transition: border-color .15s, box-shadow .15s;
    }
    .form-control-prov:focus, .form-control:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(154,18,48,0.18) !important;
      background: rgba(255,255,255,0.07) !important;
      color: #fff !important; outline: none !important;
    }
    .form-control-prov::placeholder, .form-control::placeholder { color: var(--text-faint) !important; }

    .form-select-prov, .form-select {
      background: rgba(255,255,255,0.04) !important;
      border: 1px solid rgba(255,255,255,0.10) !important;
      color: #fff !important; border-radius: 9px !important;
      font-family: inherit; font-size: .86rem;
      transition: border-color .15s, box-shadow .15s;
    }
    .form-select-prov:focus, .form-select:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 3px rgba(154,18,48,0.18) !important;
      background: rgba(255,255,255,0.07) !important;
      color: #fff !important;
    }
    .form-select-prov option, .form-select option { background: #1a1f2e; color: #e2e8f0; }

    .form-label-prov {
      color: rgba(255,255,255,0.52); font-size: .8rem;
      font-weight: 600; margin-bottom: 6px; display: block;
      letter-spacing: .2px;
    }

    /* ══ ALERTS ══════════════════════════════════════════════════════════════ */
    .alert-ok-prov {
      background: rgba(22,163,74,0.10); border: 1px solid rgba(22,163,74,0.28);
      color: #4ade80; border-radius: 10px; padding: 12px 16px;
      font-size: .85rem; margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }
    .alert-err-prov {
      background: rgba(239,68,68,0.10); border: 1px solid rgba(239,68,68,0.28);
      color: #f87171; border-radius: 10px; padding: 12px 16px;
      font-size: .85rem; margin-bottom: 20px;
      display: flex; align-items: center; gap: 8px;
    }

    /* ══ VEHICLE BLOCK (publicar/editar) ════════════════════════════════════ */
    .vehiculo-block {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 11px; padding: 18px 18px 14px;
      margin-bottom: 10px; position: relative;
    }
    .btn-remove-veh {
      position: absolute; top: 12px; right: 12px;
      background: rgba(239,68,68,0.12);
      border: 1px solid rgba(239,68,68,0.22);
      color: #f87171; border-radius: 7px;
      width: 30px; height: 30px; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: .8rem; transition: background .15s;
    }
    .btn-remove-veh:hover { background: rgba(239,68,68,0.25); }
    select:disabled { opacity: .35; cursor: not-allowed; }

    /* ══ PIEZA THUMB ═════════════════════════════════════════════════════════ */
    .pieza-thumb {
      width: 44px; height: 44px; object-fit: cover;
      border-radius: 8px; border: 1px solid var(--border);
    }

    /* ══ SECTION DIVIDER ═════════════════════════════════════════════════════ */
    .section-divider {
      font-size: .62rem; letter-spacing: 2px; text-transform: uppercase;
      color: var(--text-faint); padding: 8px 0 6px;
      border-bottom: 1px solid var(--border-soft);
      margin: 22px 0 16px;
    }

    /* ══ SCROLLBAR ═══════════════════════════════════════════════════════════ */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.10); border-radius: 99px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.20); }
  </style>
</head>
<body>

<?php
$page       = $_GET['page'] ?? 'dashboard';
$pageLabels = [
  'dashboard'      => ['Dashboard',            'fa-home'],
  'mis-piezas'     => ['Mis Piezas',           'fa-boxes'],
  'publicar-pieza' => ['Publicar nueva pieza', 'fa-plus-circle'],
  'editar-pieza'   => ['Editar pieza',         'fa-pen'],
  'estadisticas'   => ['Estadísticas',         'fa-chart-line'],
  'pedidos'        => ['Pedidos',              'fa-shopping-bag'],
  'perfil'         => ['Mi perfil',            'fa-building'],
];
[$pageLabel, $pageIcon] = $pageLabels[$page] ?? ['Dashboard', 'fa-home'];

$empresa  = htmlspecialchars($_SESSION['proveedor_empresa'] ?? $_SESSION['usuario_nombre'] ?? 'Empresa');
$inicial  = strtoupper(mb_substr($empresa, 0, 1));
$activeKey = ($page === 'editar-pieza') ? 'mis-piezas' : $page;
?>

<!-- ══ SIDEBAR ══════════════════════════════════════════════════════════════ -->
<aside class="sidebar">

  <!-- Brand -->
  <div class="sb-brand">
    <div class="sb-logo">
      <div class="sb-logo-icon"><i class="fas fa-wrench"></i></div>
      <span class="sb-logo-text">Tune<em>Fix</em></span>
      <span class="sb-tag">Pro</span>
    </div>
    <div class="sb-company">
      <div class="sb-company-avatar"><?= $inicial ?></div>
      <div style="min-width:0;">
        <div class="sb-company-name"><?= $empresa ?></div>
        <div class="sb-company-badge">
          <i class="fas fa-check-circle"></i> Verificado
        </div>
      </div>
    </div>
  </div>

  <!-- Nav -->
  <nav class="sb-nav">
    <span class="sb-group-label">Panel</span>
    <?php foreach ([
      'dashboard'      => ['Dashboard',            'fa-home'],
      'mis-piezas'     => ['Mis Piezas',           'fa-boxes'],
      'publicar-pieza' => ['Publicar pieza',        'fa-plus-circle'],
      'pedidos'        => ['Pedidos',              'fa-shopping-bag'],
    ] as $key => [$label, $icon]): ?>
      <a href="proveedor.php?page=<?= $key ?>"
         class="sb-link <?= $activeKey === $key ? 'active' : '' ?>">
        <i class="fas <?= $icon ?> sbi"></i>
        <?= $label ?>
      </a>
    <?php endforeach; ?>

    <span class="sb-group-label" style="margin-top:4px;">Análisis</span>
    <?php foreach ([
      'estadisticas'   => ['Estadísticas',         'fa-chart-line'],
      'perfil'         => ['Mi empresa',            'fa-building'],
    ] as $key => [$label, $icon]): ?>
      <a href="proveedor.php?page=<?= $key ?>"
         class="sb-link <?= $activeKey === $key ? 'active' : '' ?>">
        <i class="fas <?= $icon ?> sbi"></i>
        <?= $label ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <!-- Footer -->
  <div class="sb-footer">
    <a href="logout.php" class="sb-footer-link out">
      <i class="fas fa-sign-out-alt" style="font-size:.72rem;width:16px;text-align:center;"></i>
      Cerrar sesión
    </a>
  </div>
</aside>

<!-- ══ MAIN ══════════════════════════════════════════════════════════════════ -->
<div class="main">

  <!-- Topbar -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="topbar-icon"><i class="fas <?= $pageIcon ?>"></i></div>
      <span class="topbar-title"><?= $pageLabel ?></span>
    </div>
    <div class="topbar-right">
      <div class="topbar-chip">
        <div class="topbar-chip-dot"></div>
        <?= $empresa ?>
      </div>
    </div>
  </header>

  <!-- Content -->
  <main class="content">
    <?php require __DIR__ . '/' . $vista . '.php'; ?>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
