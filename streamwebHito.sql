CREATE DATABASE IF NOT EXISTS streamweb;
USE streamweb;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    edad INT NOT NULL,
    plan_base ENUM('Básico', 'Estándar', 'Premium') NOT NULL,
    paquete ENUM('Ninguno', 'Infantil', 'Cine', 'Deporte') NOT NULL DEFAULT 'Ninguno',
    duracion ENUM('Mensual', 'Anual') NOT NULL
);

INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete, duracion) VALUES
('Juan Pérez', 'González', 'juan@example.com', 25, 'Premium', 'Cine', 'Mensual'),
('María López', 'Fernández', 'maria@example.com', 17, 'Estándar', 'Infantil', 'Anual'),
('Carlos Gómez', 'Martínez', 'carlos@example.com', 30, 'Básico', 'Deporte', 'Anual');

ALTER TABLE usuarios MODIFY paquete VARCHAR(255) NOT NULL;