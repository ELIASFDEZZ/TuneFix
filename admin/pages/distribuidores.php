<?php
$tab = $_GET['tab'] ?? 'tiendas';
if (!in_array($tab, ['tiendas', 'links'], true)) $tab = 'tiendas';
$ok  = $_GET['ok'] ?? '';

// ── Datos ──────────────────────────────────────────────────────────────────
$distribuidores = $pdo->query(
    "SELECT id, nombre, url_base FROM distribuidor ORDER BY nombre ASC"
)->fetchAll();

$links = $pdo->query(
    "SELECT dp.id, dp.nombre, dp.url_directa,
            d.id AS distribuidor_id, d.nombre AS distribuidor_nombre,
            p.id AS pieza_id,       p.nombre AS pieza_nombre, p.referencia
     FROM distribuidor_pieza dp
     JOIN distribuidor d ON d.id = dp.distribuidor_id
     JOIN pieza        p ON p.id = dp.pieza_id
     ORDER BY d.nombre ASC, p.nombre ASC"
)->fetchAll();

$piezas = $pdo->query(
    "SELECT id, referencia, nombre FROM pieza ORDER BY nombre ASC"
)->fetchAll();
?>

<?php if ($ok === 'saved'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Guardado correctamente.</div>
<?php elseif ($ok === 'del'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Eliminado correctamente.</div>
<?php endif; ?>

<!-- ── Pestañas ─────────────────────────────────────────────────────────── -->
<div style="display:flex;gap:6px;margin-bottom:20px;">
  <?php foreach ([
    'tiendas' => ['fa-truck', 'Tiendas',        count($distribuidores)],
    'links'   => ['fa-link',  'Links a piezas', count($links)],
  ] as $key => [$icon, $label, $cnt]): ?>
    <a href="index.php?page=distribuidores&tab=<?= $key ?>"
       style="display:flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;
              text-decoration:none;font-size:.85rem;font-weight:600;transition:all .2s;
              <?= $tab === $key
                ? 'background:rgb(164,4,46);color:#fff;'
                : 'background:#f3f4f6;color:#6b7280;border:1px solid #e4e6ea;' ?>">
      <i class="fas <?= $icon ?>"></i> <?= $label ?>
      <span style="background:rgba(0,0,0,.1);padding:1px 7px;border-radius:20px;font-size:.75rem;"><?= $cnt ?></span>
    </a>
  <?php endforeach; ?>
</div>

<!-- ══════════════════════════════════════════════════════════════════════ -->
<!-- TAB: TIENDAS                                                          -->
<!-- ══════════════════════════════════════════════════════════════════════ -->
<?php if ($tab === 'tiendas'): ?>
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-truck"></i> Tiendas / Distribuidores (<?= count($distribuidores) ?>)</div>
    <button class="btn-red" onclick="abrirModal('tienda')"><i class="fas fa-plus me-1"></i> Añadir tienda</button>
  </div>
  <div class="table-responsive">
    <table class="table table-admin">
      <thead><tr><th>#</th><th>Nombre</th><th>URL base</th><th>Links activos</th><th>Acciones</th></tr></thead>
      <tbody>
        <?php if (empty($distribuidores)): ?>
          <tr><td colspan="5" class="text-center" style="color:#9ca3af;padding:30px;">No hay distribuidores.</td></tr>
        <?php endif; ?>
        <?php foreach ($distribuidores as $d): ?>
        <?php $linksCount = count(array_filter($links, fn($l) => $l['distribuidor_id'] == $d['id'])); ?>
        <tr>
          <td style="color:#9ca3af;width:50px;"><?= $d['id'] ?></td>
          <td style="color:#111827;font-weight:500;"><?= htmlspecialchars($d['nombre']) ?></td>
          <td>
            <?php if ($d['url_base']): ?>
              <a href="<?= htmlspecialchars($d['url_base']) ?>" target="_blank" rel="noopener"
                 style="color:rgb(164,4,46);font-size:.82rem;text-decoration:none;word-break:break-all;">
                <i class="fas fa-external-link-alt me-1"></i><?= htmlspecialchars($d['url_base']) ?>
              </a>
            <?php else: ?><span style="color:#9ca3af;">—</span><?php endif; ?>
          </td>
          <td>
            <span style="background:#f3f4f6;padding:3px 10px;border-radius:20px;font-size:.78rem;color:#374151;border:1px solid #e4e6ea;">
              <?= $linksCount ?> <?= $linksCount === 1 ? 'link' : 'links' ?>
            </span>
          </td>
          <td style="display:flex;gap:6px;">
            <button class="btn-ghost" style="font-size:.75rem;padding:4px 10px;"
              onclick='abrirModal("tienda", <?= json_encode([
                "id"       => $d["id"],
                "nombre"   => $d["nombre"],
                "url_base" => $d["url_base"] ?? "",
              ]) ?>)'>
              <i class="fas fa-edit"></i> Editar
            </button>
            <form method="POST" action="index.php?page=distribuidores&tab=tiendas" style="display:inline;"
                  onsubmit="return confirm('¿Eliminar esta tienda? Se eliminarán también todos sus links.')">
              <input type="hidden" name="action" value="delete_distribuidor">
              <input type="hidden" name="id" value="<?= $d['id'] ?>">
              <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal tienda -->
<div id="modalTienda" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;
     align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border:1px solid #e4e6ea;border-radius:14px;box-shadow:0 20px 50px rgba(0,0,0,.12);
              width:100%;max-width:480px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;background:#fafafa;border-radius:14px 14px 0 0;">
      <h3 id="tituloTienda" style="color:#111827;font-size:1rem;font-weight:700;margin:0;">Añadir tienda</h3>
      <button onclick="cerrarModal('tienda')" style="background:none;border:none;color:#6b7280;font-size:1.4rem;cursor:pointer;">&times;</button>
    </div>
    <form method="POST" action="index.php?page=distribuidores&tab=tiendas" style="padding:20px;">
      <input type="hidden" name="action" value="save_distribuidor">
      <input type="hidden" name="id" id="tId" value="0">
      <div style="margin-bottom:14px;">
        <label class="form-label-admin">Nombre <span style="color:rgb(164,4,46);">*</span></label>
        <input type="text" name="nombre" id="tNombre" required maxlength="100"
               placeholder="Ej: Autodoc" class="form-control-admin form-control">
      </div>
      <div style="margin-bottom:20px;">
        <label class="form-label-admin">URL base <span style="color:#9ca3af;font-weight:400;">(opcional)</span></label>
        <input type="url" name="url_base" id="tUrl" maxlength="255"
               placeholder="https://www.autodoc.es/" class="form-control-admin form-control">
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:14px;border-top:1px solid #f0f0f0;">
        <button type="button" onclick="cerrarModal('tienda')" class="btn-ghost">Cancelar</button>
        <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════ -->
<!-- TAB: LINKS A PIEZAS                                                   -->
<!-- ══════════════════════════════════════════════════════════════════════ -->
<?php elseif ($tab === 'links'): ?>
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-link"></i> Links a piezas (<?= count($links) ?>)</div>
    <button class="btn-red" onclick="abrirModal('link')"><i class="fas fa-plus me-1"></i> Añadir link</button>
  </div>
  <div class="table-responsive">
    <table class="table table-admin">
      <thead>
        <tr><th>Tienda</th><th>Pieza</th><th>Nombre en tienda</th><th>URL directa</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php if (empty($links)): ?>
          <tr><td colspan="5" class="text-center" style="color:#9ca3af;padding:30px;">No hay links.</td></tr>
        <?php endif; ?>
        <?php foreach ($links as $l): ?>
        <tr>
          <td>
            <span style="background:#f3f4f6;padding:3px 10px;border-radius:20px;
                         font-size:.78rem;color:#374151;border:1px solid #e4e6ea;">
              <i class="fas fa-truck me-1" style="color:rgb(164,4,46);font-size:.7rem;"></i>
              <?= htmlspecialchars($l['distribuidor_nombre']) ?>
            </span>
          </td>
          <td>
            <div style="font-size:.82rem;">
              <span style="background:rgba(164,4,46,.12);color:rgb(164,4,46);padding:2px 7px;
                           border-radius:5px;font-family:monospace;font-size:.75rem;">
                <?= htmlspecialchars($l['referencia']) ?>
              </span>
              <div style="color:#374151;margin-top:3px;"><?= htmlspecialchars($l['pieza_nombre']) ?></div>
            </div>
          </td>
          <td style="color:#6b7280;font-size:.85rem;max-width:180px;">
            <?= $l['nombre'] ? htmlspecialchars($l['nombre']) : '<span style="color:#9ca3af;">—</span>' ?>
          </td>
          <td>
            <?php if ($l['url_directa']): ?>
              <a href="<?= htmlspecialchars($l['url_directa']) ?>" target="_blank" rel="noopener"
                 style="color:rgb(164,4,46);font-size:.8rem;text-decoration:none;">
                <i class="fas fa-external-link-alt me-1"></i>Ver enlace
              </a>
            <?php else: ?><span style="color:#9ca3af;">—</span><?php endif; ?>
          </td>
          <td style="display:flex;gap:6px;">
            <button class="btn-ghost" style="font-size:.75rem;padding:4px 10px;"
              onclick='abrirModal("link", <?= json_encode([
                "id"             => $l["id"],
                "distribuidor_id"=> $l["distribuidor_id"],
                "pieza_id"       => $l["pieza_id"],
                "nombre"         => $l["nombre"]      ?? "",
                "url_directa"    => $l["url_directa"] ?? "",
              ]) ?>)'>
              <i class="fas fa-edit"></i> Editar
            </button>
            <form method="POST" action="index.php?page=distribuidores&tab=links" style="display:inline;"
                  onsubmit="return confirm('¿Eliminar este link?')">
              <input type="hidden" name="action" value="delete_distribuidor_pieza">
              <input type="hidden" name="id" value="<?= $l['id'] ?>">
              <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal link -->
<div id="modalLink" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;
     align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border:1px solid #e4e6ea;border-radius:14px;box-shadow:0 20px 50px rgba(0,0,0,.12);
              width:100%;max-width:560px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;background:#fafafa;border-radius:14px 14px 0 0;">
      <h3 id="tituloLink" style="color:#111827;font-size:1rem;font-weight:700;margin:0;">Añadir link a pieza</h3>
      <button onclick="cerrarModal('link')" style="background:none;border:none;color:#6b7280;font-size:1.4rem;cursor:pointer;">&times;</button>
    </div>
    <form method="POST" action="index.php?page=distribuidores&tab=links" style="padding:20px;">
      <input type="hidden" name="action" value="save_distribuidor_pieza">
      <input type="hidden" name="id" id="lId" value="0">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div>
          <label class="form-label-admin">Tienda <span style="color:rgb(164,4,46);">*</span></label>
          <select name="distribuidor_id" id="lDistribuidor" required class="form-select form-select-admin">
            <option value="">— Selecciona —</option>
            <?php foreach ($distribuidores as $d): ?>
              <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label-admin">Pieza <span style="color:rgb(164,4,46);">*</span></label>
          <select name="pieza_id" id="lPieza" required class="form-select form-select-admin">
            <option value="">— Selecciona —</option>
            <?php foreach ($piezas as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['referencia'] . ' – ' . $p['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div style="margin-bottom:14px;">
        <label class="form-label-admin">Nombre en la tienda <span style="color:#9ca3af;font-weight:400;">(opcional)</span></label>
        <input type="text" name="nombre" id="lNombre" maxlength="150"
               placeholder="Ej: Kit distribución INA para 1.6 TDI" class="form-control-admin form-control">
      </div>
      <div style="margin-bottom:4px;">
        <label class="form-label-admin">URL directa al producto <span style="color:#9ca3af;font-weight:400;">(opcional)</span></label>
        <input type="url" name="url_directa" id="lUrl" maxlength="500"
               placeholder="https://www.autodoc.es/pieza/..." class="form-control-admin form-control">
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:14px;border-top:1px solid #f0f0f0;margin-top:14px;">
        <button type="button" onclick="cerrarModal('link')" class="btn-ghost">Cancelar</button>
        <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
function abrirModal(tipo, data = null) {
  if (tipo === 'tienda') {
    document.getElementById('tId').value     = data?.id       ?? 0;
    document.getElementById('tNombre').value = data?.nombre   ?? '';
    document.getElementById('tUrl').value    = data?.url_base ?? '';
    document.getElementById('tituloTienda').textContent = data ? 'Editar tienda' : 'Añadir tienda';
    mostrarModal('modalTienda');

  } else if (tipo === 'link') {
    document.getElementById('lId').value           = data?.id              ?? 0;
    document.getElementById('lDistribuidor').value = data?.distribuidor_id ?? '';
    document.getElementById('lPieza').value        = data?.pieza_id        ?? '';
    document.getElementById('lNombre').value       = data?.nombre          ?? '';
    document.getElementById('lUrl').value          = data?.url_directa     ?? '';
    document.getElementById('tituloLink').textContent = data ? 'Editar link' : 'Añadir link a pieza';
    mostrarModal('modalLink');
  }
}

function cerrarModal(tipo) {
  const ids = { tienda: 'modalTienda', link: 'modalLink' };
  document.getElementById(ids[tipo]).style.display = 'none';
}

function mostrarModal(id) {
  document.getElementById(id).style.display = 'flex';
}

['modalTienda', 'modalLink'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('click', e => { if (e.target === el) el.style.display = 'none'; });
});
</script>