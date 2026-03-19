<?php

require_once __DIR__ . '/../config/Database.php';

class PiezaModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Devuelve las últimas N piezas (para mostrar sin filtro).
     */
    public function getRecientes(int $limite = 6): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, referencia, nombre, descripcion, imagen
             FROM pieza
             ORDER BY id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve las piezas compatibles con una motorización concreta.
     */
    public function getByMotorizacion(int $motorizacionId, int $limite = 3): array {
        $stmt = $this->pdo->prepare(
            "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen
             FROM pieza p
             JOIN compatibilidad_pieza cp ON cp.pieza_id = p.id
             WHERE cp.motorizacion_id = ?
             ORDER BY p.id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $motorizacionId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByModelo(int $modeloId, int $limite = 3): array {
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT p.id, p.referencia, p.nombre, p.descripcion, p.imagen
             FROM pieza p
             JOIN compatibilidad_pieza cp ON cp.pieza_id = p.id
             JOIN motorizacion m ON m.id = cp.motorizacion_id
             WHERE m.modelo_id = ?
             ORDER BY p.id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $modeloId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
