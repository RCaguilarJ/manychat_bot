<?php
// conexion.php
$host = 'localhost';
$dbname = 'manychatdb'; // ¡Asegúrate de poner el nombre real!
$username = 'root';
$password = '';

try {
    // Creamos la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configuramos para que nos avise si hay errores de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Si falla, detenemos todo y mostramos un mensaje genérico por seguridad
    die("Error de conexión a la base de datos."); 
    // En producción, aquí podrías escribir el error real en un archivo de log
}
?>