<?php
// admin/dashboard.php
require_once '../check_session.php';

// Verificar que el usuario sea administrador
if ($_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            background: var(--primary);
            color: var(--text-light);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .admin-header h1 {
            display: flex;
            align-items: center;
            gap: 15px;
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
        }
        
        .stat-card i {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .admin-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .admin-btn:hover {
            transform: translateY(-5px);
        }
        
        .admin-btn i {
            font-size: 30px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .user-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid var(--accent);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        </div>
        
        <div class="user-info">
            <p><strong>Información de sesión:</strong> 
               ID: <?php echo $_SESSION['usuario_id']; ?> | 
               Email: <?php echo $_SESSION['usuario_email']; ?> | 
               Rol: <?php echo $_SESSION['rol']; ?>
            </p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>150</h3>
                <p>Usuarios Registrados</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-shopping-cart"></i>
                <h3>89</h3>
                <p>Pedidos Hoy</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-wine-bottle"></i>
                <h3>56</h3>
                <p>Productos</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3>$2,580</h3>
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
            <a href="../logout.php" class="admin-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>
</body>
</html>