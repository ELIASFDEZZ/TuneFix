<?php

class FavoritosController
{
    public function index(): void
    {
        $data = ['titulo' => 'Mis Favoritos - TuneFix'];
        extract($data);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/favoritos/index.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}
