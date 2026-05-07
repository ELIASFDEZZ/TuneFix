<?php

require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/SeguimientoModel.php';

/**
 * Controlador que gestiona la página de tutoriales para principiantes
 */
class TodosTutorialesController
{
    private TutorialModel      $tutorialModel;
    private SeguimientoModel   $seguimientoModel;

    public function __construct()
    {
        $this->tutorialModel    = new TutorialModel();
        $this->seguimientoModel = new SeguimientoModel();
    }

    /**
     * Acción principal: muestra tutoriales filtrados por motorización si se indica, o todos.
     */
    public function index(): void
    {
        $motorizacionId = isset($_GET['motorizacion_id']) ? (int) $_GET['motorizacion_id'] : 0;
        $vehiculo       = trim($_GET['vehiculo'] ?? '');
        $busqueda       = trim($_GET['busqueda'] ?? '');
        $usuarioId      = isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;

        $seguidos = $usuarioId
            ? array_column($this->seguimientoModel->getProfesionalesSeguidos($usuarioId), 'id')
            : [];

        if ($motorizacionId > 0) {
            $tutoriales = $this->tutorialModel->getAllByMotorizacion($motorizacionId, $busqueda);
            $titulo     = 'Tutoriales compatibles - TuneFix';
        } else {
            $tutoriales = $this->tutorialModel->getAll($busqueda);
            $titulo     = 'Tutoriales para Principiantes - TuneFix';
        }

        $data = [
            'titulo'         => $titulo,
            'tutoriales'     => $tutoriales,
            'busqueda'       => $busqueda,
            'seguidos'       => $seguidos,
            'usuarioId'      => $usuarioId,
            'motorizacionId' => $motorizacionId,
            'vehiculo'       => $vehiculo,
        ];

        $this->render('tutoriales/index', $data);
    }

    /**
     * Función auxiliar para cargar header + vista + footer
     */
    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
