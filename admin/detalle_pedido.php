<?php


// Redirigir si no ha iniciado sesión o no es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del pedido de la URL
$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$pedido = null;
$detalles_pedido = [];

if ($pedido_id > 0) {
    // Obtener los datos del pedido principal
    $sql_pedido = "SELECT p.*, u.nombre AS nombre_usuario, u.email, u.telefono, u.direccion, u.ciudad, u.codigo_postal, u.pais
                   FROM pedidos p 
                   JOIN usuarios u ON p.usuario_id = u.id 
                   WHERE p.id = ?";
    $stmt_pedido = $conn->prepare($sql_pedido);
    $stmt_pedido->bind_param("i", $pedido_id);
    $stmt_pedido->execute();
    $result_pedido = $stmt_pedido->get_result();
    $pedido = $result_pedido->fetch_assoc();
    $stmt_pedido->close();

    // Obtener los detalles de los productos del pedido
    $sql_detalles = "SELECT dp.*, perf.nombre AS nombre_perfume 
                     FROM detalle_pedido dp 
                     JOIN perfumes perf ON dp.perfume_id = perf.id 
                     WHERE dp.pedido_id = ?";
    $stmt_detalles = $conn->prepare($sql_detalles);
    $stmt_detalles->bind_param("i", $pedido_id);
    $stmt_detalles->execute();
    $result_detalles = $stmt_detalles->get_result();
    $detalles_pedido = $result_detalles->fetch_all(MYSQLI_ASSOC);
    $stmt_detalles->close();
}
$conn->close();

?>

<main class="container detalle-container">
    <div class="header-detalle">
        <a href="dashboard.php" class="btn-back">← Volver al Listado</a>
        <h2 class="section-title">Detalles del Pedido #<?php echo htmlspecialchars($pedido['numero_pedido'] ?? ''); ?></h2>
    </div>

    <?php if (!$pedido): ?>
        <div class="alert alert-danger">
            Pedido no encontrado.
        </div>
    <?php else: ?>
    <div class="detalle-content">
        <div class="card info-pedido">
            <h3>Información del Pedido</h3>
            <p><strong>Fecha del Pedido:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($pedido['fecha_pedido']))); ?></p>
            <p><strong>Estado:</strong> <span class="badge status-<?php echo strtolower(str_replace(' ', '-', $pedido['estado'])); ?>"><?php echo htmlspecialchars($pedido['estado']); ?></span></p>
            <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago']); ?></p>
            <p><strong>Notas del Cliente:</strong> <?php echo htmlspecialchars($pedido['notas'] ?: 'Ninguna'); ?></p>
        </div>

        <div class="card info-cliente">
            <h3>Información del Cliente</h3>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre_usuario']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono'] ?: 'No registrado'); ?></p>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion'] ?: 'No registrado'); ?>, <?php echo htmlspecialchars($pedido['ciudad'] ?: 'N/A'); ?>, <?php echo htmlspecialchars($pedido['pais'] ?: 'N/A'); ?></p>
        </div>

        <div class="card productos-pedido">
            <h3>Productos Comprados</h3>
            <table class="table-productos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles_pedido as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre_perfume']); ?></td>
                        <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                        <td>$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                        <td>$<?php echo number_format($item['cantidad'] * $item['precio_unitario'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="resumen-pedido">
            <p><strong>Subtotal:</strong> $<?php echo number_format($pedido['subtotal'], 2); ?></p>
            <p><strong>Costo de Envío:</strong> $<?php echo number_format($pedido['envio'], 2); ?></p>
            <p class="total-final"><strong>Total del Pedido:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
        </div>

        <div class="action-buttons">
            <button class="btn btn-success">Marcar como Entregado</button>
            <button class="btn btn-danger">Cancelar Pedido</button>
        </div>
    </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>

<style>
/* Estilos para la página de detalles del pedido */
.detalle-container {
    padding-top: 50px;
    padding-bottom: 50px;
}

.header-detalle {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.btn-back {
    color: var(--primary);
    text-decoration: none;
    font-weight: bold;
}

.detalle-content {
    display: grid;
    gap: 30px;
}

.card {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.card h3 {
    margin-top: 0;
    color: var(--primary);
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.info-pedido p, .info-cliente p {
    font-size: 1em;
    line-height: 1.6;
}

.table-productos {
    width: 100%;
    border-collapse: collapse;
}

.table-productos th, .table-productos td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.table-productos th {
    background-color: #f8f9fa;
}

.resumen-pedido {
    text-align: right;
    font-size: 1.2em;
    line-height: 1.8;
}

.total-final {
    font-size: 1.5em;
    font-weight: bold;
    color: var(--primary);
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    color: #fff;
    font-size: 0.9em;
    font-weight: bold;
}

.status-pendiente { background-color: #f39c12; }
.status-en-proceso { background-color: #3498db; }
.status-entregado { background-color: #27ae60; }
.status-cancelado { background-color: #e74c3c; }

.action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 20px;
}

@media (min-width: 768px) {
    .detalle-content {
        grid-template-columns: 1fr 1fr;
    }
    .productos-pedido, .resumen-pedido, .action-buttons {
        grid-column: span 2;
    }
}
</style>