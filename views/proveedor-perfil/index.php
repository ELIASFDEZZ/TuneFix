<style>
  .prov-hero {
    background: linear-gradient(180deg, #0d0d0d 0%, #12121f 100%);
    padding: 48px 0 40px;
    border-bottom: 1px solid rgba(255,136,0,0.15);
  }
  .prov-avatar {
    width: 90px; height: 90px;
    background: linear-gradient(135deg, #ffc107, #ff8800);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem; font-weight: 800; color: #fff;
    border: 3px solid rgba(255,255,255,0.12);
    flex-shrink: 0;
    user-select: none;
  }
  .prov-stat-val {
    font-size: 1.35rem; font-weight: 700; color: #fff; line-height: 1;
  }
  .prov-stat-label {
    font-size: 0.7rem; color: rgba(255,255,255,0.4);
    text-transform: uppercase; letter-spacing: 0.8px; margin-top: 3px;
  }
  .prov-stat-sep {
    width: 1px; background: rgba(255,255,255,0.1); align-self: stretch;
  }
  .prov-content {
    background: #0d0d0d; flex: 1; padding: 40px 0 60px;
  }
  .badge-cnt-prov {
    background: rgba(255,136,0,0.08);
    border: 1px solid rgba(255,136,0,0.25);
    color: rgba(255,136,0,0.9);
    font-size: 0.8rem; padding: 6px 14px; border-radius: 50px;
  }
  .card-pieza-prov {
    background: #1a1a2e;
    border: 1px solid rgba(255,136,0,0.12);
    border-radius: 12px;
    overflow: hidden;
    transition: all .3s;
    height: 100%;
    display: flex; flex-direction: column;
    text-decoration: none;
  }
  .card-pieza-prov:hover {
    transform: translateY(-5px);
    border-color: rgba(255,136,0,0.45);
    box-shadow: 0 14px 32px rgba(255,136,0,0.15);
  }
  .card-pieza-prov img {
    width: 100%; height: 155px; object-fit: cover;
    transition: transform .35s;
  }
  .card-pieza-prov:hover img { transform: scale(1.05); }
  .card-pieza-prov .p-info { padding: 12px 14px; flex: 1; }
  .card-pieza-prov .acento { height: 2px; background: linear-gradient(90deg,#8b5e00,#ff8800); }
  .badge-ref-prov {
    background: rgba(255,136,0,0.15); border: 1px solid rgba(255,136,0,0.3);
    color: #ff8800; font-size: 0.65rem; border-radius: 4px;
    padding: 2px 7px; font-weight: 700; letter-spacing: 0.3px;
  }
  .empty-prov { color: rgba(255,255,255,0.08); }
</style>

<!-- ══ HERO ══ -->
<section class="prov-hero">
  <div class="container">
    <div class="mb-4">
      <a href="javascript:history.back()"
         class="btn btn-sm"
         style="border:1px solid rgba(255,255,255,0.22);color:rgba(255,255,255,0.6);border-radius:50px;font-size:0.82rem;">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>

    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">

      <!-- Avatar -->
      <div class="prov-avatar">
        <?= mb_strtoupper(mb_substr(htmlspecialchars($proveedor['nombre_empresa']), 0, 1)) ?>
      </div>

      <!-- Info -->
      <div class="flex-grow-1 text-center text-md-start">

        <!-- Nombre + badge -->
        <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mb-1">
          <h2 class="text-white fw-bold mb-0 fs-3">
            <?= htmlspecialchars($proveedor['nombre_empresa']) ?>
          </h2>
          <span class="badge"
                style="background:linear-gradient(45deg,#8b5e00,#ff8800);font-size:0.68rem;border-radius:50px;padding:3px 10px;">
            <i class="fas fa-store me-1"></i>Proveedor
          </span>
        </div>

        <!-- Provincia / web -->
        <div class="mb-3" style="color:rgba(255,255,255,0.38);font-size:0.88rem;">
          <?php if (!empty($proveedor['provincia'])): ?>
            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($proveedor['provincia']) ?>
          <?php endif; ?>
          <?php if (!empty($proveedor['sitio_web'])): ?>
            <?php if (!empty($proveedor['provincia'])): ?> &nbsp;·&nbsp; <?php endif; ?>
            <a href="<?= htmlspecialchars($proveedor['sitio_web']) ?>" target="_blank" rel="noopener"
               style="color:#ff8800;text-decoration:none;">
              <i class="fas fa-globe me-1"></i>Sitio web
            </a>
          <?php endif; ?>
        </div>

        <!-- Estadística -->
        <div class="d-flex gap-4 justify-content-center justify-content-md-start align-items-center mb-4">
          <div class="text-center">
            <div class="prov-stat-val"><?= $totalPiezas ?></div>
            <div class="prov-stat-label">Piezas</div>
          </div>
        </div>

        <!-- Descripción -->
        <?php if (!empty($proveedor['descripcion'])): ?>
          <p style="color:rgba(255,255,255,0.5);font-size:0.88rem;max-width:560px;line-height:1.6;margin:0;">
            <?= nl2br(htmlspecialchars($proveedor['descripcion'])) ?>
          </p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<!-- ══ PIEZAS ══ -->
<section class="prov-content">
  <div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <span class="badge-cnt-prov">
        <i class="fas fa-cog me-1"></i>
        <?= $totalPiezas ?> pieza<?= $totalPiezas !== 1 ? 's' : '' ?>
      </span>
    </div>

    <?php if (empty($piezas)): ?>
      <div class="text-center py-5">
        <i class="fas fa-cog fa-4x empty-prov mb-4 d-block"></i>
        <h5 class="text-white-50">Sin piezas publicadas</h5>
        <p style="color:rgba(255,255,255,0.3);font-size:0.88rem;">
          <?= htmlspecialchars($proveedor['nombre_empresa']) ?> todavía no tiene piezas en el catálogo.
        </p>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($piezas as $p):
          if (!(bool)($p['activa'] ?? 1)) continue;
          $imgP = !empty($p['imagen']) ? $p['imagen'] : (!empty($p['url']) ? $p['url'] : 'public/img/no-image.svg');
        ?>
          <div class="col-6 col-md-4 col-lg-3">
            <a href="pieza-detalle.php?id=<?= (int)$p['id'] ?>" class="card-pieza-prov">
              <div style="position:relative;overflow:hidden;">
                <img src="<?= htmlspecialchars($imgP) ?>"
                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                     onerror="this.onerror=null;this.src='public/img/no-image.svg'">
                <?php
                  $eLabels = ['usada_buena'=>['👍 Buen estado','#4fc3f7'],'usada_desgaste'=>['⚠️ Desgaste','#ff8800']];
                  $eL = $eLabels[$p['estado_pieza']] ?? null;
                ?>
                <?php if ($eL): ?>
                  <div style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,0.75);color:<?= $eL[1] ?>;font-size:.62rem;font-weight:700;padding:3px 8px;border-radius:4px;">
                    <?= $eL[0] ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($p['stock']) && (int)$p['stock'] <= 0): ?>
                  <div style="position:absolute;top:8px;right:8px;background:rgba(220,53,69,0.85);color:#fff;font-size:.62rem;font-weight:700;padding:3px 8px;border-radius:4px;">
                    Sin stock
                  </div>
                <?php endif; ?>
              </div>
              <div class="p-info">
                <div class="badge-ref-prov mb-2 d-inline-block">
                  <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($p['referencia']) ?>
                </div>
                <div style="color:rgba(255,255,255,0.88);font-size:0.84rem;font-weight:600;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  <?= htmlspecialchars($p['nombre']) ?>
                </div>
                <?php if (!empty($p['precio']) && (float)$p['precio'] > 0): ?>
                  <div style="color:#ff8800;font-weight:700;font-size:0.95rem;margin-top:8px;">
                    <?= number_format((float)$p['precio'], 2) ?> €
                  </div>
                <?php endif; ?>
              </div>
              <div class="acento"></div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>
