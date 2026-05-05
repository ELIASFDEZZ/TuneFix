<?php
$erroresMsgs = [
    'campos'    => 'Completa todos los campos obligatorios (nombre, descripción, precio, stock, categoría y estado).',
    'vehiculo'  => 'Debes seleccionar al menos una marca de vehículo compatible.',
    'db'        => 'Error al guardar en la base de datos. Comprueba que las tablas estén creadas (migrations/proveedores.sql).',
    'post_size' => 'Las imágenes son demasiado grandes. Reduce el tamaño total o sube menos fotos.',
];
$msgError = ($error && isset($erroresMsgs[$error])) ? $erroresMsgs[$error] : null;

$categorias = ['Motor','Frenos','Suspensión','Transmisión','Carrocería','Electricidad',
               'Climatización','Escape','Filtros','Dirección','Iluminación','Otros'];
?>

<style>
.vehiculo-block {
  background: #141727;
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 10px;
  padding: 18px 18px 14px;
  margin-bottom: 12px;
  position: relative;
}
.btn-remove-veh {
  position: absolute; top: 12px; right: 12px;
  background: rgba(220,53,69,.15); border: 1px solid rgba(220,53,69,.2);
  color: #ff6b6b; border-radius: 6px; width: 30px; height: 30px;
  cursor: pointer; font-size: .8rem;
  display: flex; align-items: center; justify-content: center;
}
.btn-remove-veh:hover { background: rgba(220,53,69,.35); }
select:disabled { opacity: .4; cursor: not-allowed; }
</style>

<?php if ($msgError): ?>
  <div class="alert-err-prov"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($msgError) ?></div>
<?php endif; ?>

<form action="proveedor.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="action" value="guardar_pieza">

  <!-- ══ VEHÍCULOS COMPATIBLES ══════════════════════════════════════════════ -->
  <div class="admin-card mb-4">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="fas fa-car"></i> Vehículos compatibles
        <span style="color:rgba(255,60,0,.8); font-size:.78rem; margin-left:6px;">* obligatorio</span>
      </div>
      <button type="button" class="btn-ghost btn btn-sm" id="btn-add-veh">
        <i class="fas fa-plus me-1"></i> Añadir vehículo
      </button>
    </div>
    <div style="padding:20px;">
      <div id="vehiculos-container">
        <!-- Se genera por JS al cargar -->
      </div>
      <p style="color:rgba(255,255,255,.25); font-size:.78rem; margin:8px 0 0;">
        <i class="fas fa-info-circle me-1"></i>
        Selecciona marca → modelo → motorización. Puedes añadir varios vehículos.
      </p>
    </div>
  </div>

  <!-- ══ DATOS DE LA PIEZA ══════════════════════════════════════════════════ -->
  <div class="admin-card mb-4">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="fas fa-cog"></i> Datos de la pieza</div>
    </div>
    <div style="padding:24px;">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label-prov">Nombre de la pieza <span class="text-danger">*</span></label>
          <input type="text" name="nombre" class="form-control form-control-prov"
                 placeholder="Ej: Kit de frenos delanteros" required>
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Referencia OEM</label>
          <input type="text" name="referencia_oem" class="form-control form-control-prov"
                 placeholder="Ej: 1K0615123A">
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Categoría <span class="text-danger">*</span></label>
          <select name="categoria" class="form-select form-select-prov" required>
            <option value="">— Selecciona —</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Estado de la pieza <span class="text-danger">*</span></label>
          <select name="estado_pieza" class="form-select form-select-prov" required>
            <option value="">— Selecciona —</option>
            <option value="nueva">Nueva</option>
            <option value="usada_buena">Usada — buen estado</option>
            <option value="usada_desgaste">Usada — con desgaste</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Garantía</label>
          <select name="garantia" class="form-select form-select-prov">
            <option value="Sin garantía">Sin garantía</option>
            <option value="1 mes">1 mes</option>
            <option value="3 meses">3 meses</option>
            <option value="6 meses">6 meses</option>
            <option value="1 año">1 año</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label-prov">Precio (€) <span class="text-danger">*</span></label>
          <input type="number" name="precio" class="form-control form-control-prov"
                 placeholder="0.00" min="0" step="0.01" required>
        </div>
        <div class="col-md-6">
          <label class="form-label-prov">Stock (unidades) <span class="text-danger">*</span></label>
          <input type="number" name="stock" class="form-control form-control-prov"
                 placeholder="0" min="0" required>
        </div>
        <div class="col-12">
          <label class="form-label-prov">Descripción detallada <span class="text-danger">*</span></label>
          <textarea name="descripcion" class="form-control form-control-prov" rows="4"
                    placeholder="Describe materiales, instrucciones de montaje, características técnicas…" required></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ FOTO URL ═════════════════════════════════════════════════════════ -->
  <div class="admin-card mb-4">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="fas fa-image"></i> Foto de la pieza</div>
      <span style="font-size:.78rem; color:rgba(255,255,255,.3);">URL directa de la imagen · Opcional</span>
    </div>
    <div style="padding:20px;">
      <label class="form-label-prov">URL de la imagen</label>
      <input type="url" name="imagen_url" class="form-control form-control-prov"
             placeholder="https://example.com/imagen.jpg">
      <p style="color:rgba(255,255,255,.35); margin:0; font-size:.9rem;">
        Introduce la URL completa de la imagen para que se muestre en la ficha.
      </p>
    </div>
  </div>

  <div style="display:flex; gap:12px; justify-content:flex-end;">
    <a href="proveedor.php?page=mis-piezas" class="btn btn-ghost">Cancelar</a>
    <button type="submit" class="btn btn-red" id="btn-submit">
      <i class="fas fa-paper-plane me-2"></i>Publicar pieza
    </button>
  </div>
</form>

<script>
// ── Datos de marcas desde PHP ─────────────────────────────────────────────
const MARCAS = <?= json_encode(array_values($marcas), JSON_UNESCAPED_UNICODE) ?>;
const URL_MODELOS = 'public/ajax/get_modelos.php';
const URL_MOTOR   = 'public/ajax/get_motorizaciones.php';

let vIdx = 0;

// ── Construir bloque de vehículo ──────────────────────────────────────────
function crearBloque(idx) {
  const div = document.createElement('div');
  div.className = 'vehiculo-block';
  div.dataset.idx = idx;

  const opsMarca = '<option value="">— Selecciona marca —</option>' +
    MARCAS.map(m => `<option value="${esc(m.nombre)}" data-id="${m.id}">${esc(m.nombre)}</option>`).join('');

  div.innerHTML = `
    ${idx > 0 ? `<button type="button" class="btn-remove-veh" onclick="this.closest('.vehiculo-block').remove()"><i class="fas fa-times"></i></button>` : ''}
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label-prov">Marca <span class="text-danger">*</span></label>
        <select name="marca_nombre[]" class="form-select form-select-prov sel-marca" required>
          ${opsMarca}
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label-prov">Modelo</label>
        <select name="modelo_nombre[]" class="form-select form-select-prov sel-modelo" disabled>
          <option value="">— Elige marca primero —</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label-prov">Motorización</label>
        <select name="motorizacion_texto[]" class="form-select form-select-prov sel-motor" disabled>
          <option value="">— Elige modelo primero —</option>
        </select>
        <input type="hidden" name="motorizacion_id[]" class="motorizacion-id" value="">
      </div>
    </div>`;

  return div;
}

// ── Enlazar eventos en cascada ────────────────────────────────────────────
function bindBloque(bloque) {
  const selMarca  = bloque.querySelector('.sel-marca');
  const selModelo = bloque.querySelector('.sel-modelo');
  const selMotor  = bloque.querySelector('.sel-motor');

  selMarca.addEventListener('change', async function () {
    // Resetear dependientes
    resetSel(selModelo, '— Elige marca primero —', true);
    resetSel(selMotor,  '— Elige modelo primero —', true);

    const opt = this.options[this.selectedIndex];
    const marcaId = opt?.dataset?.id;
    if (!marcaId) return;

    resetSel(selModelo, 'Cargando modelos…', true);
    const modelos = await fetchJSON(`${URL_MODELOS}?marca_id=${marcaId}`);

    if (!modelos.length) {
      resetSel(selModelo, '— Sin modelos en BD —', false);
      return;
    }
    resetSel(selModelo, '— Selecciona modelo —', false);
    modelos.forEach(m => {
      const o = new Option(m.nombre, m.nombre);
      o.dataset.id = m.id;
      selModelo.add(o);
    });
  });

  selModelo.addEventListener('change', async function () {
    resetSel(selMotor, '— Elige modelo primero —', true);
    bloque.querySelector('.motorizacion-id').value = '';

    const opt = this.options[this.selectedIndex];
    const modeloId = opt?.dataset?.id;
    if (!modeloId) return;

    resetSel(selMotor, 'Cargando motorizaciones…', true);
    const motors = await fetchJSON(`${URL_MOTOR}?modelo_id=${modeloId}`);

    resetSel(selMotor, motors.length ? '— Selecciona (opcional) —' : '— Sin motorizaciones en BD —', false);
    motors.forEach(m => {
      const option = new Option(m.texto, m.texto);
      option.dataset.id = m.id;
      selMotor.add(option);
    });
  });

  selMotor.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const motId = opt?.dataset?.id || '';
    bloque.querySelector('.motorizacion-id').value = motId;
  });
}

function resetSel(sel, placeholder, disabled) {
  sel.innerHTML = `<option value="">${placeholder}</option>`;
  sel.disabled  = disabled;
}

async function fetchJSON(url) {
  try {
    const r = await fetch(url);
    return r.ok ? await r.json() : [];
  } catch { return []; }
}

function esc(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Botón añadir vehículo ─────────────────────────────────────────────────
document.getElementById('btn-add-veh').addEventListener('click', () => {
  vIdx++;
  const b = crearBloque(vIdx);
  document.getElementById('vehiculos-container').appendChild(b);
  bindBloque(b);
});

// ── Bloque inicial ────────────────────────────────────────────────────────
(function () {
  const b = crearBloque(0);
  document.getElementById('vehiculos-container').appendChild(b);
  bindBloque(b);
})();

// ── Validación antes de enviar ────────────────────────────────────────────
document.querySelector('form').addEventListener('submit', function (e) {
  const primMarca = document.querySelector('.sel-marca');
  if (primMarca && primMarca.value === '') {
    e.preventDefault();
    alert('Debes seleccionar al menos una marca de vehículo compatible.');
    primMarca.focus();
  }
});

</script>
