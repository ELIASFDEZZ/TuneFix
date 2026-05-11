<style>
  /* ── HERO DEL PERFIL ── */
  .perfil-hero {
    background: linear-gradient(180deg, #0d0d0d 0%, #12121f 100%);
    padding: 48px 0 40px;
    border-bottom: 1px solid rgba(255, 60, 0, 0.15);
  }

  .perfil-avatar {
    width: 90px; height: 90px;
    background: linear-gradient(135deg, #a4042e, #ff8800);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.4rem; font-weight: 800; color: #fff;
    border: 3px solid rgba(255, 255, 255, 0.12);
    flex-shrink: 0;
    user-select: none;
  }

  .perfil-verificado {
    color: #4fc3f7;
    font-size: 1.05rem;
  }

  .perfil-stat-val {
    font-size: 1.35rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
  }
  .perfil-stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-top: 3px;
  }
  .perfil-stat-sep {
    width: 1px;
    background: rgba(255, 255, 255, 0.1);
    align-self: stretch;
  }

  /* ── BOTÓN SEGUIR ── */
  .btn-seguir-perfil {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border: none;
    color: #fff;
    font-weight: 700;
    border-radius: 50px;
    padding: 9px 30px;
    font-size: 0.9rem;
    transition: all 0.25s ease;
    cursor: pointer;
  }
  .btn-seguir-perfil:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(164, 4, 46, 0.45);
    color: #fff;
  }
  .btn-siguiendo-perfil {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
    border-radius: 50px;
    padding: 9px 30px;
    font-size: 0.9rem;
    transition: all 0.25s ease;
    cursor: pointer;
  }
  .btn-siguiendo-perfil:hover {
    background: rgba(220, 53, 69, 0.12);
    border-color: rgba(220, 53, 69, 0.35);
    color: #ff6b6b;
  }

  /* ── SECCIÓN DE VÍDEOS ── */
  .videos-section {
    background: #0d0d0d;
    flex: 1;
    padding: 40px 0 60px;
  }

  /* ── FILTROS ── */
  .btn-filtro {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.5);
    border-radius: 50px;
    padding: 7px 20px;
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-block;
  }
  .btn-filtro:hover {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.22);
  }
  .btn-filtro.activo {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border-color: transparent;
    color: #fff;
  }

  /* ── TARJETA DE VÍDEO ── */
  .card-video-perfil {
    background: #1a1a2e;
    border: 1px solid rgba(255, 60, 0, 0.12);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
  }
  .card-video-perfil:hover {
    transform: translateY(-5px);
    border-color: rgba(164, 4, 46, 0.5);
    box-shadow: 0 14px 32px rgba(164, 4, 46, 0.22);
  }

  .card-video-perfil .img-wrap {
    position: relative;
    overflow: hidden;
  }
  .card-video-perfil .img-wrap img {
    width: 100%;
    height: 158px;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
  }
  .card-video-perfil:hover .img-wrap img {
    transform: scale(1.05);
  }

  .play-overlay-dark {
    position: absolute; inset: 0;
    background: rgba(0, 0, 0, 0);
    display: flex; align-items: center; justify-content: center;
    transition: background 0.3s ease;
  }
  .card-video-perfil:hover .play-overlay-dark {
    background: rgba(0, 0, 0, 0.42);
  }
  .play-btn-dark {
    width: 52px; height: 52px;
    background: #ff0000;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: scale(0.55);
    transition: all 0.25s ease;
    box-shadow: 0 4px 18px rgba(255, 0, 0, 0.55);
  }
  .play-btn-dark i { color: #fff; font-size: 1.2rem; margin-left: 3px; }
  .card-video-perfil:hover .play-btn-dark {
    opacity: 1; transform: scale(1);
  }

  .yt-badge-dark {
    position: absolute; bottom: 8px; right: 8px;
    background: rgba(0, 0, 0, 0.8); color: #fff;
    font-size: .62rem; font-weight: 700; letter-spacing: .5px;
    padding: 3px 7px; border-radius: 4px;
    display: flex; align-items: center; gap: 4px;
  }

  .video-title {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.86rem;
    font-weight: 600;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .badge-pieza-dark {
    background: rgba(255, 60, 0, 0.08);
    border: 1px solid rgba(255, 60, 0, 0.28);
    color: rgba(255, 120, 0, 0.85);
    font-size: 0.65rem;
    border-radius: 4px;
    padding: 2px 7px;
  }

  .video-acento {
    height: 2px;
    background: linear-gradient(90deg, #a4042e, #ff8800);
  }

  .badge-contador {
    background: rgba(255, 60, 0, 0.08);
    border: 1px solid rgba(255, 60, 0, 0.25);
    color: rgba(255, 120, 0, 0.9);
    font-size: 0.8rem;
    padding: 6px 14px;
    border-radius: 50px;
  }

  .rol-badge {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    font-size: 0.68rem;
    padding: 3px 10px;
    border-radius: 50px;
  }

  .empty-icon-dark { color: rgba(255, 255, 255, 0.08); }

  /* ── TABS ── */
  .perfil-tabs {
    display: flex;
    gap: 4px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    margin-bottom: 28px;
  }
  .perfil-tab {
    padding: 10px 22px;
    font-size: 0.85rem;
    font-weight: 700;
    color: rgba(255,255,255,0.4);
    border-bottom: 2px solid transparent;
    text-decoration: none;
    transition: all .2s;
    letter-spacing: 0.3px;
  }
  .perfil-tab:hover { color: rgba(255,255,255,0.75); }
  .perfil-tab.activo {
    color: #fff;
    border-bottom-color: rgb(164,4,46);
  }

  /* ── TARJETA DE PIEZA (dark) ── */
  .card-pieza-perfil {
    background: #1a1a2e;
    border: 1px solid rgba(255,136,0,0.12);
    border-radius: 12px;
    overflow: hidden;
    transition: all .3s;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
  }
  .card-pieza-perfil:hover {
    transform: translateY(-5px);
    border-color: rgba(255,136,0,0.45);
    box-shadow: 0 14px 32px rgba(255,136,0,0.15);
  }
  .card-pieza-perfil img {
    width: 100%; height: 155px; object-fit: cover;
    transition: transform .35s;
  }
  .card-pieza-perfil:hover img { transform: scale(1.05); }
  .card-pieza-perfil .p-info { padding: 12px 14px; flex: 1; }
  .card-pieza-perfil .acento-pieza {
    height: 2px;
    background: linear-gradient(90deg,#8b5e00,#ff8800);
  }
  .badge-ref-dark {
    background: rgba(255,136,0,0.15);
    border: 1px solid rgba(255,136,0,0.3);
    color: #ff8800;
    font-size: 0.65rem;
    border-radius: 4px;
    padding: 2px 7px;
    font-weight: 700;
    letter-spacing: 0.3px;
  }
</style>

<!-- ══════════════ HERO DEL PERFIL ══════════════ -->
<section class="perfil-hero">
  <div class="container">

    <!-- Botón volver -->
    <div class="mb-4">
      <a href="javascript:history.back()"
         class="btn btn-sm"
         style="border:1px solid rgba(255,255,255,0.22);color:rgba(255,255,255,0.6);border-radius:50px;font-size:0.82rem;">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>

    <!-- Bloque principal del perfil -->
    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">

      <!-- Avatar -->
      <div class="perfil-avatar">
        <?= mb_strtoupper(mb_substr(htmlspecialchars($perfil['nombre']), 0, 1)) ?>
      </div>

      <!-- Info -->
      <div class="flex-grow-1 text-center text-md-start">

        <!-- Nombre + insignias -->
        <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mb-1">
          <h2 class="text-white fw-bold mb-0 fs-3">
            <?= htmlspecialchars($perfil['nombre']) ?>
          </h2>
          <?php if ($perfil['email_verificado']): ?>
            <span class="perfil-verificado" title="Verificado">
              <i class="fas fa-circle-check"></i>
            </span>
          <?php endif; ?>
          <?php if ($perfil['rol'] === 'profesional'): ?>
            <span class="badge rol-badge">Profesional</span>
          <?php elseif ($perfil['rol'] === 'entusiasta'): ?>
            <span class="badge" style="background:rgba(255,136,0,0.2);border:1px solid rgba(255,136,0,0.4);color:#ff8800;font-size:0.68rem;border-radius:50px;padding:3px 10px;">Entusiasta</span>
          <?php endif; ?>
        </div>

        <!-- Handle -->
        <div class="mb-3" style="color:rgba(255,255,255,0.38);font-size:0.88rem;">
          @<?= htmlspecialchars($handle) ?>
        </div>

        <!-- Estadísticas -->
        <div class="d-flex gap-4 justify-content-center justify-content-md-start align-items-center mb-4">
          <div class="text-center">
            <div class="perfil-stat-val" id="seguidores-count"><?= $totalSeguidores ?></div>
            <div class="perfil-stat-label">Seguidores</div>
          </div>
          <div class="perfil-stat-sep"></div>
          <div class="text-center">
            <div class="perfil-stat-val"><?= $totalVideos ?></div>
            <div class="perfil-stat-label">Vídeos</div>
          </div>
          <?php if ($totalPiezas > 0): ?>
          <div class="perfil-stat-sep"></div>
          <div class="text-center">
            <div class="perfil-stat-val"><?= $totalPiezas ?></div>
            <div class="perfil-stat-label">Piezas</div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Acción: Seguir / Siguiendo / Editar -->
        <?php if ($esMiPerfil): ?>
          <a href="perfil.php"
             class="btn"
             style="border:1px solid rgba(255,255,255,0.22);color:rgba(255,255,255,0.75);border-radius:50px;padding:9px 28px;font-size:0.9rem;font-weight:600;">
            <i class="fas fa-pen me-2"></i>Editar perfil
          </a>
        <?php elseif (!$miId): ?>
          <a href="login.php"
             class="btn btn-seguir-perfil">
            <i class="fas fa-user-plus me-2"></i>Seguir
          </a>
        <?php else: ?>
          <button id="btn-seguir-perfil"
                  class="btn <?= $yoSigo ? 'btn-siguiendo-perfil' : 'btn-seguir-perfil' ?>"
                  data-id="<?= $perfilId ?>"
                  data-siguiendo="<?= $yoSigo ? '1' : '0' ?>"
                  onclick="toggleSeguirPerfil(this)">
            <i class="fas <?= $yoSigo ? 'fa-user-check' : 'fa-user-plus' ?> me-2"></i>
            <span><?= $yoSigo ? 'Siguiendo' : 'Seguir' ?></span>
          </button>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<!-- ══════════════ CONTENIDO CON TABS ══════════════ -->
<section class="videos-section">
  <div class="container">

    <!-- Tabs -->
    <div class="perfil-tabs">
      <a href="perfil-usuario.php?id=<?= $perfilId ?>&tab=videos&orden=<?= $orden ?>"
         class="perfil-tab <?= $tab === 'videos' ? 'activo' : '' ?>">
        <i class="fas fa-video me-2"></i>Vídeos
        <span style="margin-left:6px;background:rgba(255,255,255,0.08);border-radius:50px;padding:1px 8px;font-size:.72rem;"><?= $totalVideos ?></span>
      </a>
      <a href="perfil-usuario.php?id=<?= $perfilId ?>&tab=piezas"
         class="perfil-tab <?= $tab === 'piezas' ? 'activo' : '' ?>">
        <i class="fas fa-cog me-2"></i>Piezas
        <span style="margin-left:6px;background:rgba(255,255,255,0.08);border-radius:50px;padding:1px 8px;font-size:.72rem;"><?= $totalPiezas ?></span>
      </a>
    </div>

    <?php if ($tab === 'videos'): ?>
    <!-- ── TAB: VÍDEOS ── -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
      <div class="d-flex gap-2 flex-wrap">
        <?php foreach (['recientes' => 'Más recientes', 'populares' => 'Populares', 'antiguos' => 'Más antiguos'] as $key => $label): ?>
          <a href="perfil-usuario.php?id=<?= $perfilId ?>&tab=videos&orden=<?= $key ?>"
             class="btn-filtro <?= $orden === $key ? 'activo' : '' ?>">
            <?= $label ?>
          </a>
        <?php endforeach; ?>
      </div>
      <span class="badge-contador">
        <i class="fas fa-video me-1"></i><?= $totalVideos ?> vídeo<?= $totalVideos !== 1 ? 's' : '' ?>
      </span>
    </div>

    <?php if (empty($tutoriales)): ?>
      <div class="text-center py-5">
        <i class="fas fa-video-slash fa-4x empty-icon-dark mb-4 d-block"></i>
        <h5 class="text-white-50">Sin vídeos publicados</h5>
        <p style="color:rgba(255,255,255,0.3);font-size:0.88rem;">
          <?= htmlspecialchars($perfil['nombre']) ?> todavía no ha subido ningún tutorial.
        </p>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($tutoriales as $t): ?>
          <?php
            $ytId  = $t['youtube_id'] ?? '';
            $thumb = $ytId
              ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg"
              : (!empty($t['imagen']) ? $t['imagen'] : 'public/img/no-image.svg');
            $pieza = $t['pieza_nombre'] ?? 'General';
          ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card-video-perfil"
                 onclick="verVideoP('<?= htmlspecialchars($ytId, ENT_QUOTES) ?>', '<?= htmlspecialchars($t['titulo'], ENT_QUOTES) ?>')">
              <div class="img-wrap">
                <img src="<?= htmlspecialchars($thumb) ?>"
                     alt="<?= htmlspecialchars($t['titulo']) ?>"
                     onerror="this.onerror=null;this.src='public/img/no-image.svg'">
                <div class="play-overlay-dark">
                  <div class="play-btn-dark"><i class="fas fa-play"></i></div>
                </div>
                <?php if ($ytId): ?>
                  <div class="yt-badge-dark"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube</div>
                <?php endif; ?>
              </div>
              <div class="p-3">
                <div class="video-title mb-2"><?= htmlspecialchars($t['titulo']) ?></div>
                <span class="badge-pieza-dark"><i class="fas fa-cog me-1"></i><?= htmlspecialchars($pieza) ?></span>
              </div>
              <div class="video-acento"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- ── TAB: PIEZAS ── -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <span class="badge-contador">
        <i class="fas fa-cog me-1"></i><?= $totalPiezas ?> pieza<?= $totalPiezas !== 1 ? 's' : '' ?>
      </span>
    </div>

    <?php if (empty($piezas)): ?>
      <div class="text-center py-5">
        <i class="fas fa-cog fa-4x empty-icon-dark mb-4 d-block"></i>
        <h5 class="text-white-50">Sin piezas publicadas</h5>
        <p style="color:rgba(255,255,255,0.3);font-size:0.88rem;">
          <?= htmlspecialchars($perfil['nombre']) ?> todavía no ha publicado ninguna pieza.
        </p>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($piezas as $p):
          $imgP = !empty($p['imagen']) ? $p['imagen'] : (!empty($p['url']) ? $p['url'] : 'public/img/no-image.svg');
        ?>
          <div class="col-6 col-md-4 col-lg-3">
            <a href="pieza-detalle.php?id=<?= (int)$p['id'] ?>" class="card-pieza-perfil">
              <div style="position:relative;overflow:hidden;">
                <img src="<?= htmlspecialchars($imgP) ?>"
                     alt="<?= htmlspecialchars($p['nombre']) ?>"
                     onerror="this.onerror=null;this.src='public/img/no-image.svg'">
                <?php if (!empty($p['estado_pieza']) && $p['estado_pieza'] !== 'nueva'): ?>
                  <?php $eLabel = ['usada_buena'=>['👍 Buen estado','#4fc3f7'],'usada_desgaste'=>['⚠️ Desgaste','#ff8800']][$p['estado_pieza']] ?? null; ?>
                  <?php if ($eLabel): ?>
                    <div style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,0.75);color:<?= $eLabel[1] ?>;font-size:.62rem;font-weight:700;padding:3px 8px;border-radius:4px;">
                      <?= $eLabel[0] ?>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
              <div class="p-info">
                <div class="badge-ref-dark mb-2 d-inline-block">
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
              <div class="acento-pieza"></div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php endif; ?>

  </div>
</section>

<!-- ══ MODAL REPRODUCTOR ══════════════════════════════════════════ -->
<div class="modal fade" id="videoModalPerfil" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background:#0d0d0d;border:1px solid rgba(255,60,0,.25);border-radius:14px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);padding:12px 18px;">
        <h6 class="modal-title text-white fw-semibold mb-0" id="videoModalPerfilTitle" style="max-width:90%;"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
          <iframe id="videoIframePerfil" src="" title=""
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function verVideoP(ytId, titulo) {
  if (!ytId) return;
  document.getElementById('videoModalPerfilTitle').textContent = titulo;
  document.getElementById('videoIframePerfil').src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1';
  new bootstrap.Modal(document.getElementById('videoModalPerfil')).show();
}
document.getElementById('videoModalPerfil').addEventListener('hidden.bs.modal', function () {
  document.getElementById('videoIframePerfil').src = '';
});

function toggleSeguirPerfil(btn) {
  const profesionalId = btn.dataset.id;
  const siguiendo     = btn.dataset.siguiendo === '1';
  const accion        = siguiendo ? 'dejar' : 'seguir';
  btn.disabled = true;

  const fd = new FormData();
  fd.append('profesional_id', profesionalId);

  fetch('seguir.php?accion=' + accion, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const nuevoEstado = !siguiendo;
        btn.dataset.siguiendo = nuevoEstado ? '1' : '0';
        btn.className = 'btn ' + (nuevoEstado ? 'btn-siguiendo-perfil' : 'btn-seguir-perfil');
        btn.querySelector('i').className = 'fas ' + (nuevoEstado ? 'fa-user-check' : 'fa-user-plus') + ' me-2';
        btn.querySelector('span').textContent = nuevoEstado ? 'Siguiendo' : 'Seguir';
        const countEl = document.getElementById('seguidores-count');
        if (countEl) countEl.textContent = data.seguidores;
      }
    })
    .catch(() => {})
    .finally(() => { btn.disabled = false; });
}
</script>
