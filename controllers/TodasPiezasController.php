<?php

require_once __DIR__ . '/../models/PiezaModel.php';

/**
 * Controlador que gestiona la página de todas las piezas.
 * Si se recibe motorizacion_id en GET, muestra solo las piezas
 * compatibles con esa motorización (venido desde la página de principiantes).
 */
class TodasPiezasController
{
    private PiezaModel $piezaModel;

    public function __construct()
    {
        $this->piezaModel = new PiezaModel();
    }

    public function index(): void
    {
        $motorizacionId = isset($_GET['motorizacion_id']) ? (int) $_GET['motorizacion_id'] : 0;
        $vehiculo       = trim($_GET['vehiculo'] ?? '');
        $busqueda       = trim($_GET['busqueda'] ?? '');

        if ($motorizacionId > 0) {
            // Piezas compatibles con la motorización (con búsqueda opcional dentro de ellas)
            $piezas  = $this->piezaModel->getAllByMotorizacion($motorizacionId, $busqueda);
            $titulo  = 'Piezas compatibles - TuneFix';
        } else {
            // Catálogo completo (con búsqueda opcional)
            $piezas  = $this->piezaModel->getAll($busqueda);
            $titulo  = 'Todas las Piezas - TuneFix';
        }

        $data = [
            'titulo'         => $titulo,
            'piezas'         => $piezas,
            'busqueda'       => $busqueda,
            'motorizacionId' => $motorizacionId,
            'vehiculo'       => $vehiculo,
        ];

        $this->render('piezas/index', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
