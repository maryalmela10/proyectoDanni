-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2024 a las 12:27:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE PMP;
USE PMP;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pmp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_actualizacion` datetime NOT NULL,
  `id_usu` int(11) NOT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `asunto` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `prioridad` enum('baja','media','alta') DEFAULT 'media',
  `estado` enum('Creado','En proceso','Solucionado','Cerrado') DEFAULT 'Creado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(100) NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `contraseña` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rol` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--
CREATE TABLE mensajes (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ticket_id` INT NOT NULL,
    `remitente_id` INT NOT NULL,
    `contenido` TEXT NOT NULL,
    `fecha_envio` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`id_usu`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usu`) REFERENCES `usuarios` (`id`);
COMMIT;

--
-- Foreign key de mensajes
--
ALTER TABLE mensajes
ADD CONSTRAINT fk_ticket_id
FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE;

ALTER TABLE mensajes
ADD CONSTRAINT fk_remitente_id
FOREIGN KEY (remitente_id) REFERENCES usuarios(id) ON DELETE CASCADE;
--
-- Agregar columna archivo_adjunto
--
ALTER TABLE tickets ADD COLUMN archivo_adjunto VARCHAR(255);
--
-- Agregar el token y la columna para activo y no activo
--
ALTER TABLE usuarios
ADD COLUMN token_activacion VARCHAR(64),
ADD COLUMN activo int(1) DEFAULT 0;
--
-- Agregar columna para telefono, dirección y departamento
--
ALTER TABLE usuarios
ADD COLUMN telefono VARCHAR(9),
ADD COLUMN direccion VARCHAR(100),
ADD COLUMN departamento VARCHAR(50);

-- Agregar la restricción CHECK para el teléfono
ALTER TABLE usuarios
ADD CONSTRAINT chk_telefono 
CHECK (telefono REGEXP '^[0-9]{9}$');
--
-- Agregar columna para foto de perfil
--
ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL;

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña`, `rol`, `token_activacion`, `activo`,`telefono`,`direccion`,`departamento`,`foto_perfil`) VALUES
(11, 'Pablo', 'pablo@empresa.com', '$2y$10$4oNTO8FUrkvKeaIxmUEUi.YaAZSyTK5k79Rs7UZZXlvNRqcoy0BY.', 0, NULL, 1, NULL, NULL, 'Ventas', 'perfiles/6737b3521b486.jpg'),
(12, 'Paty', 'paty@soporte.empresa.com', '$2y$10$jAZFpJs2rgfPFGAEx1Ru2e1MNN1OwxBo9dAc7ZU9bA.FUOyURD9Fe', 1, NULL, 1, NULL, NULL, 'Técnico', 'perfiles/673865c6c4e3a.jpg');
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
