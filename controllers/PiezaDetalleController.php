<?php

require_once __DIR__ . '/../models/PiezaModel.php';

class PiezaDetalleController
{
    private PiezaModel $piezaModel;

    public function __construct()
    {
        $this->piezaModel = new PiezaModel();
    }

    public function index(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            header('Location: todas-piezas.php');
            exit;
        }

        $pieza = $this->piezaModel->getById($id);

        if ($pieza === null) {
            header('Location: todas-piezas.php');
            exit;
        }

        $motorizacionId = isset($_GET['motorizacion_id']) ? (int) $_GET['motorizacion_id'] : 0;
        $vehiculo       = trim($_GET['vehiculo'] ?? '');

        $data = [
            'titulo'         => htmlspecialchars($pieza['nombre']) . ' - TuneFix',
            'pieza'          => $pieza,
            'motorizacionId' => $motorizacionId,
            'vehiculo'       => $vehiculo,
        ];

        $this->render('piezas/detalle', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
