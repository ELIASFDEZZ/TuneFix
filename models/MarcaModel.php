<?php

require_once __DIR__ . '/../config/Database.php';

/**
 * Modelo que maneja todas las operaciones relacionadas con la tabla "marca"
 */
class MarcaModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Obtiene todas las marcas ordenadas alfabéticamente
     * @return array Lista de marcas (id + nombre)
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, nombre FROM marca ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
