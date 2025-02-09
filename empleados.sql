-- Configuración para compatibilidad con servicios en la nube
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS empleados
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE empleados;

-- Crear tabla con configuración optimizada para la nube
CREATE TABLE IF NOT EXISTS empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_correo (correo),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos iniciales para pruebas
INSERT INTO empleados (nombre, correo) VALUES
('Usuario Prueba', 'test@example.com');

SET FOREIGN_KEY_CHECKS = 1;