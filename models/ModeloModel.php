<?php

require_once __DIR__ . '/../config/Database.php';

class ModeloModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Devuelve los modelos de una marca concreta.
     */
    public function getByMarca(int $marcaId): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre FROM modelo WHERE marca_id = ? ORDER BY nombre"
        );
        $stmt->execute([$marcaId]);
        return $stmt->fetchAll();
    }
}
