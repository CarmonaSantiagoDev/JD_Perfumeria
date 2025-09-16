# 🗄️ Guía de Implementación de Base de Datos MySQL para JD Perfumería

## 📋 **Índice**
1. [Estructura de la Base de Datos](#estructura-de-la-base-de-datos)
2. [Scripts SQL](#scripts-sql)
3. [Configuración de Conexión](#configuración-de-conexión)
4. [Implementación en PHP](#implementación-en-php)
5. [Migración del Sistema Actual](#migración-del-sistema-actual)
6. [Optimización y Seguridad](#optimización-y-seguridad)

---

## 🏗️ **Estructura de la Base de Datos**

### **Tabla: `usuarios`**
```sql
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    ciudad VARCHAR(50),
    codigo_postal VARCHAR(10),
    pais VARCHAR(50) DEFAULT 'México',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    activo BOOLEAN DEFAULT TRUE,
    rol ENUM('cliente', 'admin', 'vendedor') DEFAULT 'cliente'
);
```

### **Tabla: `categorias`**
```sql
CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    orden INT DEFAULT 0
);
```

### **Tabla: `marcas`**
```sql
CREATE TABLE marcas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    logo VARCHAR(255),
    pais_origen VARCHAR(50),
    activo BOOLEAN DEFAULT TRUE
);
```

### **Tabla: `perfumes`**
```sql
CREATE TABLE perfumes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    marca_id INT NOT NULL,
    categoria_id INT NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2),
    stock INT DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    volumen VARCHAR(20),
    tipo VARCHAR(50),
    popularidad INT DEFAULT 0,
    imagen_principal VARCHAR(255),
    imagenes_adicionales TEXT,
    notas_olfativas TEXT,
    genero ENUM('masculino', 'femenino', 'unisex'),
    temporada ENUM('primavera', 'verano', 'otoño', 'invierno', 'todo_el_año'),
    activo BOOLEAN DEFAULT TRUE,
    destacado BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (marca_id) REFERENCES marcas(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

### **Tabla: `carrito`**
```sql
CREATE TABLE carrito (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    perfume_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id)
);
```

### **Tabla: `favoritos`**
```sql
CREATE TABLE favoritos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    perfume_id INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id),
    UNIQUE KEY unique_favorito (usuario_id, perfume_id)
);
```

### **Tabla: `pedidos`**
```sql
CREATE TABLE pedidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_pedido VARCHAR(20) UNIQUE NOT NULL,
    usuario_id INT NOT NULL,
    estado ENUM('pendiente', 'procesando', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    subtotal DECIMAL(10,2) NOT NULL,
    impuestos DECIMAL(10,2) DEFAULT 0,
    envio DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50),
    direccion_envio TEXT,
    notas TEXT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_envio TIMESTAMP NULL,
    fecha_entrega TIMESTAMP NULL,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
```

### **Tabla: `detalles_pedido`**
```sql
CREATE TABLE detalles_pedido (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pedido_id INT NOT NULL,
    perfume_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id)
);
```

### **Tabla: `reseñas`**
```sql
CREATE TABLE reseñas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    perfume_id INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion >= 1 AND calificacion <= 5),
    comentario TEXT,
    fecha_reseña TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    aprobada BOOLEAN DEFAULT FALSE,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id)
);
```

---

## 📜 **Scripts SQL**

### **1. Crear Base de Datos**
```sql
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS jd_perfumeria
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE jd_perfumeria;
```

### **2. Insertar Datos de Ejemplo**

#### **Categorías**
```sql
INSERT INTO categorias (nombre, descripcion, orden) VALUES
('Masculino', 'Fragancias diseñadas específicamente para hombres', 1),
('Femenino', 'Fragancias diseñadas específicamente para mujeres', 2),
('Unisex', 'Fragancias que pueden ser usadas por cualquier género', 3),
('Nicho', 'Fragancias exclusivas y artesanales', 4),
('Lujo', 'Fragancias de alta gama y marcas premium', 5);
```

#### **Marcas**
```sql
INSERT INTO marcas (nombre, descripcion, pais_origen) VALUES
('Chanel', 'Casa de moda francesa fundada por Coco Chanel', 'Francia'),
('Dior', 'Luxury goods company fundada por Christian Dior', 'Francia'),
('Tom Ford', 'Marca de lujo fundada por el diseñador Tom Ford', 'Estados Unidos'),
('Jo Malone', 'Marca británica de fragancias y velas', 'Reino Unido'),
('Byredo', 'Marca sueca de fragancias de nicho', 'Suecia'),
('Le Labo', 'Marca de fragancias artesanales', 'Estados Unidos'),
('Maison Margiela', 'Casa de moda francesa', 'Francia'),
('Yves Saint Laurent', 'Casa de moda francesa', 'Francia');
```

#### **Perfumes de Ejemplo**
```sql
INSERT INTO perfumes (nombre, marca_id, categoria_id, descripcion, precio, stock, volumen, tipo, genero, temporada, popularidad, destacado) VALUES
('Bleu de Chanel', 1, 1, 'Una fragancia masculina sofisticada con notas de cítricos y maderas', 125.00, 25, '100ml', 'Eau de Parfum', 'masculino', 'todo_el_año', 5, TRUE),
('J\'adore', 2, 2, 'Una fragancia femenina floral y frutal', 145.00, 30, '100ml', 'Eau de Parfum', 'femenino', 'primavera', 5, TRUE),
('Black Orchid', 3, 2, 'Una fragancia oriental misteriosa y seductora', 165.00, 15, '100ml', 'Eau de Parfum', 'femenino', 'otoño', 4, TRUE),
('Wood Sage & Sea Salt', 4, 3, 'Una fragancia fresca y natural', 95.00, 20, '100ml', 'Cologne', 'unisex', 'verano', 4, FALSE),
('Gypsy Water', 5, 4, 'Una fragancia de nicho con notas de bergamota y vainilla', 185.00, 10, '100ml', 'Eau de Parfum', 'unisex', 'todo_el_año', 5, TRUE);
```

---

## 🔌 **Configuración de Conexión**

### **Archivo: `config/database.php`**
```php
<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'jd_perfumeria';
    private $username = 'tu_usuario';
    private $password = 'tu_password';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
```

---

## 🐘 **Implementación en PHP**

### **Archivo: `models/Perfume.php`**
```php
<?php
class Perfume {
    private $conn;
    private $table_name = "perfumes";
    
    public $id;
    public $nombre;
    public $marca_id;
    public $categoria_id;
    public $descripcion;
    public $precio;
    public $stock;
    public $volumen;
    public $tipo;
    public $genero;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Obtener todos los perfumes
    public function getAll($limit = null, $offset = null) {
        $query = "SELECT p.*, m.nombre as marca_nombre, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN marcas m ON p.marca_id = m.id
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.activo = 1
                  ORDER BY p.destacado DESC, p.popularidad DESC";
        
        if ($limit) {
            $query .= " LIMIT " . $limit;
            if ($offset) {
                $query .= " OFFSET " . $offset;
            }
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Obtener perfumes por categoría
    public function getByCategory($categoria_id) {
        $query = "SELECT p.*, m.nombre as marca_nombre, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN marcas m ON p.marca_id = m.id
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.categoria_id = ? AND p.activo = 1
                  ORDER BY p.popularidad DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $categoria_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Buscar perfumes
    public function search($search_term) {
        $query = "SELECT p.*, m.nombre as marca_nombre, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN marcas m ON p.marca_id = m.id
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE (p.nombre LIKE ? OR m.nombre LIKE ? OR p.descripcion LIKE ?) 
                  AND p.activo = 1
                  ORDER BY p.popularidad DESC";
        
        $search_term = "%{$search_term}%";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $search_term);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Obtener perfume por ID
    public function getById($id) {
        $query = "SELECT p.*, m.nombre as marca_nombre, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN marcas m ON p.marca_id = m.id
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = ? AND p.activo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
}
?>
```

### **Archivo: `api/perfumes.php`**
```php
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../models/Perfume.php';

$database = new Database();
$db = $database->getConnection();
$perfume = new Perfume($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener perfume específico
            $result = $perfume->getById($_GET['id']);
            echo json_encode($result);
        } elseif (isset($_GET['categoria'])) {
            // Obtener perfumes por categoría
            $stmt = $perfume->getByCategory($_GET['categoria']);
            $perfumes = $stmt->fetchAll();
            echo json_encode($perfumes);
        } elseif (isset($_GET['buscar'])) {
            // Buscar perfumes
            $stmt = $perfume->search($_GET['buscar']);
            $perfumes = $stmt->fetchAll();
            echo json_encode($perfumes);
        } else {
            // Obtener todos los perfumes
            $stmt = $perfume->getAll();
            $perfumes = $stmt->fetchAll();
            echo json_encode($perfumes);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
?>
```

---

## 🔄 **Migración del Sistema Actual**

### **1. Actualizar JavaScript para usar API**
```javascript
// Reemplazar la función mostrarCatalogo() actual
async function mostrarCatalogo(perfumesAMostrar = null) {
    try {
        const catalogo = document.getElementById("catalogo");
        if (!catalogo) return;
        
        catalogo.innerHTML = "";
        
        let perfumes;
        if (perfumesAMostrar) {
            perfumes = perfumesAMostrar;
        } else {
            // Obtener perfumes desde la API
            const response = await fetch('api/perfumes.php');
            perfumes = await response.json();
        }
        
        if (perfumes.length === 0) {
            catalogo.innerHTML = `
                <div class="catalogo-vacio">
                    <h3>No se encontraron perfumes</h3>
                    <p>Intenta ajustar los filtros de búsqueda</p>
                    <button onclick="limpiarFiltros()" class="btn-limpiar-filtros">Limpiar filtros</button>
                </div>
            `;
            return;
        }
        
        // Resto del código de renderizado...
        perfumes.forEach((perfume, index) => {
            // Crear cards con los datos de la API
        });
        
    } catch (error) {
        console.error("Error al cargar el catálogo:", error);
        mostrarNotificacion("Error al cargar el catálogo", "error");
    }
}
```

### **2. Crear Sistema de Autenticación**
```php
<?php
// Archivo: auth/login.php
session_start();

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Verificar credenciales en la base de datos
    $query = "SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = ? AND activo = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    
    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        
        echo json_encode(['success' => true, 'redirect' => 'usuario.html']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
    }
}
?>
```

---

## 🚀 **Optimización y Seguridad**

### **1. Índices para Mejor Rendimiento**
```sql
-- Índices para búsquedas rápidas
CREATE INDEX idx_perfumes_nombre ON perfumes(nombre);
CREATE INDEX idx_perfumes_marca ON perfumes(marca_id);
CREATE INDEX idx_perfumes_categoria ON perfumes(categoria_id);
CREATE INDEX idx_perfumes_precio ON perfumes(precio);
CREATE INDEX idx_perfumes_stock ON perfumes(stock);
CREATE INDEX idx_perfumes_activo ON perfumes(activo);

-- Índices para relaciones
CREATE INDEX idx_carrito_usuario ON carrito(usuario_id);
CREATE INDEX idx_favoritos_usuario ON favoritos(usuario_id);
CREATE INDEX idx_pedidos_usuario ON pedidos(usuario_id);
```

### **2. Configuración de Seguridad**
```php
<?php
// Archivo: config/security.php
class Security {
    // Sanitizar inputs
    public static function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    // Validar email
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    // Generar token CSRF
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    // Verificar token CSRF
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
```

### **3. Archivo .htaccess para Seguridad**
```apache
# Archivo: .htaccess
RewriteEngine On

# Proteger archivos sensibles
<Files "*.php">
    Order Allow,Deny
    Allow from all
</Files>

# Bloquear acceso a archivos de configuración
<Files "config/*">
    Order Deny,Allow
    Deny from all
</Files>

# Headers de seguridad
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Comprimir archivos
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

---

## 📱 **Panel de Administración**

### **Archivo: `admin/index.php`**
```php
<?php
session_start();
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - JD Perfumería</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Panel de Administración</h2>
            <ul>
                <li><a href="#dashboard">Dashboard</a></li>
                <li><a href="#perfumes">Gestionar Perfumes</a></li>
                <li><a href="#usuarios">Gestionar Usuarios</a></li>
                <li><a href="#pedidos">Ver Pedidos</a></li>
                <li><a href="#estadisticas">Estadísticas</a></li>
            </ul>
        </nav>
        
        <main class="admin-content">
            <div id="dashboard" class="admin-section">
                <h1>Dashboard</h1>
                <!-- Contenido del dashboard -->
            </div>
        </main>
    </div>
    
    <script src="js/admin.js"></script>
</body>
</html>
```

---

## 🔧 **Pasos para Implementar**

### **1. Preparación**
- [ ] Instalar XAMPP/WAMP/MAMP
- [ ] Crear la base de datos MySQL
- [ ] Configurar usuario y contraseña de la base de datos

### **2. Implementación de la Base de Datos**
- [ ] Ejecutar scripts SQL para crear tablas
- [ ] Insertar datos de ejemplo
- [ ] Crear índices para optimización

### **3. Desarrollo del Backend**
- [ ] Crear archivos de configuración
- [ ] Implementar modelos de datos
- [ ] Crear APIs REST
- [ ] Implementar sistema de autenticación

### **4. Migración del Frontend**
- [ ] Actualizar JavaScript para usar APIs
- [ ] Implementar sistema de login/registro
- [ ] Crear panel de administración
- [ ] Actualizar funcionalidades del carrito

### **5. Pruebas y Optimización**
- [ ] Probar todas las funcionalidades
- [ ] Optimizar consultas SQL
- [ ] Implementar caché
- [ ] Configurar seguridad

---

## 📚 **Recursos Adicionales**

- **Documentación MySQL**: https://dev.mysql.com/doc/
- **Documentación PHP PDO**: https://www.php.net/manual/es/book.pdo.php
- **Buenas Prácticas de Seguridad**: https://owasp.org/
- **Optimización de MySQL**: https://dev.mysql.com/doc/refman/8.0/en/optimization.html

---

## 🆘 **Soporte**

Si tienes alguna pregunta o necesitas ayuda con la implementación, puedes:

1. Revisar la documentación oficial de MySQL y PHP
2. Consultar foros de desarrollo web
3. Contactar con un desarrollador experimentado en bases de datos

---

**¡Con esta implementación tendrás un sistema robusto y escalable para tu perfumería!** 🎉
