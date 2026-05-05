<?php

require_once __DIR__ . '/../models/ProveedorModel.php';
require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';

class ProveedorController
{
    private ProveedorModel $model;
    private MarcaModel $marcaModel;
    private PiezaModel $piezaModel;
    private string $piezasDir;
    private string $piezasUrl;

    public function __construct()
    {
        $this->model      = new ProveedorModel();
        $this->marcaModel = new MarcaModel();
        $this->piezaModel = new PiezaModel();
        $this->piezasDir  = __DIR__ . '/../uploads/proveedores/piezas/';
        $this->piezasUrl  = 'uploads/proveedores/piezas/';
    }

    private function guard(): void
    {
        if (($_SESSION['usuario_rol'] ?? '') !== 'proveedor' || empty($_SESSION['proveedor_id'])) {
            header('Location: login.php?error=proveedor_pendiente');
            exit;
        }
    }

    private function proveedorId(): int
    {
        return (int)$_SESSION['proveedor_id'];
    }

    // ── Páginas ───────────────────────────────────────────────────────────────

    public function dashboard(): void
    {
        $this->guard();
        $proveedor  = $this->model->getPorId($this->proveedorId());
        $stats      = $this->model->getEstadisticas($this->proveedorId());
        $ultimasPiezas = array_slice($this->model->getPiezas($this->proveedorId()), 0, 5);

        $data = compact('proveedor', 'stats', 'ultimasPiezas');
        $this->render('dashboard', $data);
    }

    public function misPiezas(): void
    {
        $this->guard();
        $proveedor = $this->model->getPorId($this->proveedorId());
        $piezas    = $this->model->getPiezas($this->proveedorId());

        $data = compact('proveedor', 'piezas');
        $this->render('mis-piezas', $data);
    }

    public function publicarPieza(): void
    {
        $this->guard();
        $proveedor = $this->model->getPorId($this->proveedorId());
        $error     = $_GET['error'] ?? null;
        $marcas    = $this->marcaModel->getAll();

        $data = compact('proveedor', 'error', 'marcas');
        $this->render('publicar-pieza', $data);
    }

    public function guardarPieza(): void
    {
        $this->guard();

        // ── Si $_POST está vacío, el POST superó post_max_size ────────────
        if (empty($_POST)) {
            header('Location: proveedor.php?page=publicar-pieza&error=post_size');
            exit;
        }

        // ── Campos básicos ────────────────────────────────────────────────
        $nombre       = trim($_POST['nombre']       ?? '');
        $descripcion  = trim($_POST['descripcion']  ?? '');
        $precio       = $_POST['precio']            ?? '';
        $stock        = $_POST['stock']             ?? '';
        $categoria    = $_POST['categoria']         ?? '';
        $estado_pieza = $_POST['estado_pieza']      ?? '';
        $garantia     = $_POST['garantia']          ?? 'Sin garantía';
        $referencia   = trim($_POST['referencia_oem'] ?? '');
        $imagenUrl    = trim($_POST['imagen_url']   ?? '');

        if (!$nombre || !$descripcion || $precio === '' || $stock === '' || !$categoria || !$estado_pieza) {
            header('Location: proveedor.php?page=publicar-pieza&error=campos');
            exit;
        }

        // ── Al menos 1 vehículo ───────────────────────────────────────────
        $marcas  = array_filter(array_map('trim', $_POST['marca_nombre']       ?? []), fn($v) => $v !== '');
        $modelos = array_map('trim', $_POST['modelo_nombre']      ?? []);
        $motors  = array_map('trim', $_POST['motorizacion_texto'] ?? []);

        if (empty($marcas)) {
            header('Location: proveedor.php?page=publicar-pieza&error=vehiculo');
            exit;
        }

        try {
            // ── Crear pieza en la tabla pieza ─────────────────────────────
            $piezaId = $this->model->crearPieza([
                'nombre'         => $nombre,
                'referencia_oem' => $referencia ?: null,
                'categoria'      => $categoria,
                'estado_pieza'   => $estado_pieza,
                'precio'         => (float)str_replace(',', '.', $precio),
                'stock'          => (int)$stock,
                'descripcion'    => $descripcion,
                'garantia'       => $garantia,
                'imagen'         => $imagenUrl,
            ], $this->proveedorId());

            if ($imagenUrl === '' && !empty($_FILES['fotos']['name'][0])) {
                if (!is_dir($this->piezasDir)) {
                    mkdir($this->piezasDir, 0755, true);
                }

                $total = count($_FILES['fotos']['name']);
                $orden = 0;
                for ($i = 0; $i < min($total, 6); $i++) {
                    if (($_FILES['fotos']['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
                    $ruta = $this->subirFoto($_FILES['fotos'], $i, $piezaId, $orden);
                    if ($ruta) {
                        $this->model->addFotoPieza($piezaId, $ruta, $orden);
                        $orden++;
                    }
                }
            }

            // ── Vehículos compatibles ─────────────────────────────────────
            $motorizacionIds = array_filter(array_map('intval', $_POST['motorizacion_id'] ?? []), fn($id) => $id > 0);
            foreach ($motorizacionIds as $motorizacionId) {
                $this->model->addVehiculoPieza($piezaId, $motorizacionId);
            }

        } catch (\Throwable $e) {
            error_log('ProveedorController::guardarPieza error: ' . $e->getMessage());
            header('Location: proveedor.php?page=publicar-pieza&error=db');
            exit;
        }

        header('Location: proveedor.php?page=mis-piezas&ok=publicada');
        exit;
    }

    public function togglePieza(): void
    {
        $this->guard();
        $piezaId = (int)($_POST['pieza_id'] ?? 0);
        if ($piezaId > 0) {
            $this->model->toggleActiva($piezaId, $this->proveedorId());
        }
        header('Location: proveedor.php?page=mis-piezas');
        exit;
    }

    public function estadisticas(): void
    {
        $this->guard();
        $proveedor = $this->model->getPorId($this->proveedorId());
        $stats     = $this->model->getEstadisticas($this->proveedorId());
        $piezas    = $this->model->getPiezas($this->proveedorId());

        $data = compact('proveedor', 'stats', 'piezas');
        $this->render('estadisticas', $data);
    }

    public function perfil(): void
    {
        $this->guard();
        $proveedor = $this->model->getPorId($this->proveedorId());
        $ok    = isset($_GET['ok']);
        $error = $_GET['error'] ?? null;

        $data = compact('proveedor', 'ok', 'error');
        $this->render('perfil', $data);
    }

    public function guardarPerfil(): void
    {
        $this->guard();
        $datos = [
            'nombre_empresa' => trim($_POST['nombre_empresa'] ?? ''),
            'provincia'      => trim($_POST['provincia']      ?? ''),
            'telefono'       => trim($_POST['telefono']       ?? ''),
            'sitio_web'      => trim($_POST['sitio_web']      ?? '') ?: null,
            'descripcion'    => trim($_POST['descripcion']    ?? ''),
        ];
        if (!$datos['nombre_empresa'] || !$datos['provincia'] || !$datos['telefono']) {
            header('Location: proveedor.php?page=perfil&error=campos');
            exit;
        }
        $this->model->actualizarPerfil($this->proveedorId(), $datos);
        $_SESSION['usuario_nombre']    = $datos['nombre_empresa'];
        $_SESSION['proveedor_empresa'] = $datos['nombre_empresa'];
        header('Location: proveedor.php?page=perfil&ok=1');
        exit;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function subirFoto(array $files, int $index, int $piezaId, int $orden): string|false
    {
        $extsOk  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $maxSize = 8 * 1024 * 1024; // 8 MB

        $tmpName = $files['tmp_name'][$index] ?? '';
        $origName = $files['name'][$index] ?? '';
        $size    = $files['size'][$index] ?? 0;

        if (!$tmpName || !is_uploaded_file($tmpName)) return false;
        if ($size > $maxSize || $size === 0) return false;

        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        if (!in_array($ext, $extsOk, true)) return false;

        // Verificar firma de bytes del archivo (magic bytes) — sin mime_content_type()
        $fh  = fopen($tmpName, 'rb');
        $sig = fread($fh, 12);
        fclose($fh);
        $esImagen = (
            str_starts_with($sig, "\xFF\xD8\xFF")           // JPEG
            || str_starts_with($sig, "\x89PNG\r\n\x1A\n")   // PNG
            || str_starts_with($sig, "RIFF") && str_contains($sig, "WEBP") // WEBP
            || str_starts_with($sig, "GIF8")                // GIF
        );
        if (!$esImagen) return false;

        $nombre  = 'pieza_' . $piezaId . '_' . $orden . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destino = $this->piezasDir . $nombre;
        if (!move_uploaded_file($tmpName, $destino)) return false;
        return $nombre;
    }

    private function render(string $vistaName, array $data = []): void
    {
        $vista = $vistaName;
        extract($data);
        require_once __DIR__ . '/../views/proveedor/layout.php';
    }
}
