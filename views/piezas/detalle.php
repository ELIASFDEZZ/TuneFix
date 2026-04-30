<style>
  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.85)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 70px 0 55px;
  }

  .content-section {
    background: rgba(255, 255, 255, 0.95);
    flex: 1;
  }

  .badge-ref {
    background: rgb(164, 4, 46);
    color: #fff;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
  }

  .pieza-img {
    width: 100%;
    max-height: 480px;
    object-fit: contain;
    border-radius: 12px;
    background: #f8f8f8;
    border: 1px solid rgba(255, 60, 0, 0.15);
  }

  .btn-volver {
    border-color: rgba(255,255,255,0.35);
    color: rgba(255,255,255,0.75);
    font-size: 0.85rem;
  }
  .btn-volver:hover {
    background: rgba(255,255,255,0.12);
    color: #fff;
    border-color: rgba(255,255,255,0.7);
  }

  .section-divider {
    border-top: 2px solid rgba(255, 60, 0, 0.2);
    margin-bottom: 2rem;
  }

  .desc-box {
    border-left: 4px solid rgb(164, 4, 46);
    padding: 1rem 1.25rem;
    background: rgba(164, 4, 46, 0.04);
    border-radius: 0 8px 8px 0;
  }
</style>

<!-- ══════════════ HERO ══════════════ -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <h1 class="display-5 fw-bold mb-2">
      <i class="fas fa-cog me-3" style="color: rgba(255,60,0,0.9);"></i>
      <?= htmlspecialchars($pieza['nombre']) ?>
    </h1>
    <span class="badge badge-ref fs-6 px-3 py-2 mt-1">
      <i class="fas fa-barcode me-2"></i><?= htmlspecialchars($pieza['referencia']) ?>
    </span>
  </div>
</section>

<!-- ══════════════ CONTENIDO ══════════════ -->
<section class="content-section py-5">
  <div class="container">
    <div class="section-divider"></div>

    <div class="row g-5 align-items-start">

      <!-- Imagen -->
      <div class="col-lg-6">
        <?php
          $img = !empty($pieza['imagen'])
            ? $pieza['imagen']
            : 'https://via.placeholder.com/600x400?text=Sin+imagen';
        ?>
        <img
          src="<?= htmlspecialchars($img) ?>"
          class="pieza-img shadow"
          alt="<?= htmlspecialchars($pieza['nombre']) ?>"
          onerror="this.src='https://via.placeholder.com/600x400?text=Sin+imagen'"
        >
      </div>

      <!-- Información -->
      <div class="col-lg-6">
        <h2 class="fw-bold text-black mb-3">
          <?= htmlspecialchars($pieza['nombre']) ?>
        </h2>

        <span class="badge badge-ref mb-4 px-3 py-2">
          <i class="fas fa-barcode me-2"></i><?= htmlspecialchars($pieza['referencia']) ?>
        </span>

        <h5 class="fw-semibold text-black mt-4 mb-2">
          <i class="fas fa-info-circle me-2" style="color: rgb(164,4,46);"></i>Descripción
        </h5>
        <div class="desc-box">
          <p class="text-black-50 mb-0" style="line-height: 1.75;">
            <?= nl2br(htmlspecialchars($pieza['descripcion'] ?? 'Sin descripción disponible.')) ?>
          </p>
        </div>

        <div class="mt-4 d-flex align-items-center gap-3 flex-wrap">
          <?php
            if ($motorizacionId > 0) {
              $urlPiezas = 'todas-piezas.php?motorizacion_id=' . $motorizacionId . ($vehiculo !== '' ? '&vehiculo=' . urlencode($vehiculo) : '');
              $labelPiezas = 'Ver piezas compatibles' . ($vehiculo !== '' ? ' con tu vehículo' : '');
            } else {
              $urlPiezas   = 'todas-piezas.php';
              $labelPiezas = 'Ver todas las piezas';
            }
          ?>
          <a href="<?= $urlPiezas ?>" class="btn btn-outline-secondary">
            <i class="fas fa-list me-1"></i> <?= htmlspecialchars($labelPiezas) ?>
          </a>

          <?php if ($usuarioId): ?>
          <button id="btn-megusta"
                  data-pieza-id="<?= (int)$pieza['id'] ?>"
                  data-liked="<?= $meGusta ? '1' : '0' ?>"
                  onclick="toggleMeGusta(this)"
                  class="btn d-flex align-items-center gap-2"
                  style="<?= $meGusta
                    ? 'background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border:none;'
                    : 'background:#fff;color:#a4042e;border:1.5px solid #a4042e;' ?>
                    border-radius:50px;font-weight:700;font-size:.9rem;padding:8px 20px;transition:all .25s;">
            <i class="fas fa-heart"></i>
            <span id="btn-megusta-label"><?= $meGusta ? 'Guardado' : 'Me gusta' ?></span>
            <span id="btn-megusta-count"
                  style="background:rgba(0,0,0,0.12);border-radius:50px;padding:1px 8px;font-size:.78rem;">
              <?= $totalMeGusta ?>
            </span>
          </button>
          <?php else: ?>
          <a href="login.php" class="btn d-flex align-items-center gap-2"
             style="background:#fff;color:#a4042e;border:1.5px solid #a4042e;border-radius:50px;font-weight:700;font-size:.9rem;padding:8px 20px;">
            <i class="fas fa-heart"></i> Me gusta
            <span style="background:rgba(0,0,0,0.08);border-radius:50px;padding:1px 8px;font-size:.78rem;">
              <?= $totalMeGusta ?>
            </span>
          </a>
          <?php endif; ?>
        </div>

<script>
function toggleMeGusta(btn) {
  const piezaId = btn.dataset.piezaId;
  btn.disabled = true;

  fetch('public/ajax/toggle_megusta_pieza.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'pieza_id=' + piezaId
  })
  .then(r => r.json())
  .then(data => {
    if (data.error) { btn.disabled = false; return; }

    const liked = data.liked;
    btn.dataset.liked = liked ? '1' : '0';
    document.getElementById('btn-megusta-label').textContent = liked ? 'Guardado' : 'Me gusta';
    document.getElementById('btn-megusta-count').textContent = data.total;

    if (liked) {
      btn.style.cssText = 'background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border:none;border-radius:50px;font-weight:700;font-size:.9rem;padding:8px 20px;transition:all .25s;display:flex;align-items:center;gap:.5rem;';
    } else {
      btn.style.cssText = 'background:#fff;color:#a4042e;border:1.5px solid #a4042e;border-radius:50px;font-weight:700;font-size:.9rem;padding:8px 20px;transition:all .25s;display:flex;align-items:center;gap:.5rem;';
    }
    btn.disabled = false;
  })
  .catch(() => { btn.disabled = false; });
}
</script>
      </div>

    </div>
  </div>
</section>
