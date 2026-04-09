<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/MarcaModel.php';

class PerfilController
{
    private UsuarioModel $usuarioModel;
    private MarcaModel   $marcaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->marcaModel   = new MarcaModel();
    }

    public function index(): void
    {
        $userId = (int) $_SESSION['usuario_id'];
        $msg    = null; // ['tipo' => 'success'|'danger'|'warning', 'texto' => '...']

        // ── Procesar POST ──────────────────────────────────────────
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';

            // 1. Cambiar nombre
            if ($accion === 'nombre') {
                $nuevoNombre = trim($_POST['nombre'] ?? '');
                if ($nuevoNombre === '') {
                    $msg = ['tipo' => 'danger', 'texto' => 'El nombre no puede estar vacío.'];
                } elseif (mb_strlen($nuevoNombre) > 80) {
                    $msg = ['tipo' => 'danger', 'texto' => 'El nombre es demasiado largo.'];
                } else {
                    $this->usuarioModel->actualizarNombre($userId, $nuevoNombre);
                    $_SESSION['usuario_nombre'] = $nuevoNombre;
                    $msg = ['tipo' => 'success', 'texto' => 'Nombre actualizado correctamente.'];
                }
            }

            // 2. Cambiar contraseña
            elseif ($accion === 'password') {
                $actual    = $_POST['password_actual'] ?? '';
                $nueva     = $_POST['password_nueva']  ?? '';
                $confirmar = $_POST['password_confirm'] ?? '';

                if ($nueva !== $confirmar) {
                    $msg = ['tipo' => 'danger', 'texto' => 'Las contraseñas nuevas no coinciden.'];
                } elseif (strlen($nueva) < 6) {
                    $msg = ['tipo' => 'danger', 'texto' => 'La nueva contraseña debe tener al menos 6 caracteres.'];
                } else {
                    $resultado = $this->usuarioModel->cambiarContrasenia($userId, $actual, $nueva);
                    if ($resultado === 'ok') {
                        $msg = ['tipo' => 'success', 'texto' => 'Contraseña cambiada correctamente.'];
                    } elseif ($resultado === 'wrong_current') {
                        $msg = ['tipo' => 'danger', 'texto' => 'La contraseña actual es incorrecta.'];
                    } else {
                        $msg = ['tipo' => 'danger', 'texto' => 'Error al cambiar la contraseña.'];
                    }
                }
            }

            // 3. Añadir coche
            elseif ($accion === 'add_coche') {
                $motId = (int) ($_POST['motorizacion_id'] ?? 0);
                if ($motId <= 0) {
                    $msg = ['tipo' => 'warning', 'texto' => 'Selecciona una motorización completa.'];
                } else {
                    $ok = $this->usuarioModel->addCoche($userId, $motId);
                    $msg = $ok
                        ? ['tipo' => 'success', 'texto' => 'Vehículo añadido a tu perfil.']
                        : ['tipo' => 'warning', 'texto' => 'Este vehículo ya estaba en tu perfil.'];
                }
            }

            // 4. Eliminar coche
            elseif ($accion === 'remove_coche') {
                $relId = (int) ($_POST['rel_id'] ?? 0);
                $this->usuarioModel->removeCoche($relId, $userId);
                $msg = ['tipo' => 'success', 'texto' => 'Vehículo eliminado de tu perfil.'];
            }
        }

        // ── Preparar datos para la vista ──────────────────────────
        $data = [
            'titulo'  => 'Mi Perfil - TuneFix',
            'marcas'  => $this->marcaModel->getAll(),
            'coches'  => $this->usuarioModel->getCoches($userId),
            'msg'     => $msg,
        ];

        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/perfil/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
