<style>
  /* ── Página entera: fondo oscuro como profesional ── */
  body { background: #0d0d1a; }

  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.70), rgba(0,0,0,0.85)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 50px;
  }

  .btn-volver {
    border-color: rgba(255,255,255,0.3);
    color: rgba(255,255,255,0.7);
    font-size: 0.85rem;
  }
  .btn-volver:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-color: rgba(255,255,255,0.6);
  }

  /* ── Tarjetas de resumen ── */
  .stat-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,60,0,0.2);
    border-radius: 16px;
    padding: 24px 20px;
    transition: all 0.3s;
  }
  .stat-card:hover {
    border-color: rgba(255,60,0,0.5);
    background: rgba(255,60,0,0.07);
    transform: translateY(-3px);
  }
  .stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
  }
  .stat-value {
    font-size: 1.9rem;
    font-weight: 800;
    line-height: 1;
    color: #fff;
  }
  .stat-label {
    font-size: 0.78rem;
    color: rgba(255,255,255,0.45);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-top: 4px;
  }

  /* ── Sección de contenido ── */
  .content-dark {
    background: #0d0d1a;
    padding: 50px 0 70px;
  }
  .section-title {
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.45);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.08);
  }

  /* ── Tarjeta de pedido ── */
  .pedido-card {
    background: #12121f;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.25s;
  }
  .pedido-card:hover { border-color: rgba(255,60,0,0.3); }

  .pedido-header {
    background: rgba(255,255,255,0.04);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
  }

  .badge-estado-pagado   { background: rgba(40,167,69,0.15);  color: #4cde7a;  border: 1px solid rgba(40,167,69,0.3); }
  .badge-estado-pendiente{ background: rgba(255,193,7,0.12);  color: #ffc107;  border: 1px solid rgba(255,193,7,0.3); }
  .badge-estado-cancelado{ background: rgba(220,53,69,0.12);  color: #dc3545;  border: 1px solid rgba(220,53,69,0.3); }

  .pedido-items { padding: 16px 20px; display: flex; flex-direction: column; gap: 12px; }

  .item-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 12px;
    background: rgba(255,255,255,0.03);
    border-radius: 10px;
  }
  .item-img {
    width: 52px; height: 52px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    background: rgba(255,255,255,0.05);
  }
  .item-ref {
    font-size: 0.68rem;
    background: rgba(164,4,46,0.2);
    border: 1px solid rgba(164,4,46,0.35);
    color: #ff6b6b;
    border-radius: 50px;
    padding: 1px 8px;
    white-space: nowrap;
  }
  .item-nombre { font-size: 0.88rem; font-weight: 600; color: #fff; }
  .item-precio { font-size: 0.82rem; color: rgba(255,255,255,0.5); }
  .item-subtotal { font-size: 0.95rem; font-weight: 700; color: #ff6b35; white-space: nowrap; }

  .pedido-footer {
    padding: 12px 20px;
    border-top: 1px solid rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .comprador-info { font-size: 0.8rem; color: rgba(255,255,255,0.4); }
  .pedido-total   { font-size: 1rem; font-weight: 800; color: #ff6b35; }

  /* ── Tarjetas de piezas ── */
  .pieza-card {
    background: #12121f;
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.25s;
  }
  .pieza-card:hover {
    border-color: rgba(255,60,0,0.3);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
  }
  .pieza-img {
    width: 100%; height: 140px;
    object-fit: cover;
    background: rgba(255,255,255,0.03);
  }
  .pieza-body { padding: 14px; }
  .pieza-ref-badge {
    font-size: 0.65rem;
    background: rgba(164,4,46,0.2);
    border: 1px solid rgba(164,4,46,0.35);
    color: #ff6b6b;
    border-radius: 50px;
    padding: 2px 8px;
    display: inline-block;
    margin-bottom: 6px;
  }
  .pieza-nombre { font-size: 0.88rem; font-weight: 700; color: #fff; margin-bottom: 10px; line-height: 1.3; }
  .pieza-stat-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: rgba(255,255,255,0.45);
    border-top: 1px solid rgba(255,255,255,0.06);
    padding-top: 10px;
    margin-top: 8px;
  }
  .pieza-ingreso { font-size: 0.9rem; font-weight: 700; color: #ff6b35; }
  .badge-activa   { background: rgba(40,167,69,0.15);  color: #4cde7a;  border: 1px solid rgba(40,167,69,0.3);  font-size: 0.65rem; }
  .badge-pausada  { background: rgba(255,193,7,0.12);  color: #ffc107;  border: 1px solid rgba(255,193,7,0.3);   font-size: 0.65rem; }

  .empty-state { color: rgba(255,255,255,0.2); }

  /* ── Pestañas de filtro por estado ── */
  .estado-tab {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 50px;
    color: rgba(255,255,255,0.5);
    font-size: 0.8rem;
    font-weight: 600;
    padding: 5px 16px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .estado-tab:hover, .estado-tab.activo {
    background: rgba(255,60,0,0.15);
    border-color: rgba(255,60,0,0.4);
    color: #ff6b35;
  }
</style>

<!-- ══════════════ HERO ══════════════ -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="profesional.php" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Volver al modo profesional
      </a>
    </div>
    <div class="row align-items-end">
      <div class="col-lg-7">
        <span class="badge mb-3 px-3 py-2" style="background:linear-gradient(45deg,rgb(164,4,46),#ff3c00);font-size:0.75rem;letter-spacing:1px;">
          <i class="fas fa-crown me-1"></i> PROFESIONAL
        </span>
        <h1 class="display-4 fw-bold mb-2">Mis <span style="color:#ff6b35;">Ventas</span></h1>
        <p class="mb-0" style="color:rgba(255,255,255,0.55);">
          Piezas que has publicado y pedidos de tus clientes
        </p>
      </div>

      <!-- Tarjetas de resumen -->
      <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="row g-3">
          <div class="col-6">
            <div class="stat-card">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="stat-icon" style="background:rgba(255,60,0,0.15);">
                  <i class="fas fa-cog" style="color:#ff6b35;"></i>
                </div>
                <div>
                  <div class="stat-value"><?= (int)($resumen['total_piezas'] ?? 0) ?></div>
                  <div class="stat-label">Piezas subidas</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-card">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="stat-icon" style="background:rgba(40,167,69,0.12);">
                  <i class="fas fa-shopping-bag" style="color:#4cde7a;"></i>
                </div>
                <div>
                  <div class="stat-value"><?= (int)($resumen['total_pedidos'] ?? 0) ?></div>
                  <div class="stat-label">Pedidos pagados</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-card">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="stat-icon" style="background:rgba(255,193,7,0.12);">
                  <i class="fas fa-clock" style="color:#ffc107;"></i>
                </div>
                <div>
                  <div class="stat-value"><?= (int)($resumen['pedidos_pendientes'] ?? 0) ?></div>
                  <div class="stat-label">Pendientes</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-card">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="stat-icon" style="background:rgba(164,4,46,0.15);">
                  <i class="fas fa-euro-sign" style="color:#ff6b35;"></i>
                </div>
                <div>
                  <div class="stat-value"><?= number_format((float)($resumen['total_facturado'] ?? 0), 0, ',', '.') ?>€</div>
                  <div class="stat-label">Facturado</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════ CONTENIDO ══════════════ -->
<section class="content-dark">
  <div class="container">

    <!-- ── PEDIDOS RECIBIDOS ── -->
    <div class="section-title">
      <i class="fas fa-shopping-bag" style="color:#ff6b35;"></i> Pedidos recibidos
    </div>

    <?php if (empty($pedidos)): ?>
      <div class="text-center py-5 mb-5">
        <i class="fas fa-inbox fa-4x empty-state mb-3 d-block"></i>
        <p class="empty-state fs-5">Aún no has recibido pedidos de tus piezas.</p>
        <a href="subir-pieza.php" class="btn mt-2 px-4"
           style="background:linear-gradient(45deg,rgb(164,4,46),#ff3c00);color:#fff;border-radius:50px;font-weight:700;">
          <i class="fas fa-plus me-2"></i>Subir una pieza
        </a>
      </div>
    <?php else: ?>

      <!-- Filtros por estado -->
      <div class="d-flex gap-2 flex-wrap mb-4">
        <button class="estado-tab activo" onclick="filtrarEstado('todos', this)">
          Todos (<?= count($pedidos) ?>)
        </button>
        <?php
          $estados = ['pagado' => 0, 'pendiente' => 0, 'cancelado' => 0];
          foreach ($pedidos as $p) {
            if (isset($estados[$p['estado']])) $estados[$p['estado']]++;
          }
          $labels = ['pagado' => 'Pagados', 'pendiente' => 'Pendientes', 'cancelado' => 'Cancelados'];
          foreach ($estados as $est => $cnt):
            if ($cnt === 0) continue;
        ?>
        <button class="estado-tab" onclick="filtrarEstado('<?= $est ?>', this)">
          <?= $labels[$est] ?> (<?= $cnt ?>)
        </button>
        <?php endforeach; ?>
      </div>

      <div class="d-flex flex-column gap-3 mb-5" id="lista-pedidos">
        <?php foreach ($pedidos as $pedido): ?>
          <?php
            $estadoClass = match($pedido['estado']) {
              'pagado'    => 'badge-estado-pagado',
              'pendiente' => 'badge-estado-pendiente',
              default     => 'badge-estado-cancelado',
            };
            $estadoIcon = match($pedido['estado']) {
              'pagado'    => 'fa-check-circle',
              'pendiente' => 'fa-clock',
              default     => 'fa-times-circle',
            };
          ?>
          <div class="pedido-card" data-estado="<?= $pedido['estado'] ?>">
            <!-- Cabecera del pedido -->
            <div class="pedido-header">
              <div class="d-flex align-items-center gap-3">
                <span style="color:rgba(255,255,255,0.35);font-size:0.8rem;">
                  <i class="fas fa-hashtag me-1"></i><?= $pedido['pedido_id'] ?>
                </span>
                <span class="badge <?= $estadoClass ?> rounded-pill px-3 py-1">
                  <i class="fas <?= $estadoIcon ?> me-1"></i>
                  <?= ucfirst($pedido['estado']) ?>
                </span>
              </div>
              <div class="d-flex align-items-center gap-3">
                <span style="color:rgba(255,255,255,0.3);font-size:0.78rem;">
                  <i class="fas fa-calendar me-1"></i>
                  <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?>
                </span>
              </div>
            </div>

            <!-- Ítems del pedido -->
            <div class="pedido-items">
              <?php foreach ($pedido['items'] as $item):
                $noImg = 'public/img/no-image.svg';
                $img   = !empty($item['pieza_imagen']) ? $item['pieza_imagen'] : $noImg;
              ?>
              <div class="item-row">
                <img src="<?= htmlspecialchars($img) ?>"
                     class="item-img"
                     alt="<?= htmlspecialchars($item['pieza_nombre']) ?>"
                     onerror="this.onerror=null;this.src='<?= $noImg ?>'">
                <div class="flex-grow-1 min-w-0">
                  <div class="mb-1">
                    <span class="item-ref"><?= htmlspecialchars($item['pieza_ref']) ?></span>
                  </div>
                  <div class="item-nombre"><?= htmlspecialchars($item['pieza_nombre']) ?></div>
                  <div class="item-precio">
                    <?= number_format((float)$item['precio_u'], 2) ?> € × <?= (int)$item['cantidad'] ?> ud.
                  </div>
                </div>
                <div class="item-subtotal">
                  <?= number_format((float)$item['subtotal'], 2) ?> €
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Pie del pedido -->
            <div class="pedido-footer">
              <div class="comprador-info">
                <i class="fas fa-user me-1"></i>
                <?= htmlspecialchars($pedido['comprador']) ?>
              </div>
              <div class="pedido-total">
                Total mis piezas: <?= number_format((float)$pedido['subtotal'], 2) ?> €
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- ── MIS PIEZAS PUBLICADAS ── -->
    <div class="section-title mt-3">
      <i class="fas fa-cog" style="color:#ff6b35;"></i> Mis piezas publicadas
      <a href="subir-pieza.php" class="btn btn-sm ms-auto"
         style="background:rgba(255,60,0,0.15);border:1px solid rgba(255,60,0,0.35);color:#ff6b35;border-radius:50px;font-size:0.75rem;font-weight:700;">
        <i class="fas fa-plus me-1"></i> Subir pieza
      </a>
    </div>

    <?php if (empty($misPiezas)): ?>
      <div class="text-center py-4">
        <p class="empty-state">Aún no has publicado ninguna pieza.</p>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($misPiezas as $pz):
          $noImg = 'public/img/no-image.svg';
          $img   = !empty($pz['imagen']) ? $pz['imagen'] : $noImg;
        ?>
        <div class="col-sm-6 col-lg-4 col-xl-3">
          <div class="pieza-card h-100">
            <img src="<?= htmlspecialchars($img) ?>"
                 class="pieza-img"
                 alt="<?= htmlspecialchars($pz['nombre']) ?>"
                 onerror="this.onerror=null;this.src='<?= $noImg ?>'">
            <div class="pieza-body">
              <span class="pieza-ref-badge"><?= htmlspecialchars($pz['referencia']) ?></span>
              <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                <div class="pieza-nombre"><?= htmlspecialchars($pz['nombre']) ?></div>
                <?php if ((int)$pz['activa']): ?>
                  <span class="badge badge-activa rounded-pill px-2 py-1 flex-shrink-0">Activa</span>
                <?php else: ?>
                  <span class="badge badge-pausada rounded-pill px-2 py-1 flex-shrink-0">Pausada</span>
                <?php endif; ?>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <span style="color:rgba(255,255,255,0.35);font-size:0.78rem;">
                  <?= number_format((float)$pz['precio'], 2) ?> € · Stock: <?= (int)$pz['stock'] ?>
                </span>
                <span class="pieza-ingreso">
                  <?= number_format((float)$pz['ingresos_generados'], 2) ?> €
                </span>
              </div>
              <div class="pieza-stat-row">
                <span><i class="fas fa-shopping-cart me-1"></i><?= (int)$pz['veces_en_pedido'] ?> pedidos</span>
                <span><i class="fas fa-boxes me-1"></i><?= (int)$pz['unidades_vendidas'] ?> uds. vendidas</span>
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
function filtrarEstado(estado, btn) {
  document.querySelectorAll('.estado-tab').forEach(b => b.classList.remove('activo'));
  btn.classList.add('activo');

  document.querySelectorAll('#lista-pedidos .pedido-card').forEach(card => {
    if (estado === 'todos' || card.dataset.estado === estado) {
      card.style.display = '';
    } else {
      card.style.display = 'none';
    }
  });
}
</script>
