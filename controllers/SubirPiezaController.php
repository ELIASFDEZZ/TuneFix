<?php

require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/MarcaModel.php';

class SubirPiezaController
{
    private PiezaModel $piezaModel;
    private MarcaModel $marcaModel;

    public function __construct()
    {
        $this->piezaModel = new PiezaModel();
        $this->marcaModel = new MarcaModel();
    }

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->guardar();
            return;
        }
        $this->mostrarFormulario();
    }

    private function guardar(): void
    {
        $nombre      = trim($_POST['nombre']      ?? '');
        $referencia  = trim($_POST['referencia']  ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '') ?: null;
        $imagen      = trim($_POST['imagen']      ?? '') ?: '';
        $url         = trim($_POST['url']         ?? '') ?: null;
        $categoria   = trim($_POST['categoria']   ?? '') ?: null;
        $estadoPieza = $_POST['estado_pieza'] ?? 'nueva';
        $precio      = is_numeric($_POST['precio'] ?? '') ? (float) $_POST['precio'] : 0.00;
        $stock       = ctype_digit($_POST['stock'] ?? '') ? (int) $_POST['stock'] : 0;
        $garantia    = trim($_POST['garantia'] ?? '') ?: 'Sin garantía';
        $motorizId   = !empty($_POST['motorizacion_id']) ? (int) $_POST['motorizacion_id'] : null;

        if ($nombre === '' || $referencia === '') {
            $this->mostrarFormulario('El nombre y la referencia son obligatorios.');
            return;
        }

        if (!in_array($estadoPieza, ['nueva', 'usada_buena', 'usada_desgaste'], true)) {
            $estadoPieza = 'nueva';
        }

        if ($this->piezaModel->referenciaExiste($referencia)) {
            $this->mostrarFormulario("La referencia «{$referencia}» ya está registrada. Usa una referencia única.");
            return;
        }

        $piezaId = $this->piezaModel->crear([
            'referencia'   => $referencia,
            'nombre'       => $nombre,
            'descripcion'  => $descripcion,
            'imagen'       => $imagen,
            'url'          => $url,
            'categoria'    => $categoria,
            'estado_pieza' => $estadoPieza,
            'precio'       => $precio,
            'stock'        => $stock,
            'garantia'     => $garantia,
            'subido_por'   => (int) $_SESSION['usuario_id'],
        ]);

        if ($motorizId) {
            $this->piezaModel->agregarCompatibilidad($piezaId, $motorizId);
        }

        $this->mostrarFormulario(null, true, $piezaId);
    }

    private function mostrarFormulario(?string $error = null, bool $exito = false, int $piezaId = 0): void
    {
        $marcas = $this->marcaModel->getAll();

        $titulo_pagina = 'Subir pieza - TuneFix';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/subir-pieza/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
