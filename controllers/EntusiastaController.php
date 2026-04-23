<?php

require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class EntusiastaController {

    private MarcaModel    $marcaModel;
    private TutorialModel $tutorialModel;
    private PiezaModel    $piezaModel;
    private UsuarioModel  $usuarioModel;

    public function __construct() {
        $this->marcaModel    = new MarcaModel();
        $this->tutorialModel = new TutorialModel();
        $this->piezaModel    = new PiezaModel();
        $this->usuarioModel  = new UsuarioModel();
    }

    public function index(): void {
        $cochesUsuario = [];
        if (isset($_SESSION['usuario_id'])) {
            $cochesUsuario = $this->usuarioModel->getCoches((int) $_SESSION['usuario_id']);
        }

        $data = [
            'titulo'        => 'Modo Entusiasta - TuneFix',
            'marcas'        => $this->marcaModel->getAll(),
            'tutoriales'    => $this->tutorialModel->getRecientes(8),
            'piezas'        => $this->piezaModel->getRecientes(12),
            'cochesUsuario' => $cochesUsuario,
        ];

        $this->render('entusiasta/index', $data);
    }

    private function render(string $vista, array $data = []): void {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}