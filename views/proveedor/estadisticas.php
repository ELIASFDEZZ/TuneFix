<?php
$totalPiezas  = $stats['total'];
$totalActivas = $stats['activas'];
$sinStock     = $stats['sin_stock'];
$pausadas     = $totalPiezas - $totalActivas;

// Agrupar piezas por categoría
$porCategoria = [];
foreach ($piezas as $p) {
    $cat = $p['categoria'] ?: 'Otras';
    $porCategoria[$cat] = ($porCategoria[$cat] ?? 0) + 1;
}
arsort($porCategoria);

// Valor total del inventario
$valorTotal = array_sum(array_map(fn($p) => $p['precio'] * $p['stock'], $piezas));

// Ventas
$numPedidos      = $statsVentas['num_pedidos'];
$unidadesVendidas= $statsVentas['unidades_vendidas'];
$ingresosTotales = $statsVentas['ingresos_totales'];
$topPiezas       = $statsVentas['top_piezas'];
$porMes          = $statsVentas['por_mes'];
$recientes       = $statsVentas['recientes'];
$maxIngresosMes  = $porMes ? max(array_column($porMes, 'ingresos')) : 0;
$maxUnidades     = $topPiezas ? max(array_column($topPiezas, 'unidades')) : 0;
?>

<!-- ══ STATS INVENTARIO ══ -->
<div style="font-size:.68rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.25);margin-bottom:14px;">Inventario</div>
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(164,4,46,0.15); color:#ff6b6b;"><i class="fas fa-boxes"></i></div>
      <div><div class="stat-value"><?= $totalPiezas ?></div><div class="stat-label">Total piezas</div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(25,135,84,0.15); color:#75b798;"><i class="fas fa-check-circle"></i></div>
      <div><div class="stat-value"><?= $totalActivas ?></div><div class="stat-label">Activas</div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(255,193,7,0.12); color:#ffc107;"><i class="fas fa-pause-circle"></i></div>
      <div><div class="stat-value"><?= $pausadas ?></div><div class="stat-label">Pausadas</div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(13,110,253,0.12); color:#6ea8fe;"><i class="fas fa-euro-sign"></i></div>
      <div><div class="stat-value"><?= number_format($valorTotal, 0, ',', '.') ?>€</div><div class="stat-label">Valor inventario</div></div>
    </div>
  </div>
</div>

<!-- ══ STATS VENTAS ══ -->
<div style="font-size:.68rem;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.25);margin-bottom:14px;">Ventas</div>
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(111,66,193,0.15); color:#c29bff;"><i class="fas fa-shopping-bag"></i></div>
      <div><div class="stat-value"><?= $numPedidos ?></div><div class="stat-label">Pedidos completados</div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(255,60,0,0.12); color:#ff7f50;"><i class="fas fa-cubes"></i></div>
      <div><div class="stat-value"><?= $unidadesVendidas ?></div><div class="stat-label">Unidades vendidas</div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(25,135,84,0.15); color:#75b798;"><i class="fas fa-coins"></i></div>
      <div><div class="stat-value"><?= number_format($ingresosTotales, 2, ',', '.') ?>€</div><div class="stat-label">Ingresos totales</div></div>
    </div>
  </div>
</div>

<!-- ══ GRÁFICOS + TABLAS ══ -->
<div class="row g-3 mb-4">

  <!-- Ingresos por mes -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-chart-bar"></i> Ingresos por mes</div></div>
      <div style="padding:20px;">
        <?php if (empty($porMes)): ?>
          <p style="color:rgba(255,255,255,.3); text-align:center; margin:30px 0;"><i class="fas fa-chart-bar fa-2x" style="display:block;margin-bottom:10px;opacity:.2;"></i>Sin ventas registradas aún</p>
        <?php else: ?>
          <?php foreach ($porMes as $m): ?>
            <div style="margin-bottom:14px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <span style="font-size:.82rem; color:rgba(255,255,255,.6);"><?= htmlspecialchars($m['mes_label']) ?></span>
                <span style="font-size:.82rem; color:#75b798; font-weight:700;"><?= number_format($m['ingresos'], 2, ',', '.') ?>€</span>
              </div>
              <div style="background:rgba(255,255,255,.07); border-radius:4px; height:8px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,#198754,#75b798); height:100%; width:<?= $maxIngresosMes > 0 ? round($m['ingresos']/$maxIngresosMes*100) : 0 ?>%; border-radius:4px; transition:width .4s;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Top piezas más vendidas -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-trophy"></i> Piezas más vendidas</div></div>
      <div style="padding:20px;">
        <?php if (empty($topPiezas)): ?>
          <p style="color:rgba(255,255,255,.3); text-align:center; margin:30px 0;"><i class="fas fa-trophy fa-2x" style="display:block;margin-bottom:10px;opacity:.2;"></i>Sin ventas registradas aún</p>
        <?php else: ?>
          <?php foreach ($topPiezas as $i => $tp): ?>
            <div style="margin-bottom:14px;">
              <div style="display:flex; align-items:center; gap:10px; margin-bottom:5px;">
                <span style="font-size:.75rem; font-weight:800; color:<?= $i === 0 ? '#ffc107' : ($i === 1 ? '#adb5bd' : ($i === 2 ? '#cd7f32' : 'rgba(255,255,255,.3)')) ?>; width:18px; text-align:center;">#<?= $i+1 ?></span>
                <?php if ($tp['imagen']): ?>
                  <img src="<?= htmlspecialchars($tp['imagen']) ?>" style="width:32px;height:32px;object-fit:cover;border-radius:6px;border:1px solid rgba(255,255,255,.1);" onerror="this.style.display='none'" alt="">
                <?php endif; ?>
                <span style="font-size:.85rem; color:rgba(255,255,255,.8); flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= htmlspecialchars($tp['nombre']) ?></span>
                <span style="font-size:.8rem; color:#ffc107; font-weight:700; white-space:nowrap;"><?= (int)$tp['unidades'] ?> ud.</span>
              </div>
              <div style="margin-left:28px; background:rgba(255,255,255,.07); border-radius:4px; height:5px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,#a4042e,#ff3c00); height:100%; width:<?= $maxUnidades > 0 ? round($tp['unidades']/$maxUnidades*100) : 0 ?>%; border-radius:4px;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<!-- ══ FILA INFERIOR: inventario + ventas recientes ══ -->
<div class="row g-3">

  <!-- Piezas por categoría -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-chart-pie"></i> Piezas por categoría</div></div>
      <div style="padding:20px;">
        <?php if (empty($porCategoria)): ?>
          <p style="color:rgba(255,255,255,.3); text-align:center; margin:20px 0;">Sin datos todavía</p>
        <?php else: ?>
          <?php $maxVal = max($porCategoria); ?>
          <?php foreach ($porCategoria as $cat => $count): ?>
            <div style="margin-bottom:14px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <span style="font-size:.85rem; color:rgba(255,255,255,.7);"><?= htmlspecialchars($cat) ?></span>
                <span style="font-size:.82rem; color:#ffc107; font-weight:600;"><?= $count ?></span>
              </div>
              <div style="background:rgba(255,255,255,.07); border-radius:4px; height:6px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,#a4042e,#ff3c00); height:100%; width:<?= round($count/$maxVal*100) ?>%; border-radius:4px; transition:width .4s;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Ventas recientes -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-receipt"></i> Ventas recientes</div></div>
      <?php if (empty($recientes)): ?>
        <div style="padding:40px; text-align:center; color:rgba(255,255,255,.3);">
          <i class="fas fa-receipt fa-2x" style="display:block;margin-bottom:10px;opacity:.2;"></i>Sin ventas registradas aún
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-prov" style="margin:0;">
            <thead><tr>
              <th>Pieza</th>
              <th>Uds.</th>
              <th>Subtotal</th>
              <th>Fecha</th>
            </tr></thead>
            <tbody>
              <?php foreach ($recientes as $r): ?>
              <tr>
                <td style="max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#fff; font-weight:500;">
                  <?= htmlspecialchars($r['pieza_nombre']) ?>
                </td>
                <td style="color:rgba(255,255,255,.6);"><?= (int)$r['cantidad'] ?></td>
                <td style="color:#75b798; font-weight:700;"><?= number_format($r['subtotal'], 2, ',', '.') ?>€</td>
                <td style="color:rgba(255,255,255,.4); font-size:.78rem; white-space:nowrap;">
                  <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Piezas sin stock -->
  <?php $sinStockPiezas = array_filter($piezas, fn($p) => $p['stock'] == 0); ?>
  <?php if (!empty($sinStockPiezas)): ?>
  <div class="col-12">
    <div class="admin-card">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-exclamation-triangle"></i> Piezas sin stock</div></div>
      <div style="padding:16px 20px;">
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ($sinStockPiezas as $p): ?>
            <span style="background:rgba(220,53,69,.1);border:1px solid rgba(220,53,69,.25);color:#ff8888;padding:4px 12px;border-radius:20px;font-size:.8rem;">
              <i class="fas fa-exclamation-circle me-1"></i><?= htmlspecialchars($p['nombre']) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
