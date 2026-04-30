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
            "SELECT t.id, t.titulo, t.pieza_id, p.nombre AS pieza_nombre, t.imagen, t.youtube_id
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
     * Tutoriales para una motorización: primero los específicos, luego genéricos (sin vehículo).
     */
    public function getByMotorizacion(int $motorizacionId, int $limite = 3): array {
        $stmt = $this->pdo->prepare(
            "SELECT t.id, t.titulo, t.youtube_id, t.imagen, p.nombre AS pieza_nombre,
                    IF(t.motorizacion_id = ?, 0, 1) AS es_generico
             FROM tutorial t
             LEFT JOIN pieza p ON t.pieza_id = p.id
             WHERE t.motorizacion_id = ? OR t.motorizacion_id IS NULL
             ORDER BY es_generico ASC, t.id DESC
             LIMIT {$limite}"
        );
        $stmt->bindValue(1, $motorizacionId, PDO::PARAM_INT);
        $stmt->bindValue(2, $motorizacionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByModelo(int $modeloId, int $limite = 3): array {
        $stmt = $this->pdo->prepare(
            "SELECT t.id, t.titulo, t.youtube_id, t.imagen, p.nombre AS pieza_nombre,
                    IF(t.motorizacion_id IS NULL, 1, 0) AS es_generico
             FROM tutorial t
             LEFT JOIN pieza p ON t.pieza_id = p.id
             LEFT JOIN motorizacion m ON t.motorizacion_id = m.id
             WHERE m.modelo_id = ? OR t.motorizacion_id IS NULL
             ORDER BY es_generico ASC, t.id DESC
             LIMIT {$limite}"
        );
        $stmt->bindValue(1, $modeloId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Inserta un nuevo tutorial y devuelve su ID.
     */
    public function crear(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tutorial (usuario_id, titulo, youtube_id, imagen, pieza_id, motorizacion_id)
             VALUES (:usuario_id, :titulo, :youtube_id, :imagen, :pieza_id, :motorizacion_id)"
        );
        $stmt->execute([
            ':usuario_id'      => $data['usuario_id']      ?? null,
            ':titulo'          => $data['titulo'],
            ':youtube_id'      => $data['youtube_id'],
            ':imagen'          => $data['imagen']          ?? null,
            ':pieza_id'        => $data['pieza_id']        ?? null,
            ':motorizacion_id' => $data['motorizacion_id'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Devuelve todos los tutoriales, con búsqueda opcional por título.
     */
    public function getAll(string $busqueda = ''): array {
        if ($busqueda !== '') {
            $stmt = $this->pdo->prepare(
                "SELECT t.id, t.titulo, t.imagen, t.youtube_id, p.nombre AS pieza_nombre,
                        t.usuario_id, u.nombre AS nombre_usuario
                 FROM tutorial t
                 LEFT JOIN pieza p ON t.pieza_id = p.id
                 LEFT JOIN usuario u ON t.usuario_id = u.id
                 WHERE t.titulo LIKE ? OR p.nombre LIKE ?
                 ORDER BY t.titulo ASC"
            );
            $like = '%' . $busqueda . '%';
            $stmt->bindValue(1, $like);
            $stmt->bindValue(2, $like);
        } else {
            $stmt = $this->pdo->prepare(
                "SELECT t.id, t.titulo, t.imagen, t.youtube_id, p.nombre AS pieza_nombre,
                        t.usuario_id, u.nombre AS nombre_usuario
                 FROM tutorial t
                 LEFT JOIN pieza p ON t.pieza_id = p.id
                 LEFT JOIN usuario u ON t.usuario_id = u.id
                 ORDER BY t.titulo ASC"
            );
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
