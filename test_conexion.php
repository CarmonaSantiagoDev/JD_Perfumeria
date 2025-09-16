<?php
// test_conexion.php - Verificar conexión a la base de datos
echo "<h2>Test de Conexión - JD Perfumería</h2>";

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("<p style='color: red;'>Conexión fallida: " . $conn->connect_error . "</p>");
} else {
    echo "<p style='color: green;'>✅ Conexión exitosa a MySQL</p>";
}
   