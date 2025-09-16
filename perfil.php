<?php
include 'includes/header.php';

// Verificar sesión
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

// Conexión BD
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos del usuario
$sql_usuario = "SELECT nombre, email, telefono, direccion, ciudad, codigo_postal, pais 
                FROM usuarios WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $usuario_id);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$stmt_usuario->close();
?>

<main class="container profile-page">
    <h2>Mi Perfil</h2>
    <div class="profile-info card">
        <h3>Información Personal</h3>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($usuario['direccion']); ?></p>
        <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($usuario['ciudad']); ?></p>
        <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($usuario['codigo_postal']); ?></p>
        <p><strong>País:</strong> <?php echo htmlspecialchars($usuario['pais']); ?></p>
        <a href="#" class="btn btn-secondary edit-btn">Editar Perfil</a>
    </div>

    <hr>

    <div class="user-orders card">
        <h3>Mis Pedidos</h3>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Número de Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_pedidos = "SELECT id, numero_pedido, fecha_pedido, estado, total 
                                FROM pedidos 
                                WHERE usuario_id = ? ORDER BY fecha_pedido DESC";
                $stmt_pedidos = $conn->prepare($sql_pedidos);
                $stmt_pedidos->bind_param("i", $usuario_id);
                $stmt_pedidos->execute();
                $result_pedidos = $stmt_pedidos->get_result();

                if ($result_pedidos->num_rows > 0) {
                    while($pedido = $result_pedidos->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['numero_pedido']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($pedido['fecha_pedido']))); ?></td>
                            <td><span class="badge status-<?php echo strtolower($pedido['estado']); ?>"><?php echo htmlspecialchars($pedido['estado']); ?></span></td>
                            <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                            <td><a href="detalle_pedido.php?id=<?php echo $pedido['id']; ?>" class="btn btn-small btn-primary">Ver Detalles</a></td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='5'>No has realizado ningún pedido todavía.</td></tr>";
                }
                $stmt_pedidos->close();
                ?>
            </tbody>
        </table>
    </div>
</main>

<?php 
$conn->close();
include 'includes/footer.php'; 
?>

<style>
/* Estilos para el perfil (unificados) */
.profile-page h2 {
    color: var(--primary);
    text-align: center;
    margin-bottom: 30px;
}
.card {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}
.profile-info h3, .user-orders h3 {
    color: var(--primary);
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
}
.profile-info p {
    margin: 5px 0;
    font-size: 1.1em;
}
.edit-btn {
    margin-top: 20px;
}
.orders-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.orders-table th, .orders-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}
.orders-table th {
    background-color: #f2f2f2;
    color: #555;
    font-weight: bold;
}
.orders-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
.badge {
    padding: 4px 8px;
    border-radius: 12px;
    color: #fff;
    font-size: 0.9em;
    font-weight: bold;
}
.status-pendiente { background-color: #f39c12; }
.status-en_proceso { background-color: #3498db; }
.status-entregado { background-color: #27ae60; }
.status-cancelado { background-color: #e74c3c; }
</style>