<?php

require_once __DIR__ . '/../models/UsuarioModel.php';

class AuthController
{
    private UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Muestra el formulario de login
     */
    public function showLogin(): void
    {
        $redirect = $_GET['redirect'] ?? 'index';
        $error = $_GET['error'] ?? null;

        $data = [
            'titulo'   => 'Iniciar Sesión - TuneFix',
            'redirect' => htmlspecialchars($redirect),
            'error'    => $error,
        ];

        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Procesa el formulario de login (solo POST)
     */
    public function processLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: login.php');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $redirect = $_POST['redirect'] ?? 'index';

        // Páginas permitidas como destino tras login
        $permitidas = ['principiante', 'entusiasta', 'profesional', 'index'];
        if (!in_array($redirect, $permitidas, true)) {
            $redirect = 'index';
        }

        $usuario = $this->usuarioModel->login($email, $password);

        if ($usuario === false) {
            header('Location: login.php?redirect=' . urlencode($redirect) . '&error=1');
            exit;
        }

        // Guardar datos en sesión
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        header('Location: ' . $redirect . '.php');
        exit;
    }

    /**
     * Muestra el formulario de registro
     */
    public function showRegister(): void
    {
        $data = [
            'titulo' => 'Registrarse - TuneFix',
            'error'  => $_GET['error'] ?? null,
        ];

        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Procesa el formulario de registro (solo POST)
     */
    public function processRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: register.php');
            exit;
        }

        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';
        $rol      = trim($_POST['rol'] ?? '');

        $rolesValidos = ['principiante', 'entusiasta', 'profesional'];

        // Validaciones básicas
        if ($nombre === '' || $email === '' || $password === '') {
            header('Location: register.php?error=campos');
            exit;
        }

        if (!in_array($rol, $rolesValidos, true)) {
            header('Location: register.php?error=rol');
            exit;
        }

        if ($password !== $confirm) {
            header('Location: register.php?error=password');
            exit;
        }

        if (strlen($password) < 6) {
            header('Location: register.php?error=corta');
            exit;
        }

        if ($this->usuarioModel->emailExiste($email)) {
            header('Location: register.php?error=email');
            exit;
        }

        $usuario = $this->usuarioModel->registrar($nombre, $email, $password, $rol);

        // Auto-login tras registro
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_rol']    = $usuario['rol'];

        header('Location: index.php');
        exit;
    }

    /**
     * Cierra la sesión
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
