<?php
$okMsgs = [
    'publicada' => 'Pieza publicada correctamente.',
    'editada'   => 'Pieza actualizada correctamente.',
    'eliminada' => 'Pieza eliminada del catálogo.',
];
$okMsg = $okMsgs[$_GET['ok'] ?? ''] ?? null;
?>

<style>
.btn-action-sm {
  display:inline-flex; align-items:center; justify-content:center;
  width:30px; height:30px; border-radius:7px; font-size:.78rem;
  border:1px solid var(--border); background:rgba(255,255,255,.04);
  color:var(--text-muted); cursor:pointer; transition:.18s;
  text-decoration:none;
}
.btn-action-sm:hover { background:rgba(255,255,255,.1); color:var(--text); border-color:rgba(255,255,255,.18); }
.btn-action-edit { background:var(--accent-dim); border-color:var(--accent-glow); color:var(--accent); }
.btn-action-edit:hover { background:rgba(164,4,46,.28); color:var(--accent); }
.btn-action-del { background:rgba(220,53,69,.1); border-color:rgba(220,53,69,.25); color:#f87171; }
.btn-action-del:hover { background:rgba(220,53,69,.25); color:#fca5a5; }
</style>

<?php if ($okMsg): ?>
  <div class="<?= ($_GET['ok'] ?? '') === 'eliminada' ? 'alert-err-prov' : 'alert-ok-prov' ?>">
    <i class="fas fa-<?= ($_GET['ok'] ?? '') === 'eliminada' ? 'trash-alt' : 'check-circle' ?> me-2"></i><?= $okMsg ?>
  </div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-boxes"></i> Mis Piezas (<?= count($piezas) ?>)</div>
    <a href="proveedor.php?page=publicar-pieza" class="btn-red btn">
      <i class="fas fa-plus me-1"></i> Nueva pieza
    </a>
  </div>

  <?php if (empty($piezas)): ?>
    <div style="padding:60px 20px; text-align:center;">
      <i class="fas fa-box-open" style="font-size:2.8rem; display:block; margin-bottom:16px; color:var(--text-faint);"></i>
      <p style="margin:0 0 20px; font-size:.95rem; color:var(--text-muted);">Aún no has publicado ninguna pieza.</p>
      <a href="proveedor.php?page=publicar-pieza" class="btn-red btn">Publicar primera pieza</a>
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
                <img src="<?= htmlspecialchars($p['imagen']) ?>" class="pieza-thumb" alt="">
              <?php else: ?>
                <div class="pieza-thumb d-flex align-items-center justify-content-center"
                     style="background:var(--card); color:var(--text-faint); font-size:.8rem;">
                  <i class="fas fa-image"></i>
                </div>
              <?php endif; ?>
            </td>
            <td style="max-width:180px;">
              <span style="color:var(--text); font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></span>
              <?php if ($p['estado_pieza'] === 'nueva'): ?>
                <span style="display:inline-block;font-size:.68rem;background:rgba(25,135,84,.15);color:#22c55e;padding:1px 7px;border-radius:10px;margin-left:4px;">Nueva</span>
              <?php endif; ?>
            </td>
            <td><span style="color:var(--text-muted); font-size:.85rem;"><?= htmlspecialchars($p['categoria']) ?></span></td>
            <td>
              <code style="color:var(--accent); font-size:.78rem; opacity:.85;">
                <?= $p['referencia_oem'] ? htmlspecialchars($p['referencia_oem']) : '<span style="color:var(--text-faint)">—</span>' ?>
              </code>
            </td>
            <td style="color:var(--accent); font-weight:700; white-space:nowrap;"><?= number_format($p['precio'], 2) ?> €</td>
            <td style="color:<?= $p['stock'] == 0 ? '#c4920a' : 'var(--text)' ?>;font-weight:<?= $p['stock'] == 0 ? '700' : '400' ?>;">
              <?= $p['stock'] ?> uds.
            </td>
            <td><span style="color:var(--text-muted); font-size:.82rem;"><?= $p['num_vehiculos'] ?> veh.</span></td>
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
              <div class="d-flex gap-1 flex-wrap">
                <form method="POST" action="proveedor.php" style="display:inline;">
                  <input type="hidden" name="action" value="toggle_pieza">
                  <input type="hidden" name="pieza_id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn-action-sm" title="<?= $p['activa'] ? 'Pausar' : 'Activar' ?>">
                    <i class="fas <?= $p['activa'] ? 'fa-pause' : 'fa-play' ?>"></i>
                  </button>
                </form>
                <a href="proveedor.php?page=editar-pieza&id=<?= $p['id'] ?>" class="btn-action-sm btn-action-edit" title="Editar">
                  <i class="fas fa-pen"></i>
                </a>
                <form method="POST" action="proveedor.php" style="display:inline;"
                      onsubmit="return confirm('¿Eliminar esta pieza del catálogo? No se puede deshacer.')">
                  <input type="hidden" name="action" value="eliminar_pieza">
                  <input type="hidden" name="pieza_id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn-action-sm btn-action-del" title="Eliminar">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
