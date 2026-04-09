<style>
  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.88)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 50px;
  }
  .content-section { background: rgba(255,255,255,0.95); flex: 1; }
  .section-divider  { border-top: 2px solid rgba(255,60,0,0.2); margin-bottom: 2rem; }

  .profile-avatar {
    width: 82px; height: 82px;
    background: linear-gradient(135deg, rgb(164,4,46), #ff8800);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 800; color: #fff;
    border: 4px solid rgba(255,255,255,0.25);
    flex-shrink: 0;
  }
  .btn-volver { border-color: rgba(255,255,255,0.35); color: rgba(255,255,255,0.75); font-size: 0.85rem; }
  .btn-volver:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.7); }

  /* ── Tarjetas ── */
  .p-card {
    background: #fff;
    border: 1px solid rgba(255,60,0,0.15);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
    margin-bottom: 1.25rem;
  }
  .p-card-header {
    background: linear-gradient(135deg, rgb(164,4,46), #ff8800);
    padding: 15px 20px;
    display: flex; align-items: center; gap: 10px;
  }
  .p-card-header h6 { color: #fff; font-weight: 700; margin: 0; font-size: 0.95rem; }
  .p-card-header .hicon { color: rgba(255,255,255,0.8); }
  .p-card-body { padding: 20px; }

  /* ── Campos de info ── */
  .field-row {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,60,0,0.08);
  }
  .field-row:last-child { border-bottom: none; padding-bottom: 0; }
  .field-icon {
    width: 34px; height: 34px; flex-shrink: 0;
    background: rgba(255,60,0,0.08);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: rgb(164,4,46); font-size: 0.85rem;
  }
  .field-label { font-size: 0.72rem; color: rgba(0,0,0,0.42); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
  .field-value { font-size: 0.93rem; color: #111; font-weight: 500; }

  /* ── Inputs ── */
  .form-control-tuning {
    border: 1px solid rgba(255,60,0,0.25);
    border-radius: 8px;
    font-size: 0.9rem;
    padding: 9px 13px;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .form-control-tuning:focus {
    border-color: rgb(164,4,46);
    box-shadow: 0 0 0 0.2rem rgba(164,4,46,0.15);
    outline: none;
  }
  .form-select-tuning {
    border: 1px solid rgba(255,60,0,0.25);
    border-radius: 8px;
    font-size: 0.9rem;
    padding: 9px 13px;
  }
  .form-select-tuning:focus {
    border-color: rgb(164,4,46);
    box-shadow: 0 0 0 0.2rem rgba(164,4,46,0.15);
  }

  /* ── Botones ── */
  .btn-primary-tuning {
    background: rgb(164,4,46); border: none; color: #fff;
    font-weight: 600; border-radius: 8px; padding: 9px 20px;
    transition: all 0.2s ease; font-size: 0.88rem;
  }
  .btn-primary-tuning:hover { background: #a8032c; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(164,4,46,0.3); }
  .btn-outline-tuning {
    border: 1px solid rgba(164,4,46,0.4); color: rgb(164,4,46);
    background: transparent; font-weight: 600; border-radius: 8px;
    padding: 7px 16px; font-size: 0.83rem; transition: all 0.2s ease;
  }
  .btn-outline-tuning:hover { background: rgba(164,4,46,0.06); border-color: rgb(164,4,46); }

  /* ── Coche guardado ── */
  .coche-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    border: 1px solid rgba(255,60,0,0.12);
    border-radius: 10px;
    background: rgba(255,60,0,0.03);
    margin-bottom: 8px;
    transition: background 0.2s ease;
  }
  .coche-item:hover { background: rgba(255,60,0,0.07); }
  .coche-icon {
    width: 38px; height: 38px; flex-shrink: 0;
    background: linear-gradient(135deg, rgb(164,4,46), #ff8800);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 0.9rem;
  }
  .coche-info { flex: 1; min-width: 0; }
  .coche-nombre { font-weight: 700; color: #111; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .coche-sub   { font-size: 0.75rem; color: rgba(0,0,0,0.45); }
  .btn-remove-coche {
    background: rgba(220,53,69,0.08); border: 1px solid rgba(220,53,69,0.25);
    color: #dc3545; border-radius: 7px; padding: 5px 10px;
    font-size: 0.78rem; font-weight: 600; transition: all 0.2s ease; flex-shrink: 0;
    cursor: pointer;
  }
  .btn-remove-coche:hover { background: rgba(220,53,69,0.15); border-color: #dc3545; }

  /* ── Accordion personalizado ── */
  .acc-toggle {
    background: none; border: none; width: 100%;
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px; font-weight: 600; font-size: 0.88rem;
    color: rgb(164,4,46); cursor: pointer;
    border-top: 1px solid rgba(255,60,0,0.1);
    transition: background 0.2s ease;
  }
  .acc-toggle:hover { background: rgba(255,60,0,0.04); }
  .acc-toggle .chevron { transition: transform 0.25s ease; font-size: 0.75rem; }
  .acc-toggle.open .chevron { transform: rotate(180deg); }
  .acc-body { display: none; padding: 0 20px 18px; }
  .acc-body.open { display: block; }

  /* ── Alert ── */
  .alert-tuning {
    border-radius: 10px; font-size: 0.88rem; font-weight: 500;
    border: none; padding: 12px 16px;
    display: flex; align-items: center; gap: 10px;
  }
  .alert-success-t { background: rgba(25,135,84,0.1); color: #0f5132; }
  .alert-danger-t  { background: rgba(220,53,69,0.1); color: #842029; }
  .alert-warning-t { background: rgba(255,193,7,0.12); color: #664d03; }

  /* ── Selects de añadir coche ── */
  .add-car-section select:disabled { opacity: 0.5; }
</style>

<!-- HERO -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <div class="d-flex align-items-center gap-4">
      <div class="profile-avatar"><?= strtoupper(mb_substr($_SESSION['usuario_nombre'], 0, 1)) ?></div>
      <div>
        <h1 class="display-5 fw-bold mb-1"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h1>
        <p class="mb-0" style="color:rgba(255,255,255,0.6);">
          <i class="fas fa-envelope me-1"></i><?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- CONTENIDO -->
<section class="content-section py-5">
  <div class="container">
    <div class="section-divider"></div>

    <?php if ($msg): ?>
      <?php $claseAlert = match($msg['tipo']) { 'success' => 'alert-success-t', 'danger' => 'alert-danger-t', default => 'alert-warning-t' }; ?>
      <?php $iconAlert  = match($msg['tipo']) { 'success' => 'fa-check-circle', 'danger' => 'fa-times-circle', default => 'fa-exclamation-circle' }; ?>
      <div class="alert-tuning <?= $claseAlert ?> mb-4">
        <i class="fas <?= $iconAlert ?>"></i>
        <?= htmlspecialchars($msg['texto']) ?>
      </div>
    <?php endif; ?>

    <div class="row g-4">

      <!-- ══ COLUMNA IZQUIERDA ══ -->
      <div class="col-lg-6">

        <!-- Info de la cuenta -->
        <div class="p-card">
          <div class="p-card-header">
            <i class="fas fa-id-card hicon"></i>
            <h6>Información de la cuenta</h6>
          </div>
          <div class="p-card-body">
            <!-- Nombre -->
            <div class="field-row">
              <div class="field-icon"><i class="fas fa-user"></i></div>
              <div class="flex-grow-1">
                <div class="field-label">Nombre de usuario</div>
                <div class="field-value"><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></div>
              </div>
            </div>
            <!-- Email -->
            <div class="field-row">
              <div class="field-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <div class="field-label">Correo electrónico</div>
                <div class="field-value"><?= htmlspecialchars($_SESSION['usuario_email'] ?? '—') ?></div>
              </div>
            </div>
          </div>

          <!-- Acordeón: Editar nombre -->
          <button class="acc-toggle" onclick="toggleAcc('acc-nombre', this)">
            <span><i class="fas fa-pen me-2"></i>Editar nombre de usuario</span>
            <i class="fas fa-chevron-down chevron"></i>
          </button>
          <div class="acc-body" id="acc-nombre">
            <form method="POST" action="">
              <input type="hidden" name="accion" value="nombre">
              <label class="field-label mb-1 d-block">Nuevo nombre</label>
              <div class="d-flex gap-2">
                <input
                  type="text"
                  name="nombre"
                  class="form-control form-control-tuning flex-grow-1"
                  placeholder="Tu nuevo nombre..."
                  value="<?= htmlspecialchars($_SESSION['usuario_nombre']) ?>"
                  required maxlength="80"
                >
                <button type="submit" class="btn-primary-tuning">Guardar</button>
              </div>
            </form>
          </div>

          <!-- Acordeón: Cambiar contraseña -->
          <button class="acc-toggle" onclick="toggleAcc('acc-pwd', this)">
            <span><i class="fas fa-lock me-2"></i>Cambiar contraseña</span>
            <i class="fas fa-chevron-down chevron"></i>
          </button>
          <div class="acc-body" id="acc-pwd">
            <form method="POST" action="">
              <input type="hidden" name="accion" value="password">
              <div class="mb-3">
                <label class="field-label mb-1 d-block">Contraseña actual</label>
                <input type="password" name="password_actual" class="form-control form-control-tuning" placeholder="••••••••" required>
              </div>
              <div class="mb-3">
                <label class="field-label mb-1 d-block">Nueva contraseña</label>
                <input type="password" name="password_nueva" class="form-control form-control-tuning" placeholder="Mínimo 6 caracteres" required minlength="6">
              </div>
              <div class="mb-3">
                <label class="field-label mb-1 d-block">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirm" class="form-control form-control-tuning" placeholder="Repite la contraseña" required minlength="6">
              </div>
              <button type="submit" class="btn-primary-tuning w-100">Cambiar contraseña</button>
            </form>
          </div>
        </div>

      </div>

      <!-- ══ COLUMNA DERECHA: Mis coches ══ -->
      <div class="col-lg-6">
        <div class="p-card">
          <div class="p-card-header">
            <i class="fas fa-car hicon"></i>
            <h6>Mis vehículos</h6>
            <span class="ms-auto badge" style="background:rgba(255,255,255,0.25); font-size:0.75rem;">
              <?= count($coches) ?> guardado<?= count($coches) !== 1 ? 's' : '' ?>
            </span>
          </div>
          <div class="p-card-body">

            <!-- Lista de coches guardados -->
            <?php if (empty($coches)): ?>
              <p class="text-black-50 text-center py-3 mb-0" style="font-size:0.88rem;">
                <i class="fas fa-car d-block fa-2x mb-2" style="color:rgba(164,4,46,0.2);"></i>
                Aún no tienes vehículos guardados.
              </p>
            <?php else: ?>
              <div class="mb-3">
                <?php foreach ($coches as $coche): ?>
                  <div class="coche-item">
                    <div class="coche-icon"><i class="fas fa-car"></i></div>
                    <div class="coche-info">
                      <div class="coche-nombre">
                        <?= htmlspecialchars($coche['marca_nombre'] . ' ' . $coche['modelo_nombre']) ?>
                      </div>
                      <div class="coche-sub">
                        <?= htmlspecialchars($coche['motor_nombre']) ?>
                        <?php if ($coche['tipo_combustible']): ?>
                          · <?= htmlspecialchars($coche['tipo_combustible']) ?>
                        <?php endif; ?>
                        <?php if ($coche['potencia']): ?>
                          · <?= htmlspecialchars($coche['potencia']) ?>
                        <?php endif; ?>
                      </div>
                    </div>
                    <form method="POST" action="" style="margin:0;">
                      <input type="hidden" name="accion" value="remove_coche">
                      <input type="hidden" name="rel_id" value="<?= $coche['rel_id'] ?>">
                      <button type="submit" class="btn-remove-coche" title="Eliminar vehículo"
                        onclick="return confirm('¿Eliminar este vehículo de tu perfil?')">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- Acordeón: Añadir coche -->
            <button class="acc-toggle" style="margin: 0 -20px; width: calc(100% + 40px);" onclick="toggleAcc('acc-car', this)">
              <span><i class="fas fa-plus me-2"></i>Añadir vehículo</span>
              <i class="fas fa-chevron-down chevron"></i>
            </button>
            <div class="acc-body add-car-section" id="acc-car" style="margin: 0 -20px; padding: 16px 20px 4px;">
              <form method="POST" action="">
                <input type="hidden" name="accion" value="add_coche">
                <input type="hidden" name="motorizacion_id" id="motId" value="">

                <div class="mb-2">
                  <label class="field-label mb-1 d-block">Marca</label>
                  <select class="form-select form-select-tuning" id="pMarca" onchange="cargarModelos()">
                    <option value="" disabled selected>Selecciona marca</option>
                    <?php foreach ($marcas as $m): ?>
                      <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mb-2">
                  <label class="field-label mb-1 d-block">Modelo</label>
                  <select class="form-select form-select-tuning" id="pModelo" disabled onchange="cargarMotorizaciones()">
                    <option value="" disabled selected>Primero selecciona marca</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="field-label mb-1 d-block">Motorización</label>
                  <select class="form-select form-select-tuning" id="pMotor" disabled onchange="document.getElementById('motId').value=this.value">
                    <option value="" disabled selected>Primero selecciona modelo</option>
                  </select>
                </div>

                <button type="submit" class="btn-primary-tuning w-100" id="btnAddCar" disabled>
                  <i class="fas fa-plus me-1"></i> Añadir vehículo
                </button>
              </form>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
// ── Acordeón ───────────────────────────────────────────
function toggleAcc(id, btn) {
  const body = document.getElementById(id);
  const open = body.classList.toggle('open');
  btn.classList.toggle('open', open);
}

// ── Cascada de selects: marca → modelo → motorización ──
function cargarModelos() {
  const marcaId = document.getElementById('pMarca').value;
  const selMod  = document.getElementById('pModelo');
  const selMot  = document.getElementById('pMotor');
  const btnAdd  = document.getElementById('btnAddCar');

  selMod.innerHTML = '<option selected disabled>Cargando...</option>';
  selMod.disabled  = true;
  selMot.innerHTML = '<option selected disabled>Primero selecciona modelo</option>';
  selMot.disabled  = true;
  btnAdd.disabled  = true;
  document.getElementById('motId').value = '';

  fetch(`public/ajax/get_modelos.php?marca_id=${marcaId}`)
    .then(r => r.json())
    .then(data => {
      selMod.innerHTML = '<option value="" disabled selected>Selecciona modelo</option>';
      data.forEach(m => {
        const o = document.createElement('option');
        o.value = m.id; o.textContent = m.nombre;
        selMod.appendChild(o);
      });
      selMod.disabled = data.length === 0;
    })
    .catch(() => selMod.innerHTML = '<option disabled>Error al cargar</option>');
}

function cargarMotorizaciones() {
  const modeloId = document.getElementById('pModelo').value;
  const selMot   = document.getElementById('pMotor');
  const btnAdd   = document.getElementById('btnAddCar');

  selMot.innerHTML = '<option selected disabled>Cargando...</option>';
  selMot.disabled  = true;
  btnAdd.disabled  = true;
  document.getElementById('motId').value = '';

  fetch(`public/ajax/get_motorizaciones.php?modelo_id=${modeloId}`)
    .then(r => r.json())
    .then(data => {
      selMot.innerHTML = '<option value="" disabled selected>Selecciona motorización</option>';
      data.forEach(m => {
        const o = document.createElement('option');
        o.value = m.id; o.textContent = m.texto || m.nombre;
        selMot.appendChild(o);
      });
      selMot.disabled = data.length === 0;
    })
    .catch(() => selMot.innerHTML = '<option disabled>Error al cargar</option>');
}

// Habilitar botón cuando se selecciona motorización
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('pMotor').addEventListener('change', function() {
    document.getElementById('btnAddCar').disabled = !this.value;
  });

  <?php if ($msg && $msg['tipo'] === 'danger'): ?>
    // Re-abrir el acordeón si hubo error en contraseña
    const bodyPwd = document.getElementById('acc-pwd');
    const btnPwd  = document.querySelector('[onclick="toggleAcc(\'acc-pwd\', this)"]');
    if (bodyPwd && btnPwd) { bodyPwd.classList.add('open'); btnPwd.classList.add('open'); }
  <?php endif; ?>
});
</script>
