<?php
$totalPiezas  = $stats['total'];
$totalActivas = $stats['activas'];
$sinStock     = $stats['sin_stock'];
$pausadas     = $totalPiezas - $totalActivas;

$porCategoria = [];
foreach ($piezas as $p) {
    $cat = $p['categoria'] ?: 'Otras';
    $porCategoria[$cat] = ($porCategoria[$cat] ?? 0) + 1;
}
arsort($porCategoria);

$valorTotal = array_sum(array_map(fn($p) => $p['precio'] * $p['stock'], $piezas));

$numPedidos       = $statsVentas['num_pedidos'];
$unidadesVendidas = $statsVentas['unidades_vendidas'];
$ingresosTotales  = $statsVentas['ingresos_totales'];
$topPiezas        = $statsVentas['top_piezas'];
$porMes           = $statsVentas['por_mes'];
$recientes        = $statsVentas['recientes'];
$maxIngresosMes   = $porMes   ? max(array_column($porMes,   'ingresos'))  : 0;
$maxUnidades      = $topPiezas ? max(array_column($topPiezas,'unidades')) : 0;
?>

<!-- ══ INVENTARIO ══ -->
<div style="font-size:.68rem;letter-spacing:2px;text-transform:uppercase;color:var(--text-faint);margin-bottom:14px;">Inventario</div>
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--accent-dim); color:var(--accent);"><i class="fas fa-boxes"></i></div>
      <div><div class="stat-value"><?= $totalPiezas ?></div><div class="stat-label">Total piezas</div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(25,135,84,0.15); color:#22c55e;"><i class="fas fa-check-circle"></i></div>
      <div><div class="stat-value"><?= $totalActivas ?></div><div class="stat-label">Activas</div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(245,158,11,0.12); color:#c4920a;"><i class="fas fa-pause-circle"></i></div>
      <div><div class="stat-value"><?= $pausadas ?></div><div class="stat-label">Pausadas</div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(99,102,241,0.15); color:#7c87d8;"><i class="fas fa-euro-sign"></i></div>
      <div><div class="stat-value"><?= number_format($valorTotal, 0, ',', '.') ?>€</div><div class="stat-label">Valor inventario</div></div>
    </div>
  </div>
</div>

<!-- ══ VENTAS ══ -->
<div style="font-size:.68rem;letter-spacing:2px;text-transform:uppercase;color:var(--text-faint);margin-bottom:14px;">Ventas</div>
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(139,92,246,0.15); color:#9580e0;"><i class="fas fa-shopping-bag"></i></div>
      <div><div class="stat-value"><?= $numPedidos ?></div><div class="stat-label">Pedidos completados</div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--accent-dim); color:var(--accent);"><i class="fas fa-cubes"></i></div>
      <div><div class="stat-value"><?= $unidadesVendidas ?></div><div class="stat-label">Unidades vendidas</div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(25,135,84,0.15); color:#22c55e;"><i class="fas fa-coins"></i></div>
      <div><div class="stat-value"><?= number_format($ingresosTotales, 2, ',', '.') ?>€</div><div class="stat-label">Ingresos totales</div></div>
    </div>
  </div>
</div>

<!-- ══ GRÁFICOS ══ -->
<div class="row g-3 mb-4">

  <!-- Ingresos por mes -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-chart-bar"></i> Ingresos por mes</div></div>
      <div style="padding:20px;">
        <?php if (empty($porMes)): ?>
          <div style="color:var(--text-muted); text-align:center; margin:30px 0;">
            <i class="fas fa-chart-bar fa-2x" style="display:block;margin-bottom:10px;opacity:.2;"></i>Sin ventas registradas aún
          </div>
        <?php else: ?>
          <?php foreach ($porMes as $m): ?>
            <div style="margin-bottom:14px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <span style="font-size:.82rem; color:var(--text-muted);"><?= htmlspecialchars($m['mes_label']) ?></span>
                <span style="font-size:.82rem; color:#22c55e; font-weight:700;"><?= number_format($m['ingresos'], 2, ',', '.') ?>€</span>
              </div>
              <div style="background:var(--border-soft); border:1px solid var(--border); border-radius:4px; height:8px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,#14532d,#22c55e); height:100%; width:<?= $maxIngresosMes > 0 ? round($m['ingresos']/$maxIngresosMes*100) : 0 ?>%; border-radius:4px; transition:width .4s;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Top piezas -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-trophy"></i> Piezas más vendidas</div></div>
      <div style="padding:20px;">
        <?php if (empty($topPiezas)): ?>
          <div style="color:var(--text-muted); text-align:center; margin:30px 0;">
            <i class="fas fa-trophy fa-2x" style="display:block;margin-bottom:10px;opacity:.2;"></i>Sin ventas registradas aún
          </div>
        <?php else: ?>
          <?php foreach ($topPiezas as $i => $tp): ?>
            <?php $rankColor = $i === 0 ? '#c4920a' : ($i === 1 ? '#94a3b8' : ($i === 2 ? '#c2894f' : 'var(--text-faint)')); ?>
            <div style="margin-bottom:14px;">
              <div style="display:flex; align-items:center; gap:10px; margin-bottom:5px;">
                <span style="font-size:.75rem;font-weight:800;color:<?= $rankColor ?>;width:18px;text-align:center;">#<?= $i+1 ?></span>
                <?php if ($tp['imagen']): ?>
                  <img src="<?= htmlspecialchars($tp['imagen']) ?>"
                       style="width:32px;height:32px;object-fit:cover;border-radius:6px;border:1px solid var(--border);"
                       onerror="this.style.display='none'" alt="">
                <?php endif; ?>
                <span style="font-size:.85rem; color:var(--text); flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= htmlspecialchars($tp['nombre']) ?></span>
                <span style="font-size:.8rem; color:var(--accent); font-weight:700; white-space:nowrap;"><?= (int)$tp['unidades'] ?> ud.</span>
              </div>
              <div style="margin-left:28px; background:var(--border-soft); border:1px solid var(--border); border-radius:4px; height:5px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,var(--accent),rgba(164,4,46,.5)); height:100%; width:<?= $maxUnidades > 0 ? round($tp['unidades']/$maxUnidades*100) : 0 ?>%; border-radius:4px;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<!-- ══ FILA INFERIOR ══ -->
<div class="row g-3">

  <!-- Piezas por categoría -->
  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-chart-pie"></i> Piezas por categoría</div></div>
      <div style="padding:20px;">
        <?php if (empty($porCategoria)): ?>
          <p style="color:var(--text-muted); text-align:center; margin:20px 0;">Sin datos todavía</p>
        <?php else: ?>
          <?php $maxVal = max($porCategoria); ?>
          <?php foreach ($porCategoria as $cat => $count): ?>
            <div style="margin-bottom:14px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                <span style="font-size:.85rem; color:var(--text);"><?= htmlspecialchars($cat) ?></span>
                <span style="font-size:.82rem; color:var(--accent); font-weight:600;"><?= $count ?></span>
              </div>
              <div style="background:var(--border-soft); border:1px solid var(--border); border-radius:4px; height:6px; overflow:hidden;">
                <div style="background:linear-gradient(90deg,var(--accent),rgba(164,4,46,.5)); height:100%; width:<?= round($count/$maxVal*100) ?>%; border-radius:4px; transition:width .4s;"></div>
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
        <div style="padding:40px; text-align:center; color:var(--text-muted);">
          <i class="fas fa-receipt fa-2x" style="display:block;margin-bottom:10px;opacity:.2;"></i>Sin ventas registradas aún
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-prov" style="margin:0;">
            <thead><tr>
              <th>Pieza</th><th>Uds.</th><th>Subtotal</th><th>Fecha</th>
            </tr></thead>
            <tbody>
              <?php foreach ($recientes as $r): ?>
              <tr>
                <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text);font-weight:500;">
                  <?= htmlspecialchars($r['pieza_nombre']) ?>
                </td>
                <td style="color:var(--text-muted);"><?= (int)$r['cantidad'] ?></td>
                <td style="color:#22c55e; font-weight:700;"><?= number_format($r['subtotal'], 2, ',', '.') ?>€</td>
                <td style="color:var(--text-muted); font-size:.78rem; white-space:nowrap;">
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
            <span style="background:rgba(220,53,69,.1);border:1px solid rgba(220,53,69,.25);color:#f87171;padding:4px 12px;border-radius:20px;font-size:.8rem;">
              <i class="fas fa-exclamation-circle me-1"></i><?= htmlspecialchars($p['nombre']) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
