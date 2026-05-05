<?php
$errores = [
    'publicada' => null,
];
$okMsg = isset($_GET['ok']) && $_GET['ok'] === 'publicada' ? 'Pieza publicada correctamente.' : null;
?>

<?php if ($okMsg): ?>
  <div class="alert-ok-prov"><i class="fas fa-check-circle me-2"></i><?= $okMsg ?></div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-boxes"></i> Mis Piezas (<?= count($piezas) ?>)</div>
    <a href="proveedor.php?page=publicar-pieza" class="btn-red btn">
      <i class="fas fa-plus me-1"></i> Nueva pieza
    </a>
  </div>

  <?php if (empty($piezas)): ?>
    <div style="padding:50px; text-align:center; color:rgba(255,255,255,0.3);">
      <i class="fas fa-box-open" style="font-size:2.5rem; display:block; margin-bottom:14px;"></i>
      <p style="margin:0;">Aún no has publicado ninguna pieza.</p>
      <a href="proveedor.php?page=publicar-pieza" class="btn-red btn mt-3">Publicar primera pieza</a>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-prov">
        <thead><tr>
          <th>Foto</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Ref. OEM</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Vehículos</th>
          <th>Estado</th>
          <th>Acción</th>
        </tr></thead>
        <tbody>
          <?php foreach ($piezas as $p): ?>
          <tr>
            <td>
              <?php if ($p['imagen']): ?>
                <img src="<?= htmlspecialchars($p['imagen']) ?>"
                     class="pieza-thumb" alt="">
              <?php else: ?>
                <div class="pieza-thumb d-flex align-items-center justify-content-center" style="background:#1a1a2e; color:rgba(255,255,255,.2); font-size:.8rem;"><i class="fas fa-image"></i></div>
              <?php endif; ?>
            </td>
            <td style="color:#fff; font-weight:600; max-width:180px;">
              <?= htmlspecialchars($p['nombre']) ?>
              <?php if ($p['estado_pieza'] === 'nueva'): ?>
                <span style="font-size:.68rem; background:rgba(25,135,84,.15); color:#75b798; padding:1px 7px; border-radius:10px; margin-left:4px;">Nueva</span>
              <?php endif; ?>
            </td>
            <td><span style="color:rgba(255,255,255,.55); font-size:.85rem;"><?= htmlspecialchars($p['categoria']) ?></span></td>
            <td><code style="color:rgba(255,193,7,.7); font-size:.78rem;"><?= $p['referencia_oem'] ? htmlspecialchars($p['referencia_oem']) : '—' ?></code></td>
            <td style="color:#ffc107; font-weight:700;"><?= number_format($p['precio'], 2) ?> €</td>
            <td><?= $p['stock'] ?> uds.</td>
            <td><span style="color:rgba(255,255,255,.4); font-size:.82rem;"><?= $p['num_vehiculos'] ?> veh.</span></td>
            <td>
              <?php if (!$p['activa']): ?>
                <span class="badge-pausada">Pausada</span>
              <?php elseif ($p['stock'] == 0): ?>
                <span class="badge-sinstock">Sin stock</span>
              <?php else: ?>
                <span class="badge-activa">Activa</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" action="proveedor.php" style="display:inline;">
                <input type="hidden" name="action" value="toggle_pieza">
                <input type="hidden" name="pieza_id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-sm"
                  style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); color:rgba(255,255,255,.7); border-radius:6px; font-size:.78rem; padding:4px 10px;"
                  title="<?= $p['activa'] ? 'Pausar' : 'Activar' ?>">
                  <i class="fas <?= $p['activa'] ? 'fa-pause' : 'fa-play' ?>"></i>
                  <?= $p['activa'] ? 'Pausar' : 'Activar' ?>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
