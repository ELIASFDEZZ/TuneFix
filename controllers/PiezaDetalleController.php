<?php

require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/MeGustaPiezaModel.php';

class PiezaDetalleController
{
    private PiezaModel        $piezaModel;
    private MeGustaPiezaModel $meGustaModel;

    public function __construct()
    {
        $this->piezaModel   = new PiezaModel();
        $this->meGustaModel = new MeGustaPiezaModel();
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
        $usuarioId      = isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
        $meGusta        = $usuarioId ? $this->meGustaModel->isLiked($usuarioId, $id) : false;
        $totalMeGusta   = $this->meGustaModel->getCount($id);

        $data = [
            'titulo'         => htmlspecialchars($pieza['nombre']) . ' - TuneFix',
            'pieza'          => $pieza,
            'motorizacionId' => $motorizacionId,
            'vehiculo'       => $vehiculo,
            'usuarioId'      => $usuarioId,
            'meGusta'        => $meGusta,
            'totalMeGusta'   => $totalMeGusta,
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
