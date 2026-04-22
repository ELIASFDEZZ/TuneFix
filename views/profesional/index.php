<style>
  .card-tuning { 
    transition: all 0.4s ease; 
  }
  .card-tuning:hover { 
    transform: scale(1.03) translateY(-12px); 
    box-shadow: 0 25px 50px rgba(255, 60, 0, 0.5); 
  }
  .accent-red { color: #ffffff; }
  .badge-pro { 
    background: linear-gradient(45deg, #ff3c00, #ff0000); 
  }
  .btn-all {
    transition: all 0.3s ease;
  }
  .btn-all:hover {
    transform: translateY(-3px);
  }
  .clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

  /* Tarjeta distribuidor */
  .card-dist {
    background: #1a1a2e;
    border: 1px solid rgba(255,60,0,0.2);
    border-radius: 12px;
    transition: all .3s;
  }
  .card-dist:hover {
    border-color: #ff3c00;
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(255,60,0,0.25);
  }
  .dist-logo {
    width: 42px; height: 42px;
    background: rgba(255,60,0,0.15);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: #ff3c00; flex-shrink: 0;
  }

  /* Tarjeta manual */
  .card-manual {
    background: #12121f;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    transition: all .3s;
  }
  .card-manual:hover {
    border-color: rgba(255,60,0,0.4);
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.4);
  }
  .manual-icon {
    width: 42px; height: 42px;
    background: rgba(255,255,255,0.06);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #aaa; flex-shrink: 0;
  }

  .tag-fuente {
    font-size: .7rem;
    background: rgba(255,255,255,0.08);
    color: #aaa;
    border-radius: 50px;
    padding: 2px 10px;
  }
</style>

<section style="background: linear-gradient(rgba(0,0,0,0.30), rgba(0,0,0,0.30)), url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; min-height:100vh; padding-bottom: 60px;">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-11">

          <!-- Cabecera -->
          <div class="text-center mb-5 text-white">
            <h1 class="display-3 fw-bold mb-3">Modo <span class="accent-red">Profesional</span></h1>
            <p class="lead text-white-50">Acceso total · Distribuidores reales · Manuales técnicos · Sin límites</p>
          </div>

          <!-- Selectores en cascada -->
          <div class="row g-4 mb-5">
            <div class="col-md-4">
              <label class="form-label fw-bold text-white-50">MARCA</label>
              <select class="form-select form-select-lg" id="marcaSelect">
                <option selected disabled>Selecciona marca</option>
                <?php foreach ($marcas as $marca): ?>
                  <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold text-white-50">MODELO</label>
              <select class="form-select form-select-lg" id="modeloSelect" disabled>
                <option selected disabled>Primero selecciona marca</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold text-white-50">MOTORIZACIÓN</label>
              <select class="form-select form-select-lg" id="motorSelect" disabled>
                <option selected disabled>Primero selecciona modelo</option>
              </select>
            </div>
          </div>

          <!-- Resultados -->
          <div id="resultados" class="d-none text-white">

            <!-- Spinner -->
            <div class="text-center mb-4" id="spinner" style="display:none!important;">
              <div class="spinner-border text-danger" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
            </div>

            <!-- Badge vehículo -->
            <div class="d-flex align-items-center gap-3 mb-4">
              <span class="badge badge-pro fs-5 px-4 py-2" id="vehiculo-badge">─</span>
              <span class="text-white-50">Información profesional completa</span>
            </div>

            <div class="row g-4">

              <!-- ── Distribuidores de piezas ── -->
              <div class="col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h3 class="accent-red mb-0">
                    <i class="fas fa-store me-2"></i> Distribuidores de piezas
                  </h3>
                </div>
                <div id="lista-distribuidores">
                  <!-- Cards inyectadas por JS -->
                </div>
              </div>

              <!-- ── Manuales técnicos ── -->
              <div class="col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h3 class="accent-red mb-0">
                    <i class="fas fa-file-pdf me-2"></i> Manuales técnicos
                  </h3>
                </div>
                <div id="lista-manuales">
                  <!-- Cards inyectadas por JS -->
                </div>
              </div>

            </div><!-- /row -->

            <!-- Sin resultados -->
            <div id="sin-resultados" class="d-none text-center py-5">
              <i class="fas fa-search fa-3x text-white-50 mb-3"></i>
              <p class="text-white-50 fs-4">No hay resultados para esta motorización.</p>
            </div>

          </div><!-- /resultados -->

        </div>
      </div>
    </div>
</section>

<script>
// ==================== JS PROFESIONAL ====================

const marcaSelect  = document.getElementById('marcaSelect');
const modeloSelect = document.getElementById('modeloSelect');
const motorSelect  = document.getElementById('motorSelect');
const resultados   = document.getElementById('resultados');
const spinner      = document.getElementById('spinner');

function setLoading(on) {
  spinner.style.setProperty('display', on ? 'block' : 'none', 'important');
}

function esc(str) {
  return String(str ?? '')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Card distribuidor ─────────────────────────────────────────────────────────
function cardDistribuidor(d) {
  const url = d.url_directa || d.url_base || '#';
  return `
    <div class="card-dist p-3 mb-3 d-flex gap-3 align-items-start">
      <div class="dist-logo"><i class="fas fa-store"></i></div>
      <div class="flex-grow-1 overflow-hidden">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="fw-bold text-white">${esc(d.distribuidor_nombre)}</span>
          <a href="${esc(url)}" target="_blank" rel="noopener"
             class="btn btn-sm btn-outline-danger" style="border-radius:50px;font-size:.75rem;">
            <i class="fas fa-external-link-alt me-1"></i>Ver
          </a>
        </div>
        <p class="mb-1 small text-white-50 clamp-2">${esc(d.nombre_pieza_dist || d.pieza_nombre)}</p>
        <span class="badge bg-danger" style="font-size:.7rem;">${esc(d.referencia)}</span>
      </div>
    </div>`;
}

// ── Card manual ───────────────────────────────────────────────────────────────
function cardManual(m) {
  const url   = m.archivo_url || '#';
  const pieza = m.pieza_nombre ? `<span class="text-white-50 small">Pieza: ${esc(m.pieza_nombre)}</span>` : '';
  return `
    <div class="card-manual p-3 mb-3 d-flex gap-3 align-items-start">
      <div class="manual-icon"><i class="fas fa-file-pdf"></i></div>
      <div class="flex-grow-1 overflow-hidden">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <span class="fw-bold text-white clamp-2">${esc(m.titulo)}</span>
          <a href="${esc(url)}" target="_blank" rel="noopener"
             class="btn btn-sm btn-outline-light ms-2" style="border-radius:50px;font-size:.75rem;flex-shrink:0;">
            <i class="fas fa-download me-1"></i>PDF
          </a>
        </div>
        ${pieza}
        ${m.fuente ? `<span class="tag-fuente mt-1 d-inline-block">${esc(m.fuente)}</span>` : ''}
      </div>
    </div>`;
}

// ── Marca → Modelos ───────────────────────────────────────────────────────────
marcaSelect.addEventListener('change', () => {
  const marcaId = marcaSelect.value;
  modeloSelect.innerHTML = '<option selected disabled>Cargando modelos...</option>';
  motorSelect.innerHTML  = '<option selected disabled>Primero selecciona un modelo</option>';
  modeloSelect.disabled = true;
  motorSelect.disabled  = true;
  resultados.classList.add('d-none');

  fetch(`public/ajax/get_modelos.php?marca_id=${marcaId}`)
    .then(r => r.json())
    .then(data => {
      modeloSelect.innerHTML = '<option selected disabled>Selecciona un modelo</option>';
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.nombre;
        modeloSelect.appendChild(opt);
      });
      modeloSelect.disabled = data.length === 0;
    })
    .catch(() => modeloSelect.innerHTML = '<option selected disabled>Error al cargar</option>');
});

// ── Modelo → Motorizaciones ───────────────────────────────────────────────────
modeloSelect.addEventListener('change', () => {
  const modeloId = modeloSelect.value;
  motorSelect.innerHTML = '<option selected disabled>Cargando motorizaciones...</option>';
  motorSelect.disabled = true;
  resultados.classList.add('d-none');

  fetch(`public/ajax/get_motorizaciones.php?modelo_id=${modeloId}`)
    .then(r => r.json())
    .then(data => {
      motorSelect.innerHTML = '<option selected disabled>Selecciona una motorización</option>';
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.texto || m.nombre;
        motorSelect.appendChild(opt);
      });
      motorSelect.disabled = data.length === 0;
    })
    .catch(() => motorSelect.innerHTML = '<option selected disabled>Error al cargar</option>');
});

// ── Motorización → Distribuidores + Manuales ─────────────────────────────────
motorSelect.addEventListener('change', () => {
  const motId    = motorSelect.value;
  const marcaTxt = marcaSelect.options[marcaSelect.selectedIndex]?.text || '';
  const modelTxt = modeloSelect.options[modeloSelect.selectedIndex]?.text || '';
  const motorTxt = motorSelect.options[motorSelect.selectedIndex]?.text || '';

  resultados.classList.remove('d-none');
  setLoading(true);

  document.getElementById('vehiculo-badge').textContent = `${marcaTxt} ${modelTxt} · ${motorTxt}`;
  document.getElementById('lista-distribuidores').innerHTML = '';
  document.getElementById('lista-manuales').innerHTML = '';
  document.getElementById('sin-resultados').classList.add('d-none');

  fetch(`public/ajax/get_resultados_pro.php?motorizacion_id=${motId}`)
    .then(r => r.json())
    .then(data => {
      setLoading(false);

      const distribuidores = data.distribuidores || [];
      const manuales       = data.manuales       || [];

      if (distribuidores.length === 0 && manuales.length === 0) {
        document.getElementById('sin-resultados').classList.remove('d-none');
        return;
      }

      document.getElementById('lista-distribuidores').innerHTML =
        distribuidores.length
          ? distribuidores.map(cardDistribuidor).join('')
          : '<p class="text-white-50 small">No hay distribuidores para esta motorización.</p>';

      document.getElementById('lista-manuales').innerHTML =
        manuales.length
          ? manuales.map(cardManual).join('')
          : '<p class="text-white-50 small">No hay manuales para esta motorización.</p>';
    })
    .catch(() => {
      setLoading(false);
      document.getElementById('lista-distribuidores').innerHTML =
        '<p class="text-danger">Error al cargar los resultados.</p>';
    });
});
</script> 