<style>
  /* ── HERO: misma foto y overlay que principiante ── */
  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.85)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 70px 0 55px;
  }

  /* ── SECCIÓN DE CONTENIDO: mismo blanco que el footer ── */
  .content-section {
    background: rgba(255, 255, 255, 0.95);
    flex: 1;
  }

  /* ── TARJETAS: blancas con borde suave rojo-naranja (igual que footer border-top) ── */
  .card-pieza {
    background: #fff;
    border: 1px solid rgba(255, 60, 0, 0.15) !important;
    transition: all 0.3s ease;
  }
  .card-pieza:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(164, 4, 46, 0.18);
    border-color: rgba(164, 4, 46, 0.4) !important;
  }

  /* ── BADGE REFERENCIA: rojo del navbar ── */
  .badge-ref {
    background: rgb(164, 4, 46);
    color: #fff;
    font-size: 0.70rem;
    letter-spacing: 0.4px;
  }

  /* ── CONTADOR: borde rojo-naranja del footer ── */
  .counter-badge {
    background: rgba(255, 60, 0, 0.08);
    border: 1px solid rgba(255, 60, 0, 0.3);
    color: rgb(164, 4, 46);
    font-weight: 600;
  }

  /* ── BUSCADOR: blanco con foco rojo ── */
  .search-input {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #fff;
  }
  .search-input:focus {
    background: rgba(255, 255, 255, 0.18);
    border-color: rgba(255, 60, 0, 0.7);
    color: #fff;
    box-shadow: 0 0 0 0.2rem rgba(164, 4, 46, 0.3);
  }
  .search-input::placeholder { color: rgba(255,255,255,0.45); }

  /* ── BOTÓN BUSCAR: rojo del navbar ── */
  .btn-buscar {
    background: rgb(164, 4, 46);
    border: none;
    color: #fff;
    font-weight: 600;
    transition: all 0.25s ease;
  }
  .btn-buscar:hover {
    background: #a8032c;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(164, 4, 46, 0.4);
  }

  /* ── BOTÓN VOLVER ── */
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

  /* ── SEPARADOR: igual que el border-top del footer ── */
  .section-divider {
    border-top: 2px solid rgba(255, 60, 0, 0.2);
    margin-bottom: 2rem;
  }

  .clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* ── ICONO VACÍO ── */
  .empty-icon { color: rgba(164, 4, 46, 0.25); }
</style>

<!-- ══════════════ HERO ══════════════ -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="principiante.php" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Volver a Principiantes
      </a>
    </div>
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-4 fw-bold mb-2">
          <i class="fas fa-cog me-3" style="color: rgba(255,60,0,0.9);"></i>
          <?php if ($motorizacionId > 0): ?>
            Piezas Compatibles
          <?php else: ?>
            Todas las Piezas
          <?php endif; ?>
        </h1>
        <?php if ($motorizacionId > 0 && $vehiculo !== ''): ?>
          <!-- Badge con el nombre del coche -->
          <div class="mb-2">
            <span class="badge fs-6 px-3 py-2" style="background: linear-gradient(45deg, rgb(164,4,46), #ff8800);">
              <i class="fas fa-car me-2"></i><?= htmlspecialchars($vehiculo) ?>
            </span>
          </div>
          <p class="lead mb-0" style="color: rgba(255,255,255,0.65);">
            Todas las piezas compatibles con tu vehículo
          </p>
        <?php else: ?>
          <p class="lead mb-0" style="color: rgba(255,255,255,0.65);">
            Catálogo completo de piezas de tuning disponibles en TuneFix
          </p>
        <?php endif; ?>
      </div>
      <!-- Buscador: siempre visible -->
      <div class="col-lg-6 mt-4 mt-lg-0">
        <form method="GET" action="">
          <?php if ($motorizacionId > 0): ?>
            <!-- Preservar el filtro del vehículo al buscar -->
            <input type="hidden" name="motorizacion_id" value="<?= $motorizacionId ?>">
            <input type="hidden" name="vehiculo" value="<?= htmlspecialchars($vehiculo) ?>">
          <?php endif; ?>
          <div class="input-group input-group-lg">
            <input
              type="text"
              name="busqueda"
              class="form-control search-input"
              placeholder="<?= $motorizacionId > 0 ? 'Buscar entre piezas compatibles...' : 'Buscar por nombre o referencia...' ?>"
              value="<?= htmlspecialchars($busqueda) ?>"
            >
            <button class="btn btn-buscar px-4" type="submit">
              <i class="fas fa-search me-1"></i> Buscar
            </button>
          </div>
          <?php if ($busqueda !== ''): ?>
            <div class="mt-2">
              <?php
                $urlLimpiar = $motorizacionId > 0
                  ? 'todas-piezas.php?motorizacion_id=' . $motorizacionId . '&vehiculo=' . urlencode($vehiculo)
                  : 'todas-piezas.php';
              ?>
              <a href="<?= $urlLimpiar ?>" class="text-decoration-none" style="color: rgba(255,255,255,0.55); font-size: 0.85rem;">
                <i class="fas fa-times me-1"></i>Limpiar búsqueda
              </a>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════ CONTENIDO (blanco como footer) ══════════════ -->
<section class="content-section py-5">
  <div class="container">

    <!-- Separador decorativo igual al border-top del footer -->
    <div class="section-divider"></div>

    <!-- Contador -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <span class="badge counter-badge fs-6 px-3 py-2 rounded-pill">
        <i class="fas fa-layer-group me-2"></i>
        <?php
          $total = count($piezas);
          if ($motorizacionId > 0 && $busqueda !== '') {
            echo $total . ' resultado' . ($total !== 1 ? 's' : '') . ' para "<em>' . htmlspecialchars($busqueda) . '</em>" en tu vehículo';
          } elseif ($motorizacionId > 0) {
            echo $total . ' pieza' . ($total !== 1 ? 's' : '') . ' compatible' . ($total !== 1 ? 's' : '') . ' con tu vehículo';
          } elseif ($busqueda !== '') {
            echo $total . ' resultado' . ($total !== 1 ? 's' : '') . ' para "<em>' . htmlspecialchars($busqueda) . '</em>"';
          } else {
            echo $total . ' pieza' . ($total !== 1 ? 's' : '') . ' en el catálogo';
          }
        ?>
      </span>
      <?php if ($busqueda !== ''): ?>
        <a href="todas-piezas.php" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-list me-1"></i> Ver todas
        </a>
      <?php endif; ?>
    </div>

    <!-- Sin resultados -->
    <?php if (empty($piezas)): ?>
      <div class="text-center py-5">
        <i class="fas fa-search fa-4x empty-icon mb-4 d-block"></i>
        <h4 class="text-black-50">No se encontraron piezas</h4>
        <?php if ($motorizacionId > 0): ?>
          <p class="text-black-50">No hay piezas registradas como compatibles con este vehículo aún.</p>
          <a href="todas-piezas.php" class="btn mt-2" style="background: rgb(164,4,46); color:#fff;">
            Ver catálogo completo
          </a>
        <?php elseif ($busqueda !== ''): ?>
          <p class="text-black-50">Ninguna pieza coincide con "<?= htmlspecialchars($busqueda) ?>".</p>
          <a href="todas-piezas.php" class="btn mt-2" style="background: rgb(164,4,46); color:#fff;">
            Ver todas las piezas
          </a>
        <?php else: ?>
          <p class="text-black-50">Aún no hay piezas registradas en el catálogo.</p>
        <?php endif; ?>
      </div>

    <!-- Grid de piezas -->
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($piezas as $pieza): ?>
          <?php
            $img  = !empty($pieza['imagen'])      ? $pieza['imagen']      : 'https://via.placeholder.com/400x220?text=Sin+imagen';
            $desc = !empty($pieza['descripcion']) ? $pieza['descripcion'] : 'Sin descripción disponible.';
          ?>
          <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm card-pieza h-100">
              <img
                src="<?= htmlspecialchars($img) ?>"
                class="card-img-top"
                style="height: 175px; object-fit: cover;"
                alt="<?= htmlspecialchars($pieza['nombre']) ?>"
                onerror="this.src='https://via.placeholder.com/400x220?text=Sin+imagen'"
              >
              <div class="card-body d-flex flex-column p-3">
                <span class="badge badge-ref align-self-start mb-2">
                  <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($pieza['referencia']) ?>
                </span>
                <h6 class="card-title fw-semibold text-black mb-2" style="line-height: 1.4;">
                  <?= htmlspecialchars($pieza['nombre']) ?>
                </h6>
                <p class="card-text small text-black-50 clamp-3 mt-auto mb-0">
                  <?= htmlspecialchars($desc) ?>
                </p>
              </div>
              <!-- Línea inferior roja-naranja (guiño al footer) -->
              <div style="height: 3px; background: linear-gradient(90deg, rgb(164,4,46), #ff8800);"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
