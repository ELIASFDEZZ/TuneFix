<?php
$search = trim($_GET['q'] ?? '');
$page_n = max(1, (int)($_GET['p'] ?? 1));
$perPage = 15;
$offset  = ($page_n - 1) * $perPage;

// Total
$countSql = "SELECT COUNT(*) FROM pieza";
$countParams = [];
if ($search !== '') {
    $countSql .= " WHERE nombre LIKE ? OR referencia LIKE ?";
    $like = '%'.$search.'%';
    $countParams = [$like, $like];
}
$stmtC = $pdo->prepare($countSql); $stmtC->execute($countParams);
$total = (int) $stmtC->fetchColumn();
$totalPages = (int) ceil($total / $perPage);

// List (incluye vehículo asociado si lo hay)
$sql = "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen,
               (SELECT COUNT(*) FROM compatibilidad_pieza cp WHERE cp.pieza_id = p.id) AS compat,
               (SELECT mz.id FROM compatibilidad_pieza cp
                JOIN motorizacion mz ON mz.id = cp.motorizacion_id
                WHERE cp.pieza_id = p.id LIMIT 1) AS mot_id,
               (SELECT CONCAT(ma.nombre,' ',mo.nombre,' · ',mz.nombre)
                FROM compatibilidad_pieza cp
                JOIN motorizacion mz ON mz.id = cp.motorizacion_id
                JOIN modelo mo ON mo.id = mz.modelo_id
                JOIN marca ma ON ma.id = mo.marca_id
                WHERE cp.pieza_id = p.id LIMIT 1) AS vehiculo
        FROM pieza p";
$params = [];
if ($search !== '') {
    $sql .= " WHERE p.nombre LIKE ? OR p.referencia LIKE ?";
    $like = '%'.$search.'%';
    $params = [$like, $like];
}
$sql .= " ORDER BY p.nombre ASC LIMIT {$perPage} OFFSET {$offset}";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$piezas = $stmt->fetchAll();

// Motorizaciones para el selector
$motorizaciones = $pdo->query(
    "SELECT mz.id, CONCAT(ma.nombre, ' ', mo.nombre, ' · ', mz.nombre) AS label
     FROM motorizacion mz
     JOIN modelo mo ON mo.id = mz.modelo_id
     JOIN marca ma  ON ma.id = mo.marca_id
     ORDER BY ma.nombre, mo.nombre, mz.nombre"
)->fetchAll();

$ok = $_GET['ok'] ?? '';
?>

<?php if ($ok): ?>
  <div class="alert-success-admin">
    <i class="fas fa-check-circle me-2"></i>
    <?= $ok === 'del' ? 'Pieza eliminada.' : 'Pieza guardada correctamente.' ?>
  </div>
<?php endif; ?>

<!-- Header con buscador + botón añadir -->
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-cog"></i> Piezas (<?= $total ?>)</div>
    <div class="d-flex gap-2 align-items-center">
      <form method="GET" action="index.php" class="d-flex gap-2">
        <input type="hidden" name="page" value="piezas">
        <input type="text" name="q" class="search-box" placeholder="Buscar pieza..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn-red"><i class="fas fa-search"></i></button>
        <?php if ($search): ?><a href="index.php?page=piezas" class="btn-ghost" style="text-decoration:none;">✕</a><?php endif; ?>
      </form>
      <button class="btn-red" data-bs-toggle="modal" data-bs-target="#modalPieza" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i>Nueva pieza
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-admin">
      <thead>
        <tr><th>Imagen</th><th>Referencia</th><th>Nombre</th><th>Vehículo</th><th>Compat.</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php if (empty($piezas)): ?>
          <tr><td colspan="7" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay piezas.</td></tr>
        <?php endif; ?>
        <?php foreach ($piezas as $p): ?>
        <tr>
          <td>
            <img src="<?= htmlspecialchars($p['imagen'] ?: 'https://via.placeholder.com/44?text=?') ?>"
                 class="pieza-thumb" alt=""
                 onerror="this.src='https://via.placeholder.com/44?text=?'">
          </td>
          <td><span style="background:rgba(164,4,46,.15);color:rgb(164,4,46);padding:3px 8px;border-radius:6px;font-size:.75rem;font-family:monospace;"><?= htmlspecialchars($p['referencia']) ?></span></td>
          <td style="color:#fff;font-weight:500;max-width:200px;"><?= htmlspecialchars($p['nombre']) ?></td>
          <td style="color:rgba(255,255,255,.55);font-size:.78rem;max-width:180px;">
            <?= $p['vehiculo'] ? htmlspecialchars($p['vehiculo']) : '<span style="color:rgba(255,255,255,.2);">—</span>' ?>
          </td>
          <td><span style="background:rgba(255,255,255,.07);padding:3px 8px;border-radius:20px;font-size:.78rem;"><?= $p['compat'] ?></span></td>
          <td>
            <div class="d-flex gap-2">
              <button class="btn-edit-sm"
                      onclick='editPieza(<?= htmlspecialchars(json_encode([
                        "id"          => $p["id"],
                        "referencia"  => $p["referencia"],
                        "nombre"      => $p["nombre"],
                        "descripcion" => $p["descripcion"] ?? "",
                        "imagen"      => $p["imagen"] ?? "",
                        "mot_id"      => $p["mot_id"] ?? "",
                      ])) ?>)'>
                <i class="fas fa-pen"></i> Editar
              </button>
              <form method="POST" action="index.php?page=piezas" style="display:inline;"
                    onsubmit="return confirm('¿Eliminar esta pieza?')">
                <input type="hidden" name="action" value="delete_pieza">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Paginación -->
  <?php if ($totalPages > 1): ?>
  <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;gap:6px;justify-content:flex-end;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="index.php?page=piezas&p=<?= $i ?>&q=<?= urlencode($search) ?>"
         style="padding:5px 11px;border-radius:6px;font-size:.8rem;text-decoration:none;
                <?= $i===$page_n ? 'background:rgb(164,4,46);color:#fff;' : 'background:rgba(255,255,255,.07);color:rgba(255,255,255,.6);' ?>">
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<!-- Modal Añadir / Editar pieza -->
<div class="modal fade" id="modalPieza" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="index.php?page=piezas">
        <input type="hidden" name="action" value="save_pieza">
        <input type="hidden" name="id" id="pieza_id" value="">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="modalTitle"><i class="fas fa-cog me-2" style="color:rgb(164,4,46);"></i>Nueva pieza</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label-admin">Referencia *</label>
              <input type="text" name="referencia" id="p_ref" class="form-control-admin form-control" placeholder="5Q0 407 151 A" required>
            </div>
            <div class="col-md-8">
              <label class="form-label-admin">Nombre *</label>
              <input type="text" name="nombre" id="p_nom" class="form-control-admin form-control" placeholder="Nombre de la pieza" required>
            </div>
            <div class="col-12">
              <label class="form-label-admin">Descripción</label>
              <textarea name="descripcion" id="p_desc" class="form-control-admin form-control" rows="3" placeholder="Descripción técnica de la pieza..."></textarea>
            </div>
            <div class="col-12">
              <label class="form-label-admin">URL de imagen</label>
              <input type="url" name="imagen" id="p_img" class="form-control-admin form-control" placeholder="https://...">
            </div>
            <div class="col-12">
              <label class="form-label-admin">Vehículo / Motorización <span style="color:rgba(255,255,255,.3);font-weight:400;">(opcional)</span></label>
              <select name="motorizacion_id" id="p_mot" class="form-select form-select-admin">
                <option value="">— Sin vehículo —</option>
                <?php foreach ($motorizaciones as $m): ?>
                  <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['label']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i>Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function resetForm() {
  document.getElementById('pieza_id').value = '';
  document.getElementById('p_ref').value    = '';
  document.getElementById('p_nom').value    = '';
  document.getElementById('p_desc').value   = '';
  document.getElementById('p_img').value    = '';
  document.getElementById('p_mot').value    = '';
  document.getElementById('modalTitle').innerHTML = '<i class="fas fa-cog me-2" style="color:rgb(164,4,46);"></i>Nueva pieza';
}
function editPieza(data) {
  document.getElementById('pieza_id').value = data.id;
  document.getElementById('p_ref').value    = data.referencia;
  document.getElementById('p_nom').value    = data.nombre;
  document.getElementById('p_desc').value   = data.descripcion;
  document.getElementById('p_img').value    = data.imagen;
  document.getElementById('p_mot').value    = data.mot_id || '';
  document.getElementById('modalTitle').innerHTML = '<i class="fas fa-pen me-2" style="color:#ffc107;"></i>Editar pieza';
  new bootstrap.Modal(document.getElementById('modalPieza')).show();
}
</script>
