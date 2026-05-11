<style>
  .sp-hero {
    background: linear-gradient(rgba(0,0,0,0.72), rgba(0,0,0,0.82)),
      url('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 50px;
  }
  .sp-card {
    background: #1a1a2e;
    border: 1px solid rgba(255,60,0,0.18);
    border-radius: 18px;
    padding: 40px 44px;
    max-width: 860px;
    margin: 0 auto;
  }
  .sp-label {
    font-size: 0.78rem; font-weight: 700; letter-spacing: 0.6px;
    color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 6px;
  }
  .sp-input {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: #fff; border-radius: 10px; padding: 11px 14px;
    font-size: 0.9rem; width: 100%; transition: border-color 0.2s, box-shadow 0.2s;
  }
  .sp-input:focus {
    outline: none;
    border-color: rgba(255,60,0,0.6);
    box-shadow: 0 0 0 3px rgba(164,4,46,0.15);
    background: rgba(255,255,255,0.08);
    color: #fff;
  }
  .sp-input::placeholder { color: rgba(255,255,255,0.25); }
  .sp-input option { background: #1a1a2e; color: #fff; }

  .sp-section-title {
    font-size: 0.7rem; font-weight: 700; letter-spacing: 1.2px;
    color: rgba(255,60,0,0.7); text-transform: uppercase;
    border-bottom: 1px solid rgba(255,60,0,0.15);
    padding-bottom: 8px; margin-bottom: 20px;
  }

  .btn-sp-submit {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border: none; color: #fff; font-weight: 700;
    border-radius: 50px; padding: 12px 36px; font-size: 0.95rem;
    transition: all 0.25s ease; cursor: pointer;
  }
  .btn-sp-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(164,4,46,0.45);
  }
  .btn-sp-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

  .alert-sp-error {
    background: rgba(220,53,69,0.12); border: 1px solid rgba(220,53,69,0.35);
    color: #ff8080; border-radius: 10px; padding: 12px 18px; font-size: 0.88rem;
  }
  .alert-sp-success {
    background: rgba(0,180,100,0.1); border: 1px solid rgba(0,180,100,0.3);
    color: #4ddb9e; border-radius: 10px; padding: 18px 22px;
  }

  /* Preview imagen */
  #img-preview {
    width: 100%; height: 160px; object-fit: cover; border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.1); display: none;
    background: rgba(255,255,255,0.03);
  }
  #img-placeholder {
    width: 100%; height: 160px; border-radius: 10px;
    border: 1px dashed rgba(255,255,255,0.12);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.2); font-size: 0.82rem; flex-direction: column; gap: 8px;
    background: rgba(255,255,255,0.02);
  }

  /* Estado pills */
  .estado-group { display: flex; gap: 10px; flex-wrap: wrap; }
  .estado-label {
    display: flex; align-items: center; gap: 8px;
    cursor: pointer; padding: 9px 18px;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 50px; font-size: 0.82rem; color: rgba(255,255,255,0.5);
    transition: all 0.2s ease; user-select: none;
  }
  .estado-label input[type="radio"] { display: none; }
  .estado-label:has(input:checked) {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border-color: transparent; color: #fff;
  }
</style>

<!-- ══ HERO ══ -->
<section class="sp-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="mi-canal.php" class="btn btn-sm"
         style="border:1px solid rgba(255,255,255,0.22);color:rgba(255,255,255,0.6);border-radius:50px;font-size:0.82rem;">
        <i class="fas fa-arrow-left me-1"></i> Mi Canal
      </a>
    </div>
    <h1 class="display-5 fw-bold mb-1">
      <i class="fas fa-cog me-2" style="color:rgba(255,60,0,0.9);"></i> Subir pieza
    </h1>
    <p class="mb-0" style="color:rgba(255,255,255,0.5);">Añade una pieza al catálogo de TuneFix para la comunidad.</p>
  </div>
</section>

<!-- ══ FORMULARIO ══ -->
<section style="background:#0d0d0d;flex:1;padding:48px 0 70px;">
  <div class="container">
    <div class="sp-card">

      <!-- Éxito -->
      <?php if ($exito): ?>
        <div class="alert-sp-success mb-4">
          <div class="d-flex align-items-start gap-3">
            <i class="fas fa-check-circle fa-lg mt-1" style="color:#00c87a;flex-shrink:0;"></i>
            <div>
              <div class="fw-bold mb-1" style="color:#4ddb9e;">¡Pieza publicada correctamente!</div>
              <div class="d-flex gap-3 flex-wrap mt-2">
                <a href="pieza-detalle.php?id=<?= $piezaId ?>"
                   class="btn btn-sm" style="background:rgba(0,180,100,0.15);border:1px solid rgba(0,180,100,0.3);color:#4ddb9e;border-radius:50px;">
                  <i class="fas fa-eye me-1"></i> Ver pieza
                </a>
                <a href="subir-pieza.php"
                   class="btn btn-sm" style="background:rgba(255,60,0,0.12);border:1px solid rgba(255,60,0,0.3);color:#ff8060;border-radius:50px;">
                  <i class="fas fa-plus me-1"></i> Subir otra
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Error -->
      <?php if (!empty($error)): ?>
        <div class="alert-sp-error mb-4">
          <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="subir-pieza.php" id="form-pieza">

        <!-- ── INFORMACIÓN BÁSICA ── -->
        <div class="sp-section-title mb-3">
          <i class="fas fa-info-circle me-2"></i>Información básica
        </div>

        <div class="row g-4 mb-4">
          <!-- Nombre -->
          <div class="col-md-7">
            <label class="sp-label">Nombre de la pieza <span style="color:#ff3c00;">*</span></label>
            <input type="text" name="nombre" class="sp-input"
                   placeholder="Ej: Filtro de aceite Volkswagen Golf VII"
                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
          </div>
          <!-- Referencia -->
          <div class="col-md-5">
            <label class="sp-label">Referencia <span style="color:#ff3c00;">*</span></label>
            <input type="text" name="referencia" class="sp-input"
                   placeholder="Ej: 5Q0-407-151-A"
                   value="<?= htmlspecialchars($_POST['referencia'] ?? '') ?>" required>
          </div>

          <!-- Categoría -->
          <div class="col-md-6">
            <label class="sp-label">Categoría</label>
            <select name="categoria" class="sp-input">
              <option value="">Sin categoría</option>
              <?php foreach (['Motor','Suspensión','Frenos','Transmisión','Carrocería','Interior','Eléctrico','Escape','Turbo/Compresor','Refrigeración','Dirección','Otros'] as $cat):
                $sel = (($_POST['categoria'] ?? '') === $cat) ? 'selected' : '';
              ?>
                <option value="<?= $cat ?>" <?= $sel ?>><?= $cat ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Precio -->
          <div class="col-md-3">
            <label class="sp-label">Precio (€)</label>
            <input type="number" name="precio" class="sp-input" min="0" step="0.01"
                   placeholder="0.00"
                   value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>">
          </div>

          <!-- Stock -->
          <div class="col-md-3">
            <label class="sp-label">Stock (uds.)</label>
            <input type="number" name="stock" class="sp-input" min="0" step="1"
                   placeholder="0"
                   value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">
          </div>

          <!-- Estado -->
          <div class="col-12">
            <label class="sp-label d-block mb-2">Estado de la pieza</label>
            <div class="estado-group">
              <?php
                $estadoActual = $_POST['estado_pieza'] ?? 'nueva';
                $estados = ['nueva' => '✨ Nueva', 'usada_buena' => '👍 Usada – buen estado', 'usada_desgaste' => '⚠️ Usada – con desgaste'];
                foreach ($estados as $val => $lbl):
              ?>
                <label class="estado-label">
                  <input type="radio" name="estado_pieza" value="<?= $val ?>"
                         <?= $estadoActual === $val ? 'checked' : '' ?>>
                  <?= $lbl ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Garantía -->
          <div class="col-md-6">
            <label class="sp-label">Garantía</label>
            <input type="text" name="garantia" class="sp-input"
                   placeholder="Ej: 12 meses, Sin garantía…"
                   value="<?= htmlspecialchars($_POST['garantia'] ?? '') ?>">
          </div>

          <!-- URL externa -->
          <div class="col-md-6">
            <label class="sp-label">Enlace externo (opcional)</label>
            <input type="url" name="url" class="sp-input"
                   placeholder="https://tienda.com/pieza"
                   value="<?= htmlspecialchars($_POST['url'] ?? '') ?>">
          </div>
        </div>

        <!-- ── IMAGEN ── -->
        <div class="sp-section-title mb-3">
          <i class="fas fa-image me-2"></i>Imagen de la pieza <span style="color:rgba(255,60,0,0.7);font-size:0.68rem;text-transform:none;font-weight:400;letter-spacing:0;">(pega aquí la URL de la foto)</span>
        </div>
        <div class="row g-4 mb-4 align-items-center">
          <div class="col-md-7">
            <label class="sp-label">URL de la imagen <span style="color:#ff3c00;">*</span></label>
            <input type="url" name="imagen" id="input-imagen" class="sp-input"
                   placeholder="https://ejemplo.com/imagen.jpg"
                   value="<?= htmlspecialchars($_POST['imagen'] ?? '') ?>"
                   oninput="previewImagen(this.value)">
            <div class="mt-2" style="color:rgba(255,255,255,0.25);font-size:0.75rem;">
              <i class="fas fa-info-circle me-1"></i>Pega aquí la URL directa de la foto de la pieza (jpg, png, webp…)
            </div>
          </div>
          <div class="col-md-5">
            <div id="img-placeholder">
              <i class="fas fa-image fa-2x"></i>
              <span>Vista previa</span>
            </div>
            <img id="img-preview" src="" alt="Vista previa"
                 onerror="this.style.display='none';document.getElementById('img-placeholder').style.display='flex';">
          </div>
        </div>

        <!-- ── DESCRIPCIÓN ── -->
        <div class="sp-section-title mb-3">
          <i class="fas fa-align-left me-2"></i>Descripción
        </div>
        <div class="mb-4">
          <textarea name="descripcion" class="sp-input" rows="4"
                    placeholder="Describe la pieza: materiales, especificaciones técnicas, compatibilidades generales…"
                    style="resize:vertical;"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
        </div>

        <!-- ── COMPATIBILIDAD ── -->
        <div class="sp-section-title mb-3">
          <i class="fas fa-car me-2"></i>Compatibilidad con vehículo <span style="color:rgba(255,255,255,0.35);font-size:0.68rem;text-transform:none;font-weight:400;letter-spacing:0;">(opcional)</span>
        </div>
        <div class="row g-3 mb-5">
          <!-- Marca -->
          <div class="col-md-4">
            <label class="sp-label">Marca</label>
            <select id="sel-marca" class="sp-input" onchange="cargarModelos(this.value)">
              <option value="">Selecciona marca</option>
              <?php foreach ($marcas as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- Modelo -->
          <div class="col-md-4">
            <label class="sp-label">Modelo</label>
            <select id="sel-modelo" class="sp-input" onchange="cargarMotorizaciones(this.value)" disabled>
              <option value="">Primero selecciona marca</option>
            </select>
          </div>
          <!-- Motorización -->
          <div class="col-md-4">
            <label class="sp-label">Motorización</label>
            <select id="sel-motorizacion" name="motorizacion_id" class="sp-input" disabled>
              <option value="">Primero selecciona modelo</option>
            </select>
          </div>
        </div>

        <!-- Submit -->
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <button type="submit" class="btn-sp-submit" id="btn-submit">
            <i class="fas fa-upload me-2"></i>Publicar pieza
          </button>
          <a href="mi-canal.php" style="color:rgba(255,255,255,0.35);font-size:0.85rem;text-decoration:none;">
            Cancelar
          </a>
        </div>

      </form>
    </div>
  </div>
</section>

<script>
/* ── Preview de imagen ── */
function previewImagen(url) {
  const img  = document.getElementById('img-preview');
  const ph   = document.getElementById('img-placeholder');
  if (url.trim()) {
    img.src = url.trim();
    img.style.display = 'block';
    ph.style.display  = 'none';
  } else {
    img.style.display = 'none';
    ph.style.display  = 'flex';
  }
}
// Inicializar preview si ya hay URL (tras error de validación)
(function() {
  const val = document.getElementById('input-imagen').value;
  if (val) previewImagen(val);
})();

/* ── Cascada Marca → Modelo → Motorización ── */
function cargarModelos(marcaId) {
  const selMod  = document.getElementById('sel-modelo');
  const selMot  = document.getElementById('sel-motorizacion');

  selMod.innerHTML  = '<option value="">Cargando…</option>';
  selMod.disabled   = true;
  selMot.innerHTML  = '<option value="">Primero selecciona modelo</option>';
  selMot.disabled   = true;

  if (!marcaId) {
    selMod.innerHTML = '<option value="">Primero selecciona marca</option>';
    return;
  }

  fetch('public/ajax/get_modelos.php?marca_id=' + marcaId)
    .then(r => r.json())
    .then(data => {
      selMod.innerHTML = '<option value="">Selecciona modelo</option>';
      data.forEach(m => {
        selMod.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
      });
      selMod.disabled = false;
    });
}

function cargarMotorizaciones(modeloId) {
  const selMot = document.getElementById('sel-motorizacion');
  selMot.innerHTML = '<option value="">Cargando…</option>';
  selMot.disabled  = true;

  if (!modeloId) {
    selMot.innerHTML = '<option value="">Primero selecciona modelo</option>';
    return;
  }

  fetch('public/ajax/get_motorizaciones.php?modelo_id=' + modeloId)
    .then(r => r.json())
    .then(data => {
      selMot.innerHTML = '<option value="">Sin motorización específica</option>';
      data.forEach(m => {
        selMot.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
      });
      selMot.disabled = false;
    });
}

/* ── Deshabilitar botón al enviar para evitar doble clic ── */
document.getElementById('form-pieza').addEventListener('submit', function() {
  const btn = document.getElementById('btn-submit');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Publicando…';
});
</script>
