-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-02-2026 a las 19:02:50
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tunefix`
--
CREATE DATABASE IF NOT EXISTS `tunefix` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tunefix`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compatibilidad_pieza`
--

CREATE TABLE `compatibilidad_pieza` (
  `id` int(11) NOT NULL,
  `pieza_id` int(11) NOT NULL,
  `motorizacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compatibilidad_pieza`
--

INSERT INTO `compatibilidad_pieza` (`id`, `pieza_id`, `motorizacion_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 1),
(5, 4, 1),
(6, 4, 2),
(7, 5, 1),
(8, 5, 2),
(9, 6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribuidor`
--

CREATE TABLE `distribuidor` (
  `id` int(11) NOT NULL,
  `url_base` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `distribuidor`
--

INSERT INTO `distribuidor` (`id`, `url_base`, `nombre`) VALUES
(1, 'https://www.rockauto.com/', 'RockAuto'),
(2, 'https://www.autodoc.es/', 'Autodoc'),
(3, 'https://www.oscaro.es/', 'Oscaro'),
(4, 'https://www.mecatechnic.com/', 'Mecatechnic (especialista VW)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribuidor_pieza`
--

CREATE TABLE `distribuidor_pieza` (
  `id` int(11) NOT NULL,
  `distribuidor_id` int(11) DEFAULT NULL,
  `pieza_id` int(11) DEFAULT NULL,
  `url_directa` varchar(500) DEFAULT NULL,
  `nombre` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `distribuidor_pieza`
--

INSERT INTO `distribuidor_pieza` (`id`, `distribuidor_id`, `pieza_id`, `url_directa`, `nombre`) VALUES
(1, 1, 1, 'https://www.rockauto.com/es/moreinfo.php?pk=1234567&cc=VolkswagenGolf', 'Brazo suspensión izquierdo - Lemförder'),
(2, 2, 3, 'https://www.autodoc.es/pieza-de-repuesto/kit-de-correa-de-distribucion-10512/vw/golf-vii-5g1-be1/1-6-tdi', 'Kit distribución INA para 1.6 TDI'),
(3, 3, 5, 'https://www.oscaro.es/bateria-varta-silver-dynamic-agm-a7-70ah-760a-5q0915105ab', 'Batería Varta AGM 70Ah VW ref'),
(4, 4, 1, 'https://www.mecatechnic.com/es-ES/referencia-5Q0407151A', 'Brazo suspensión Meyle HD Golf VII');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `manual`
--

CREATE TABLE `manual` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `fuente` varchar(100) DEFAULT NULL,
  `archivo_url` varchar(500) DEFAULT NULL,
  `motorizacion_id` int(11) DEFAULT NULL,
  `pieza_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `nombre`) VALUES
(1, 'Volkswagen'),
(2, 'BMW'),
(3, 'Toyota');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `anio_inicio` int(11) DEFAULT NULL,
  `anio_fin` int(11) DEFAULT NULL,
  `marca_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo`
--

INSERT INTO `modelo` (`id`, `nombre`, `anio_inicio`, `anio_fin`, `marca_id`) VALUES
(1, 'Golf VII', 2012, 2019, 1),
(2, 'Golf VIII', 2019, 2025, 1),
(3, 'Serie 3 (G20)', 2019, NULL, 2),
(4, 'Corolla E210', 2018, NULL, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motorizacion`
--

CREATE TABLE `motorizacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `potencia` varchar(50) DEFAULT NULL,
  `tipo_combustible` varchar(50) DEFAULT NULL,
  `tipo_motor` varchar(50) DEFAULT NULL,
  `codigo_motor` varchar(50) DEFAULT NULL,
  `modelo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motorizacion`
--

INSERT INTO `motorizacion` (`id`, `nombre`, `potencia`, `tipo_combustible`, `tipo_motor`, `codigo_motor`, `modelo_id`) VALUES
(1, '1.6 TDI CR', '105 CV', 'Diésel', '4 cilindros turbo', 'CLHA', 1),
(2, '2.0 TDI EA288', '150 CV', 'Diésel', '4 cilindros common rail', 'DFGA', 1),
(3, '1.5 TSI EVO', '130 CV', 'Gasolina', '4 cilindros turbo', 'DADA', 2),
(4, '2.0 TFSI EA888', '190 CV', 'Gasolina', '4 cilindros turbo', 'DKZA', 2),
(5, '320d B48', '190 CV', 'Diésel', '4 cilindros turbo', 'B47', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pieza`
--

CREATE TABLE `pieza` (
  `id` int(11) NOT NULL,
  `referencia` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pieza`
--

INSERT INTO `pieza` (`id`, `referencia`, `nombre`, `descripcion`) VALUES
(1, '5Q0 407 151 A', 'Brazo de suspensión delantero izquierdo', 'Brazo inferior control arm para Golf MK7, compatible con 1.6 TDI y 2.0 TDI'),
(2, '04L 115 466', 'Bomba de aceite', 'Bomba de aceite original para motores EA288 2.0 TDI'),
(3, '5Q0 198 119 B', 'Kit de distribución + bomba de agua', 'Kit completo de correa de distribución + bomba para 1.6 TDI CLHA/CRKB'),
(4, '1K0 919 081 AH', 'Sensor de temperatura del refrigerante', 'Sensor NTC para Golf VII varios motores TDI/TSI'),
(5, '5Q0 915 105 AB', 'Batería AGM 70Ah', 'Batería start-stop original VW para Golf 2015-2020'),
(6, '3C0 919 081', 'Módulo de control de motor (ECU)', 'ECU para 2.0 TDI 150 CV - reprogramable');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutorial`
--

CREATE TABLE `tutorial` (
  `id` int(11) NOT NULL,
  `pieza_id` int(11) NOT NULL,
  `motorizacion_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tutorial`
--

INSERT INTO `tutorial` (`id`, `pieza_id`, `motorizacion_id`, `titulo`) VALUES
(1, 3, 1, 'Cómo cambiar kit de distribución 1.6 TDI CLHA Golf MK7 (paso a paso)'),
(2, 1, 1, 'Reemplazo de brazos de suspensión delantera Golf VII 1.6 TDI'),
(3, 2, 2, 'Cambio de bomba de aceite en motor 2.0 TDI EA288 - Golf 2015-2019'),
(4, 5, 1, 'Instalación de batería AGM start-stop en Volkswagen Golf VII');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `email`, `contrasenia`, `rol`) VALUES
(1, 'Juan Pérez', 'juan.mecanico@gmail.com', '$2y$10$ejemploHasheado1234567890', 'usuario'),
(2, 'Ana López', 'ana.tunefix@hotmail.com', '$2y$10$otroHashSeguroAqui..', 'admin'),
(3, 'Carlos Gómez', 'carlos88@yahoo.es', '$2y$10$hashDePruebaParaInsert', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_motorizacion`
--

CREATE TABLE `usuario_motorizacion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `motorizacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `compatibilidad_pieza`
--
ALTER TABLE `compatibilidad_pieza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pieza_id` (`pieza_id`),
  ADD KEY `motorizacion_id` (`motorizacion_id`);

--
-- Indices de la tabla `distribuidor`
--
ALTER TABLE `distribuidor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `distribuidor_pieza`
--
ALTER TABLE `distribuidor_pieza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distribuidor_id` (`distribuidor_id`),
  ADD KEY `pieza_id` (`pieza_id`);

--
-- Indices de la tabla `manual`
--
ALTER TABLE `manual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `motorizacion_id` (`motorizacion_id`),
  ADD KEY `pieza_id` (`pieza_id`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marca_id` (`marca_id`);

--
-- Indices de la tabla `motorizacion`
--
ALTER TABLE `motorizacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modelo_id` (`modelo_id`);

--
-- Indices de la tabla `pieza`
--
ALTER TABLE `pieza`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referencia` (`referencia`);

--
-- Indices de la tabla `tutorial`
--
ALTER TABLE `tutorial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pieza_id` (`pieza_id`),
  ADD KEY `motorizacion_id` (`motorizacion_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuario_motorizacion`
--
ALTER TABLE `usuario_motorizacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `motorizacion_id` (`motorizacion_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `compatibilidad_pieza`
--
ALTER TABLE `compatibilidad_pieza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `distribuidor`
--
ALTER TABLE `distribuidor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `distribuidor_pieza`
--
ALTER TABLE `distribuidor_pieza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `manual`
--
ALTER TABLE `manual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `motorizacion`
--
ALTER TABLE `motorizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pieza`
--
ALTER TABLE `pieza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tutorial`
--
ALTER TABLE `tutorial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario_motorizacion`
--
ALTER TABLE `usuario_motorizacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compatibilidad_pieza`
--
ALTER TABLE `compatibilidad_pieza`
  ADD CONSTRAINT `compatibilidad_pieza_ibfk_1` FOREIGN KEY (`pieza_id`) REFERENCES `pieza` (`id`),
  ADD CONSTRAINT `compatibilidad_pieza_ibfk_2` FOREIGN KEY (`motorizacion_id`) REFERENCES `motorizacion` (`id`);

--
-- Filtros para la tabla `distribuidor_pieza`
--
ALTER TABLE `distribuidor_pieza`
  ADD CONSTRAINT `distribuidor_pieza_ibfk_1` FOREIGN KEY (`distribuidor_id`) REFERENCES `distribuidor` (`id`),
  ADD CONSTRAINT `distribuidor_pieza_ibfk_2` FOREIGN KEY (`pieza_id`) REFERENCES `pieza` (`id`);

--
-- Filtros para la tabla `manual`
--
ALTER TABLE `manual`
  ADD CONSTRAINT `manual_ibfk_1` FOREIGN KEY (`motorizacion_id`) REFERENCES `motorizacion` (`id`),
  ADD CONSTRAINT `manual_ibfk_2` FOREIGN KEY (`pieza_id`) REFERENCES `pieza` (`id`);

--
-- Filtros para la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `modelo_ibfk_1` FOREIGN KEY (`marca_id`) REFERENCES `marca` (`id`);

--
-- Filtros para la tabla `motorizacion`
--
ALTER TABLE `motorizacion`
  ADD CONSTRAINT `motorizacion_ibfk_1` FOREIGN KEY (`modelo_id`) REFERENCES `modelo` (`id`);

--
-- Filtros para la tabla `tutorial`
--
ALTER TABLE `tutorial`
  ADD CONSTRAINT `tutorial_ibfk_1` FOREIGN KEY (`pieza_id`) REFERENCES `pieza` (`id`),
  ADD CONSTRAINT `tutorial_ibfk_2` FOREIGN KEY (`motorizacion_id`) REFERENCES `motorizacion` (`id`);

--
-- Filtros para la tabla `usuario_motorizacion`
--
ALTER TABLE `usuario_motorizacion`
  ADD CONSTRAINT `usuario_motorizacion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `usuario_motorizacion_ibfk_2` FOREIGN KEY (`motorizacion_id`) REFERENCES `motorizacion` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
