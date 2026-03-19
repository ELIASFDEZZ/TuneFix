<?php

require_once __DIR__ . '/../config/Database.php';

class MotorizacionModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Devuelve las motorizaciones de un modelo concreto.
     */
    public function getByModelo(int $modeloId): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, potencia, tipo_combustible
             FROM motorizacion
             WHERE modelo_id = ?
             ORDER BY nombre"
        );
        $stmt->execute([$modeloId]);
        return $stmt->fetchAll();
    }
}
