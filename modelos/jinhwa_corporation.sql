-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-04-2026 a las 19:22:58
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jinhwa_corporation`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Infantil', NULL),
(2, 'Juvenil', NULL),
(3, 'Mayores', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `nombre`) VALUES
(1, 'Blanco'),
(2, 'Amarillo'),
(3, 'Verde'),
(4, 'Azul'),
(5, 'Rojo'),
(6, 'Negro 1er Dan');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros`
--

CREATE TABLE `miembros` (
  `id_miembro` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `num_doc` varchar(20) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT NULL,
  `fecha_n` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `id_sede` int DEFAULT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `id_grado` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `descripcion_perfil` text,
  `logros` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `miembros`
--

INSERT INTO `miembros` (`id_miembro`, `nombre`, `apellido`, `peso`, `num_doc`, `tipo_documento`, `fecha_n`, `telefono`, `activo`, `id_sede`, `rol`, `id_grado`, `id_categoria`, `descripcion_perfil`, `logros`) VALUES
(1, 'Estefanía', 'Londoño', 55.50, '1023456789', NULL, '2008-05-15', '3001234567', 1, 1, NULL, 6, 2, NULL, NULL),
(2, 'Samuel', 'Gomez', 62.00, '1098765432', NULL, '2009-11-20', '3109876543', 1, 1, NULL, 1, 2, NULL, NULL),
(3, 'Admin', 'General', NULL, '123456789', NULL, '2000-01-01', '000000', 1, NULL, NULL, 1, NULL, NULL, NULL),
(5, 'samir', 'remolinos', NULL, '1013462218', NULL, '2008-11-14', '3246783188', 1, NULL, NULL, 1, NULL, NULL, NULL),
(6, 'mariana', 'mora', NULL, '1015190715', NULL, '2009-11-21', '3023410826', 1, NULL, NULL, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multimedia_galeria`
--

CREATE TABLE `multimedia_galeria` (
  `id_multimedia` int NOT NULL,
  `id_miembro` int DEFAULT NULL,
  `url_youtube` varchar(255) DEFAULT NULL,
  `url_instagram` varchar(255) DEFAULT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `fecha_agregado` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id_noticias` int NOT NULL,
  `id_miembro` int DEFAULT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `contenido` text,
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id_sede` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre`, `lugar`, `horario`) VALUES
(1, 'Sede Central Santa Margarita', 'Calle 60 #100-20', 'Lunes a Viernes 4:00 PM - 8:00 PM'),
(2, 'Sede Satélite San Javier', 'Carrera 99 #45-10', 'Sábados 8:00 AM - 12:00 PM'),
(3, 'roblemar', 'calle31 #1', '30000000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teoria`
--

CREATE TABLE `teoria` (
  `id_teoria` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `id_tipo_de_t` int DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contenido` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_de_teoria`
--

CREATE TABLE `tipo_de_teoria` (
  `id_tipo_de_t` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userlog`
--

CREATE TABLE `userlog` (
  `id_userlog` int NOT NULL,
  `id_miembro` int NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`);

--
-- Indices de la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD PRIMARY KEY (`id_miembro`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `id_grado` (`id_grado`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `multimedia_galeria`
--
ALTER TABLE `multimedia_galeria`
  ADD PRIMARY KEY (`id_multimedia`),
  ADD KEY `fk_miembro_galeria` (`id_miembro`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id_noticias`),
  ADD KEY `id_miembro` (`id_miembro`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id_sede`);

--
-- Indices de la tabla `teoria`
--
ALTER TABLE `teoria`
  ADD PRIMARY KEY (`id_teoria`),
  ADD KEY `id_grado` (`id_grado`),
  ADD KEY `id_tipo_de_t` (`id_tipo_de_t`);

--
-- Indices de la tabla `tipo_de_teoria`
--
ALTER TABLE `tipo_de_teoria`
  ADD PRIMARY KEY (`id_tipo_de_t`);

--
-- Indices de la tabla `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id_userlog`),
  ADD UNIQUE KEY `uk_id_miembro` (`id_miembro`),
  ADD UNIQUE KEY `uk_correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id_miembro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `multimedia_galeria`
--
ALTER TABLE `multimedia_galeria`
  MODIFY `id_multimedia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id_noticias` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `teoria`
--
ALTER TABLE `teoria`
  MODIFY `id_teoria` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_de_teoria`
--
ALTER TABLE `tipo_de_teoria`
  MODIFY `id_tipo_de_t` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id_userlog` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD CONSTRAINT `miembros_ibfk_2` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `miembros_ibfk_4` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`),
  ADD CONSTRAINT `miembros_ibfk_5` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `multimedia_galeria`
--
ALTER TABLE `multimedia_galeria`
  ADD CONSTRAINT `fk_miembro_galeria` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`);

--
-- Filtros para la tabla `teoria`
--
ALTER TABLE `teoria`
  ADD CONSTRAINT `teoria_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`),
  ADD CONSTRAINT `teoria_ibfk_2` FOREIGN KEY (`id_tipo_de_t`) REFERENCES `tipo_de_teoria` (`id_tipo_de_t`);

--
-- Filtros para la tabla `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `fk_userlog_miembro` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
