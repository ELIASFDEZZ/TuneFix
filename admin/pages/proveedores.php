<?php
require_once __DIR__ . '/../../models/ProveedorModel.php';

$pm      = new ProveedorModel();
$filtro  = $_GET['estado'] ?? '';
$lista   = $pm->getTodos($filtro);
$conteos = $pm->contarPorEstado();
$total   = array_sum($conteos);

$okMsg = match ($_GET['ok'] ?? '') {
    'aceptado'  => 'Proveedor aceptado y notificado por email.',
    'rechazado' => 'Solicitud rechazada y notificada por email.',
    default     => null,
};
?>

<?php if ($okMsg): ?>
  <div class="alert-success-admin"><?= $okMsg ?></div>
<?php endif; ?>

<!-- Filtros de estado -->
<div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
  <?php
  $filtros = [
    ''           => ['Todos',      $total,              '#e0e0e0'],
    'pendiente'  => ['Pendientes', $conteos['pendiente'], '#ffc107'],
    'aceptado'   => ['Aceptados',  $conteos['aceptado'],  '#75b798'],
    'rechazado'  => ['Rechazados', $conteos['rechazado'], '#ff6b6b'],
  ];
  foreach ($filtros as $val => [$label, $count, $color]):
  ?>
    <a href="index.php?page=proveedores<?= $val ? '&estado='.$val : '' ?>"
       style="background:<?= $filtro===$val ? 'rgba(164,4,46,.2)' : 'rgba(255,255,255,.05)' ?>; border:1px solid <?= $filtro===$val ? 'rgba(164,4,46,.4)' : 'rgba(255,255,255,.08)' ?>; color:<?= $filtro===$val ? '#fff' : 'rgba(255,255,255,.45)' ?>; padding:6px 14px; border-radius:20px; text-decoration:none; font-size:.82rem; display:inline-flex; align-items:center; gap:6px;">
      <?= $label ?>
      <span style="background:rgba(255,255,255,.1); color:<?= $color ?>; padding:1px 7px; border-radius:10px; font-size:.72rem; font-weight:700;"><?= $count ?></span>
    </a>
  <?php endforeach; ?>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-store"></i> Solicitudes de Proveedor</div>
  </div>

  <?php if (empty($lista)): ?>
    <div style="padding:40px; text-align:center; color:rgba(255,255,255,.3);">
      <i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:12px;"></i>
      No hay solicitudes<?= $filtro ? ' en este estado' : '' ?>.
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-admin">
        <thead><tr>
          <th>Empresa</th>
          <th>CIF</th>
          <th>Responsable</th>
          <th>Email</th>
          <th>Fecha solicitud</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr></thead>
        <tbody>
          <?php foreach ($lista as $prov): ?>
          <tr>
            <td style="color:#fff; font-weight:600;"><?= htmlspecialchars($prov['nombre_empresa']) ?></td>
            <td><code style="color:#ffc107; font-size:.8rem;"><?= htmlspecialchars($prov['cif']) ?></code></td>
            <td><?= htmlspecialchars($prov['nombre_responsable']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($prov['email']) ?>" style="color:rgba(255,255,255,.55); text-decoration:none; font-size:.85rem;"><?= htmlspecialchars($prov['email']) ?></a></td>
            <td style="color:rgba(255,255,255,.4); font-size:.82rem;"><?= date('d/m/Y H:i', strtotime($prov['created_at'])) ?></td>
            <td>
              <span class="estado-<?= $prov['estado'] ?>">
                <?= ucfirst($prov['estado']) ?>
              </span>
            </td>
            <td>
              <a href="index.php?page=proveedor-detalle&id=<?= $prov['id'] ?>"
                 class="btn-edit-sm btn btn-sm">
                <i class="fas fa-eye me-1"></i>Ver detalle
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
