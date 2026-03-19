<style>
  .overlay        { background: rgba(0,0,0,0.60); }
  .card-img-top   { height: 180px; object-fit: cover; }
  #resultados     { transition: opacity .3s; }
  #resultados.cargando { opacity: .4; pointer-events: none; }
  .spinner-wrap   { display: none; }
  .spinner-wrap.activo { display: flex; justify-content: center; padding: 2rem 0; }
</style>

<section class="position-relative text-white d-flex align-items-start"
  style="background:url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; min-height:100vh;">

  <div class="position-absolute top-0 start-0 w-100 h-100 overlay"></div>

  <div class="container position-relative py-5">

    <!-- ── Título + Selectores ───────────────────────────────────── -->
    <h1 class="display-4 mb-4">Principiantes</h1>
    <p class="mb-4 text-white-50">Selecciona tu vehículo para ver las piezas y tutoriales recomendados.</p>

    <div class="row g-3 mb-5">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Marca</label>
        <select class="form-select" id="marcaSelect">
          <option selected disabled>Selecciona una marca</option>
          <?php foreach ($marcas as $marca): ?>
            <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Modelo</label>
        <select class="form-select" id="modeloSelect" disabled>
          <option selected disabled>Primero selecciona una marca</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Motorización</label>
        <select class="form-select" id="motorSelect" disabled>
          <option selected disabled>Primero selecciona un modelo</option>
        </select>
      </div>
    </div>

    <!-- ── Resultados dinámicos ───────────────────────────────────── -->
    <div id="resultados" class="d-none">

      <!-- Spinner de carga -->
      <div class="spinner-wrap" id="spinner">
        <div class="spinner-border text-light" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>

      <!-- Cabecera con el vehículo seleccionado -->
      <div class="d-flex align-items-center gap-3 mb-4">
        <span class="badge bg-danger fs-6 px-3 py-2" id="vehiculo-badge">─</span>
        <span class="text-white-50 small">Resultados para tu vehículo</span>
      </div>

      <!-- Tutoriales -->
      <h3 class="mb-3">
        <span class="me-2"></span>Tutoriales

      </h3>
      <div class="row g-3 mb-5" id="lista-tutoriales">
        <!-- se rellena por JS -->
      </div>

      <!-- Piezas -->
      <h3 class="mb-3">
        <span class="me-2"></span>Piezas compatibles

      </h3>
      <div class="row g-3" id="lista-piezas">
        <!-- se rellena por JS -->
      </div>

      <!-- Mensaje sin resultados -->
      <div id="sin-resultados" class="d-none text-center py-5">
        <p class="text-white-50 fs-5">No hay piezas ni tutoriales registrados para esta motorización todavía.</p>
      </div>

    </div>
    <!-- /resultados -->

  </div>
</section>

<script>
const marcaSelect  = document.getElementById('marcaSelect');
const modeloSelect = document.getElementById('modeloSelect');
const motorSelect  = document.getElementById('motorSelect');
const resultados   = document.getElementById('resultados');
const spinner      = document.getElementById('spinner');

// ── Helpers ──────────────────────────────────────────────────────────────────

function setLoading(on) {
  spinner.classList.toggle('activo', on);
  resultados.classList.toggle('cargando', on);
}

function cardTutorial(t) {
  const img = t.imagen || 'https://via.placeholder.com/300x180?text=Sin+imagen';
  return `
    <div class="col-md-6 col-lg-4">
      <div class="card bg-dark text-white border-0 shadow h-100">
        <img src="${esc(img)}" class="card-img-top" alt="${esc(t.titulo)}">
        <div class="card-body">
          <h6 class="card-title">${esc(t.titulo)}</h6>
          <p class="card-text small text-muted mb-0">Pieza: ${esc(t.pieza_nombre || 'General')}</p>
        </div>
      </div>
    </div>`;
}

function cardPieza(p) {
  const img = p.imagen || 'https://via.placeholder.com/300x180?text=Sin+imagen';
  const desc = (p.descripcion || '').substring(0, 70);
  return `
    <div class="col-md-6 col-lg-4">
      <div class="card bg-dark text-white border-0 shadow-sm h-100">
        <img src="${esc(img)}" class="card-img-top" alt="${esc(p.nombre)}">
        <div class="card-body p-3">
          <span class="badge bg-danger mb-1">${esc(p.referencia)}</span>
          <h6 class="card-title small mb-1">${esc(p.nombre)}</h6>
          <p class="card-text small text-muted mb-0">${esc(desc)}${desc.length === 70 ? '…' : ''}</p>
        </div>
      </div>
    </div>`;
}

function esc(str) {
  return String(str ?? '')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Marca → Modelos ───────────────────────────────────────────────────────────

marcaSelect.addEventListener('change', () => {
  const marcaId = marcaSelect.value;

  modeloSelect.innerHTML = '<option selected disabled>Cargando modelos…</option>';
  motorSelect.innerHTML  = '<option selected disabled>Primero selecciona un modelo</option>';
  modeloSelect.disabled  = true;
  motorSelect.disabled   = true;
  resultados.classList.add('d-none');

  fetch(`public/ajax/get_modelos.php?marca_id=${marcaId}`)
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
    .then(data => {
      modeloSelect.innerHTML = '<option selected disabled>Selecciona un modelo</option>';
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id; opt.textContent = m.nombre;
        modeloSelect.appendChild(opt);
      });
      modeloSelect.disabled = data.length === 0;
    })
    .catch(() => {
      modeloSelect.innerHTML = '<option selected disabled>Error al cargar modelos</option>';
    });
});

// ── Modelo → Motorizaciones ────────────────────────────────────────────────────

modeloSelect.addEventListener('change', () => {
  const modeloId = modeloSelect.value;

  motorSelect.innerHTML = '<option selected disabled>Cargando motorizaciones…</option>';
  motorSelect.disabled  = true;
  resultados.classList.add('d-none');

  fetch(`public/ajax/get_motorizaciones.php?modelo_id=${modeloId}`)
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
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
    .catch(() => {
      motorSelect.innerHTML = '<option selected disabled>Error al cargar motorizaciones</option>';
    });
});

// ── Motorización → Piezas + Tutoriales ────────────────────────────────────────

motorSelect.addEventListener('change', () => {
  const motId    = motorSelect.value;
  const marcaTxt = marcaSelect.options[marcaSelect.selectedIndex].text;
  const modelTxt = modeloSelect.options[modeloSelect.selectedIndex].text;
  const motorTxt = motorSelect.options[motorSelect.selectedIndex].text;

  // Mostrar sección y activar spinner
  resultados.classList.remove('d-none');
  setLoading(true);
  document.getElementById('vehiculo-badge').textContent = `${marcaTxt} ${modelTxt} · ${motorTxt}`;
  document.getElementById('lista-tutoriales').innerHTML = '';
  document.getElementById('lista-piezas').innerHTML     = '';
  document.getElementById('sin-resultados').classList.add('d-none');

  const modeloId = modeloSelect.value;
  fetch(`public/ajax/get_resultados.php?motorizacion_id=${motId}&modelo_id=${modeloId}`)
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
    .then(data => {
      setLoading(false);

      const tutoriales = data.tutoriales || [];
      const piezas     = data.piezas     || [];

      if (tutoriales.length === 0 && piezas.length === 0) {
        document.getElementById('sin-resultados').classList.remove('d-none');
        return;
      }

      document.getElementById('lista-tutoriales').innerHTML =
        tutoriales.length ? tutoriales.map(cardTutorial).join('') : '<p class="text-white-50 small">No hay tutoriales para esta motorización.</p>';

      document.getElementById('lista-piezas').innerHTML =
        piezas.length ? piezas.map(cardPieza).join('') : '<p class="text-white-50 small">No hay piezas registradas para esta motorización.</p>';
    })
    .catch(() => {
      setLoading(false);
      document.getElementById('lista-tutoriales').innerHTML = '<p class="text-danger">Error al cargar los resultados.</p>';
    });
});
</script>
