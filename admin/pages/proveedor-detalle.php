<?php
require_once __DIR__ . '/../../models/ProveedorModel.php';

$pm  = new ProveedorModel();
$id  = (int)($_GET['id'] ?? 0);
$prov = $id > 0 ? $pm->getPorId($id) : null;

if (!$prov) {
    echo '<div class="alert-success-admin" style="background:rgba(220,53,69,.12);border-color:rgba(220,53,69,.3);color:#ff8888;">Proveedor no encontrado.</div>';
    return;
}

$docsDir = __DIR__ . '/../../uploads/proveedores/docs/';
?>

<div style="margin-bottom:16px;">
  <a href="index.php?page=proveedores" style="color:rgba(255,255,255,.45); text-decoration:none; font-size:.85rem;">
    <i class="fas fa-arrow-left me-1"></i> Volver a Proveedores
  </a>
</div>

<div class="row g-4">

  <!-- Columna izquierda: datos + documentos -->
  <div class="col-md-7">
    <div class="admin-card mb-4">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-building"></i> <?= htmlspecialchars($prov['nombre_empresa']) ?></div>
        <span class="estado-<?= $prov['estado'] ?>"><?= ucfirst($prov['estado']) ?></span>
      </div>
      <div style="padding:24px;">
        <div class="row g-3">
          <?php
          $campos = [
            'CIF / NIF'        => $prov['cif'],
            'Dirección fiscal' => $prov['direccion'],
            'Provincia'        => $prov['provincia'],
            'Teléfono'         => $prov['telefono'],
            'Sitio web'        => $prov['sitio_web'] ? '<a href="'.htmlspecialchars($prov['sitio_web']).'" target="_blank" style="color:#6ea8fe;">'.htmlspecialchars($prov['sitio_web']).'</a>' : '—',
            'Responsable'      => $prov['nombre_responsable'],
            'Email'            => '<a href="mailto:'.htmlspecialchars($prov['email']).'" style="color:#6ea8fe;">'.htmlspecialchars($prov['email']).'</a>',
            'Fecha solicitud'  => date('d/m/Y H:i', strtotime($prov['created_at'])),
          ];
          foreach ($campos as $label => $valor): ?>
            <div class="col-md-6">
              <div style="font-size:.7rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:3px;"><?= $label ?></div>
              <div style="color:rgba(255,255,255,.8); font-size:.9rem;"><?= $valor ?></div>
            </div>
          <?php endforeach; ?>
          <div class="col-12">
            <div style="font-size:.7rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Descripción</div>
            <div style="background:#0d0d1a; border-radius:8px; padding:14px; color:rgba(255,255,255,.65); font-size:.88rem; line-height:1.6;">
              <?= nl2br(htmlspecialchars($prov['descripcion'] ?? '—')) ?>
            </div>
          </div>
          <?php if ($prov['estado'] === 'rechazado' && $prov['motivo_rechazo']): ?>
            <div class="col-12">
              <div style="font-size:.7rem; color:rgba(255,100,100,.5); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Motivo de rechazo</div>
              <div style="background:rgba(220,53,69,.08); border:1px solid rgba(220,53,69,.2); border-radius:8px; padding:14px; color:#ff8888; font-size:.88rem;">
                <?= nl2br(htmlspecialchars($prov['motivo_rechazo'])) ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Documentos -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-file-alt"></i> Documentación</div>
      </div>
      <div style="padding:20px; display:flex; gap:12px; flex-wrap:wrap;">
        <?php
        $docs = [
            'CIF / Escritura' => $prov['doc_cif'],
            'IAE / Certificado' => $prov['doc_iae'],
        ];
        foreach ($docs as $nombre => $archivo): ?>
          <div style="background:#0d0d1a; border:1px solid rgba(255,255,255,.08); border-radius:10px; padding:16px; min-width:200px;">
            <div style="font-size:.75rem; color:rgba(255,255,255,.35); margin-bottom:8px;"><?= $nombre ?></div>
            <?php if ($archivo && file_exists($docsDir . $archivo)): ?>
              <?php $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION)); ?>
              <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                <div style="margin-bottom:10px;">
                  <img src="admin-doc.php?file=<?= urlencode($archivo) ?>" alt="Documento"
                       style="max-width:180px; border-radius:6px; border:1px solid rgba(255,255,255,.1);">
                </div>
              <?php else: ?>
                <div style="font-size:2rem; margin-bottom:8px; text-align:center;">📄</div>
              <?php endif; ?>
              <a href="admin-doc.php?file=<?= urlencode($archivo) ?>" target="_blank"
                 class="btn-edit-sm btn btn-sm" style="width:100%; text-align:center;">
                <i class="fas fa-download me-1"></i>Ver / Descargar
              </a>
            <?php else: ?>
              <div style="color:rgba(255,255,255,.25); font-size:.82rem; text-align:center;">No disponible</div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Columna derecha: acciones -->
  <div class="col-md-5">
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-tasks"></i> Acciones</div>
      </div>
      <div style="padding:24px;">

        <?php if ($prov['estado'] !== 'aceptado'): ?>
        <!-- Aceptar -->
        <form method="POST" action="index.php?page=proveedores" onsubmit="return confirm('¿Aceptar a este proveedor y enviarle el email de acceso?')">
          <input type="hidden" name="action" value="aceptar_proveedor">
          <input type="hidden" name="id" value="<?= $prov['id'] ?>">
          <button type="submit" class="btn w-100 mb-3"
            style="background:rgba(25,135,84,.2); border:1px solid rgba(25,135,84,.4); color:#75b798; border-radius:10px; padding:14px; font-weight:700; font-size:.95rem; transition:all .2s;">
            <i class="fas fa-check-circle me-2"></i> ✅ Aceptar proveedor
          </button>
        </form>
        <?php else: ?>
          <div style="background:rgba(25,135,84,.1); border:1px solid rgba(25,135,84,.3); color:#75b798; border-radius:10px; padding:14px; text-align:center; margin-bottom:16px; font-size:.9rem; font-weight:600;">
            <i class="fas fa-check-circle me-2"></i> Proveedor ya aceptado
          </div>
        <?php endif; ?>

        <?php if ($prov['estado'] !== 'rechazado'): ?>
        <!-- Rechazar con motivo -->
        <div style="background:#0d0d1a; border:1px solid rgba(255,255,255,.07); border-radius:10px; padding:16px;">
          <div style="font-size:.78rem; color:rgba(255,255,255,.35); margin-bottom:10px; text-transform:uppercase; letter-spacing:1px;">❌ Rechazar solicitud</div>
          <form method="POST" action="index.php?page=proveedores" onsubmit="return validarRechazo()">
            <input type="hidden" name="action" value="rechazar_proveedor">
            <input type="hidden" name="id" value="<?= $prov['id'] ?>">
            <div class="mb-3">
              <label class="form-label-admin" style="font-size:.82rem;">Motivo del rechazo <span class="text-danger">*</span></label>
              <textarea id="motivo-rechazo" name="motivo" class="form-control form-control-admin" rows="4"
                        placeholder="Indica el motivo por el que no se aprueba la solicitud. El proveedor recibirá este mensaje por email."></textarea>
            </div>
            <button type="submit" class="btn w-100"
              style="background:rgba(220,53,69,.15); border:1px solid rgba(220,53,69,.35); color:#ff6b6b; border-radius:8px; padding:10px; font-weight:700; font-size:.88rem;">
              <i class="fas fa-times-circle me-1"></i> Rechazar y notificar
            </button>
          </form>
        </div>
        <?php else: ?>
          <div style="background:rgba(220,53,69,.08); border:1px solid rgba(220,53,69,.25); color:#ff8888; border-radius:10px; padding:14px; text-align:center; font-size:.88rem;">
            <i class="fas fa-times-circle me-2"></i> Solicitud ya rechazada
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<script>
function validarRechazo() {
  const motivo = document.getElementById('motivo-rechazo').value.trim();
  if (motivo.length < 10) {
    alert('Por favor escribe un motivo de rechazo más detallado (mínimo 10 caracteres).');
    return false;
  }
  return confirm('¿Rechazar esta solicitud y enviar el email de notificación al proveedor?');
}
</script>
