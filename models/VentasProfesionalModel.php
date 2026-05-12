<?php

require_once __DIR__ . '/../config/Database.php';

class VentasProfesionalModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** Resumen: piezas subidas, pedidos recibidos y total facturado */
    public function getResumen(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
               (SELECT COUNT(*) FROM pieza WHERE subido_por = :uid)              AS total_piezas,
               (SELECT COUNT(DISTINCT p.id)
                FROM pedido p
                JOIN pedido_item pi ON pi.pedido_id = p.id
                JOIN pieza pz       ON pz.id = pi.pieza_id
                WHERE pz.subido_por = :uid AND p.estado = 'pagado')              AS total_pedidos,
               (SELECT COALESCE(SUM(pi.cantidad * pi.precio_u), 0)
                FROM pedido_item pi
                JOIN pieza pz ON pz.id = pi.pieza_id
                JOIN pedido p  ON p.id = pi.pedido_id
                WHERE pz.subido_por = :uid AND p.estado = 'pagado')              AS total_facturado,
               (SELECT COUNT(DISTINCT p.id)
                FROM pedido p
                JOIN pedido_item pi ON pi.pedido_id = p.id
                JOIN pieza pz       ON pz.id = pi.pieza_id
                WHERE pz.subido_por = :uid AND p.estado = 'pendiente')           AS pedidos_pendientes"
        );
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Líneas de pedido que contienen piezas del profesional.
     * Se devuelven ordenadas por fecha desc; en PHP se agrupan por pedido_id.
     */
    public function getLineasPedidos(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
               p.id           AS pedido_id,
               p.created_at   AS fecha,
               p.estado,
               u.nombre       AS comprador,
               pz.id          AS pieza_id,
               pz.nombre      AS pieza_nombre,
               pz.referencia  AS pieza_ref,
               pz.imagen      AS pieza_imagen,
               pi.cantidad,
               pi.precio_u,
               (pi.cantidad * pi.precio_u) AS subtotal
             FROM pieza pz
             JOIN pedido_item pi ON pi.pieza_id  = pz.id
             JOIN pedido p       ON p.id         = pi.pedido_id
             JOIN usuario u      ON u.id         = p.usuario_id
             WHERE pz.subido_por = :uid
             ORDER BY p.created_at DESC, p.id DESC"
        );
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Piezas subidas por el profesional con estadísticas de ventas */
    public function getMisPiezas(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT
               pz.id, pz.referencia, pz.nombre, pz.imagen,
               pz.precio, pz.stock, pz.activa, pz.created_at,
               COUNT(pi.id)                                  AS veces_en_pedido,
               COALESCE(SUM(pi.cantidad), 0)                 AS unidades_vendidas,
               COALESCE(SUM(pi.cantidad * pi.precio_u), 0)   AS ingresos_generados
             FROM pieza pz
             LEFT JOIN pedido_item pi ON pi.pieza_id = pz.id
             LEFT JOIN pedido p       ON p.id = pi.pedido_id AND p.estado = 'pagado'
             WHERE pz.subido_por = :uid
             GROUP BY pz.id, pz.referencia, pz.nombre, pz.imagen,
                      pz.precio, pz.stock, pz.activa, pz.created_at
             ORDER BY ingresos_generados DESC, pz.created_at DESC"
        );
        $stmt->execute([':uid' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
