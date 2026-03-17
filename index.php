<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tipo de Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<?php include('header.php'); ?>

  <!-- Hero Section -->
  <section class="position-relative text-white text-center d-flex align-items-center justify-content-center"
           style="background: url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; height:75vh;">

    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>

    <div class="container position-relative">
      <h2 class="mb-5">¿Que tipo de usuario eres?</h2>

      <div class="row text-start">

        <div class="col-md-4 mb-4">
          <h5>Principiante</h5>
          <p class="small">
            Personas que no tienen apenas conocimientos y quieren realizar cosas básicas.
          </p>
          <div class="text-center mt-3">
            <a href="./principiante.php" class="btn btn-light">Principiante</a>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <h5>Entusiasta</h5>
          <p class="small">
            Persona a la cual le apasiona este mundo y quiere saber mas.
          </p>
          <div class="text-center mt-3">
            <a href="#" class="btn btn-light">Entusiasta</a>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <h5>Profesional</h5>
          <p class="small">
            Persona que trabaja en este sector.
          </p>
          <div class="text-center mt-3">
            <a href="#" class="btn btn-light">Profesional</a>
          </div>
        </div>

      </div>
    </div>
  </section>

<?php include('footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>