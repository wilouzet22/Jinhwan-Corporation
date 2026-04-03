-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 05-02-2026 a las 20:42:22
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
-- Base de datos: `tkd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cedes`
--

CREATE TABLE `cedes` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `direccion` varchar(180) NOT NULL,
  `telefono` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro_alumno`
--

CREATE TABLE `maestro_alumno` (
  `maestro_id` int UNSIGNED NOT NULL,
  `alumno_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `orden` tinyint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `nombre`, `orden`) VALUES
(1, 'Pinta Amarillo', 1),
(2, 'Amarillo', 2),
(3, 'Pinta Verde', 3),
(4, 'Verde', 4),
(5, 'Pinta Azul', 5),
(6, 'Azul', 6),
(7, 'Pinta Rojo', 7),
(8, 'Rojo', 8),
(9, 'Pinta Negro', 9),
(10, 'Negro 1', 10),
(11, 'Negro 2', 11),
(12, 'Negro 3', 12),
(13, 'Negro 4', 13),
(14, 'Negro 5', 14),
(15, 'Negro 6', 15),
(16, 'Negro 7', 16),
(17, 'Negro 8', 17),
(18, 'Negro 9', 18),
(19, 'Ninguno / Administrativo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int UNSIGNED NOT NULL,
  `usuario_id` int UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `proximo_vencimiento` date NOT NULL,
  `mensaje_alerta` text,
  `visto_por_alumno` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` tinyint UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Superusuario'),
(2, 'Administrador'),
(3, 'Contador'),
(4, 'Maestro'),
(5, 'Estudiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teoria_galeria`
--

CREATE TABLE `teoria_galeria` (
  `id` int UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `url_video` varchar(255) DEFAULT NULL,
  `nivel_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int UNSIGNED NOT NULL,
  `rol_id` tinyint UNSIGNED NOT NULL,
  `nivel_id` int UNSIGNED DEFAULT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(80) NOT NULL,
  `tipo_documento` enum('TI','CC','CE','PAS') NOT NULL DEFAULT 'CC',
  `numero_documento` varchar(30) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_sede`
--

CREATE TABLE `usuario_sede` (
  `usuario_id` int UNSIGNED NOT NULL,
  `sede_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_teoria_personal`
--

CREATE TABLE `usuario_teoria_personal` (
  `usuario_id` int UNSIGNED NOT NULL,
  `teoria_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cedes`
--
ALTER TABLE `cedes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `maestro_alumno`
--
ALTER TABLE `maestro_alumno`
  ADD PRIMARY KEY (`maestro_id`,`alumno_id`),
  ADD KEY `alumno_id` (`alumno_id`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orden` (`orden`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `teoria_galeria`
--
ALTER TABLE `teoria_galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nivel_id` (`nivel_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`),
  ADD KEY `rol_id` (`rol_id`),
  ADD KEY `nivel_id` (`nivel_id`);

--
-- Indices de la tabla `usuario_sede`
--
ALTER TABLE `usuario_sede`
  ADD PRIMARY KEY (`usuario_id`,`sede_id`),
  ADD KEY `sede_id` (`sede_id`);

--
-- Indices de la tabla `usuario_teoria_personal`
--
ALTER TABLE `usuario_teoria_personal`
  ADD PRIMARY KEY (`usuario_id`,`teoria_id`),
  ADD KEY `teoria_id` (`teoria_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cedes`
--
ALTER TABLE `cedes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `teoria_galeria`
--
ALTER TABLE `teoria_galeria`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `maestro_alumno`
--
ALTER TABLE `maestro_alumno`
  ADD CONSTRAINT `maestro_alumno_ibfk_1` FOREIGN KEY (`maestro_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `maestro_alumno_ibfk_2` FOREIGN KEY (`alumno_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `teoria_galeria`
--
ALTER TABLE `teoria_galeria`
  ADD CONSTRAINT `teoria_galeria_ibfk_1` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`);

--
-- Filtros para la tabla `usuario_sede`
--
ALTER TABLE `usuario_sede`
  ADD CONSTRAINT `usuario_sede_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_sede_ibfk_2` FOREIGN KEY (`sede_id`) REFERENCES `cedes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_teoria_personal`
--
ALTER TABLE `usuario_teoria_personal`
  ADD CONSTRAINT `usuario_teoria_personal_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_teoria_personal_ibfk_2` FOREIGN KEY (`teoria_id`) REFERENCES `teoria_galeria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
