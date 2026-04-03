-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-03-2026 a las 18:45:19
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
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descripcion_pago`
--

CREATE TABLE `descripcion_pago` (
  `id_descrip` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documento` int NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_miembros`
--

CREATE TABLE `info_miembros` (
  `id_info_miemb` int NOT NULL,
  `id_miembro` int NOT NULL,
  `descripcion_perfil` text,
  `logros` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `fecha_n` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `id_documento` int NOT NULL,
  `id_sede` int DEFAULT NULL,
  `id_rol` int DEFAULT NULL,
  `id_grado` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multimedia_galeria`
--

CREATE TABLE `multimedia_galeria` (
  `id_multimedia` int NOT NULL,
  `id_miembro` int NOT NULL,
  `tipo_archivo` enum('imagen','video') NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id_noticias` int NOT NULL,
  `id_miembro` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `contenido` text,
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int NOT NULL,
  `id_miembro` int NOT NULL,
  `id_descrip` int NOT NULL,
  `id_transaccion_api` varchar(255) DEFAULT NULL,
  `estado_pago` varchar(50) DEFAULT NULL,
  `plataforma_pago` varchar(100) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int NOT NULL,
  `nombre` varchar(50) NOT NULL
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teoria`
--

CREATE TABLE `teoria` (
  `id_teoria` int NOT NULL,
  `id_grado` int NOT NULL,
  `id_tipo_de_t` int NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contenido` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_de_teoria`
--

CREATE TABLE `tipo_de_teoria` (
  `id_tipo_de_t` int NOT NULL,
  `nombre` varchar(100) NOT NULL
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
-- Indices de la tabla `descripcion_pago`
--
ALTER TABLE `descripcion_pago`
  ADD PRIMARY KEY (`id_descrip`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`);

--
-- Indices de la tabla `info_miembros`
--
ALTER TABLE `info_miembros`
  ADD PRIMARY KEY (`id_info_miemb`),
  ADD UNIQUE KEY `id_miembro` (`id_miembro`);

--
-- Indices de la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD PRIMARY KEY (`id_miembro`),
  ADD UNIQUE KEY `num_doc` (`num_doc`),
  ADD KEY `id_documento` (`id_documento`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_grado` (`id_grado`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `multimedia_galeria`
--
ALTER TABLE `multimedia_galeria`
  ADD PRIMARY KEY (`id_multimedia`),
  ADD KEY `id_miembro` (`id_miembro`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id_noticias`),
  ADD KEY `id_miembro` (`id_miembro`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD UNIQUE KEY `id_transaccion_api` (`id_transaccion_api`),
  ADD KEY `id_miembro` (`id_miembro`),
  ADD KEY `id_descrip` (`id_descrip`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

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
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `id_miembro` (`id_miembro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `descripcion_pago`
--
ALTER TABLE `descripcion_pago`
  MODIFY `id_descrip` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documento` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `info_miembros`
--
ALTER TABLE `info_miembros`
  MODIFY `id_info_miemb` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id_miembro` int NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `info_miembros`
--
ALTER TABLE `info_miembros`
  ADD CONSTRAINT `info_miembros_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD CONSTRAINT `miembros_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`) ON UPDATE CASCADE,
  ADD CONSTRAINT `miembros_ibfk_2` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`) ON UPDATE CASCADE,
  ADD CONSTRAINT `miembros_ibfk_3` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `miembros_ibfk_4` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `miembros_ibfk_5` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `multimedia_galeria`
--
ALTER TABLE `multimedia_galeria`
  ADD CONSTRAINT `multimedia_galeria_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_descrip`) REFERENCES `descripcion_pago` (`id_descrip`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `teoria`
--
ALTER TABLE `teoria`
  ADD CONSTRAINT `teoria_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `teoria_ibfk_2` FOREIGN KEY (`id_tipo_de_t`) REFERENCES `tipo_de_teoria` (`id_tipo_de_t`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `userlog_ibfk_1` FOREIGN KEY (`id_miembro`) REFERENCES `miembros` (`id_miembro`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
