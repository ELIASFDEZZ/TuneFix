<?php
session_start();

require_once __DIR__ . '/models/UsuarioModel.php';

$token = trim($_GET['token'] ?? '');
$model = new UsuarioModel();

$ok = false;
if ($token !== '') {
    $ok = $model->verificarToken($token);
}

$titulo = 'Verificación de cuenta - TuneFix';
require_once __DIR__ . '/views/layouts/header.php';
?>

<style>
  body { background: #0f0f0f; color: #e0e0e0; }
  .verify-card {
    max-width: 480px;
    margin: 100px auto;
    background: #1a1a1a;
    border-radius: 16px;
    padding: 48px 40px;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0,0,0,.5);
  }
  .verify-icon { font-size: 56px; margin-bottom: 20px; }
  .verify-card h2 { font-size: 22px; margin-bottom: 12px; }
  .verify-card p  { color: #888; font-size: 15px; }
  .btn-tunefix {
    background: #ff6b35; border: none; color: #fff;
    padding: 12px 32px; border-radius: 8px; font-size: 15px;
    text-decoration: none; display: inline-block; margin-top: 24px;
  }
  .btn-tunefix:hover { background: #e05520; color: #fff; }
</style>

<div class="verify-card">
  <?php if ($ok): ?>
    <div class="verify-icon text-success">&#10003;</div>
    <h2>¡Cuenta verificada!</h2>
    <p>Tu cuenta profesional en TuneFix ha sido activada correctamente. Ya puedes iniciar sesión.</p>
    <a href="login.php" class="btn-tunefix">Iniciar sesión</a>
  <?php else: ?>
    <div class="verify-icon text-danger">&#10007;</div>
    <h2>Enlace no válido</h2>
    <p>El enlace de verificación es incorrecto o ya fue utilizado. Si crees que hay un error, contacta con soporte.</p>
    <a href="index.php" class="btn-tunefix">Volver al inicio</a>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/views/layouts/footer.php'; ?>
