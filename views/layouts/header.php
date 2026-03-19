<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo ?? 'TuneFix') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../../index.php">TuneFix</a>

    <div class="mx-auto">
      <a href="../../index.php" class="btn btn-dark btn-sm rounded-pill me-2">Inicio</a>
      <a href="#" class="btn btn-dark btn-sm rounded-pill">Somos</a>
    </div>

    <div>
      <a href="#" class="btn btn-light btn-sm me-2">Sign in</a>
      <a href="#" class="btn btn-light btn-sm">Register</a>
    </div>
  </div>
</nav>
