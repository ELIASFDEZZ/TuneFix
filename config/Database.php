<?php

/**
 * Clase para gestionar la conexión a la base de datos usando PDO
 * Implementa el patrón Singleton → solo se crea una conexión
 */
class Database
{
    // Variable estática que guarda la única instancia de PDO
    private static ?PDO $instance = null;

    // Credenciales de conexión (cámbialas según tu entorno)
    private static string $host = 'localhost';
    private static string $dbname = 'tunefix';
    private static string $username = 'root';
    private static string $password = '';

    /**
     * Obtiene (o crea) la conexión PDO única
     * @return PDO Conexión activa a la base de datos
     */
    public static function getConnection(): PDO
    {
        // Si aún no existe la conexión, la creamos
        if (self::$instance === null) {
            try {
                // Cadena de conexión con charset utf8mb4 (soporta emojis y caracteres especiales)
                self::$instance = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$username,
                    self::$password
                );

                // Configuraciones recomendadas
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     // Lanza excepciones en errores
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Devuelve arrays asociativos por defecto
            } catch (PDOException $e) {
                // Si falla la conexión, mostramos error y paramos
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    // Bloqueamos clonación e instanciación directa (por seguridad del Singleton)
    private function __construct()
    {
    }
    private function __clone()
    {
    }
}