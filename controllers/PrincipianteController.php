<?php

require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';

/**
 * Controlador que gestiona la sección para usuarios principiantes
 */
class PrincipianteController
{
    private MarcaModel $marcaModel;
    private TutorialModel $tutorialModel;
    private PiezaModel $piezaModel;

    public function __construct()
    {
        $this->marcaModel = new MarcaModel();
        $this->tutorialModel = new TutorialModel();
        $this->piezaModel = new PiezaModel();
    }

    /**
     * Acción principal: muestra la página de principiantes
     * Carga marcas, tutoriales recientes y piezas recientes
     */
    public function index(): void
    {
        // Preparamos todos los datos que necesita la vista
        $data = [
            'titulo' => 'Principiantes - TuneFix',
            'marcas' => $this->marcaModel->getAll(),               // Todas las marcas para el primer select
            'tutoriales' => $this->tutorialModel->getRecientes(4),     // Últimos 4 tutoriales
            'piezas' => $this->piezaModel->getRecientes(6),        // Últimas 6 piezas
        ];

        // Renderizamos la vista con los datos
        $this->render('principiante/index', $data);
    }

    /**
     * Función auxiliar para cargar header + vista + footer
     * @param string $vista Ruta relativa dentro de views/ (sin .php)
     * @param array $data Variables que se pasarán a la vista
     */
    private function render(string $vista, array $data = []): void
    {
        // Convertimos el array $data en variables individuales
        extract($data);

        // Cargamos las 3 partes comunes + la vista específica
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}