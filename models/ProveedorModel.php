<?php

require_once __DIR__ . '/../config/Database.php';

class ProveedorModel
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // ── Registro / Solicitud ──────────────────────────────────────────────────

    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM proveedores WHERE email = ?");
        $stmt->execute([$email]);
        return (bool)$stmt->fetch();
    }

    public function cifExiste(string $cif): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM proveedores WHERE cif = ?");
        $stmt->execute([$cif]);
        return (bool)$stmt->fetch();
    }

    public function crear(array $datos): int
    {
        $hash = password_hash($datos['password'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("
            INSERT INTO proveedores
              (nombre_empresa, cif, direccion, provincia, telefono, sitio_web,
               nombre_responsable, email, password, descripcion, doc_cif, doc_iae)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $datos['nombre_empresa'],
            $datos['cif'],
            $datos['direccion'],
            $datos['provincia'] ?? null,
            $datos['telefono']  ?? null,
            $datos['sitio_web'] ?? null,
            $datos['nombre_responsable'] ?? null,
            $datos['email'],
            $hash,
            $datos['descripcion'] ?? null,
            $datos['doc_cif'] ?? null,
            $datos['doc_iae'] ?? null,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // ── Autenticación ─────────────────────────────────────────────────────────

    public function login(string $email, string $password): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM proveedores WHERE email = ?");
        $stmt->execute([$email]);
        $proveedor = $stmt->fetch();
        if (!$proveedor) return false;
        if (!password_verify($password, $proveedor['password'])) return false;
        return $proveedor;
    }

    // ── Búsqueda ──────────────────────────────────────────────────────────────

    public function getPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM proveedores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getTodos(string $estado = ''): array
    {
        if ($estado && in_array($estado, ['pendiente','aceptado','rechazado'], true)) {
            $stmt = $this->pdo->prepare("SELECT * FROM proveedores WHERE estado = ? ORDER BY created_at DESC");
            $stmt->execute([$estado]);
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM proveedores ORDER BY created_at DESC");
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function contarPorEstado(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT estado, COUNT(*) AS total FROM proveedores GROUP BY estado
        ");
        $stmt->execute();
        $raw = $stmt->fetchAll();
        $result = ['pendiente' => 0, 'aceptado' => 0, 'rechazado' => 0];
        foreach ($raw as $row) $result[$row['estado']] = (int)$row['total'];
        return $result;
    }

    // ── Cambio de estado (admin) ──────────────────────────────────────────────

    public function aceptar(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE proveedores SET estado='aceptado', motivo_rechazo=NULL WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function rechazar(int $id, string $motivo): bool
    {
        $stmt = $this->pdo->prepare("UPDATE proveedores SET estado='rechazado', motivo_rechazo=? WHERE id=?");
        return $stmt->execute([$motivo, $id]);
    }

    public function actualizarPerfil(int $id, array $datos): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE proveedores
               SET nombre_empresa=?, provincia=?, telefono=?, sitio_web=?, descripcion=?
             WHERE id=?
        ");
        return $stmt->execute([
            $datos['nombre_empresa'],
            $datos['provincia'],
            $datos['telefono'],
            $datos['sitio_web'] ?? null,
            $datos['descripcion'],
            $id,
        ]);
    }

    // ── Piezas ────────────────────────────────────────────────────────────────

    public function getPiezas(int $proveedorId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.*, p.referencia AS referencia_oem, 
                    (SELECT COUNT(*) FROM compatibilidad_pieza cp WHERE cp.pieza_id = p.id) AS num_vehiculos
               FROM pieza p
              WHERE p.proveedor_id = ?
              ORDER BY p.id DESC"
        );
        $stmt->execute([$proveedorId]);
        return $stmt->fetchAll();
    }

    public function getPiezaPorId(int $piezaId, int $proveedorId): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pieza WHERE id = ? AND proveedor_id = ?");
        $stmt->execute([$piezaId, $proveedorId]);
        return $stmt->fetch();
    }

    public function getFotosPieza(int $piezaId): array
    {
        $stmt = $this->pdo->prepare("SELECT imagen AS ruta, 0 AS orden FROM pieza WHERE id = ?");
        $stmt->execute([$piezaId]);
        return $stmt->fetchAll();
    }

    public function getVehiculosPieza(int $piezaId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM compatibilidad_pieza WHERE pieza_id = ? ORDER BY id");
        $stmt->execute([$piezaId]);
        return $stmt->fetchAll();
    }

    public function crearPieza(array $datos, int $proveedorId): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pieza
               (referencia, nombre, descripcion, imagen, proveedor_id, categoria, estado_pieza, precio, stock, garantia, activa)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->execute([
            $datos['referencia_oem'] ?? '',
            $datos['nombre'],
            $datos['descripcion'],
            $datos['imagen'] ?? '',
            $proveedorId,
            $datos['categoria'],
            $datos['estado_pieza'],
            $datos['precio'],
            (int)$datos['stock'],
            $datos['garantia'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function addFotoPieza(int $piezaId, string $ruta, int $orden): void
    {
        if ($orden !== 0) {
            return;
        }
        $stmt = $this->pdo->prepare("UPDATE pieza SET imagen = ? WHERE id = ?");
        $stmt->execute([$ruta, $piezaId]);
    }

    public function addVehiculoPieza(int $piezaId, int $motorizacionId): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO compatibilidad_pieza (pieza_id, motorizacion_id) VALUES (?,?)");
        $stmt->execute([$piezaId, $motorizacionId]);
    }

    public function toggleActiva(int $piezaId, int $proveedorId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE pieza SET activa = NOT activa WHERE id = ? AND proveedor_id = ?");
        return $stmt->execute([$piezaId, $proveedorId]);
    }

    // ── Estadísticas ──────────────────────────────────────────────────────────

    public function getEstadisticas(int $proveedorId): array
    {
        $total = $this->pdo->prepare("SELECT COUNT(*) FROM pieza WHERE proveedor_id = ?");
        $total->execute([$proveedorId]);

        $activas = $this->pdo->prepare("SELECT COUNT(*) FROM pieza WHERE proveedor_id = ? AND activa = 1");
        $activas->execute([$proveedorId]);

        $sinStock = $this->pdo->prepare("SELECT COUNT(*) FROM pieza WHERE proveedor_id = ? AND stock = 0");
        $sinStock->execute([$proveedorId]);

        return [
            'total'    => (int)$total->fetchColumn(),
            'activas'  => (int)$activas->fetchColumn(),
            'sin_stock'=> (int)$sinStock->fetchColumn(),
        ];
    }
}
