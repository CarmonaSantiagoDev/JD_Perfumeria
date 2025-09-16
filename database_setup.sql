-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS jd_perfumeria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jd_perfumeria;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    color VARCHAR(7) DEFAULT '#c19b2e',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de marcas
CREATE TABLE IF NOT EXISTS marcas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    logo VARCHAR(255),
    pais_origen VARCHAR(100),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de perfumes
CREATE TABLE IF NOT EXISTS perfumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    marca_id INT NOT NULL,
    categoria_id INT NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    volumen VARCHAR(50),
    tipo VARCHAR(100),
    genero ENUM('masculino', 'femenino', 'unisex') DEFAULT 'unisex',
    stock INT DEFAULT 0,
    imagen VARCHAR(255),
    destacado BOOLEAN DEFAULT FALSE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (marca_id) REFERENCES marcas(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(10),
    pais VARCHAR(100) DEFAULT 'México',
    rol ENUM('cliente', 'admin') DEFAULT 'cliente',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de carrito
CREATE TABLE IF NOT EXISTS carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    perfume_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id) ON DELETE CASCADE
);

-- Tabla de favoritos
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    perfume_id INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito (usuario_id, perfume_id)
);

-- Tabla de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    metodo_pago VARCHAR(50),
    direccion_envio TEXT,
    ciudad_envio VARCHAR(100),
    codigo_postal_envio VARCHAR(10),
    telefono_envio VARCHAR(20),
    notas TEXT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de detalles del pedido
CREATE TABLE IF NOT EXISTS detalles_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    perfume_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id) ON DELETE CASCADE
);

-- Tabla de reseñas
CREATE TABLE IF NOT EXISTS reseñas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    perfume_id INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion >= 1 AND calificacion <= 5),
    comentario TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (perfume_id) REFERENCES perfumes(id) ON DELETE CASCADE
);

-- Insertar datos de ejemplo

-- Categorías
INSERT INTO categorias (nombre, descripcion, color) VALUES
('Masculino', 'Perfumes para hombres', '#4a90e2'),
('Femenino', 'Perfumes para mujeres', '#e91e63'),
('Unisex', 'Perfumes para ambos géneros', '#9c27b0'),
('Familiar', 'Perfumes para toda la familia', '#ff9800'),
('Ocasional', 'Perfumes para ocasiones especiales', '#f44336');

-- Marcas
INSERT INTO marcas (nombre, descripcion, pais_origen) VALUES
('Chanel', 'Luxury French fashion house', 'Francia'),
('Dior', 'French luxury goods company', 'Francia'),
('Versace', 'Italian luxury fashion company', 'Italia'),
('Calvin Klein', 'American fashion house', 'Estados Unidos'),
('Hugo Boss', 'German luxury fashion house', 'Alemania'),
('Tom Ford', 'American luxury fashion house', 'Estados Unidos'),
('Jo Malone', 'British luxury fragrance house', 'Reino Unido'),
('Yves Saint Laurent', 'French luxury fashion house', 'Francia');

-- Perfumes de ejemplo
INSERT INTO perfumes (nombre, marca_id, categoria_id, descripcion, precio, volumen, tipo, genero, stock, imagen) VALUES
('N°5', 1, 2, 'El perfume más famoso del mundo, una fragancia floral aldehídica', 2999.00, '100ml', 'Eau de Parfum', 'femenino', 15, 'img/perfumes/chanel-n5.jpg'),
('Bleu de Chanel', 1, 1, 'Una fragancia masculina moderna y sofisticada', 2499.00, '100ml', 'Eau de Parfum', 'masculino', 12, 'img/perfumes/bleu-chanel.jpg'),
('Sauvage', 2, 1, 'Una fragancia fresca y salvaje para el hombre moderno', 1899.00, '100ml', 'Eau de Toilette', 'masculino', 20, 'img/perfumes/dior-sauvage.jpg'),
('J\'adore', 2, 2, 'Una fragancia floral frutal para la mujer sensual', 2199.00, '100ml', 'Eau de Parfum', 'femenino', 18, 'img/perfumes/dior-jadore.jpg'),
('Eros', 3, 1, 'Una fragancia masculina intensa y seductora', 1599.00, '100ml', 'Eau de Toilette', 'masculino', 25, 'img/perfumes/versace-eros.jpg'),
('Bright Crystal', 3, 2, 'Una fragancia femenina fresca y luminosa', 1399.00, '100ml', 'Eau de Toilette', 'femenino', 22, 'img/perfumes/versace-bright-crystal.jpg'),
('CK One', 4, 3, 'Una fragancia unisex fresca y minimalista', 899.00, '100ml', 'Eau de Toilette', 'unisex', 30, 'img/perfumes/ck-one.jpg'),
('Boss Bottled', 5, 1, 'Una fragancia masculina elegante y profesional', 1299.00, '100ml', 'Eau de Toilette', 'masculino', 16, 'img/perfumes/boss-bottled.jpg'),
('Black Orchid', 6, 3, 'Una fragancia unisex misteriosa y exótica', 3499.00, '100ml', 'Eau de Parfum', 'unisex', 8, 'img/perfumes/tom-ford-black-orchid.jpg'),
('Wood Sage & Sea Salt', 7, 3, 'Una fragancia unisex fresca y natural', 1899.00, '100ml', 'Eau de Cologne', 'unisex', 14, 'img/perfumes/jo-malone-wood-sage.jpg');

-- Crear índices para optimizar consultas
CREATE INDEX idx_perfumes_categoria ON perfumes(categoria_id);
CREATE INDEX idx_perfumes_marca ON perfumes(marca_id);
CREATE INDEX idx_perfumes_genero ON perfumes(genero);
CREATE INDEX idx_perfumes_activo ON perfumes(activo);
CREATE INDEX idx_perfumes_destacado ON perfumes(destacado);
CREATE INDEX idx_carrito_usuario ON carrito(usuario_id);
CREATE INDEX idx_favoritos_usuario ON favoritos(usuario_id);
CREATE INDEX idx_pedidos_usuario ON pedidos(usuario_id);
CREATE INDEX idx_pedidos_estado ON pedidos(estado);
CREATE INDEX idx_reseñas_perfume ON reseñas(perfume_id);
CREATE INDEX idx_reseñas_usuario ON reseñas(usuario_id);
