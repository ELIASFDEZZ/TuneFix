<style>
  .hero-bg {
    background: linear-gradient(rgba(0,0,0,0.60), rgba(0,0,0,0.60)),
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

  /* ── Sección "Elige tu coche" ── */
  .mis-coches-section {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    padding: 20px 22px;
    margin-bottom: 28px;
  }
  .mis-coches-title {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.5);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .mis-coches-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.1);
  }
  .coche-btn {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 12px;
    padding: 12px 16px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: left;
    width: 100%;
  }
  .coche-btn:hover {
    background: rgba(255,60,0,0.2);
    border-color: rgba(255,60,0,0.5);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(255,60,0,0.2);
  }
  .coche-btn.activo {
    background: linear-gradient(135deg, rgba(164,4,46,0.6), rgba(255,136,0,0.4));
    border-color: rgba(255,136,0,0.7);
    box-shadow: 0 4px 16px rgba(255,60,0,0.3);
  }
  .coche-btn-icon {
    width: 38px; height: 38px; flex-shrink: 0;
    background: linear-gradient(135deg, rgb(164,4,46), #ff8800);
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
  }
  .coche-btn-marca  { font-weight: 700; font-size: 0.9rem; line-height: 1.2; }
  .coche-btn-motor  { font-size: 0.75rem; color: rgba(255,255,255,0.55); margin-top: 1px; }
  .divider-o {
    display: flex; align-items: center; gap: 12px;
    color: rgba(255,255,255,0.35); font-size: 0.8rem; font-weight: 600;
    margin-bottom: 20px;
  }
  .divider-o::before, .divider-o::after {
    content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.12);
  }

  /* ── YouTube tutorial card ── */
  .yt-img-wrap { position: relative; overflow: hidden; }
  .yt-play-overlay {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0);
    transition: background .3s ease;
  }
  .card-tuning:hover .yt-play-overlay { background: rgba(0,0,0,0.35); }
  .yt-play-btn {
    width: 48px; height: 48px;
    background: #ff0000; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: scale(0.7);
    transition: opacity .25s ease, transform .25s ease;
    box-shadow: 0 4px 16px rgba(255,0,0,.5);
  }
  .yt-play-btn i { color: #fff; font-size: 1.1rem; margin-left: 3px; }
  .card-tuning:hover .yt-play-btn { opacity: 1; transform: scale(1); }
  .yt-badge-overlay {
    position: absolute; bottom: 6px; right: 6px;
    background: rgba(0,0,0,.75); color: #fff;
    font-size: .62rem; font-weight: 700; letter-spacing: .4px;
    padding: 2px 6px; border-radius: 4px;
    display: flex; align-items: center; gap: 4px;
  }
</style>

<section class="hero-bg text-white"
style="background: url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; min-height:100vh; padding-bottom: 60px; position: relative;">
  <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
  <div class="container py-5 position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="text-center mb-5">
          <h1 class="display-3 fw-bold mb-3">Modo <span class="accent-red">Principiantes</span></h1>
          <p class="lead text-white-50">Empieza tu aventura en el tuning de forma sencilla y segura</p>
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
                $motId   = (int)  $c['motorizacion_id'];
                $label   = htmlspecialchars($c['marca_nombre'] . ' ' . $c['modelo_nombre']);
                $sublabel = htmlspecialchars($c['motor_nombre'] . ($c['tipo_combustible'] ? ' · ' . $c['tipo_combustible'] : '') . ($c['potencia'] ? ' · ' . $c['potencia'] : ''));
                $vehiculoJS = htmlspecialchars($c['marca_nombre'] . ' ' . $c['modelo_nombre'] . ' · ' . $c['motor_nombre'], ENT_QUOTES);
              ?>
              <div class="col-sm-6 col-lg-4">
                <button
                  class="coche-btn"
                  id="coche-<?= $motId ?>"
                  onclick="elegirCoche(<?= $motId ?>, '<?= $vehiculoJS ?>')"
                >
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
        <!-- Divisor "o busca manualmente" -->
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
                <a href="todos-tutoriales.php" id="btn-todos-tutoriales" class="btn btn-outline-light btn-all">
                  <i class="fas fa-list me-1"></i> Ver todos los tutoriales
                </a>
              </div>
              <div class="row g-4" id="lista-tutoriales"></div>
            </div>

            <!-- Piezas -->
            <div class="col-lg-6">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="accent-red"><i class="fas fa-cog me-2"></i> Piezas compatibles</h3>
                <a href="todas-piezas.php" id="btn-todas-piezas" class="btn btn-outline-light btn-all">
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
  const ytId  = t.youtube_id || '';
  const thumb = ytId
    ? `https://img.youtube.com/vi/${ytId}/hqdefault.jpg`
    : (t.imagen || 'https://via.placeholder.com/300x180?text=Sin+imagen');
  const ytBadge = ytId
    ? `<span style="background:#ff0000;color:#fff;font-size:.65rem;padding:2px 7px;border-radius:4px;font-weight:700;">
         <i class="fab fa-youtube me-1"></i>YouTube
       </span>`
    : '';
  const clickAttr = ytId
    ? `onclick="verVideo('${ytId}','${esc(t.titulo).replace(/'/g,"\\'")}')" style="cursor:pointer;"`
    : `style="cursor:default;"`;
  return `
    <div class="col-12 col-sm-6">
      <div ${clickAttr}>
        <div class="card bg-dark text-white border-0 shadow card-tuning h-100" style="transition:transform .25s,box-shadow .25s;">
          <div style="position:relative;overflow:hidden;">
            <img src="${esc(thumb)}" class="card-img-top" style="height:160px;object-fit:cover;" alt="${esc(t.titulo)}"
                 onerror="this.src='https://via.placeholder.com/300x180?text=Sin+imagen'">
            <div class="yt-play-overlay">
              <div class="yt-play-btn"><i class="fas fa-play"></i></div>
            </div>
            ${ytId ? `<div style="position:absolute;bottom:7px;right:7px;background:rgba(0,0,0,.75);padding:2px 6px;border-radius:3px;font-size:.65rem;display:flex;align-items:center;gap:3px;"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube</div>` : ''}
          </div>
          <div class="card-body d-flex flex-column p-3">
            ${ytBadge}
            <h6 class="card-title clamp-2 mt-1">${esc(t.titulo)}</h6>
            <p class="card-text small text-muted mt-auto mb-0">Pieza: ${esc(t.pieza_nombre || 'General')}</p>
          </div>
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

// ── Función central: carga resultados dado un motorizacion_id y nombre legible ──
function cargarResultados(motId, vehiculo) {
  const params = `?motorizacion_id=${encodeURIComponent(motId)}&vehiculo=${encodeURIComponent(vehiculo)}`;
  document.getElementById('btn-todas-piezas').href     = 'todas-piezas.php'     + params;
  document.getElementById('btn-todos-tutoriales').href = 'todos-tutoriales.php' + params;

  resultados.classList.remove('d-none');
  setLoading(true);
  document.getElementById('vehiculo-badge').textContent = vehiculo;
  document.getElementById('lista-tutoriales').innerHTML = '';
  document.getElementById('lista-piezas').innerHTML     = '';
  document.getElementById('sin-resultados').classList.add('d-none');

  // Scroll suave hacia los resultados
  resultados.scrollIntoView({ behavior: 'smooth', block: 'start' });

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
}

// ── Clic en coche guardado ──
function elegirCoche(motId, vehiculo) {
  // Marcar la tarjeta activa y desmarcar las demás
  document.querySelectorAll('.coche-btn').forEach(b => b.classList.remove('activo'));
  const btn = document.getElementById('coche-' + motId);
  if (btn) btn.classList.add('activo');

  // Resetear los selects manuales (para no confundir)
  marcaSelect.selectedIndex  = 0;
  modeloSelect.innerHTML = '<option selected disabled>Primero selecciona marca</option>';
  modeloSelect.disabled  = true;
  motorSelect.innerHTML  = '<option selected disabled>Primero selecciona modelo</option>';
  motorSelect.disabled   = true;

  cargarResultados(motId, vehiculo);
}

// ── Marca → Modelos ──
marcaSelect.addEventListener('change', () => {
  // Desmarcar coches guardados si el usuario cambia el selector
  document.querySelectorAll('.coche-btn').forEach(b => b.classList.remove('activo'));

  const marcaId = marcaSelect.value;
  modeloSelect.innerHTML = '<option selected disabled>Cargando modelos...</option>';
  motorSelect.innerHTML  = '<option selected disabled>Primero selecciona un modelo</option>';
  modeloSelect.disabled  = true;
  motorSelect.disabled   = true;
  resultados.classList.add('d-none');

  fetch(`public/ajax/get_modelos.php?marca_id=${marcaId}`)
    .then(r => r.json())
    .then(data => {
      modeloSelect.innerHTML = '<option selected disabled>Selecciona un modelo</option>';
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id; opt.textContent = m.nombre;
        modeloSelect.appendChild(opt);
      });
      modeloSelect.disabled = data.length === 0;
    })
    .catch(() => modeloSelect.innerHTML = '<option selected disabled>Error al cargar</option>');
});

// ── Modelo → Motorizaciones ──
modeloSelect.addEventListener('change', () => {
  const modeloId = modeloSelect.value;
  motorSelect.innerHTML = '<option selected disabled>Cargando motorizaciones...</option>';
  motorSelect.disabled  = true;
  resultados.classList.add('d-none');

  fetch(`public/ajax/get_motorizaciones.php?modelo_id=${modeloId}`)
    .then(r => r.json())
    .then(data => {
      motorSelect.innerHTML = '<option selected disabled>Selecciona una motorización</option>';
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id; opt.textContent = m.texto || m.nombre;
        motorSelect.appendChild(opt);
      });
      motorSelect.disabled = data.length === 0;
    })
    .catch(() => motorSelect.innerHTML = '<option selected disabled>Error al cargar</option>');
});

// ── Motorización → Resultados (selector manual) ──
motorSelect.addEventListener('change', () => {
  const motId    = motorSelect.value;
  const marcaTxt = marcaSelect.options[marcaSelect.selectedIndex]?.text || '';
  const modelTxt = modeloSelect.options[modeloSelect.selectedIndex]?.text || '';
  const motorTxt = motorSelect.options[motorSelect.selectedIndex]?.text || '';
  const vehiculo = `${marcaTxt} ${modelTxt} · ${motorTxt}`;

  cargarResultados(motId, vehiculo);
});

// ── Reproductor de video en modal ──
function verVideo(ytId, titulo) {
  if (!ytId) return;
  document.getElementById('videoModalTitle').textContent = titulo;
  document.getElementById('videoIframe').src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1';
  new bootstrap.Modal(document.getElementById('videoModal')).show();
}
document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
  document.getElementById('videoIframe').src = '';
});
</script>

<!-- ══ MODAL REPRODUCTOR ══════════════════════════════════════════ -->
<div class="modal fade" id="videoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background:#0d0d0d;border:1px solid rgba(255,60,0,.25);border-radius:14px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);padding:12px 18px;">
        <h6 class="modal-title text-white fw-semibold mb-0" id="videoModalTitle" style="max-width:90%;"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
          <iframe id="videoIframe" src="" title=""
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>