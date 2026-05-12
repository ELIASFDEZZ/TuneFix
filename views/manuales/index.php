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

  .card-manual {
    background: #fff;
    border: 1px solid rgba(255, 60, 0, 0.15) !important;
    transition: all 0.3s ease;
  }
  .card-manual:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(164, 4, 46, 0.15);
    border-color: rgba(164, 4, 46, 0.35) !important;
  }

  .pdf-icon-wrap {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, rgb(164,4,46), #ff8800);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .badge-vehiculo {
    background: rgba(255, 60, 0, 0.10);
    border: 1px solid rgba(255, 60, 0, 0.25);
    color: rgb(164, 4, 46);
    font-size: 0.70rem;
    font-weight: 600;
  }

  .badge-pieza {
    background: rgba(164, 4, 46, 0.08);
    border: 1px solid rgba(164, 4, 46, 0.2);
    color: rgb(164, 4, 46);
    font-size: 0.68rem;
  }

  .counter-badge {
    background: rgba(255, 60, 0, 0.08);
    border: 1px solid rgba(255, 60, 0, 0.3);
    color: rgb(164, 4, 46);
    font-weight: 600;
  }

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

  .btn-descargar {
    background: rgb(164, 4, 46);
    color: #fff;
    border: none;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border-radius: 50px;
    padding: 6px 16px;
  }
  .btn-descargar:hover {
    background: #a8032c;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(164, 4, 46, 0.35);
  }

  .section-divider {
    border-top: 2px solid rgba(255, 60, 0, 0.2);
    margin-bottom: 2rem;
  }

  .empty-icon { color: rgba(164, 4, 46, 0.25); }

  .fuente-text {
    font-size: 0.78rem;
    color: #888;
  }
</style>

<!-- ══════════════ HERO ══════════════ -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <?php if ($motorizacionId > 0): ?>
        <a href="entusiasta.php" class="btn btn-outline-light btn-volver">
          <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
      <?php else: ?>
        <a href="javascript:history.back()" class="btn btn-outline-light btn-volver">
          <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
      <?php endif; ?>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-4 fw-bold mb-2">
          <i class="fas fa-file-pdf me-3" style="color: rgba(255,60,0,0.9);"></i>
          <?php if ($motorizacionId > 0): ?>
            Manuales Compatibles
          <?php else: ?>
            Todos los Manuales
          <?php endif; ?>
        </h1>
        <?php if ($motorizacionId > 0 && $vehiculo !== ''): ?>
          <div class="mb-2">
            <span class="badge fs-6 px-3 py-2" style="background: linear-gradient(45deg, rgb(164,4,46), #ff8800);">
              <i class="fas fa-car me-2"></i><?= htmlspecialchars($vehiculo) ?>
            </span>
          </div>
          <p class="lead mb-0" style="color: rgba(255,255,255,0.65);">
            Manuales técnicos compatibles con tu vehículo
          </p>
        <?php else: ?>
          <p class="lead mb-0" style="color: rgba(255,255,255,0.65);">
            Catálogo completo de manuales técnicos de TuneFix
          </p>
        <?php endif; ?>
      </div>

      <div class="col-lg-6 mt-4 mt-lg-0">
        <form method="GET" action="">
          <?php if ($motorizacionId > 0): ?>
            <input type="hidden" name="motorizacion_id" value="<?= $motorizacionId ?>">
            <input type="hidden" name="vehiculo" value="<?= htmlspecialchars($vehiculo) ?>">
          <?php endif; ?>
          <div class="input-group input-group-lg">
            <input
              type="text"
              name="busqueda"
              class="form-control search-input"
              placeholder="<?= $motorizacionId > 0 ? 'Buscar entre manuales compatibles...' : 'Buscar por título, fuente o vehículo...' ?>"
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
                  ? 'todas-manuales.php?motorizacion_id=' . $motorizacionId . '&vehiculo=' . urlencode($vehiculo)
                  : 'todas-manuales.php';
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

<!-- ══════════════ CONTENIDO ══════════════ -->
<section class="content-section py-5">
  <div class="container">

    <div class="section-divider"></div>

    <!-- Contador -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <span class="badge counter-badge fs-6 px-3 py-2 rounded-pill">
        <i class="fas fa-book me-2"></i>
        <?php
          $total = count($manuales);
          if ($motorizacionId > 0 && $busqueda !== '') {
            echo $total . ' resultado' . ($total !== 1 ? 's' : '') . ' para "<em>' . htmlspecialchars($busqueda) . '</em>" en tu vehículo';
          } elseif ($motorizacionId > 0) {
            echo $total . ' manual' . ($total !== 1 ? 'es' : '') . ' compatible' . ($total !== 1 ? 's' : '') . ' con tu vehículo';
          } elseif ($busqueda !== '') {
            echo $total . ' resultado' . ($total !== 1 ? 's' : '') . ' para "<em>' . htmlspecialchars($busqueda) . '</em>"';
          } else {
            echo $total . ' manual' . ($total !== 1 ? 'es' : '') . ' en el catálogo';
          }
        ?>
      </span>
      <?php if ($busqueda !== ''): ?>
        <a href="todas-manuales.php<?= $motorizacionId > 0 ? '?motorizacion_id=' . $motorizacionId . '&vehiculo=' . urlencode($vehiculo) : '' ?>"
           class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-list me-1"></i> Ver todos
        </a>
      <?php endif; ?>
    </div>

    <!-- Sin resultados -->
    <?php if (empty($manuales)): ?>
      <div class="text-center py-5">
        <i class="fas fa-file-pdf fa-4x empty-icon mb-4 d-block"></i>
        <h4 class="text-black-50">No se encontraron manuales</h4>
        <?php if ($motorizacionId > 0): ?>
          <p class="text-black-50">No hay manuales registrados como compatibles con este vehículo aún.</p>
          <a href="todas-manuales.php" class="btn mt-2" style="background: rgb(164,4,46); color:#fff;">
            Ver catálogo completo
          </a>
        <?php elseif ($busqueda !== ''): ?>
          <p class="text-black-50">Ningún manual coincide con "<?= htmlspecialchars($busqueda) ?>".</p>
          <a href="todas-manuales.php" class="btn mt-2" style="background: rgb(164,4,46); color:#fff;">
            Ver todos los manuales
          </a>
        <?php else: ?>
          <p class="text-black-50">Aún no hay manuales registrados en el catálogo.</p>
        <?php endif; ?>
      </div>

    <!-- Lista de manuales -->
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($manuales as $manual): ?>
          <?php
            $vehiculoLabel = '';
            if (!empty($manual['marca_nombre']) && !empty($manual['modelo_nombre'])) {
              $vehiculoLabel = $manual['marca_nombre'] . ' ' . $manual['modelo_nombre'];
              if (!empty($manual['motor_nombre'])) {
                $vehiculoLabel .= ' · ' . $manual['motor_nombre'];
              }
            }
            $tienePdf = !empty($manual['archivo_url']);
          ?>
          <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm card-manual h-100">
              <div class="card-body p-4 d-flex flex-column gap-3">

                <!-- Cabecera: icono + título -->
                <div class="d-flex align-items-start gap-3">
                  <div class="pdf-icon-wrap">
                    <i class="fas fa-file-pdf fa-lg text-white"></i>
                  </div>
                  <div class="flex-grow-1 min-w-0">
                    <h6 class="fw-bold text-dark mb-1 lh-sm" style="font-size: 0.95rem;">
                      <?= htmlspecialchars($manual['titulo']) ?>
                    </h6>
                    <?php if (!empty($manual['fuente'])): ?>
                      <p class="fuente-text mb-0">
                        <i class="fas fa-user-pen me-1"></i><?= htmlspecialchars($manual['fuente']) ?>
                      </p>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Badges -->
                <div class="d-flex flex-wrap gap-2">
                  <?php if ($vehiculoLabel !== ''): ?>
                    <span class="badge badge-vehiculo rounded-pill">
                      <i class="fas fa-car me-1"></i><?= htmlspecialchars($vehiculoLabel) ?>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($manual['pieza_nombre'])): ?>
                    <span class="badge badge-pieza rounded-pill">
                      <i class="fas fa-cog me-1"></i><?= htmlspecialchars($manual['pieza_nombre']) ?>
                    </span>
                  <?php endif; ?>
                </div>

                <!-- Botón descarga -->
                <div class="mt-auto pt-1">
                  <?php if ($tienePdf): ?>
                    <a href="<?= htmlspecialchars($manual['archivo_url']) ?>"
                       class="btn btn-descargar"
                       target="_blank"
                       download>
                      <i class="fas fa-download me-2"></i>Descargar PDF
                    </a>
                  <?php else: ?>
                    <span class="text-black-50 small"><i class="fas fa-ban me-1"></i>Sin archivo disponible</span>
                  <?php endif; ?>
                </div>

              </div>
              <!-- Línea inferior roja-naranja -->
              <div style="height: 3px; background: linear-gradient(90deg, rgb(164,4,46), #ff8800); border-radius: 0 0 4px 4px;"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
