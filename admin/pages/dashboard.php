<?php
// ── Stats ────────────────────────────────────────────────────────────────────
$stats = [];
foreach ([
    'usuarios'       => "SELECT COUNT(*) FROM usuario",
    'piezas'         => "SELECT COUNT(*) FROM pieza",
    'tutoriales'     => "SELECT COUNT(*) FROM tutorial",
    'manuales'       => "SELECT COUNT(*) FROM manual",
    'marcas'         => "SELECT COUNT(*) FROM marca",
    'motorizaciones' => "SELECT COUNT(*) FROM motorizacion",
] as $key => $sql) {
    $stats[$key] = (int) $pdo->query($sql)->fetchColumn();
}

// Usuarios por rol
$rolesData = $pdo->query(
    "SELECT rol, COUNT(*) as cnt FROM usuario GROUP BY rol ORDER BY cnt DESC"
)->fetchAll();

// Últimos 5 usuarios
$recentUsers = $pdo->query(
    "SELECT id, nombre, email, rol FROM usuario ORDER BY id DESC LIMIT 5"
)->fetchAll();

// Piezas más compatibles
$topPiezas = $pdo->query(
    "SELECT p.nombre, COUNT(cp.motorizacion_id) as compat
     FROM pieza p
     LEFT JOIN compatibilidad_pieza cp ON cp.pieza_id = p.id
     GROUP BY p.id ORDER BY compat DESC LIMIT 5"
)->fetchAll();
?>

<!-- KPIs -->
<div class="row g-3 mb-4">
  <?php
  $kpis = [
    ['Usuarios',       $stats['usuarios'],       'fa-users',       'rgba(164,4,46,.2)',    'rgba(164,4,46,.8)'],
    ['Piezas',         $stats['piezas'],          'fa-cog',         'rgba(255,136,0,.2)',   'rgba(255,136,0,.8)'],
    ['Tutoriales',     $stats['tutoriales'],      'fa-play-circle', 'rgba(13,110,253,.2)',  'rgba(13,110,253,.8)'],
    ['Manuales',       $stats['manuales'],        'fa-file-pdf',    'rgba(25,135,84,.2)',   'rgba(25,135,84,.8)'],
    ['Marcas',         $stats['marcas'],          'fa-car',         'rgba(102,16,242,.2)',  'rgba(102,16,242,.8)'],
    ['Motorizaciones', $stats['motorizaciones'],  'fa-tachometer-alt','rgba(32,201,151,.2)','rgba(32,201,151,.8)'],
  ];
  foreach ($kpis as [$label, $val, $icon, $bgColor, $iconColor]):
  ?>
  <div class="col-sm-6 col-lg-4 col-xl-2">
    <div class="stat-card">
      <div class="stat-icon" style="background:<?= $bgColor ?>;">
        <i class="fas <?= $icon ?>" style="color:<?= $iconColor ?>;"></i>
      </div>
      <div>
        <div class="stat-value"><?= $val ?></div>
        <div class="stat-label"><?= $label ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">

  <!-- Usuarios recientes -->
  <div class="col-lg-6">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-user-clock"></i> Últimos registros</div>
        <a href="index.php?page=usuarios" class="btn-ghost" style="font-size:.75rem;text-decoration:none;padding:5px 10px;">Ver todos</a>
      </div>
      <table class="table table-admin">
        <thead>
          <tr><th>Usuario</th><th>Email</th><th>Rol</th></tr>
        </thead>
        <tbody>
          <?php foreach ($recentUsers as $u): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:30px;height:30px;background:rgba(164,4,46,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;color:var(--red,rgb(164,4,46));">
                  <?= strtoupper(mb_substr($u['nombre'],0,1)) ?>
                </div>
                <?= htmlspecialchars($u['nombre']) ?>
              </div>
            </td>
            <td style="color:rgba(255,255,255,.5);font-size:.82rem;"><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="rol-badge rol-<?= $u['rol'] ?>"><?= ucfirst($u['rol']) ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Distribución de roles -->
  <div class="col-lg-3">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-chart-pie"></i> Roles</div>
      </div>
      <div style="padding:20px;">
        <?php
        $roleColors = ['principiante'=>'rgba(13,110,253,.7)','entusiasta'=>'rgba(255,136,0,.7)','profesional'=>'rgba(164,4,46,.7)'];
        foreach ($rolesData as $r):
          $pct = $stats['usuarios'] > 0 ? round($r['cnt'] / $stats['usuarios'] * 100) : 0;
        ?>
        <div class="mb-3">
          <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
            <span style="font-size:.82rem;color:rgba(255,255,255,.7);"><?= ucfirst($r['rol']) ?></span>
            <span style="font-size:.82rem;font-weight:700;color:#fff;"><?= $r['cnt'] ?></span>
          </div>
          <div style="background:rgba(255,255,255,.07);border-radius:20px;height:6px;">
            <div style="background:<?= $roleColors[$r['rol']] ?? '#888' ?>;width:<?= $pct ?>%;height:6px;border-radius:20px;"></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Top piezas compatibles -->
  <div class="col-lg-3">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-star"></i> Top piezas</div>
      </div>
      <div style="padding:16px;">
        <?php foreach ($topPiezas as $i => $p): ?>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <div style="width:24px;height:24px;background:rgba(164,4,46,.2);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:rgb(164,4,46);flex-shrink:0;">
            <?= $i+1 ?>
          </div>
          <div style="flex:1;overflow:hidden;">
            <div style="font-size:.8rem;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($p['nombre']) ?></div>
            <div style="font-size:.72rem;color:rgba(255,255,255,.35);"><?= $p['compat'] ?> motorizaciones</div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
