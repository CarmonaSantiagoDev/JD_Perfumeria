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

// Verificar si la base de datos existe
if ($conn->select_db($dbname)) {
    echo "<p style='color: green;'>✅ Base de datos '$dbname' encontrada</p>";
    
    // Verificar tabla de usuarios
    $result = $conn->query("SHOW TABLES LIKE 'usuarios'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Tabla 'usuarios' encontrada</p>";
        
        // Verificar usuarios administradores
        $admin_query = "SELECT id, nombre, email, rol FROM usuarios WHERE rol = 'admin'";
        $admin_result = $conn->query($admin_query);
        
        if ($admin_result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Se encontraron " . $admin_result->num_rows . " administrador(es)</p>";
            echo "<h3>Administradores en el sistema:</h3>";
            echo "<table border='1' cellpadding='8'>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th></tr>";
            
            while ($row = $admin_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['rol'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ No se encontraron usuarios administradores</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Tabla 'usuarios' NO encontrada</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Base de datos '$dbname' NO encontrada</p>";
    echo "<p>Intentando crear la base de datos...</p>";
    
    // Crear la base de datos
    $create_db = $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    if ($create_db) {
        echo "<p style='color: green;'>✅ Base de datos '$dbname' creada exitosamente</p>";
        $conn->select_db($dbname);
    } else {
        echo "<p style='color: red;'>❌ Error al crear la base de datos: " . $conn->error . "</p>";
    }
}

$conn->close();
?>