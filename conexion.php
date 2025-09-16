<?php
$host = "localhost";
$user = "root";   // Usuario de MySQL en WAMP
$pass = "";       // Contraseña de MySQL (vacío por defecto en WAMP)
$db   = "jd_perfumeria";  // Nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
