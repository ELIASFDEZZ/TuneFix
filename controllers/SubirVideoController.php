<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/mailer.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/SeguimientoModel.php';

class SubirVideoController
{
    private PDO              $pdo;
    private TutorialModel    $tutorialModel;
    private SeguimientoModel $seguimientoModel;

    public function __construct()
    {
        $this->pdo              = Database::getConnection();
        $this->tutorialModel    = new TutorialModel();
        $this->seguimientoModel = new SeguimientoModel();
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
        $titulo          = trim($_POST['titulo'] ?? '');
        $youtube_id      = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_POST['youtube_id'] ?? ''));
        $imagen          = trim($_POST['imagen'] ?? '') ?: null;
        $pieza_id        = !empty($_POST['pieza_id'])        ? (int) $_POST['pieza_id']        : null;
        $motorizacion_id = !empty($_POST['motorizacion_id']) ? (int) $_POST['motorizacion_id'] : null;
        $usuario_id      = (int) $_SESSION['usuario_id'];

        if ($titulo === '' || $youtube_id === '') {
            $this->mostrarFormulario('El título y el vídeo de YouTube son obligatorios.');
            return;
        }

        $tutorialId = $this->tutorialModel->crear([
            'usuario_id'      => $usuario_id,
            'titulo'          => $titulo,
            'youtube_id'      => $youtube_id,
            'imagen'          => $imagen,
            'pieza_id'        => $pieza_id,
            'motorizacion_id' => $motorizacion_id,
        ]);

        $this->enviarNotificaciones($usuario_id, $titulo, $youtube_id);

        $this->mostrarFormulario(null, true);
    }

    private function enviarNotificaciones(int $profesionalId, string $titulo, string $youtubeId): void
    {
        $nombreProfesional = $_SESSION['usuario_nombre'] ?? 'Un profesional';
        $seguidores        = $this->seguimientoModel->getSeguidores($profesionalId);

        foreach ($seguidores as $seguidor) {
            $ok = enviarNotificacionNuevoVideo(
                $seguidor['email'],
                $seguidor['nombre'],
                $nombreProfesional,
                $titulo,
                $youtubeId
            );
            if (!$ok) {
                error_log("SubirVideoController: fallo al enviar notificación a {$seguidor['email']}");
            }
        }
    }

    private function mostrarFormulario(?string $error = null, bool $exito = false): void
    {
        $piezas = $this->pdo->query(
            "SELECT id, nombre FROM pieza ORDER BY nombre ASC"
        )->fetchAll();

        $motorizaciones = $this->pdo->query(
            "SELECT mz.id, CONCAT(ma.nombre, ' ', mo.nombre, ' · ', mz.nombre) AS label
             FROM motorizacion mz
             JOIN modelo mo ON mo.id = mz.modelo_id
             JOIN marca ma  ON ma.id = mo.marca_id
             ORDER BY ma.nombre, mo.nombre, mz.nombre"
        )->fetchAll();

        $titulo_pagina = 'Subir vídeo - TuneFix';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/subir-video/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
