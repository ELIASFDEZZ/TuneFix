<?php

require_once __DIR__ . '/../config/Database.php';

class CarritoModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** Devuelve los ítems del carrito de un usuario con datos de la pieza */
    public function getItems(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.pieza_id, c.cantidad, c.precio_u,
                   p.nombre, p.referencia, p.imagen, p.stock,
                   p.categoria, p.estado_pieza, p.garantia
            FROM carrito c
            JOIN pieza p ON p.id = c.pieza_id
            WHERE c.usuario_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /** Número total de ítems (para el badge del navbar) */
    public function countItems(int $usuarioId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(cantidad), 0) FROM carrito WHERE usuario_id = ?"
        );
        $stmt->execute([$usuarioId]);
        return (int) $stmt->fetchColumn();
    }

    /** Añade o incrementa una pieza en el carrito */
    public function addOrIncrement(int $usuarioId, int $piezaId, float $precioU, int $cantidad = 1): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO carrito (usuario_id, pieza_id, cantidad, precio_u)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
        ");
        $stmt->execute([$usuarioId, $piezaId, $cantidad, $precioU]);
    }

    /** Actualiza la cantidad de un ítem (0 = eliminar) */
    public function updateCantidad(int $carritoId, int $usuarioId, int $cantidad): void
    {
        if ($cantidad <= 0) {
            $this->remove($carritoId, $usuarioId);
            return;
        }
        $stmt = $this->pdo->prepare(
            "UPDATE carrito SET cantidad = ? WHERE id = ? AND usuario_id = ?"
        );
        $stmt->execute([$cantidad, $carritoId, $usuarioId]);
    }

    /** Elimina un ítem del carrito */
    public function remove(int $carritoId, int $usuarioId): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM carrito WHERE id = ? AND usuario_id = ?"
        );
        $stmt->execute([$carritoId, $usuarioId]);
    }

    /** Vacía el carrito completo de un usuario */
    public function clear(int $usuarioId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM carrito WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
    }

    /** Total del carrito */
    public function getTotal(int $usuarioId): float
    {
        $stmt = $this->pdo->prepare(
            "SELECT COALESCE(SUM(cantidad * precio_u), 0) FROM carrito WHERE usuario_id = ?"
        );
        $stmt->execute([$usuarioId]);
        return (float) $stmt->fetchColumn();
    }
}
