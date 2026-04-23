<?php
$search  = trim($_GET['q'] ?? '');
$rolFilt = $_GET['rol'] ?? '';

$sql    = "SELECT u.id, u.nombre, u.email, u.rol,
                  (SELECT COUNT(*) FROM usuario_motorizacion um WHERE um.usuario_id = u.id) AS coches
           FROM usuario u WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (u.nombre LIKE ? OR u.email LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like; $params[] = $like;
}
if (in_array($rolFilt, ['principiante','entusiasta','profesional'], true)) {
    $sql .= " AND u.rol = ?";
    $params[] = $rolFilt;
}
$sql .= " ORDER BY u.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();

$ok = $_GET['ok'] ?? '';
?>

<?php if ($ok): ?>
  <div class="alert-success-admin">
    <i class="fas fa-check-circle me-2"></i>
    <?= $ok === 'rol' ? 'Rol actualizado correctamente.' : 'Usuario eliminado.' ?>
  </div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-users"></i> Usuarios (<?= count($usuarios) ?>)</div>
    <!-- Filtros -->
    <form method="GET" action="index.php" class="d-flex gap-2 align-items-center">
      <input type="hidden" name="page" value="usuarios">
      <input type="text" name="q" class="search-box" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">
      <select name="rol" class="form-select-admin" style="padding:7px 10px;font-size:.82rem;width:auto;" onchange="this.form.submit()">
        <option value="">Todos los roles</option>
        <option value="principiante" <?= $rolFilt==='principiante'?'selected':'' ?>>Principiante</option>
        <option value="entusiasta"   <?= $rolFilt==='entusiasta'  ?'selected':'' ?>>Entusiasta</option>
        <option value="profesional"  <?= $rolFilt==='profesional' ?'selected':'' ?>>Profesional</option>
      </select>
      <button type="submit" class="btn-red"><i class="fas fa-search"></i></button>
      <?php if ($search || $rolFilt): ?>
        <a href="index.php?page=usuarios" class="btn-ghost" style="text-decoration:none;">Limpiar</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-admin">
      <thead>
        <tr>
          <th>#</th>
          <th>Usuario</th>
          <th>Email</th>
          <th>Rol</th>
          <th>Coches</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($usuarios)): ?>
          <tr><td colspan="6" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay usuarios.</td></tr>
        <?php endif; ?>
        <?php foreach ($usuarios as $u): ?>
        <tr>
          <td style="color:rgba(255,255,255,.3);font-size:.8rem;"><?= $u['id'] ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:34px;height:34px;background:rgba(164,4,46,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;color:rgb(164,4,46);flex-shrink:0;">
                <?= strtoupper(mb_substr($u['nombre'],0,1)) ?>
              </div>
              <span style="color:#fff;"><?= htmlspecialchars($u['nombre']) ?></span>
            </div>
          </td>
          <td style="color:rgba(255,255,255,.5);"><?= htmlspecialchars($u['email']) ?></td>
          <td>
            <!-- Cambiar rol inline -->
            <form method="POST" action="index.php?page=usuarios" style="display:inline;">
              <input type="hidden" name="action" value="update_rol">
              <input type="hidden" name="id" value="<?= $u['id'] ?>">
              <div style="display:flex;gap:6px;align-items:center;">
                <select name="rol" class="form-select-admin" style="padding:4px 8px;font-size:.78rem;width:auto;" onchange="this.form.submit()">
                  <option value="principiante" <?= $u['rol']==='principiante'?'selected':'' ?>>Principiante</option>
                  <option value="entusiasta"   <?= $u['rol']==='entusiasta'  ?'selected':'' ?>>Entusiasta</option>
                  <option value="profesional"  <?= $u['rol']==='profesional' ?'selected':'' ?>>Profesional</option>
                </select>
              </div>
            </form>
          </td>
          <td>
            <span style="background:rgba(255,255,255,.07);padding:3px 10px;border-radius:20px;font-size:.78rem;">
              <i class="fas fa-car me-1" style="color:rgba(255,255,255,.3);"></i><?= $u['coches'] ?>
            </span>
          </td>
          <td>
            <form method="POST" action="index.php?page=usuarios" style="display:inline;"
                  onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars(addslashes($u['nombre'])) ?>? Esta acción no se puede deshacer.')">
              <input type="hidden" name="action" value="delete_usuario">
              <input type="hidden" name="id" value="<?= $u['id'] ?>">
              <button type="submit" class="btn-danger-sm">
                <i class="fas fa-trash"></i> Eliminar
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
