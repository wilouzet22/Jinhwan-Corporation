-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2026 at 09:32 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jinhwa_corporation`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `permisos_extra` json DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `nombre`, `apellido`, `correo`, `clave`, `permisos_extra`, `activo`, `created_at`) VALUES
(1, 'Administrador', 'General', 'admin@admin.com', '$2y$10$45YUeh7Y/t9J99i86uA1Fud5ekOo1FMdYaVD1vZkGy5zN5GQCWP9a', '{\"sedes\": true, \"galeria\": true, \"ascensos\": true, \"reportes\": true, \"registros\": true, \"calendario\": true}', 1, '2026-08-29 17:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorias`
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
-- Table structure for table `certificados_ascenso`
--

CREATE TABLE `certificados_ascenso` (
  `id_certificado` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `id_maestro` int NOT NULL,
  `grado_anterior` varchar(80) NOT NULL,
  `grado_nuevo` varchar(80) NOT NULL,
  `fecha_examen` date NOT NULL,
  `observaciones` text,
  `folio` varchar(30) NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `certificados_ascenso`
--

INSERT INTO `certificados_ascenso` (`id_certificado`, `id_estudiante`, `id_maestro`, `grado_anterior`, `grado_nuevo`, `fecha_examen`, `observaciones`, `folio`, `creado_en`) VALUES
(1, 148, 150, 'Azul', 'Pinta Rojo', '2026-08-29', 'El practicante buen despeño tiene muy buana tecnica de pateo pero tiene que mejorar en las poomseas tiene un cardio muy bajo debe mejorar eso no sabe combatir peor lo intenta y tiene muy buen compañerismo.', 'JH-2026-0001', '2026-08-29 18:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `estudiante`
--

CREATE TABLE `estudiante` (
  `id_estudiante` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT 'TI',
  `num_doc` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `id_sede` int DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `fecha_n` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `rh` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `estudiante`
--

INSERT INTO `estudiante` (`id_estudiante`, `id_grado`, `id_categoria`, `nombre`, `apellido`, `tipo_documento`, `num_doc`, `telefono`, `id_sede`, `foto_perfil`, `fecha_n`, `peso`, `division`, `eps`, `rh`, `correo`, `clave`, `activo`, `created_at`) VALUES
(101, 11, 1, 'Jean Karlo', 'García León', 'TI', '1192466428', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05'),
(102, 9, 4, 'Samuel', 'Velásquez Sánchez', 'TI', '1023647991', '', 2, NULL, '2014-07-06', '38.00', NULL, 'Sisbén', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(103, 7, 4, 'Daniel Andrés', 'Montoya Calle', 'TI', '1021940897', '', 2, NULL, '2016-11-07', '44.60', NULL, 'N eps', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(104, 5, 4, 'Miguel Ángel', 'Bautista Monroy', 'TI', '1195214019', '', 2, NULL, '2013-08-13', '45.00', NULL, 'sisben', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(105, 8, 6, 'Daniel', 'Pérez Sanmartín', 'TI', '1020226169', '', 2, NULL, '2010-08-19', '52.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(106, 9, 1, 'Juan Camilo', 'Martínez Sanmartín', 'TI', '1020229154', '', 2, NULL, '2013-01-18', '37.00', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(107, 5, 6, 'Salome', 'Taborda Blando', 'TI', '1020226181', '', 2, NULL, '2010-08-20', '67.70', NULL, 'savia', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(108, 9, 4, 'Matías', 'García León', 'TI', '1033265504', '', 2, NULL, '2015-04-17', '30.00', NULL, 'sanita', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(109, 5, 4, 'Juan Pablo', 'Betancourt Ospina', 'TI', '1017265346', '', 2, NULL, '2015-04-13', '42.30', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(110, 4, 4, 'Juan Camilo', 'Vega O', 'TI', '1021937647', '', 2, NULL, '2015-07-02', '44.30', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(111, 1, 6, 'Danna Sofia', 'Alfonso', 'TI', '1011405201', '', 2, NULL, '2012-03-27', '58.90', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(112, 8, 6, 'Samir Enrique', 'Nava Martínez', 'TI', '7235701', '', 1, NULL, '2009-11-12', '70.80', NULL, 'savia', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(113, 7, 6, 'Matías', 'Ochoa García', 'TI', '1025661867', '', 1, NULL, '2009-06-03', '53.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(114, 7, 4, 'Maximiliano', 'Carvajal Ruiz', 'TI', '10376553567', '', 1, NULL, '2014-12-06', '40.00', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(115, 6, 6, 'Sofia', 'Medina Castrillon', 'TI', '1027741702', '', 2, NULL, '2010-01-26', '56.40', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(116, 3, 1, 'Dylan Andrés', 'Gaviria Alvarez', 'TI', '1233898589', '', 2, NULL, '2016-05-21', '27.80', NULL, 'Mutual', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(117, 3, 4, 'Santiago Andres', 'Hernandez Torres', 'TI', '1103755101', '', 2, NULL, '2015-08-25', '27.00', NULL, 'militar', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(118, 3, 4, 'Smith', 'Méndez Alvarez', 'TI', '1032059029', '', 1, NULL, '2016-07-02', '34.20', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(119, 9, 6, 'Aaron David', 'Loaiza Monroy', 'TI', '1422730', '', 2, NULL, '2010-09-14', '63.30', NULL, 'sura', 'O-', NULL, NULL, 1, '2026-08-29 17:59:05'),
(120, 11, 1, 'Anderson Steven', 'Loaiza Quintero', 'CC', '1033426095', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05'),
(121, 11, 1, 'Nicolás', 'Osorio Valencia', 'TI', '1054875293', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05'),
(122, 9, 4, 'Jerónimo', 'Osorio Valencia', 'TI', '1054882227', '', 2, NULL, '2013-10-22', '45.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(123, 11, 1, 'Juan David', 'Orrego Ossa', 'CC', '1020419153', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-29 17:59:05'),
(124, 9, 1, 'Sara', 'Ríos Daza', 'CC', '1022152029', '', 2, NULL, '2010-09-06', '49.00', NULL, 'ponal', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(125, 4, 1, 'Ana Sofía', 'Hurtado Ocampo', 'TI', '1036259874', '', 3, NULL, '2011-05-19', '68.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(126, 9, 1, 'José Ignacio', 'Marín Vásquez', 'TI', '1232598434', '', 2, NULL, '2014-01-04', '36.80', NULL, 'Total', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(127, 9, 1, 'Marcelo Gabriel', 'Marín Vázquez', 'TI', '1232598435', '', 2, NULL, '2010-09-08', '51.00', NULL, 'Total', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(128, 7, 6, 'Juan Camilo', 'García Barba', 'TI', '1023637476', '', 1, NULL, '2009-06-03', '89.00', NULL, 'Savia', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(129, 9, 6, 'Samuel Cano', 'Pulgarin', 'TI', '1033491933', '', 2, NULL, '2009-05-14', '55.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(130, 1, 6, 'Hillary', 'Gómez García', 'TI', '119246017', '', 2, NULL, '2011-11-03', '45.50', NULL, 'sura', 'A-', NULL, NULL, 1, '2026-08-29 17:59:05'),
(131, 11, 6, 'Diego Fernando', 'Salcedo Bonza', 'TI', '1096807375', '', 2, NULL, '2011-03-04', '60.00', NULL, 'Sanita', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(132, 1, 4, 'Rubiangelys Sofía', 'Camacho Figueroa', 'TI', '6164279', '', 2, NULL, '2013-11-17', '50.00', NULL, 'Savia', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(133, 1, 1, 'Jeziel Abrahán', 'Ruiz Figueroa', 'TI', '1087754585', '', 2, NULL, '2020-05-26', '19.00', NULL, 'savia', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(134, 1, 1, 'Juan José', 'Diosa Ospina', 'TI', '1013464771', '', 2, NULL, '2013-02-22', '57.00', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(135, 9, 6, 'Kevin Andrés', 'Herrera', 'TI', '1037126322', '', 2, NULL, '2010-11-16', '63.00', NULL, 'total', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(136, 9, 6, 'Samuel', 'Mejía Villa', 'TI', '1088302615', '', 2, NULL, '2010-07-29', '63.00', NULL, 'Sura', 'O-', NULL, NULL, 1, '2026-08-29 17:59:05'),
(137, 9, 2, 'Emanuel', 'Alcaraz Ocampo', 'TI', '1011519226', '', 2, NULL, '2017-11-12', '43.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(138, 2, 7, 'Ana Sofía', 'Flórez Guzmán', 'TI', '1020224629', '', 1, NULL, '2008-06-13', '56.00', NULL, 'sura', 'A-', NULL, NULL, 1, '2026-08-29 17:59:05'),
(139, 8, 1, 'Samuel', 'Valencia Tabares', 'TI', '1192467492', '', 2, NULL, '2012-03-08', '40.80', NULL, 'Sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(140, 9, 7, 'Sarah Sofía', 'Triviño Saavedra', 'TI', '1094911412', '', 2, NULL, '2008-02-19', '57.00', NULL, 'suri', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(141, 5, 6, 'Ana Sofía', 'Quiroz Puerta', 'TI', '1021927403', '', 2, NULL, '2009-07-02', '60.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(142, 4, 1, 'Ismael', 'Arboleda Gutiérrez', 'TI', '1020123545', '', 2, NULL, '2015-05-20', '39.00', NULL, 'Sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(143, 9, 6, 'Valeria', 'Matiz Escudero', 'TI', '1028141323', '', 2, NULL, '2008-11-13', '63.00', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(144, 7, 6, 'Mariana', 'Giraldo Rincon', 'TI', '1027809885', '', 2, NULL, '2011-03-12', '36.00', NULL, 'sura', 'A+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(145, 4, 6, 'Jimena', 'Velásquez Ospina', 'TI', '1011594301', '', 1, NULL, '2009-01-12', '70.00', NULL, 'nueva', NULL, NULL, NULL, 1, '2026-08-29 17:59:05'),
(146, 9, 1, 'Luciana', 'Pino Monsalve', 'TI', '1020122879', '', 2, NULL, '2014-09-16', '0.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(147, 9, 4, 'Gabriela', 'Almenares Fonegra', 'TI', '1020120658', '', 2, NULL, '2012-02-23', '47.00', NULL, 'sura', 'O+', NULL, NULL, 1, '2026-08-29 17:59:05'),
(148, 8, 1, 'Ana Sofia', 'Sánchez Agudelo', 'TI', '1011222665', '', 2, NULL, '2013-01-27', '60.00', NULL, 'Policía', NULL, NULL, NULL, 1, '2026-08-29 17:59:05'),
(153, 1, 1, 'Mateo', 'Ríos', 'TI', '10000002', '', 2, NULL, NULL, NULL, NULL, NULL, NULL, 'estudiante@jinhwan.com', '$2y$10$T7OWSqUPLN2lIqOAaJTQsuW1oDhHYcs/cJrPg0b2q1tjIWVYNWIYu', 1, '2026-08-29 17:59:05'),
(154, 7, 5, 'maria jose', 'gomez londoño', 'TI', '1013462218', '3246783188', 1, NULL, '2010-11-11', '60.00', '-65', 'Sura', 'O+', 'samugomedo0@gmail.com', '$2y$10$lp.U80H.P2fdX5qJKbU8/upbxvDswylhNls8z8RZgBaUp0cgRSyNK', 1, '2026-09-06 01:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id_evento` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `id_maestro` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeria_multimedia`
--

CREATE TABLE `galeria_multimedia` (
  `id_multimedia` int NOT NULL,
  `id_persona` int DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `galeria_multimedia`
--

INSERT INTO `galeria_multimedia` (`id_multimedia`, `id_persona`, `url`, `titulo`, `descripcion`) VALUES
(3, 119, 'https://www.youtube.com/watch?v=5ZXHQDD2ITs', NULL, NULL),
(4, 154, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grados`
--

CREATE TABLE `grados` (
  `id_grado` int NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `grados`
--

INSERT INTO `grados` (`id_grado`, `nombre`) VALUES
(1, 'Blanco'),
(2, 'Pinta Amarillo'),
(3, 'Amarillo'),
(4, 'Pinta Verde'),
(5, 'Verde'),
(6, 'Pinta Azul'),
(7, 'Azul'),
(8, 'Pinta Rojo'),
(9, 'Rojo'),
(10, 'Pinta Negro'),
(11, 'Negro 1 Dan'),
(12, 'Negro 2 Dan'),
(13, 'Negro 3 Dan'),
(14, 'Negro 4 Dan'),
(15, 'Negro 5 Dan'),
(16, 'Negro 6 Dan'),
(17, 'Negro 7 Dan'),
(18, 'Negro 8 Dan'),
(19, 'Negro 9 Dan'),
(20, 'Ninguno');

-- --------------------------------------------------------

--
-- Table structure for table `maestro`
--

CREATE TABLE `maestro` (
  `id_maestro` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT 'CC',
  `num_doc` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `id_sede` int DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `descripcion_perfil` text,
  `logros` text,
  `mostrar_en_web` tinyint(1) DEFAULT '0',
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `permisos_extra` json DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `maestro`
--

INSERT INTO `maestro` (`id_maestro`, `id_grado`, `nombre`, `apellido`, `tipo_documento`, `num_doc`, `telefono`, `id_sede`, `foto_perfil`, `descripcion_perfil`, `logros`, `mostrar_en_web`, `correo`, `clave`, `permisos_extra`, `activo`, `created_at`) VALUES
(150, 11, 'Samuel', 'Gómez', 'TI', '1013462218', '3246783188', 1, '606e99e11b6916d3ccf18632109d0667.jpg', 'Profesor de Taekwondo federado y creador de la plataforma', 'Campeón olímpico', 0, 'samugomedo@gmail.com', '$2y$10$7MwlMj3DwkcViNNvF1rscugFN4Cko0.OzeysQGMJL34cSm2qmX.le', '{\"sedes\": false, \"galeria\": false, \"ascensos\": false, \"reportes\": false, \"registros\": false, \"calendario\": false}', 1, '2026-08-29 17:59:05'),
(152, 11, 'Carlos', 'Mendoza', 'CC', '10000001', '', 2, NULL, 'Maestro principal de la sede', 'Cinturón Negro 1 Dan', 0, 'maestro@jinhwan.com', '$2y$10$Tn44kYfa6/Jacswrc8IR8unk0GpBthtGDc3iHK1JZfWbeSJHf8nG.', '{\"sedes\": true, \"galeria\": true, \"ascensos\": true, \"reportes\": true, \"registros\": true, \"calendario\": true}', 1, '2026-08-29 17:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `sedes`
--

CREATE TABLE `sedes` (
  `id_sede` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre`, `direccion`, `telefono`, `horario`) VALUES
(1, 'Sede San Cristóbal', 'Cl. 62 #131-80, Nazaret, San Cristóbal, Medellín, Antioquia', '3206641361', 'Lun - Vie: 4:00 PM - 8:00 PM'),
(2, 'Sede Principal Santa Mónica Campo Alegre', 'Cl 38 #9255, Belencito, Medellín, La América, Medellín, Antioquia', '3206641361', 'Lun - Sáb: 3:00 PM - 9:00 PM'),
(3, 'Sede Itagüí', 'Cra. 59 #70-349, Alicate, Itagüí, Antioquia', '3042243561', 'Mar - Sáb: 4:00 PM - 8:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `teorias`
--

CREATE TABLE `teorias` (
  `id_teoria` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `id_tipo_teoria` int DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contenido` text,
  `url_video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipos_teoria`
--

CREATE TABLE `tipos_teoria` (
  `id_tipo_teoria` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tipos_teoria`
--

INSERT INTO `tipos_teoria` (`id_tipo_teoria`, `nombre`, `descripcion`) VALUES
(1, 'General', 'Conceptos fundamentales de Taekwondo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_administrador`),
  ADD UNIQUE KEY `uk_admin_correo` (`correo`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `certificados_ascenso`
--
ALTER TABLE `certificados_ascenso`
  ADD PRIMARY KEY (`id_certificado`),
  ADD KEY `fk_cert_estudiante` (`id_estudiante`),
  ADD KEY `fk_cert_maestro` (`id_maestro`);

--
-- Indexes for table `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `idx_estudiante_grado` (`id_grado`),
  ADD KEY `idx_estudiante_categoria` (`id_categoria`),
  ADD KEY `idx_estudiante_sede` (`id_sede`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `idx_eventos_maestro` (`id_maestro`);

--
-- Indexes for table `galeria_multimedia`
--
ALTER TABLE `galeria_multimedia`
  ADD PRIMARY KEY (`id_multimedia`);

--
-- Indexes for table `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`);

--
-- Indexes for table `maestro`
--
ALTER TABLE `maestro`
  ADD PRIMARY KEY (`id_maestro`),
  ADD UNIQUE KEY `uk_maestro_correo` (`correo`),
  ADD KEY `idx_maestro_grado` (`id_grado`),
  ADD KEY `idx_maestro_sede` (`id_sede`);

--
-- Indexes for table `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id_sede`);

--
-- Indexes for table `teorias`
--
ALTER TABLE `teorias`
  ADD PRIMARY KEY (`id_teoria`),
  ADD KEY `idx_teoria_grado` (`id_grado`),
  ADD KEY `idx_teoria_tipo` (`id_tipo_teoria`);

--
-- Indexes for table `tipos_teoria`
--
ALTER TABLE `tipos_teoria`
  ADD PRIMARY KEY (`id_tipo_teoria`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `certificados_ascenso`
--
ALTER TABLE `certificados_ascenso`
  MODIFY `id_certificado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galeria_multimedia`
--
ALTER TABLE `galeria_multimedia`
  MODIFY `id_multimedia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teorias`
--
ALTER TABLE `teorias`
  MODIFY `id_teoria` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipos_teoria`
--
ALTER TABLE `tipos_teoria`
  MODIFY `id_tipo_teoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificados_ascenso`
--
ALTER TABLE `certificados_ascenso`
  ADD CONSTRAINT `fk_cert_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiante` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cert_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE CASCADE;

--
-- Constraints for table `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `fk_estudiante_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_estudiante_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_estudiante_sede` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_eventos_maestro` FOREIGN KEY (`id_maestro`) REFERENCES `maestro` (`id_maestro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maestro`
--
ALTER TABLE `maestro`
  ADD CONSTRAINT `fk_maestro_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_maestro_sede` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `teorias`
--
ALTER TABLE `teorias`
  ADD CONSTRAINT `fk_teoria_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teoria_tipo` FOREIGN KEY (`id_tipo_teoria`) REFERENCES `tipos_teoria` (`id_tipo_teoria`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
