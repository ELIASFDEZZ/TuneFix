<?php
$empresa    = htmlspecialchars($proveedor['nombre_empresa'] ?? '');
$responsable = htmlspecialchars($proveedor['nombre_responsable'] ?? '');
$ok = isset($_GET['ok']);
?>

<!-- Bienvenida -->
<div style="background: linear-gradient(135deg,#1c0b14,#2a0d1a); border: 1px solid rgba(164,4,46,0.3); border-radius: 14px; padding: 24px 28px; margin-bottom: 28px;">
  <div style="display:flex; align-items:center; gap: 16px; flex-wrap:wrap;">
    <div style="font-size:2.5rem;">🏭</div>
    <div>
      <h2 style="margin:0; color:#fff; font-size:1.4rem; font-weight:800;"><?= $empresa ?></h2>
      <p style="margin:4px 0 0; color:rgba(255,255,255,0.5); font-size:0.9rem;">
        Bienvenido, <?= $responsable ?> &nbsp;·&nbsp;
        <span style="background:rgba(255,193,7,0.15); border:1px solid rgba(255,193,7,0.4); color:#ffc107; font-size:0.75rem; font-weight:700; padding:2px 10px; border-radius:20px;">
          🏅 Proveedor Verificado ✓
        </span>
      </p>
    </div>
  </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(164,4,46,0.15); color:#ff6b6b;"><i class="fas fa-boxes"></i></div>
      <div>
        <div class="stat-value"><?= $stats['total'] ?></div>
        <div class="stat-label">Piezas publicadas</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(25,135,84,0.15); color:#75b798;"><i class="fas fa-check-circle"></i></div>
      <div>
        <div class="stat-value"><?= $stats['activas'] ?></div>
        <div class="stat-label">Piezas activas</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(255,193,7,0.12); color:#ffc107;"><i class="fas fa-exclamation-triangle"></i></div>
      <div>
        <div class="stat-value"><?= $stats['sin_stock'] ?></div>
        <div class="stat-label">Sin stock</div>
      </div>
    </div>
  </div>
</div>

<!-- Últimas piezas -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-boxes"></i> Últimas piezas publicadas</div>
    <a href="proveedor.php?page=publicar-pieza" class="btn-red btn">
      <i class="fas fa-plus me-1"></i> Publicar pieza
    </a>
  </div>

  <?php if (empty($ultimasPiezas)): ?>
    <div style="padding:40px; text-align:center; color:rgba(255,255,255,0.3);">
      <i class="fas fa-box-open" style="font-size:2rem; display:block; margin-bottom:12px;"></i>
      Aún no has publicado ninguna pieza. ¡Empieza ahora!
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-prov">
        <thead><tr>
          <th>Foto</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Estado</th>
        </tr></thead>
        <tbody>
          <?php foreach ($ultimasPiezas as $p): ?>
          <tr>
            <td>
              <?php if ($p['foto_principal']): ?>
                <img src="uploads/proveedores/piezas/<?= htmlspecialchars($p['foto_principal']) ?>"
                     class="pieza-thumb" alt="">
              <?php else: ?>
                <div class="pieza-thumb d-flex align-items-center justify-content-center" style="background:#1a1a2e; color:rgba(255,255,255,.2);"><i class="fas fa-image"></i></div>
              <?php endif; ?>
            </td>
            <td style="color:#fff; font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></td>
            <td><?= htmlspecialchars($p['categoria']) ?></td>
            <td style="color:#ffc107; font-weight:600;"><?= number_format($p['precio'], 2) ?> €</td>
            <td><?= $p['stock'] ?> uds.</td>
            <td>
              <?php if (!$p['activa']): ?>
                <span class="badge-pausada">Pausada</span>
              <?php elseif ($p['stock'] == 0): ?>
                <span class="badge-sinstock">Sin stock</span>
              <?php else: ?>
                <span class="badge-activa">Activa</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div style="padding:12px 20px; border-top:1px solid rgba(255,255,255,0.05); text-align:right;">
      <a href="proveedor.php?page=mis-piezas" class="btn-ghost btn btn-sm">Ver todas las piezas</a>
    </div>
  <?php endif; ?>
</div>
