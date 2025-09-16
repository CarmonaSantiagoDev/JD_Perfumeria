<?php
// Incluye el encabezado que ya inicia la sesión
include 'includes/header.php';

// Configuración de la base de datos
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener parámetros de filtrado
$genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;
$precio_min = isset($_GET['precio_min']) ? floatval($_GET['precio_min']) : 0;
$precio_max = isset($_GET['precio_max']) ? floatval($_GET['precio_max']) : 0;
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'id_desc';

// Configurar paginación
$productos_por_pagina = 12;
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina - 1) * $productos_por_pagina;

// Construir consulta base
$sql = "SELECT p.id, p.nombre, p.precio, p.precio_oferta, p.imagen, p.descripcion, p.genero, 
               c.nombre as categoria_nombre 
        FROM perfumes p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.activo = TRUE";
$count_sql = "SELECT COUNT(*) as total 
              FROM perfumes p 
              WHERE p.activo = TRUE";
$params = [];
$types = "";

// Aplicar filtros
if (!empty($genero)) {
    $sql .= " AND p.genero = ?";
    $count_sql .= " AND p.genero = ?";
    $params[] = $genero;
    $types .= "s";
}

if ($categoria_id > 0) {
    $sql .= " AND p.categoria_id = ?";
    $count_sql .= " AND p.categoria_id = ?";
    $params[] = $categoria_id;
    $types .= "i";
}

if ($precio_min > 0) {
    $sql .= " AND p.precio >= ?";
    $count_sql .= " AND p.precio >= ?";
    $params[] = $precio_min;
    $types .= "d";
}

if ($precio_max > 0 && $precio_max >= $precio_min) {
    $sql .= " AND p.precio <= ?";
    $count_sql .= " AND p.precio <= ?";
    $params[] = $precio_max;
    $types .= "d";
}

// Aplicar ordenamiento
switch ($orden) {
    case 'precio_asc':
        $sql .= " ORDER BY p.precio ASC";
        break;
    case 'precio_desc':
        $sql .= " ORDER BY p.precio DESC";
        break;
    case 'nombre_asc':
        $sql .= " ORDER BY p.nombre ASC";
        break;
    case 'nombre_desc':
        $sql .= " ORDER BY p.nombre DESC";
        break;
    case 'popularidad':
        $sql .= " ORDER BY p.popularidad DESC";
        break;
    default:
        $sql .= " ORDER BY p.id DESC";
}

// Añadir límites para paginación
$sql .= " LIMIT ? OFFSET ?";
$params[] = $productos_por_pagina;
$params[] = $offset;
$types .= "ii";

// Preparar y ejecutar consulta
$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$perfumes = $result->fetch_all(MYSQLI_ASSOC);

// Obtener categorías para el filtro
$categorias_result = $conn->query("SELECT id, nombre FROM categorias WHERE activo = TRUE ORDER BY nombre");
$categorias = [];
if ($categorias_result->num_rows > 0) {
    while ($row = $categorias_result->fetch_assoc()) {
        $categorias[] = $row;
    }
}

// Obtener total de productos para paginación
$count_stmt = $conn->prepare($count_sql);
if ($types) {
    // Remover los tipos para límites (los últimos 2 caracteres son para los límites)
    $count_types = substr($types, 0, -2);
    if (!empty($count_types)) {
        $count_params = array_slice($params, 0, -2);
        $count_stmt->bind_param($count_types, ...$count_params);
    }
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_productos = $count_result->fetch_assoc()['total'];
$total_paginas = ceil($total_productos / $productos_por_pagina);

$conn->close();
?>

<main class="container">
    <h2 class="section-title">Nuestros Perfumes</h2>
    
    <!-- Filtros y ordenamiento -->
    <section class="catalog-filters">
        <div class="filter-header">
            <h3>Filtrar Productos</h3>
            <button class="filter-toggle" id="filterToggle">Filtros ▾</button>
        </div>
        
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-group">
                <label for="genero">Género:</label>
                <select name="genero" id="genero">
                    <option value="">Todos los géneros</option>
                    <option value="masculino" <?php echo $genero == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                    <option value="femenino" <?php echo $genero == 'femenino' ? 'selected' : ''; ?>>Femenino</option>
                    <option value="unisex" <?php echo $genero == 'unisex' ? 'selected' : ''; ?>>Unisex</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="categoria_id">Categoría:</label>
                <select name="categoria_id" id="categoria_id">
                    <option value="0">Todas las categorías</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>" <?php echo $categoria_id == $categoria['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="precio_min">Precio mínimo:</label>
                <input type="number" name="precio_min" id="precio_min" value="<?php echo $precio_min > 0 ? $precio_min : ''; ?>" placeholder="0" min="0">
            </div>
            
            <div class="filter-group">
                <label for="precio_max">Precio máximo:</label>
                <input type="number" name="precio_max" id="precio_max" value="<?php echo $precio_max > 0 ? $precio_max : ''; ?>" placeholder="Sin límite" min="0">
            </div>
            
            <div class="filter-group">
                <label for="orden">Ordenar por:</label>
                <select name="orden" id="orden">
                    <option value="id_desc" <?php echo $orden == 'id_desc' ? 'selected' : ''; ?>>Más recientes</option>
                    <option value="precio_asc" <?php echo $orden == 'precio_asc' ? 'selected' : ''; ?>>Precio: menor a mayor</option>
                    <option value="precio_desc" <?php echo $orden == 'precio_desc' ? 'selected' : ''; ?>>Precio: mayor a menor</option>
                    <option value="nombre_asc" <?php echo $orden == 'nombre_asc' ? 'selected' : ''; ?>>Nombre: A-Z</option>
                    <option value="nombre_desc" <?php echo $orden == 'nombre_desc' ? 'selected' : ''; ?>>Nombre: Z-A</option>
                    <option value="popularidad" <?php echo $orden == 'popularidad' ? 'selected' : ''; ?>>Más populares</option>
                </select>
            </div>
            
            <div class="filter-buttons">
                <button type="submit" class="btn btn-filter">Aplicar Filtros</button>
                <a href="catalogo.php" class="btn btn-clear">Limpiar Filtros</a>
            </div>
        </form>
    </section>
    
    <!-- Información de resultados -->
    <div class="results-info">
        <p>Mostrando <?php echo count($perfumes); ?> de <?php echo $total_productos; ?> productos</p>
    </div>
    
    <!-- Grid de productos -->
    <section class="catalog-grid">
        <?php if (!empty($perfumes)): ?>
            <?php foreach ($perfumes as $perfume): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($perfume['imagen'] ?? 'img/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($perfume['nombre']); ?>">
                        <div class="product-overlay">
                            <div class="overlay-content">
                                <button class="btn-quick-view" onclick="abrirModalDetalle(<?php echo $perfume['id']; ?>)">
                                    <i class="fas fa-eye"></i> Vista rápida
                                </button>
                                <?php if (isset($_SESSION['usuario_id'])): ?>
                                <button class="btn-add-cart" onclick="agregarAlCarrito(<?php echo $perfume['id']; ?>)">
                                    <i class="fas fa-calendar-check"></i> Apartar
                                </button>
                                <?php else: ?>
                                <a href="login.php" class="btn-add-cart">
                                    <i class="fas fa-user"></i> Iniciar sesión
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($perfume['precio_oferta'] > 0 && $perfume['precio_oferta'] < $perfume['precio']): ?>
                            <span class="product-badge">Oferta</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <?php if (!empty($perfume['genero'])): ?>
                            <span class="product-gender"><?php echo ucfirst($perfume['genero']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($perfume['categoria_nombre'])): ?>
                            <span class="product-category"><?php echo htmlspecialchars($perfume['categoria_nombre']); ?></span>
                        <?php endif; ?>
                        <h3 class="product-name"><?php echo htmlspecialchars($perfume['nombre']); ?></h3>
                        <div class="product-price-container">
                            <?php if ($perfume['precio_oferta'] > 0 && $perfume['precio_oferta'] < $perfume['precio']): ?>
                                <span class="product-price-old">$<?php echo number_format($perfume['precio'], 2); ?></span>
                                <span class="product-price">$<?php echo number_format($perfume['precio_oferta'], 2); ?></span>
                            <?php else: ?>
                                <span class="product-price">$<?php echo number_format($perfume['precio'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <a href="perfume.php?id=<?php echo htmlspecialchars($perfume['id']); ?>" class="btn btn-details">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-products">
                <i class="fas fa-search"></i>
                <h3>No se encontraron productos</h3>
                <p>Intenta ajustar tus filtros de búsqueda</p>
                <a href="catalogo.php" class="btn btn-primary">Ver todos los productos</a>
            </div>
        <?php endif; ?>
    </section>
    
    <!-- Paginación -->
    <?php if ($total_paginas > 1): ?>
    <div class="pagination">
        <?php if ($pagina > 1): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => 1])); ?>" class="page-link">« Primera</a>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])); ?>" class="page-link">‹ Anterior</a>
        <?php endif; ?>
        
        <?php 
        // Mostrar números de página
        $inicio = max(1, $pagina - 2);
        $fin = min($total_paginas, $pagina + 2);
        
        for ($i = $inicio; $i <= $fin; $i++): 
        ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $i])); ?>" class="page-link <?php echo $i == $pagina ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        
        <?php if ($pagina < $total_paginas): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])); ?>" class="page-link">Siguiente ›</a>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['pagina' => $total_paginas])); ?>" class="page-link">Última »</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</main>

<!-- Modal para vista rápida -->
<div id="modalDetalle" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div id="modalContent" class="modal-body"></div>
    </div>
</div>

<?php 
// Incluye el pie de página que cierra el HTML
include 'includes/footer.php'; 
?>

<style>
/* Estilos para el catálogo mejorado */
.section-title {
    text-align: center;
    margin-bottom: 30px;
    font-size: 2.5rem;
    color: var(--primary);
    font-family: 'Playfair Display', serif;
}

/* Filtros */
.catalog-filters {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.filter-header h3 {
    margin: 0;
    color: var(--primary);
}

.filter-toggle {
    display: none;
    background: none;
    border: 1px solid var(--secondary);
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--secondary);
}

.filter-group select,
.filter-group input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.filter-buttons {
    grid-column: 1 / -1;
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.btn-filter {
    background-color: var(--primary);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-clear {
    background-color: #6c757d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

/* Información de resultados */
.results-info {
    margin-bottom: 20px;
    text-align: center;
    color: var(--secondary);
}

/* Grid de productos */
.catalog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.product-card {
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.product-image-container {
    width: 100%;
    height: 250px;
    overflow: hidden;
    position: relative;
}

.product-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--accent);
    color: var(--text-dark);
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 12px;
    z-index: 2;
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(11, 23, 54, 0.95);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.overlay-content {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 80%;
}

.btn-quick-view,
.btn-add-cart {
    background: var(--accent);
    color: var(--text-dark);
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: block;
}

.btn-quick-view:hover,
.btn-add-cart:hover {
    background: #d4af37;
    transform: translateY(-2px);
}

.product-info {
    padding: 20px;
}

.product-gender,
.product-category {
    display: inline-block;
    background: var(--secondary);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    margin-bottom: 8px;
    margin-right: 5px;
}

.product-name {
    font-size: 1.25rem;
    color: var(--primary);
    margin-bottom: 8px;
    font-weight: 600;
}

.product-price-container {
    margin-bottom: 15px;
}

.product-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
}

.product-price-old {
    font-size: 1rem;
    text-decoration: line-through;
    color: #999;
    margin-right: 10px;
}

.product-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-details {
    background-color: var(--secondary);
    color: white;
    text-align: center;
    padding: 10px;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.btn-details:hover {
    background-color: #2c3e50;
}

/* Sin productos */
.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--secondary);
}

.no-products i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #ddd;
}

.no-products h3 {
    margin-bottom: 10px;
    color: var(--primary);
}

/* Paginación */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 40px;
    flex-wrap: wrap;
}

.page-link {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-decoration: none;
    color: var(--primary);
    transition: all 0.3s ease;
}

.page-link:hover,
.page-link.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Modal mejorado */
.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 700px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    animation: slideIn 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #aaa;
    z-index: 10;
    background: rgba(255,255,255,0.9);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    color: var(--primary);
    background: rgba(255,255,255,1);
}

.modal-body {
    padding: 30px;
}

/* Estilos para el modal de producto */
.modal-product {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.modal-product-image img {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.modal-product-info h2 {
    color: var(--primary);
    margin-bottom: 15px;
    font-family: 'Playfair Display', serif;
}

.modal-product-meta {
    margin-bottom: 20px;
}

.modal-tag {
    display: inline-block;
    background: var(--secondary);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    margin-right: 8px;
}

.modal-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary);
    margin-bottom: 20px;
}

.modal-price-old {
    color: #999;
    text-decoration: line-through;
    margin-bottom: 5px;
}

.modal-price span {
    color: var(--accent);
}

.modal-product-description h4 {
    color: var(--primary);
    margin-bottom: 10px;
}

.modal-product-description p {
    line-height: 1.6;
    color: #666;
}

.modal-product-actions {
    margin-top: 25px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.modal-error {
    text-align: center;
    padding: 30px;
}

.modal-error i {
    font-size: 3rem;
    color: #ff6b6b;
    margin-bottom: 15px;
}

.modal-error h3 {
    color: var(--primary);
    margin-bottom: 10px;
}

.btn-primary {
    background-color: var(--primary);
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background-color: #0a1429;
}

.btn-disabled {
    background-color: #ccc;
    color: #666;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: not-allowed;
    text-decoration: none;
    display: inline-block;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-form {
        grid-template-columns: 1fr;
    }
    
    .filter-toggle {
        display: block;
    }
    
    .filter-form {
        display: none;
    }
    
    .filter-form.active {
        display: grid;
    }
    
    .catalog-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .pagination {
        gap: 5px;
    }
    
    .page-link {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
    
    .modal-product {
        grid-template-columns: 1fr;
    }
    
    .modal-product-actions {
        flex-direction: column;
    }
    
    .modal-product-actions .btn {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .section-title {
        font-size: 2rem;
    }
    
    .catalog-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        margin: 5% auto;
        width: 98%;
    }
    
    .modal-body {
        padding: 20px;
    }
}
</style>

<script>
// Toggle para filtros en móviles
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterForm = document.getElementById('filterForm');
    
    if (filterToggle && filterForm) {
        filterToggle.addEventListener('click', function() {
            filterForm.classList.toggle('active');
            filterToggle.textContent = filterForm.classList.contains('active') ? 'Filtros ▴' : 'Filtros ▾';
        });
    }
});

// Función para abrir modal de detalle rápido
function abrirModalDetalle(perfumeId) {
    // Mostrar loader mientras carga
    document.getElementById('modalContent').innerHTML = `
        <div class="modal-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Cargando información del producto...</p>
        </div>
    `;
    document.getElementById('modalDetalle').style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    fetch(`api/perfumes.php?id=${perfumeId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los detalles');
            }
            return response.json();
        })
        .then(data => {
            let precioHTML = '';
            if (data.precio_oferta > 0 && data.precio_oferta < data.precio) {
                precioHTML = `
                    <p class="modal-price-old">Precio normal: <s>$${parseFloat(data.precio).toFixed(2)}</s></p>
                    <p class="modal-price">Precio especial: <span>$${parseFloat(data.precio_oferta).toFixed(2)}</span></p>
                `;
            } else {
                precioHTML = `<p class="modal-price">Precio: <span>$${parseFloat(data.precio).toFixed(2)}</span></p>`;
            }
            
            document.getElementById('modalContent').innerHTML = `
                <div class="modal-product">
                    <div class="modal-product-image">
                        <img src="${data.imagen}" alt="${data.nombre}">
                    </div>
                    <div class="modal-product-info">
                        <h2>${data.nombre}</h2>
                        <div class="modal-product-meta">
                            ${data.genero ? `<span class="modal-tag">${data.genero}</span>` : ''}
                            ${data.categoria_nombre ? `<span class="modal-tag">${data.categoria_nombre}</span>` : ''}
                        </div>
                        ${precioHTML}
                        <div class="modal-product-description">
                            <h4>Descripción</h4>
                            <p>${data.descripcion || 'No hay descripción disponible.'}</p>
                        </div>
                        <div class="modal-product-actions">
                            <a href="perfume.php?id=${data.id}" class="btn btn-details">Ver detalles completos</a>
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                            <button class="btn btn-primary" onclick="agregarAlCarrito(${data.id})">
                                <i class="fas fa-calendar-check"></i> Apartar ahora
                            </button>
                            <?php else: ?>
                            <a href="login.php" class="btn btn-primary">
                                <i class="fas fa-user"></i> Iniciar sesión para apartar
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = `
                <div class="modal-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Error al cargar los detalles</h3>
                    <p>No pudimos cargar la información del producto. Por favor, intenta nuevamente.</p>
                    <button onclick="cerrarModal()" class="btn btn-primary">Cerrar</button>
                </div>
            `;
        });
}

// Función para cerrar modal
function cerrarModal() {
    document.getElementById('modalDetalle').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Cerrar modal
document.querySelector('.modal-close').addEventListener('click', cerrarModal);

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function(event) {
    if (event.target == document.getElementById('modalDetalle')) {
        cerrarModal();
    }
});

// Cerrar modal con ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cerrarModal();
    }
});

// Función para agregar al carrito
function agregarAlCarrito(perfumeId) {
    // Redirigir a la página de apartado
    window.location.href = `procesar_solicitud.php?perfume_id=${perfumeId}`;
}
</script>