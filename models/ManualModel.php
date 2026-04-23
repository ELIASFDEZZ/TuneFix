<?php

require_once __DIR__ . '/../config/Database.php';

class ManualModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Devuelve los manuales asociados a una motorización concreta,
     * incluyendo el nombre de la pieza relacionada si existe.
     */
    public function getByMotorizacion(int $motorizacionId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.id, m.titulo, m.fuente, m.archivo_url,
                    p.nombre AS pieza_nombre
             FROM manual m
             LEFT JOIN pieza p ON p.id = m.pieza_id
             WHERE m.motorizacion_id = ?
             ORDER BY m.titulo ASC"
        );
        $stmt->execute([$motorizacionId]);
        return $stmt->fetchAll();
    }

    /** Todos los manuales disponibles */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT m.id, m.titulo, m.fuente, m.archivo_url,
                    p.nombre AS pieza_nombre
             FROM manual m
             LEFT JOIN pieza p ON p.id = m.pieza_id
             ORDER BY m.titulo ASC"
        );
        return $stmt->fetchAll();
    }
}