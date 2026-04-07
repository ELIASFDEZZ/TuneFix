<section class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 160px);">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5 col-lg-4">

        <div class="card border-0 shadow-lg" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
          <div class="card-body p-5">

            <div class="text-center mb-4">
              <h2 class="text-white fw-bold mb-1">Crear cuenta</h2>
              <p class="text-white-50 small">Únete a la comunidad TuneFix</p>
            </div>

            <?php
              $mensajes = [
                'campos'   => 'Todos los campos son obligatorios.',
                'password' => 'Las contraseñas no coinciden.',
                'corta'    => 'La contraseña debe tener al menos 6 caracteres.',
                'email'    => 'Ese email ya está registrado.',
              ];
              if ($error && isset($mensajes[$error])):
            ?>
              <div class="alert alert-danger py-2 small text-center" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i>
                <?= $mensajes[$error] ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="register.php">

              <div class="mb-3">
                <label for="nombre" class="form-label text-white-50 small">Nombre</label>
                <input
                  type="text"
                  id="nombre"
                  name="nombre"
                  class="form-control bg-dark text-white border-secondary"
                  placeholder="Tu nombre"
                  required
                  autocomplete="name"
                >
              </div>

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

              <div class="mb-3">
                <label for="password" class="form-label text-white-50 small">Contraseña</label>
                <div class="input-group">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control bg-dark text-white border-secondary"
                    placeholder="Mínimo 6 caracteres"
                    required
                    autocomplete="new-password"
                  >
                  <button type="button" class="btn btn-outline-secondary" onclick="togglePass('password','eye1')" tabindex="-1">
                    <i class="fas fa-eye" id="eye1"></i>
                  </button>
                </div>
              </div>

              <div class="mb-4">
                <label for="confirm" class="form-label text-white-50 small">Repetir contraseña</label>
                <div class="input-group">
                  <input
                    type="password"
                    id="confirm"
                    name="confirm"
                    class="form-control bg-dark text-white border-secondary"
                    placeholder="Repite la contraseña"
                    required
                    autocomplete="new-password"
                  >
                  <button type="button" class="btn btn-outline-secondary" onclick="togglePass('confirm','eye2')" tabindex="-1">
                    <i class="fas fa-eye" id="eye2"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="btn w-100 fw-bold py-2"
                style="background: rgb(164,4,46); color: white; border: none;">
                <i class="fas fa-user-plus me-2"></i>Crear cuenta
              </button>
            </form>

            <div class="text-center mt-4">
              <span class="text-white-50 small">¿Ya tienes cuenta?</span>
              <a href="login.php" class="text-white small ms-1 text-decoration-none fw-semibold">Iniciar sesión</a>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
function togglePass(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}
</script>
