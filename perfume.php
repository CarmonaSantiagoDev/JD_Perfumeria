<?php
// Incluye solo la lógica de sesión primero, sin output
ob_start(); // Iniciar buffer de output
session_start();

// Verificar que se haya proporcionado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: catalogo.php');
    exit;
}

$perfume_id = intval($_GET['id']);

// Configuración de la base de datos
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Manejar error sin output
    header('Location: catalogo.php?error=db');
    exit;
}

// Obtener los detalles del perfume
$sql = "SELECT p.*, c.nombre as categoria_nombre, m.nombre as marca_nombre 
        FROM perfumes p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        LEFT JOIN marcas m ON p.marca_id = m.id 
        WHERE p.id = ? AND p.activo = TRUE";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $perfume_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Limpiar buffer y redirigir
    ob_end_clean();
    header('Location: catalogo.php');
    exit;
}

$perfume = $result->fetch_assoc();

// Obtener perfumes relacionados (misma categoría)
$sql_relacionados = "SELECT id, nombre, precio, imagen 
                     FROM perfumes 
                     WHERE categoria_id = ? AND id != ? AND activo = TRUE 
                     ORDER BY RAND() 
                     LIMIT 4";
                     
$stmt_relacionados = $conn->prepare($sql_relacionados);
$stmt_relacionados->bind_param("ii", $perfume['categoria_id'], $perfume_id);
$stmt_relacionados->execute();
$relacionados = $stmt_relacionados->get_result()->fetch_all(MYSQLI_ASSOC);

$conn->close();

// Ahora incluir el header completo después de toda la lógica
include 'includes/header.php';
?>

<main class="container">
    <div class="product-detail">
        <!-- Ruta de navegación -->
        <nav class="breadcrumb">
            <a href="index.php">Inicio</a> >
            <a href="catalogo.php">Catálogo</a> >
            <span><?php echo htmlspecialchars($perfume['nombre']); ?></span>
        </nav>
        
        <div class="product-detail-container">
            <div class="product-detail-image">
                <img src="<?php echo htmlspecialchars($perfume['imagen']); ?>" alt="<?php echo htmlspecialchars($perfume['nombre']); ?>">
                <?php if ($perfume['precio_oferta'] > 0 && $perfume['precio_oferta'] < $perfume['precio']): ?>
                    <span class="product-badge">Oferta</span>
                <?php endif; ?>
            </div>
            
            <div class="product-detail-info">
                <?php if (!empty($perfume['genero'])): ?>
                    <span class="product-tag"><?php echo ucfirst($perfume['genero']); ?></span>
                <?php endif; ?>
                
                <?php if (!empty($perfume['categoria_nombre'])): ?>
                    <span class="product-tag"><?php echo htmlspecialchars($perfume['categoria_nombre']); ?></span>
                <?php endif; ?>
                
                <h1><?php echo htmlspecialchars($perfume['nombre']); ?></h1>
                
                <?php if (!empty($perfume['marca_nombre'])): ?>
                    <p class="product-brand">Marca: <?php echo htmlspecialchars($perfume['marca_nombre']); ?></p>
                <?php endif; ?>
                
                <div class="product-price-detail">
                    <?php if ($perfume['precio_oferta'] > 0 && $perfume['precio_oferta'] < $perfume['precio']): ?>
                        <span class="price-old">$<?php echo number_format($perfume['precio'], 2); ?></span>
                        <span class="price-current">$<?php echo number_format($perfume['precio_oferta'], 2); ?></span>
                    <?php else: ?>
                        <span class="price-current">$<?php echo number_format($perfume['precio'], 2); ?></span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($perfume['descripcion'])): ?>
                    <div class="product-description">
                        <h3>Descripción</h3>
                        <p><?php echo nl2br(htmlspecialchars($perfume['descripcion'])); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($perfume['notas_olfativas'])): ?>
                    <div class="product-notes">
                        <h3>Notas Olfativas</h3>
                        <p><?php echo nl2br(htmlspecialchars($perfume['notas_olfativas'])); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="product-specs">
                    <?php if (!empty($perfume['volumen'])): ?>
                        <div class="spec-item">
                            <strong>Volumen:</strong> <?php echo htmlspecialchars($perfume['volumen']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($perfume['tipo'])): ?>
                        <div class="spec-item">
                            <strong>Tipo:</strong> <?php echo htmlspecialchars($perfume['tipo']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($perfume['temporada'])): ?>
                        <div class="spec-item">
                            <strong>Temporada:</strong> <?php echo htmlspecialchars($perfume['temporada']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="product-actions-detail">
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <button class="btn btn-primary btn-large" onclick="agregarAlCarrito(<?php echo $perfume['id']; ?>)">
                            <i class="fas fa-calendar-check"></i> Apartar ahora
                        </button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-large">
                            <i class="fas fa-user"></i> Iniciar sesión para apartar
                        </a>
                    <?php endif; ?>
                    
                    <button class="btn btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Productos relacionados -->
        <?php if (!empty($relacionados)): ?>
        <section class="related-products">
            <h2>Productos Relacionados</h2>
            <div class="related-grid">
                <?php foreach ($relacionados as $relacionado): ?>
                <div class="related-product">
                    <a href="perfume.php?id=<?php echo $relacionado['id']; ?>">
                        <img src="<?php echo htmlspecialchars($relacionado['imagen']); ?>" alt="<?php echo htmlspecialchars($relacionado['nombre']); ?>">
                        <h4><?php echo htmlspecialchars($relacionado['nombre']); ?></h4>
                        <p class="related-price">$<?php echo number_format($relacionado['precio'], 2); ?></p>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>

<?php 
// Incluye el pie de página que cierra el HTML
include 'includes/footer.php'; 
?>

<style>
/* Estilos para la página de detalle */
.breadcrumb {
    margin-bottom: 20px;
    font-size: 14px;
    color: var(--secondary);
}

.breadcrumb a {
    color: var(--secondary);
    text-decoration: none;
}

.breadcrumb a:hover {
    color: var(--primary);
}

.product-detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 50px;
}

.product-detail-image {
    position: relative;
}

.product-detail-image img {
    width: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-tag {
    display: inline-block;
    background: var(--secondary);
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    margin-right: 8px;
    margin-bottom: 15px;
}

.product-detail-info h1 {
    color: var(--primary);
    font-size: 2.2rem;
    margin-bottom: 10px;
    font-family: 'Playfair Display', serif;
}

.product-brand {
    color: var(--secondary);
    margin-bottom: 20px;
    font-style: italic;
}

.product-price-detail {
    margin: 25px 0;
}

.price-current {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary);
}

.price-old {
    font-size: 1.4rem;
    text-decoration: line-through;
    color: #999;
    margin-right: 15px;
}

.product-description,
.product-notes {
    margin: 25px 0;
}

.product-description h3,
.product-notes h3 {
    color: var(--primary);
    margin-bottom: 10px;
}

.product-description p,
.product-notes p {
    line-height: 1.6;
    color: #666;
}

.product-specs {
    margin: 25px 0;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.spec-item {
    margin-bottom: 10px;
}

.spec-item strong {
    color: var(--primary);
}

.product-actions-detail {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-large {
    padding: 15px 30px;
    font-size: 1.1rem;
}

/* Productos relacionados */
.related-products {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 1px solid #eee;
}

.related-products h2 {
    color: var(--primary);
    margin-bottom: 30px;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.related-product {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.related-product:hover {
    transform: translateY(-5px);
}

.related-product a {
    text-decoration: none;
    color: inherit;
}

.related-product img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 10px;
}

.related-product h4 {
    color: var(--primary);
    margin-bottom: 8px;
    font-size: 1rem;
}

.related-price {
    font-weight: bold;
    color: var(--secondary);
}

/* Responsive */
@media (max-width: 768px) {
    .product-detail-container {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .product-actions-detail {
        flex-direction: column;
    }
    
    .btn-large {
        width: 100%;
        text-align: center;
    }
    
    .related-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@media (max-width: 480px) {
    .product-detail-info h1 {
        font-size: 1.8rem;
    }
    
    .price-current {
        font-size: 1.6rem;
    }
    
    .price-old {
        font-size: 1.2rem;
    }
}
</style>

<script>
// Función para agregar al carrito
function agregarAlCarrito(perfumeId) {
    // Redirigir a la página de apartado
    window.location.href = `procesar_solicitud.php?perfume_id=${perfumeId}`;
}
</script>