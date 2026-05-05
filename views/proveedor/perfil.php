<?php
$provincias = ['Álava','Albacete','Alicante','Almería','Asturias','Ávila','Badajoz','Barcelona',
  'Burgos','Cáceres','Cádiz','Cantabria','Castellón','Ciudad Real','Córdoba','Cuenca',
  'Gerona','Granada','Guadalajara','Guipúzcoa','Huelva','Huesca','Islas Baleares',
  'Jaén','La Coruña','La Rioja','Las Palmas','León','Lérida','Lugo','Madrid','Málaga',
  'Murcia','Navarra','Orense','Palencia','Pontevedra','Salamanca','Santa Cruz de Tenerife',
  'Segovia','Sevilla','Soria','Tarragona','Teruel','Toledo','Valencia','Valladolid',
  'Vizcaya','Zamora','Zaragoza','Ceuta','Melilla'];
?>

<?php if ($ok): ?>
  <div class="alert-ok-prov"><i class="fas fa-check-circle me-2"></i>Perfil actualizado correctamente.</div>
<?php endif; ?>
<?php if ($error === 'campos'): ?>
  <div class="alert-err-prov"><i class="fas fa-exclamation-circle me-2"></i>Completa los campos obligatorios.</div>
<?php endif; ?>

<div class="row g-4">
  <!-- Info no editable -->
  <div class="col-md-4">
    <div class="admin-card">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-id-card"></i> Datos registrales</div></div>
      <div style="padding:20px;">
        <div style="margin-bottom:14px;">
          <div style="font-size:.72rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:3px;">CIF / NIF</div>
          <div style="color:#ffc107; font-weight:700; font-size:.95rem;"><?= htmlspecialchars($proveedor['cif']) ?></div>
        </div>
        <div style="margin-bottom:14px;">
          <div style="font-size:.72rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:3px;">Dirección fiscal</div>
          <div style="color:rgba(255,255,255,.7); font-size:.88rem;"><?= htmlspecialchars($proveedor['direccion']) ?></div>
        </div>
        <div style="margin-bottom:14px;">
          <div style="font-size:.72rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:3px;">Email</div>
          <div style="color:rgba(255,255,255,.7); font-size:.88rem;"><?= htmlspecialchars($proveedor['email']) ?></div>
        </div>
        <div style="margin-bottom:14px;">
          <div style="font-size:.72rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:3px;">Responsable</div>
          <div style="color:rgba(255,255,255,.7); font-size:.88rem;"><?= htmlspecialchars($proveedor['nombre_responsable']) ?></div>
        </div>
        <div>
          <div style="font-size:.72rem; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:1px; margin-bottom:3px;">Estado</div>
          <span style="background:rgba(25,135,84,.15); border:1px solid rgba(25,135,84,.3); color:#75b798; font-size:.75rem; font-weight:700; padding:3px 10px; border-radius:20px;">
            <i class="fas fa-check-circle me-1"></i>Verificado
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Formulario editable -->
  <div class="col-md-8">
    <div class="admin-card">
      <div class="admin-card-header"><div class="admin-card-title"><i class="fas fa-edit"></i> Editar perfil</div></div>
      <div style="padding:24px;">
        <form action="proveedor.php" method="POST">
          <input type="hidden" name="action" value="guardar_perfil">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label-prov">Nombre de la empresa <span class="text-danger">*</span></label>
              <input type="text" name="nombre_empresa" class="form-control form-control-prov"
                     value="<?= htmlspecialchars($proveedor['nombre_empresa']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label-prov">Provincia <span class="text-danger">*</span></label>
              <select name="provincia" class="form-select form-select-prov" required>
                <?php foreach ($provincias as $p): ?>
                  <option value="<?= $p ?>" <?= ($proveedor['provincia'] ?? '') === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label-prov">Teléfono <span class="text-danger">*</span></label>
              <input type="tel" name="telefono" class="form-control form-control-prov"
                     value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label-prov">Sitio web</label>
              <input type="url" name="sitio_web" class="form-control form-control-prov"
                     placeholder="https://www.tuempresa.com"
                     value="<?= htmlspecialchars($proveedor['sitio_web'] ?? '') ?>">
            </div>
            <div class="col-12">
              <label class="form-label-prov">Descripción de la empresa</label>
              <textarea name="descripcion" class="form-control form-control-prov" rows="4"
                        placeholder="Describe qué tipo de recambios ofrecéis…"><?= htmlspecialchars($proveedor['descripcion'] ?? '') ?></textarea>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
              <button type="submit" class="btn btn-red"><i class="fas fa-save me-2"></i>Guardar cambios</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
