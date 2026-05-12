<?php
$search = trim($_GET['q'] ?? '');

$sql = "SELECT m.id, m.titulo, m.fuente, m.archivo_url,
               p.id AS pieza_id, p.nombre AS pieza_nombre,
               m.motorizacion_id,
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
  $params[] = '%' . $search . '%';
}
$sql .= " ORDER BY m.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$manuales = $stmt->fetchAll();

$marcas = $pdo->query("SELECT id, nombre FROM marca ORDER BY nombre ASC")->fetchAll();
$piezas = $pdo->query("SELECT id, nombre FROM pieza ORDER BY nombre ASC")->fetchAll();

$ok = $_GET['ok'] ?? '';
?>

<?php if ($ok === 'del'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Manual eliminado.</div>
<?php elseif ($ok === 'saved'): ?>
  <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Manual guardado correctamente.</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><i class="fas fa-file-pdf"></i> Manuales (<?= count($manuales) ?>)</div>
    <div class="d-flex gap-2 align-items-center">
      <form method="GET" action="index.php" class="d-flex gap-2">
        <input type="hidden" name="page" value="manuales">
        <input type="text" name="q" class="search-box" placeholder="Buscar manual..."
          value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn-red"><i class="fas fa-search"></i></button>
        <?php if ($search): ?><a href="index.php?page=manuales" class="btn-ghost"
            style="text-decoration:none;">✕</a><?php endif; ?>
      </form>
      <button class="btn-red" onclick="abrirModal()"><i class="fas fa-plus me-1"></i> Añadir</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-admin">
      <thead>
        <tr>
          <th>Título</th>
          <th>Pieza</th>
          <th>Vehículo</th>
          <th>Fuente</th>
          <th>PDF</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($manuales)): ?>
          <tr>
            <td colspan="6" class="text-center" style="color:#9ca3af;padding:30px;">No hay manuales.</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($manuales as $m): ?>
          <tr>
            <td style="color:#111827;font-weight:500;max-width:250px;"><?= htmlspecialchars($m['titulo'] ?? '—') ?></td>
            <td>
              <?php if ($m['pieza_nombre']): ?>
                <span style="background:rgba(164,4,46,.12);color:rgb(164,4,46);padding:3px 8px;border-radius:6px;font-size:.75rem;"><?= htmlspecialchars($m['pieza_nombre']) ?></span>
              <?php else: ?><span style="color:#9ca3af;">—</span><?php endif; ?>
            </td>
            <td style="color:#6b7280;font-size:.8rem;"><?= htmlspecialchars($m['vehiculo'] ?? '—') ?></td>
            <td>
              <?php if ($m['fuente']): ?>
                <span style="background:#f3f4f6;padding:3px 8px;border-radius:20px;font-size:.75rem;color:#374151;border:1px solid #e4e6ea;"><?= htmlspecialchars($m['fuente']) ?></span>
              <?php else: ?><span style="color:#9ca3af;">—</span><?php endif; ?>
            </td>
            <td>
              <?php if ($m['archivo_url']): ?>
                <a href="../<?= htmlspecialchars($m['archivo_url']) ?>" target="_blank" rel="noopener"
                  style="color:rgb(164,4,46);font-size:.8rem;text-decoration:none;">
                  <i class="fas fa-file-pdf me-1"></i>Ver PDF
                </a>
              <?php else: ?><span style="color:#9ca3af;">—</span><?php endif; ?>
            </td>
            <td style="display:flex;gap:6px;flex-wrap:nowrap;">
              <button class="btn-ghost" style="font-size:.75rem;padding:4px 10px;" onclick='editarManual(<?= json_encode([
                "id" => $m["id"],
                "titulo" => $m["titulo"] ?? "",
                "fuente" => $m["fuente"] ?? "",
                "archivo_url" => $m["archivo_url"] ?? "",
              ]) ?>)'>
                <i class="fas fa-edit"></i> Editar
              </button>
              <form method="POST" action="index.php?page=manuales" style="display:inline;"
                onsubmit="return confirm('¿Eliminar este manual?')">
                <input type="hidden" name="action" value="delete_manual">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="modalManual" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;
     align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border:1px solid #e4e6ea;border-radius:14px;box-shadow:0 20px 50px rgba(0,0,0,.12);
              width:100%;max-width:680px;max-height:90vh;overflow-y:auto;">

    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f0f0f0;background:#fafafa;border-radius:14px 14px 0 0;">
      <h3 id="modalTitle" style="color:#111827;font-size:1rem;font-weight:700;margin:0;">
        <i class="fas fa-file-pdf me-2" style="color:rgb(164,4,46);"></i>Añadir manual
      </h3>
      <button onclick="cerrarModal()" style="background:none;border:none;color:#6b7280;
              font-size:1.4rem;cursor:pointer;line-height:1;">&times;</button>
    </div>

    <form method="POST" action="index.php?page=manuales" enctype="multipart/form-data" style="padding:20px;">
      <input type="hidden" name="action" value="save_manual">
      <input type="hidden" name="id" id="fId" value="0">
      <input type="hidden" name="archivo_url_actual" id="fArchivoActual" value="">

      <div style="margin-bottom:14px;">
        <label class="form-label-admin">Título <span style="color:rgb(164,4,46);">*</span></label>
        <input type="text" name="titulo" id="fTitulo" required maxlength="200"
          placeholder="Ej: Manual de cambio de aceite – Golf MkVII 2.0 TDI"
          class="form-control-admin form-control">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;">
        <div>
          <label class="form-label-admin">Marca</label>
          <select id="fMarca" onchange="cargarModelos()" class="form-select form-select-admin">
            <option value="">— Todas —</option>
            <?php foreach ($marcas as $ma): ?>
              <option value="<?= $ma['id'] ?>"><?= htmlspecialchars($ma['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label-admin">Modelo</label>
          <select id="fModelo" onchange="cargarMotorizaciones()" class="form-select form-select-admin">
            <option value="">— Selecciona marca —</option>
          </select>
        </div>
        <div>
          <label class="form-label-admin">Motorización</label>
          <select name="motorizacion_id" id="fMotorizacion" class="form-select form-select-admin">
            <option value="">— Selecciona modelo —</option>
          </select>
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label class="form-label-admin">Pieza relacionada <span style="color:#9ca3af;font-weight:400;">(opcional)</span></label>
        <select name="pieza_id" id="fPieza" class="form-select form-select-admin">
          <option value="">— Sin pieza asociada —</option>
          <?php foreach ($piezas as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="margin-bottom:14px;">
        <label class="form-label-admin">Fuente / Autor <span style="color:#9ca3af;font-weight:400;">(opcional)</span></label>
        <input type="text" name="fuente" id="fFuente" maxlength="100" placeholder="Ej: Volkswagen AG, Haynes, etc."
          class="form-control-admin form-control">
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label-admin">Archivo PDF</label>

        <div id="pdfActualWrap" style="display:none;margin-bottom:10px;padding:10px 14px;
             background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;
             align-items:center;gap:10px;">
          <i class="fas fa-file-pdf" style="color:#c2410c;font-size:1.2rem;"></i>
          <span id="pdfActualNombre" style="color:#374151;font-size:.85rem;flex:1;word-break:break-all;"></span>
          <a id="pdfActualLink" href="#" target="_blank" rel="noopener"
            style="color:#c2410c;font-size:.8rem;text-decoration:none;white-space:nowrap;">
            <i class="fas fa-external-link-alt me-1"></i>Ver
          </a>
        </div>

        <div id="dropzone" style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                    gap:8px;padding:28px;border:2px dashed #d1d5db;border-radius:10px;
                    cursor:pointer;transition:border-color .2s;background:#fafafa;">
          <i class="fas fa-cloud-upload-alt" style="font-size:1.8rem;color:#9ca3af;pointer-events:none;"></i>
          <span style="color:#6b7280;font-size:.85rem;pointer-events:none;">
            Arrastra el PDF aquí o <span style="color:rgb(164,4,46);">selecciona un archivo</span>
          </span>
          <span id="pdfFileName" style="color:#374151;font-size:.8rem;pointer-events:none;"></span>
          <input type="file" name="pdf" id="fPdf" accept="application/pdf" style="display:none;"
            onchange="mostrarNombrePdf(this)">
        </div>
        <p style="color:#9ca3af;font-size:.75rem;margin-top:6px;">
          Solo PDF. Máx. recomendado 20 MB. Si no seleccionas archivo nuevo, se conserva el actual.
        </p>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:14px;border-top:1px solid #f0f0f0;">
        <button type="button" onclick="cerrarModal()" class="btn-ghost">Cancelar</button>
        <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar manual</button>
      </div>
    </form>
  </div>
</div>

<script>
  async function cargarModelos() {
    const marcaId = document.getElementById('fMarca').value;
    const selMod = document.getElementById('fModelo');
    const selMot = document.getElementById('fMotorizacion');
    selMot.innerHTML = '<option value="">— Selecciona modelo —</option>';
    if (!marcaId) { selMod.innerHTML = '<option value="">— Selecciona marca —</option>'; return; }
    selMod.innerHTML = '<option value="">— Cargando... —</option>';
    const modelos = await fetch(`../public/ajax/get_modelos.php?marca_id=${marcaId}`).then(r => r.json());
    selMod.innerHTML = '<option value="">— Selecciona modelo —</option>';
    modelos.forEach(m => selMod.innerHTML += `<option value="${m.id}">${m.nombre}</option>`);
  }

  async function cargarMotorizaciones() {
    const modeloId = document.getElementById('fModelo').value;
    const selMot = document.getElementById('fMotorizacion');
    if (!modeloId) { selMot.innerHTML = '<option value="">— Selecciona modelo —</option>'; return; }
    selMot.innerHTML = '<option value="">— Cargando... —</option>';
    const motos = await fetch(`../public/ajax/get_motorizaciones.php?modelo_id=${modeloId}`).then(r => r.json());
    selMot.innerHTML = '<option value="">— Sin motorización —</option>';
    motos.forEach(m => selMot.innerHTML += `<option value="${m.id}">${m.nombre}</option>`);
  }

  function abrirModal() {
    resetModal();
    document.getElementById('modalTitle').innerHTML =
      '<i class="fas fa-file-pdf me-2" style="color:rgb(164,4,46);"></i>Añadir manual';
    document.getElementById('modalManual').style.display = 'flex';
  }

  function cerrarModal() {
    document.getElementById('modalManual').style.display = 'none';
  }

  function resetModal() {
    ['fId', 'fArchivoActual', 'fTitulo', 'fFuente'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('fId').value = '0';
    document.getElementById('fMarca').value = '';
    document.getElementById('fModelo').innerHTML = '<option value="">— Selecciona marca —</option>';
    document.getElementById('fMotorizacion').innerHTML = '<option value="">— Selecciona modelo —</option>';
    document.getElementById('fPieza').value = '';
    document.getElementById('pdfFileName').textContent = '';
    document.getElementById('fPdf').value = '';
    document.getElementById('pdfActualWrap').style.display = 'none';
  }

  function editarManual(data) {
    resetModal();
    document.getElementById('modalTitle').innerHTML =
      '<i class="fas fa-file-pdf me-2" style="color:rgb(164,4,46);"></i>Editar manual';
    document.getElementById('fId').value = data.id;
    document.getElementById('fTitulo').value = data.titulo;
    document.getElementById('fFuente').value = data.fuente;
    document.getElementById('fArchivoActual').value = data.archivo_url;
    if (data.archivo_url) {
      const partes = data.archivo_url.split('/');
      document.getElementById('pdfActualNombre').textContent = partes[partes.length - 1];
      document.getElementById('pdfActualLink').href = '../' + data.archivo_url;
      document.getElementById('pdfActualWrap').style.display = 'flex';
    }
    document.getElementById('modalManual').style.display = 'flex';
  }

  const dropzone = document.getElementById('dropzone');
  const inputPdf = document.getElementById('fPdf');
  dropzone.addEventListener('click', () => inputPdf.click());
  dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = 'rgb(164,4,46)'; });
  dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = '#d1d5db'; });
  dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.style.borderColor = '#d1d5db';
    const f = e.dataTransfer.files[0];
    if (f && f.type === 'application/pdf') {
      const dt = new DataTransfer(); dt.items.add(f); inputPdf.files = dt.files;
      mostrarNombrePdf(inputPdf);
    }
  });
  function mostrarNombrePdf(input) {
    document.getElementById('pdfFileName').textContent = input.files.length ? '📄 ' + input.files[0].name : '';
  }
  document.getElementById('modalManual').addEventListener('click', function (e) {
    if (e.target === this) cerrarModal();
  });
</script>