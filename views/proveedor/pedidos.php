<?php
/* Variables disponibles: $proveedor, $resumen, $pedidos */
?>

<!-- ── Tarjetas de resumen ── -->
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:var(--accent-dim);">
        <i class="fas fa-shopping-bag" style="color:var(--accent);"></i>
      </div>
      <div>
        <div class="stat-value"><?= (int)($resumen['total_pedidos'] ?? 0) ?></div>
        <div class="stat-label">Total pedidos</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(25,135,84,0.15);">
        <i class="fas fa-check-circle" style="color:#22c55e;"></i>
      </div>
      <div>
        <div class="stat-value"><?= (int)($resumen['pedidos_pagados'] ?? 0) ?></div>
        <div class="stat-label">Pagados</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(245,158,11,0.12);">
        <i class="fas fa-clock" style="color:#c4920a;"></i>
      </div>
      <div>
        <div class="stat-value"><?= (int)($resumen['pedidos_pendientes'] ?? 0) ?></div>
        <div class="stat-label">Pendientes</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:rgba(99,102,241,0.15);">
        <i class="fas fa-euro-sign" style="color:#7c87d8;"></i>
      </div>
      <div>
        <div class="stat-value"><?= number_format((float)($resumen['total_facturado'] ?? 0), 0, ',', '.') ?>€</div>
        <div class="stat-label">Facturado</div>
      </div>
    </div>
  </div>
</div>

<!-- ── Filtros de estado ── -->
<?php if (!empty($pedidos)): ?>
<div class="d-flex gap-2 flex-wrap mb-3">
  <button class="btn btn-sm btn-ghost estado-tab activo" onclick="filtrar('todos', this)">
    Todos (<?= count($pedidos) ?>)
  </button>
  <?php
    $conteos = ['pagado' => 0, 'pendiente' => 0, 'cancelado' => 0];
    foreach ($pedidos as $p) { if (isset($conteos[$p['estado']])) $conteos[$p['estado']]++; }
    $etiquetas = ['pagado' => 'Pagados', 'pendiente' => 'Pendientes', 'cancelado' => 'Cancelados'];
    foreach ($conteos as $est => $cnt):
      if ($cnt === 0) continue;
  ?>
  <button class="btn btn-sm btn-ghost estado-tab" onclick="filtrar('<?= $est ?>', this)">
    <?= $etiquetas[$est] ?> (<?= $cnt ?>)
  </button>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ── Lista de pedidos ── -->
<?php if (empty($pedidos)): ?>
  <div class="admin-card">
    <div class="text-center py-5">
      <i class="fas fa-inbox fa-3x mb-3" style="color:var(--text-faint);"></i>
      <p style="color:var(--text-muted);">Aún no hay pedidos de tus piezas.</p>
      <a href="proveedor.php?page=publicar-pieza" class="btn btn-red">
        <i class="fas fa-plus me-1"></i> Publicar una pieza
      </a>
    </div>
  </div>
<?php else: ?>
  <div id="lista-pedidos" style="display:flex;flex-direction:column;gap:12px;">
    <?php foreach ($pedidos as $pedido): ?>
      <?php
        $estadoClass = match($pedido['estado']) {
          'pagado'    => 'badge-activa',
          'pendiente' => 'badge-pausada',
          default     => 'badge-sinstock',
        };
        $estadoIcon = match($pedido['estado']) {
          'pagado'    => 'fa-check-circle',
          'pendiente' => 'fa-clock',
          default     => 'fa-times-circle',
        };
      ?>
      <div class="admin-card" data-estado="<?= $pedido['estado'] ?>">

        <!-- Cabecera del pedido -->
        <div class="admin-card-header">
          <div class="d-flex align-items-center gap-3 flex-wrap">
            <span style="color:var(--text-muted);font-size:0.8rem;font-weight:600;">
              <i class="fas fa-hashtag me-1"></i><?= $pedido['pedido_id'] ?>
            </span>
            <span class="<?= $estadoClass ?>" style="display:inline-flex;align-items:center;gap:5px;">
              <i class="fas <?= $estadoIcon ?>"></i>
              <?= ucfirst($pedido['estado']) ?>
            </span>
            <span style="color:var(--text-muted);font-size:0.78rem;">
              <i class="fas fa-user me-1"></i><?= htmlspecialchars($pedido['comprador']) ?>
            </span>
          </div>
          <div style="color:var(--text-muted);font-size:0.78rem;">
            <i class="fas fa-calendar me-1"></i>
            <?= date('d/m/Y H:i', strtotime($pedido['fecha'])) ?>
          </div>
        </div>

        <!-- Ítems del pedido -->
        <table class="table table-prov mb-0">
          <thead>
            <tr>
              <th>Pieza</th>
              <th>Referencia</th>
              <th class="text-center">Cantidad</th>
              <th class="text-end">Precio u.</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pedido['items'] as $item):
              $noImg = 'public/img/no-image.svg';
              $img   = !empty($item['pieza_imagen']) ? $item['pieza_imagen'] : $noImg;
            ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="<?= htmlspecialchars($img) ?>"
                       class="pieza-thumb"
                       alt="<?= htmlspecialchars($item['pieza_nombre']) ?>"
                       onerror="this.onerror=null;this.src='<?= $noImg ?>'">
                  <span style="font-weight:600;color:var(--text);"><?= htmlspecialchars($item['pieza_nombre']) ?></span>
                </div>
              </td>
              <td>
                <span style="background:var(--accent-dim);border:1px solid var(--accent-glow);color:var(--accent);
                             border-radius:50px;padding:2px 10px;font-size:0.72rem;white-space:nowrap;">
                  <?= htmlspecialchars($item['pieza_ref']) ?>
                </span>
              </td>
              <td class="text-center" style="color:var(--text-muted);"><?= (int)$item['cantidad'] ?> ud.</td>
              <td class="text-end" style="color:var(--text-muted);"><?= number_format((float)$item['precio_u'], 2) ?> €</td>
              <td class="text-end" style="font-weight:700;color:#22c55e;">
                <?= number_format((float)$item['subtotal'], 2) ?> €
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" class="text-end" style="color:var(--text-muted);font-size:0.8rem;">
                Total de mis piezas en este pedido
              </td>
              <td class="text-end" style="font-weight:800;color:#22c55e;font-size:1rem;">
                <?= number_format((float)$pedido['subtotal'], 2) ?> €
              </td>
            </tr>
          </tfoot>
        </table>

      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<style>
  .estado-tab.activo {
    background: var(--accent-dim) !important;
    border-color: var(--accent-glow) !important;
    color: var(--accent) !important;
  }
  .table-prov tfoot td {
    background: var(--surface);
    border-color: var(--border-soft);
    padding: 10px 16px;
    font-size: 0.87rem;
  }
</style>

<script>
function filtrar(estado, btn) {
  document.querySelectorAll('.estado-tab').forEach(b => b.classList.remove('activo'));
  btn.classList.add('activo');
  document.querySelectorAll('#lista-pedidos .admin-card').forEach(card => {
    card.style.display = (estado === 'todos' || card.dataset.estado === estado) ? '' : 'none';
  });
}
</script>
