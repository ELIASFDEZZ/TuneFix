<style>
  .hero-bg { 
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.85)), 
    url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; 
    min-height: 100vh;
  }
  .card-tuning { transition: all 0.3s ease; }
  .card-tuning:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(255, 60, 0, 0.3); }
  .accent-red { color: #ffffff; }
  .badge-tuning { background: linear-gradient(45deg, #ff3c00, #ff8800); }
  .btn-all { transition: all 0.3s ease; }
  .btn-all:hover { transform: translateY(-3px); }
  .clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<section class="hero-bg text-white"
style="background: url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; min-height:100vh; padding-bottom: 60px;">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="text-center mb-5">
          <h1 class="display-3 fw-bold mb-3">Modo <span class="accent-red">Principiantes</span></h1>
          <p class="lead text-white-50">Empieza tu aventura en el tuning de forma sencilla y segura</p>
        </div>

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
            <div class="spinner-border text-light" role="status">
              <span class="visually-hidden">Cargando...</span>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <span class="badge badge-tuning fs-5 px-4 py-2" id="vehiculo-badge">─</span>
            <span class="text-white-50">Resultados recomendados para tu vehículo</span>
          </div>

          <div class="row">
            <!-- Tutoriales -->
            <div class="col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="accent-red"><i class="fas fa-play-circle me-2"></i> Tutoriales para ti</h3>
                <a href="todos-tutoriales.php" class="btn btn-outline-light btn-all">
                  <i class="fas fa-list me-1"></i> Ver todos los tutoriales
                </a>
              </div>
              <div class="row g-4" id="lista-tutoriales"></div>
            </div>

            <!-- Piezas -->
            <div class="col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="accent-red"><i class="fas fa-cog me-2"></i> Piezas compatibles</h3>
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
// ==================== JAVASCRIPT CORREGIDO Y OPTIMIZADO ====================

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

function cardTutorial(t) {
  const img = t.imagen || 'https://via.placeholder.com/300x180?text=Sin+imagen';
  return `
    <div class="col-12 col-sm-6">
      <div class="card bg-dark text-white border-0 shadow card-tuning h-100">
        <img src="${esc(img)}" class="card-img-top" style="height:160px;object-fit:cover;" alt="${esc(t.titulo)}">
        <div class="card-body d-flex flex-column p-3">
          <h6 class="card-title clamp-2">${esc(t.titulo)}</h6>
          <p class="card-text small text-muted mt-auto mb-0">Pieza: ${esc(t.pieza_nombre || 'General')}</p>
        </div>
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
          <span class="badge bg-danger mb-2 align-self-start">${esc(p.referencia)}</span>
          <h6 class="card-title clamp-2">${esc(p.nombre)}</h6>
          <p class="card-text small text-muted mt-auto mb-0">${esc(desc)}${p.descripcion && p.descripcion.length > 80 ? '…' : ''}</p>
        </div>
      </div>
    </div>`;
}

// Marca → Modelos
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

// Modelo → Motorizaciones
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

// Motorización → Resultados
motorSelect.addEventListener('change', () => {
  const motId = motorSelect.value;
  const marcaTxt = marcaSelect.options[marcaSelect.selectedIndex]?.text || '';
  const modelTxt = modeloSelect.options[modeloSelect.selectedIndex]?.text || '';
  const motorTxt = motorSelect.options[motorSelect.selectedIndex]?.text || '';

  resultados.classList.remove('d-none');
  setLoading(true);

  document.getElementById('vehiculo-badge').textContent = `${marcaTxt} ${modelTxt} · ${motorTxt}`;

  document.getElementById('lista-tutoriales').innerHTML = '';
  document.getElementById('lista-piezas').innerHTML = '';
  document.getElementById('sin-resultados').classList.add('d-none');

  fetch(`public/ajax/get_resultados.php?motorizacion_id=${motId}`)
    .then(r => r.json())
    .then(data => {
      setLoading(false);

      const tutoriales = data.tutoriales || [];
      const piezas     = data.piezas     || [];

      if (tutoriales.length === 0 && piezas.length === 0) {
        document.getElementById('sin-resultados').classList.remove('d-none');
        return;
      }

      document.getElementById('lista-tutoriales').innerHTML = tutoriales.slice(0, 2).map(cardTutorial).join('');
      document.getElementById('lista-piezas').innerHTML     = piezas.slice(0, 2).map(cardPieza).join('');
    })
    .catch(() => {
      setLoading(false);
      document.getElementById('lista-tutoriales').innerHTML = '<p class="text-danger">Error al cargar los resultados.</p>';
    });
});
</script>