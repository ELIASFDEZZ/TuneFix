<?php

require_once __DIR__ . '/../models/CarritoModel.php';

class CarritoController
{
    private CarritoModel $model;

    public function __construct()
    {
        $this->model = new CarritoModel();
    }

    public function index(): void
    {
        $usuarioId = (int) $_SESSION['usuario_id'];
        $items     = $this->model->getItems($usuarioId);
        $total     = $this->model->getTotal($usuarioId);

        $data = [
            'titulo' => 'Mi Carrito — TuneFix',
            'items'  => $items,
            'total'  => $total,
        ];

        $this->render('carrito/index', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
