<style>
  .sp-hero {
    background: linear-gradient(rgba(0,0,0,0.72), rgba(0,0,0,0.82)),
      url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 50px;
  }
  .sp-card {
    background: #1a1a2e; border: 1px solid rgba(255,60,0,0.18);
    border-radius: 18px; padding: 40px 44px;
    max-width: 860px; margin: 0 auto;
  }
  .sp-label {
    font-size: 0.78rem; font-weight: 700; letter-spacing: 0.6px;
    color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 6px;
  }
  .sp-input {
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
    color: #fff; border-radius: 10px; padding: 11px 14px;
    font-size: 0.9rem; width: 100%; transition: border-color .2s, box-shadow .2s;
  }
  .sp-input:focus {
    outline: none; border-color: rgba(255,60,0,0.6);
    box-shadow: 0 0 0 3px rgba(164,4,46,0.15);
    background: rgba(255,255,255,0.08); color: #fff;
  }
  .sp-input::placeholder { color: rgba(255,255,255,0.25); }
  .sp-input option { background: #1a1a2e; color: #fff; }
  .sp-section-title {
    font-size: 0.7rem; font-weight: 700; letter-spacing: 1.2px;
    color: rgba(255,60,0,0.7); text-transform: uppercase;
    border-bottom: 1px solid rgba(255,60,0,0.15); padding-bottom: 8px; margin-bottom: 20px;
  }
  .btn-sp-submit {
    background: linear-gradient(45deg,#a4042e,#ff3c00); border: none; color: #fff;
    font-weight: 700; border-radius: 50px; padding: 12px 36px; font-size: 0.95rem;
    transition: all .25s; cursor: pointer;
  }
  .btn-sp-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(164,4,46,.45); }
  .btn-sp-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }
  .btn-sp-delete {
    background: rgba(220,53,69,0.12); border: 1px solid rgba(220,53,69,0.35);
    color: #ff6b6b; font-weight: 700; border-radius: 50px; padding: 12px 28px;
    font-size: 0.9rem; cursor: pointer; transition: all .25s;
  }
  .btn-sp-delete:hover { background: rgba(220,53,69,0.22); border-color: rgba(220,53,69,0.6); }
  .alert-sp-error {
    background: rgba(220,53,69,0.12); border: 1px solid rgba(220,53,69,0.35);
    color: #ff8080; border-radius: 10px; padding: 12px 18px; font-size: 0.88rem;
  }
  #img-preview { width:100%; height:160px; object-fit:cover; border-radius:10px; border:1px solid rgba(255,255,255,0.1); display:none; }
  #img-placeholder {
    width:100%; height:160px; border-radius:10px; border:1px dashed rgba(255,255,255,0.12);
    display:flex; align-items:center; justify-content:center;
    color:rgba(255,255,255,0.2); font-size:0.82rem; flex-direction:column; gap:8px;
    background:rgba(255,255,255,0.02);
  }
  .estado-group { display:flex; gap:10px; flex-wrap:wrap; }
  .estado-label {
    display:flex; align-items:center; gap:8px; cursor:pointer; padding:9px 18px;
    background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
    border-radius:50px; font-size:0.82rem; color:rgba(255,255,255,0.5);
    transition:all .2s; user-select:none;
  }
  .estado-label input[type="radio"] { display:none; }
  .estado-label:has(input:checked) { background:linear-gradient(45deg,#a4042e,#ff3c00); border-color:transparent; color:#fff; }
</style>

<!-- ══ HERO ══ -->
<section class="sp-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="mi-canal.php?tab=piezas" class="btn btn-sm"
         style="border:1px solid rgba(255,255,255,0.22);color:rgba(255,255,255,0.6);border-radius:50px;font-size:0.82rem;">
        <i class="fas fa-arrow-left me-1"></i> Mis piezas
      </a>
    </div>
    <h1 class="display-5 fw-bold mb-1">
      <i class="fas fa-edit me-2" style="color:rgba(255,60,0,0.9);"></i> Editar pieza
    </h1>
    <p class="mb-0" style="color:rgba(255,255,255,0.5);"><?= htmlspecialchars($pieza['nombre']) ?></p>
  </div>
</section>

<!-- ══ FORMULARIO ══ -->
<section style="background:#0d0d0d;flex:1;padding:48px 0 70px;">
  <div class="container">
    <div class="sp-card">

      <?php if (!empty($error)): ?>
        <div class="alert-sp-error mb-4">
          <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <!-- Formulario edición -->
      <form method="POST" action="editar-pieza.php" id="form-editar">
        <input type="hidden" name="id" value="<?= (int)$pieza['id'] ?>">
        <input type="hidden" name="_action" value="guardar">

        <div class="sp-section-title mb-3"><i class="fas fa-info-circle me-2"></i>Información básica</div>
        <div class="row g-4 mb-4">
          <div class="col-md-7">
            <label class="sp-label">Nombre <span style="color:#ff3c00;">*</span></label>
            <input type="text" name="nombre" class="sp-input"
                   value="<?= htmlspecialchars($pieza['nombre']) ?>" required>
          </div>
          <div class="col-md-5">
            <label class="sp-label">Referencia <span style="color:#ff3c00;">*</span></label>
            <input type="text" name="referencia" class="sp-input"
                   value="<?= htmlspecialchars($pieza['referencia']) ?>" required>
          </div>
          <div class="col-md-6">
            <label class="sp-label">Categoría</label>
            <select name="categoria" class="sp-input">
              <option value="">Sin categoría</option>
              <?php foreach (['Motor','Suspensión','Frenos','Transmisión','Carrocería','Interior','Eléctrico','Escape','Turbo/Compresor','Refrigeración','Dirección','Otros'] as $cat):
                $sel = ($pieza['categoria'] === $cat) ? 'selected' : '';
              ?>
                <option value="<?= $cat ?>" <?= $sel ?>><?= $cat ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="sp-label">Precio (€)</label>
            <input type="number" name="precio" class="sp-input" min="0" step="0.01"
                   value="<?= htmlspecialchars($pieza['precio'] ?? '0') ?>">
          </div>
          <div class="col-md-3">
            <label class="sp-label">Stock (uds.)</label>
            <input type="number" name="stock" class="sp-input" min="0" step="1"
                   value="<?= (int)($pieza['stock'] ?? 0) ?>">
          </div>
          <div class="col-12">
            <label class="sp-label d-block mb-2">Estado de la pieza</label>
            <div class="estado-group">
              <?php
                $estadoActual = $pieza['estado_pieza'] ?? 'nueva';
                foreach (['nueva' => '✨ Nueva', 'usada_buena' => '👍 Usada – buen estado', 'usada_desgaste' => '⚠️ Usada – con desgaste'] as $val => $lbl):
              ?>
                <label class="estado-label">
                  <input type="radio" name="estado_pieza" value="<?= $val ?>" <?= $estadoActual === $val ? 'checked' : '' ?>>
                  <?= $lbl ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="col-md-6">
            <label class="sp-label">Garantía</label>
            <input type="text" name="garantia" class="sp-input"
                   value="<?= htmlspecialchars($pieza['garantia'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="sp-label">Enlace externo (opcional)</label>
            <input type="url" name="url" class="sp-input"
                   value="<?= htmlspecialchars($pieza['url'] ?? '') ?>">
          </div>
        </div>

        <div class="sp-section-title mb-3"><i class="fas fa-image me-2"></i>Imagen de la pieza <span style="color:rgba(255,60,0,0.7);font-size:0.68rem;text-transform:none;font-weight:400;letter-spacing:0;">(pega aquí la URL de la foto)</span></div>
        <div class="row g-4 mb-4 align-items-center">
          <div class="col-md-7">
            <label class="sp-label">URL de la imagen <span style="color:#ff3c00;">*</span></label>
            <input type="url" name="imagen" id="input-imagen" class="sp-input"
                   value="<?= htmlspecialchars(!empty($pieza['imagen']) ? $pieza['imagen'] : ($pieza['url'] ?? '')) ?>"
                   oninput="previewImagen(this.value)">
            <div class="mt-2" style="color:rgba(255,255,255,0.25);font-size:0.75rem;">
              <i class="fas fa-info-circle me-1"></i>Pega aquí la URL directa de la foto de la pieza
            </div>
          </div>
          <div class="col-md-5">
            <?php $imgPreview = !empty($pieza['imagen']) ? $pieza['imagen'] : ($pieza['url'] ?? ''); ?>
            <div id="img-placeholder" <?= !empty($imgPreview) ? 'style="display:none;"' : '' ?>>
              <i class="fas fa-image fa-2x"></i><span>Vista previa</span>
            </div>
            <img id="img-preview"
                 src="<?= htmlspecialchars($imgPreview) ?>"
                 alt="Vista previa"
                 <?= !empty($imgPreview) ? 'style="display:block;"' : '' ?>
                 onerror="this.style.display='none';document.getElementById('img-placeholder').style.display='flex';">
          </div>
        </div>

        <div class="sp-section-title mb-3"><i class="fas fa-align-left me-2"></i>Descripción</div>
        <div class="mb-5">
          <textarea name="descripcion" class="sp-input" rows="4" style="resize:vertical;"><?= htmlspecialchars($pieza['descripcion'] ?? '') ?></textarea>
        </div>

        <!-- Acciones -->
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <button type="submit" class="btn-sp-submit" id="btn-guardar">
            <i class="fas fa-save me-2"></i>Guardar cambios
          </button>
          <a href="mi-canal.php?tab=piezas" style="color:rgba(255,255,255,0.35);font-size:0.85rem;text-decoration:none;">
            Cancelar
          </a>
        </div>
      </form>

      <!-- Zona de peligro: eliminar -->
      <div style="margin-top:40px;border-top:1px solid rgba(220,53,69,0.18);padding-top:28px;">
        <div class="sp-section-title" style="color:rgba(220,53,69,0.6);">
          <i class="fas fa-trash-alt me-2"></i>Zona de peligro
        </div>
        <p style="color:rgba(255,255,255,0.35);font-size:0.85rem;margin-bottom:16px;">
          Eliminar esta pieza la ocultará del catálogo permanentemente.
        </p>
        <form method="POST" action="editar-pieza.php" id="form-eliminar">
          <input type="hidden" name="id" value="<?= (int)$pieza['id'] ?>">
          <input type="hidden" name="_action" value="eliminar">
          <button type="button" class="btn-sp-delete" onclick="confirmarEliminar()">
            <i class="fas fa-trash-alt me-2"></i>Eliminar pieza
          </button>
        </form>
      </div>

    </div>
  </div>
</section>

<script>
function previewImagen(url) {
  const img = document.getElementById('img-preview');
  const ph  = document.getElementById('img-placeholder');
  if (url.trim()) { img.src = url.trim(); img.style.display = 'block'; ph.style.display = 'none'; }
  else            { img.style.display = 'none'; ph.style.display = 'flex'; }
}

function confirmarEliminar() {
  if (confirm('¿Estás seguro de que quieres eliminar esta pieza? Esta acción no se puede deshacer.')) {
    document.getElementById('form-eliminar').submit();
  }
}

document.getElementById('form-editar').addEventListener('submit', function() {
  const btn = document.getElementById('btn-guardar');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando…';
});
</script>
