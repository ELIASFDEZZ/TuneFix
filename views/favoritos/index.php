<style>
  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.88)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 50px;
  }
  .content-section { background: rgba(255,255,255,0.95); flex: 1; }
  .section-divider  { border-top: 2px solid rgba(255,60,0,0.2); margin-bottom: 2rem; }
  .btn-volver { border-color: rgba(255,255,255,0.35); color: rgba(255,255,255,0.75); font-size: 0.85rem; }
  .btn-volver:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.7); }
  .empty-icon { color: rgba(164,4,46,0.2); }

  .card-pieza-fav {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: transform .2s, box-shadow .2s;
    overflow: hidden;
  }
  .card-pieza-fav:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 22px rgba(164,4,46,0.15);
  }
  .badge-ref-fav {
    background: rgb(164, 4, 46);
    color: #fff;
    font-size: 0.68rem;
  }
  .btn-quitar {
    border: 1.5px solid rgba(164,4,46,0.4);
    color: #a4042e;
    background: transparent;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 12px;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
  }
  .btn-quitar:hover {
    background: #a4042e;
    color: #fff;
  }
</style>

<!-- HERO -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <h1 class="display-4 fw-bold mb-2">
      <i class="fas fa-heart me-3" style="color: rgba(255,60,0,0.9);"></i>Mis Favoritos
    </h1>
    <p class="lead mb-0" style="color: rgba(255,255,255,0.6);">
      Piezas que has guardado con Me gusta
    </p>
  </div>
</section>

<!-- CONTENIDO -->
<section class="content-section py-5">
  <div class="container">
    <div class="section-divider"></div>

    <?php if (empty($piezasGuardadas)): ?>
      <div class="text-center py-5">
        <i class="fas fa-heart fa-5x empty-icon mb-4 d-block"></i>
        <h4 class="fw-bold text-black mb-2">Aún no has guardado ninguna pieza</h4>
        <p class="text-black-50 mb-4">
          Pulsa el botón <strong>Me gusta</strong> en cualquier pieza para guardarla aquí.
        </p>
        <a href="todas-piezas.php" class="btn px-4 py-2 fw-semibold"
           style="background: rgb(164,4,46); color: #fff; border-radius: 50px;">
          <i class="fas fa-cog me-2"></i>Explorar piezas
        </a>
      </div>

    <?php else: ?>
      <h5 class="fw-semibold text-black mb-4">
        <i class="fas fa-heart me-2" style="color:#a4042e;"></i>
        <?= count($piezasGuardadas) ?> pieza<?= count($piezasGuardadas) !== 1 ? 's' : '' ?> guardada<?= count($piezasGuardadas) !== 1 ? 's' : '' ?>
      </h5>

      <div class="row g-4" id="lista-favoritos">
        <?php foreach ($piezasGuardadas as $p): ?>
          <?php
            $img  = !empty($p['imagen'])      ? $p['imagen']      : 'https://via.placeholder.com/400x220?text=Sin+imagen';
            $desc = !empty($p['descripcion']) ? $p['descripcion'] : 'Sin descripción disponible.';
          ?>
          <div class="col-sm-6 col-md-4 col-xl-3" id="fav-card-<?= (int)$p['id'] ?>">
            <div class="card card-pieza-fav h-100">
              <a href="pieza-detalle.php?id=<?= (int)$p['id'] ?>" class="text-decoration-none">
                <img
                  src="<?= htmlspecialchars($img) ?>"
                  class="card-img-top"
                  style="height: 160px; object-fit: cover;"
                  alt="<?= htmlspecialchars($p['nombre']) ?>"
                  onerror="this.src='https://via.placeholder.com/400x220?text=Sin+imagen'"
                >
              </a>
              <div class="card-body d-flex flex-column p-3">
                <span class="badge badge-ref-fav align-self-start mb-2">
                  <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($p['referencia']) ?>
                </span>
                <a href="pieza-detalle.php?id=<?= (int)$p['id'] ?>" class="text-decoration-none">
                  <h6 class="card-title fw-semibold text-black mb-2" style="line-height:1.4;">
                    <?= htmlspecialchars($p['nombre']) ?>
                  </h6>
                </a>
                <p class="card-text small text-black-50 mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  <?= htmlspecialchars($desc) ?>
                </p>
                <div class="mt-auto d-flex align-items-center justify-content-between">
                  <small class="text-muted" style="font-size:.68rem;">
                    <i class="fas fa-clock me-1"></i>
                    <?= date('d/m/Y', strtotime($p['fecha'])) ?>
                  </small>
                  <button class="btn-quitar"
                          data-pieza-id="<?= (int)$p['id'] ?>"
                          onclick="quitarMeGusta(this)">
                    <i class="fas fa-heart-broken me-1"></i>Quitar
                  </button>
                </div>
              </div>
              <div style="height:3px;background:linear-gradient(90deg,rgb(164,4,46),#ff8800);"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<script>
function quitarMeGusta(btn) {
  const piezaId = btn.dataset.piezaId;
  btn.disabled = true;

  fetch('public/ajax/toggle_megusta_pieza.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'pieza_id=' + piezaId
  })
  .then(r => r.json())
  .then(data => {
    if (data.error || data.liked) { btn.disabled = false; return; }
    const card = document.getElementById('fav-card-' + piezaId);
    card.style.transition = 'opacity .3s';
    card.style.opacity = '0';
    setTimeout(() => {
      card.remove();
      const lista = document.getElementById('lista-favoritos');
      if (lista && lista.children.length === 0) location.reload();
    }, 300);
  })
  .catch(() => { btn.disabled = false; });
}
</script>
