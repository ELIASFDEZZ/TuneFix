<style>
  .denied-hero {
    background: linear-gradient(rgba(0,0,0,0.80), rgba(0,0,0,0.90)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    min-height: calc(100vh - 160px);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .denied-card {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(164,4,46,0.35);
    border-radius: 16px;
    padding: 3rem 2.5rem;
    text-align: center;
    max-width: 480px;
    backdrop-filter: blur(10px);
  }

  .denied-icon {
    width: 80px; height: 80px;
    background: rgba(164,4,46,0.15);
    border: 2px solid rgba(164,4,46,0.4);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: rgb(164,4,46);
  }

  .rol-badge {
    display: inline-block;
    background: rgba(164,4,46,0.2);
    border: 1px solid rgba(164,4,46,0.5);
    color: #ff6b6b;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
  }

  .btn-volver-home {
    background: rgb(164,4,46);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 28px;
    font-weight: 600;
    transition: all 0.2s ease;
  }
  .btn-volver-home:hover {
    background: #a8032c;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(164,4,46,0.4);
  }
</style>

<div class="denied-hero">
  <div class="denied-card">

    <div class="denied-icon">
      <i class="fas fa-lock"></i>
    </div>

    <div class="rol-badge">
      <i class="fas fa-user me-1"></i>
      <?php
        $roles = [
          'principiante' => 'Principiante',
          'entusiasta'   => 'Entusiasta',
          'profesional'  => 'Profesional',
        ];
        echo $roles[$_SESSION['usuario_rol'] ?? ''] ?? 'Usuario';
      ?>
    </div>

    <h3 class="text-white fw-bold mb-2">Acceso restringido</h3>
    <p class="text-white-50 mb-4">
      La sección <strong class="text-white"><?= htmlspecialchars($seccion ?? 'Profesional') ?></strong>
      está disponible únicamente para usuarios <strong class="text-white">Profesional</strong>.
    </p>
    <p class="small mb-4" style="color: rgba(255,255,255,0.35);">
      Tu nivel actual no tiene acceso a este contenido.
      Si crees que es un error, contacta con soporte.
    </p>

    <a href="index.php" class="btn btn-volver-home">
      <i class="fas fa-home me-2"></i>Volver al inicio
    </a>

  </div>
</div>
