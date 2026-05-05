<?php
$rol = $_SESSION['usuario_rol'] ?? null;
$esPrincipiante = $rol === 'principiante';
$esEntusiasta   = $rol === 'entusiasta';
$esProfesional  = $rol === 'profesional';
$esProveedor    = $rol === 'proveedor';
?>
<section class="position-relative text-white text-center d-flex align-items-center justify-content-center"
         style="background: url('https://images.unsplash.com/photo-1603386329225-868f9b1ee6c9?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; height:75vh;">

  <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>

  <div class="container position-relative">
    <h2 class="mb-5">¿Que tipo de usuario eres?</h2>

    <div class="row text-start justify-content-center">

      <?php if ($esPrincipiante || $esEntusiasta || $esProfesional || !$rol): ?>
      <div class="col-md-4 mb-4">
        <h5>Principiante</h5>
        <p class="small">
          Personas que no tienen apenas conocimientos y quieren realizar reparaciones básicas.
        </p>
        <div class="text-center mt-3">
          <a href="principiante.php" class="btn btn-light">Principiante</a>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($esEntusiasta || $esProfesional || !$rol): ?>
      <div class="col-md-4 mb-4">
        <h5>Entusiasta</h5>
        <p class="small">
          Persona a la cual le apasiona este mundo y quiere saber más.
        </p>
        <div class="text-center mt-3">
          <a href="entusiasta.php" class="btn btn-light">Entusiasta</a>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($esProfesional || !$rol): ?>
      <div class="col-md-4 mb-4">
        <h5>Profesional</h5>
        <p class="small">
          Persona que trabaja en este sector.
        </p>
        <div class="text-center mt-3">
          <a href="profesional.php" class="btn btn-light">Profesional</a>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php if (!$esProveedor && !$rol): ?>
<!-- BANNER PROVEEDORES -->
<section style="background: linear-gradient(135deg, #1a0a0e 0%, #2d0a18 50%, #1a0a0e 100%); border-top: 1px solid rgba(164,4,46,0.3); border-bottom: 1px solid rgba(164,4,46,0.3); padding: 56px 0;">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-2 text-center">
        <div style="font-size: 4rem; line-height:1;">🏭</div>
      </div>
      <div class="col-lg-7">
        <span style="background: rgba(164,4,46,0.2); border: 1px solid rgba(164,4,46,0.4); color: #ff6b6b; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 14px;">
          Para empresas y distribuidores
        </span>
        <h2 style="color: #fff; font-size: 1.6rem; font-weight: 800; margin: 0 0 10px; line-height: 1.3;">
          ¿Eres una empresa o distribuidor de recambios?
        </h2>
        <p style="color: rgba(255,255,255,0.6); margin: 0; font-size: 1rem; line-height: 1.6;">
          Únete a TuneFix como <strong style="color: #ffc107;">Proveedor Verificado</strong> y llega a miles de mecánicos y entusiastas que buscan recambios de calidad. Publica tu catálogo, especifica compatibilidad por vehículo y recibe contactos directos.
        </p>
        <div style="margin-top: 16px; display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.85rem; color: rgba(255,255,255,0.45);">
          <span><i class="fas fa-check-circle" style="color:#75b798;"></i> Sin coste de registro</span>
          <span><i class="fas fa-check-circle" style="color:#75b798;"></i> Panel propio de gestión</span>
          <span><i class="fas fa-check-circle" style="color:#75b798;"></i> Badge Proveedor Verificado ✓</span>
        </div>
      </div>
      <div class="col-lg-3 text-center">
        <a href="solicitud-proveedor.php"
           style="display: inline-block; background: linear-gradient(135deg, #a4042e, #ff3c00); color: #fff; text-decoration: none; padding: 16px 28px; border-radius: 12px; font-weight: 700; font-size: 1rem; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 20px rgba(164,4,46,0.4);"
           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(164,4,46,0.6)'"
           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(164,4,46,0.4)'">
          <i class="fas fa-store me-2"></i>Solicitar acceso como Proveedor
        </a>
        <p style="color: rgba(255,255,255,0.25); font-size: 0.75rem; margin-top: 10px;">
          Revisamos tu solicitud en 48 h
        </p>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
