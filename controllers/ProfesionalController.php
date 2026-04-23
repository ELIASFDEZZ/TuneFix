<?php

require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/DistribuidorModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class ProfesionalController {

    private MarcaModel        $marcaModel;
    private DistribuidorModel $distribuidorModel;
    private PiezaModel        $piezaModel;
    private UsuarioModel      $usuarioModel;

    public function __construct() {
        $this->marcaModel        = new MarcaModel();
        $this->distribuidorModel = new DistribuidorModel();
        $this->piezaModel        = new PiezaModel();
        $this->usuarioModel      = new UsuarioModel();
    }

    public function index(): void {
        $cochesUsuario = [];
        if (isset($_SESSION['usuario_id'])) {
            $cochesUsuario = $this->usuarioModel->getCoches((int) $_SESSION['usuario_id']);
        }

        $data = [
            'titulo'        => 'Modo Profesional - TuneFix',
            'marcas'        => $this->marcaModel->getAll(),
            'cochesUsuario' => $cochesUsuario,
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