<?php
session_start();

// Redirigir si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si la solicitud es un POST y si tiene los datos necesarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['perfume_id'])) {
    $perfume_id = $_POST['perfume_id'];
    $cantidad = isset($_POST['cantidad']) && is_numeric($_POST['cantidad']) ? $_POST['cantidad'] : 1;
    $usuario_id = $_SESSION['usuario_id'];

    // Configuración de la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "jd_perfumeria";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si el perfume ya está en el carrito
    $sql_check = "SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND perfume_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $usuario_id, $perfume_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si el perfume ya está, actualizamos la cantidad
        $row = $result_check->fetch_assoc();
        $nueva_cantidad = $row['cantidad'] + $cantidad;
        $sql_update = "UPDATE carrito SET cantidad = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $nueva_cantidad, $row['id']);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Si no está, lo añadimos como un nuevo producto
        $sql_insert = "INSERT INTO carrito (usuario_id, perfume_id, cantidad) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $usuario_id, $perfume_id, $cantidad);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $stmt_check->close();
    $conn->close();

    // Redirigir al usuario a la página del carrito después de agregar el producto
    header("Location: carrito.php");
    exit();
} else {
    // Si la solicitud no es válida, redirigir al catálogo
    header("Location: catalogo.php");
    exit();
}
?>