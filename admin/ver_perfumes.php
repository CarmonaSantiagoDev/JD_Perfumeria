<?php
// admin/ver_perfumes.php
require_once '../check_session.php';

if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Obtener productos con información de categorías y marcas
$productos = [];
if (!$conn->connect_error) {
    $query = "SELECT p.*, c.nombre as categoria_nombre, m.nombre as marca_nombre 
              FROM perfumes p 
              LEFT JOIN categorias c ON p.categoria_id = c.id 
              LEFT JOIN marcas m ON p.marca_id = m.id 
              ORDER BY p.fecha_creacion DESC";
    
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos - JD Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #7E57C2;
            --primary-light: #9575CD;
            --primary-dark: #5E35B1;
            --secondary: #FF4081;
            --dark: #37474F;
            --light: #F5F5F5;
            --gray: #90A4AE;
            --success: #4CAF50;
            --warning: #FFC107;
            --danger: #F44336;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .page-header h1 {
            color: var(--primary);
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--secondary);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
        }
        
        .product-image {
            height: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 50px;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
            line-height: 1.3;
        }
        
        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .product-category, .product-brand {
            background: #e2e8f0;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .product-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .product-description {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-stock {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 15px;
        }
        
        .stock-low {
            color: var(--danger);
            font-weight: 600;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-edit, .btn-delete {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-edit {
            background: #3b82f6;
            color: white;
        }
        
        .btn-edit:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        
        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
        
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .empty-state i {
            font-size: 70px;
            color: #cbd5e0;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #64748b;
            margin-bottom: 10px;
            font-size: 22px;
        }
        
        .empty-state p {
            color: #94a3b8;
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .admin-container {
                padding: 15px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .product-actions {
                flex-direction: column;
            }
            
            .btn-edit, .btn-delete {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="admin-container">
        <div class="page-header">
            <h1><i class="fas fa-cubes"></i> Gestionar Productos</h1>
            <a href="agregar_perfume.php" class="btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>
        
        <div class="products-grid">
            <?php if (count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                <div class="product-card">
                    <?php if ($producto['destacado'] == 1): ?>
                    <div class="product-badge">Destacado</div>
                    <?php endif; ?>
                    
                    <div class="product-image">
                        <?php if (!empty($producto['imagen_principal'])): ?>
                            <img src="../<?php echo htmlspecialchars($producto['imagen_principal']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <?php else: ?>
                            <i class="fas fa-wine-bottle"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        
                        <div class="product-meta">
                            <span class="product-category">
                                <i class="fas fa-tag"></i> 
                                <?php echo !empty($producto['categoria_nombre']) ? htmlspecialchars($producto['categoria_nombre']) : 'Sin categoría'; ?>
                            </span>
                            <span class="product-brand">
                                <i class="fas fa-trademark"></i> 
                                <?php echo !empty($producto['marca_nombre']) ? htmlspecialchars($producto['marca_nombre']) : 'Sin marca'; ?>
                            </span>
                        </div>
                        
                        <div class="product-price">$<?php echo number_format($producto['precio'], 2); ?></div>
                        
                        <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        
                        <div class="product-stock <?php echo $producto['stock'] < $producto['stock_minimo'] ? 'stock-low' : ''; ?>">
                            <i class="fas fa-boxes"></i> 
                            Stock: <?php echo $producto['stock']; ?> unidades
                            <?php if ($producto['stock'] < $producto['stock_minimo']): ?>
                                <i class="fas fa-exclamation-triangle"></i>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-actions">
                            <a href="editar_perfume.php?id=<?php echo $producto['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="eliminar_perfume.php?id=<?php echo $producto['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No hay productos registrados</h3>
                    <p>Comienza agregando tu primer producto al catálogo</p>
                    <a href="agregar_perfume.php" class="btn-primary">
                        <i class="fas fa-plus"></i> Agregar Primer Producto
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>