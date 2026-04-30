<?php

require_once __DIR__ . '/../models/SeguimientoModel.php';

class SeguimientoController
{
    private SeguimientoModel $seguimientoModel;

    public function __construct()
    {
        $this->seguimientoModel = new SeguimientoModel();
    }

    /** POST — responde JSON {success, seguidores} */
    public function seguir(): void
    {
        $this->requireSesion();
        $this->requirePost();

        $seguidorId    = (int) $_SESSION['usuario_id'];
        $profesionalId = (int) ($_POST['profesional_id'] ?? 0);

        if ($profesionalId <= 0 || $profesionalId === $seguidorId) {
            $this->json(['success' => false, 'error' => 'Acción no permitida']);
            return;
        }

        $ok        = $this->seguimientoModel->seguir($seguidorId, $profesionalId);
        $seguidores = $this->seguimientoModel->contarSeguidores($profesionalId);

        $this->json(['success' => $ok, 'seguidores' => $seguidores]);
    }

    /** POST — responde JSON {success, seguidores} */
    public function dejarDeSeguir(): void
    {
        $this->requireSesion();
        $this->requirePost();

        $seguidorId    = (int) $_SESSION['usuario_id'];
        $profesionalId = (int) ($_POST['profesional_id'] ?? 0);

        if ($profesionalId <= 0 || $profesionalId === $seguidorId) {
            $this->json(['success' => false, 'error' => 'Acción no permitida']);
            return;
        }

        $ok        = $this->seguimientoModel->dejarDeSeguir($seguidorId, $profesionalId);
        $seguidores = $this->seguimientoModel->contarSeguidores($profesionalId);

        $this->json(['success' => $ok, 'seguidores' => $seguidores]);
    }

    /** GET — vista de seguidores del profesional autenticado */
    public function misSeguidores(): void
    {
        $this->requireSesion();

        if ($_SESSION['usuario_rol'] !== 'profesional') {
            header('Location: index.php');
            exit;
        }

        $profesionalId = (int) $_SESSION['usuario_id'];
        $seguidores    = $this->seguimientoModel->getSeguidores($profesionalId);
        $total         = count($seguidores);

        $titulo_pagina = 'Mis seguidores - TuneFix';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/profesional/mis-seguidores.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    private function requireSesion(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'No autenticado']);
            } else {
                header('Location: login.php');
            }
            exit;
        }
    }

    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }
    }

    private function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function json(array $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
