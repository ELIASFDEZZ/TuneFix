<?php

require_once __DIR__ . '/../config/Database.php';

class ManualModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** Manuales de una motorización concreta */
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

    /** Todos los manuales con info de vehículo y búsqueda opcional */
    public function getAll(string $busqueda = ''): array
    {
        $sql = "SELECT m.id, m.titulo, m.fuente, m.archivo_url,
                       p.nombre  AS pieza_nombre,
                       mk.nombre AS marca_nombre,
                       mo.nombre AS modelo_nombre,
                       mt.nombre AS motor_nombre
                FROM manual m
                LEFT JOIN pieza       p  ON p.id  = m.pieza_id
                LEFT JOIN motorizacion mt ON mt.id = m.motorizacion_id
                LEFT JOIN modelo      mo ON mo.id  = mt.modelo_id
                LEFT JOIN marca       mk ON mk.id  = mo.marca_id";

        if ($busqueda !== '') {
            $sql .= " WHERE m.titulo LIKE :q OR m.fuente LIKE :q OR mk.nombre LIKE :q OR mo.nombre LIKE :q";
            $sql .= " ORDER BY m.titulo ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':q' => '%' . $busqueda . '%']);
        } else {
            $sql .= " ORDER BY m.titulo ASC";
            $stmt = $this->pdo->query($sql);
        }

        return $stmt->fetchAll();
    }

    /** Manuales filtrados por motorización con info de vehículo */
    public function getAllByMotorizacion(int $motorizacionId, string $busqueda = ''): array
    {
        $sql = "SELECT m.id, m.titulo, m.fuente, m.archivo_url,
                       p.nombre  AS pieza_nombre,
                       mk.nombre AS marca_nombre,
                       mo.nombre AS modelo_nombre,
                       mt.nombre AS motor_nombre
                FROM manual m
                LEFT JOIN pieza       p  ON p.id  = m.pieza_id
                LEFT JOIN motorizacion mt ON mt.id = m.motorizacion_id
                LEFT JOIN modelo      mo ON mo.id  = mt.modelo_id
                LEFT JOIN marca       mk ON mk.id  = mo.marca_id
                WHERE m.motorizacion_id = :motId";

        if ($busqueda !== '') {
            $sql .= " AND (m.titulo LIKE :q OR m.fuente LIKE :q)";
            $sql .= " ORDER BY m.titulo ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':motId' => $motorizacionId, ':q' => '%' . $busqueda . '%']);
        } else {
            $sql .= " ORDER BY m.titulo ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':motId' => $motorizacionId]);
        }

        return $stmt->fetchAll();
    }

    /** Un manual por id */
    public function getById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, p.nombre AS pieza_nombre
             FROM manual m
             LEFT JOIN pieza p ON p.id = m.pieza_id
             WHERE m.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /** Crea un manual y devuelve el id insertado */
    public function crear(
        string $titulo,
        ?string $fuente,
        ?string $archivoUrl,
        ?int $motorizacionId,
        ?int $piezaId
    ): int {
        $this->pdo->prepare(
            "INSERT INTO manual (titulo, fuente, archivo_url, motorizacion_id, pieza_id)
             VALUES (?, ?, ?, ?, ?)"
        )->execute([$titulo, $fuente, $archivoUrl, $motorizacionId, $piezaId]);
        return (int) $this->pdo->lastInsertId();
    }

    /** Actualiza un manual existente */
    public function actualizar(
        int $id,
        string $titulo,
        ?string $fuente,
        ?string $archivoUrl,
        ?int $motorizacionId,
        ?int $piezaId
    ): void {
        $this->pdo->prepare(
            "UPDATE manual
             SET titulo=?, fuente=?, archivo_url=?, motorizacion_id=?, pieza_id=?
             WHERE id=?"
        )->execute([$titulo, $fuente, $archivoUrl, $motorizacionId, $piezaId, $id]);
    }

    /** Elimina un manual y devuelve la ruta del PDF para borrarlo del disco */
    public function eliminar(int $id): ?string
    {
        $stmt = $this->pdo->prepare("SELECT archivo_url FROM manual WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $this->pdo->prepare("DELETE FROM manual WHERE id=?")->execute([$id]);
        return $row['archivo_url'] ?? null;
    }
}