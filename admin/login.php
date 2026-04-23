<?php
session_start();
require_once __DIR__ . '/config.php';

if (isset($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
        $_SESSION[ADMIN_SESSION_KEY] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TuneFix Admin — Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background: #0d0d1a; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .card-login { background: #161625; border: 1px solid rgba(164,4,46,0.3); border-radius: 16px; padding: 2.5rem 2rem; width: 100%; max-width: 380px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); }
    .brand { font-size: 1.9rem; font-weight: 800; letter-spacing: -1.5px; color: #fff; }
    .brand em { color: rgb(164,4,46); font-style: normal; }
    .badge-admin { background: rgba(164,4,46,0.15); border: 1px solid rgba(164,4,46,0.3); color: rgba(255,255,255,0.5); font-size: .7rem; letter-spacing: 2px; text-transform: uppercase; padding: 3px 10px; border-radius: 20px; }
    .form-control { background: #0d0d1a; border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; }
    .form-control:focus { background: #0d0d1a; border-color: rgb(164,4,46); color: #fff; box-shadow: 0 0 0 .2rem rgba(164,4,46,.2); }
    .form-control::placeholder { color: rgba(255,255,255,0.2); }
    .form-label { color: rgba(255,255,255,0.5); font-size: .82rem; font-weight: 500; }
    .btn-login { background: rgb(164,4,46); color: #fff; border: none; font-weight: 700; border-radius: 8px; padding: 10px; }
    .btn-login:hover { background: #c0053a; color: #fff; }
    .link-back { color: rgba(255,255,255,0.25); font-size: .78rem; text-decoration: none; }
    .link-back:hover { color: rgba(255,255,255,0.6); }
  </style>
</head>
<body>
  <div class="card-login">
    <div class="text-center mb-4">
      <div class="brand mb-1">Tune<em>Fix</em></div>
      <span class="badge-admin">Backoffice</span>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2 small border-0" style="background:rgba(220,53,69,.15);color:#ff6b6b;">
        <i class="fas fa-exclamation-circle me-1"></i><?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" name="usuario" class="form-control" placeholder="admin" required autocomplete="username">
      </div>
      <div class="mb-4">
        <label class="form-label">Contraseña</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-login w-100">
        <i class="fas fa-lock me-2"></i>Acceder al panel
      </button>
    </form>

    <div class="text-center mt-3">
      <a href="../index.php" class="link-back">
        <i class="fas fa-arrow-left me-1"></i>Volver al sitio
      </a>
    </div>
  </div>
</body>
</html>
