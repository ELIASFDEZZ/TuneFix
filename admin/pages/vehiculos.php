<?php
$tab = $_GET['tab'] ?? 'marcas';
if (!in_array($tab, ['marcas', 'modelos', 'motorizaciones'], true))
    $tab = 'marcas';
$ok = $_GET['ok'] ?? '';

// ── Datos ──────────────────────────────────────────────────────────────────
$marcas = $pdo->query("SELECT id, nombre FROM marca ORDER BY nombre ASC")->fetchAll();

$modelos = $pdo->query(
    "SELECT mo.id, mo.nombre, mo.anio_inicio, mo.anio_fin, ma.nombre AS marca_nombre, mo.marca_id
     FROM modelo mo
     JOIN marca ma ON ma.id = mo.marca_id
     ORDER BY ma.nombre ASC, mo.nombre ASC"
)->fetchAll();

$motorizaciones = $pdo->query(
    "SELECT mz.id, mz.nombre, mz.potencia, mz.tipo_combustible, mz.tipo_motor, mz.codigo_motor,
            mz.modelo_id, mo.nombre AS modelo_nombre, ma.nombre AS marca_nombre
     FROM motorizacion mz
     JOIN modelo mo ON mo.id = mz.modelo_id
     JOIN marca ma ON ma.id = mo.marca_id
     ORDER BY ma.nombre ASC, mo.nombre ASC, mz.nombre ASC"
)->fetchAll();
?>

<?php if ($ok === 'saved'): ?>
    <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Guardado correctamente.</div>
<?php elseif ($ok === 'del'): ?>
    <div class="alert-success-admin"><i class="fas fa-check-circle me-2"></i>Eliminado correctamente.</div>
<?php endif; ?>

<!-- ── Pestañas ─────────────────────────────────────────────────────────── -->
<div style="display:flex;gap:6px;margin-bottom:20px;">
    <?php foreach ([
        'marcas' => ['fa-trademark', 'Marcas', count($marcas)],
        'modelos' => ['fa-car-side', 'Modelos', count($modelos)],
        'motorizaciones' => ['fa-tachometer-alt', 'Motorizaciones', count($motorizaciones)],
    ] as $key => [$icon, $label, $cnt]): ?>
        <a href="index.php?page=vehiculos&tab=<?= $key ?>"
            style="display:flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;
              text-decoration:none;font-size:.85rem;font-weight:600;transition:all .2s;
              <?= $tab === $key
                  ? 'background:rgb(164,4,46);color:#fff;'
                  : 'background:rgba(255,255,255,.06);color:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.1);' ?>">
            <i class="fas <?= $icon ?>"></i> <?= $label ?>
            <span style="background:rgba(0,0,0,.25);padding:1px 7px;border-radius:20px;font-size:.75rem;"><?= $cnt ?></span>
        </a>
    <?php endforeach; ?>
</div>

<!-- ══════════════════════════════════════════════════════════════════════ -->
<!-- TAB: MARCAS                                                           -->
<!-- ══════════════════════════════════════════════════════════════════════ -->
<?php if ($tab === 'marcas'): ?>
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title"><i class="fas fa-trademark"></i> Marcas (<?= count($marcas) ?>)</div>
            <button class="btn-red" onclick="abrirModal('marca')"><i class="fas fa-plus me-1"></i> Añadir marca</button>
        </div>
        <div class="table-responsive">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($marcas)): ?>
                        <tr>
                            <td colspan="3" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay marcas.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($marcas as $m): ?>
                        <tr>
                            <td style="color:rgba(255,255,255,.3);width:50px;"><?= $m['id'] ?></td>
                            <td style="color:#fff;font-weight:500;"><?= htmlspecialchars($m['nombre']) ?></td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn-ghost" style="font-size:.75rem;padding:4px 10px;"
                                    onclick='abrirModal("marca", <?= json_encode(["id" => $m["id"], "nombre" => $m["nombre"]]) ?>)'>
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form method="POST" action="index.php?page=vehiculos&tab=marcas" style="display:inline;"
                                    onsubmit="return confirm('¿Eliminar esta marca? Se eliminarán también sus modelos y motorizaciones.')">
                                    <input type="hidden" name="action" value="delete_marca">
                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                    <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal marca -->
    <div id="modalMarca" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:1000;
     align-items:center;justify-content:center;padding:20px;">
        <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,.1);border-radius:12px;
              width:100%;max-width:420px;padding:28px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h3 id="tituloMarca" style="color:#fff;font-size:1rem;font-weight:600;margin:0;">Añadir marca</h3>
                <button onclick="cerrarModal('marca')"
                    style="background:none;border:none;color:rgba(255,255,255,.4);font-size:1.4rem;cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?page=vehiculos&tab=marcas">
                <input type="hidden" name="action" value="save_marca">
                <input type="hidden" name="id" id="mId" value="0">
                <div style="margin-bottom:18px;">
                    <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                        Nombre <span style="color:rgb(164,4,46);">*</span>
                    </label>
                    <input type="text" name="nombre" id="mNombre" required maxlength="100" placeholder="Ej: Volkswagen"
                        style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                      border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="cerrarModal('marca')" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════ -->
    <!-- TAB: MODELOS                                                          -->
    <!-- ══════════════════════════════════════════════════════════════════════ -->
<?php elseif ($tab === 'modelos'): ?>
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title"><i class="fas fa-car-side"></i> Modelos (<?= count($modelos) ?>)</div>
            <button class="btn-red" onclick="abrirModal('modelo')"><i class="fas fa-plus me-1"></i> Añadir modelo</button>
        </div>
        <div class="table-responsive">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Años</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($modelos)): ?>
                        <tr>
                            <td colspan="4" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay modelos.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($modelos as $m): ?>
                        <tr>
                            <td>
                                <span style="background:rgba(255,255,255,.07);padding:3px 10px;border-radius:20px;
                         font-size:.78rem;color:rgba(255,255,255,.6);">
                                    <?= htmlspecialchars($m['marca_nombre']) ?>
                                </span>
                            </td>
                            <td style="color:#fff;font-weight:500;"><?= htmlspecialchars($m['nombre']) ?></td>
                            <td style="color:rgba(255,255,255,.45);font-size:.85rem;">
                                <?= $m['anio_inicio'] ?? '—' ?> – <?= $m['anio_fin'] ?? 'act.' ?>
                            </td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn-ghost" style="font-size:.75rem;padding:4px 10px;" onclick='abrirModal("modelo", <?= json_encode([
                                    "id" => $m["id"],
                                    "nombre" => $m["nombre"],
                                    "marca_id" => $m["marca_id"],
                                    "anio_inicio" => $m["anio_inicio"],
                                    "anio_fin" => $m["anio_fin"],
                                ]) ?>)'>
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form method="POST" action="index.php?page=vehiculos&tab=modelos" style="display:inline;"
                                    onsubmit="return confirm('¿Eliminar este modelo? Se eliminarán también sus motorizaciones.')">
                                    <input type="hidden" name="action" value="delete_modelo">
                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                    <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal modelo -->
    <div id="modalModelo" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:1000;
     align-items:center;justify-content:center;padding:20px;">
        <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,.1);border-radius:12px;
              width:100%;max-width:520px;padding:28px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h3 id="tituloModelo" style="color:#fff;font-size:1rem;font-weight:600;margin:0;">Añadir modelo</h3>
                <button onclick="cerrarModal('modelo')"
                    style="background:none;border:none;color:rgba(255,255,255,.4);font-size:1.4rem;cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?page=vehiculos&tab=modelos">
                <input type="hidden" name="action" value="save_modelo">
                <input type="hidden" name="id" id="moId" value="0">
                <div style="margin-bottom:14px;">
                    <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                        Marca <span style="color:rgb(164,4,46);">*</span>
                    </label>
                    <select name="marca_id" id="moMarcaId" required style="width:100%;background:#111;border:1px solid rgba(255,255,255,.12);
                       border-radius:8px;padding:10px 12px;color:#fff;font-size:.85rem;">
                        <option value="">— Selecciona —</option>
                        <?php foreach ($marcas as $ma): ?>
                            <option value="<?= $ma['id'] ?>"><?= htmlspecialchars($ma['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom:14px;">
                    <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                        Nombre del modelo <span style="color:rgb(164,4,46);">*</span>
                    </label>
                    <input type="text" name="nombre" id="moNombre" required maxlength="100" placeholder="Ej: Golf VII"
                        style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                      border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px;">
                    <div>
                        <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                            Año inicio <span style="color:rgba(255,255,255,.3);font-size:.75rem;">(opcional)</span>
                        </label>
                        <input type="number" name="anio_inicio" id="moAnioInicio" min="1900" max="2100"
                            placeholder="Ej: 2012" style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                        border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                            Año fin <span style="color:rgba(255,255,255,.3);font-size:.75rem;">(vacío = actual)</span>
                        </label>
                        <input type="number" name="anio_fin" id="moAnioFin" min="1900" max="2100" placeholder="Ej: 2019"
                            style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                        border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="cerrarModal('modelo')" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════ -->
    <!-- TAB: MOTORIZACIONES                                                   -->
    <!-- ══════════════════════════════════════════════════════════════════════ -->
<?php elseif ($tab === 'motorizaciones'): ?>
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title"><i class="fas fa-tachometer-alt"></i> Motorizaciones
                (<?= count($motorizaciones) ?>)</div>
            <button class="btn-red" onclick="abrirModal('motorizacion')"><i class="fas fa-plus me-1"></i> Añadir
                motorización</button>
        </div>
        <div class="table-responsive">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>Vehículo</th>
                        <th>Motorización</th>
                        <th>Potencia</th>
                        <th>Combustible</th>
                        <th>Código motor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($motorizaciones)): ?>
                        <tr>
                            <td colspan="6" class="text-center" style="color:rgba(255,255,255,.3);padding:30px;">No hay
                                motorizaciones.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($motorizaciones as $mz): ?>
                        <tr>
                            <td style="font-size:.8rem;">
                                <span style="color:rgba(255,255,255,.4);"><?= htmlspecialchars($mz['marca_nombre']) ?></span>
                                <span style="color:rgba(255,255,255,.2);margin:0 4px;">›</span>
                                <span style="color:rgba(255,255,255,.7);"><?= htmlspecialchars($mz['modelo_nombre']) ?></span>
                            </td>
                            <td style="color:#fff;font-weight:500;"><?= htmlspecialchars($mz['nombre']) ?></td>
                            <td style="color:rgba(255,255,255,.55);font-size:.85rem;">
                                <?= htmlspecialchars($mz['potencia'] ?? '—') ?></td>
                            <td>
                                <?php if ($mz['tipo_combustible']): ?>
                                    <?php
                                    $cbColor = match (strtolower($mz['tipo_combustible'])) {
                                        'diésel', 'diesel' => 'rgba(255,136,0,.15);color:#ffaa44;border:1px solid rgba(255,136,0,.3)',
                                        'gasolina' => 'rgba(13,110,253,.12);color:#6ea8fe;border:1px solid rgba(13,110,253,.3)',
                                        'híbrido', 'hibrido' => 'rgba(25,135,84,.12);color:#75b798;border:1px solid rgba(25,135,84,.3)',
                                        'eléctrico', 'electrico' => 'rgba(13,202,240,.12);color:#6edff6;border:1px solid rgba(13,202,240,.3)',
                                        default => 'rgba(255,255,255,.07);color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.1)',
                                    };
                                    ?>
                                    <span style="background:<?= $cbColor ?>;padding:2px 8px;border-radius:20px;font-size:.75rem;">
                                        <?= htmlspecialchars($mz['tipo_combustible']) ?>
                                    </span>
                                <?php else: ?><span style="color:rgba(255,255,255,.2);">—</span><?php endif; ?>
                            </td>
                            <td style="color:rgba(255,255,255,.4);font-size:.82rem;font-family:monospace;">
                                <?= htmlspecialchars($mz['codigo_motor'] ?? '—') ?>
                            </td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn-ghost" style="font-size:.75rem;padding:4px 10px;" onclick='abrirModal("motorizacion", <?= json_encode([
                                    "id" => $mz["id"],
                                    "nombre" => $mz["nombre"],
                                    "modelo_id" => $mz["modelo_id"],
                                    "potencia" => $mz["potencia"] ?? "",
                                    "tipo_combustible" => $mz["tipo_combustible"] ?? "",
                                    "tipo_motor" => $mz["tipo_motor"] ?? "",
                                    "codigo_motor" => $mz["codigo_motor"] ?? "",
                                ]) ?>)'>
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <form method="POST" action="index.php?page=vehiculos&tab=motorizaciones" style="display:inline;"
                                    onsubmit="return confirm('¿Eliminar esta motorización?')">
                                    <input type="hidden" name="action" value="delete_motorizacion">
                                    <input type="hidden" name="id" value="<?= $mz['id'] ?>">
                                    <button type="submit" class="btn-danger-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal motorización -->
    <div id="modalMotorizacion" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:1000;
     align-items:center;justify-content:center;padding:20px;">
        <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,.1);border-radius:12px;
              width:100%;max-width:600px;max-height:90vh;overflow-y:auto;padding:28px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h3 id="tituloMotorizacion" style="color:#fff;font-size:1rem;font-weight:600;margin:0;">Añadir motorización
                </h3>
                <button onclick="cerrarModal('motorizacion')"
                    style="background:none;border:none;color:rgba(255,255,255,.4);font-size:1.4rem;cursor:pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?page=vehiculos&tab=motorizaciones">
                <input type="hidden" name="action" value="save_motorizacion">
                <input type="hidden" name="id" id="mzId" value="0">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                    <div>
                        <label
                            style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">Marca</label>
                        <select id="mzMarca" onchange="mzCargarModelos()" style="width:100%;background:#111;border:1px solid rgba(255,255,255,.12);
                         border-radius:8px;padding:10px 12px;color:#fff;font-size:.85rem;">
                            <option value="">— Selecciona —</option>
                            <?php foreach ($marcas as $ma): ?>
                                <option value="<?= $ma['id'] ?>"><?= htmlspecialchars($ma['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                            Modelo <span style="color:rgb(164,4,46);">*</span>
                        </label>
                        <select name="modelo_id" id="mzModeloId" required style="width:100%;background:#111;border:1px solid rgba(255,255,255,.12);
                         border-radius:8px;padding:10px 12px;color:#fff;font-size:.85rem;">
                            <option value="">— Selecciona marca —</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom:14px;">
                    <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">
                        Nombre de la motorización <span style="color:rgb(164,4,46);">*</span>
                    </label>
                    <input type="text" name="nombre" id="mzNombre" required maxlength="100" placeholder="Ej: 2.0 TDI 150 CV"
                        style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                      border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                    <div>
                        <label
                            style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">Potencia</label>
                        <input type="text" name="potencia" id="mzPotencia" maxlength="50" placeholder="Ej: 150 CV" style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                        border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                    </div>
                    <div>
                        <label
                            style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">Combustible</label>
                        <select name="tipo_combustible" id="mzCombustible" style="width:100%;background:#111;border:1px solid rgba(255,255,255,.12);
                         border-radius:8px;padding:10px 12px;color:#fff;font-size:.85rem;">
                            <option value="">— Selecciona —</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Diésel">Diésel</option>
                            <option value="Híbrido">Híbrido</option>
                            <option value="Eléctrico">Eléctrico</option>
                            <option value="GLP">GLP</option>
                            <option value="GNC">GNC</option>
                        </select>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px;">
                    <div>
                        <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">Tipo de
                            motor</label>
                        <input type="text" name="tipo_motor" id="mzTipoMotor" maxlength="50"
                            placeholder="Ej: 4 cilindros turbo" style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                        border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="color:rgba(255,255,255,.6);font-size:.8rem;display:block;margin-bottom:6px;">Código
                            motor</label>
                        <input type="text" name="codigo_motor" id="mzCodigo" maxlength="50" placeholder="Ej: DFGA" style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
                        border-radius:8px;padding:10px 14px;color:#fff;font-size:.9rem;
                        box-sizing:border-box;font-family:monospace;">
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="cerrarModal('motorizacion')" class="btn-ghost">Cancelar</button>
                    <button type="submit" class="btn-red"><i class="fas fa-save me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- ── JavaScript ──────────────────────────────────────────────────────── -->
<script>
    const modelosPorMarca = {};
    <?php foreach ($modelos as $mo): ?>
        if (!modelosPorMarca[<?= $mo['marca_id'] ?>]) modelosPorMarca[<?= $mo['marca_id'] ?>] = [];
        modelosPorMarca[<?= $mo['marca_id'] ?>].push({ id: <?= $mo['id'] ?>, nombre: <?= json_encode($mo['nombre']) ?> });
    <?php endforeach; ?>

    function mzCargarModelos() {
        const marcaId = document.getElementById('mzMarca').value;
        const sel = document.getElementById('mzModeloId');
        sel.innerHTML = '<option value="">— Selecciona modelo —</option>';
        if (!marcaId || !modelosPorMarca[marcaId]) return;
        modelosPorMarca[marcaId].forEach(m => {
            sel.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
        });
    }

    function abrirModal(tipo, data = null) {
        if (tipo === 'marca') {
            document.getElementById('mId').value = data?.id ?? 0;
            document.getElementById('mNombre').value = data?.nombre ?? '';
            document.getElementById('tituloMarca').textContent = data ? 'Editar marca' : 'Añadir marca';
            mostrarModal('modalMarca');

        } else if (tipo === 'modelo') {
            document.getElementById('moId').value = data?.id ?? 0;
            document.getElementById('moNombre').value = data?.nombre ?? '';
            document.getElementById('moMarcaId').value = data?.marca_id ?? '';
            document.getElementById('moAnioInicio').value = data?.anio_inicio ?? '';
            document.getElementById('moAnioFin').value = data?.anio_fin ?? '';
            document.getElementById('tituloModelo').textContent = data ? 'Editar modelo' : 'Añadir modelo';
            mostrarModal('modalModelo');

        } else if (tipo === 'motorizacion') {
            document.getElementById('mzId').value = data?.id ?? 0;
            document.getElementById('mzNombre').value = data?.nombre ?? '';
            document.getElementById('mzPotencia').value = data?.potencia ?? '';
            document.getElementById('mzCombustible').value = data?.tipo_combustible ?? '';
            document.getElementById('mzTipoMotor').value = data?.tipo_motor ?? '';
            document.getElementById('mzCodigo').value = data?.codigo_motor ?? '';
            document.getElementById('mzMarca').value = '';
            document.getElementById('mzModeloId').innerHTML = '<option value="">— Selecciona marca —</option>';
            if (data?.modelo_id) {
                for (const [marcaId, modelos] of Object.entries(modelosPorMarca)) {
                    if (modelos.some(m => m.id == data.modelo_id)) {
                        document.getElementById('mzMarca').value = marcaId;
                        mzCargarModelos();
                        setTimeout(() => { document.getElementById('mzModeloId').value = data.modelo_id; }, 0);
                        break;
                    }
                }
            }
            document.getElementById('tituloMotorizacion').textContent = data ? 'Editar motorización' : 'Añadir motorización';
            mostrarModal('modalMotorizacion');
        }
    }

    function cerrarModal(tipo) {
        const ids = { marca: 'modalMarca', modelo: 'modalModelo', motorizacion: 'modalMotorizacion' };
        document.getElementById(ids[tipo]).style.display = 'none';
    }

    function mostrarModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    ['modalMarca', 'modalModelo', 'modalMotorizacion'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', e => { if (e.target === el) el.style.display = 'none'; });
    });
</script>