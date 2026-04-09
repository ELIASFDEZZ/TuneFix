<?php

require_once __DIR__ . '/../models/TutorialModel.php';

/**
 * Controlador que gestiona la página de tutoriales para principiantes
 */
class TodosTutorialesController
{
    private TutorialModel $tutorialModel;

    public function __construct()
    {
        $this->tutorialModel = new TutorialModel();
    }

    /**
     * Acción principal: muestra todos los tutoriales con búsqueda opcional
     */
    public function index(): void
    {
        $busqueda = trim($_GET['busqueda'] ?? '');

        $data = [
            'titulo'     => 'Tutoriales para Principiantes - TuneFix',
            'tutoriales' => $this->tutorialModel->getAll($busqueda),
            'busqueda'   => $busqueda,
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
