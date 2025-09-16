<?php
session_start();

// Asegurarse de que el usuario esté logueado y que se haya pasado un ID
if (!isset($_SESSION['usuario_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: carrito.php");
    exit();
}

$id_item_carrito = $_GET['id'];
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

// Eliminar el item del carrito de la base de datos
$sql = "DELETE FROM carrito WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_item_carrito, $usuario_id);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirigir de vuelta al carrito
header("Location: carrito.php");
exit();
?>