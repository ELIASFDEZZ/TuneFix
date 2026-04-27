<style>
  .sv-card {
    background: #1a1a2e;
    border: 1px solid rgba(255,60,0,0.2);
    border-radius: 16px;
    padding: 36px 40px;
    max-width: 700px;
    margin: 0 auto;
  }
  .sv-label {
    color: rgba(255,255,255,0.6);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 6px;
  }
  .sv-input {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 10px;
    color: #fff;
    padding: 11px 14px;
    font-size: 0.9rem;
    width: 100%;
    transition: border-color 0.2s;
  }
  .sv-input:focus {
    outline: none;
    border-color: rgba(255,60,0,0.6);
    background: rgba(255,255,255,0.08);
  }
  .sv-input option { background: #1a1a2e; }
  .sv-btn {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border: none;
    border-radius: 50px;
    color: #fff;
    font-weight: 700;
    padding: 12px 36px;
    font-size: 0.95rem;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.2s;
  }
  .sv-btn:hover { opacity: 0.88; transform: translateY(-2px); }
  .sv-extract {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    color: #fff;
    padding: 8px 14px;
    font-size: 0.82rem;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s;
  }
  .sv-extract:hover { background: rgba(255,60,0,0.2); }
</style>

<section style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
       url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
       center/cover no-repeat; min-height: 100vh; padding: 60px 0;">

  <div class="container">

    <div class="text-center text-white mb-5">
      <h1 class="fw-bold display-5">
        <i class="fas fa-video me-2" style="color:#ff3c00;"></i> Subir vídeo tutorial
      </h1>
      <p class="text-white-50">Tu vídeo quedará disponible para toda la comunidad TuneFix.</p>
    </div>

    <?php if ($exito): ?>
    <div class="sv-card text-center">
      <i class="fas fa-check-circle fa-3x mb-3" style="color:#00b464;"></i>
      <h4 class="text-white fw-bold mb-2">¡Vídeo publicado!</h4>
      <p class="text-white-50 mb-4">El tutorial ya está disponible en la plataforma.</p>
      <a href="subir-video.php" class="sv-btn" style="text-decoration:none;">Subir otro vídeo</a>
      &nbsp;
      <a href="todos-tutoriales.php" class="sv-btn" style="background:rgba(255,255,255,0.1);text-decoration:none;">Ver tutoriales</a>
    </div>
    <?php else: ?>

    <div class="sv-card">

      <?php if ($error): ?>
      <div style="background:rgba(220,53,69,0.15);border:1px solid rgba(220,53,69,0.4);border-radius:10px;
                  color:#ff6b6b;padding:12px 16px;margin-bottom:24px;font-size:0.88rem;">
        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="subir-video.php">
        <input type="hidden" name="youtube_id" id="fYtId">

        <!-- Título -->
        <div class="mb-4">
          <div class="sv-label">Título del tutorial *</div>
          <input type="text" name="titulo" class="sv-input" required
                 placeholder="Ej: Cómo cambiar el filtro de aceite en un BMW Serie 3"
                 value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
        </div>

        <!-- YouTube URL -->
        <div class="mb-4">
          <div class="sv-label"><i class="fab fa-youtube me-1" style="color:#ff0000;"></i> URL o iframe de YouTube *</div>
          <div class="d-flex gap-2 align-items-start">
            <textarea id="fYtUrl" class="sv-input" rows="3"
              style="resize:vertical;font-family:monospace;font-size:0.8rem;"
              placeholder="Pega aquí la URL del vídeo, el iframe de YouTube o directamente el ID"></textarea>
            <button type="button" class="sv-extract" onclick="extraerYtId()">
              <i class="fas fa-magic me-1"></i>Extraer ID
            </button>
          </div>
          <div id="ytPreview" style="margin-top:10px;display:none;"></div>
        </div>

        <!-- Imagen URL (fallback) -->
        <div class="mb-4">
          <div class="sv-label">URL de imagen <span style="color:rgba(255,255,255,.3);font-weight:400;text-transform:none;">(opcional, solo si no hay YouTube)</span></div>
          <input type="url" name="imagen" class="sv-input" placeholder="https://...">
        </div>

        <!-- Pieza (opcional) -->
        <div class="mb-4">
          <div class="sv-label">Pieza relacionada <span style="color:rgba(255,255,255,.3);font-weight:400;text-transform:none;">(opcional)</span></div>
          <select name="pieza_id" class="sv-input">
            <option value="">— Sin pieza —</option>
            <?php foreach ($piezas as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Motorización (opcional) -->
        <div class="mb-5">
          <div class="sv-label">Vehículo / Motorización <span style="color:rgba(255,255,255,.3);font-weight:400;text-transform:none;">(opcional)</span></div>
          <select name="motorizacion_id" class="sv-input">
            <option value="">— Sin vehículo —</option>
            <?php foreach ($motorizaciones as $m): ?>
              <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="text-center">
          <button type="submit" class="sv-btn">
            <i class="fas fa-upload me-2"></i>Publicar tutorial
          </button>
        </div>

      </form>
    </div>

    <?php endif; ?>
  </div>
</section>

<script>
function extraerYtId() {
  const raw = document.getElementById('fYtUrl').value.trim();
  let id = '';

  const iframeSrc = raw.match(/src=["']([^"']+)["']/i);
  if (iframeSrc) {
    const em = iframeSrc[1].match(/\/embed\/([a-zA-Z0-9_-]{6,20})/);
    if (em) id = em[1];
  }

  if (!id) {
    try {
      const url = new URL(raw);
      if (url.hostname.includes('youtu.be')) {
        id = url.pathname.slice(1).split('?')[0];
      } else {
        id = url.searchParams.get('v') || '';
        if (!id) {
          const m = url.pathname.match(/\/(embed|shorts|v)\/([a-zA-Z0-9_-]{6,20})/);
          if (m) id = m[2];
        }
      }
    } catch(_) {
      const plain = raw.replace(/[^a-zA-Z0-9_-]/g, '');
      if (plain.length >= 6 && plain.length <= 20) id = plain;
    }
  }

  document.getElementById('fYtId').value = id;
  mostrarPreview(id);
}

function mostrarPreview(id) {
  const box = document.getElementById('ytPreview');
  if (!id) { box.style.display = 'none'; box.innerHTML = ''; return; }
  box.style.display = 'block';
  box.innerHTML = `
    <div style="display:flex;align-items:center;gap:12px;background:rgba(0,180,100,0.08);
                border:1px solid rgba(0,180,100,0.25);border-radius:8px;padding:8px 12px;">
      <img src="https://img.youtube.com/vi/${id}/default.jpg"
           style="width:80px;height:45px;object-fit:cover;border-radius:4px;"
           onerror="this.style.opacity='.3'">
      <div>
        <div style="color:rgba(255,255,255,.5);font-size:.72rem;">ID detectado:</div>
        <code style="color:#00e676;font-size:.85rem;">${id}</code>
        <a href="https://www.youtube.com/watch?v=${id}" target="_blank"
           style="display:block;font-size:.7rem;color:rgba(255,255,255,.3);margin-top:2px;">
          <i class="fab fa-youtube me-1" style="color:#ff0000;"></i>Verificar en YouTube
        </a>
      </div>
    </div>`;
}

document.getElementById('fYtUrl').addEventListener('blur', extraerYtId);
document.getElementById('fYtUrl').addEventListener('paste', () => setTimeout(extraerYtId, 50));
</script>
