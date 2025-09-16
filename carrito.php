<?php


// Redirigir si no hay sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener productos del carrito
$sql_carrito = "SELECT c.id, p.nombre, p.precio, p.url_imagen, c.cantidad 
                FROM carrito c 
                JOIN perfumes p ON c.perfume_id = p.id 
                WHERE c.usuario_id = ?";
$stmt_carrito = $conn->prepare($sql_carrito);
$stmt_carrito->bind_param("i", $usuario_id);
$stmt_carrito->execute();
$result_carrito = $stmt_carrito->get_result();

$total = 0;
?>

<main class="container">
    <h2>Mi Carrito</h2>
    <div class="cart-items">
        <?php if ($result_carrito->num_rows > 0): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $result_carrito->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="item-info">
                                    <img src="<?php echo htmlspecialchars($item['url_imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="item-image">
                                    <span class="item-name"><?php echo htmlspecialchars($item['nombre']); ?></span>
                                </div>
                            </td>
                            <td>$<?php echo number_format($item['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                            <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                            <td>
                                <form action="eliminar_del_carrito.php" method="POST">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php $total += $item['precio'] * $item['cantidad']; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="cart-summary">
                <h3>Resumen del Pedido</h3>
                <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
                <a href="checkout.php" class="btn btn-primary">Proceder al Pago</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Tu carrito está vacío.</div>
        <?php endif; ?>
    </div>
</main>

<?php 
$stmt_carrito->close();
$conn->close();
include 'includes/footer.php'; 
?>