<?php


// Redirigir si el usuario no ha iniciado sesión o si no hay datos POST
if (!isset($_SESSION['usuario_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$direccion_envio = $_POST['direccion_envio'];
$telefono_envio = $_POST['telefono_envio'];
$notas = $_POST['notas'] ?? ''; 
$metodo_pago = 'Pago contra entrega';

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los productos del carrito y calcular el subtotal
$sql_carrito = "SELECT p.id, p.precio, c.cantidad FROM carrito c JOIN perfumes p ON c.perfume_id = p.id WHERE c.usuario_id = ?";
$stmt_carrito = $conn->prepare($sql_carrito);
$stmt_carrito->bind_param("i", $usuario_id);
$stmt_carrito->execute();
$result_carrito = $stmt_carrito->get_result();
$productos_carrito = $result_carrito->fetch_all(MYSQLI_ASSOC);
$stmt_carrito->close();

if (empty($productos_carrito)) {
    header("Location: carrito.php?error=carrito_vacio");
    exit();
}

// Calcular totales
$subtotal = 0;
foreach ($productos_carrito as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}

$costo_envio = 5.00;
$impuestos = 0.00;
$total_pedido = $subtotal + $impuestos + $costo_envio;

// Generar un número de pedido único
$numero_pedido = 'JD' . time() . rand(1000, 9999);

// Insertar el pedido en la tabla 'pedidos' sin la columna 'nombre_envio' y 'telefono_envio'
// Ya que la tabla que proporcionaste no las tenía
$sql_pedido = "INSERT INTO pedidos (numero_pedido, usuario_id, subtotal, impuestos, envio, total, metodo_pago, direccion_envio, notas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_pedido = $conn->prepare($sql_pedido);
$stmt_pedido->bind_param("sidddssss", $numero_pedido, $usuario_id, $subtotal, $impuestos, $costo_envio, $total_pedido, $metodo_pago, $direccion_envio, $notas);
$stmt_pedido->execute();
$pedido_id = $conn->insert_id;
$stmt_pedido->close();

// Insertar los productos del carrito como detalles del pedido
$sql_detalle = "INSERT INTO detalle_pedido (pedido_id, perfume_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
$stmt_detalle = $conn->prepare($sql_detalle);
foreach ($productos_carrito as $item) {
    $stmt_detalle->bind_param("iiid", $pedido_id, $item['id'], $item['cantidad'], $item['precio']);
    $stmt_detalle->execute();
}
$stmt_detalle->close();

// Vaciar el carrito del usuario
$sql_vaciar = "DELETE FROM carrito WHERE usuario_id = ?";
$stmt_vaciar = $conn->prepare($sql_vaciar);
$stmt_vaciar->bind_param("i", $usuario_id);
$stmt_vaciar->execute();
$stmt_vaciar->close();

$conn->close();

// Redirigir a una página de confirmación
header("Location: confirmacion_pago.php?pedido_id=" . $pedido_id);
exit();
?>