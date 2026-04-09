<?php

require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class PrincipianteController
{
    private MarcaModel    $marcaModel;
    private TutorialModel $tutorialModel;
    private PiezaModel    $piezaModel;
    private UsuarioModel  $usuarioModel;

    public function __construct()
    {
        $this->marcaModel    = new MarcaModel();
        $this->tutorialModel = new TutorialModel();
        $this->piezaModel    = new PiezaModel();
        $this->usuarioModel  = new UsuarioModel();
    }

    public function index(): void
    {
        // Coches guardados del usuario (solo si está logueado)
        $cochesUsuario = [];
        if (isset($_SESSION['usuario_id'])) {
            $cochesUsuario = $this->usuarioModel->getCoches((int) $_SESSION['usuario_id']);
        }

        $data = [
            'titulo'        => 'Principiantes - TuneFix',
            'marcas'        => $this->marcaModel->getAll(),
            'tutoriales'    => $this->tutorialModel->getRecientes(4),
            'piezas'        => $this->piezaModel->getRecientes(6),
            'cochesUsuario' => $cochesUsuario,
        ];

        $this->render('principiante/index', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
