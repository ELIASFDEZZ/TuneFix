<style>
  .seg-card {
    background: #1a1a2e;
    border: 1px solid rgba(255, 60, 0, 0.2);
    border-radius: 16px;
    padding: 36px 40px;
    max-width: 860px;
    margin: 0 auto;
  }
  .seg-badge {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    border-radius: 50px;
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 4px 14px;
    display: inline-block;
  }

  /* Grid de tarjetas de seguidos */
  .seguido-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 60, 0, 0.12);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.25s ease;
    text-decoration: none;
    display: block;
    height: 100%;
  }
  .seguido-card:hover {
    background: rgba(164, 4, 46, 0.08);
    border-color: rgba(164, 4, 46, 0.4);
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(164, 4, 46, 0.18);
  }

  .seg-avatar {
    width: 52px; height: 52px;
    background: linear-gradient(135deg, #ff6b35, #e63946);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem; color: #fff;
    flex-shrink: 0;
    margin: 0 auto 12px;
  }

  .seg-nombre {
    font-weight: 700;
    color: rgba(255, 255, 255, 0.92);
    font-size: 0.95rem;
  }
  .seg-rol {
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.35);
    text-transform: capitalize;
  }
  .seg-fecha {
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.3);
    margin-top: 6px;
  }
  .seg-handle {
    font-size: 0.78rem;
    color: rgba(255, 120, 0, 0.7);
  }

  .ver-perfil-link {
    font-size: 0.75rem;
    color: rgba(255, 60, 0, 0.7);
    text-decoration: none;
    font-weight: 600;
    margin-top: 10px;
    display: inline-block;
    transition: color 0.2s;
  }
  .seguido-card:hover .ver-perfil-link {
    color: #ff3c00;
  }
</style>

<section style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
       url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
       center/cover no-repeat; min-height: 100vh; padding: 60px 0;">

  <div class="container">

    <div class="text-center text-white mb-5">
      <h1 class="fw-bold display-5">
        <i class="fas fa-user-friends me-2" style="color:#ff3c00;"></i> Mis seguidos
      </h1>
      <p class="text-white-50">Profesionales que estás siguiendo en TuneFix.</p>
    </div>

    <div class="seg-card">

      <div class="d-flex align-items-center justify-content-between mb-4">
        <span class="seg-badge">
          <i class="fas fa-user-check me-1"></i>
          <?= $total ?> <?= $total === 1 ? 'seguido' : 'seguidos' ?>
        </span>
        <a href="todos-tutoriales.php" class="btn btn-sm btn-outline-danger" style="border-radius:50px;">
          <i class="fas fa-play-circle me-1"></i> Ver tutoriales
        </a>
      </div>

      <?php if (empty($seguidos)): ?>
        <div class="text-center py-5">
          <i class="fas fa-user-slash fa-3x mb-3" style="color:rgba(255,255,255,0.15);display:block;"></i>
          <p class="text-white-50 mb-3">Aún no sigues a ningún profesional.</p>
          <a href="todos-tutoriales.php" class="btn" style="background:linear-gradient(45deg,#a4042e,#ff3c00);color:#fff;border-radius:50px;font-weight:600;">
            <i class="fas fa-search me-2"></i>Descubrir tutoriales
          </a>
        </div>

      <?php else: ?>
        <div class="row g-3">
          <?php foreach ($seguidos as $s):
            $handle = strtolower(preg_replace('/[^a-z0-9]/i', '', $s['nombre']));
            $handle = $handle ?: 'usuario' . $s['id'];
          ?>
            <div class="col-sm-6 col-md-4">
              <a href="perfil-usuario.php?id=<?= (int) $s['id'] ?>" class="seguido-card">
                <div class="seg-avatar">
                  <?= mb_strtoupper(mb_substr(htmlspecialchars($s['nombre']), 0, 1)) ?>
                </div>
                <div class="text-center">
                  <div class="seg-nombre"><?= htmlspecialchars($s['nombre']) ?></div>
                  <div class="seg-handle">@<?= htmlspecialchars($handle) ?></div>
                  <div class="seg-fecha">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Siguiendo desde <?= htmlspecialchars(date('d/m/Y', strtotime($s['fecha_seguimiento']))) ?>
                  </div>
                  <div class="ver-perfil-link mt-2">
                    <i class="fas fa-arrow-right me-1"></i>Ver perfil
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>

  </div>
</section>
