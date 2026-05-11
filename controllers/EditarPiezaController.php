<?php

require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/MarcaModel.php';

class EditarPiezaController
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
        $miId = (int) $_SESSION['usuario_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['_action'] ?? 'guardar';
            $id     = (int) ($_POST['id'] ?? 0);

            if ($action === 'eliminar') {
                $this->eliminar($id, $miId);
            } else {
                $this->guardar($id, $miId);
            }
            return;
        }

        $id    = (int) ($_GET['id'] ?? 0);
        $pieza = $this->cargarPiezaPropia($id, $miId);
        $this->mostrarFormulario($pieza);
    }

    private function guardar(int $id, int $miId): void
    {
        $pieza = $this->cargarPiezaPropia($id, $miId);

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

        if ($nombre === '' || $referencia === '') {
            $this->mostrarFormulario($pieza, 'El nombre y la referencia son obligatorios.');
            return;
        }

        if (!in_array($estadoPieza, ['nueva', 'usada_buena', 'usada_desgaste'], true)) {
            $estadoPieza = 'nueva';
        }

        // Verificar referencia única si cambió
        if ($referencia !== $pieza['referencia'] && $this->piezaModel->referenciaExiste($referencia)) {
            $this->mostrarFormulario($pieza, "La referencia «{$referencia}» ya está en uso.");
            return;
        }

        $this->piezaModel->actualizar($id, [
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
            'subido_por'   => $miId,
        ]);

        header('Location: mi-canal.php?tab=piezas&editado=1');
        exit;
    }

    private function eliminar(int $id, int $miId): void
    {
        $this->piezaModel->eliminar($id, $miId);
        header('Location: mi-canal.php?tab=piezas&eliminado=1');
        exit;
    }

    private function cargarPiezaPropia(int $id, int $miId): array
    {
        if ($id <= 0) { header('Location: mi-canal.php?tab=piezas'); exit; }

        $pieza = $this->piezaModel->getByIdFull($id);

        if (!$pieza || (int) $pieza['subido_por'] !== $miId) {
            header('Location: mi-canal.php?tab=piezas');
            exit;
        }
        return $pieza;
    }

    private function mostrarFormulario(array $pieza, ?string $error = null): void
    {
        $marcas = $this->marcaModel->getAll();
        $titulo_pagina = 'Editar pieza - TuneFix';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/editar-pieza/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
