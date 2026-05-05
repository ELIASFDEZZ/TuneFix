<?php

require_once __DIR__ . '/../models/ProveedorModel.php';
require_once __DIR__ . '/../config/mailer.php';

class SolicitudProveedorController
{
    private ProveedorModel $model;

    // Carpeta para documentos protegidos (fuera del webroot público)
    private string $docsDir;
    private string $piezasDir;

    public function __construct()
    {
        $this->model    = new ProveedorModel();
        $this->docsDir  = __DIR__ . '/../uploads/proveedores/docs/';
        $this->piezasDir = __DIR__ . '/../uploads/proveedores/piezas/';
    }

    public function show(): void
    {
        $data = [
            'titulo' => 'Solicitar acceso como Proveedor - TuneFix',
            'error'  => $_GET['error'] ?? null,
        ];
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/solicitud-proveedor/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function process(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: solicitud-proveedor.php');
            exit;
        }

        // ── Recoger campos ────────────────────────────────────────────────────
        $nombre_empresa     = trim($_POST['nombre_empresa']     ?? '');
        $cif                = strtoupper(trim($_POST['cif']      ?? ''));
        $direccion          = trim($_POST['direccion']           ?? '');
        $provincia          = trim($_POST['provincia']           ?? '');
        $telefono           = trim($_POST['telefono']            ?? '');
        $sitio_web          = trim($_POST['sitio_web']           ?? '');
        $nombre_responsable = trim($_POST['nombre_responsable']  ?? '');
        $email              = trim($_POST['email']               ?? '');
        $password           = $_POST['password']                 ?? '';
        $confirm            = $_POST['confirm']                  ?? '';
        $descripcion        = trim($_POST['descripcion']         ?? '');

        // ── Validaciones ──────────────────────────────────────────────────────
        if (!$nombre_empresa || !$cif || !$direccion || !$provincia ||
            !$telefono || !$nombre_responsable || !$email || !$password || !$descripcion) {
            header('Location: solicitud-proveedor.php?error=campos');
            exit;
        }

        if (!preg_match('/^[A-Z][0-9]{8}$|^[0-9]{8}[A-Z]$/i', $cif)) {
            header('Location: solicitud-proveedor.php?error=cif');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: solicitud-proveedor.php?error=email_formato');
            exit;
        }

        if ($password !== $confirm) {
            header('Location: solicitud-proveedor.php?error=password');
            exit;
        }

        if (strlen($password) < 8) {
            header('Location: solicitud-proveedor.php?error=corta');
            exit;
        }

        if (strlen($descripcion) < 50) {
            header('Location: solicitud-proveedor.php?error=descripcion');
            exit;
        }

        if ($this->model->emailExiste($email)) {
            header('Location: solicitud-proveedor.php?error=email_existe');
            exit;
        }

        if ($this->model->cifExiste($cif)) {
            header('Location: solicitud-proveedor.php?error=cif_existe');
            exit;
        }

        // ── Subida de documentos ──────────────────────────────────────────────
        if (!isset($_FILES['doc_cif']) || $_FILES['doc_cif']['error'] !== UPLOAD_ERR_OK) {
            header('Location: solicitud-proveedor.php?error=doc_cif');
            exit;
        }
        if (!isset($_FILES['doc_iae']) || $_FILES['doc_iae']['error'] !== UPLOAD_ERR_OK) {
            header('Location: solicitud-proveedor.php?error=doc_iae');
            exit;
        }

        $rutaCif = $this->subirDocumento($_FILES['doc_cif'], 'cif_' . preg_replace('/[^a-z0-9]/i', '_', $cif));
        $rutaIae = $this->subirDocumento($_FILES['doc_iae'], 'iae_' . preg_replace('/[^a-z0-9]/i', '_', $cif));

        if (!$rutaCif || !$rutaIae) {
            header('Location: solicitud-proveedor.php?error=upload');
            exit;
        }

        // ── Guardar en BD ─────────────────────────────────────────────────────
        $this->model->crear([
            'nombre_empresa'     => $nombre_empresa,
            'cif'                => $cif,
            'direccion'          => $direccion,
            'provincia'          => $provincia,
            'telefono'           => $telefono,
            'sitio_web'          => $sitio_web ?: null,
            'nombre_responsable' => $nombre_responsable,
            'email'              => $email,
            'password'           => $password,
            'descripcion'        => $descripcion,
            'doc_cif'            => $rutaCif,
            'doc_iae'            => $rutaIae,
        ]);

        // ── Notificar al admin ────────────────────────────────────────────────
        enviarNotificacionAdminNuevaSolicitud($nombre_empresa, $cif, $nombre_responsable, $email);

        header('Location: solicitud-proveedor.php?ok=1');
        exit;
    }

    private function subirDocumento(array $file, string $prefijo): string|false
    {
        $maxSize  = 8 * 1024 * 1024; // 8 MB
        $tiposOk  = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        $extsOk   = ['pdf', 'jpg', 'jpeg', 'png'];

        if ($file['size'] > $maxSize) return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $extsOk, true)) return false;

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $tiposOk, true)) return false;

        if (!is_dir($this->docsDir)) {
            mkdir($this->docsDir, 0755, true);
        }

        $nombreFinal = $prefijo . '_' . uniqid() . '.' . $ext;
        $destino     = $this->docsDir . $nombreFinal;

        if (!move_uploaded_file($file['tmp_name'], $destino)) return false;

        return $nombreFinal;
    }
}
