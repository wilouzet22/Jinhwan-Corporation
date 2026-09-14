-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql113.infinityfree.com
-- Tiempo de generación: 13-09-2026 a las 21:23:29
-- Versión del servidor: 11.4.13-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_42216592_jinhwa_corporation`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `permisos_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `nombre`, `apellido`, `correo`, `clave`, `foto_perfil`, `permisos_extra`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'General', 'admin@admin.com', '$2y$10$45YUeh7Y/t9J99i86uA1Fud5ekOo1FMdYaVD1vZkGy5zN5GQCWP9a', NULL, '{\"sedes\": true, \"galeria\": true, \"ascensos\": true, \"reportes\": true, \"registros\": true, \"calendario\": true}', 1, '2026-08-29 17:59:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Ninguno', 'Sin categoría asignada'),
(2, 'Pre-benjamín', 'Menores de 7 años'),
(3, 'Benjamín', '8 a 9 años'),
(4, 'Pre-cadete', '10 a 11 años'),
(5, 'Cadete', '12 a 14 años'),
(6, 'Junior', '15 a 17 años'),
(7, 'Mayores', '18 años en adelante'),
(8, 'Máster', '35 años en adelante'),
(9, 'Poomsae', 'Modalidad de formas (técnica)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificados_ascenso`
--

CREATE TABLE `certificados_ascenso` (
  `id_certificado` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_maestro` int(11) NOT NULL,
  `grado_anterior` varchar(80) NOT NULL,
  `grado_nuevo` varchar(80) NOT NULL,
  `fecha_examen` date NOT NULL,
  `observaciones` text DEFAULT NULL,
  `folio` varchar(30) NOT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `certificados_ascenso`
--

INSERT INTO `certificados_ascenso` (`id_certificado`, `id_estudiante`, `id_maestro`, `grado_anterior`, `grado_nuevo`, `fecha_examen`, `observaciones`, `folio`, `creado_en`) VALUES
(1, 148, 150, 'Azul', 'Pinta Rojo', '2026-08-29', 'El practicante buen despeño tiene muy buana tecnica de pateo pero tiene que mejorar en las poomseas tiene un cardio muy bajo debe mejorar eso no sabe combatir peor lo intenta y tiene muy buen compañerismo.', 'JH-2026-0001', '2026-08-29 18:19:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase_ejercicios`
--

CREATE TABLE `clase_ejercicios` (
  `id_clase_ejercicio` int(11) NOT NULL,
  `id_cronograma` int(11) NOT NULL,
  `id_ejercicio` int(11) NOT NULL,
  `fase` enum('inicial','central','final') NOT NULL,
  `series_o_tiempo` varchar(100) DEFAULT NULL COMMENT 'Ej: 3 series de 20 reps o 45 segundos',
  `observaciones_especificas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronogramas_clase`
--

CREATE TABLE `cronogramas_clase` (
  `id_cronograma` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL,
  `id_maestro` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `objetivo` text NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id_ejercicio` int(11) NOT NULL,
  `tipo` enum('Fuerza general','Fuerza Especifica','Pliometria','Coordinación','Resistencia Aerobica','Resistencia anaerobica','Combate','Flexibilidad','Velocidad','Otro') NOT NULL DEFAULT 'Otro',
  `nombre` varchar(150) NOT NULL,
  `explicacion` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int(11) NOT NULL,
  `id_grado` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_maestro` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT 'TI',
  `num_doc` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `rh` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `id_grado`, `id_categoria`, `id_grupo`, `id_maestro`, `nombre`, `apellido`, `tipo_documento`, `num_doc`, `telefono`, `foto_perfil`, `fecha_nacimiento`, `peso`, `division`, `eps`, `rh`, `correo`, `clave`, `activo`, `created_at`, `updated_at`) VALUES
(101, 11, 1, 1, 152, 'Jean Karlo', 'García León', 'TI', '1192466428', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(102, 9, 4, 1, 152, 'Samuel', 'Velásquez Sánchez', 'TI', '1023647991', '', NULL, '2014-07-06', '38.00', NULL, 'Sisbén', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(103, 7, 4, 1, 152, 'Daniel Andrés', 'Montoya Calle', 'TI', '1021940897', '', NULL, '2016-11-07', '44.60', NULL, 'N eps', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(104, 5, 4, 1, 152, 'Miguel Ángel', 'Bautista Monroy', 'TI', '1195214019', '', NULL, '2013-08-13', '45.00', NULL, 'sisben', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(105, 8, 6, 1, 152, 'Daniel', 'Pérez Sanmartín', 'TI', '1020226169', '', NULL, '2010-08-19', '52.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(106, 9, 1, 1, 152, 'Juan Camilo', 'Martínez Sanmartín', 'TI', '1020229154', '', NULL, '2013-01-18', '37.00', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(107, 5, 6, 1, 152, 'Salome', 'Taborda Blando', 'TI', '1020226181', '', NULL, '2010-08-20', '67.70', NULL, 'savia', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(108, 9, 4, 1, 152, 'Matías', 'García León', 'TI', '1033265504', '', NULL, '2015-04-17', '30.00', NULL, 'sanita', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(109, 5, 4, 1, 152, 'Juan Pablo', 'Betancourt Ospina', 'TI', '1017265346', '', NULL, '2015-04-13', '42.30', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(110, 4, 4, 1, 152, 'Juan Camilo', 'Vega O', 'TI', '1021937647', '', NULL, '2015-07-02', '44.30', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(111, 1, 6, 1, 152, 'Danna Sofia', 'Alfonso', 'TI', '1011405201', '', NULL, '2012-03-27', '58.90', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(112, 8, 6, 2, 150, 'Samir Enrique', 'Nava Martínez', 'TI', '7235701', '', NULL, '2009-11-12', '70.80', NULL, 'savia', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(113, 7, 6, 2, 150, 'Matías', 'Ochoa García', 'TI', '1025661867', '', NULL, '2009-06-03', '53.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(114, 7, 4, 2, 150, 'Maximiliano', 'Carvajal Ruiz', 'TI', '10376553567', '', NULL, '2014-12-06', '40.00', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(115, 6, 6, 1, 152, 'Sofia', 'Medina Castrillon', 'TI', '1027741702', '', NULL, '2010-01-26', '56.40', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(116, 3, 1, 1, 152, 'Dylan Andrés', 'Gaviria Alvarez', 'TI', '1233898589', '', NULL, '2016-05-21', '27.80', NULL, 'Mutual', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(117, 3, 4, 1, 152, 'Santiago Andres', 'Hernandez Torres', 'TI', '1103755101', '', NULL, '2015-08-25', '27.00', NULL, 'militar', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(118, 3, 4, 2, 150, 'Smith', 'Méndez Alvarez', 'TI', '1032059029', '', NULL, '2016-07-02', '34.20', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(119, 9, 6, 1, 152, 'Aaron David', 'Loaiza Monroy', 'TI', '1422730', '', NULL, '2010-09-14', '63.30', NULL, 'sura', 'O-', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(120, 11, 1, 1, 152, 'Anderson Steven', 'Loaiza Quintero', 'CC', '1033426095', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(121, 11, 1, 1, 152, 'Nicolás', 'Osorio Valencia', 'TI', '1054875293', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(122, 9, 4, 1, 152, 'Jerónimo', 'Osorio Valencia', 'TI', '1054882227', '', NULL, '2013-10-22', '45.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(123, 11, 1, 1, 152, 'Juan David', 'Orrego Ossa', 'CC', '1020419153', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(124, 9, 1, 1, 152, 'Sara', 'Ríos Daza', 'CC', '1022152029', '', NULL, '2010-09-06', '49.00', NULL, 'ponal', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(125, 4, 1, 1, 152, 'Ana Sofía', 'Hurtado Ocampo', 'TI', '1036259874', '', NULL, '2011-05-19', '68.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(126, 9, 1, 1, 152, 'José Ignacio', 'Marín Vásquez', 'TI', '1232598434', '', NULL, '2014-01-04', '36.80', NULL, 'Total', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(127, 9, 1, 1, 152, 'Marcelo Gabriel', 'Marín Vázquez', 'TI', '1232598435', '', NULL, '2010-09-08', '51.00', NULL, 'Total', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(128, 7, 6, 2, 150, 'Juan Camilo', 'García Barba', 'TI', '1023637476', '', NULL, '2009-06-03', '89.00', NULL, 'Savia', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(129, 9, 6, 1, 152, 'Samuel Cano', 'Pulgarin', 'TI', '1033491933', '', NULL, '2009-05-14', '55.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(130, 1, 6, 1, 152, 'Hillary', 'Gómez García', 'TI', '119246017', '', NULL, '2011-11-03', '45.50', NULL, 'sura', 'A-', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(131, 11, 6, 1, 152, 'Diego Fernando', 'Salcedo Bonza', 'TI', '1096807375', '', NULL, '2011-03-04', '60.00', NULL, 'Sanita', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(132, 1, 4, 1, 152, 'Rubiangelys Sofía', 'Camacho Figueroa', 'TI', '6164279', '', NULL, '2013-11-17', '50.00', NULL, 'Savia', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(133, 1, 1, 3, 152, 'Jeziel Abrahán', 'Ruiz Figueroa', 'TI', '1087754585', '', NULL, '2020-05-26', '19.00', NULL, 'savia', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(134, 1, 1, 1, 152, 'Juan José', 'Diosa Ospina', 'TI', '1013464771', '', NULL, '2013-02-22', '57.00', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(135, 9, 6, 1, 152, 'Kevin Andrés', 'Herrera', 'TI', '1037126322', '', NULL, '2010-11-16', '63.00', NULL, 'total', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(136, 9, 6, 1, 152, 'Samuel', 'Mejía Villa', 'TI', '1088302615', '', NULL, '2010-07-29', '63.00', NULL, 'Sura', 'O-', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(137, 9, 2, 3, 152, 'Emanuel', 'Alcaraz Ocampo', 'TI', '1011519226', '', NULL, '2017-11-12', '43.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(138, 2, 7, 2, 150, 'Ana Sofía', 'Flórez Guzmán', 'TI', '1020224629', '', NULL, '2008-06-13', '56.00', NULL, 'sura', 'A-', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(139, 8, 1, 1, 152, 'Samuel', 'Valencia Tabares', 'TI', '1192467492', '', NULL, '2012-03-08', '40.80', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(140, 9, 7, 1, 152, 'Sarah Sofía', 'Triviño Saavedra', 'TI', '1094911412', '', NULL, '2008-02-19', '57.00', NULL, 'suri', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(141, 5, 6, 1, 152, 'Ana Sofía', 'Quiroz Puerta', 'TI', '1021927403', '', NULL, '2009-07-02', '60.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(142, 4, 1, 1, 152, 'Ismael', 'Arboleda Gutiérrez', 'TI', '1020123545', '', NULL, '2015-05-20', '39.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(143, 9, 6, 1, 152, 'Valeria', 'Matiz Escudero', 'TI', '1028141323', '', NULL, '2008-11-13', '63.00', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(144, 7, 6, 1, 152, 'Mariana', 'Giraldo Rincon', 'TI', '1027809885', '', NULL, '2011-03-12', '36.00', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(145, 4, 6, 2, 150, 'Jimena', 'Velásquez Ospina', 'TI', '1011594301', '', NULL, '2009-01-12', '70.00', NULL, 'nueva', NULL, NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(146, 9, 1, 1, 152, 'Luciana', 'Pino Monsalve', 'TI', '1020122879', '', NULL, '2014-09-16', '0.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(147, 9, 4, 1, 152, 'Gabriela', 'Almenares Fonegra', 'TI', '1020120658', '', NULL, '2012-02-23', '47.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(148, 8, 1, 1, 152, 'Ana Sofia', 'Sánchez Agudelo', 'TI', '1011222665', '', NULL, '2013-01-27', '60.00', NULL, 'Policía', NULL, NULL, NULL, 1, '2026-08-29 17:59:05', NULL),
(153, 1, 1, 1, 152, 'Mateo', 'Ríos', 'TI', '10000002', '', NULL, NULL, NULL, NULL, NULL, NULL, 'estudiante@jinhwan.com', '$2y$10$T7OWSqUPLN2lIqOAaJTQsuW1oDhHYcs/cJrPg0b2q1tjIWVYNWIYu', 1, '2026-08-29 17:59:05', NULL),
(154, 7, 5, 2, 150, 'maria jose', 'gomez londoño', 'TI', '1013462218', '3246783188', NULL, '2010-11-11', '60.00', '-65', 'Sura', 'O+', 'samugomedo0@gmail.com', '$2y$10$lp.U80H.P2fdX5qJKbU8/upbxvDswylhNls8z8RZgBaUp0cgRSyNK', 1, '2026-09-06 01:21:57', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `id_maestro` int(11) DEFAULT NULL,
  `id_sede` int(11) DEFAULT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('entrenamiento','torneo','examen','evento_especial','otro') DEFAULT 'entrenamiento',
  `color` varchar(20) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `todo_dia` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria_multimedia`
--

CREATE TABLE `galeria_multimedia` (
  `id_multimedia` int(11) NOT NULL,
  `id_maestro` int(11) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('imagen','video','documento') DEFAULT 'imagen',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `galeria_multimedia`
--

INSERT INTO `galeria_multimedia` (`id_multimedia`, `id_maestro`, `url`, `titulo`, `descripcion`, `tipo`, `created_at`) VALUES
(3, NULL, 'https://www.youtube.com/watch?v=5ZXHQDD2ITs', NULL, NULL, 'video', '2026-09-13 23:11:34'),
(4, NULL, '', NULL, NULL, 'imagen', '2026-09-13 23:11:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `tipo` enum('color','dan') DEFAULT 'color',
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `nombre`, `color`, `tipo`, `descripcion`) VALUES
(1, 'Blanco', '#f8fafc', 'color', 'Cinturón blanco — nivel inicial'),
(2, 'Pinta Amarillo', '#fef08a', 'color', 'Cinturón blanco con punta amarilla'),
(3, 'Amarillo', '#eab308', 'color', 'Cinturón amarillo'),
(4, 'Pinta Verde', '#86efac', 'color', 'Cinturón amarillo con punta verde'),
(5, 'Verde', '#22c55e', 'color', 'Cinturón verde'),
(6, 'Pinta Azul', '#93c5fd', 'color', 'Cinturón verde con punta azul'),
(7, 'Azul', '#3b82f6', 'color', 'Cinturón azul'),
(8, 'Pinta Rojo', '#fca5a5', 'color', 'Cinturón azul con punta roja'),
(9, 'Rojo', '#ef4444', 'color', 'Cinturón rojo'),
(10, 'Pinta Negro', '#c084fc', 'color', 'Cinturón rojo con punta negra'),
(11, 'Negro 1 Dan', '#334155', 'dan', 'Cinturón negro — Primer Dan'),
(12, 'Negro 2 Dan', '#334155', 'dan', 'Cinturón negro — Segundo Dan'),
(13, 'Negro 3 Dan', '#334155', 'dan', 'Cinturón negro — Tercer Dan'),
(14, 'Negro 4 Dan', '#334155', 'dan', 'Cinturón negro — Cuarto Dan'),
(15, 'Negro 5 Dan', '#334155', 'dan', 'Cinturón negro — Quinto Dan'),
(16, 'Negro 6 Dan', '#334155', 'dan', 'Cinturón negro — Sexto Dan'),
(17, 'Negro 7 Dan', '#334155', 'dan', 'Cinturón negro — Séptimo Dan'),
(18, 'Negro 8 Dan', '#334155', 'dan', 'Cinturón negro — Octavo Dan'),
(19, 'Negro 9 Dan', '#334155', 'dan', 'Cinturón negro — Noveno Dan'),
(20, 'Ninguno', NULL, 'color', 'Sin grado asignado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `id_maestro` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `horario` varchar(150) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `id_sede`, `id_maestro`, `nombre`, `descripcion`, `horario`, `activo`, `created_at`) VALUES
(1, 2, 152, 'Grupo A', 'Tradicional Santa Mónica', 'Sábados: 4:00 PM - 6:00 PM | Domingos: 8:00 AM - 10:00 AM', 1, '2026-09-13 23:11:34'),
(2, 1, 150, 'Grupo B', 'Tradicional San Cristóbal', 'Lunes y Miércoles: 6:00 PM - 7:30 PM', 1, '2026-09-13 23:11:34'),
(3, 2, 152, 'Grupo C', 'Peques Santa Mónica', 'Viernes: 6:30 PM - 8:00 PM | Domingos: 10:00 AM - 11:30 AM', 1, '2026-09-13 23:11:34'),
(4, 2, 152, 'Grupo D', 'Selección de Combate', 'Martes y Jueves: 6:00 PM - 8:00 PM', 1, '2026-09-13 23:11:34'),
(5, 2, 152, 'Grupo E', 'Poomsaes', 'Sábados: 2:00 PM - 4:00 PM', 1, '2026-09-13 23:11:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_grados`
--

CREATE TABLE `historial_grados` (
  `id_historial` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `fecha_obtencion` date NOT NULL,
  `id_certificado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maestro`
--

CREATE TABLE `maestro` (
  `id_maestro` int(11) NOT NULL,
  `id_grado` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT 'CC',
  `num_doc` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `id_sede` int(11) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `descripcion_perfil` text DEFAULT NULL,
  `logros` text DEFAULT NULL,
  `mostrar_en_web` tinyint(1) DEFAULT 0,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `permisos_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `maestro`
--

INSERT INTO `maestro` (`id_maestro`, `id_grado`, `nombre`, `apellido`, `tipo_documento`, `num_doc`, `telefono`, `id_sede`, `foto_perfil`, `descripcion_perfil`, `logros`, `mostrar_en_web`, `correo`, `clave`, `permisos_extra`, `activo`, `created_at`, `updated_at`) VALUES
(150, 11, 'Samuel', 'Gómez', 'TI', '1013462218', '3246783188', 1, '606e99e11b6916d3ccf18632109d0667.jpg', 'Profesor de Taekwondo federado y creador de la plataforma', 'Campeón olímpico', 0, 'samugomedo@gmail.com', '$2y$10$7MwlMj3DwkcViNNvF1rscugFN4Cko0.OzeysQGMJL34cSm2qmX.le', '{\"sedes\": false, \"galeria\": false, \"ascensos\": false, \"reportes\": false, \"registros\": false, \"calendario\": false}', 1, '2026-08-29 17:59:05', NULL),
(152, 11, 'Carlos', 'Mendoza', 'CC', '10000001', '', 2, NULL, 'Maestro principal de la sede', 'Cinturón Negro 1 Dan', 0, 'maestro@jinhwan.com', '$2y$10$Tn44kYfa6/Jacswrc8IR8unk0GpBthtGDc3iHK1JZfWbeSJHf8nG.', '{\"sedes\": true, \"galeria\": true, \"ascensos\": true, \"reportes\": true, \"registros\": true, \"calendario\": true}', 1, '2026-08-29 17:59:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id_sede` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre`, `direccion`, `telefono`, `email`, `horario`, `imagen`, `activo`) VALUES
(1, 'Sede San Cristóbal', 'Cl. 62 #131-80, Nazaret, San Cristóbal, Medellín, Antioquia', '3206641361', NULL, 'Lun - Vie: 4:00 PM - 8:00 PM', NULL, 1),
(2, 'Sede Principal Santa Mónica Campo Alegre', 'Cl 38 #9255, Belencito, Medellín, La América, Medellín, Antioquia', '3206641361', NULL, 'Lun - Sáb: 3:00 PM - 9:00 PM', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teorias`
--

CREATE TABLE `teorias` (
  `id_teoria` int(11) NOT NULL,
  `id_grado` int(11) DEFAULT NULL,
  `id_tipo_teoria` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `url_video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_teoria`
--

CREATE TABLE `tipos_teoria` (
  `id_tipo_teoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipos_teoria`
--

INSERT INTO `tipos_teoria` (`id_tipo_teoria`, `nombre`, `descripcion`) VALUES
(1, 'General', 'Conceptos fundamentales de Taekwondo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `certificados_ascenso`
--
ALTER TABLE `certificados_ascenso`
  ADD PRIMARY KEY (`id_certificado`),
  ADD KEY `fk_cert_estudiante` (`id_estudiante`),
  ADD KEY `fk_cert_maestro` (`id_maestro`);

--
-- Indices de la tabla `clase_ejercicios`
--
ALTER TABLE `clase_ejercicios`
  ADD PRIMARY KEY (`id_clase_ejercicio`),
  ADD KEY `fk_ce_cronograma` (`id_cronograma`),
  ADD KEY `fk_ce_ejercicio` (`id_ejercicio`);

--
-- Indices de la tabla `cronogramas_clase`
--
ALTER TABLE `cronogramas_clase`
  ADD PRIMARY KEY (`id_cronograma`),
  ADD KEY `fk_cronograma_grupo` (`id_grupo`),
  ADD KEY `fk_cronograma_maestro` (`id_maestro`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id_ejercicio`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `uk_estudiante_correo` (`correo`),
  ADD UNIQUE KEY `uk_estudiante_doc` (`num_doc`),
  ADD KEY `idx_estudiante_grado` (`id_grado`),
  ADD KEY `idx_estudiante_categoria` (`id_categoria`),
  ADD KEY `idx_estudiante_grupo` (`id_grupo`),
  ADD KEY `idx_estudiante_maestro` (`id_maestro`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `idx_eventos_maestro` (`id_maestro`),
  ADD KEY `idx_eventos_sede` (`id_sede`);

--
-- Indices de la tabla `galeria_multimedia`
--
ALTER TABLE `galeria_multimedia`
  ADD PRIMARY KEY (`id_multimedia`),
  ADD KEY `fk_galeria_maestro` (`id_maestro`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `fk_grupo_sede` (`id_sede`),
  ADD KEY `fk_grupo_maestro` (`id_maestro`);

--
-- Indices de la tabla `historial_grados`
--
ALTER TABLE `historial_grados`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `idx_historial_estudiante` (`id_estudiante`),
  ADD KEY `idx_historial_grado` (`id_grado`),
  ADD KEY `idx_historial_certificado` (`id_certificado`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id_sede`);

--
-- Indices de la tabla `teorias`
--
ALTER TABLE `teorias`
  ADD PRIMARY KEY (`id_teoria`),
  ADD KEY `idx_teoria_grado` (`id_grado`),
  ADD KEY `idx_teoria_tipo` (`id_tipo_teoria`);

--
-- Indices de la tabla `tipos_teoria`
--
ALTER TABLE `tipos_teoria`
  ADD PRIMARY KEY (`id_tipo_teoria`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `certificados_ascenso`
--
ALTER TABLE `certificados_ascenso`
  MODIFY `id_certificado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clase_ejercicios`
--
ALTER TABLE `clase_ejercicios`
  MODIFY `id_clase_ejercicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cronogramas_clase`
--
ALTER TABLE `cronogramas_clase`
  MODIFY `id_cronograma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `galeria_multimedia`
--
ALTER TABLE `galeria_multimedia`
  MODIFY `id_multimedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_grados`
--
ALTER TABLE `historial_grados`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maestro`
--
ALTER TABLE `maestro`
  MODIFY `id_maestro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `teorias`
--
ALTER TABLE `teorias`
  MODIFY `id_teoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_teoria`
--
ALTER TABLE `tipos_teoria`
  MODIFY `id_tipo_teoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `certificados_ascenso`
--
ALTER TABLE `certificados_ascenso`
  ADD CONSTRAINT `fk_cert_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cert_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clase_ejercicios`
--
ALTER TABLE `clase_ejercicios`
  ADD CONSTRAINT `fk_ce_cronograma` FOREIGN KEY (`id_cronograma`) REFERENCES `cronogramas_clase` (`id_cronograma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ce_ejercicio` FOREIGN KEY (`id_ejercicio`) REFERENCES `ejercicios` (`id_ejercicio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cronogramas_clase`
--
ALTER TABLE `cronogramas_clase`
  ADD CONSTRAINT `fk_cronograma_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cronograma_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `fk_estudiante_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_estudiante_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_estudiante_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_estudiante_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_eventos_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eventos_sede` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `galeria_multimedia`
--
ALTER TABLE `galeria_multimedia`
  ADD CONSTRAINT `fk_galeria_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `fk_grupo_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grupo_sede` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_grados`
--
ALTER TABLE `historial_grados`
  ADD CONSTRAINT `fk_hist_certificado` FOREIGN KEY (`id_certificado`) REFERENCES `certificados_ascenso` (`id_certificado`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_hist_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_hist_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE CASCADE;

--
-- Filtros para la tabla `teorias`
--
ALTER TABLE `teorias`
  ADD CONSTRAINT `fk_teoria_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teoria_tipo` FOREIGN KEY (`id_tipo_teoria`) REFERENCES `tipos_teoria` (`id_tipo_teoria`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
