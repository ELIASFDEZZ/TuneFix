<?php

require_once __DIR__ . '/../models/ModeloModel.php';
require_once __DIR__ . '/../models/MotorizacionModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';

/**
 * Controlador que responde a las peticiones AJAX (devuelve JSON)
 * Se usa para los selects en cascada y mostrar resultados
 */
class AjaxController
{
    private ModeloModel $modeloModel;
    private MotorizacionModel $motorizacionModel;
    private PiezaModel $piezaModel;
    private TutorialModel $tutorialModel;

    public function __construct()
    {
        $this->modeloModel = new ModeloModel();
        $this->motorizacionModel = new MotorizacionModel();
        $this->piezaModel = new PiezaModel();
        $this->tutorialModel = new TutorialModel();
    }

    /**
     * Devuelve los modelos de una marca (para el segundo select)
     * URL ejemplo: get_modelos.php?marca_id=5
     */
    public function getModelos(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        // Validación básica
        if (!isset($_GET['marca_id']) || !is_numeric($_GET['marca_id'])) {
            echo json_encode([]);
            exit;
        }

        $modelos = $this->modeloModel->getByMarca((int) $_GET['marca_id']);
        echo json_encode($modelos);
        exit;
    }

    /**
     * Devuelve las motorizaciones de un modelo
     * Incluye nombre + potencia + combustible en un campo "texto" amigable
     */
    public function getMotorizaciones(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_GET['modelo_id']) || !is_numeric($_GET['modelo_id'])) {
            echo json_encode([]);
            exit;
        }

        $datos = $this->motorizacionModel->getByModelo((int) $_GET['modelo_id']);

        // Mejoramos la visualización para el frontend
        foreach ($datos as &$item) {
            $texto = $item['nombre'];
            if (!empty($item['potencia']))
                $texto .= " ({$item['potencia']})";
            if (!empty($item['tipo_combustible']))
                $texto .= " - {$item['tipo_combustible']}";
            $item['texto'] = $texto;
        }

        echo json_encode($datos);
        exit;
    }

    /**
     * Devuelve piezas y tutoriales compatibles
     * Puede filtrar por motorizacion_id o solo por modelo_id
     */
    public function getResultados(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $motId = isset($_GET['motorizacion_id']) && is_numeric($_GET['motorizacion_id']) ? (int) $_GET['motorizacion_id'] : null;
        $modelId = isset($_GET['modelo_id']) && is_numeric($_GET['modelo_id']) ? (int) $_GET['modelo_id'] : null;

        // Si no hay ningún filtro válido → devolvemos vacío
        if (!$motId && !$modelId) {
            echo json_encode(['piezas' => [], 'tutoriales' => []]);
            exit;
        }

        if ($motId) {
            // Prioridad: filtro por motorización concreta
            echo json_encode([
                'piezas' => $this->piezaModel->getByMotorizacion($motId, 3),
                'tutoriales' => $this->tutorialModel->getByMotorizacion($motId, 3),
            ]);
        } else {
            // Si solo tenemos modelo → mostramos de ese modelo
            echo json_encode([
                'piezas' => $this->piezaModel->getByModelo($modelId, 3),
                'tutoriales' => $this->tutorialModel->getByModelo($modelId, 3),
            ]);
        }
        exit;
    }
}