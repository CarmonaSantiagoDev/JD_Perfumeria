<?php

include 'includes/header.php';

// Redirigir si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$perfumes_en_carrito = [];
$total_carrito = 0;
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

// Obtener los datos del carrito para el resumen
$sql = "SELECT p.nombre, p.precio, p.imagen, c.cantidad 
        FROM carrito c 
        JOIN perfumes p ON c.perfume_id = p.id 
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $perfumes_en_carrito[] = $row;
        $total_carrito += $row['precio'] * $row['cantidad'];
    }
}
$stmt->close();
$conn->close();

// Si el carrito está vacío, redirigir al carrito
if (empty($perfumes_en_carrito)) {
    header("Location: carrito.php?error=carrito_vacio");
    exit();
}
?>

<main class="container checkout-container">
    <h2 class="section-title">Finalizar Compra</h2>
    
    <div class="checkout-grid">
        <div class="checkout-summary">
            <h3>Resumen de tu Pedido</h3>
            <?php foreach ($perfumes_en_carrito as $item): ?>
                <div class="summary-item">
                    <img src="<?php echo htmlspecialchars($item['imagen'] ?? 'img/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="summary-item-image">
                    <div class="summary-item-details">
                        <p class="summary-item-title"><?php echo htmlspecialchars($item['nombre']); ?></p>
                        <p class="summary-item-qty-price"><?php echo htmlspecialchars($item['cantidad']); ?> x $<?php echo number_format($item['precio'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="summary-total">
                <h4>Total del Pedido:</h4>
                <p>$<?php echo number_format($total_carrito, 2); ?></p>
            </div>
        </div>
        
        <div class="checkout-form-container">
            <h3>Detalles para el Envío</h3>
            <form action="procesar_pago.php" method="POST" class="checkout-form">
                <div class="form-group">
                    <label for="direccion">Dirección de Envío Completa</label>
                    <input type="text" id="direccion" name="direccion_envio" placeholder="Ej: Calle 10 #20-30, Barrio X" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono de Contacto</label>
                    <input type="tel" id="telefono" name="telefono_envio" required>
                </div>
                 <div class="form-group">
                    <label for="notas">Notas Adicionales (Ej: Horario de entrega, indicaciones)</label>
                    <textarea id="notas" name="notas" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Confirmar Pedido</button>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
/* Los estilos CSS no se modifican */
.checkout-container { padding-top: 50px; padding-bottom: 50px; }
.checkout-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; }
.checkout-summary, .checkout-form-container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.summary-item { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
.summary-item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
.summary-item-details { flex-grow: 1; }
.summary-item-title { margin: 0; font-weight: bold; }
.summary-item-qty-price { margin: 0; color: #888; }
.summary-total { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; font-size: 1.2em; font-weight: bold; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
.btn-block { width: 100%; padding: 15px; font-size: 1.1em; }
</style>