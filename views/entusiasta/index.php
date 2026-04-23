<style>
  .hero-bg { 
    background: linear-gradient(rgba(0,0,0,0.60), rgba(0,0,0,0.60)), 
                url('https://images.unsplash.com/photo-1492144534652-916b3d5f0f1f?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; 
    min-height: 100vh;
  }
  .card-tuning { transition: all 0.3s ease; }
  .card-tuning:hover { transform: translateY(-10px); box-shadow: 0 20px 35px rgba(255, 136, 0, 0.4); }
  .accent-orange {color: #ffffff;}
  .badge-entusiasta { background: linear-gradient(45deg, #ff8800, #ffc107); }
  .btn-all { transition: all 0.3s ease; }
  .btn-all:hover { transform: translateY(-3px); }
  .clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

  .mis-coches-section {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    padding: 20px 22px;
    margin-bottom: 28px;
  }
  .mis-coches-title {
    font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; color: rgba(255,255,255,0.5);
    margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
  }
  .mis-coches-title::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.1); }
  .coche-btn {
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);
    border-radius: 12px; padding: 12px 16px; color: #fff;
    display: flex; align-items: center; gap: 12px;
    cursor: pointer; transition: all 0.25s ease; text-align: left; width: 100%;
  }
  .coche-btn:hover { background: rgba(255,136,0,0.2); border-color: rgba(255,136,0,0.5); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(255,136,0,0.2); }
  .coche-btn.activo { background: linear-gradient(135deg, rgba(255,136,0,0.5), rgba(255,193,7,0.3)); border-color: rgba(255,193,7,0.7); box-shadow: 0 4px 16px rgba(255,136,0,0.3); }
  .coche-btn-icon { width: 38px; height: 38px; flex-shrink: 0; background: linear-gradient(135deg, #ff8800, #ffc107); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
  .coche-btn-marca { font-weight: 700; font-size: 0.9rem; line-height: 1.2; }
  .coche-btn-motor { font-size: 0.75rem; color: rgba(255,255,255,0.55); margin-top: 1px; }
  .divider-o { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.35); font-size: 0.8rem; font-weight: 600; margin-bottom: 20px; }
  .divider-o::before, .divider-o::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.12); }
</style>

<section class="hero-bg text-white"
style="background: url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; min-height:100vh; padding-bottom: 60px; position: relative;">
  <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50" style="z-index:0;"></div>
<div class="container py-5 position-relative" style="z-index:1;">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="text-center mb-5">
          <h1 class="display-3 fw-bold mb-3">Modo <span class="accent-orange">Entusiasta</span></h1>
          <p class="lead text-white-50">Más profundidad, más piezas y tutoriales ampliados</p>
        </div>

        <?php if (!empty($cochesUsuario)): ?>
        <!-- ── MIS COCHES GUARDADOS ── -->
        <div class="mis-coches-section">
          <div class="mis-coches-title">
            <i class="fas fa-car"></i> Elige tu coche
          </div>
          <div class="row g-2">
            <?php foreach ($cochesUsuario as $c): ?>
              <?php
                $motId    = (int) $c['motorizacion_id'];
                $label    = htmlspecialchars($c['marca_nombre'] . ' ' . $c['modelo_nombre']);
                $sublabel = htmlspecialchars($c['motor_nombre'] . ($c['tipo_combustible'] ? ' · ' . $c['tipo_combustible'] : '') . ($c['potencia'] ? ' · ' . $c['potencia'] : ''));
                $vehiculoJS = htmlspecialchars($c['marca_nombre'] . ' ' . $c['modelo_nombre'] . ' · ' . $c['motor_nombre'], ENT_QUOTES);
              ?>
              <div class="col-sm-6 col-lg-4">
                <button class="coche-btn" id="coche-<?= $motId ?>" onclick="elegirCoche(<?= $motId ?>, '<?= $vehiculoJS ?>')">
                  <div class="coche-btn-icon"><i class="fas fa-car"></i></div>
                  <div>
                    <div class="coche-btn-marca"><?= $label ?></div>
                    <div class="coche-btn-motor"><?= $sublabel ?></div>
                  </div>
                </button>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="divider-o">o busca manualmente</div>
        <?php endif; ?>

        <!-- Selectores -->
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
        <div id="resultados" class="d-none">
          <!-- Spinner -->
          <div class="spinner-wrap text-center mb-4" id="spinner" style="display: none;">
            <div class="spinner-border text-warning" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <span class="badge badge-entusiasta fs-5 px-4 py-2" id="vehiculo-badge">─</span>
            <span class="text-white-50">Contenido ampliado para entusiastas</span>
          </div>

          <div class="row">
            <!-- Manuales -->
            <div class="col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="accent-orange"><i class="fas fa-file-pdf me-2"></i> Manuales recomendados</h3>
              </div>
              <div id="lista-manuales"></div>
            </div>

            <!-- Piezas -->
            <div class="col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="accent-orange"><i class="fas fa-cog me-2"></i> Piezas compatibles</h3>
                <a href="todas-piezas.php" class="btn btn-outline-light btn-all">
                  <i class="fas fa-list me-1"></i> Ver todas las piezas
                </a>
              </div>
              <div class="row g-4" id="lista-piezas"></div>
            </div>
          </div>

          <div id="sin-resultados" class="d-none text-center py-5">
            <p class="text-white-50 fs-4">No hay contenido disponible para esta motorización aún.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// ==================== JAVASCRIPT PARA ENTUSIASTA ====================

const marcaSelect  = document.getElementById('marcaSelect');
const modeloSelect = document.getElementById('modeloSelect');
const motorSelect  = document.getElementById('motorSelect');
const resultados   = document.getElementById('resultados');
const spinner      = document.getElementById('spinner');

function setLoading(on) {
  spinner.style.display = on ? 'block' : 'none';
  resultados.classList.toggle('cargando', on);
}

function esc(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function cardManual(m) {
  const url   = m.archivo_url || '#';
  const pieza = m.pieza_nombre ? `<span class="text-white-50 small">Pieza: ${esc(m.pieza_nombre)}</span>` : '';
  return `
    <div style="background:#1a1a2e;border:1px solid rgba(255,136,0,0.25);border-radius:12px;padding:14px;margin-bottom:12px;display:flex;gap:12px;align-items:flex-start;">
      <div style="width:40px;height:40px;flex-shrink:0;background:rgba(255,136,0,0.15);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#ff8800;">
        <i class="fas fa-file-pdf"></i>
      </div>
      <div style="flex:1;overflow:hidden;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:4px;">
          <span class="fw-bold text-white clamp-2">${esc(m.titulo)}</span>
          <a href="${esc(url)}" target="_blank" rel="noopener"
             class="btn btn-sm btn-outline-warning" style="border-radius:50px;font-size:.75rem;flex-shrink:0;">
            <i class="fas fa-download me-1"></i>PDF
          </a>
        </div>
        ${pieza}
        ${m.fuente ? `<span style="font-size:.7rem;background:rgba(255,255,255,0.08);color:#aaa;border-radius:50px;padding:2px 10px;">${esc(m.fuente)}</span>` : ''}
      </div>
    </div>`;
}

function cardPieza(p) {
  const img = p.imagen || 'https://via.placeholder.com/300x180?text=Sin+imagen';
  const desc = (p.descripcion || '').substring(0, 80);
  return `
    <div class="col-12 col-sm-6">
      <div class="card bg-dark text-white border-0 shadow card-tuning h-100">
        <img src="${esc(img)}" class="card-img-top" style="height:160px;object-fit:cover;" alt="${esc(p.nombre)}">
        <div class="card-body d-flex flex-column p-3">
          <span class="badge bg-warning mb-2 align-self-start">${esc(p.referencia)}</span>
          <h6 class="card-title clamp-2">${esc(p.nombre)}</h6>
          <p class="card-text small text-muted mt-auto mb-0">${esc(desc)}${p.descripcion && p.descripcion.length > 80 ? '…' : ''}</p>
        </div>
      </div>
    </div>`;
}

// ── Clic en coche guardado ──
function elegirCoche(motId, vehiculo) {
  document.querySelectorAll('.coche-btn').forEach(b => b.classList.remove('activo'));
  const btn = document.getElementById('coche-' + motId);
  if (btn) btn.classList.add('activo');
  marcaSelect.selectedIndex = 0;
  modeloSelect.innerHTML = '<option selected disabled>Primero selecciona marca</option>';
  modeloSelect.disabled  = true;
  motorSelect.innerHTML  = '<option selected disabled>Primero selecciona modelo</option>';
  motorSelect.disabled   = true;
  cargarResultados(motId, vehiculo);
}

// Los mismos eventos que en principiantes (marca → modelo → motorización → resultados)
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
    });
});

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
    });
});

motorSelect.addEventListener('change', () => {
  const motId    = motorSelect.value;
  const marcaTxt = marcaSelect.options[marcaSelect.selectedIndex]?.text || '';
  const modelTxt = modeloSelect.options[modeloSelect.selectedIndex]?.text || '';
  const motorTxt = motorSelect.options[motorSelect.selectedIndex]?.text || '';
  cargarResultados(motId, `${marcaTxt} ${modelTxt} · ${motorTxt}`);
});

function cargarResultados(motId, vehiculo) {
  resultados.classList.remove('d-none');
  setLoading(true);

  document.getElementById('vehiculo-badge').textContent = vehiculo;
  document.getElementById('lista-manuales').innerHTML = '';
  document.getElementById('lista-piezas').innerHTML   = '';
  document.getElementById('sin-resultados').classList.add('d-none');

  resultados.scrollIntoView({ behavior: 'smooth', block: 'start' });

  fetch(`public/ajax/get_resultados_entusiasta.php?motorizacion_id=${motId}`)
    .then(r => r.json())
    .then(data => {
      setLoading(false);
      const manuales = data.manuales || [];
      const piezas   = data.piezas   || [];

      if (manuales.length === 0 && piezas.length === 0) {
        document.getElementById('sin-resultados').classList.remove('d-none');
        return;
      }

      document.getElementById('lista-manuales').innerHTML =
        manuales.length
          ? manuales.map(cardManual).join('')
          : '<p class="text-white-50 small">No hay manuales para esta motorización.</p>';
      document.getElementById('lista-piezas').innerHTML =
        piezas.length
          ? `<div class="row g-3">${piezas.map(cardPieza).join('')}</div>`
          : '<p class="text-white-50 small">No hay piezas para esta motorización.</p>';
    })
    .catch(() => {
      setLoading(false);
      document.getElementById('lista-manuales').innerHTML = '<p class="text-danger">Error al cargar los resultados.</p>';
    });
}
</script>