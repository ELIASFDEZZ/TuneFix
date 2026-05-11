<?php
$erroresMsgs = [
    'campos' => 'Completa todos los campos obligatorios (nombre, precio, stock, categoría y estado).',
];
$msgError = ($error && isset($erroresMsgs[$error])) ? $erroresMsgs[$error] : null;

$categorias = ['Motor','Frenos','Suspensión','Transmisión','Carrocería','Electricidad',
               'Climatización','Escape','Filtros','Dirección','Iluminación','Otros'];
?>

<?php if ($msgError): ?>
  <div class="alert-err-prov"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($msgError) ?></div>
<?php endif; ?>

<form action="proveedor.php" method="POST">
  <input type="hidden" name="action"   value="guardar_edicion_pieza">
  <input type="hidden" name="pieza_id" value="<?= (int)$pieza['id'] ?>">

  <!-- ══ DATOS ══ -->
  <div class="admin-card mb-4">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="fas fa-cog"></i> Editar pieza</div>
    </div>
    <div style="padding:24px;">
      <div class="row g-3">

        <div class="col-md-8">
          <label class="form-label-prov">Nombre <span class="text-danger">*</span></label>
          <input type="text" name="nombre" class="form-control form-control-prov"
                 value="<?= htmlspecialchars($pieza['nombre']) ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Referencia OEM</label>
          <input type="text" name="referencia_oem" class="form-control form-control-prov"
                 value="<?= htmlspecialchars($pieza['referencia'] ?? '') ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label-prov">Categoría <span class="text-danger">*</span></label>
          <select name="categoria" class="form-select form-select-prov" required>
            <option value="">— Selecciona —</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= $c ?>" <?= ($pieza['categoria'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Estado <span class="text-danger">*</span></label>
          <select name="estado_pieza" class="form-select form-select-prov" required>
            <option value="">— Selecciona —</option>
            <option value="nueva"          <?= ($pieza['estado_pieza'] ?? '') === 'nueva'          ? 'selected' : '' ?>>Nueva</option>
            <option value="usada_buena"    <?= ($pieza['estado_pieza'] ?? '') === 'usada_buena'    ? 'selected' : '' ?>>Usada — buen estado</option>
            <option value="usada_desgaste" <?= ($pieza['estado_pieza'] ?? '') === 'usada_desgaste' ? 'selected' : '' ?>>Usada — con desgaste</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label-prov">Garantía</label>
          <select name="garantia" class="form-select form-select-prov">
            <?php foreach (['Sin garantía','1 mes','3 meses','6 meses','1 año'] as $g): ?>
              <option value="<?= $g ?>" <?= ($pieza['garantia'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label-prov">Precio (€) <span class="text-danger">*</span></label>
          <input type="number" name="precio" class="form-control form-control-prov"
                 min="0" step="0.01" required
                 value="<?= htmlspecialchars($pieza['precio'] ?? '0') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label-prov">Stock (uds.) <span class="text-danger">*</span></label>
          <input type="number" name="stock" class="form-control form-control-prov"
                 min="0" required
                 value="<?= (int)($pieza['stock'] ?? 0) ?>">
        </div>

        <div class="col-12">
          <label class="form-label-prov">Descripción</label>
          <textarea name="descripcion" class="form-control form-control-prov" rows="4"><?= htmlspecialchars($pieza['descripcion'] ?? '') ?></textarea>
        </div>

      </div>
    </div>
  </div>

  <!-- ══ IMAGEN ══ -->
  <div class="admin-card mb-4">
    <div class="admin-card-header">
      <div class="admin-card-title"><i class="fas fa-image"></i> Foto de la pieza</div>
    </div>
    <div style="padding:20px;" class="row g-3 align-items-center">
      <div class="col-md-7">
        <label class="form-label-prov">URL de la imagen</label>
        <input type="url" name="imagen_url" id="input-img" class="form-control form-control-prov"
               value="<?= htmlspecialchars($pieza['imagen'] ?? '') ?>"
               oninput="prevImg(this.value)"
               placeholder="https://ejemplo.com/imagen.jpg">
      </div>
      <div class="col-md-5">
        <div id="img-ph" style="width:100%;height:120px;border:1px dashed rgba(255,255,255,.12);border-radius:8px;display:<?= !empty($pieza['imagen']) ? 'none' : 'flex' ?>;align-items:center;justify-content:center;color:rgba(255,255,255,.2);font-size:.8rem;flex-direction:column;gap:6px;">
          <i class="fas fa-image fa-2x"></i><span>Vista previa</span>
        </div>
        <img id="img-prev" src="<?= htmlspecialchars($pieza['imagen'] ?? '') ?>"
             style="width:100%;height:120px;object-fit:cover;border-radius:8px;border:1px solid rgba(255,255,255,.1);display:<?= !empty($pieza['imagen']) ? 'block' : 'none' ?>;"
             onerror="this.style.display='none';document.getElementById('img-ph').style.display='flex';"
             alt="">
      </div>
    </div>
  </div>

  <!-- ══ ACCIONES (solo guardar y cancelar) ══ -->
  <div style="display:flex;gap:12px;justify-content:flex-end;align-items:center;flex-wrap:wrap;">
    <a href="proveedor.php?page=mis-piezas" class="btn btn-ghost">Cancelar</a>
    <button type="submit" class="btn btn-red">
      <i class="fas fa-save me-2"></i>Guardar cambios
    </button>
  </div>

</form>

<!-- ══ ELIMINAR (fuera del form principal) ══ -->
<div style="margin-top:28px;padding-top:20px;border-top:1px solid rgba(220,53,69,0.18);">
  <form method="POST" action="proveedor.php"
        onsubmit="return confirm('¿Eliminar esta pieza del catálogo? No se puede deshacer.')">
    <input type="hidden" name="action"   value="eliminar_pieza">
    <input type="hidden" name="pieza_id" value="<?= (int)$pieza['id'] ?>">
    <button type="submit" class="btn"
            style="background:rgba(220,53,69,.12);border:1px solid rgba(220,53,69,.3);color:#ff6b6b;border-radius:8px;font-weight:600;padding:8px 18px;font-size:.85rem;">
      <i class="fas fa-trash-alt me-2"></i>Eliminar pieza
    </button>
  </form>
</div>

<script>
function prevImg(url) {
  const img = document.getElementById('img-prev');
  const ph  = document.getElementById('img-ph');
  if (url.trim()) {
    img.src = url.trim();
    img.style.display = 'block';
    ph.style.display  = 'none';
  } else {
    img.style.display = 'none';
    ph.style.display  = 'flex';
  }
}
</script>
