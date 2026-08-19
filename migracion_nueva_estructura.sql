-- =============================================================================
-- SCRIPT DE MIGRACIÓN Y NUEVA ESTRUCTURA NORMALIZADA
-- JINHWA CORPORATION - BASE DE DATOS
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- -----------------------------------------------------------------------------
-- 1. TABLA: sedes
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sedes` (
  `id_sede` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `horario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_sede`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- 2. TABLA: categorias
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- 3. TABLA: grados
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `grados` (
  `id_grado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_grado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- 4. TABLA BASE: personas (Datos personales generales)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `personas` (
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

-- -----------------------------------------------------------------------------
-- 5. TABLA: credenciales (Autenticación y roles de acceso)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `credenciales` (
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

-- -----------------------------------------------------------------------------
-- 6. TABLA: perfil_deportistas (Subtipo: Información técnica de Taekwondo)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfil_deportistas` (
  `id_persona` int NOT NULL,
  `id_grado` int DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `fecha_n` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `ctgc` varchar(100) DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `rh` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_persona`),
  KEY `idx_deportista_grado` (`id_grado`),
  KEY `idx_deportista_categoria` (`id_categoria`),
  CONSTRAINT `fk_perfil_deportista_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_perfil_deportista_grado` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_perfil_deportista_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- 7. TABLA: perfil_maestros (Subtipo: Información para docentes y web)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `perfil_maestros` (
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

-- -----------------------------------------------------------------------------
-- 8. TABLA: solicitudes_ascenso
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `solicitudes_ascenso` (
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

-- -----------------------------------------------------------------------------
-- 9. TABLA: eventos
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `eventos` (
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

-- -----------------------------------------------------------------------------
-- 10. TABLA: noticias
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `noticias` (
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

-- -----------------------------------------------------------------------------
-- 11. TABLA: tipos_teoria
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tipos_teoria` (
  `id_tipo_teoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_teoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- 12. TABLA: teorias
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `teorias` (
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

-- -----------------------------------------------------------------------------
-- 13. TABLA: galeria_multimedia
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galeria_multimedia` (
  `id_multimedia` int NOT NULL AUTO_INCREMENT,
  `id_persona` int DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_multimedia`),
  KEY `idx_galeria_persona` (`id_persona`),
  CONSTRAINT `fk_galeria_persona` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
