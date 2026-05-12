<?php
$empresa     = htmlspecialchars($proveedor['nombre_empresa'] ?? '');
$responsable = htmlspecialchars($proveedor['nombre_responsable'] ?? '');
$inicial     = strtoupper(mb_substr($proveedor['nombre_empresa'] ?? 'P', 0, 1));

$totalPiezas    = (int)($stats['total']     ?? 0);
$piezasActivas  = (int)($stats['activas']   ?? 0);
$sinStock       = (int)($stats['sin_stock'] ?? 0);
$piezasPausadas = $totalPiezas - $piezasActivas;

$pedidosPagados    = (int)($resumenPedidos['pedidos_pagados']    ?? 0);
$pedidosPendientes = (int)($resumenPedidos['pedidos_pendientes'] ?? 0);
$facturado         = (float)($resumenPedidos['total_facturado']  ?? 0);
?>

<!-- ══ HERO ══════════════════════════════════════════════════════════════ -->
<div style="background:linear-gradient(135deg,#0f1623 0%,#1a1030 50%,#0f1623 100%);
     border:1px solid rgba(154,18,48,0.28);border-radius:18px;padding:32px 36px;
     margin-bottom:28px;position:relative;overflow:hidden;
     box-shadow:0 4px 24px rgba(0,0,0,0.4);">

  <div style="position:absolute;inset:0;pointer-events:none;overflow:hidden;border-radius:18px;">
    <div style="position:absolute;top:-60px;right:-60px;width:220px;height:220px;
         border-radius:50%;background:radial-gradient(circle,rgba(154,18,48,0.18) 0%,transparent 70%);"></div>
    <div style="position:absolute;bottom:-40px;left:30%;width:160px;height:160px;
         border-radius:50%;background:radial-gradient(circle,rgba(100,20,60,0.14) 0%,transparent 70%);"></div>
  </div>

  <div style="position:relative;display:flex;align-items:center;gap:22px;flex-wrap:wrap;">

    <div style="width:68px;height:68px;border-radius:18px;flex-shrink:0;
         background:linear-gradient(135deg,#8b0226,rgb(154,18,48));
         display:flex;align-items:center;justify-content:center;
         font-size:1.8rem;font-weight:900;color:#fff;
         box-shadow:0 8px 24px rgba(154,18,48,0.30);">
      <?= $inicial ?>
    </div>

    <div style="flex:1;min-width:0;">
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:4px;">
        <h2 style="margin:0;color:#fff;font-size:1.45rem;font-weight:800;letter-spacing:-.5px;">
          <?= $empresa ?>
        </h2>
        <span style="background:rgba(196,146,10,0.15);border:1px solid rgba(196,146,10,0.40);
               color:#fbbf24;font-size:.68rem;font-weight:700;padding:3px 10px;
               border-radius:20px;letter-spacing:.5px;white-space:nowrap;">
          <i class="fas fa-check-circle me-1"></i>VERIFICADO
        </span>
      </div>
      <p style="margin:0;color:rgba(255,255,255,0.42);font-size:.88rem;">
        Bienvenido de nuevo, <span style="color:rgba(255,255,255,0.75);font-weight:600;"><?= $responsable ?></span>
      </p>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="proveedor.php?page=publicar-pieza" class="btn-red btn"
         style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;font-size:.85rem;">
        <i class="fas fa-plus"></i> Nueva pieza
      </a>
      <a href="proveedor.php?page=pedidos" class="btn-ghost btn"
         style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;font-size:.85rem;">
        <i class="fas fa-shopping-bag"></i> Pedidos
      </a>
    </div>
  </div>

  <div style="position:relative;margin-top:24px;padding-top:20px;
       border-top:1px solid rgba(255,255,255,0.07);
       display:flex;gap:28px;flex-wrap:wrap;">
    <div>
      <div style="font-size:1.3rem;font-weight:800;color:#fff;line-height:1;"><?= $facturado > 0 ? number_format($facturado,0,',','.') . ' €' : '—' ?></div>
      <div style="font-size:.72rem;color:rgba(255,255,255,0.38);margin-top:3px;text-transform:uppercase;letter-spacing:.8px;">Facturado total</div>
    </div>
    <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
    <div>
      <div style="font-size:1.3rem;font-weight:800;color:#fff;line-height:1;"><?= $pedidosPagados ?></div>
      <div style="font-size:.72rem;color:rgba(255,255,255,0.38);margin-top:3px;text-transform:uppercase;letter-spacing:.8px;">Pedidos pagados</div>
    </div>
    <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
    <div>
      <div style="font-size:1.3rem;font-weight:800;color:<?= $pedidosPendientes > 0 ? '#fbbf24' : '#fff' ?>;line-height:1;"><?= $pedidosPendientes ?></div>
      <div style="font-size:.72rem;color:rgba(255,255,255,0.38);margin-top:3px;text-transform:uppercase;letter-spacing:.8px;">Pendientes de cobro</div>
    </div>
    <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
    <div>
      <div style="font-size:1.3rem;font-weight:800;color:#fff;line-height:1;"><?= $totalPiezas ?></div>
      <div style="font-size:.72rem;color:rgba(255,255,255,0.38);margin-top:3px;text-transform:uppercase;letter-spacing:.8px;">Piezas publicadas</div>
    </div>
  </div>
</div>

<!-- ══ TARJETAS DE MÉTRICAS ════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card" style="position:relative;overflow:hidden;">
      <div class="stat-icon" style="background:rgba(154,18,48,0.18);">
        <i class="fas fa-boxes" style="color:#ff6b6b;"></i>
      </div>
      <div>
        <div class="stat-value"><?= $totalPiezas ?></div>
        <div class="stat-label">Piezas publicadas</div>
      </div>
      <a href="proveedor.php?page=mis-piezas"
         style="position:absolute;bottom:12px;right:14px;font-size:.7rem;
                color:rgba(255,255,255,0.22);text-decoration:none;transition:color .2s;"
         onmouseover="this.style.color='rgba(255,255,255,0.6)'"
         onmouseout="this.style.color='rgba(255,255,255,0.22)'">
        Ver todas <i class="fas fa-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card" style="position:relative;overflow:hidden;">
      <div class="stat-icon" style="background:rgba(22,163,74,0.15);">
        <i class="fas fa-check-circle" style="color:#4ade80;"></i>
      </div>
      <div>
        <div class="stat-value"><?= $piezasActivas ?></div>
        <div class="stat-label">Piezas activas</div>
      </div>
      <?php if ($totalPiezas > 0): ?>
      <div style="position:absolute;bottom:0;left:0;right:0;height:3px;background:rgba(255,255,255,0.05);border-radius:0 0 12px 12px;">
        <div style="height:100%;width:<?= min(100, round($piezasActivas/$totalPiezas*100)) ?>%;
             background:rgba(22,163,74,0.60);border-radius:0 0 12px 12px;transition:width .4s;"></div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card" style="position:relative;overflow:hidden;">
      <div class="stat-icon" style="background:rgba(234,179,8,0.12);">
        <i class="fas fa-exclamation-triangle" style="color:#fbbf24;"></i>
      </div>
      <div>
        <div class="stat-value" style="<?= $sinStock > 0 ? 'color:#fbbf24;' : '' ?>"><?= $sinStock ?></div>
        <div class="stat-label">Sin stock</div>
      </div>
      <?php if ($sinStock > 0): ?>
      <a href="proveedor.php?page=mis-piezas"
         style="position:absolute;bottom:12px;right:14px;font-size:.7rem;
                color:rgba(251,191,36,0.45);text-decoration:none;transition:color .2s;"
         onmouseover="this.style.color='rgba(251,191,36,0.9)'"
         onmouseout="this.style.color='rgba(251,191,36,0.45)'">
        Gestionar <i class="fas fa-arrow-right ms-1"></i>
      </a>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="stat-card" style="position:relative;overflow:hidden;">
      <div class="stat-icon" style="background:rgba(154,18,48,0.15);">
        <i class="fas fa-euro-sign" style="color:#ff6b35;"></i>
      </div>
      <div>
        <div class="stat-value" style="font-size:<?= $facturado >= 10000 ? '1.35rem' : '1.8rem' ?>;">
          <?= $facturado > 0 ? number_format($facturado, 0, ',', '.') . ' €' : '0 €' ?>
        </div>
        <div class="stat-label">Total facturado</div>
      </div>
    </div>
  </div>

</div>

<!-- ══ FILA PRINCIPAL ════════════════════════════════════════════════════ -->
<div class="row g-4">

  <!-- Últimas piezas -->
  <div class="col-xl-8">
    <div class="admin-card h-100">
      <div class="admin-card-header">
        <div class="admin-card-title">
          <i class="fas fa-boxes"></i>
          Últimas piezas publicadas
        </div>
        <a href="proveedor.php?page=mis-piezas" class="btn-ghost btn btn-sm"
           style="font-size:.78rem;text-decoration:none;">
          Ver todas
        </a>
      </div>

      <?php if (empty($ultimasPiezas)): ?>
        <div style="padding:52px 20px;text-align:center;">
          <div style="width:64px;height:64px;background:rgba(154,18,48,0.10);border-radius:16px;
               display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <i class="fas fa-box-open" style="font-size:1.6rem;color:rgba(154,18,48,0.40);"></i>
          </div>
          <p style="color:rgba(255,255,255,0.30);margin:0 0 16px;font-size:.9rem;">
            Aún no has publicado ninguna pieza
          </p>
          <a href="proveedor.php?page=publicar-pieza" class="btn-red btn btn-sm">
            <i class="fas fa-plus me-1"></i> Publicar primera pieza
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-prov mb-0">
            <thead>
              <tr>
                <th style="width:52px;"></th>
                <th>Pieza</th>
                <th>Precio</th>
                <th class="text-center">Stock</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($ultimasPiezas as $p): ?>
              <tr>
                <td>
                  <?php if (!empty($p['imagen'])): ?>
                    <img src="<?= htmlspecialchars($p['imagen']) ?>" class="pieza-thumb" alt="">
                  <?php else: ?>
                    <div class="pieza-thumb d-flex align-items-center justify-content-center"
                         style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.20);">
                      <i class="fas fa-image"></i>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="font-weight:700;color:#e2e8f0;font-size:.88rem;line-height:1.3;">
                    <?= htmlspecialchars($p['nombre']) ?>
                  </div>
                  <?php if (!empty($p['referencia_oem'])): ?>
                  <div style="font-size:.72rem;color:rgba(255,255,255,0.28);margin-top:2px;font-family:monospace;">
                    <?= htmlspecialchars($p['referencia_oem']) ?>
                  </div>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="color:#ff6b35;font-weight:700;font-size:.88rem;">
                    <?= number_format((float)$p['precio'], 2, ',', '.') ?> €
                  </span>
                </td>
                <td class="text-center">
                  <span style="font-size:.82rem;color:<?= (int)$p['stock'] === 0 ? '#fbbf24' : 'rgba(255,255,255,0.55)' ?>;">
                    <?= (int)$p['stock'] ?> ud.
                  </span>
                </td>
                <td>
                  <?php if (!$p['activa']): ?>
                    <span class="badge-pausada">Pausada</span>
                  <?php elseif ((int)$p['stock'] === 0): ?>
                    <span class="badge-sinstock">Sin stock</span>
                  <?php else: ?>
                    <span class="badge-activa">Activa</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Panel lateral derecho -->
  <div class="col-xl-4 d-flex flex-column gap-3">

    <!-- Acciones rápidas -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-bolt"></i> Acciones rápidas</div>
      </div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:8px;">

        <a href="proveedor.php?page=publicar-pieza"
           style="display:flex;align-items:center;gap:12px;padding:12px 14px;
                  background:rgba(154,18,48,0.12);border:1px solid rgba(154,18,48,0.30);
                  border-radius:10px;text-decoration:none;color:#fff;
                  transition:background .2s,border-color .2s;"
           onmouseover="this.style.background='rgba(154,18,48,0.20)';this.style.borderColor='rgba(154,18,48,0.40)'"
           onmouseout="this.style.background='rgba(154,18,48,0.12)';this.style.borderColor='rgba(154,18,48,0.30)'">
          <div style="width:36px;height:36px;background:rgba(154,18,48,0.22);border-radius:9px;
               display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-plus" style="color:#ff6b6b;font-size:.85rem;"></i>
          </div>
          <div>
            <div style="font-size:.85rem;font-weight:700;">Publicar nueva pieza</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,0.38);">Añade stock a tu catálogo</div>
          </div>
        </a>

        <a href="proveedor.php?page=pedidos"
           style="display:flex;align-items:center;gap:12px;padding:12px 14px;
                  background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);
                  border-radius:10px;text-decoration:none;color:#fff;
                  transition:background .2s;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'"
           onmouseout="this.style.background='rgba(255,255,255,0.04)'">
          <div style="width:36px;height:36px;background:rgba(234,179,8,0.10);border-radius:9px;
               display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-shopping-bag" style="color:#fbbf24;font-size:.85rem;"></i>
          </div>
          <div style="flex:1;">
            <div style="font-size:.85rem;font-weight:700;display:flex;align-items:center;gap:6px;">
              Mis pedidos
              <?php if ($pedidosPendientes > 0): ?>
              <span style="background:rgba(234,179,8,0.18);border:1px solid rgba(234,179,8,0.38);
                     color:#fbbf24;font-size:.62rem;font-weight:800;padding:1px 6px;border-radius:4px;">
                <?= $pedidosPendientes ?>
              </span>
              <?php endif; ?>
            </div>
            <div style="font-size:.72rem;color:rgba(255,255,255,0.38);">Revisa los pedidos recibidos</div>
          </div>
        </a>

        <a href="proveedor.php?page=estadisticas"
           style="display:flex;align-items:center;gap:12px;padding:12px 14px;
                  background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);
                  border-radius:10px;text-decoration:none;color:#fff;
                  transition:background .2s;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'"
           onmouseout="this.style.background='rgba(255,255,255,0.04)'">
          <div style="width:36px;height:36px;background:rgba(14,165,233,0.10);border-radius:9px;
               display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-chart-line" style="color:#38bdf8;font-size:.85rem;"></i>
          </div>
          <div>
            <div style="font-size:.85rem;font-weight:700;">Estadísticas</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,0.38);">Ventas y rendimiento</div>
          </div>
        </a>

        <a href="proveedor.php?page=perfil"
           style="display:flex;align-items:center;gap:12px;padding:12px 14px;
                  background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);
                  border-radius:10px;text-decoration:none;color:#fff;
                  transition:background .2s;"
           onmouseover="this.style.background='rgba(255,255,255,0.08)'"
           onmouseout="this.style.background='rgba(255,255,255,0.04)'">
          <div style="width:36px;height:36px;background:rgba(124,58,237,0.10);border-radius:9px;
               display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-building" style="color:#a78bfa;font-size:.85rem;"></i>
          </div>
          <div>
            <div style="font-size:.85rem;font-weight:700;">Mi perfil de empresa</div>
            <div style="font-size:.72rem;color:rgba(255,255,255,0.38);">Información y contacto</div>
          </div>
        </a>

      </div>
    </div>

    <!-- Resumen de inventario -->
    <div class="admin-card">
      <div class="admin-card-header">
        <div class="admin-card-title"><i class="fas fa-layer-group"></i> Inventario</div>
      </div>
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px;">
        <?php
        $inventario = [
          ['Piezas activas',  $piezasActivas,  $totalPiezas, '#4ade80', 'rgba(22,163,74,0.60)'],
          ['Pausadas',        $piezasPausadas, $totalPiezas, '#fbbf24', 'rgba(234,179,8,0.60)'],
          ['Sin stock',       $sinStock,       $totalPiezas, '#f87171', 'rgba(239,68,68,0.60)'],
        ];
        foreach ($inventario as [$label, $val, $total, $color, $barColor]):
          $pct = $total > 0 ? round($val / $total * 100) : 0;
        ?>
        <div>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
            <span style="font-size:.8rem;color:rgba(255,255,255,0.48);"><?= $label ?></span>
            <span style="font-size:.82rem;font-weight:700;color:<?= $color ?>;"><?= $val ?></span>
          </div>
          <div style="background:rgba(255,255,255,0.07);border-radius:20px;height:5px;overflow:hidden;">
            <div style="width:<?= $pct ?>%;height:100%;background:<?= $barColor ?>;border-radius:20px;transition:width .5s;"></div>
          </div>
        </div>
        <?php endforeach; ?>

        <?php if ($totalPiezas === 0): ?>
        <p style="color:rgba(255,255,255,0.28);font-size:.82rem;text-align:center;margin:8px 0 0;">
          Sin piezas publicadas aún.
        </p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
