<?php
// Iniciar la sesión para acceder al usuario logueado
session_start();

// Aquí podrías agregar una verificación para asegurarte de que solo los administradores puedan acceder
// Por ahora, asumiremos que estás logueado como admin
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php"); // Redirigir al login si no está logueado
    exit();
}

// Incluye el header del panel de administración
include '../includes/header.php';

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener todos los pedidos con información del usuario y del perfume
$sql = "
    SELECT 
        p.id AS pedido_id,
        p.numero_pedido,
        p.fecha_pedido,
        p.total,
        p.estado,
        u.nombre AS nombre_usuario,
        u.email AS email_usuario,
        dp.cantidad,
        perf.nombre AS nombre_perfume
    FROM 
        pedidos p
    JOIN 
        usuarios u ON p.usuario_id = u.id
    JOIN 
        detalles_pedido dp ON p.id = dp.pedido_id
    JOIN
        perfumes perf ON dp.perfume_id = perf.id
    ORDER BY 
        p.fecha_pedido DESC
";
$result = $conn->query($sql);

?>

<main class="container">
    <h2>Gestión de Pedidos</h2>
    <p>Aquí puedes ver y gestionar todas las solicitudes de tus clientes.</p>

    <table class="orders-admin-table">
        <thead>
            <tr>
                <th>Pedido #</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Perfume</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['numero_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_perfume']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td>$<?php echo number_format($row['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td>
                            <a href="#" class="btn btn-small">Ver Detalle</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='9'>No hay pedidos pendientes.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</main>

<?php include '../includes/footer.php'; ?>
<style>
    /* Estilos específicos para la tabla de pedidos */
    .orders-admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .orders-admin-table th, .orders-admin-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    .orders-admin-table th {
        background-color: #f2f2f2;
        color: var(--primary);
    }
    .orders-admin-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>