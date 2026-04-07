<?php

require_once __DIR__ . '/../config/Database.php';

class UsuarioModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Busca un usuario por email y verifica la contraseña cifrada con bcrypt
     * @return array|false Datos del usuario o false si no coincide
     */
    public function login(string $email, string $password): array|false
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, email, contrasenia, rol FROM usuario WHERE email = ? LIMIT 1"
        );
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['contrasenia'])) {
            unset($usuario['contrasenia']); // no guardamos el hash en sesión
            return $usuario;
        }

        return false;
    }

    /**
     * Comprueba si el email ya existe en la BD
     */
    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM usuario WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }

    /**
     * Registra un nuevo usuario con la contraseña hasheada en bcrypt
     * @return array Datos del usuario recién creado (sin contraseña)
     */
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
}
