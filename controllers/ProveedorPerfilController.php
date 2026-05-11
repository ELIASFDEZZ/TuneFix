<?php

require_once __DIR__ . '/../models/ProveedorModel.php';

class ProveedorPerfilController
{
    private ProveedorModel $proveedorModel;

    public function __construct()
    {
        $this->proveedorModel = new ProveedorModel();
    }

    public function index(): void
    {
        $proveedorId = (int) ($_GET['id'] ?? 0);

        if ($proveedorId <= 0) {
            header('Location: index.php');
            exit;
        }

        $proveedor = $this->proveedorModel->getPorId($proveedorId);

        if (!$proveedor || $proveedor['estado'] !== 'aceptado') {
            header('Location: index.php');
            exit;
        }

        $piezas      = $this->proveedorModel->getPiezas($proveedorId);
        $totalPiezas = count($piezas);

        $titulo = htmlspecialchars($proveedor['nombre_empresa']) . ' - TuneFix';

        extract(compact('proveedor', 'piezas', 'totalPiezas', 'titulo', 'proveedorId'));

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/proveedor-perfil/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
