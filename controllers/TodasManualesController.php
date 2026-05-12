<?php

require_once __DIR__ . '/../models/ManualModel.php';

class TodasManualesController
{
    private ManualModel $manualModel;

    public function __construct()
    {
        $this->manualModel = new ManualModel();
    }

    public function index(): void
    {
        $motorizacionId = isset($_GET['motorizacion_id']) ? (int) $_GET['motorizacion_id'] : 0;
        $vehiculo       = trim($_GET['vehiculo'] ?? '');
        $busqueda       = trim($_GET['busqueda'] ?? '');

        if ($motorizacionId > 0) {
            $manuales = $this->manualModel->getAllByMotorizacion($motorizacionId, $busqueda);
            $titulo   = 'Manuales compatibles - TuneFix';
        } else {
            $manuales = $this->manualModel->getAll($busqueda);
            $titulo   = 'Todos los Manuales - TuneFix';
        }

        $data = [
            'titulo'         => $titulo,
            'manuales'       => $manuales,
            'busqueda'       => $busqueda,
            'motorizacionId' => $motorizacionId,
            'vehiculo'       => $vehiculo,
        ];

        $this->render('manuales/index', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
