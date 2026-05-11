<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/TutorialModel.php';
require_once __DIR__ . '/../models/SeguimientoModel.php';
require_once __DIR__ . '/../models/PiezaModel.php';

class PerfilUsuarioController
{
    private UsuarioModel     $usuarioModel;
    private TutorialModel    $tutorialModel;
    private SeguimientoModel $seguimientoModel;
    private PiezaModel       $piezaModel;

    public function __construct()
    {
        $this->usuarioModel     = new UsuarioModel();
        $this->tutorialModel    = new TutorialModel();
        $this->seguimientoModel = new SeguimientoModel();
        $this->piezaModel       = new PiezaModel();
    }

    public function index(): void
    {
        $perfilId = (int) ($_GET['id'] ?? 0);

        if ($perfilId <= 0) {
            header('Location: index.php');
            exit;
        }

        $perfil = $this->usuarioModel->getById($perfilId);

        if (!$perfil) {
            header('Location: index.php');
            exit;
        }

        $tab    = in_array($_GET['tab'] ?? '', ['videos', 'piezas']) ? $_GET['tab'] : 'videos';
        $orden  = in_array($_GET['orden'] ?? '', ['recientes', 'populares', 'antiguos'])
                    ? $_GET['orden']
                    : 'recientes';

        $tutoriales      = $this->tutorialModel->getByUsuario($perfilId, $orden);
        $piezas          = $this->piezaModel->getByProfesional($perfilId);
        $totalVideos     = count($tutoriales);
        $totalPiezas     = count($piezas);
        $totalSeguidores = $this->seguimientoModel->contarSeguidores($perfilId);

        $miId       = isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
        $yoSigo     = $miId ? $this->seguimientoModel->estaSiguiendo($miId, $perfilId) : false;
        $esMiPerfil = $miId === $perfilId;

        $handle = strtolower(preg_replace('/[^a-z0-9]/i', '', $perfil['nombre']));
        $handle = $handle ?: 'usuario' . $perfilId;

        $titulo = htmlspecialchars($perfil['nombre']) . ' - TuneFix';

        $data = compact(
            'perfil', 'tutoriales', 'piezas',
            'totalVideos', 'totalPiezas', 'totalSeguidores',
            'miId', 'yoSigo', 'esMiPerfil', 'handle', 'orden', 'tab', 'perfilId', 'titulo'
        );

        $this->render('perfil-usuario/index', $data);
    }

    private function render(string $vista, array $data = []): void
    {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
