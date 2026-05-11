<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/SeguimientoModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';

class MiCanalController
{
    public function index(): void
    {
        if (($_SESSION['usuario_rol'] ?? '') !== 'profesional') {
            header('Location: index.php');
            exit;
        }

        $miId    = (int) $_SESSION['usuario_id'];
        $tabOpciones = ['videos', 'piezas', 'seguidores'];
        $tab         = in_array($_GET['tab'] ?? '', $tabOpciones) ? $_GET['tab'] : 'videos';
        $orden   = in_array($_GET['orden'] ?? '', ['recientes', 'populares', 'antiguos'])
                     ? $_GET['orden'] : 'recientes';

        $usuarioModel    = new UsuarioModel();
        $tutorialModel   = new TutorialModel();
        $seguimientoModel = new SeguimientoModel();

        $miUsuario       = $usuarioModel->getById($miId);
        $tutoriales      = $tutorialModel->getByUsuario($miId, $orden);
        $seguidores      = $seguimientoModel->getSeguidores($miId);
        $piezas          = (new PiezaModel())->getByProfesional($miId);
        $totalVideos     = count($tutoriales);
        $totalSeguidores = count($seguidores);
        $totalPiezas     = count($piezas);

        $handle = strtolower(preg_replace('/[^a-z0-9]/i', '', $miUsuario['nombre'] ?? ''));
        $handle = $handle ?: 'usuario' . $miId;

        $titulo = 'Mi Canal - TuneFix';

        $data = compact(
            'miUsuario', 'tutoriales', 'seguidores', 'piezas',
            'totalVideos', 'totalSeguidores', 'totalPiezas',
            'miId', 'handle', 'tab', 'orden', 'titulo'
        );

        $this->render('mi-canal/index', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
