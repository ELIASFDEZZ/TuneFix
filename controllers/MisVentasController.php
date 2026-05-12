<?php

require_once __DIR__ . '/../models/VentasProfesionalModel.php';

class MisVentasController
{
    private VentasProfesionalModel $model;

    public function __construct()
    {
        $this->model = new VentasProfesionalModel();
    }

    public function index(): void
    {
        $usuarioId = (int) $_SESSION['usuario_id'];

        $resumen      = $this->model->getResumen($usuarioId);
        $lineas       = $this->model->getLineasPedidos($usuarioId);
        $misPiezas    = $this->model->getMisPiezas($usuarioId);

        // Agrupar líneas por pedido
        $pedidos = [];
        foreach ($lineas as $linea) {
            $pid = $linea['pedido_id'];
            if (!isset($pedidos[$pid])) {
                $pedidos[$pid] = [
                    'pedido_id'  => $pid,
                    'fecha'      => $linea['fecha'],
                    'estado'     => $linea['estado'],
                    'comprador'  => $linea['comprador'],
                    'items'      => [],
                    'subtotal'   => 0,
                ];
            }
            $pedidos[$pid]['items'][]   = $linea;
            $pedidos[$pid]['subtotal'] += (float) $linea['subtotal'];
        }

        $data = [
            'titulo'    => 'Mis Ventas - TuneFix',
            'resumen'   => $resumen,
            'pedidos'   => $pedidos,
            'misPiezas' => $misPiezas,
        ];

        $this->render('profesional/mis-ventas', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
