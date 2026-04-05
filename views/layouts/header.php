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
          <a href="#" class="nav-link">Iniciar sesión</a>
          <a href="#" class="btn btn-register">Registrarse</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Espacio para navbar fijo -->
  <div style="height: 78px;"></div>