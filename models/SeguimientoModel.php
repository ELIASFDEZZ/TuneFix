<?php

require_once __DIR__ . '/../config/Database.php';

class SeguimientoModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function seguir(int $seguidorId, int $profesionalId): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT IGNORE INTO seguimiento (seguidor_id, profesional_id) VALUES (?, ?)"
            );
            return $stmt->execute([$seguidorId, $profesionalId]);
        } catch (PDOException $e) {
            error_log('SeguimientoModel::seguir — ' . $e->getMessage());
            return false;
        }
    }

    public function dejarDeSeguir(int $seguidorId, int $profesionalId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM seguimiento WHERE seguidor_id = ? AND profesional_id = ?"
        );
        return $stmt->execute([$seguidorId, $profesionalId]);
    }

    public function estaSiguiendo(int $seguidorId, int $profesionalId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM seguimiento WHERE seguidor_id = ? AND profesional_id = ? LIMIT 1"
        );
        $stmt->execute([$seguidorId, $profesionalId]);
        return (bool) $stmt->fetchColumn();
    }

    /** @return array<array{id:int, nombre:string, email:string}> */
    public function getSeguidores(int $profesionalId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT u.id, u.nombre, u.email
             FROM seguimiento s
             JOIN usuario u ON u.id = s.seguidor_id
             WHERE s.profesional_id = ?
             ORDER BY s.fecha_seguimiento DESC"
        );
        $stmt->execute([$profesionalId]);
        return $stmt->fetchAll();
    }

    /** @return array<array{id:int, nombre:string, email:string, fecha_seguimiento:string}> */
    public function getProfesionalesSeguidos(int $seguidorId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT u.id, u.nombre, u.email, s.fecha_seguimiento
             FROM seguimiento s
             JOIN usuario u ON u.id = s.profesional_id
             WHERE s.seguidor_id = ?
             ORDER BY s.fecha_seguimiento DESC"
        );
        $stmt->execute([$seguidorId]);
        return $stmt->fetchAll();
    }

    public function contarSeguidores(int $profesionalId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM seguimiento WHERE profesional_id = ?"
        );
        $stmt->execute([$profesionalId]);
        return (int) $stmt->fetchColumn();
    }
}
