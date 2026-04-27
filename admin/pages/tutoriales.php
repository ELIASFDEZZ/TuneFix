<?php
$search = trim($_GET['q'] ?? '');

$sql = "SELECT t.id, t.titulo, t.youtube_id, t.imagen,
               p.id AS pieza_id, p.nombre AS pieza_nombre,
               t.motorizacion_id,
               CONCAT(ma.nombre, ' ', mo.nombre, ' · ', mz.nombre) AS vehiculo
        FROM tutorial t
        LEFT JOIN pieza p ON p.id = t.pieza_id
        LEFT JOIN motorizacion mz ON mz.id = t.motorizacion_id
        LEFT JOIN modelo mo ON mo.id = mz.modelo_id
        LEFT JOIN marca ma ON ma.id = mo.marca_id
        WHERE 1=1";
$params = [];
if ($search !== '') {
    $sql .= " AND t.titulo LIKE ?";
    $params[] = '%'.$search.'%';
}
$sql .= " ORDER BY t.id DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$tutoriales = $stmt->fetchAll();

// For dropdowns in modal
$piezas = $pdo->query("SELECT id, nombre FROM pieza ORDER BY nombre ASC")->fetchAll();
$motorizaciones = $pdo->query(
    "SELECT mz.id, CONCAT(ma.nombre, ' ', mo.nombre, ' · ', mz.nombre) AS label
     FROM motorizacion mz
     JOIN modelo mo ON mo.id = mz.modelo_id
     JOIN marca ma ON ma.id = mo.marca_id
     ORDER BY ma.nombre, mo.nombre, mz.nombre"
)->fetchAll();

$ok = $_GET['ok'] ?? '';
?>

<?php if ($ok === 'del'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Tutorial eliminado.</div>
<?php elseif ($ok === 'saved'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Tutorial guardado correctamente.</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-play-circle"></i> Tutoriales (<?= count($tutoriales) ?>)</div>
    <div class="d-flex gap-2 align-items-center">
      <form method="GET" action="index.php" class="d-flex gap-2">
        <input type="hidden" name="page" value="tutoriales">
        <input type="text" name="q" class="search-box" placeholder="Buscar tutorial..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn-red"><i class="fas fa-search"></i></button>
        <?php if ($search): ?><a href="index.php?page=tutoriales" class="btn-ghost" style="text-decoration:none;">✕</a><?php endif; ?>
      </form>
      <button class="btn-red" onclick="abrirModal()"><i class="fas fa-plus me-1"></i> Añadir</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-admin">
      <thead>
        <tr><th>Video</th><th>Título</th><th>Pieza</th><th>Vehículo</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php if (empty($tutoriales)): ?>
          <tr><td colspan="5" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay tutoriales.</td></tr>
        <?php endif; ?>
        <?php foreach ($tutoriales as $t): ?>
        <?php
          $ytId  = $t['youtube_id'] ?? '';
          $thumb = $ytId
            ? "https://img.youtube.com/vi/{$ytId}/default.jpg"
            : ($t['imagen'] ?: 'https://via.placeholder.com/44?text=?');
        ?>
        <tr>
          <td>
            <?php if ($ytId): ?>
              <div style="position:relative;width:64px;height:36px;border-radius:6px;overflow:hidden;border:1px solid rgba(255,60,0,.2);">
                <img src="<?= htmlspecialchars($thumb) ?>" style="width:100%;height:100%;object-fit:cover;" alt=""
                     onerror="this.src='https://via.placeholder.com/64x36?text=YT'">
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                  <i class="fab fa-youtube" style="color:#ff0000;font-size:.9rem;filter:drop-shadow(0 0 3px #000);"></i>
                </div>
              </div>
            <?php else: ?>
              <img src="<?= htmlspecialchars($t['imagen'] ?: 'https://via.placeholder.com/44?text=?') ?>"
                   class="pieza-thumb" alt=""
                   onerror="this.src='https://via.placeholder.com/44?text=?'">
            <?php endif; ?>
          </td>
          <td style="color:#fff;font-weight:500;max-width:260px;"><?= htmlspecialchars($t['titulo']) ?></td>
          <td>
            <?php if ($t['pieza_nombre']): ?>
              <span style="background:rgba(164,4,46,.12);color:rgb(164,4,46);padding:3px 8px;border-radius:6px;font-size:.75rem;"><?= htmlspecialchars($t['pieza_nombre']) ?></span>
            <?php else: ?><span style="color:rgba(255,255,255,.2);">—</span><?php endif; ?>
          </td>
          <td style="color:rgba(255,255,255,.45);font-size:.8rem;"><?= htmlspecialchars($t['vehiculo'] ?? '—') ?></td>
          <td>
            <div class="d-flex gap-1">
              <button class="btn-edit-sm" onclick='abrirModal(<?= htmlspecialchars(json_encode([
                "id"             => $t["id"],
                "titulo"         => $t["titulo"],
                "youtube_id"     => $t["youtube_id"] ?? "",
                "imagen"         => $t["imagen"] ?? "",
                "pieza_id"       => $t["pieza_id"] ?? "",
                "motorizacion_id"=> $t["motorizacion_id"] ?? "",
              ])) ?>)'>
                <i class="fas fa-edit"></i> Editar
              </button>
              <form method="POST" action="index.php?page=tutoriales" style="display:inline;"
                    onsubmit="return confirm('¿Eliminar este tutorial?')">
                <input type="hidden" name="action" value="delete_tutorial">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i> Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ══ MODAL ADD/EDIT ══════════════════════════════════════════════ -->
<div class="modal fade" id="modalTutorial" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitulo" style="color:#fff;font-weight:700;"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="index.php?page=tutoriales">
        <input type="hidden" name="action" value="save_tutorial">
        <input type="hidden" name="id" id="fId">
        <div class="modal-body" style="display:grid;grid-template-columns:1fr 1fr;gap:16px 20px;">

          <!-- Título -->
          <div style="grid-column:1/-1;">
            <label class="form-label-admin">Título *</label>
            <input type="text" name="titulo" id="fTitulo" class="form-control form-control-admin" required placeholder="Ej: Cómo cambiar el filtro de aceite">
          </div>

          <!-- YouTube: pegar iframe completo o URL -->
          <div style="grid-column:1/-1;">
            <label class="form-label-admin">
              <i class="fab fa-youtube me-1" style="color:#ff0000;"></i>
              Código iframe de YouTube <span style="color:rgba(255,255,255,.3);font-weight:400;">(pega el &lt;iframe&gt; que te da YouTube → Compartir → Insertar)</span>
            </label>
            <div style="display:flex;gap:8px;align-items:flex-start;">
              <textarea id="fYtUrl" class="form-control form-control-admin" rows="3"
                style="resize:vertical;font-size:.8rem;font-family:monospace;"
                placeholder='<iframe width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID?si=..." ...></iframe>&#10;&#10;También puedes pegar solo la URL o el ID directamente.'></textarea>
              <button type="button" class="btn-red" onclick="extraerYtId()" style="white-space:nowrap;padding:8px 14px;flex-shrink:0;">
                <i class="fas fa-magic me-1"></i>Extraer ID
              </button>
            </div>
            <input type="hidden" name="youtube_id" id="fYtId">
            <div id="ytPreview" style="margin-top:10px;display:none;"></div>
          </div>

          <!-- Imagen URL (fallback) -->
          <div style="grid-column:1/-1;">
            <label class="form-label-admin">URL de imagen (opcional, solo si no hay YouTube)</label>
            <input type="url" name="imagen" id="fImagen" class="form-control form-control-admin"
                   placeholder="https://...">
          </div>

          <!-- Pieza -->
          <div>
            <label class="form-label-admin">Pieza</label>
            <select name="pieza_id" id="fPieza" class="form-select form-select-admin">
              <option value="">— Sin pieza —</option>
              <?php foreach ($piezas as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Motorización -->
          <div>
            <label class="form-label-admin">Vehículo / Motorización</label>
            <select name="motorizacion_id" id="fMot" class="form-select form-select-admin">
              <option value="">— Sin vehículo —</option>
              <?php foreach ($motorizaciones as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let bsModal = null;

function abrirModal(data) {
  if (!bsModal) {
    bsModal = new bootstrap.Modal(document.getElementById('modalTutorial'));
  }

  const editar = data && data.id;
  document.getElementById('modalTitulo').textContent = editar ? 'Editar tutorial' : 'Añadir tutorial';
  document.getElementById('fId').value      = editar ? data.id      : '';
  document.getElementById('fTitulo').value  = editar ? data.titulo  : '';
  document.getElementById('fImagen').value  = editar ? (data.imagen || '') : '';

  const ytId = editar ? (data.youtube_id || '') : '';
  document.getElementById('fYtId').value  = ytId;
  document.getElementById('fYtUrl').value = ytId ? `https://www.youtube.com/watch?v=${ytId}` : '';
  mostrarPreview(ytId);

  document.getElementById('fPieza').value = editar ? (data.pieza_id || '') : '';
  document.getElementById('fMot').value   = editar ? (data.motorizacion_id || '') : '';

  bsModal.show();
}

function extraerYtId() {
  const raw = document.getElementById('fYtUrl').value.trim();
  let id = '';

  // 1. Código iframe completo: buscar src="...embed/ID..."
  const iframeSrc = raw.match(/src=["']([^"']+)["']/i);
  if (iframeSrc) {
    const srcUrl = iframeSrc[1];
    const em = srcUrl.match(/\/embed\/([a-zA-Z0-9_-]{6,20})/);
    if (em) { id = em[1]; }
  }

  if (!id) {
    // 2. URL normal youtube.com/watch?v=ID  o  youtu.be/ID  o  /embed/ID  o  /shorts/ID
    try {
      const url = new URL(raw);
      if (url.hostname.includes('youtu.be')) {
        id = url.pathname.slice(1).split('?')[0];
      } else {
        id = url.searchParams.get('v') || '';
        if (!id) {
          const m = url.pathname.match(/\/(embed|shorts|v)\/([a-zA-Z0-9_-]{6,20})/);
          if (m) id = m[2];
        }
      }
    } catch(_) {
      // 3. ID directo (solo caracteres válidos de YouTube)
      const plain = raw.replace(/[^a-zA-Z0-9_-]/g, '');
      if (plain.length >= 6 && plain.length <= 20) id = plain;
    }
  }

  document.getElementById('fYtId').value = id;
  mostrarPreview(id);
}

function mostrarPreview(id) {
  const box = document.getElementById('ytPreview');
  if (!id) { box.style.display = 'none'; box.innerHTML = ''; return; }
  box.style.display = 'block';
  box.innerHTML = `
    <div style="display:flex;align-items:center;gap:12px;background:rgba(255,0,0,.07);border:1px solid rgba(255,0,0,.2);border-radius:8px;padding:8px 12px;">
      <img src="https://img.youtube.com/vi/${id}/default.jpg"
           style="width:80px;height:45px;object-fit:cover;border-radius:4px;"
           onerror="this.style.opacity='.3'">
      <div>
        <div style="color:rgba(255,255,255,.6);font-size:.75rem;">ID extraído:</div>
        <code style="color:#ff6b6b;font-size:.85rem;">${id}</code>
        <a href="https://www.youtube.com/watch?v=${id}" target="_blank"
           style="display:block;font-size:.72rem;color:rgba(255,255,255,.35);margin-top:2px;">
          <i class="fab fa-youtube me-1" style="color:#ff0000;"></i>Abrir en YouTube
        </a>
      </div>
    </div>`;
}

// Auto-extract on paste or blur
document.getElementById('fYtUrl').addEventListener('blur', extraerYtId);
document.getElementById('fYtUrl').addEventListener('paste', () => setTimeout(extraerYtId, 50));
</script>
