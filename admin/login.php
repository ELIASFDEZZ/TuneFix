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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --red: rgb(164,4,46);
      --red-dim: rgba(164,4,46,0.15);
      --bg: #080e1c;
      --surface: #0e1929;
      --card-bg: #111e31;
      --border: rgba(255,255,255,0.07);
    }
    * { box-sizing: border-box; }
    body {
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Inter', 'Segoe UI', sans-serif;
      position: relative;
      overflow: hidden;
    }
    /* Fondo decorativo */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background:
        radial-gradient(ellipse 70% 55% at 15% 60%, rgba(164,4,46,0.12) 0%, transparent 70%),
        radial-gradient(ellipse 60% 50% at 85% 20%, rgba(30,50,100,0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .login-wrap {
      width: 100%; max-width: 400px;
      padding: 0 16px;
      position: relative; z-index: 1;
    }

    /* Tarjeta */
    .card-login {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2.6rem 2.2rem 2.2rem;
      box-shadow: 0 24px 64px rgba(0,0,0,0.55), 0 0 0 1px rgba(164,4,46,0.08);
    }

    /* Logo */
    .brand-wrap {
      display: flex; flex-direction: column; align-items: center; margin-bottom: 2rem;
    }
    .brand-icon {
      width: 54px; height: 54px; border-radius: 16px;
      background: linear-gradient(135deg, #8b0226, rgb(164,4,46));
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; color: #fff; margin-bottom: 14px;
      box-shadow: 0 8px 24px rgba(164,4,46,0.4);
    }
    .brand {
      font-size: 1.7rem; font-weight: 800; letter-spacing: -1px; color: #fff;
    }
    .brand em { color: var(--red); font-style: normal; }
    .brand-sub {
      font-size: .62rem; font-weight: 700; letter-spacing: 2.5px;
      text-transform: uppercase; color: rgba(255,255,255,0.25);
      margin-top: 3px;
    }

    /* Form */
    .field-label {
      font-size: .75rem; font-weight: 600; letter-spacing: .4px;
      color: rgba(255,255,255,0.4); display: block; margin-bottom: 6px;
    }
    .field-wrap { position: relative; margin-bottom: 14px; }
    .field-icon {
      position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
      color: rgba(255,255,255,0.2); font-size: .8rem;
    }
    .form-control {
      background: var(--surface); border: 1px solid rgba(255,255,255,0.09);
      color: #fff; border-radius: 10px; padding: 10px 12px 10px 36px;
      font-family: inherit; font-size: .88rem; width: 100%;
      transition: border-color .18s, box-shadow .18s;
    }
    .form-control:focus {
      background: var(--surface); border-color: var(--red);
      color: #fff; outline: none;
      box-shadow: 0 0 0 3px rgba(164,4,46,0.18);
    }
    .form-control::placeholder { color: rgba(255,255,255,0.18); }

    .btn-login {
      width: 100%; background: var(--red); color: #fff;
      border: none; border-radius: 10px; font-family: inherit;
      font-size: .9rem; font-weight: 700; padding: 11px;
      cursor: pointer; transition: all .18s; margin-top: 6px;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover {
      background: #b8053a;
      box-shadow: 0 6px 20px rgba(164,4,46,0.45);
      transform: translateY(-1px);
    }

    .err-box {
      background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
      color: #f87171; border-radius: 10px; padding: 10px 14px;
      font-size: .82rem; margin-bottom: 16px;
      display: flex; align-items: center; gap: 8px;
    }

    .link-back {
      display: block; text-align: center; margin-top: 20px;
      color: rgba(255,255,255,0.2); font-size: .78rem; text-decoration: none;
      transition: color .18s;
    }
    .link-back:hover { color: rgba(255,255,255,0.55); }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="card-login">

      <div class="brand-wrap">
        <div class="brand-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="brand">Tune<em>Fix</em></div>
        <div class="brand-sub">Back Office</div>
      </div>

      <?php if ($error): ?>
        <div class="err-box">
          <i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-1">
          <label class="field-label">Usuario</label>
          <div class="field-wrap">
            <i class="fas fa-user field-icon"></i>
            <input type="text" name="usuario" class="form-control"
                   placeholder="admin" required autocomplete="username">
          </div>
        </div>
        <div class="mb-3">
          <label class="field-label">Contraseña</label>
          <div class="field-wrap">
            <i class="fas fa-lock field-icon"></i>
            <input type="password" name="password" class="form-control"
                   placeholder="••••••••" required autocomplete="current-password">
          </div>
        </div>
        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i> Entrar al panel
        </button>
      </form>

    </div>

    <a href="../index.php" class="link-back">
      <i class="fas fa-arrow-left me-1"></i> Volver al sitio
    </a>
  </div>
</body>
</html>
