<style>
  .page-hero {
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.88)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 60px 0 50px;
  }
  .content-section { background: rgba(255,255,255,0.95); flex: 1; }
  .section-divider  { border-top: 2px solid rgba(255,60,0,0.2); margin-bottom: 2rem; }
  .btn-volver { border-color: rgba(255,255,255,0.35); color: rgba(255,255,255,0.75); font-size: 0.85rem; }
  .btn-volver:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.7); }
  .empty-icon { color: rgba(164,4,46,0.2); }
  .coming-card {
    background: #fff;
    border: 2px dashed rgba(255,60,0,0.25);
    border-radius: 16px;
    padding: 60px 30px;
    text-align: center;
  }
</style>

<!-- HERO -->
<section class="page-hero text-white">
  <div class="container">
    <div class="mb-4">
      <a href="javascript:history.back()" class="btn btn-outline-light btn-volver">
        <i class="fas fa-arrow-left me-1"></i> Volver
      </a>
    </div>
    <h1 class="display-4 fw-bold mb-2">
      <i class="fas fa-heart me-3" style="color: rgba(255,60,0,0.9);"></i>Mis Favoritos
    </h1>
    <p class="lead mb-0" style="color: rgba(255,255,255,0.6);">
      Guarda piezas y tutoriales para acceder a ellos rápidamente
    </p>
  </div>
</section>

<!-- CONTENIDO -->
<section class="content-section py-5">
  <div class="container">
    <div class="section-divider"></div>

    <div class="coming-card">
      <i class="fas fa-heart fa-5x empty-icon mb-4 d-block"></i>
      <h3 class="fw-bold text-black mb-2">Próximamente</h3>
      <p class="text-black-50 mb-4">
        Aquí podrás guardar tus piezas y tutoriales favoritos para acceder a ellos de forma rápida.<br>
        Esta función estará disponible muy pronto.
      </p>
      <a href="principiante.php" class="btn px-4 py-2 fw-semibold"
         style="background: rgb(164,4,46); color: #fff; border-radius: 50px;">
        <i class="fas fa-graduation-cap me-2"></i>Explorar piezas y tutoriales
      </a>
    </div>

  </div>
</section>
