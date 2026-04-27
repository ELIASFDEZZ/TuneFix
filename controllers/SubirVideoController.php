<?php

require_once __DIR__ . '/../config/Database.php';

class SubirVideoController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function index(): void
    {
        $error   = null;
        $exito   = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo          = trim($_POST['titulo'] ?? '');
            $youtube_id      = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_POST['youtube_id'] ?? ''));
            $imagen          = trim($_POST['imagen'] ?? '') ?: null;
            $pieza_id        = !empty($_POST['pieza_id'])        ? (int) $_POST['pieza_id']        : null;
            $motorizacion_id = !empty($_POST['motorizacion_id']) ? (int) $_POST['motorizacion_id'] : null;

            if ($titulo === '' || $youtube_id === '') {
                $error = 'El título y el vídeo de YouTube son obligatorios.';
            } else {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO tutorial (titulo, youtube_id, imagen, pieza_id, motorizacion_id)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$titulo, $youtube_id, $imagen, $pieza_id, $motorizacion_id]);
                $exito = true;
            }
        }

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
