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
?>

<!-- Stats principales -->
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

<!-- Piezas por categoría -->
<div class="row g-3">
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

  <div class="col-md-6">
    <div class="admin-card" style="height:100%;">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-exclamation-triangle"></i> Piezas sin stock</div></div>
      <div style="padding:20px;">
        <?php $sinStockPiezas = array_filter($piezas, fn($p) => $p['stock'] == 0); ?>
        <?php if (empty($sinStockPiezas)): ?>
          <p style="color:#75b798; text-align:center; margin:20px 0;"><i class="fas fa-check-circle me-2"></i>Todo en stock correcto</p>
        <?php else: ?>
          <?php foreach (array_slice($sinStockPiezas, 0, 8) as $p): ?>
            <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid rgba(255,255,255,.04);">
              <span style="font-size:.85rem; color:rgba(255,255,255,.7);"><?= htmlspecialchars($p['nombre']) ?></span>
              <span class="badge-sinstock">Sin stock</span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
