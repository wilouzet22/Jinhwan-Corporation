-- ==============================================================================
-- Migración para el Control y Límite de Tokens IA en Profesores / Maestros
-- Compatible con MariaDB / MySQL en InfinityFree (phpMyAdmin) y Laragon
-- ==============================================================================

ALTER TABLE `maestro` 
ADD COLUMN IF NOT EXISTS `tokens_limite` int(11) NOT NULL DEFAULT 50000 COMMENT 'Límite máximo de tokens de IA asignados',
ADD COLUMN IF NOT EXISTS `tokens_usados` int(11) NOT NULL DEFAULT 0 COMMENT 'Tokens de IA consumidos por el profesor';

-- Asegurar que los maestros existentes tengan su cuota inicial de 50,000 tokens
UPDATE `maestro` 
SET `tokens_limite` = 50000 
WHERE `tokens_limite` IS NULL OR `tokens_limite` <= 0;

