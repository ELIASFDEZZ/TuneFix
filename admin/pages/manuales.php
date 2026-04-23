<?php
$search = trim($_GET['q'] ?? '');

$sql = "SELECT m.id, m.titulo, m.fuente, m.archivo_url,
               p.nombre AS pieza_nombre,
               CONCAT(ma.nombre, ' ', mo.nombre, ' · ', mz.nombre) AS vehiculo
        FROM manual m
        LEFT JOIN pieza p ON p.id = m.pieza_id
        LEFT JOIN motorizacion mz ON mz.id = m.motorizacion_id
        LEFT JOIN modelo mo ON mo.id = mz.modelo_id
        LEFT JOIN marca ma ON ma.id = mo.marca_id
        WHERE 1=1";
$params = [];
if ($search !== '') {
    $sql .= " AND m.titulo LIKE ?";
    $params[] = '%'.$search.'%';
}
$sql .= " ORDER BY m.id DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$manuales = $stmt->fetchAll();

$ok = $_GET['ok'] ?? '';
?>

<?php if ($ok === 'del'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Manual eliminado.</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-file-pdf"></i> Manuales (<?= count($manuales) ?>)</div>
    <form method="GET" action="index.php" class="d-flex gap-2">
      <input type="hidden" name="page" value="manuales">
      <input type="text" name="q" class="search-box" placeholder="Buscar manual..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn-red"><i class="fas fa-search"></i></button>
      <?php if ($search): ?><a href="index.php?page=manuales" class="btn-ghost" style="text-decoration:none;">✕</a><?php endif; ?>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-admin">
      <thead>
        <tr><th>Título</th><th>Pieza</th><th>Vehículo</th><th>Fuente</th><th>PDF</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php if (empty($manuales)): ?>
          <tr><td colspan="6" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay manuales.</td></tr>
        <?php endif; ?>
        <?php foreach ($manuales as $m): ?>
        <tr>
          <td style="color:#fff;font-weight:500;max-width:250px;"><?= htmlspecialchars($m['titulo'] ?? '—') ?></td>
          <td>
            <?php if ($m['pieza_nombre']): ?>
              <span style="background:rgba(164,4,46,.12);color:rgb(164,4,46);padding:3px 8px;border-radius:6px;font-size:.75rem;"><?= htmlspecialchars($m['pieza_nombre']) ?></span>
            <?php else: ?><span style="color:rgba(255,255,255,.2);">—</span><?php endif; ?>
          </td>
          <td style="color:rgba(255,255,255,.45);font-size:.8rem;"><?= htmlspecialchars($m['vehiculo'] ?? '—') ?></td>
          <td>
            <?php if ($m['fuente']): ?>
              <span style="background:rgba(255,255,255,.07);padding:3px 8px;border-radius:20px;font-size:.75rem;color:rgba(255,255,255,.5);"><?= htmlspecialchars($m['fuente']) ?></span>
            <?php else: ?><span style="color:rgba(255,255,255,.2);">—</span><?php endif; ?>
          </td>
          <td>
            <?php if ($m['archivo_url']): ?>
              <a href="<?= htmlspecialchars($m['archivo_url']) ?>" target="_blank" rel="noopener"
                 style="color:#ff8800;font-size:.8rem;text-decoration:none;">
                <i class="fas fa-external-link-alt me-1"></i>Ver PDF
              </a>
            <?php else: ?><span style="color:rgba(255,255,255,.2);">—</span><?php endif; ?>
          </td>
          <td>
            <form method="POST" action="index.php?page=manuales" style="display:inline;"
                  onsubmit="return confirm('¿Eliminar este manual?')">
              <input type="hidden" name="action" value="delete_manual">
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i> Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
