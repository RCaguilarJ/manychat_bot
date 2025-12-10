<?php
// conexion.php
$host = '127.0.0.1'; // Usamos la IP directa
$port = '3306';      // Forzamos el puerto estándar de WAMP
$dbname = 'manychatdb';
$username = 'root';
$password = '';

try {
    // Creamos la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configuramos para que nos avise si hay errores de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // CAMBIO TEMPORAL: Mostrar el error real
    die("Error SQL DETALLADO: " . $e->getMessage()); 
}
?>