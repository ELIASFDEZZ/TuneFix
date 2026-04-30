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

  /* ── TARJETAS: blancas con borde suave rojo-naranja ── */
  .card-tutorial {
    background: #fff;
    border: 1px solid rgba(255, 60, 0, 0.15) !important;
    transition: all 0.3s ease;
  }
  .card-tutorial:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(164, 4, 46, 0.18);
    border-color: rgba(164, 4, 46, 0.4) !important;
  }

  /* ── OVERLAY PLAY sobre imagen ── */
  .img-wrap { position: relative; overflow: hidden; }
  .play-overlay {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0);
    transition: background 0.3s ease;
  }
  .card-tutorial:hover .play-overlay { background: rgba(0,0,0,0.35); }
  .play-btn {
    width: 56px; height: 56px;
    background: #ff0000;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: scale(0.7);
    transition: opacity 0.25s ease, transform 0.25s ease;
    box-shadow: 0 4px 20px rgba(255,0,0,0.5);
  }
  .play-btn i { color: #fff; font-size: 1.3rem; margin-left: 4px; }
  .card-tutorial:hover .play-btn { opacity: 1; transform: scale(1); }
  /* Badge YouTube */
  .yt-badge {
    position: absolute; bottom: 8px; right: 8px;
    background: rgba(0,0,0,0.75); color: #fff;
    font-size: .65rem; font-weight: 700; letter-spacing: .5px;
    padding: 3px 7px; border-radius: 4px;
    display: flex; align-items: center; gap: 4px;
  }
  .yt-duration {
    position: absolute; bottom: 8px; left: 8px;
    background: rgba(0,0,0,0.75); color: #fff;
    font-size: .7rem; padding: 2px 6px; border-radius: 3px;
  }

  /* ── BADGE PRINCIPIANTE: rojo del navbar ── */
  .badge-nivel {
    background: rgb(164, 4, 46);
    color: #fff;
    font-size: 0.70rem;
  }

  /* ── BADGE PIEZA: borde rojo-naranja como footer ── */
  .badge-pieza {
    background: rgba(255, 60, 0, 0.08);
    border: 1px solid rgba(255, 60, 0, 0.35);
    color: rgb(164, 4, 46);
    font-size: 0.70rem;
  }

  /* ── CONTADOR ── */
  .counter-badge {
    background: rgba(255, 60, 0, 0.08);
    border: 1px solid rgba(255, 60, 0, 0.3);
    color: rgb(164, 4, 46);
    font-weight: 600;
  }

  /* ── BUSCADOR ── */
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

  /* ── SEPARADOR: igual al border-top del footer ── */
  .section-divider {
    border-top: 2px solid rgba(255, 60, 0, 0.2);
    margin-bottom: 2rem;
  }

  .clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

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
          <i class="fas fa-play-circle me-3" style="color: rgba(255,60,0,0.9);"></i>Tutoriales para Principiantes
        </h1>
        <p class="lead mb-0" style="color: rgba(255,255,255,0.65);">
          Aprende paso a paso con nuestra guía completa de tutoriales de tuning
        </p>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <form method="GET" action="">
          <div class="input-group input-group-lg">
            <input
              type="text"
              name="busqueda"
              class="form-control search-input"
              placeholder="Buscar tutoriales..."
              value="<?= htmlspecialchars($busqueda) ?>"
            >
            <button class="btn btn-buscar px-4" type="submit">
              <i class="fas fa-search me-1"></i> Buscar
            </button>
          </div>
          <?php if ($busqueda !== ''): ?>
            <div class="mt-2">
              <a href="todos-tutoriales.php" class="text-decoration-none" style="color: rgba(255,255,255,0.55); font-size: 0.85rem;">
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
        <i class="fas fa-graduation-cap me-2"></i>
        <?php
          $total = count($tutoriales);
          if ($busqueda !== '') {
            echo $total . ' resultado' . ($total !== 1 ? 's' : '') . ' para "<em>' . htmlspecialchars($busqueda) . '</em>"';
          } else {
            echo $total . ' tutorial' . ($total !== 1 ? 'es' : '') . ' disponible' . ($total !== 1 ? 's' : '');
          }
        ?>
      </span>
      <?php if ($busqueda !== ''): ?>
        <a href="todos-tutoriales.php" class="btn btn-sm btn-outline-secondary">
          <i class="fas fa-list me-1"></i> Ver todos
        </a>
      <?php endif; ?>
    </div>

    <!-- Sin resultados -->
    <?php if (empty($tutoriales)): ?>
      <div class="text-center py-5">
        <i class="fas fa-video-slash fa-4x empty-icon mb-4 d-block"></i>
        <h4 class="text-black-50">No se encontraron tutoriales</h4>
        <?php if ($busqueda !== ''): ?>
          <p class="text-black-50">Ningún tutorial coincide con "<?= htmlspecialchars($busqueda) ?>".</p>
          <a href="todos-tutoriales.php" class="btn mt-2" style="background: rgb(164,4,46); color:#fff;">
            Ver todos los tutoriales
          </a>
        <?php else: ?>
          <p class="text-black-50">Aún no hay tutoriales registrados.</p>
        <?php endif; ?>
      </div>

    <!-- Grid de tutoriales -->
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($tutoriales as $tutorial): ?>
          <?php
            $img      = !empty($tutorial['imagen'])       ? $tutorial['imagen']       : 'https://via.placeholder.com/400x220?text=Sin+imagen';
            $pieza    = !empty($tutorial['pieza_nombre']) ? $tutorial['pieza_nombre'] : 'General';
            $piezaId  = $tutorial['pieza_id'] ?? null;
          ?>
          <?php
            $ytId     = $tutorial['youtube_id'] ?? '';
            $thumb    = $ytId
              ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg"
              : (!empty($tutorial['imagen']) ? $tutorial['imagen'] : 'https://via.placeholder.com/400x220?text=Sin+imagen');
            $ytUrl    = $ytId ? "https://www.youtube.com/watch?v={$ytId}" : '#';
            $target   = $ytId ? '_blank' : '_self';
          ?>
          <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="text-decoration-none d-block h-100"
                 style="cursor:pointer;"
                 onclick="verVideo('<?= htmlspecialchars($ytId, ENT_QUOTES) ?>', '<?= htmlspecialchars($tutorial['titulo'], ENT_QUOTES) ?>')">
              <div class="card border-0 shadow-sm card-tutorial h-100">
                <div class="img-wrap">
                  <img
                    src="<?= htmlspecialchars($thumb) ?>"
                    class="card-img-top"
                    style="height: 175px; object-fit: cover; display: block;"
                    alt="<?= htmlspecialchars($tutorial['titulo']) ?>"
                    onerror="this.src='https://via.placeholder.com/400x220?text=Sin+imagen'"
                  >
                  <div class="play-overlay">
                    <div class="play-btn"><i class="fas fa-play"></i></div>
                  </div>
                  <?php if ($ytId): ?>
                    <div class="yt-badge"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube</div>
                  <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column p-3">
                  <div class="d-flex gap-2 flex-wrap mb-2">
                    <?php if ($ytId): ?>
                      <span class="badge" style="background:#ff0000;color:#fff;font-size:.68rem;">
                        <i class="fab fa-youtube me-1"></i>autodoces
                      </span>
                    <?php endif; ?>
                    <?php if ($piezaId): ?>
                      <a href="pieza-detalle.php?id=<?= (int)$piezaId ?>"
                         class="badge badge-pieza text-decoration-none"
                         onclick="event.stopPropagation()">
                        <i class="fas fa-cog me-1"></i><?= htmlspecialchars($pieza) ?>
                      </a>
                    <?php else: ?>
                      <span class="badge badge-pieza">
                        <i class="fas fa-cog me-1"></i><?= htmlspecialchars($pieza) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                  <h6 class="card-title fw-semibold text-black clamp-2 mt-auto mb-2" style="line-height: 1.4;">
                    <?= htmlspecialchars($tutorial['titulo']) ?>
                  </h6>
                  <?php
                    $subidorId   = (int) ($tutorial['usuario_id'] ?? 0);
                    $subidorNom  = $tutorial['nombre_usuario'] ?? '';
                    $esMio       = ($usuarioId && $subidorId && $subidorId === $usuarioId);
                    $yaSigue     = in_array($subidorId, $seguidos ?? [], true);
                  ?>
                  <?php if ($subidorId && !$esMio): ?>
                  <div class="d-flex align-items-center justify-content-between mt-1" onclick="event.stopPropagation()">
                    <span class="text-muted" style="font-size:.72rem;">
                      <i class="fas fa-user me-1"></i><?= htmlspecialchars($subidorNom) ?>
                    </span>
                    <button
                      class="btn-seguir-tutorial"
                      data-id="<?= $subidorId ?>"
                      data-siguiendo="<?= $yaSigue ? '1' : '0' ?>"
                      onclick="toggleSeguirTutorial(this)"
                      style="background:<?= $yaSigue ? 'rgba(0,0,0,0.08)' : 'linear-gradient(45deg,#a4042e,#ff3c00)' ?>;
                             border:none;border-radius:50px;color:<?= $yaSigue ? '#555' : '#fff' ?>;
                             font-size:.7rem;font-weight:700;padding:3px 12px;cursor:pointer;transition:all .2s;white-space:nowrap;">
                      <i class="fas <?= $yaSigue ? 'fa-user-minus' : 'fa-user-plus' ?> me-1"></i>
                      <span><?= $yaSigue ? 'Siguiendo' : 'Seguir' ?></span>
                    </button>
                  </div>
                  <?php endif; ?>
                </div>
                <div style="height: 3px; background: linear-gradient(90deg, rgb(164,4,46), #ff8800);"></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<!-- ══ MODAL REPRODUCTOR ══════════════════════════════════════════ -->
<div class="modal fade" id="videoModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background:#0d0d0d;border:1px solid rgba(255,60,0,.25);border-radius:14px;overflow:hidden;">
      <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,.08);padding:12px 18px;">
        <h6 class="modal-title text-white fw-semibold mb-0" id="videoModalTitle" style="max-width:90%;"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="ratio ratio-16x9">
          <iframe id="videoIframe" src="" title=""
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
function verVideo(ytId, titulo) {
  if (!ytId) return;
  document.getElementById('videoModalTitle').textContent = titulo;
  document.getElementById('videoIframe').src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1';
  new bootstrap.Modal(document.getElementById('videoModal')).show();
}
document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
  document.getElementById('videoIframe').src = '';
});

function toggleSeguirTutorial(btn) {
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
        btn.style.background  = nuevoEstado ? 'rgba(0,0,0,0.08)' : 'linear-gradient(45deg,#a4042e,#ff3c00)';
        btn.style.color       = nuevoEstado ? '#555' : '#fff';
        btn.querySelector('i').className = 'fas ' + (nuevoEstado ? 'fa-user-minus' : 'fa-user-plus') + ' me-1';
        btn.querySelector('span').textContent = nuevoEstado ? 'Siguiendo' : 'Seguir';
      }
    })
    .catch(() => {})
    .finally(() => { btn.disabled = false; });
}
</script>
