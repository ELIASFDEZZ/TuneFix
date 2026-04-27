<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo ?? 'TuneFix') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .navbar-tuning {
      background: rgb(164, 4, 46) !important;
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgb(164, 4, 46);
      box-shadow: 0 4px 15px rgba(164, 4, 46, 0.3);
    }

    .navbar-brand {
      font-size: 1.9rem;
      font-weight: 800;
      letter-spacing: -1.5px;
      color: #fff !important;
    }

    .nav-link {
      color: #ddd !important;
      font-weight: 500;
      padding: 8px 16px;
      border-radius: 50px;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      color: #000000 !important;
      background: rgb(255, 255, 255);
    }

    .btn-register {
      background: #ffffff;
      border: none;
      color: black;
      font-weight: 600;
      border-radius: 50px;
      padding: 8px 24px;
    }

    .btn-register:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgb(202, 202, 202);
      background: #000000 ;
      color: white;
    }

    .logo-glow {
      text-shadow: 0 0 15px rgba(113, 113, 113, 0.6);
    }

    /* ── DROPDOWN DE USUARIO ── */
    .user-dropdown-toggle {
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25);
      color: #fff !important;
      font-weight: 600;
      border-radius: 50px;
      padding: 7px 18px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.25s ease;
      cursor: pointer;
    }
    .user-dropdown-toggle:hover,
    .user-dropdown-toggle.show {
      background: rgba(255,255,255,0.28);
      border-color: rgba(255,255,255,0.5);
    }
    .user-dropdown-toggle::after { display: none; } /* quitar caret de Bootstrap */
    .user-avatar {
      width: 30px; height: 30px;
      background: #fff;
      color: rgb(164, 4, 46);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800;
      font-size: 0.8rem;
      flex-shrink: 0;
    }
    .user-dropdown-menu {
      min-width: 220px;
      background: #1e1e1e;
      border: 1px solid rgba(255,60,0,0.2);
      border-radius: 12px;
      box-shadow: 0 12px 32px rgba(0,0,0,0.45);
      padding: 6px;
      margin-top: 8px !important;
    }
    .user-dropdown-header {
      padding: 10px 14px 12px;
      border-bottom: 1px solid rgba(255,60,0,0.15);
      margin-bottom: 4px;
    }
    .user-dropdown-header .u-name {
      font-weight: 700;
      color: #fff;
      font-size: 0.95rem;
      line-height: 1.2;
    }
    .user-dropdown-header .u-email {
      font-size: 0.78rem;
      color: rgba(255,255,255,0.45);
    }
    .dropdown-item-tuning {
      color: rgba(255,255,255,0.85) !important;
      border-radius: 8px;
      padding: 9px 14px;
      font-size: 0.9rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.2s ease, color 0.2s ease;
      text-decoration: none;
    }
    .dropdown-item-tuning:hover {
      background: rgba(255,255,255,0.08);
      color: #fff !important;
    }
    .dropdown-item-tuning .item-icon {
      width: 28px; height: 28px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.8rem;
      flex-shrink: 0;
    }
    .icon-perfil   { background: rgba(255,136,0,0.15); color: #ff8800; }
    .icon-favs     { background: rgba(255,60,0,0.15);  color: #ff3c00; }
    .icon-logout   { background: rgba(220,53,69,0.15); color: #dc3545; }
    .dropdown-divider-tuning {
      border-color: rgba(255,255,255,0.08);
      margin: 4px 0;
    }
    .item-logout { color: #dc3545 !important; }
    .item-logout:hover { background: rgba(220,53,69,0.1) !important; color: #ff6b6b !important; }
  </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-dark">

  <!-- HEADER -->
  <nav class="navbar navbar-expand-lg navbar-tuning fixed-top">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand logo-glow" href="./index.php">
       </i>TuneFix
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Menú central -->
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="./index.php"><i class="fas fa-home me-1"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="./principiante.php"><i class="fas fa-graduation-cap me-1"></i>
              Principiantes</a></li>
          <li class="nav-item"><a class="nav-link" href="./entusiasta.php"><i class="fas fa-tools me-1"></i>
              Entusiasta</a></li>
          <li class="nav-item"><a class="nav-link" href="./profesional.php"><i class="fas fa-user-tie me-1"></i>
              Profesional</a></li>
        </ul>

        <!-- Botones derecha -->
        <div class="d-flex align-items-center gap-2">
          <?php if (isset($_SESSION['usuario_id'])): ?>
            <?php
              $nombreNav  = htmlspecialchars($_SESSION['usuario_nombre']);
              $emailNav   = htmlspecialchars($_SESSION['usuario_email'] ?? '');
              $inicialNav = strtoupper(mb_substr($_SESSION['usuario_nombre'], 0, 1));
            ?>
            <div class="dropdown">
              <button class="user-dropdown-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar"><?= $inicialNav ?></div>
                <?= $nombreNav ?>
                <i class="fas fa-chevron-down" style="font-size:0.7rem; opacity:0.7;"></i>
              </button>
              <ul class="dropdown-menu user-dropdown-menu">
                <!-- Cabecera con nombre y email -->
                <li>
                  <div class="user-dropdown-header">
                    <div class="u-name"><?= $nombreNav ?></div>
                    <div class="u-email"><?= $emailNav ?></div>
                  </div>
                </li>
                <!-- Perfil -->
                <li>
                  <a class="dropdown-item-tuning" href="perfil.php">
                    <span class="item-icon icon-perfil"><i class="fas fa-user"></i></span>
                    Perfil
                  </a>
                </li>
                <!-- Favoritos -->
                <li>
                  <a class="dropdown-item-tuning" href="favoritos.php">
                    <span class="item-icon icon-favs"><i class="fas fa-heart"></i></span>
                    Favoritos
                  </a>
                </li>
                <!-- Subir vídeo (solo profesional) -->
                <?php if (($_SESSION['usuario_rol'] ?? '') === 'profesional'): ?>
                <li>
                  <a class="dropdown-item-tuning" href="subir-video.php">
                    <span class="item-icon" style="background:rgba(0,180,100,0.15);color:#00b464;"><i class="fas fa-video"></i></span>
                    Subir vídeo
                  </a>
                </li>
                <?php endif; ?>
                <!-- Separador -->
                <li><hr class="dropdown-divider dropdown-divider-tuning"></li>
                <!-- Cerrar sesión -->
                <li>
                  <a class="dropdown-item-tuning item-logout" href="logout.php">
                    <span class="item-icon icon-logout"><i class="fas fa-sign-out-alt"></i></span>
                    Cerrar sesión
                  </a>
                </li>
              </ul>
            </div>
          <?php else: ?>
            <a href="login.php" class="nav-link">Iniciar sesión</a>
            <a href="register.php" class="btn btn-register">Registrarse</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Espacio para navbar fijo -->
  <div style="height: 78px;"></div>