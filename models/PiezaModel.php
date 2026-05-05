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
             WHERE activa = 1
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
               AND p.activa = 1
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
               AND p.activa = 1
             ORDER BY p.id DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $modeloId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve TODAS las piezas compatibles con una motorización, sin límite.
     * Admite búsqueda opcional por nombre o referencia dentro de esas piezas.
     */
    public function getAllByMotorizacion(int $motorizacionId, string $busqueda = ''): array {
        if ($busqueda !== '') {
            $stmt = $this->pdo->prepare(
                "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen
                 FROM pieza p
                 JOIN compatibilidad_pieza cp ON cp.pieza_id = p.id
                 WHERE cp.motorizacion_id = ?
                   AND p.activa = 1
                   AND (p.nombre LIKE ? OR p.referencia LIKE ?)
                 ORDER BY p.nombre ASC"
            );
            $like = '%' . $busqueda . '%';
            $stmt->bindValue(1, $motorizacionId, PDO::PARAM_INT);
            $stmt->bindValue(2, $like);
            $stmt->bindValue(3, $like);
        } else {
            $stmt = $this->pdo->prepare(
                "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen
                 FROM pieza p
                 JOIN compatibilidad_pieza cp ON cp.pieza_id = p.id
                 WHERE cp.motorizacion_id = ?
                   AND p.activa = 1
                 ORDER BY p.nombre ASC"
            );
            $stmt->bindValue(1, $motorizacionId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Devuelve una pieza por su ID.
     */
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT id, referencia, nombre, descripcion, imagen
             FROM pieza
             WHERE id = ?
               AND activa = 1"
        );
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(array $datos): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pieza (referencia, nombre, descripcion, imagen)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['referencia'] ?? '',
            $datos['nombre'],
            $datos['descripcion'] ?? null,
            $datos['imagen'] ?? '',
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateImagen(int $id, string $imagen): void {
        $stmt = $this->pdo->prepare("UPDATE pieza SET imagen = ? WHERE id = ?");
        $stmt->execute([$imagen, $id]);
    }

    /**
     * Devuelve todas las piezas, con búsqueda opcional por nombre/referencia.
     */
    public function getAll(string $busqueda = ''): array {
        if ($busqueda !== '') {
            $stmt = $this->pdo->prepare(
                "SELECT id, referencia, nombre, descripcion, imagen
                 FROM pieza
                 WHERE activa = 1
                   AND (nombre LIKE ? OR referencia LIKE ?)
                 ORDER BY nombre ASC"
            );
            $like = '%' . $busqueda . '%';
            $stmt->bindValue(1, $like);
            $stmt->bindValue(2, $like);
        } else {
            $stmt = $this->pdo->prepare(
                "SELECT id, referencia, nombre, descripcion, imagen
                 FROM pieza
                 WHERE activa = 1
                 ORDER BY nombre ASC"
            );
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
