<?php
// Configuración de la base de datos JD Perfumería
// Modifica estos valores según tu configuración de WAMP

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'jd_perfumeria');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'JD Perfumería');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/JD_Perfumeria');

// Configuración de seguridad
define('JWT_SECRET', 'tu_clave_secreta_aqui_cambiala_en_produccion');
define('SESSION_TIMEOUT', 3600); // 1 hora

// Configuración de archivos
define('UPLOAD_DIR', '../img/perfumes/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

// Configuración de correo (para futuras funcionalidades)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'tu_email@gmail.com');
define('SMTP_PASS', 'tu_password_de_aplicacion');

// Configuración de paginación
define('ITEMS_PER_PAGE', 12);

// Configuración de caché
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 300); // 5 minutos

// Función para obtener la configuración de la base de datos
function getDatabaseConfig() {
    return [
        'host' => DB_HOST,
        'dbname' => DB_NAME,
        'username' => DB_USER,
        'password' => DB_PASS,
        'charset' => DB_CHARSET
    ];
}

// Función para verificar la conexión a la base de datos
function testDatabaseConnection() {
    try {
        $config = getDatabaseConfig();
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return [
            'success' => true,
            'message' => 'Conexión exitosa a la base de datos',
            'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Error de conexión: ' . $e->getMessage()
        ];
    }
}

// Función para obtener información del sistema
function getSystemInfo() {
    return [
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
        'database_config' => getDatabaseConfig(),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_execution_time' => ini_get('max_execution_time'),
        'memory_limit' => ini_get('memory_limit')
    ];
}
?>
