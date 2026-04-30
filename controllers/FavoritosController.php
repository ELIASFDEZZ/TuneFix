<?php

require_once __DIR__ . '/../models/MeGustaPiezaModel.php';

class FavoritosController
{
    private MeGustaPiezaModel $meGustaModel;

    public function __construct()
    {
        $this->meGustaModel = new MeGustaPiezaModel();
    }

    public function index(): void
    {
        $usuarioId     = (int) $_SESSION['usuario_id'];
        $piezasGuardadas = $this->meGustaModel->getPiezasGuardadas($usuarioId);

        $data = [
            'titulo'          => 'Mis Favoritos - TuneFix',
            'piezasGuardadas' => $piezasGuardadas,
            'usuarioId'       => $usuarioId,
        ];

        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/favoritos/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
