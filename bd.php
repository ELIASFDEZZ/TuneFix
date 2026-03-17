<?php
$host     = 'localhost';
$dbname   = 'tunefix';           // ← Cambia si tu base de datos tiene otro nombre
$username = 'root';              // ← Usuario de MySQL (normalmente root en localhost)
$password = '';                  // ← Contraseña (vacía en muchos localhost, pon la tuya si tiene)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>