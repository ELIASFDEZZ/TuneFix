<?php

require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';

class ProfesionalController {

    private MarcaModel    $marcaModel;
    private TutorialModel $tutorialModel;
    private PiezaModel    $piezaModel;

    public function __construct() {
        $this->marcaModel    = new MarcaModel();
        $this->tutorialModel = new TutorialModel();
        $this->piezaModel    = new PiezaModel();
    }

    public function index(): void {
        $data = [
            'titulo'     => 'Modo Profesional - TuneFix',
            'marcas'     => $this->marcaModel->getAll(),
            'tutoriales' => $this->tutorialModel->getRecientes(15),   // Sin límite práctico
            'piezas'     => $this->piezaModel->getRecientes(20),
        ];

        $this->render('profesional/index', $data);
    }

    private function render(string $vista, array $data = []): void {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}