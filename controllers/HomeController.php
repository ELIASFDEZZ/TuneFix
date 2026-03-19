<?php

class HomeController {

    /**
     * Muestra la página de inicio con la selección de tipo de usuario.
     */
    public function index(): void {
        $data = [
            'titulo' => 'Tipo de Usuario - TuneFix',
        ];
        $this->render('home/index', $data);
    }

    // -------------------------------------------------------------------------
    // Helpers de renderizado
    // -------------------------------------------------------------------------

    private function render(string $vista, array $data = []): void {
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/' . $vista . '.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
