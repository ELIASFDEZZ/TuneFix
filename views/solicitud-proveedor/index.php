<?php
$errores = [
    'campos'       => 'Por favor, completa todos los campos obligatorios.',
    'cif'          => 'El formato del CIF/NIF no es válido. Usa formato español (ej: B12345678 o 12345678A).',
    'email_formato'=> 'El formato del email no es válido.',
    'email_existe' => 'Ya existe una solicitud registrada con ese email.',
    'cif_existe'   => 'Ya existe una solicitud registrada con ese CIF.',
    'password'     => 'Las contraseñas no coinciden.',
    'corta'        => 'La contraseña debe tener al menos 8 caracteres.',
    'descripcion'  => 'La descripción debe tener al menos 50 caracteres.',
    'doc_cif'      => 'Debes subir el documento CIF / escritura de empresa.',
    'doc_iae'      => 'Debes subir el alta en IAE o certificado de actividad.',
    'upload'       => 'Error al subir los documentos. Verifica que sean PDF, JPG o PNG y no superen 8 MB.',
];
$mensajeError = isset($error, $errores[$error]) ? $errores[$error] : null;
$ok = isset($_GET['ok']);
?>

<style>
  .form-proveedor-wrap {
    max-width: 820px;
    margin: 40px auto 60px;
    background: #161625;
    border: 1px solid rgba(255,60,0,0.15);
    border-radius: 16px;
    overflow: hidden;
  }
  .form-prov-header {
    background: linear-gradient(135deg, #a4042e, #ff3c00);
    padding: 36px 40px;
    text-align: center;
  }
  .form-prov-header h1 { font-size: 1.7rem; font-weight: 800; color: #fff; margin: 0 0 8px; }
  .form-prov-header p  { color: rgba(255,255,255,0.85); margin: 0; font-size: 0.95rem; }
  .form-prov-body { padding: 36px 40px; }

  .section-title {
    font-size: 0.7rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
    margin: 28px 0 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .section-title:first-child { margin-top: 0; }

  .form-label { color: rgba(255,255,255,0.65); font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; }
  .form-control, .form-select {
    background: #0d0d1a;
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 8px;
  }
  .form-control:focus, .form-select:focus {
    background: #0d0d1a;
    border-color: #a4042e;
    color: #fff;
    box-shadow: 0 0 0 0.2rem rgba(164,4,46,0.2);
  }
  .form-control::placeholder { color: rgba(255,255,255,0.2); }
  .form-select option { background: #1c2033; }
  .form-text { color: rgba(255,255,255,0.3); font-size: 0.78rem; }

  .upload-zone {
    border: 2px dashed rgba(255,255,255,0.12);
    border-radius: 10px;
    padding: 18px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.25s;
    background: #0d0d1a;
  }
  .upload-zone:hover { border-color: rgba(164,4,46,0.5); }
  .upload-zone input[type=file] { display: none; }
  .upload-zone label { cursor: pointer; color: rgba(255,255,255,0.5); font-size: 0.87rem; }
  .upload-zone .file-name { font-size: 0.8rem; color: #ff8800; margin-top: 6px; }

  .btn-submit-prov {
    background: linear-gradient(135deg, #a4042e, #ff3c00);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 14px 40px;
    font-size: 1rem;
    font-weight: 700;
    width: 100%;
    transition: opacity 0.2s;
  }
  .btn-submit-prov:hover { opacity: 0.9; color: #fff; }

  .alert-err { background: rgba(220,53,69,0.12); border: 1px solid rgba(220,53,69,0.3); color: #ff8888; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.9rem; }
  .alert-ok  { background: rgba(25,135,84,0.12); border: 1px solid rgba(25,135,84,0.3); color: #75b798; border-radius: 8px; padding: 20px; text-align: center; }
  .alert-ok h4 { color: #75b798; margin-bottom: 8px; }

  .char-count { font-size: 0.75rem; color: rgba(255,255,255,0.25); text-align: right; margin-top: 4px; }
</style>

<div class="container">
  <div class="form-proveedor-wrap">
    <div class="form-prov-header">
      <div style="font-size:2.5rem; margin-bottom:12px;">🏭</div>
      <h1>Solicitud de acceso como Proveedor</h1>
      <p>Completa el formulario y revisaremos tu solicitud en un plazo de 48 horas</p>
    </div>

    <div class="form-prov-body">

      <?php if ($ok): ?>
        <div class="alert-ok">
          <div style="font-size:2.5rem; margin-bottom:12px;">✅</div>
          <h4>¡Solicitud enviada correctamente!</h4>
          <p style="color: rgba(255,255,255,0.6); margin: 0;">
            Tu solicitud ha sido enviada. Revisaremos tu documentación y recibirás una respuesta
            por email en un plazo de <strong>48 horas</strong>.
          </p>
          <div class="mt-4">
            <a href="index.php" class="btn btn-outline-light btn-sm">Volver al inicio</a>
          </div>
        </div>
      <?php else: ?>

        <?php if ($mensajeError): ?>
          <div class="alert-err"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($mensajeError) ?></div>
        <?php endif; ?>

        <form action="solicitud-proveedor.php" method="POST" enctype="multipart/form-data" novalidate>

          <!-- DATOS DE EMPRESA -->
          <div class="section-title"><i class="fas fa-building me-1"></i> Datos de la empresa</div>

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
              <input type="text" name="nombre_empresa" class="form-control"
                     placeholder="Recambios García S.L."
                     value="<?= htmlspecialchars($_POST['nombre_empresa'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">CIF / NIF <span class="text-danger">*</span></label>
              <input type="text" name="cif" class="form-control"
                     placeholder="B12345678"
                     value="<?= htmlspecialchars($_POST['cif'] ?? '') ?>" required>
              <div class="form-text">Formato: B12345678 o 12345678A</div>
            </div>
            <div class="col-12">
              <label class="form-label">Dirección fiscal completa <span class="text-danger">*</span></label>
              <input type="text" name="direccion" class="form-control"
                     placeholder="Calle Mayor 12, 2º B, 28001"
                     value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Provincia / Comunidad Autónoma <span class="text-danger">*</span></label>
              <select name="provincia" class="form-select" required>
                <option value="">— Selecciona —</option>
                <?php
                $provincias = ['Álava','Albacete','Alicante','Almería','Asturias','Ávila','Badajoz','Barcelona',
                  'Burgos','Cáceres','Cádiz','Cantabria','Castellón','Ciudad Real','Córdoba','Cuenca',
                  'Gerona','Granada','Guadalajara','Guipúzcoa','Huelva','Huesca','Islas Baleares',
                  'Jaén','La Coruña','La Rioja','Las Palmas','León','Lérida','Lugo','Madrid','Málaga',
                  'Murcia','Navarra','Orense','Palencia','Pontevedra','Salamanca','Santa Cruz de Tenerife',
                  'Segovia','Sevilla','Soria','Tarragona','Teruel','Toledo','Valencia','Valladolid',
                  'Vizcaya','Zamora','Zaragoza','Ceuta','Melilla'];
                $selProv = $_POST['provincia'] ?? '';
                foreach ($provincias as $p):
                ?>
                  <option value="<?= $p ?>" <?= $selProv === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono de contacto <span class="text-danger">*</span></label>
              <input type="tel" name="telefono" class="form-control"
                     placeholder="+34 600 000 000"
                     value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label">Sitio web de la empresa <span class="text-muted">(opcional)</span></label>
              <input type="url" name="sitio_web" class="form-control"
                     placeholder="https://www.tuempresa.com"
                     value="<?= htmlspecialchars($_POST['sitio_web'] ?? '') ?>">
            </div>
          </div>

          <!-- DATOS DEL RESPONSABLE -->
          <div class="section-title mt-4"><i class="fas fa-user-tie me-1"></i> Datos del responsable</div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre completo del responsable <span class="text-danger">*</span></label>
              <input type="text" name="nombre_responsable" class="form-control"
                     placeholder="Juan García López"
                     value="<?= htmlspecialchars($_POST['nombre_responsable'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email de contacto <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control"
                     placeholder="contacto@empresa.com"
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contraseña <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control"
                     placeholder="Mínimo 8 caracteres" minlength="8" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
              <input type="password" name="confirm" class="form-control"
                     placeholder="Repite la contraseña" required>
            </div>
          </div>

          <!-- DOCUMENTACIÓN -->
          <div class="section-title mt-4"><i class="fas fa-file-alt me-1"></i> Documentación</div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Documento CIF / escritura de empresa <span class="text-danger">*</span></label>
              <div class="upload-zone" id="zone-cif">
                <input type="file" name="doc_cif" id="doc_cif" accept=".pdf,.jpg,.jpeg,.png" required>
                <label for="doc_cif">
                  <i class="fas fa-cloud-upload-alt" style="font-size:1.5rem; color:rgba(255,255,255,0.3); display:block; margin-bottom:6px;"></i>
                  Haz clic o arrastra el archivo aquí<br>
                  <span style="font-size:0.75rem; color:rgba(255,255,255,0.2);">PDF, JPG o PNG · Máx. 8 MB</span>
                </label>
                <div class="file-name" id="nombre-cif"></div>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Alta en IAE / certificado de actividad <span class="text-danger">*</span></label>
              <div class="upload-zone" id="zone-iae">
                <input type="file" name="doc_iae" id="doc_iae" accept=".pdf,.jpg,.jpeg,.png" required>
                <label for="doc_iae">
                  <i class="fas fa-cloud-upload-alt" style="font-size:1.5rem; color:rgba(255,255,255,0.3); display:block; margin-bottom:6px;"></i>
                  Haz clic o arrastra el archivo aquí<br>
                  <span style="font-size:0.75rem; color:rgba(255,255,255,0.2);">PDF, JPG o PNG · Máx. 8 MB</span>
                </label>
                <div class="file-name" id="nombre-iae"></div>
              </div>
            </div>
          </div>

          <!-- DESCRIPCIÓN -->
          <div class="section-title mt-4"><i class="fas fa-align-left me-1"></i> Descripción</div>

          <div class="mb-2">
            <label class="form-label">Describe brevemente qué tipo de recambios o piezas ofreces <span class="text-danger">*</span></label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="5"
                      placeholder="Somos distribuidores de recambios originales y alternativos para vehículos europeos, especializados en frenos, suspensión y filtros. Llevamos más de 15 años en el sector..." required minlength="50"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            <div class="char-count"><span id="char-count">0</span> / mínimo 50 caracteres</div>
          </div>

          <div class="mt-4 pt-2">
            <button type="submit" class="btn btn-submit-prov">
              <i class="fas fa-paper-plane me-2"></i> Enviar solicitud
            </button>
          </div>

        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
// Mostrar nombre del archivo seleccionado
function bindFileInput(inputId, displayId) {
  document.getElementById(inputId).addEventListener('change', function() {
    const display = document.getElementById(displayId);
    display.textContent = this.files[0] ? '✓ ' + this.files[0].name : '';
  });
}
bindFileInput('doc_cif', 'nombre-cif');
bindFileInput('doc_iae', 'nombre-iae');

// Contador de caracteres para descripción
const desc    = document.getElementById('descripcion');
const counter = document.getElementById('char-count');
function updateCount() {
  const n = desc.value.length;
  counter.textContent = n;
  counter.style.color = n >= 50 ? '#75b798' : 'rgba(255,100,100,0.6)';
}
desc.addEventListener('input', updateCount);
updateCount();
</script>
