<?php

require_once __DIR__ . '/../config/Database.php';

class TutorialModel {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Devuelve los últimos N tutoriales (para mostrar sin filtro).
     */
    public function getRecientes(int $limite = 4): array {
        $stmt = $this->pdo->prepare(
            "SELECT t.id, t.titulo, t.pieza_id, p.nombre AS pieza_nombre, t.imagen
             FROM tutorial t
             LEFT JOIN pieza p ON t.pieza_id = p.id
             ORDER BY t.id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve los tutoriales de una motorización concreta.
     */
    public function getByMotorizacion(int $motorizacionId, int $limite = 3): array {
        $stmt = $this->pdo->prepare(
            "SELECT t.id, t.titulo, t.imagen, p.nombre AS pieza_nombre
             FROM tutorial t
             LEFT JOIN pieza p ON t.pieza_id = p.id
             WHERE t.motorizacion_id = ?
             ORDER BY t.id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $motorizacionId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByModelo(int $modeloId, int $limite = 3): array {
        $stmt = $this->pdo->prepare(
            "SELECT t.id, t.titulo, t.imagen, p.nombre AS pieza_nombre
             FROM tutorial t
             LEFT JOIN pieza p ON t.pieza_id = p.id
             JOIN motorizacion m ON t.motorizacion_id = m.id
             WHERE m.modelo_id = ?
             ORDER BY t.id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $modeloId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
