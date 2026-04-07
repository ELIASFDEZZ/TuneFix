<section class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 160px);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5 col-lg-4">

        <div class="card border-0 shadow-lg" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
          <div class="card-body p-5">

            <div class="text-center mb-4">
              <h2 class="text-white fw-bold mb-1">Bienvenido</h2>
              <p class="text-white-50 small">Inicia sesión para continuar</p>
            </div>

            <?php if ($error): ?>
              <div class="alert alert-danger py-2 small text-center" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i>
                Email o contraseña incorrectos.
              </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
              <input type="hidden" name="redirect" value="<?= $redirect ?>">

              <div class="mb-3">
                <label for="email" class="form-label text-white-50 small">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control bg-dark text-white border-secondary"
                  placeholder="tu@email.com"
                  required
                  autocomplete="email"
                >
              </div>

              <div class="mb-4">
                <label for="password" class="form-label text-white-50 small">Contraseña</label>
                <div class="input-group">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control bg-dark text-white border-secondary"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                  >
                  <button
                    type="button"
                    class="btn btn-outline-secondary"
                    onclick="togglePassword()"
                    tabindex="-1"
                  >
                    <i class="fas fa-eye" id="eye-icon"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="btn w-100 fw-bold py-2"
                style="background: rgb(164,4,46); color: white; border: none;">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
              </button>
            </form>

            <div class="text-center mt-4">
              <a href="index.php" class="text-white-50 small text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>Volver al inicio
              </a>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eye-icon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}
</script>
