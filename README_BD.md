# 🗄️ Implementación de Base de Datos - JD Perfumería

## 📋 Resumen

Este documento describe la implementación completa de la base de datos MySQL para el sitio web de JD Perfumería, incluyendo la migración desde datos locales a una base de datos relacional.

## 🚀 Características Implementadas

### ✅ Base de Datos
- **MySQL** con estructura relacional completa
- **8 tablas** principales: usuarios, categorías, marcas, perfumes, carrito, favoritos, pedidos, reseñas
- **Relaciones** con claves foráneas y restricciones de integridad
- **Índices** para optimizar consultas
- **Datos de ejemplo** incluidos

### ✅ Backend PHP
- **Clase Database** para conexiones PDO seguras
- **Clase Perfume** para operaciones CRUD
- **API RESTful** para comunicación frontend-backend
- **Manejo de errores** robusto
- **Configuración centralizada**

### ✅ Frontend JavaScript
- **Carga asíncrona** de datos desde la API
- **Sistema de respaldo** si la BD falla
- **Loading states** para mejor UX
- **Manejo de errores** en la interfaz

## 🛠️ Instalación y Configuración

### 1. Preparar el Entorno
```bash
# Asegúrate de tener WAMP/XAMPP funcionando
# PHP 7.4+ y MySQL 5.7+ recomendados
```

### 2. Crear la Base de Datos
```sql
-- Opción 1: Usar phpMyAdmin
-- 1. Abre http://localhost/phpmyadmin
-- 2. Crea una nueva base de datos llamada "jd_perfumeria"
-- 3. Selecciona la base de datos
-- 4. Ve a la pestaña "SQL"
-- 5. Copia y pega el contenido de database_setup.sql

-- Opción 2: Línea de comandos
mysql -u root -p < database_setup.sql
```

### 3. Configurar Credenciales
```php
// Edita config/config.php
define('DB_HOST', 'localhost');        // Tu host MySQL
define('DB_NAME', 'jd_perfumeria');    // Nombre de tu BD
define('DB_USER', 'root');             // Tu usuario MySQL
define('DB_PASS', '');                 // Tu password MySQL
```

### 4. Verificar Instalación
```bash
# Accede a: http://localhost/JD_Perfumeria/test_db.php
# Deberías ver todos los checks en verde ✅
```

## 📁 Estructura de Archivos

```
JD_Perfumeria/
├── 📁 config/
│   ├── config.php          # Configuración centralizada
│   └── database.php        # Clase de conexión BD
├── 📁 models/
│   └── Perfume.php         # Modelo de datos
├── 📁 api/
│   └── perfumes.php        # Endpoint API
├── 📁 css/
│   └── style.css           # Estilos (incluye loading)
├── 📁 js/
│   └── script.js           # Frontend (actualizado para BD)
├── database_setup.sql      # Script de creación BD
├── .htaccess               # Configuración servidor
├── test_db.php            # Archivo de pruebas
└── README_BD.md           # Este archivo
```

## 🔌 API Endpoints

### GET /api/perfumes.php
Obtiene todos los perfumes
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "N°5",
      "marca": "Chanel",
      "precio": "2999.00",
      "categoria": "femenino",
      "stock": 15,
      "imagen": "img/perfumes/chanel-n5.jpg"
    }
  ]
}
```

### GET /api/perfumes.php?id=1
Obtiene un perfume específico
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nombre": "N°5",
    "marca": "Chanel",
    "precio": "2999.00",
    "categoria": "femenino",
    "stock": 15,
    "imagen": "img/perfumes/chanel-n5.jpg",
    "descripcion": "El perfume más famoso del mundo...",
    "volumen": "100ml",
    "tipo": "Eau de Parfum"
  }
}
```

### GET /api/perfumes.php?categoria=1
Filtra por categoría
### GET /api/perfumes.php?search=chanel
Busca por nombre, marca o descripción

## 🎯 Funcionalidades del Frontend

### Carga de Datos
- **Automática**: Al cargar la página
- **Asíncrona**: Sin bloquear la interfaz
- **Con respaldo**: Si la BD falla, usa datos locales
- **Loading states**: Spinner mientras carga

### Filtros y Búsqueda
- **Por categoría**: Masculino, Femenino, Unisex
- **Por precio**: Ascendente/Descendente
- **Por stock**: Más disponible
- **Búsqueda**: Nombre, marca, descripción

### Carrito y Favoritos
- **Persistente**: Guardado en localStorage
- **Sincronizado**: Con la base de datos
- **Validación**: Stock disponible

## 🔧 Personalización

### Agregar Nuevos Perfumes
```sql
INSERT INTO perfumes (nombre, marca_id, categoria_id, descripcion, precio, volumen, tipo, genero, stock, imagen) 
VALUES ('Nuevo Perfume', 1, 2, 'Descripción...', 1999.00, '100ml', 'Eau de Parfum', 'femenino', 20, 'img/perfumes/nuevo.jpg');
```

### Modificar Categorías
```sql
UPDATE categorias SET nombre = 'Nueva Categoría', color = '#ff0000' WHERE id = 1;
```

### Agregar Marcas
```sql
INSERT INTO marcas (nombre, descripcion, pais_origen) 
VALUES ('Nueva Marca', 'Descripción de la marca', 'País');
```

## 🚨 Solución de Problemas

### Error de Conexión
```bash
# Verifica en test_db.php:
1. Credenciales correctas en config/config.php
2. MySQL funcionando en WAMP
3. Base de datos "jd_perfumeria" creada
4. Usuario tiene permisos
```

### API No Responde
```bash
# Verifica:
1. Archivo .htaccess presente
2. Módulo rewrite habilitado en Apache
3. PHP funcionando correctamente
4. Permisos de archivos correctos
```

### Datos No Se Cargan
```bash
# Verifica en consola del navegador:
1. No hay errores JavaScript
2. La API responde correctamente
3. Los datos tienen el formato esperado
4. Las imágenes existen en img/perfumes/
```

## 📊 Monitoreo y Mantenimiento

### Logs de Error
```php
// Los errores se registran en el log de PHP
// Revisa: WAMP/logs/php_error.log
```

### Rendimiento
```sql
-- Verificar consultas lentas
EXPLAIN SELECT * FROM perfumes WHERE categoria_id = 1;

-- Verificar índices
SHOW INDEX FROM perfumes;
```

### Backup
```bash
# Backup manual
mysqldump -u root -p jd_perfumeria > backup_$(date +%Y%m%d).sql

# Restaurar
mysql -u root -p jd_perfumeria < backup_20241201.sql
```

## 🔮 Próximas Mejoras

### Funcionalidades Planificadas
- [ ] **Sistema de usuarios** con login/registro
- [ ] **Panel de administración** para gestionar catálogo
- [ ] **Sistema de pedidos** completo
- [ ] **Reseñas y calificaciones** de usuarios
- [ ] **Sistema de notificaciones** por email
- [ ] **Caché Redis** para mejor rendimiento
- [ ] **API con autenticación** JWT
- [ ] **Subida de imágenes** desde admin panel

### Optimizaciones Técnicas
- [ ] **Paginación** para catálogos grandes
- [ ] **Lazy loading** de imágenes
- [ ] **Compresión** de respuestas API
- [ ] **CDN** para imágenes estáticas
- [ ] **Monitoreo** de rendimiento

## 📞 Soporte

### Recursos Útiles
- **Documentación MySQL**: https://dev.mysql.com/doc/
- **Documentación PHP**: https://www.php.net/docs.php
- **WAMP**: https://www.wampserver.com/
- **phpMyAdmin**: https://www.phpmyadmin.net/

### Comandos Útiles
```bash
# Verificar estado de servicios WAMP
# (Icono WAMP en bandeja del sistema)

# Reiniciar servicios
# Click derecho en icono WAMP → Restart All Services

# Ver logs de Apache
# WAMP/logs/apache_error.log

# Ver logs de MySQL
# WAMP/logs/mysql.log
```

## 🎉 ¡Listo!

Tu sitio web de JD Perfumería ahora tiene:
- ✅ **Base de datos MySQL** completa y funcional
- ✅ **API RESTful** para comunicación frontend-backend
- ✅ **Sistema de respaldo** para mayor confiabilidad
- ✅ **Configuración centralizada** fácil de mantener
- ✅ **Archivos de prueba** para verificar funcionamiento

¡Disfruta de tu nueva infraestructura de base de datos! 🚀
