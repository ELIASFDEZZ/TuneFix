<style>
  .exito-hero {
    background: linear-gradient(rgba(0,0,0,0.78), rgba(0,0,0,0.88)),
      url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80')
      center/cover no-repeat;
    padding: 80px 0 70px;
    min-height: 70vh;
    display: flex; align-items: center;
  }
  .card-exito {
    background: rgba(255,255,255,0.97);
    border-radius: 24px;
    border: 1px solid rgba(255,60,0,0.15);
    box-shadow: 0 20px 60px rgba(0,0,0,0.35);
    padding: 50px 40px;
    max-width: 560px;
    margin: 0 auto;
  }
  .check-circle {
    width: 100px; height: 100px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 28px;
    box-shadow: 0 8px 24px rgba(40,167,69,0.35);
  }
  .session-badge {
    background: rgba(164,4,46,.06);
    border: 1px solid rgba(164,4,46,.2);
    border-radius: 8px;
    padding: 10px 16px;
    font-family: monospace;
    font-size: .9rem;
    color: rgb(164,4,46);
    word-break: break-all;
  }
  .btn-catalogo {
    background: linear-gradient(45deg, #a4042e, #ff3c00);
    color: #fff; border: none; border-radius: 50px;
    font-weight: 700; font-size: 1rem;
    padding: 14px 36px; transition: opacity .2s;
  }
  .btn-catalogo:hover { opacity: .88; color: #fff; }
</style>

<section class="exito-hero text-white">
  <div class="container">
    <div class="card-exito text-center">
      <?php if ($pagoCorrecto): ?>

      <div class="check-circle">
        <i class="fas fa-check" style="font-size:2.5rem;color:#fff;"></i>
      </div>

      <h2 class="fw-bold mb-2" style="color:rgb(164,4,46);">¡Pago completado!</h2>
      <p class="text-muted mb-4" style="font-size:1.05rem;">
        Tu pedido ha sido procesado correctamente.<br>
        Gracias por confiar en <strong>TuneFix</strong>.
      </p>

      <?php if ($sessionId !== ''): ?>
      <div class="mb-4">
        <small class="text-muted d-block mb-1">Referencia de pago</small>
        <div class="session-badge">
          #<?= htmlspecialchars(substr($sessionId, -10)) ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="todas-piezas.php" class="btn btn-catalogo">
          <i class="fas fa-search me-2"></i>Seguir comprando
        </a>
        <a href="index.php" class="btn"
           style="border:1.5px solid rgba(164,4,46,.4);color:rgb(164,4,46);border-radius:50px;font-weight:600;padding:14px 28px;">
          <i class="fas fa-home me-2"></i>Inicio
        </a>
      </div>

      <?php else: ?>

      <div style="width:100px;height:100px;background:rgba(220,53,69,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 28px;">
        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
      </div>

      <h2 class="fw-bold mb-2 text-danger">No se pudo verificar el pago</h2>
      <p class="text-muted mb-4">
        Si realizaste el pago, tu pedido puede estar en proceso. Contacta con soporte si el problema persiste.
      </p>

      <a href="carrito.php" class="btn btn-catalogo">
        <i class="fas fa-arrow-left me-2"></i>Volver al carrito
      </a>

      <?php endif; ?>
    </div>
  </div>
</section>
