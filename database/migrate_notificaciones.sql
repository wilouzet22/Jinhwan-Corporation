-- Migración para el Sistema de Notificaciones de Jinhwan Corporation
-- Compatible con MariaDB / MySQL en InfinityFree y Laragon

CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL DEFAULT 'sistema' COMMENT 'caracterizacion_nueva, caracterizacion_update, registro, ascenso, sistema',
  `titulo` varchar(150) NOT NULL,
  `mensaje` text NOT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_notificacion`),
  KEY `idx_leida` (`leida`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
