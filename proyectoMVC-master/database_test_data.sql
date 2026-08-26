-- Script de Creación de Base de Datos y Datos de Prueba para AcademiaAds
-- Base de Datos: basemvc

CREATE DATABASE IF NOT EXISTS `basemvc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `basemvc`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `presentacion` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `presentacion`, `precio`, `id_tipo`) VALUES
(1, 'PROD-001', 'Leche Entera AcademiaAds', '1 Litro', 2500.00, 'Lácteos'),
(2, 'PROD-002', 'Queso Doble Crema', '500g', 8500.00, 'Lácteos'),
(3, 'PROD-003', 'Yogurt de Fresa', '1 Litro', 4200.00, 'Lácteos'),
(4, 'PROD-004', 'Jugo de Naranja Natural', '300ml', 1500.00, 'Bebidas'),
(5, 'PROD-005', 'Gaseosa Cola', '2 Litros', 5000.00, 'Bebidas'),
(6, 'PROD-006', 'Papas Fritas Sabor BBQ', 'Paca 12 Unidades', 12000.00, 'Fritos'),
(7, 'PROD-007', 'Platanitos Maduros Dulces', '150g', 2000.00, 'Fritos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` enum('administrador','usuario') NOT NULL DEFAULT 'usuario',
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `clave` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `perfil`, `nombre`, `correo`, `clave`) VALUES
(1, 'administrador', 'Admin AcademiaAds', 'admin@academiaads.com', 'admin123'),
(2, 'usuario', 'Usuario Prueba 1', 'user1@test.com', 'usuario123'),
(3, 'usuario', 'Usuario Prueba 2', 'user2@test.com', 'usuario123');

-- --------------------------------------------------------
-- Final del script
