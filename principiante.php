<?php
// principiantes.php

include('bd.php');  // ← Asegúrate que el archivo se llame exactamente bd.php

// Cargar SOLO las marcas al inicio
$stmt_marcas = $pdo->query("SELECT id, nombre FROM marca ORDER BY nombre");
$marcas = $stmt_marcas->fetchAll();

// Tutoriales y piezas (por ahora los últimos, luego se pueden filtrar si quieres)
$stmt_tutoriales = $pdo->query("
    SELECT t.id, t.titulo, t.pieza_id, p.nombre AS pieza_nombre
    FROM tutorial t
    LEFT JOIN pieza p ON t.pieza_id = p.id
    ORDER BY t.id DESC
    LIMIT 4
");
$tutoriales = $stmt_tutoriales->fetchAll();

$stmt_piezas = $pdo->query("
    SELECT id, referencia, nombre, descripcion
    FROM pieza
    ORDER BY id DESC
    LIMIT 6
");
$piezas = $stmt_piezas->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Principiantes - Tunefix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .overlay { background: rgba(0,0,0,0.55); }
    .card-img-top { height: 180px; object-fit: cover; }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php include('header.php'); ?>

<section class="position-relative text-white d-flex align-items-center"
         style="background:url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; min-height:80vh;">

  <div class="position-absolute top-0 start-0 w-100 h-100 overlay"></div>

  <div class="container position-relative py-5">
    <div class="row align-items-start">

      <!-- IZQUIERDA - Filtros -->
      <div class="col-lg-6">

        <h1 class="display-3 mb-5">Principiantes</h1>

        <div class="mb-4 w-100">
          <label class="form-label text-white">Marca</label>
          <select class="form-select" id="marcaSelect">
            <option selected disabled>Selecciona una marca</option>
            <?php foreach ($marcas as $marca): ?>
              <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-4 w-100">
          <label class="form-label text-white">Modelo</label>
          <select class="form-select" id="modeloSelect" disabled>
            <option selected disabled>Primero selecciona una marca</option>
          </select>
        </div>

        <div class="mb-4 w-100">
          <label class="form-label text-white">Motorización</label>
          <select class="form-select" id="motorSelect" disabled>
            <option selected disabled>Primero selecciona un modelo</option>
          </select>
        </div>

      </div>

      <!-- DERECHA - Contenido sugerido -->
      <div class="col-lg-6 text-center text-white">

        <h3 class="mb-4">Tutoriales recomendados</h3>

        <div class="row g-3 mb-5">
          <?php if (empty($tutoriales)): ?>
            <p>Aún no hay tutoriales cargados.</p>
          <?php else: ?>
            <?php foreach ($tutoriales as $tuto): ?>
              <div class="col-md-6">
                <div class="card bg-dark text-white border-0 shadow">
                  <img src="https://img.youtube.com/vi/9bZkp7q19f0/maxresdefault.jpg" 
                       class="card-img-top" alt="Tutorial">
                  <div class="card-body">
                    <h6 class="card-title"><?= htmlspecialchars($tuto['titulo']) ?></h6>
                    <p class="card-text small text-muted">
                      Pieza: <?= htmlspecialchars($tuto['pieza_nombre'] ?? 'General') ?>
                    </p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <h3 class="mb-4">Piezas populares</h3>

        <div class="row g-3 justify-content-center">
          <?php if (empty($piezas)): ?>
            <p>No hay piezas cargadas aún.</p>
          <?php else: ?>
            <?php foreach ($piezas as $pieza): ?>
              <div class="col-6 col-md-4">
                <div class="card bg-dark text-white border-0 shadow-sm">
                  <img src="https://via.placeholder.com/300x200?text=Pieza+<?= htmlspecialchars($pieza['referencia'] ?? 'N/D') ?>" 
                       class="card-img-top" alt="<?= htmlspecialchars($pieza['nombre']) ?>">
                  <div class="card-body p-3">
                    <h6 class="card-title small mb-1"><?= htmlspecialchars($pieza['nombre']) ?></h6>
                    <p class="card-text very-small text-muted">
                      <?= htmlspecialchars(substr($pieza['descripcion'] ?? '', 0, 60)) ?>...
                    </p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

      </div>

    </div>
  </div>
</section>

<?php include('footer.php'); ?>

<script>
// Referencias
const marcaSelect  = document.getElementById('marcaSelect');
const modeloSelect = document.getElementById('modeloSelect');
const motorSelect  = document.getElementById('motorSelect');

// Reset inicial
modeloSelect.disabled = true;
motorSelect.disabled = true;

// Cambia marca → carga modelos
marcaSelect.addEventListener('change', () => {
  const marcaId = marcaSelect.value;
  
  modeloSelect.innerHTML = '<option selected disabled>Cargando modelos...</option>';
  motorSelect.innerHTML = '<option selected disabled>Primero selecciona modelo</option>';
  modeloSelect.disabled = true;
  motorSelect.disabled = true;

  if (!marcaId) {
    modeloSelect.innerHTML = '<option selected disabled>Primero selecciona una marca</option>';
    return;
  }

  fetch(`get_modelos.php?marca_id=${marcaId}`)
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
    .then(data => {
      modeloSelect.innerHTML = '<option selected disabled>Selecciona modelo</option>';
      if (data.error) {
        modeloSelect.innerHTML += `<option disabled>${data.error}</option>`;
        return;
      }
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.nombre;
        modeloSelect.appendChild(opt);
      });
      modeloSelect.disabled = data.length === 0;
    })
    .catch(err => {
      console.error(err);
      modeloSelect.innerHTML = `<option selected disabled>Error: ${err.message}</option>`;
    });
});

// Cambia modelo → carga motorizaciones
modeloSelect.addEventListener('change', () => {
  const modeloId = modeloSelect.value;
  
  motorSelect.innerHTML = '<option selected disabled>Cargando motorizaciones...</option>';
  motorSelect.disabled = true;

  if (!modeloId) {
    motorSelect.innerHTML = '<option selected disabled>Primero selecciona modelo</option>';
    return;
  }

  fetch(`get_motorizaciones.php?modelo_id=${modeloId}`)
    .then(r => {
      if (!r.ok) throw new Error(`HTTP ${r.status}`);
      return r.json();
    })
    .then(data => {
      motorSelect.innerHTML = '<option selected disabled>Selecciona motorización</option>';
      if (data.error) {
        motorSelect.innerHTML += `<option disabled>${data.error}</option>`;
        return;
      }
      data.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.display || `${m.nombre} (${m.potencia || '?'} ${m.tipo_combustible || ''})`;
        motorSelect.appendChild(opt);
      });
      motorSelect.disabled = data.length === 0;
    })
    .catch(err => {
      console.error(err);
      motorSelect.innerHTML = `<option selected disabled>Error: ${err.message}</option>`;
    });
});
</script>

</body>
</html>