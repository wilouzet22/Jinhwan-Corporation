-- ==============================================================================
-- Migración: Tabla de Favoritos de Teoría para Estudiantes
-- Compatible con MariaDB / MySQL en InfinityFree (phpMyAdmin) y Laragon
-- Ejecutar una sola vez en phpMyAdmin antes de usar la función "Mi Teoría"
-- ==============================================================================

CREATE TABLE IF NOT EXISTS `teoria_favoritos` (
    `id`            INT          NOT NULL AUTO_INCREMENT,
    `id_estudiante` INT          NOT NULL,
    `id_teoria`     INT          NOT NULL,
    `created_at`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_fav` (`id_estudiante`, `id_teoria`),
    KEY `idx_estudiante` (`id_estudiante`),
    KEY `idx_teoria`     (`id_teoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Teorías marcadas como favoritas por cada estudiante';
