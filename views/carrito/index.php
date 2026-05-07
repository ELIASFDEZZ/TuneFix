<style>
  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.78), rgba(0,0,0,0.88)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 45px;
  }
  .content-section { background: rgba(255,255,255,0.95); flex: 1; }
  .section-divider  { border-top: 2px solid rgba(255,60,0,0.2); margin-bottom: 2rem; }

  /* ── Tabla carrito ── */
  .table-carrito thead th {
    background: rgba(164,4,46,0.06);
    color: rgb(164,4,46);
    font-size: .8rem;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-bottom: 2px solid rgba(255,60,0,0.2);
    vertical-align: middle;
  }
  .table-carrito td { vertical-align: middle; }
  .img-carrito {
    width: 70px; height: 70px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid rgba(255,60,0,0.15);
  }

  /* ── Controles cantidad ── */
  .qty-wrap {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid rgba(164,4,46,0.35);
    border-radius: 50px;
    overflow: hidden;
  }
  .qty-wrap button {
    background: none; border: none;
    color: rgb(164,4,46); font-weight: 700; font-size: 1rem;
    padding: 2px 12px; cursor: pointer; transition: background .2s;
  }
  .qty-wrap button:hover { background: rgba(164,4,46,0.08); }
  .qty-wrap span { min-width: 28px; text-align: center; font-weight: 700; }

  /* ── Card resumen ── */
  .card-resumen {
    border: 1px solid rgba(255,60,0,0.2);
    border-radius: 16px;
    overflow: hidden;
  }
  .card-resumen .header-resumen {
    background: linear-gradient(135deg, rgb(164,4,46), #ff3c00);
    color: #fff;
    padding: 18px 22px;
  }
  .card-resumen .body-resumen { padding: 20px 22px; }

  /* ── Tabla comparativa ── */
  .table-comparativa thead th {
    background: #1e1e1e;
    color: #fff;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .5px;
  }
  .row-barato  td:first-child { border-left: 4px solid #28a745; }
  .row-caro    td:first-child { border-left: 4px solid #ff8800; }
  .badge-estado-nueva        { background: rgba(40,167,69,.15);  color: #28a745; border: 1px solid rgba(40,167,69,.3); }
  .badge-estado-usada_buena  { background: rgba(255,193,7,.15);  color: #b38000; border: 1px solid rgba(255,193,7,.3); }
  .badge-estado-usada_desgaste { background: rgba(220,53,69,.1); color: #dc3545; border: 1px solid rgba(220,53,69,.2); }

  .btn-pagar {
    background: linear-gradient(45deg,#a4042e,#ff3c00);
    color: #fff; border: none; border-radius: 50px;
    font-weight: 700; font-size: 1rem; padding: 14px 0;
    width: 100%; transition: opacity .2s;
  }
  .btn-pagar:hover { opacity: .88; color: #fff; }
  .btn-pagar:disabled { opacity: .55; cursor: not-allowed; }
  .btn-seguir-comprando {
    background: transparent;
    border: 1.5px solid rgba(164,4,46,0.4);
    color: rgb(164,4,46); border-radius: 50px;
    font-weight: 600; padding: 10px 0; width: 100%;
    transition: all .2s;
  }
  .btn-seguir-comprando:hover {
    background: rgba(164,4,46,0.06);
    border-color: rgb(164,4,46);
  }

  .total-display { font-size: 1.9rem; font-weight: 800; color: rgb(164,4,46); }
  .empty-icon    { color: rgba(164,4,46,.2); }

  .btn-volver { border-color: rgba(255,255,255,0.35); color: rgba(255,255,255,0.75); font-size: .85rem; }
  .btn-volver:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.7); }
</style>

<!-- ══ HERO ══════════════════════════════════════════════════════════ -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="todas-piezas.php" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Seguir comprando
      </a>
    </div>
    <h1 class="display-4 fw-bold mb-1">
      <i class="fas fa-shopping-cart me-3" style="color:rgba(255,60,0,.9);"></i>Mi Carrito
    </h1>
    <p class="lead mb-0" style="color:rgba(255,255,255,.6);">
      <?= count($items) ?> producto<?= count($items) !== 1 ? 's' : '' ?> en tu carrito
    </p>
  </div>
</section>

<!-- ══ CONTENIDO ════════════════════════════════════════════════════ -->
<section class="content-section py-5">
  <div class="container">
    <div class="section-divider"></div>

    <?php if (empty($items)): ?>
    <!-- Carrito vacío -->
    <div class="text-center py-5">
      <i class="fas fa-shopping-cart fa-5x empty-icon mb-4 d-block"></i>
      <h3 class="text-black-50 fw-semibold">Tu carrito está vacío</h3>
      <p class="text-black-50 mb-4">Explora nuestro catálogo y añade las piezas que necesitas.</p>
      <a href="todas-piezas.php" class="btn fw-bold px-5 py-3"
         style="background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border-radius:50px;font-size:1rem;">
        <i class="fas fa-search me-2"></i>Ver catálogo de piezas
      </a>
    </div>

    <?php else: ?>
    <div class="row g-4 align-items-start">

      <!-- ══ Columna principal: tabla ══════════════════════════════ -->
      <div class="col-lg-8">

        <?php if (isset($_GET['cancelado'])): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
          <i class="fas fa-exclamation-circle"></i>
          Pago cancelado. Puedes revisar tu carrito e intentarlo de nuevo.
        </div>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-carrito align-middle mb-0">
            <thead>
              <tr>
                <th style="width:80px;"></th>
                <th>Pieza</th>
                <th class="text-center">Precio</th>
                <th class="text-center">Cantidad</th>
                <th class="text-end">Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="carrito-body">
              <?php foreach ($items as $item): ?>
              <?php
                $img      = !empty($item['imagen']) ? $item['imagen'] : 'https://via.placeholder.com/70?text=N/A';
                $subtotal = (float)$item['cantidad'] * (float)$item['precio_u'];
              ?>
              <tr id="row-<?= (int)$item['id'] ?>">
                <td>
                  <img src="<?= htmlspecialchars($img) ?>" class="img-carrito"
                       onerror="this.src='https://via.placeholder.com/70?text=N/A'"
                       alt="<?= htmlspecialchars($item['nombre']) ?>">
                </td>
                <td>
                  <div class="fw-semibold text-black" style="font-size:.95rem;">
                    <?= htmlspecialchars($item['nombre']) ?>
                  </div>
                  <span class="badge" style="background:rgb(164,4,46);color:#fff;font-size:.68rem;">
                    <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($item['referencia']) ?>
                  </span>
                  <?php if ((int)$item['stock'] <= 3): ?>
                  <span class="badge ms-1" style="background:rgba(255,136,0,.15);color:#b36a00;border:1px solid rgba(255,136,0,.3);font-size:.65rem;">
                    ¡Solo <?= (int)$item['stock'] ?> en stock!
                  </span>
                  <?php endif; ?>
                </td>
                <td class="text-center fw-semibold" style="color:rgb(164,4,46);">
                  <?= number_format((float)$item['precio_u'], 2) ?> €
                </td>
                <td class="text-center">
                  <div class="qty-wrap">
                    <button onclick="cambiarCantidad(<?= (int)$item['id'] ?>, -1)" aria-label="Reducir">−</button>
                    <span id="qty-<?= (int)$item['id'] ?>"><?= (int)$item['cantidad'] ?></span>
                    <button onclick="cambiarCantidad(<?= (int)$item['id'] ?>, +1)" aria-label="Aumentar">+</button>
                  </div>
                </td>
                <td class="text-end fw-bold" id="sub-<?= (int)$item['id'] ?>" style="color:rgb(164,4,46);">
                  <?= number_format($subtotal, 2) ?> €
                </td>
                <td class="text-end">
                  <button onclick="eliminarItem(<?= (int)$item['id'] ?>)"
                          class="btn btn-sm"
                          style="color:#dc3545;background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);border-radius:8px;"
                          title="Eliminar">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php if (count($items) >= 2): ?>
        <!-- ══ Tabla comparativa ══════════════════════════════════ -->
        <div class="mt-5">
          <h5 class="fw-bold mb-3" style="color:rgb(164,4,46);">
            <i class="fas fa-balance-scale me-2"></i>Comparativa de piezas en el carrito
          </h5>
          <?php
            $precios  = array_column($items, 'precio_u');
            $minPrecio = min($precios);
            $maxPrecio = max($precios);
            $etiquetasEstado = [
              'nueva'           => 'Nueva',
              'usada_buena'     => 'Usada (buena)',
              'usada_desgaste'  => 'Usada (desgaste)',
            ];
          ?>
          <div class="table-responsive rounded" style="border:1px solid rgba(0,0,0,.1);">
            <table class="table table-comparativa mb-0">
              <thead>
                <tr>
                  <th>Pieza</th>
                  <th>Referencia</th>
                  <th>Categoría</th>
                  <th>Estado</th>
                  <th>Garantía</th>
                  <th class="text-end">Precio/u</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($items as $item): ?>
                <?php
                  $esMasBarato = (float)$item['precio_u'] == $minPrecio && count($items) > 1;
                  $esMasCaro   = (float)$item['precio_u'] == $maxPrecio && $minPrecio != $maxPrecio;
                  $rowClass    = $esMasBarato ? 'row-barato' : ($esMasCaro ? 'row-caro' : '');
                  $estadoKey   = $item['estado_pieza'] ?? 'nueva';
                  $estadoLabel = $etiquetasEstado[$estadoKey] ?? $estadoKey;
                ?>
                <tr class="<?= $rowClass ?>">
                  <td class="fw-semibold"><?= htmlspecialchars($item['nombre']) ?></td>
                  <td>
                    <span class="badge" style="background:rgb(164,4,46);color:#fff;font-size:.68rem;">
                      <?= htmlspecialchars($item['referencia']) ?>
                    </span>
                  </td>
                  <td class="text-muted small"><?= htmlspecialchars($item['categoria'] ?? '—') ?></td>
                  <td>
                    <span class="badge badge-estado-<?= htmlspecialchars($estadoKey) ?>">
                      <?= htmlspecialchars($estadoLabel) ?>
                    </span>
                  </td>
                  <td class="text-muted small"><?= htmlspecialchars($item['garantia'] ?? 'Sin garantía') ?></td>
                  <td class="text-end fw-bold" style="color:<?= $esMasBarato ? '#28a745' : ($esMasCaro ? '#ff8800' : 'inherit') ?>;">
                    <?= number_format((float)$item['precio_u'], 2) ?> €
                    <?php if ($esMasBarato): ?>
                      <i class="fas fa-arrow-down ms-1 text-success" title="Más barata"></i>
                    <?php elseif ($esMasCaro): ?>
                      <i class="fas fa-arrow-up ms-1 text-warning" title="Más cara"></i>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <p class="small text-muted mt-2">
            <span class="me-3"><i class="fas fa-arrow-down text-success me-1"></i>Precio más bajo</span>
            <span><i class="fas fa-arrow-up text-warning me-1"></i>Precio más alto</span>
          </p>
        </div>
        <?php endif; ?>
      </div><!-- /col-lg-8 -->

      <!-- ══ Sidebar: resumen ══════════════════════════════════════ -->
      <div class="col-lg-4">
        <div class="card-resumen shadow-sm sticky-top" style="top:100px;">
          <div class="header-resumen">
            <h5 class="fw-bold mb-0"><i class="fas fa-receipt me-2"></i>Resumen del pedido</h5>
          </div>
          <div class="body-resumen">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="text-muted">Artículos</span>
              <span class="fw-semibold" id="resumen-count">
                <?= array_reduce($items, fn($c,$i) => $c + $i['cantidad'], 0) ?>
              </span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Envío</span>
              <span class="fw-semibold text-success">Gratis</span>
            </div>
            <hr style="border-color:rgba(255,60,0,.15);">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <span class="fw-bold fs-5">Total</span>
              <span class="total-display" id="resumen-total">
                <?= number_format($total, 2) ?> €
              </span>
            </div>
            <a href="pagar.php" class="btn btn-pagar mb-3" id="btn-pagar">
              <i class="fab fa-stripe-s me-2"></i>Pagar con Stripe
            </a>
            <a href="todas-piezas.php" class="btn btn-seguir-comprando">
              <i class="fas fa-arrow-left me-2"></i>Seguir comprando
            </a>
            <div class="mt-3 text-center">
              <small class="text-muted">
                <i class="fas fa-lock me-1"></i>Pago seguro con Stripe
              </small>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /row -->
    <?php endif; ?>

  </div>
</section>

<script>
// Mapa precio_u por carritoId (para calcular subtotales sin recargar)
const preciosPorId = {
  <?php foreach ($items as $item): ?>
  <?= (int)$item['id'] ?>: <?= (float)$item['precio_u'] ?>,
  <?php endforeach; ?>
};

function cambiarCantidad(carritoId, delta) {
  const qtyEl = document.getElementById('qty-' + carritoId);
  const actual = parseInt(qtyEl.textContent);
  const nueva  = Math.max(1, actual + delta);
  if (nueva === actual) return;
  actualizarServidor(carritoId, nueva);
}

function eliminarItem(carritoId) {
  actualizarServidor(carritoId, 0);
}

function actualizarServidor(carritoId, cantidad) {
  fetch('public/ajax/carrito_update.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'carrito_id=' + carritoId + '&cantidad=' + cantidad
  })
  .then(r => r.json())
  .then(data => {
    if (!data.ok) return;

    if (cantidad <= 0) {
      // Eliminar fila
      const row = document.getElementById('row-' + carritoId);
      if (row) row.remove();
    } else {
      // Actualizar cantidad y subtotal
      const qtyEl = document.getElementById('qty-' + carritoId);
      const subEl = document.getElementById('sub-' + carritoId);
      if (qtyEl) qtyEl.textContent = cantidad;
      if (subEl) {
        const precio = preciosPorId[carritoId] || 0;
        subEl.textContent = (precio * cantidad).toFixed(2).replace('.', ',') + ' €';
      }
    }

    // Actualizar resumen
    document.getElementById('resumen-total').textContent = data.total.replace('.', ',') + ' €';
    document.getElementById('resumen-count').textContent = data.count;

    // Actualizar badge navbar
    const badge = document.getElementById('cart-badge');
    if (badge) {
      badge.textContent = data.count;
      badge.style.display = data.count > 0 ? '' : 'none';
    }

    // Si quedó vacío, recargar para mostrar vista vacía
    if (data.count === 0) {
      window.location.reload();
    }
  })
  .catch(err => console.error('Error actualizando carrito:', err));
}
</script>
