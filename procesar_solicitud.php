<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las variables del formulario existen
    $metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : 'No especificado';
    $direccion_envio = isset($_POST['direccion_envio']) ? $_POST['direccion_envio'] : '';
    $notas = isset($_POST['notas']) ? $_POST['notas'] : '';

    // Lógica para calcular el total del pedido desde el carrito
    $usuario_id = $_SESSION['usuario_id'];
    $sql_total = "SELECT SUM(p.precio * c.cantidad) AS total_pedido FROM carrito c JOIN perfumes p ON c.perfume_id = p.id WHERE c.usuario_id = ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("i", $usuario_id);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $row_total = $result_total->fetch_assoc();
    $stmt_total->close();

    // **AQUÍ ESTÁ LA CORRECCIÓN:**
    // Validar que el total no sea nulo o 0 (es decir, que el carrito no esté vacío)
    if ($row_total['total_pedido'] == NULL || $row_total['total_pedido'] == 0) {
        // Redirigir al usuario al carrito con un mensaje de error
        header("Location: carrito.php?error=carrito_vacio");
        exit();
    }
    
    $total_pedido = $row_total['total_pedido'];

    // Generar un número de pedido único
    $numero_pedido = 'ORD-' . strtoupper(uniqid());
    
    // Asumimos que subtotal, impuestos y envío son parte del cálculo
    $subtotal = $total_pedido;
    $impuestos = $subtotal * 0.16; // Ejemplo: 16% de IVA
    $envio = 150.00; // Ejemplo: costo de envío fijo
    $total_con_extras = $subtotal + $impuestos + $envio;

    // Insertar el nuevo pedido en la tabla `pedidos`
    $sql_pedido = "INSERT INTO pedidos (usuario_id, numero_pedido, total, subtotal, impuestos, envio, metodo_pago, direccion_envio, notas, fecha_pedido) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("isddddsss", $usuario_id, $numero_pedido, $total_con_extras, $subtotal, $impuestos, $envio, $metodo_pago, $direccion_envio, $notas);

    if ($stmt_pedido->execute()) {
        $pedido_id = $conn->insert_id;
        $stmt_pedido->close();

        // Mover los productos del carrito a los detalles del pedido
        $sql_carrito = "SELECT perfume_id, cantidad, p.precio FROM carrito c JOIN perfumes p ON c.perfume_id = p.id WHERE c.usuario_id = ?";
        $stmt_carrito = $conn->prepare($sql_carrito);
        $stmt_carrito->bind_param("i", $usuario_id);
        $stmt_carrito->execute();
        $result_carrito = $stmt_carrito->get_result();
        
        while ($item = $result_carrito->fetch_assoc()) {
            $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, perfume_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt_detalle = $conn->prepare($sql_detalle);
            $subtotal_detalle = $item['cantidad'] * $item['precio'];
            $stmt_detalle->bind_param("iiidd", $pedido_id, $item['perfume_id'], $item['cantidad'], $item['precio'], $subtotal_detalle);
            $stmt_detalle->execute();
            $stmt_detalle->close();
        }
        
        $stmt_carrito->close();

        // Vaciar el carrito del usuario
        $sql_vaciar = "DELETE FROM carrito WHERE usuario_id = ?";
        $stmt_vaciar = $conn->prepare($sql_vaciar);
        $stmt_vaciar->bind_param("i", $usuario_id);
        $stmt_vaciar->execute();
        $stmt_vaciar->close();

        // Redirigir a una página de confirmación de pedido
        header("Location: confirmacion_pedido.php?pedido=" . urlencode($numero_pedido));
        exit();

    } else {
        echo "Error al procesar el pedido: " . $stmt_pedido->error;
    }
}

$conn->close();
?>