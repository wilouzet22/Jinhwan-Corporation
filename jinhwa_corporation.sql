-- phpMyAdmin SQL Dump
-- Database: `jinhwa_corporation`
-- Estructura normalizada y optimizada con separación de personas, credenciales y perfiles especializados

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- LIMPIEZA DE TABLAS Y VISTAS PREVIAS
-- --------------------------------------------------------
DROP TABLE IF EXISTS `solicitudes_ascenso`;
DROP TABLE IF EXISTS `galeria_multimedia`;
DROP TABLE IF EXISTS `noticias`;
DROP TABLE IF EXISTS `eventos`;
DROP TABLE IF EXISTS `teorias`;
DROP TABLE IF EXISTS `tipos_teoria`;
DROP TABLE IF EXISTS `tipo_de_teoria`;
DROP TABLE IF EXISTS `teoria`;
DROP TABLE IF EXISTS `multimedia_galeria`;
DROP TABLE IF EXISTS `perfil_deportistas`;
DROP TABLE IF EXISTS `perfil_maestros`;
DROP TABLE IF EXISTS `credenciales`;
DROP TABLE IF EXISTS `userlog`;
DROP TABLE IF EXISTS `miembros`;
DROP TABLE IF EXISTS `personas`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `categoria`;
DROP TABLE IF EXISTS `grados`;
DROP TABLE IF EXISTS `sedes`;

-- --------------------------------------------------------

-- --------------------------------------------------------
-- 1. Tabla: `sedes`
-- --------------------------------------------------------
CREATE TABLE `sedes` (
  `id_sede` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_sede`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `sedes` (`id_sede`, `nombre`, `direccion`, `telefono`, `horario`) VALUES
(1, 'Sede San Cristóbal', 'Cl. 62 #131-80, Nazaret, San Cristóbal, Medellín, Antioquia', '3206641361', 'Lun - Vie: 4:00 PM - 8:00 PM'),
(2, 'Sede Principal Santa Mónica Campo Alegre', 'Cl 38 #9255, Belencito, Medellín, La América, Medellín, Antioquia', '3206641361', 'Lun - Sáb: 3:00 PM - 9:00 PM'),
(3, 'Sede Itagüí', 'Cra. 59 #70-349, Alicate, Itagüí, Antioquia', '3042243561', 'Mar - Sáb: 4:00 PM - 8:00 PM');

-- --------------------------------------------------------
-- 2. Tabla: `categorias`
-- --------------------------------------------------------
CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- 3. Tabla: `grados`
-- --------------------------------------------------------
CREATE TABLE `grados` (
  `id_grado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_grado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- 4. Tabla Base: `personas` (Datos personales y comunes)
-- --------------------------------------------------------
CREATE TABLE `personas` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT 'TI',
  `num_doc` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `id_sede` int DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_persona`),
  KEY `idx_personas_sede` (`id_sede`),
  KEY `idx_personas_num_doc` (`num_doc`),
  CONSTRAINT `fk_personas_sede` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `personas` (`id_persona`, `nombre`, `apellido`, `tipo_documento`, `num_doc`, `telefono`, `id_sede`, `foto_perfil`, `activo`) VALUES
(101, 'Jean Karlo', 'García León', 'TI', '1192466428', '', 2, NULL, 1),
(102, 'Samuel', 'Velásquez Sánchez', 'TI', '1023647991', '', 2, NULL, 1),
(103, 'Daniel Andrés', 'Montoya Calle', 'TI', '1021940897', '', 2, NULL, 1),
(104, 'Miguel Ángel', 'Bautista Monroy', 'TI', '1195214019', '', 2, NULL, 1),
(105, 'Daniel', 'Pérez Sanmartín', 'TI', '1020226169', '', 2, NULL, 1),
(106, 'Juan Camilo', 'Martínez Sanmartín', 'TI', '1020229154', '', 2, NULL, 1),
(107, 'Salome', 'Taborda Blando', 'TI', '1020226181', '', 2, NULL, 1),
(108, 'Matías', 'García León', 'TI', '1033265504', '', 2, NULL, 1),
(109, 'Juan Pablo', 'Betancourt Ospina', 'TI', '1017265346', '', 2, NULL, 1),
(110, 'Juan Camilo', 'Vega O', 'TI', '1021937647', '', 2, NULL, 1),
(111, 'Danna Sofia', 'Alfonso', 'TI', '1011405201', '', 2, NULL, 1),
(112, 'Samir Enrique', 'Nava Martínez', 'TI', '7235701', '', 1, NULL, 1),
(113, 'Matías', 'Ochoa García', 'TI', '1025661867', '', 1, NULL, 1),
(114, 'Maximiliano', 'Carvajal Ruiz', 'TI', '10376553567', '', 1, NULL, 1),
(115, 'Sofia', 'Medina Castrillon', 'TI', '1027741702', '', 2, NULL, 1),
(116, 'Dylan Andrés', 'Gaviria Alvarez', 'TI', '1233898589', '', 2, NULL, 1),
(117, 'Santiago Andres', 'Hernandez Torres', 'TI', '1103755101', '', 2, NULL, 1),
(118, 'Smith', 'Méndez Alvarez', 'TI', '1032059029', '', 1, NULL, 1),
(119, 'Aaron David', 'Loaiza Monroy', 'TI', '1422730', '', 2, NULL, 1),
(120, 'Anderson Steven', 'Loaiza Quintero', 'CC', '1033426095', '', 2, NULL, 1),
(121, 'Nicolás', 'Osorio Valencia', 'TI', '1054875293', '', 2, NULL, 1),
(122, 'Jerónimo', 'Osorio Valencia', 'TI', '1054882227', '', 2, NULL, 1),
(123, 'Juan David', 'Orrego Ossa', 'CC', '1020419153', '', 2, NULL, 1),
(124, 'Sara', 'Ríos Daza', 'CC', '1022152029', '', 2, NULL, 1),
(125, 'Ana Sofía', 'Hurtado Ocampo', 'TI', '1036259874', '', 3, NULL, 1),
(126, 'José Ignacio', 'Marín Vásquez', 'TI', '1232598434', '', 2, NULL, 1),
(127, 'Marcelo Gabriel', 'Marín Vázquez', 'TI', '1232598435', '', 2, NULL, 1),
(128, 'Juan Camilo', 'García Barba', 'TI', '1023637476', '', 1, NULL, 1),
(129, 'Samuel Cano', 'Pulgarin', 'TI', '1033491933', '', 2, NULL, 1),
(130, 'Hillary', 'Gómez García', 'TI', '119246017', '', 2, NULL, 1),
(131, 'Diego Fernando', 'Salcedo Bonza', 'TI', '1096807375', '', 2, NULL, 1),
(132, 'Rubiangelys Sofía', 'Camacho Figueroa', 'TI', '6164279', '', 2, NULL, 1),
(133, 'Jeziel Abrahán', 'Ruiz Figueroa', 'TI', '1087754585', '', 2, NULL, 1),
(134, 'Juan José', 'Diosa Ospina', 'TI', '1013464771', '', 2, NULL, 1),
(135, 'Kevin Andrés', 'Herrera', 'TI', '1037126322', '', 2, NULL, 1),
(136, 'Samuel', 'Mejía Villa', 'TI', '1088302615', '', 2, NULL, 1),
(137, 'Emanuel', 'Alcaraz Ocampo', 'TI', '1011519226', '', 2, NULL, 1),
(138, 'Ana Sofía', 'Flórez Guzmán', 'TI', '1020224629', '', 1, NULL, 1),
(139, 'Samuel', 'Valencia Tabares', 'TI', '1192467492', '', 2, NULL, 1),
(140, 'Sarah Sofía', 'Triviño Saavedra', 'TI', '1094911412', '', 2, NULL, 1),
(141, 'Ana Sofía', 'Quiroz Puerta', 'TI', '1021927403', '', 2, NULL, 1),
(142, 'Ismael', 'Arboleda Gutiérrez', 'TI', '1020123545', '', 2, NULL, 1),
(143, 'Valeria', 'Matiz Escudero', 'TI', '1028141323', '', 2, NULL, 1),
(144, 'Mariana', 'Giraldo Rincon', 'TI', '1027809885', '', 2, NULL, 1),
(145, 'Jimena', 'Velásquez Ospina', 'TI', '1011594301', '', 1, NULL, 1),
(146, 'Luciana', 'Pino Monsalve', 'TI', '1020122879', '', 2, NULL, 1),
(147, 'Gabriela', 'Almenares Fonegra', 'TI', '1020120658', '', 2, NULL, 1),
(148, 'Ana Sofia', 'Sánchez Agudelo', 'TI', '1011222665', '', 2, NULL, 1),
(149, 'Administrador', 'General', 'CC', '99999999', '', 2, NULL, 1),
(150, 'Samuel', 'Gómez', 'TI', '1013462218', '3246783188', 1, '606e99e11b6916d3ccf18632109d0667.jpg', 1),
(152, 'Carlos', 'Mendoza', 'CC', '10000001', '', 2, NULL, 1),
(153, 'Mateo', 'Ríos', 'TI', '10000002', '', 2, NULL, 1);

-- --------------------------------------------------------
-- 5. Tabla: `credenciales` (Login y Autenticación)
-- --------------------------------------------------------
CREATE TABLE `credenciales` (
  `id_credencial` int NOT NULL AUTO_INCREMENT,
  `id_persona` int NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'Deportistas',
  `permisos_extra` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_credencial`),
  UNIQUE KEY `uk_credenciales_persona` (`id_persona`),
  UNIQUE KEY `uk_credenciales_correo` (`correo`),
  CONSTRAINT `fk_credenciales_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `credenciales` (`id_credencial`, `id_persona`, `correo`, `clave`, `rol`, `permisos_extra`) VALUES
(12, 149, 'admin@admin.com', '$2y$10$45YUeh7Y/t9J99i86uA1Fud5ekOo1FMdYaVD1vZkGy5zN5GQCWP9a', 'Administracion', '{\"sedes\":true,\"registros\":true,\"ascensos\":true,\"calendario\":true,\"galeria\":true,\"reportes\":true}'),
(14, 150, 'samugomedo@gmail.com', '$2y$10$7MwlMj3DwkcViNNvF1rscugFN4Cko0.OzeysQGMJL34cSm2qmX.le', 'Maestros', '{\"sedes\":false,\"registros\":false,\"ascensos\":false,\"calendario\":false,\"galeria\":false,\"reportes\":false}'),
(17, 152, 'maestro@jinhwan.com', '$2y$10$Tn44kYfa6/Jacswrc8IR8unk0GpBthtGDc3iHK1JZfWbeSJHf8nG.', 'Maestros', '{\"sedes\":true,\"registros\":true,\"ascensos\":true,\"calendario\":true,\"galeria\":true,\"reportes\":true}'),
(18, 153, 'estudiante@jinhwan.com', '$2y$10$T7OWSqUPLN2lIqOAaJTQsuW1oDhHYcs/cJrPg0b2q1tjIWVYNWIYu', 'Deportistas', NULL);

-- --------------------------------------------------------
-- 6. Tabla: `perfil_deportistas` (Información Deportiva)
-- --------------------------------------------------------
CREATE TABLE `perfil_deportistas` (
  `id_persona` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `fecha_n` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `rh` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_persona`),
  KEY `idx_deportista_grado` (`id_grado`),
  KEY `idx_deportista_categoria` (`id_categoria`),
  CONSTRAINT `fk_perfil_deportista_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_perfil_deportista_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_perfil_deportista_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `perfil_deportistas` (`id_persona`, `id_grado`, `id_categoria`, `fecha_n`, `peso`, `division`, `eps`, `rh`) VALUES
(102, 9, 4, '2014-07-06', '38.00', NULL, 'Sisbén', 'O+'),
(103, 7, 4, '2016-11-07', '44.60', NULL, 'N eps', 'O+'),
(104, 5, 4, '2013-08-13', '45.00', NULL, 'sisben', 'A+'),
(105, 8, 6, '2010-08-19', '52.00', NULL, 'sura', 'O+'),
(106, 9, 1, '2013-01-18', '37.00', NULL, 'sura', 'A+'),
(107, 5, 6, '2010-08-20', '67.70', NULL, 'savia', 'O+'),
(108, 9, 4, '2015-04-17', '30.00', NULL, 'sanita', 'O+'),
(109, 5, 4, '2015-04-13', '42.30', NULL, 'sura', 'O+'),
(110, 4, 4, '2015-07-02', '44.30', NULL, 'Sura', 'O+'),
(111, 1, 6, '2012-03-27', '58.90', NULL, 'sura', 'O+'),
(112, 8, 6, '2009-11-12', '70.80', NULL, 'savia', 'O+'),
(113, 7, 6, '2010-12-07', '53.00', NULL, 'Sura', 'O+'),
(114, 7, 4, '2014-12-06', '40.00', NULL, 'Sura', 'O+'),
(115, 7, 6, '2010-01-26', '56.40', NULL, 'Sura', 'O+'),
(116, 6, 1, '2016-05-21', '27.80', NULL, 'Mutual', 'A+'),
(117, 3, 4, '2015-08-25', '27.00', NULL, 'militar', 'O+'),
(118, 3, 4, '2016-07-02', '34.20', NULL, 'sura', 'A+'),
(119, 9, 6, '2010-09-14', '63.30', NULL, 'sura', 'O-'),
(122, 9, 4, '2013-10-22', '45.00', NULL, 'sura', 'O+'),
(124, 9, 1, '2010-09-06', '49.00', NULL, 'ponal', 'O+'),
(125, 4, 1, '2011-05-19', '68.00', NULL, 'sura', 'O+'),
(126, 9, 1, '2014-01-04', '36.80', NULL, 'Total', 'O+'),
(127, 9, 1, '2010-09-08', '51.00', NULL, 'Total', 'O+'),
(128, 7, 6, '2009-06-03', '89.00', NULL, 'Savia', 'A+'),
(129, 9, 6, '2009-05-14', '55.00', NULL, 'sura', 'O+'),
(130, 1, 6, '2011-11-03', '45.50', NULL, 'sura', 'A-'),
(131, 11, 6, '2011-03-04', '60.00', NULL, 'Sanita', 'O+'),
(132, 1, 4, '2013-11-17', '50.00', NULL, 'Savia', 'A+'),
(133, 1, 1, '2020-05-26', '19.00', NULL, 'savia', 'A+'),
(134, 1, 1, '2013-02-22', '57.00', NULL, 'Sura', 'O+'),
(135, 9, 6, '2010-11-16', '63.00', NULL, 'total', 'O+'),
(136, 9, 6, '2010-07-29', '63.00', NULL, 'Sura', 'O-'),
(137, 9, 2, '2017-11-12', '43.00', NULL, 'Sura', 'A+'),
(138, 2, 7, '2008-06-13', '56.00', NULL, 'sura', 'A-'),
(139, 8, 1, '2012-03-08', '40.80', NULL, 'Sura', 'O+'),
(140, 9, 7, '2008-02-19', '57.00', NULL, 'suri', 'O+'),
(141, 5, 6, '2009-07-02', '60.00', NULL, 'Sura', 'A+'),
(142, 4, 1, '2015-05-20', '39.00', NULL, 'Sura', 'A+'),
(143, 9, 6, '2008-11-13', '63.00', NULL, 'sura', 'A+'),
(144, 7, 6, '2011-03-12', '36.00', NULL, 'sura', 'A+'),
(145, 4, 6, '2009-01-12', '70.00', NULL, 'nueva', NULL),
(146, 9, 1, '2014-09-16', '0.00', NULL, 'sura', 'O+'),
(147, 9, 4, '2012-02-23', '47.00', NULL, 'sura', 'O+'),
(148, 7, 1, '2013-01-27', '60.00', NULL, 'Policía', NULL),
(150, 11, 7, '2008-11-14', '74.00', '-80', 'sanita', 'A+'),
(153, 1, 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------
-- 7. Tabla: `perfil_maestros` (Información Docente/Web)
-- --------------------------------------------------------
CREATE TABLE `perfil_maestros` (
  `id_persona` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `descripcion_perfil` text,
  `logros` text,
  `mostrar_en_web` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_persona`),
  KEY `idx_maestro_grado` (`id_grado`),
  CONSTRAINT `fk_perfil_maestro_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_perfil_maestro_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `perfil_maestros` (`id_persona`, `id_grado`, `descripcion_perfil`, `logros`, `mostrar_en_web`) VALUES
(101, 11, NULL, NULL, 0),
(119, 9, '', NULL, 1),
(120, 11, NULL, NULL, 0),
(121, 11, NULL, NULL, 0),
(123, 11, NULL, NULL, 0),
(150, 11, 'Profesor de Taekwondo federado y creador de la plataforma', 'Campeón olímpico', 1),
(152, 11, 'Maestro principal de la sede', 'Cinturón Negro 1 Dan', 0);

-- --------------------------------------------------------
-- 8. Tabla: `solicitudes_ascenso`
-- --------------------------------------------------------
CREATE TABLE `solicitudes_ascenso` (
  `id_solicitud` int NOT NULL AUTO_INCREMENT,
  `id_persona_estudiante` int NOT NULL,
  `id_grado_actual` int NOT NULL,
  `id_grado_solicitado` int NOT NULL,
  `id_persona_maestro` int NOT NULL,
  `observaciones` text,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_resolucion` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`),
  KEY `idx_solicitud_estudiante` (`id_persona_estudiante`),
  KEY `idx_solicitud_maestro` (`id_persona_maestro`),
  KEY `idx_solicitud_grado_actual` (`id_grado_actual`),
  KEY `idx_solicitud_grado_solicitado` (`id_grado_solicitado`),
  CONSTRAINT `fk_solicitud_estudiante` FOREIGN KEY (`id_persona_estudiante`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_maestro` FOREIGN KEY (`id_persona_maestro`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_grado_act` FOREIGN KEY (`id_grado_actual`) REFERENCES `grados` (`id_grado`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_solicitud_grado_sol` FOREIGN KEY (`id_grado_solicitado`) REFERENCES `grados` (`id_grado`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- 9. Tabla: `certificados_ascenso`
-- --------------------------------------------------------
CREATE TABLE `certificados_ascenso` (
  `id_certificado`   INT          NOT NULL AUTO_INCREMENT,
  `id_solicitud`     INT          NOT NULL,
  `id_persona`       INT          NOT NULL,
  `id_maestro`       INT          NOT NULL,
  `grado_anterior`   VARCHAR(80)  NOT NULL,
  `grado_nuevo`      VARCHAR(80)  NOT NULL,
  `fecha_examen`     DATE         NOT NULL,
  `observaciones`    TEXT,
  `folio`            VARCHAR(30)  NOT NULL,
  `creado_en`        TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_certificado`),
  UNIQUE KEY `uk_solicitud` (`id_solicitud`),
  CONSTRAINT `fk_cert_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_ascenso` (`id_solicitud`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_persona`   FOREIGN KEY (`id_persona`)   REFERENCES `personas` (`id_persona`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_maestro`   FOREIGN KEY (`id_maestro`)   REFERENCES `personas` (`id_persona`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 10. Tabla: `eventos`
-- --------------------------------------------------------
CREATE TABLE `eventos` (
  `id_evento` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `id_persona` int NOT NULL,
  PRIMARY KEY (`id_evento`),
  KEY `idx_eventos_persona` (`id_persona`),
  CONSTRAINT `fk_eventos_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- 11. Tabla: `noticias`
-- --------------------------------------------------------
CREATE TABLE `noticias` (
  `id_noticia` int NOT NULL AUTO_INCREMENT,
  `id_persona` int DEFAULT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `contenido` text,
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_noticia`),
  KEY `idx_noticias_persona` (`id_persona`),
  CONSTRAINT `fk_noticias_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- 12. Tabla: `tipos_teoria`
-- --------------------------------------------------------
CREATE TABLE `tipos_teoria` (
  `id_tipo_teoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_teoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tipos_teoria` (`id_tipo_teoria`, `nombre`, `descripcion`) VALUES
(1, 'General', 'Conceptos fundamentales de Taekwondo');

-- --------------------------------------------------------
-- 13. Tabla: `teorias`
-- --------------------------------------------------------
CREATE TABLE `teorias` (
  `id_teoria` int NOT NULL AUTO_INCREMENT,
  `id_grado` int DEFAULT NULL,
  `id_tipo_teoria` int DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contenido` text,
  `url_video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_teoria`),
  KEY `idx_teoria_grado` (`id_grado`),
  KEY `idx_teoria_tipo` (`id_tipo_teoria`),
  CONSTRAINT `fk_teoria_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_teoria_tipo` FOREIGN KEY (`id_tipo_teoria`) REFERENCES `tipos_teoria` (`id_tipo_teoria`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- 13. Tabla: `galeria_multimedia`
-- --------------------------------------------------------
CREATE TABLE `galeria_multimedia` (
  `id_multimedia` int NOT NULL AUTO_INCREMENT,
  `id_persona` int DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_multimedia`),
  KEY `idx_galeria_persona` (`id_persona`),
  CONSTRAINT `fk_galeria_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `galeria_multimedia` (`id_multimedia`, `id_persona`, `url`, `titulo`, `descripcion`) VALUES
(3, 119, 'https://www.youtube.com/watch?v=5ZXHQDD2ITs', NULL, NULL);

-- =============================================================================
-- FINALIZACIÓN Y COMPROMISO DE TRANSACCIÓN
-- =============================================================================
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
