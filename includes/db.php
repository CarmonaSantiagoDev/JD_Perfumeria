<?php
// Configuración de conexión a la base de datos
$host = "localhost";   // Servidor
$user = "root";        // Usuario por defecto en WAMP
$password = "";        // Contraseña (en WAMP normalmente está vacía)
$dbname = "jd_perfumeria"; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
