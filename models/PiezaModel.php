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
            "SELECT id, referencia, nombre, descripcion, imagen, url,
                    precio, stock, garantia, estado_pieza, categoria
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
            "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen, p.url,
                    p.precio, p.stock, p.garantia, p.estado_pieza, p.categoria
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
            "SELECT DISTINCT p.id, p.referencia, p.nombre, p.descripcion, p.imagen, p.url,
                    p.precio, p.stock, p.garantia, p.estado_pieza, p.categoria
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
                "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen, p.url,
                        p.precio, p.stock, p.garantia, p.estado_pieza, p.categoria
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
                "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen, p.url,
                        p.precio, p.stock, p.garantia, p.estado_pieza, p.categoria
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
            "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen, p.url,
                    p.precio, p.stock, p.garantia, p.estado_pieza, p.categoria,
                    p.subido_por,     u.nombre           AS subido_por_nombre,
                    p.proveedor_id,   pr.nombre_empresa  AS proveedor_nombre
             FROM pieza p
             LEFT JOIN usuario    u  ON u.id  = p.subido_por
             LEFT JOIN proveedores pr ON pr.id = p.proveedor_id
             WHERE p.id = ? AND p.activa = 1"
        );
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function crear(array $datos): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pieza (referencia, nombre, descripcion, imagen, url, categoria, estado_pieza, precio, stock, garantia, subido_por)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $datos['referencia']   ?? '',
            $datos['nombre'],
            $datos['descripcion']  ?? null,
            $datos['imagen']       ?? '',
            $datos['url']          ?? null,
            $datos['categoria']    ?? null,
            $datos['estado_pieza'] ?? 'nueva',
            $datos['precio']       ?? 0.00,
            $datos['stock']        ?? 0,
            $datos['garantia']     ?? 'Sin garantía',
            $datos['subido_por']   ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function getByProfesional(int $userId): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, referencia, nombre, descripcion, imagen, url,
                    precio, stock, garantia, estado_pieza, categoria, created_at
             FROM pieza
             WHERE subido_por = ? AND activa = 1
             ORDER BY id DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** Devuelve todos los campos de una pieza (incluso inactiva) para el formulario de edición */
    public function getByIdFull(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT p.id, p.referencia, p.nombre, p.descripcion, p.imagen, p.url,
                    p.precio, p.stock, p.garantia, p.estado_pieza, p.categoria,
                    p.activa, p.subido_por
             FROM pieza p
             WHERE p.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: false;
    }

    public function actualizar(int $id, array $datos): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE pieza SET referencia=?, nombre=?, descripcion=?, imagen=?, url=?,
                    categoria=?, estado_pieza=?, precio=?, stock=?, garantia=?
             WHERE id = ? AND subido_por = ?"
        );
        return $stmt->execute([
            $datos['referencia']   ?? '',
            $datos['nombre'],
            $datos['descripcion']  ?? null,
            $datos['imagen']       ?? '',
            $datos['url']          ?? null,
            $datos['categoria']    ?? null,
            $datos['estado_pieza'] ?? 'nueva',
            $datos['precio']       ?? 0.00,
            $datos['stock']        ?? 0,
            $datos['garantia']     ?? 'Sin garantía',
            $id,
            $datos['subido_por'],
        ]);
    }

    public function eliminar(int $id, int $subidoPor): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE pieza SET activa = 0 WHERE id = ? AND subido_por = ?"
        );
        return $stmt->execute([$id, $subidoPor]);
    }

    public function referenciaExiste(string $referencia): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM pieza WHERE referencia = ? LIMIT 1");
        $stmt->execute([$referencia]);
        return $stmt->fetch() !== false;
    }

    public function agregarCompatibilidad(int $piezaId, int $motorizacionId): void {
        $stmt = $this->pdo->prepare(
            "INSERT IGNORE INTO compatibilidad_pieza (pieza_id, motorizacion_id) VALUES (?, ?)"
        );
        $stmt->execute([$piezaId, $motorizacionId]);
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
                "SELECT id, referencia, nombre, descripcion, imagen, url,
                        precio, stock, garantia, estado_pieza, categoria
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
                "SELECT id, referencia, nombre, descripcion, imagen, url,
                        precio, stock, garantia, estado_pieza, categoria
                 FROM pieza
                 WHERE activa = 1
                 ORDER BY nombre ASC"
            );
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
