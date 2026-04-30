<style>
  .seg-card {
    background: #1a1a2e;
    border: 1px solid rgba(255,60,0,0.2);
    border-radius: 16px;
    padding: 36px 40px;
    max-width: 860px;
    margin: 0 auto;
  }
  .seg-table th {
    color: rgba(255,255,255,0.45);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    padding-bottom: 10px;
  }
  .seg-table td {
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    color: #e0e0e0;
    font-size: 0.9rem;
    vertical-align: middle;
  }
  .seg-table tr:last-child td { border-bottom: none; }
  .seg-avatar {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #ff6b35, #e63946);
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.85rem; color: #fff;
    flex-shrink: 0;
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
</style>

<section style="background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
       url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
       center/cover no-repeat; min-height: 100vh; padding: 60px 0;">

  <div class="container">

    <div class="text-center text-white mb-5">
      <h1 class="fw-bold display-5">
        <i class="fas fa-users me-2" style="color:#ff3c00;"></i> Mis seguidores
      </h1>
      <p class="text-white-50">Usuarios que siguen tus tutoriales en TuneFix.</p>
    </div>

    <div class="seg-card">

      <div class="d-flex align-items-center justify-content-between mb-4">
        <span class="seg-badge">
          <i class="fas fa-user-friends me-1"></i>
          <?= $total ?> <?= $total === 1 ? 'seguidor' : 'seguidores' ?>
        </span>
        <a href="subir-video.php" class="btn btn-sm btn-outline-danger" style="border-radius:50px;">
          <i class="fas fa-video me-1"></i> Subir vídeo
        </a>
      </div>

      <?php if (empty($seguidores)): ?>
      <div class="text-center py-5">
        <i class="fas fa-user-slash fa-3x mb-3" style="color:rgba(255,255,255,0.2);"></i>
        <p class="text-white-50">Aún no tienes seguidores. ¡Sube tutoriales para conseguir tu primera audiencia!</p>
      </div>
      <?php else: ?>
      <div class="table-responsive">
        <table class="seg-table w-100">
          <thead>
            <tr>
              <th style="width:40px;"></th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Siguiendo desde</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($seguidores as $s): ?>
            <tr>
              <td>
                <div class="seg-avatar">
                  <?= mb_strtoupper(mb_substr(htmlspecialchars($s['nombre']), 0, 1)) ?>
                </div>
              </td>
              <td class="fw-semibold"><?= htmlspecialchars($s['nombre']) ?></td>
              <td style="color:rgba(255,255,255,0.5);"><?= htmlspecialchars($s['email']) ?></td>
              <td style="color:rgba(255,255,255,0.4);font-size:0.82rem;">
                <?= htmlspecialchars(date('d/m/Y', strtotime($s['fecha_seguimiento']))) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>

    </div>

  </div>
</section>
