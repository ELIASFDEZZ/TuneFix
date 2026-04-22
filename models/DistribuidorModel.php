<?php

require_once __DIR__ . '/../config/Database.php';

class DistribuidorModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Devuelve los distribuidores que tienen piezas compatibles
     * con una motorización concreta, incluyendo los enlaces directos.
     */
    public function getByMotorizacion(int $motorizacionId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT d.id AS distribuidor_id,
                    d.nombre AS distribuidor_nombre,
                    d.url_base,
                    dp.url_directa,
                    dp.nombre  AS nombre_pieza_dist,
                    p.id       AS pieza_id,
                    p.nombre   AS pieza_nombre,
                    p.referencia
             FROM distribuidor_pieza dp
             JOIN distribuidor d ON d.id = dp.distribuidor_id
             JOIN pieza p        ON p.id = dp.pieza_id
             JOIN compatibilidad_pieza cp ON cp.pieza_id = p.id
             WHERE cp.motorizacion_id = ?
             ORDER BY d.nombre ASC, p.nombre ASC"
        );
        $stmt->execute([$motorizacionId]);
        return $stmt->fetchAll();
    }

    /** Todos los distribuidores (para mostrar en panel, etc.) */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nombre, url_base FROM distribuidor ORDER BY nombre ASC"
        );
        return $stmt->fetchAll();
    }
}