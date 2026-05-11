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
            : (!empty($pieza['url']) ? $pieza['url'] : 'public/img/no-image.svg');
        ?>
        <img
          src="<?= htmlspecialchars($img) ?>"
          class="pieza-img shadow"
          alt="<?= htmlspecialchars($pieza['nombre']) ?>"
          onerror="this.onerror=null;this.src='public/img/no-image.svg'"
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

        <!-- Precio y stock -->
        <?php
          $precio = (float)($pieza['precio'] ?? 0);
          $stock  = (int)($pieza['stock']   ?? 0);
        ?>
        <?php if ($precio > 0): ?>
        <div class="mt-4 d-flex align-items-center gap-3 flex-wrap">
          <span class="fw-bold" style="font-size:2rem;color:rgb(164,4,46);">
            <?= number_format($precio, 2) ?> €
          </span>
          <?php if ($stock > 0): ?>
            <span class="badge px-3 py-2" style="background:rgba(40,167,69,.12);color:#28a745;border:1px solid rgba(40,167,69,.3);font-size:.85rem;">
              <i class="fas fa-boxes me-1"></i>Stock: <?= $stock ?> unidades
            </span>
          <?php else: ?>
            <span class="badge px-3 py-2" style="background:rgba(220,53,69,.1);color:#dc3545;border:1px solid rgba(220,53,69,.2);font-size:.85rem;">
              <i class="fas fa-times-circle me-1"></i>Sin stock
            </span>
          <?php endif; ?>
        </div>
        <?php endif; ?>

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

        <!-- Autor de la pieza (proveedor o profesional) -->
        <?php
          $tieneProveedor   = !empty($pieza['proveedor_id'])  && !empty($pieza['proveedor_nombre']);
          $tieneProfesional = !empty($pieza['subido_por'])    && !empty($pieza['subido_por_nombre']);
        ?>
        <?php if ($tieneProveedor || $tieneProfesional): ?>
        <div class="mt-4 d-flex align-items-center gap-3"
             style="background:rgba(164,4,46,0.05);border:1px solid rgba(164,4,46,0.12);border-radius:12px;padding:14px 18px;">
          <?php if ($tieneProveedor): ?>
            <!-- Proveedor -->
            <div style="width:40px;height:40px;background:linear-gradient(135deg,#ffc107,#ff8800);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:1rem;flex-shrink:0;">
              <?= mb_strtoupper(mb_substr(htmlspecialchars($pieza['proveedor_nombre']), 0, 1)) ?>
            </div>
            <div>
              <div style="font-size:0.72rem;color:rgba(0,0,0,0.4);text-transform:uppercase;letter-spacing:0.5px;font-weight:700;">
                <i class="fas fa-store me-1"></i>Proveedor
              </div>
              <a href="proveedor-perfil.php?id=<?= (int)$pieza['proveedor_id'] ?>"
                 style="font-weight:700;color:rgb(164,4,46);text-decoration:none;font-size:0.95rem;">
                <?= htmlspecialchars($pieza['proveedor_nombre']) ?>
                <i class="fas fa-arrow-right ms-1" style="font-size:0.75rem;"></i>
              </a>
            </div>
          <?php else: ?>
            <!-- Profesional -->
            <div style="width:40px;height:40px;background:linear-gradient(135deg,#a4042e,#ff8800);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:1rem;flex-shrink:0;">
              <?= mb_strtoupper(mb_substr(htmlspecialchars($pieza['subido_por_nombre']), 0, 1)) ?>
            </div>
            <div>
              <div style="font-size:0.72rem;color:rgba(0,0,0,0.4);text-transform:uppercase;letter-spacing:0.5px;font-weight:700;">
                <i class="fas fa-user-tie me-1"></i>Publicado por
              </div>
              <a href="perfil-usuario.php?id=<?= (int)$pieza['subido_por'] ?>"
                 style="font-weight:700;color:rgb(164,4,46);text-decoration:none;font-size:0.95rem;">
                <?= htmlspecialchars($pieza['subido_por_nombre']) ?>
                <i class="fas fa-arrow-right ms-1" style="font-size:0.75rem;"></i>
              </a>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Botón Añadir al carrito -->
        <?php if ($usuarioId && $precio > 0 && $stock > 0): ?>
        <div class="mt-3 d-flex align-items-center gap-3 flex-wrap">
          <div class="d-flex align-items-center border rounded-pill px-3 py-1"
               style="border-color:rgb(164,4,46)!important;">
            <button class="btn btn-sm p-0 fw-bold" style="color:rgb(164,4,46);"
                    onclick="cambiarCantidad(-1)">−</button>
            <span id="qty-value" class="px-3 fw-bold">1</span>
            <button class="btn btn-sm p-0 fw-bold" style="color:rgb(164,4,46);"
                    onclick="cambiarCantidad(1)">+</button>
          </div>
          <button id="btn-add-cart"
                  onclick="addToCart(<?= (int)$pieza['id'] ?>)"
                  class="btn fw-bold px-4 py-2"
                  style="background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border-radius:50px;">
            <i class="fas fa-shopping-cart me-2"></i>Añadir al carrito
          </button>
        </div>
        <?php elseif ($usuarioId && $precio > 0 && $stock <= 0): ?>
        <div class="mt-3">
          <button class="btn fw-bold px-4 py-2" disabled
                  style="background:rgba(164,4,46,.3);color:#fff;border-radius:50px;cursor:not-allowed;">
            <i class="fas fa-shopping-cart me-2"></i>Sin stock disponible
          </button>
        </div>
        <?php elseif ($usuarioId && $precio == 0): ?>
        <p class="text-muted mt-3 mb-0">
          <i class="fas fa-info-circle me-1"></i>Precio no disponible actualmente
        </p>
        <?php elseif (!$usuarioId && $precio > 0): ?>
        <div class="mt-3">
          <a href="login.php" class="btn fw-bold px-4 py-2"
             style="background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border-radius:50px;">
            <i class="fas fa-sign-in-alt me-2"></i>Inicia sesión para comprar
          </a>
        </div>
        <?php endif; ?>

<script>
let qty = 1;
function cambiarCantidad(delta) {
  qty = Math.max(1, qty + delta);
  document.getElementById('qty-value').textContent = qty;
}
function addToCart(piezaId) {
  const btn = document.getElementById('btn-add-cart');
  btn.disabled = true;
  fetch('public/ajax/carrito_add.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'pieza_id=' + piezaId + '&cantidad=' + qty
  })
  .then(r => r.json())
  .then(data => {
    if (data.ok) {
      const badge = document.getElementById('cart-badge');
      if (badge) { badge.textContent = data.total_items; badge.style.display = ''; }
      btn.innerHTML = '<i class="fas fa-check me-2"></i>¡Añadido!';
      btn.style.background = 'linear-gradient(45deg,#28a745,#20c997)';
      setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Añadir al carrito';
        btn.style.background = 'linear-gradient(45deg,#a4042e,#ff3c00)';
        btn.disabled = false;
      }, 2000);
    } else {
      alert('Error al añadir al carrito');
      btn.disabled = false;
    }
  })
  .catch(() => { alert('Error de conexión'); btn.disabled = false; });
}

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
