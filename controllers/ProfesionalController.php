<?php

require_once __DIR__ . '/../models/MarcaModel.php';
require_once __DIR__ . '/../models/DistribuidorModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/SeguimientoModel.php';

class ProfesionalController {

    private MarcaModel        $marcaModel;
    private DistribuidorModel $distribuidorModel;
    private PiezaModel        $piezaModel;
    private UsuarioModel      $usuarioModel;
    private SeguimientoModel  $seguimientoModel;

    public function __construct() {
        $this->marcaModel        = new MarcaModel();
        $this->distribuidorModel = new DistribuidorModel();
        $this->piezaModel        = new PiezaModel();
        $this->usuarioModel      = new UsuarioModel();
        $this->seguimientoModel  = new SeguimientoModel();
    }

    public function index(): void {
        $usuarioId     = isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
        $profesionalId = $usuarioId; // Esta página siempre muestra el perfil del usuario autenticado

        $cochesUsuario = $usuarioId
            ? $this->usuarioModel->getCoches($usuarioId)
            : [];

        $numSeguidores = $profesionalId
            ? $this->seguimientoModel->contarSeguidores($profesionalId)
            : 0;

        $estaSiguiendo = ($usuarioId && $profesionalId && $usuarioId !== $profesionalId)
            ? $this->seguimientoModel->estaSiguiendo($usuarioId, $profesionalId)
            : false;

        $data = [
            'titulo'        => 'Modo Profesional - TuneFix',
            'marcas'        => $this->marcaModel->getAll(),
            'cochesUsuario' => $cochesUsuario,
            'numSeguidores' => $numSeguidores,
            'profesionalId' => $profesionalId,
            'estaSiguiendo' => $estaSiguiendo,
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