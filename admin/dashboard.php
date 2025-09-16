<?php
// admin/dashboard.php (versión mejorada)
require_once '../check_session.php';

// Verificar que el usuario sea administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Conectar a la base de datos para obtener estadísticas reales
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jd_perfumeria";

$conn = new mysqli($servername, $username, $password, $dbname);

// Obtener estadísticas
$total_usuarios = 0;
$total_pedidos = 0;
$total_productos = 0;
$ingresos_hoy = 0;

if (!$conn->connect_error) {
    $total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];
    $total_pedidos = $conn->query("SELECT COUNT(*) as total FROM pedidos WHERE DATE(fecha_pedido) = CURDATE()")->fetch_assoc()['total'];
    $total_productos = $conn->query("SELECT COUNT(*) as total FROM perfumes")->fetch_assoc()['total'];
    $ingresos_result = $conn->query("SELECT SUM(total) as total FROM pedidos WHERE DATE(fecha_pedido) = CURDATE() AND estado = 'completado'");
    $ingresos_hoy = $ingresos_result->fetch_assoc()['total'] ?? 0;
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - JD Perfumería</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0b1736;
            --primary-light: #1e3a8a;
            --accent: #3b82f6;
            --text-light: #ffffff;
            --text-dark: #333333;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .admin-header {
            background: var(--primary);
            color: var(--text-light);
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .admin-header h1 {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .welcome-text {
            opacity: 0.9;
            font-size: 16px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card i {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 28px;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .stat-card p {
            color: #666;
            font-weight: 500;
        }
        
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .admin-btn {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid var(--accent);
        }
        
        .admin-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            background: #f8fafc;
        }
        
        .admin-btn i {
            font-size: 24px;
            color: var(--primary);
            width: 30px;
        }
        
        .admin-btn span {
            flex: 1;
            font-weight: 600;
        }
        
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            border-left: 4px solid var(--accent);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        
        .info-item {
            padding: 10px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .info-value {
            font-size: 16px;
            color: var(--primary);
            font-weight: 600;
        }
        
        .quick-stats {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        
        .quick-stats h3 {
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
            <p class="welcome-text">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $total_usuarios; ?></h3>
                <p>Usuarios Registrados</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-cart"></i>
                <h3><?php echo $total_pedidos; ?></h3>
                <p>Pedidos Hoy</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-wine-bottle"></i>
                <h3><?php echo $total_productos; ?></h3>
                <p>Productos</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3>$<?php echo number_format($ingresos_hoy, 2); ?></h3>
                <p>Ingresos Hoy</p>
            </div>
        </div>
        
        <div class="admin-actions">
            <a href="ver_perfumes.php" class="admin-btn">
                <i class="fas fa-cubes"></i>
                <span>Gestionar Productos</span>
            </a>
            <a href="ver_pedidos.php" class="admin-btn">
                <i class="fas fa-clipboard-list"></i>
                <span>Ver Pedidos</span>
            </a>
            <a href="agregar_perfume.php" class="admin-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Agregar Producto</span>
            </a>
            <a href="ver_usuarios.php" class="admin-btn">
                <i class="fas fa-user-cog"></i>
                <span>Gestionar Usuarios</span>
            </a>
            <a href="../catalogo.php" class="admin-btn">
                <i class="fas fa-store"></i>
                <span>Ver Catálogo</span>
            </a>
            <a href="../logout.php" class="admin-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
        
        <div class="user-info">
            <h3><i class="fas fa-info-circle"></i> Información de Sesión</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">ID de Usuario</div>
                    <div class="info-value">#<?php echo $_SESSION['usuario_id']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo $_SESSION['usuario_email']; ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Rol</div>
                    <div class="info-value"><?php echo ucfirst($_SESSION['rol']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Estado</div>
                    <div class="info-value" style="color: var(--success);">Conectado</div>
                </div>
            </div>
        </div>
        
        <div class="quick-stats">
            <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
            <div class="admin-actions">
                <a href="agregar_perfume.php" class="admin-btn">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo Producto</span>
                </a>
                <a href="ver_pedidos.php?estado=pendiente" class="admin-btn">
                    <i class="fas fa-clock"></i>
                    <span>Pedidos Pendientes</span>
                </a>
                <a href="ver_usuarios.php?rol=cliente" class="admin-btn">
                    <i class="fas fa-users"></i>
                    <span>Ver Clientes</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>