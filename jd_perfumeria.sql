-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 21, 2025 at 12:22 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jd_perfumeria`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE IF NOT EXISTS `carrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `perfume_id` int NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,2) NOT NULL,
  `fecha_agregado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `perfume_id` (`perfume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imagen`, `activo`, `orden`) VALUES
(1, 'Masculino', 'Fragancias diseñadas específicamente para hombres', NULL, 1, 1),
(2, 'Femenino', 'Fragancias diseñadas específicamente para mujeres', NULL, 1, 2),
(3, 'Unisex', 'Fragancias que pueden ser usadas por cualquier género', NULL, 1, 3),
(4, 'Nicho', 'Fragancias exclusivas y artesanales', NULL, 1, 4),
(5, 'Lujo', 'Fragancias de alta gama y marcas premium', NULL, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `detalles_pedido`
--

DROP TABLE IF EXISTS `detalles_pedido`;
CREATE TABLE IF NOT EXISTS `detalles_pedido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int NOT NULL,
  `perfume_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `perfume_id` (`perfume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `perfume_id` int NOT NULL,
  `fecha_agregado` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_favorito` (`usuario_id`,`perfume_id`),
  KEY `perfume_id` (`perfume_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pais_origen` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `descripcion`, `logo`, `pais_origen`, `activo`) VALUES
(1, 'Chanel', 'Casa de moda francesa fundada por Coco Chanel', NULL, 'Francia', 1),
(2, 'Dior', 'Luxury goods company fundada por Christian Dior', NULL, 'Francia', 1),
(3, 'Tom Ford', 'Marca de lujo fundada por el diseñador Tom Ford', NULL, 'Estados Unidos', 1),
(4, 'Jo Malone', 'Marca británica de fragancias y velas', NULL, 'Reino Unido', 1),
(5, 'Byredo', 'Marca sueca de fragancias de nicho', NULL, 'Suecia', 1),
(6, 'Le Labo', 'Marca de fragancias artesanales', NULL, 'Estados Unidos', 1),
(7, 'Maison Margiela', 'Casa de moda francesa', NULL, 'Francia', 1),
(8, 'Yves Saint Laurent', 'Casa de moda francesa', NULL, 'Francia', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_pedido` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` int NOT NULL,
  `estado` enum('pendiente','procesando','enviado','entregado','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `subtotal` decimal(10,2) NOT NULL,
  `impuestos` decimal(10,2) DEFAULT '0.00',
  `envio` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_envio` text COLLATE utf8mb4_unicode_ci,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `fecha_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_envio` timestamp NULL DEFAULT NULL,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_pedido` (`numero_pedido`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perfumes`
--

DROP TABLE IF EXISTS `perfumes`;
CREATE TABLE IF NOT EXISTS `perfumes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marca_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio` decimal(10,2) NOT NULL,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `stock_minimo` int DEFAULT '5',
  `volumen` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `popularidad` int DEFAULT '0',
  `imagen_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagenes_adicionales` text COLLATE utf8mb4_unicode_ci,
  `notas_olfativas` text COLLATE utf8mb4_unicode_ci,
  `genero` enum('masculino','femenino','unisex') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temporada` enum('primavera','verano','otoño','invierno','todo_el_año') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `destacado` tinyint(1) DEFAULT '0',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `marca_id` (`marca_id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perfumes`
--

INSERT INTO `perfumes` (`id`, `nombre`, `marca_id`, `categoria_id`, `descripcion`, `precio`, `precio_oferta`, `stock`, `stock_minimo`, `volumen`, `tipo`, `popularidad`, `imagen_principal`, `imagenes_adicionales`, `notas_olfativas`, `genero`, `temporada`, `activo`, `destacado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Bleu de Chanel', 1, 1, 'Una fragancia masculina sofisticada con notas de cítricos y maderas', 125.00, NULL, 25, 5, '100ml', 'Eau de Parfum', 5, NULL, NULL, NULL, 'masculino', 'todo_el_año', 1, 1, '2025-08-16 21:37:58', '2025-08-16 21:37:58'),
(2, 'J\'adore', 2, 2, 'Una fragancia femenina floral y frutal', 145.00, NULL, 30, 5, '100ml', 'Eau de Parfum', 5, NULL, NULL, NULL, 'femenino', 'primavera', 1, 1, '2025-08-16 21:37:58', '2025-08-16 21:37:58'),
(3, 'Black Orchid', 3, 2, 'Una fragancia oriental misteriosa y seductora', 165.00, NULL, 15, 5, '100ml', 'Eau de Parfum', 4, NULL, NULL, NULL, 'femenino', 'otoño', 1, 1, '2025-08-16 21:37:58', '2025-08-16 21:37:58'),
(4, 'Wood Sage & Sea Salt', 4, 3, 'Una fragancia fresca y natural', 95.00, NULL, 20, 5, '100ml', 'Cologne', 4, NULL, NULL, NULL, 'unisex', 'verano', 1, 0, '2025-08-16 21:37:58', '2025-08-16 21:37:58'),
(5, 'Gypsy Water', 5, 4, 'Una fragancia de nicho con notas de bergamota y vainilla', 185.00, NULL, 10, 5, '100ml', 'Eau de Parfum', 5, NULL, NULL, NULL, 'unisex', 'todo_el_año', 1, 1, '2025-08-16 21:37:58', '2025-08-16 21:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `reseñas`
--

DROP TABLE IF EXISTS `reseñas`;
CREATE TABLE IF NOT EXISTS `reseñas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `perfume_id` int NOT NULL,
  `calificacion` int NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `fecha_reseña` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `aprobada` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `perfume_id` (`perfume_id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `ciudad` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pais` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'México',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `rol` enum('cliente','admin','vendedor') COLLATE utf8mb4_unicode_ci DEFAULT 'cliente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
