<?php

require_once __DIR__ . '/../config/Database.php';

class UsuarioModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** Busca un usuario por email y verifica la contraseña */
    public function login(string $email, string $password): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, email, contrasenia, rol FROM usuario WHERE email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['contrasenia'])) {
            unset($usuario['contrasenia']);
            return $usuario;
        }
        return false;
    }

    /** Comprueba si el email ya existe */
    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM usuario WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    /** Registra un nuevo usuario */
    public function registrar(string $nombre, string $email, string $password): array
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (nombre, email, contrasenia, rol) VALUES (?, ?, ?, 'usuario')"
        );
        $stmt->execute([$nombre, $email, $hash]);

        return [
            'id'     => (int) $this->pdo->lastInsertId(),
            'nombre' => $nombre,
            'email'  => $email,
            'rol'    => 'usuario',
        ];
    }

    /** Actualiza el nombre de un usuario */
    public function actualizarNombre(int $id, string $nombre): bool
    {
        $stmt = $this->pdo->prepare("UPDATE usuario SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }

    /**
     * Cambia la contraseña verificando primero la actual.
     * Devuelve 'ok', 'wrong_current' o 'error'.
     */
    public function cambiarContrasenia(int $id, string $actual, string $nueva): string
    {
        $stmt = $this->pdo->prepare("SELECT contrasenia FROM usuario WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($actual, $row['contrasenia'])) {
            return 'wrong_current';
        }

        $hash = password_hash($nueva, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE usuario SET contrasenia = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]) ? 'ok' : 'error';
    }

    /** Devuelve los coches (motorizaciones) guardados por el usuario con info completa */
    public function getCoches(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT um.id AS rel_id,
                    mo.id AS motorizacion_id, mo.nombre AS motor_nombre,
                    mo.tipo_combustible, mo.potencia,
                    md.id AS modelo_id, md.nombre AS modelo_nombre,
                    md.anio_inicio, md.anio_fin,
                    ma.id AS marca_id, ma.nombre AS marca_nombre
             FROM usuario_motorizacion um
             JOIN motorizacion mo ON mo.id = um.motorizacion_id
             JOIN modelo md ON md.id = mo.modelo_id
             JOIN marca ma ON ma.id = md.marca_id
             WHERE um.usuario_id = ?
             ORDER BY ma.nombre, md.nombre, mo.nombre"
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /** Añade un coche al perfil del usuario (evita duplicados) */
    public function addCoche(int $usuarioId, int $motorizacionId): bool
    {
        // Evitar duplicado
        $check = $this->pdo->prepare(
            "SELECT id FROM usuario_motorizacion WHERE usuario_id = ? AND motorizacion_id = ? LIMIT 1"
        );
        $check->execute([$usuarioId, $motorizacionId]);
        if ($check->fetch()) return false; // ya existe

        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario_motorizacion (usuario_id, motorizacion_id) VALUES (?, ?)"
        );
        return $stmt->execute([$usuarioId, $motorizacionId]);
    }

    /** Elimina un coche del perfil del usuario */
    public function removeCoche(int $relId, int $usuarioId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM usuario_motorizacion WHERE id = ? AND usuario_id = ?"
        );
        return $stmt->execute([$relId, $usuarioId]);
    }
}
