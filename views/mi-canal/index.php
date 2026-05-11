<style>
  /* ── BANNER DEL CANAL ── */
  .canal-banner {
    background: linear-gradient(135deg, #0a0a12 0%, #180810 40%, #0a0a18 100%);
    border-bottom: 1px solid rgba(255, 60, 0, 0.18);
    padding: 52px 0 44px;
  }
  .canal-avatar {
    width: 96px; height: 96px;
    background: linear-gradient(135deg, #a4042e, #ff8800);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.6rem; font-weight: 800; color: #fff;
    border: 3px solid rgba(255, 255, 255, 0.12);
    flex-shrink: 0;
    user-select: none;
  }
  .canal-stat-val {
    font-size: 1.4rem; font-weight: 700; color: #fff; line-height: 1;
  }
  .canal-stat-label {
    font-size: 0.68rem; color: rgba(255,255,255,0.38);
    text-transform: uppercase; letter-spacing: 0.8px; margin-top: 3px;
  }
  .canal-stat-sep { width: 1px; background: rgba(255,255,255,0.1); align-self: stretch; }

  .btn-subir-video {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border: none; color: #fff; font-weight: 700;
    border-radius: 50px; padding: 10px 26px; font-size: 0.9rem;
    transition: all 0.25s ease; text-decoration: none; display: inline-flex;
    align-items: center; gap: 8px;
  }
  .btn-subir-video:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(164,4,46,0.45); color: #fff;
  }
  .btn-ver-perfil {
    border: 1px solid rgba(255,255,255,0.2); color: rgba(255,255,255,0.65);
    border-radius: 50px; padding: 10px 22px; font-size: 0.88rem; font-weight: 600;
    text-decoration: none; transition: all 0.2s ease; display: inline-flex;
    align-items: center; gap: 7px;
  }
  .btn-ver-perfil:hover {
    background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.35);
    color: #fff;
  }
  .canal-verificado { color: #4fc3f7; font-size: 1rem; }

  /* ── TABS ── */
  .canal-tabs-bar {
    background: #0d0d0d;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    position: sticky; top: 78px; z-index: 10;
  }
  .canal-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 16px 28px; font-size: 0.9rem; font-weight: 600;
    color: rgba(255,255,255,0.4); text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
  }
  .canal-tab:hover { color: rgba(255,255,255,0.75); }
  .canal-tab.activo {
    color: #fff;
    border-bottom-color: #ff3c00;
  }

  /* ── CONTENIDO ── */
  .canal-content { background: #0d0d0d; padding: 36px 0 60px; flex: 1; }

  /* ── FILTROS ── */
  .btn-filtro-canal {
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.45); border-radius: 50px; padding: 6px 18px;
    font-size: 0.8rem; font-weight: 600; text-decoration: none;
    transition: all 0.2s ease; display: inline-block;
  }
  .btn-filtro-canal:hover { background: rgba(255,255,255,0.09); color: rgba(255,255,255,0.75); }
  .btn-filtro-canal.activo {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border-color: transparent; color: #fff;
  }

  /* ── TARJETA DE VÍDEO ── */
  .card-video-canal {
    background: #1a1a2e; border: 1px solid rgba(255,60,0,0.1);
    border-radius: 12px; overflow: hidden; transition: all 0.3s ease;
    cursor: pointer; height: 100%;
  }
  .card-video-canal:hover {
    transform: translateY(-5px); border-color: rgba(164,4,46,0.5);
    box-shadow: 0 14px 32px rgba(164,4,46,0.2);
  }
  .card-video-canal .img-wrap { position: relative; overflow: hidden; }
  .card-video-canal .img-wrap img {
    width: 100%; height: 155px; object-fit: cover; display: block;
    transition: transform 0.35s ease;
  }
  .card-video-canal:hover .img-wrap img { transform: scale(1.05); }
  .play-overlay-canal {
    position: absolute; inset: 0; background: rgba(0,0,0,0);
    display: flex; align-items: center; justify-content: center;
    transition: background 0.3s ease;
  }
  .card-video-canal:hover .play-overlay-canal { background: rgba(0,0,0,0.45); }
  .play-btn-canal {
    width: 50px; height: 50px; background: #ff0000; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: scale(0.55); transition: all 0.25s ease;
    box-shadow: 0 4px 18px rgba(255,0,0,0.55);
  }
  .play-btn-canal i { color: #fff; font-size: 1.15rem; margin-left: 3px; }
  .card-video-canal:hover .play-btn-canal { opacity: 1; transform: scale(1); }
  .yt-badge-canal {
    position: absolute; bottom: 8px; right: 8px;
    background: rgba(0,0,0,0.82); color: #fff;
    font-size: .6rem; font-weight: 700; letter-spacing: .4px;
    padding: 3px 7px; border-radius: 4px;
    display: flex; align-items: center; gap: 4px;
  }
  .video-title-canal {
    color: rgba(255,255,255,0.9); font-size: 0.84rem; font-weight: 600;
    line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
  }
  .badge-pieza-canal {
    background: rgba(255,60,0,0.08); border: 1px solid rgba(255,60,0,0.25);
    color: rgba(255,120,0,0.85); font-size: 0.63rem;
    border-radius: 4px; padding: 2px 7px; display: inline-block;
  }
  .video-acento-canal { height: 2px; background: linear-gradient(90deg,#a4042e,#ff8800); }

  /* ── TARJETA SEGUIDOR ── */
  .seguidor-row {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,0.05);
  }
  .seguidor-row:last-child { border-bottom: none; }
  .seg-av {
    width: 42px; height: 42px;
    background: linear-gradient(135deg, #ff6b35, #e63946);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.95rem; color: #fff; flex-shrink: 0;
  }
  .seg-nombre-row { color: rgba(255,255,255,0.88); font-weight: 600; font-size: 0.92rem; }
  .seg-email-row  { color: rgba(255,255,255,0.35); font-size: 0.78rem; }
  .seg-fecha-row  { color: rgba(255,255,255,0.28); font-size: 0.74rem; white-space: nowrap; }

  /* contador genérico */
  .badge-cnt {
    background: rgba(255,60,0,0.08); border: 1px solid rgba(255,60,0,0.22);
    color: rgba(255,120,0,0.9); font-size: 0.78rem; padding: 5px 14px; border-radius: 50px;
  }
  .empty-dark { color: rgba(255,255,255,0.08); }
</style>

<!-- ══════════════ BANNER DEL CANAL ══════════════ -->
<section class="canal-banner">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">

      <!-- Avatar -->
      <div class="canal-avatar">
        <?= mb_strtoupper(mb_substr(htmlspecialchars($miUsuario['nombre']), 0, 1)) ?>
      </div>

      <!-- Info -->
      <div class="flex-grow-1 text-center text-md-start">

        <!-- Nombre + verificado -->
        <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start flex-wrap mb-1">
          <h2 class="text-white fw-bold mb-0 fs-3"><?= htmlspecialchars($miUsuario['nombre']) ?></h2>
          <?php if ($miUsuario['email_verificado']): ?>
            <span class="canal-verificado" title="Verificado"><i class="fas fa-circle-check"></i></span>
          <?php endif; ?>
          <span class="badge" style="background:linear-gradient(45deg,#a4042e,#ff3c00);font-size:0.68rem;border-radius:50px;padding:3px 10px;">
            Profesional
          </span>
        </div>

        <!-- Handle -->
        <div class="mb-3" style="color:rgba(255,255,255,0.35);font-size:0.88rem;">@<?= htmlspecialchars($handle) ?></div>

        <!-- Stats -->
        <div class="d-flex gap-4 justify-content-center justify-content-md-start align-items-center mb-4">
          <div class="text-center">
            <div class="canal-stat-val"><?= $totalSeguidores ?></div>
            <div class="canal-stat-label">Seguidores</div>
          </div>
          <div class="canal-stat-sep"></div>
          <div class="text-center">
            <div class="canal-stat-val"><?= $totalVideos ?></div>
            <div class="canal-stat-label">Vídeos</div>
          </div>
        </div>

        <!-- Acciones -->
        <div class="d-flex gap-3 justify-content-center justify-content-md-start flex-wrap">
          <a href="subir-video.php" class="btn-subir-video">
            <i class="fas fa-plus-circle"></i> Subir vídeo
          </a>
          <a href="subir-pieza.php"
             style="background:linear-gradient(45deg,#8b5e00,#ff8800);border:none;color:#fff;font-weight:700;border-radius:50px;padding:10px 26px;font-size:0.9rem;transition:all .25s ease;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
            <i class="fas fa-cog"></i> Subir pieza
          </a>
          <a href="perfil-usuario.php?id=<?= $miId ?>" class="btn-ver-perfil">
            <i class="fas fa-eye"></i> Ver mi perfil público
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ══════════════ TABS ══════════════ -->
<div class="canal-tabs-bar">
  <div class="container d-flex">
    <a href="mi-canal.php?tab=videos"
       class="canal-tab <?= $tab === 'videos' ? 'activo' : '' ?>">
      <i class="fas fa-play-circle"></i>
      Mis vídeos
      <span style="background:rgba(255,255,255,0.1);border-radius:50px;padding:1px 9px;font-size:0.72rem;"><?= $totalVideos ?></span>
    </a>
    <a href="mi-canal.php?tab=piezas"
       class="canal-tab <?= $tab === 'piezas' ? 'activo' : '' ?>">
      <i class="fas fa-cog"></i>
      Mis piezas
      <span style="background:rgba(255,255,255,0.1);border-radius:50px;padding:1px 9px;font-size:0.72rem;"><?= $totalPiezas ?></span>
    </a>
    <a href="mi-canal.php?tab=seguidores"
       class="canal-tab <?= $tab === 'seguidores' ? 'activo' : '' ?>">
      <i class="fas fa-users"></i>
      Mis seguidores
      <span style="background:rgba(255,255,255,0.1);border-radius:50px;padding:1px 9px;font-size:0.72rem;"><?= $totalSeguidores ?></span>
    </a>
  </div>
</div>

<!-- ══════════════ CONTENIDO ══════════════ -->
<section class="canal-content">
  <div class="container">

    <?php if ($tab === 'videos'): ?>
    <!-- ── TAB: MIS VÍDEOS ── -->
    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4">
      <!-- Filtros -->
      <div class="d-flex gap-2 flex-wrap">
        <?php foreach (['recientes' => 'Más recientes', 'populares' => 'A-Z', 'antiguos' => 'Más antiguos'] as $k => $lbl): ?>
          <a href="mi-canal.php?tab=videos&orden=<?= $k ?>"
             class="btn-filtro-canal <?= $orden === $k ? 'activo' : '' ?>">
            <?= $lbl ?>
          </a>
        <?php endforeach; ?>
      </div>
      <span class="badge-cnt"><i class="fas fa-video me-1"></i><?= $totalVideos ?> vídeo<?= $totalVideos !== 1 ? 's' : '' ?></span>
    </div>

    <?php if (empty($tutoriales)): ?>
      <div class="text-center py-5">
        <i class="fas fa-video-slash fa-4x empty-dark mb-4 d-block"></i>
        <h5 class="text-white-50">Aún no has subido ningún vídeo</h5>
        <p class="text-white-50 mb-4" style="font-size:0.88rem;">Comparte tu conocimiento con la comunidad de TuneFix.</p>
        <a href="subir-video.php" class="btn-subir-video" style="font-size:0.88rem;padding:9px 24px;">
          <i class="fas fa-plus-circle"></i> Subir primer vídeo
        </a>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($tutoriales as $t):
          $ytId  = $t['youtube_id'] ?? '';
          $thumb = $ytId
            ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg"
            : (!empty($t['imagen']) ? $t['imagen'] : 'public/img/no-image.svg');
          $pieza = $t['pieza_nombre'] ?? 'General';
        ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card-video-canal"
                 onclick="verVideoCanal('<?= htmlspecialchars($ytId, ENT_QUOTES) ?>', '<?= htmlspecialchars($t['titulo'], ENT_QUOTES) ?>')">
              <div class="img-wrap">
                <img src="<?= htmlspecialchars($thumb) ?>"
                     alt="<?= htmlspecialchars($t['titulo']) ?>"
                     onerror="this.src='public/img/no-image.svg'">
                <div class="play-overlay-canal">
                  <div class="play-btn-canal"><i class="fas fa-play"></i></div>
                </div>
                <?php if ($ytId): ?>
                  <div class="yt-badge-canal"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube</div>
                <?php endif; ?>
              </div>
              <div class="p-3">
                <div class="video-title-canal mb-2"><?= htmlspecialchars($t['titulo']) ?></div>
                <span class="badge-pieza-canal"><i class="fas fa-cog me-1"></i><?= htmlspecialchars($pieza) ?></span>
              </div>
              <div class="video-acento-canal"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Botón subir más -->
      <div class="text-center mt-5">
        <a href="subir-video.php" class="btn-subir-video" style="font-size:0.88rem;padding:9px 24px;">
          <i class="fas fa-plus-circle"></i> Subir otro vídeo
        </a>
      </div>
    <?php endif; ?>

    <?php elseif ($tab === 'piezas'): ?>
    <!-- ── TAB: MIS PIEZAS ── -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <span class="badge-cnt"><i class="fas fa-cog me-1"></i><?= $totalPiezas ?> pieza<?= $totalPiezas !== 1 ? 's' : '' ?></span>
      <a href="subir-pieza.php"
         style="background:linear-gradient(45deg,#8b5e00,#ff8800);border:none;color:#fff;font-weight:700;border-radius:50px;padding:8px 20px;font-size:0.82rem;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
        <i class="fas fa-plus"></i> Subir pieza
      </a>
    </div>

    <!-- Alertas de acción -->
    <?php if (isset($_GET['editado'])): ?>
      <div style="background:rgba(0,180,100,0.1);border:1px solid rgba(0,180,100,0.3);color:#4ddb9e;border-radius:10px;padding:12px 18px;font-size:0.88rem;margin-bottom:20px;">
        <i class="fas fa-check-circle me-2"></i>Pieza actualizada correctamente.
      </div>
    <?php elseif (isset($_GET['eliminado'])): ?>
      <div style="background:rgba(220,53,69,0.1);border:1px solid rgba(220,53,69,0.3);color:#ff8080;border-radius:10px;padding:12px 18px;font-size:0.88rem;margin-bottom:20px;">
        <i class="fas fa-trash-alt me-2"></i>Pieza eliminada del catálogo.
      </div>
    <?php endif; ?>

    <?php if (empty($piezas)): ?>
      <div class="text-center py-5">
        <i class="fas fa-cog fa-4x empty-dark mb-4 d-block"></i>
        <h5 class="text-white-50">Aún no has subido ninguna pieza</h5>
        <p class="text-white-50 mb-4" style="font-size:0.88rem;">Añade piezas al catálogo de TuneFix.</p>
        <a href="subir-pieza.php"
           style="background:linear-gradient(45deg,#8b5e00,#ff8800);border:none;color:#fff;font-weight:700;border-radius:50px;padding:10px 24px;font-size:0.88rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
          <i class="fas fa-plus-circle"></i> Subir primera pieza
        </a>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($piezas as $p):
          $thumb = !empty($p['imagen']) ? $p['imagen'] : (!empty($p['url']) ? $p['url'] : 'public/img/no-image.svg');
          $estadoLabels = ['nueva' => ['✨ Nueva','#00c87a'], 'usada_buena' => ['👍 Buen estado','#4fc3f7'], 'usada_desgaste' => ['⚠️ Desgaste','#ff8800']];
          [$estadoTxt, $estadoColor] = $estadoLabels[$p['estado_pieza']] ?? ['–','#aaa'];
        ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card-video-canal" style="border-color:rgba(255,136,0,0.1);">
              <a href="pieza-detalle.php?id=<?= $p['id'] ?>" style="text-decoration:none;display:block;">
                <div class="img-wrap">
                  <img src="<?= htmlspecialchars($thumb) ?>"
                       alt="<?= htmlspecialchars($p['nombre']) ?>"
                       onerror="this.src='public/img/no-image.svg'">
                  <div style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,0.75);color:<?= $estadoColor ?>;font-size:.65rem;font-weight:700;padding:3px 8px;border-radius:4px;">
                    <?= $estadoTxt ?>
                  </div>
                  <?php if (!empty($p['categoria'])): ?>
                    <div style="position:absolute;bottom:8px;left:8px;background:rgba(0,0,0,0.75);color:#ff8800;font-size:.62rem;font-weight:700;padding:3px 7px;border-radius:4px;">
                      <?= htmlspecialchars($p['categoria']) ?>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="p-3 pb-2">
                  <div class="video-title-canal mb-1"><?= htmlspecialchars($p['nombre']) ?></div>
                  <div style="font-size:0.72rem;color:rgba(255,136,0,0.7);margin-bottom:6px;">
                    <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($p['referencia']) ?>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <span style="color:#4ddb9e;font-weight:700;font-size:0.88rem;">
                      <?= $p['precio'] > 0 ? number_format((float)$p['precio'], 2) . ' €' : 'Sin precio' ?>
                    </span>
                    <span style="font-size:0.72rem;color:rgba(255,255,255,0.3);">Stock: <?= (int)$p['stock'] ?></span>
                  </div>
                </div>
              </a>
              <!-- Acciones editar / eliminar -->
              <div class="d-flex gap-2 px-3 pb-3">
                <a href="editar-pieza.php?id=<?= $p['id'] ?>"
                   style="flex:1;text-align:center;font-size:0.75rem;font-weight:700;padding:6px 0;border-radius:8px;background:rgba(255,136,0,0.1);border:1px solid rgba(255,136,0,0.25);color:#ff8800;text-decoration:none;transition:all .2s;"
                   onmouseover="this.style.background='rgba(255,136,0,0.22)'" onmouseout="this.style.background='rgba(255,136,0,0.1)'">
                  <i class="fas fa-edit me-1"></i>Editar
                </a>
                <form method="POST" action="editar-pieza.php" style="flex:1;" onsubmit="return confirm('¿Eliminar esta pieza del catálogo?')">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                  <input type="hidden" name="_action" value="eliminar">
                  <button type="submit"
                          style="width:100%;font-size:0.75rem;font-weight:700;padding:6px 0;border-radius:8px;background:rgba(220,53,69,0.1);border:1px solid rgba(220,53,69,0.25);color:#ff6b6b;cursor:pointer;transition:all .2s;"
                          onmouseover="this.style.background='rgba(220,53,69,0.22)'" onmouseout="this.style.background='rgba(220,53,69,0.1)'">
                    <i class="fas fa-trash-alt me-1"></i>Eliminar
                  </button>
                </form>
              </div>
              <div style="height:2px;background:linear-gradient(90deg,#8b5e00,#ff8800);"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- ── TAB: MIS SEGUIDORES ── -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <span class="badge-cnt">
        <i class="fas fa-users me-1"></i>
        <?= $totalSeguidores ?> <?= $totalSeguidores === 1 ? 'seguidor' : 'seguidores' ?>
      </span>
    </div>

    <?php if (empty($seguidores)): ?>
      <div class="text-center py-5">
        <i class="fas fa-user-slash fa-4x empty-dark mb-4 d-block"></i>
        <h5 class="text-white-50">Aún no tienes seguidores</h5>
        <p class="text-white-50" style="font-size:0.88rem;">Sube tutoriales para conseguir tu primera audiencia.</p>
      </div>
    <?php else: ?>
      <div style="background:#1a1a2e;border:1px solid rgba(255,60,0,0.12);border-radius:14px;padding:8px 24px;max-width:720px;">
        <?php foreach ($seguidores as $s): ?>
          <div class="seguidor-row">
            <div class="seg-av">
              <?= mb_strtoupper(mb_substr(htmlspecialchars($s['nombre']), 0, 1)) ?>
            </div>
            <div class="flex-grow-1">
              <div class="seg-nombre-row"><?= htmlspecialchars($s['nombre']) ?></div>
              <div class="seg-email-row"><?= htmlspecialchars($s['email']) ?></div>
            </div>
            <div class="seg-fecha-row">
              <i class="fas fa-calendar-alt me-1" style="color:rgba(255,255,255,0.2);"></i>
              <?= htmlspecialchars(date('d/m/Y', strtotime($s['fecha_seguimiento']))) ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php endif; ?>

  </div>
</section>

<!-- ══ MODAL REPRODUCTOR ══════════════════════════════════════════ -->
<div class="modal fade" id="videoModalCanal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background:#0d0d0d;border:1px solid rgba(255,60,0,.25);border-radius:14px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);padding:12px 18px;">
        <h6 class="modal-title text-white fw-semibold mb-0" id="videoModalCanalTitle" style="max-width:90%;"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
          <iframe id="videoIframeCanal" src="" title=""
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
function verVideoCanal(ytId, titulo) {
  if (!ytId) return;
  document.getElementById('videoModalCanalTitle').textContent = titulo;
  document.getElementById('videoIframeCanal').src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1';
  new bootstrap.Modal(document.getElementById('videoModalCanal')).show();
}
document.getElementById('videoModalCanal').addEventListener('hidden.bs.modal', function () {
  document.getElementById('videoIframeCanal').src = '';
});
</script>
