<?php

require_once __DIR__ . '/../config/Database.php';

class MeGustaPiezaModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function toggle(int $usuarioId, int $piezaId): bool
    {
        if ($this->isLiked($usuarioId, $piezaId)) {
            $this->pdo->prepare("DELETE FROM megusta_pieza WHERE usuario_id=? AND pieza_id=?")
                ->execute([$usuarioId, $piezaId]);
            return false;
        }
        $this->pdo->prepare("INSERT INTO megusta_pieza (usuario_id, pieza_id) VALUES (?,?)")
            ->execute([$usuarioId, $piezaId]);
        return true;
    }

    public function isLiked(int $usuarioId, int $piezaId): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM megusta_pieza WHERE usuario_id=? AND pieza_id=? LIMIT 1");
        $stmt->execute([$usuarioId, $piezaId]);
        return (bool) $stmt->fetchColumn();
    }

    public function getCount(int $piezaId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM megusta_pieza WHERE pieza_id=?");
        $stmt->execute([$piezaId]);
        return (int) $stmt->fetchColumn();
    }

    public function getPiezasGuardadas(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.id, p.nombre, p.referencia, p.imagen, p.descripcion, mg.fecha
             FROM megusta_pieza mg
             JOIN pieza p ON p.id = mg.pieza_id
             WHERE mg.usuario_id = ?
             ORDER BY mg.fecha DESC"
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
}
