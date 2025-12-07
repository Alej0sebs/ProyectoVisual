-- Migración: Agregar sistema de cursos e inscripciones
-- Fecha: 07/12/2025

-- Tabla de cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla intermedia para la relación muchos a muchos
CREATE TABLE IF NOT EXISTS `estudiante_curso` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(10) UNSIGNED NOT NULL,
  `curso_id` int(10) UNSIGNED NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_estudiante_curso` (`estudiante_id`, `curso_id`),
  KEY `fk_estudiante_curso_estudiante` (`estudiante_id`),
  KEY `fk_estudiante_curso_curso` (`curso_id`),
  CONSTRAINT `fk_estudiante_curso_estudiante` 
    FOREIGN KEY (`estudiante_id`) 
    REFERENCES `estudiantes` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE,
  CONSTRAINT `fk_estudiante_curso_curso` 
    FOREIGN KEY (`curso_id`) 
    REFERENCES `cursos` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para cursos
INSERT INTO `cursos` (`nombre`) VALUES
('Programación Web'),
('Base de Datos'),
('Computación Visual'),
('Redes de Computadoras'),
('Inteligencia Artificial');

-- Ejemplos de inscripciones (asumiendo IDs de estudiantes existentes)
-- Nota: Ajustar los IDs según los estudiantes reales en tu base de datos
INSERT INTO `estudiante_curso` (`estudiante_id`, `curso_id`) VALUES
(1, 1),  -- Primer estudiante en Programación Web
(1, 2),  -- Primer estudiante en Base de Datos
(5, 1),  -- Estudiante con ID 5 en Programación Web
(5, 3),  -- Estudiante con ID 5 en Computación Visual
(5, 5);  -- Estudiante con ID 5 en Inteligencia Artificial
