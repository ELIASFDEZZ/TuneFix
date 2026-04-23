-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-03-2026 a las 22:05:35
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
(9, 6, 2),
(42, 16, 5),
(43, 17, 5),
(44, 18, 5),
(45, 19, 5),
(46, 20, 5),
(83, 47, 4),
(84, 48, 4);

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
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pieza`
--

INSERT INTO `pieza` (`id`, `referencia`, `nombre`, `descripcion`, `imagen`) VALUES
(1, '5Q0 407 151 A', 'Brazo de suspensión delantero izquierdo', 'Brazo inferior control arm para Golf MK7, compatible con 1.6 TDI y 2.0 TDI', 'https://imgs.search.brave.com/_rudAtrBif2z-onEo-oRVUN2JhrK_-iGTSIp_6d98tw/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NDFEYWFSNlVVRkwu/anBn'),
(2, '04L 115 466', 'Bomba de aceite', 'Bomba de aceite original para motores EA288 2.0 TDI', 'https://imgs.search.brave.com/hW5Fz3PY_5IOiAkM2mFAnF9o0yLgtuujIOf2lIbxRXA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YXV0b2RvYy5kZS90/aHVtYj9pZD0yNjQ3/MTY0NCZtPTAmbj0w/JmxuZz1lcyZyZXY9/OTQwNzc5OTE'),
(3, '5Q0 198 119 B', 'Kit de distribución + bomba de agua', 'Kit completo de correa de distribución + bomba para 1.6 TDI CLHA/CRKB', 'https://imgs.search.brave.com/ww2K7_FxpjXBwcpHi6u2MmSvUkTjeK3asO-yGL-vvZQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5tb3RvcmRvY3Rv/ci5kZS8zNjBfcGhv/dG9zLzIxODg3NTk5/L3ByZXZpZXcuanBn'),
(4, '1K0 919 081 AH', 'Sensor de temperatura del refrigerante', 'Sensor NTC para Golf VII varios motores TDI/TSI', 'https://imgs.search.brave.com/D7Z0YDHqzMY9L9D0lsefblECKypwwaiTk7QveZkDues/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmVi/YXlpbWcuY29tL3Ro/dW1icy9pbWFnZXMv/Zy91VzBBQU9TdzRp/SmVlSn5pL3MtbDQw/MC53ZWJw'),
(5, '5Q0 915 105 AB', 'Batería AGM 70Ah', 'Batería start-stop original VW para Golf 2015-2020', 'https://imgs.search.brave.com/wYgymLjPzqo69MQ2iH3wup_NW9Za-Kp8TTVjv_QDYIY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YXV0b2RvYy5kZS90/aHVtYj9pZD0xMTI5/MjMyJm09MCZuPTAm/bG5nPWVzJnJldj05/NDA3NzkxNQ'),
(6, '3C0 919 081', 'Módulo de control de motor (ECU)', 'ECU para 2.0 TDI 150 CV - reprogramable', 'https://imgs.search.brave.com/YA9WwbsWANlxUcuFq4WrLrHaJHfa9JiGuhPMBm0U9lc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/Y2FyLXRlYy5lcy93/cC1jb250ZW50L3Vw/bG9hZHMvMjAyMS8w/Mi9JTUdfMjAyMTAy/MjVfMTAyMDQ3LTg0/NXg2ODQuanBn'),
(16, '11 42 8 575 516', 'Filtro de aceite BMW B47/B48', 'Filtro de aceite original para motores BMW B47 (diésel) y B48 (gasolina)', 'https://imgs.search.brave.com/Fie3Uaw6q4ys2FCTb9ENk7urY7AU9z0vtIL3cYHfMOk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9yZXB1/ZXN0b3ZlaGljdWxh/ci5jb20vY2RuL3No/b3AvZmlsZXMvRklM/VFJPQUNFSVRFWDEu/anBnP3Y9MTcwNDEz/ODIzMyZ3aWR0aD0x/NDQ1'),
(17, '13 71 7 619 279', 'Filtro de aire BMW Serie 3 G20', 'Filtro de aire de recambio para motor BMW B47 2.0d y derivados', 'https://imgs.search.brave.com/AFGsgemh8cEop_20fPmPVGF3xYnvUsx5Z0TbzjneUV4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YXV0b2RvYy5kZS90/aHVtYj9pZD0yMTg1/NTE4MSZtPTAmbj0w/JmxuZz1lcyZyZXY9/OTQwNzc5NTA'),
(18, '34 11 6 884 307', 'Kit pastillas de freno delanteras BMW G20', 'Pastillas de freno delanteras Brembo para BMW Serie 3 G20 320d', 'https://imgs.search.brave.com/v4WjYOzx1W3478jwlttMn0DYoYJjyAHDh5Odg_Ll1Ag/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5hdXRvZG9jLmRl/LzM2MF9waG90b3Mv/MTY2MDgzMy9oLXBy/ZXZpZXcuanBn'),
(19, '34 21 6 884 308', 'Kit pastillas de freno traseras BMW G20', 'Pastillas traseras con sensor de desgaste para BMW Serie 3 G20', 'https://imgs.search.brave.com/5c7EkWGV8DElSQpoBlscs30vCIMN1WlVdIIgmRVA9so/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5hdXRvZG9jLmRl/LzM2MF9waG90b3Mv/MTExOTcyOTEvaC1w/cmV2aWV3LmpwZw'),
(20, '64 11 9 248 280', 'Filtro habitáculo (polen) BMW Serie 3 G20', 'Filtro antipolen con carbón activado para BMW Serie 3/Serie 1 F40', 'https://imgs.search.brave.com/Jfg455YsE2JueR0L3fngFd6VZOgvXiLx2z4IINr-Lws/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NzFVRDNtcHowNUwu/anBn'),
(21, '04C 129 620 D', 'Filtro de aire Golf VIII 1.0 TSI', 'Filtro de aire para motores 1.0 TSI tres cilindros (código DLAA/DKRF)', 'https://imgs.search.brave.com/-u4xcAjiqJIRUzrYM4arP-09Qwj8odK052BWdkSCF_A/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YXV0b2RvYy5kZS90/aHVtYj9pZD0yMzky/Mzk2NyZtPTAmbj0w/JmxuZz1lcyZyZXY9/OTQwNzc5MjY'),
(22, '04C 115 561 H', 'Filtro de aceite Golf VIII 1.0 TSI', 'Cartucho de filtro de aceite para motor 1.0 TSI EA211', 'https://imgs.search.brave.com/pElJInvM5QIJRhGwyVpckA9vv5CPBisrk-fNUGB7bEM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9hcmQt/bW90b3JzcG9ydC5j/b20vMTk3MC1tZWRp/dW1fZGVmYXVsdC9m/aWx0cm8tYWNlaXRl/LTIwLXRzaS1lYTg4/ODMuanBn'),
(23, '1EA 698 151', 'Pastillas de freno delanteras Golf VIII', 'Pastillas delanteras Textar para VW Golf VIII todos los motores', 'https://imgs.search.brave.com/ZSxZ6gd7OWSopgviwYP8e7vWIJTAPM5TooCZCr07VyM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zY2Ru/LmF1dG90ZWlsZWRp/cmVrdC5kZS9jYXRh/bG9nL2NhdGVnb3Jp/ZXMvNTAweDUwMC8x/OS5wbmc'),
(24, '13 72 7 848 557', 'Filtro de aire BMW Serie 1 F40', 'Filtro de aire para BMW 116d (B37) y 118i (B38A) del modelo F40', 'https://imgs.search.brave.com/-B6qAtcammRSetVg9Y827JdhG_cuJmMX-CyANYY2Nvk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9odHRw/Mi5tbHN0YXRpYy5j/b20vRF9OUV9OUF85/NTMzMTYtTUxBNTAw/Nzg5NjcwMDBfMDUy/MDIyLU8ud2VicA'),
(25, '34 10 6 889 441', 'Discos de freno delanteros BMW F40', 'Par de discos ventilados delanteros para BMW Serie 1 F40 116d/118i', 'https://imgs.search.brave.com/eEj4k5xyVqZR1sW0Be_HZerktfmefYK3Vc2ZyiwZns4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/bWVjYXRlY2huaWMu/Y29tL2ltZy9waG90/b3Mvem9vbS9CSDMw/MjAwWi0zLmpwZw'),
(26, '11 42 8 570 410', 'Filtro de aceite BMW B37 diésel', 'Filtro de aceite para motor BMW B37 tres cilindros diésel (116d F40)', 'https://imgs.search.brave.com/6oOdWXqOjWAOchFp-oAeleGm9fYiq1JTrtKXJLPZkuY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/bWVjYXRlY2huaWMu/Y29tL2ltZy9waG90/b3Mvem9vbS9CQzUx/MTA5LmpwZw'),
(27, '12 13 8 616 153', 'Bujías NGK Iridium BMW 118i B38', 'Set de 3 bujías NGK Iridium para motor BMW B38A 1.5 gasolina', 'https://imgs.search.brave.com/MzDd7laQztd9CgNKU4z5H1e3yPhkbhtKoJlRrx449ow/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9hbGxp/bnBlcmZvcm1hbmNl/LmVzL3dwLWNvbnRl/bnQvdXBsb2Fkcy9u/Z2staXJpZGl1bWJp/eC1ibXctMzVpLmpw/Zw'),
(28, '04152-YZZA6', 'Filtro de aceite Toyota Yaris IV Hybrid', 'Filtro de aceite original Toyota para motor M15A-FXE híbrido', 'https://imgs.search.brave.com/Vo3pjQVU0LiUKVeFDyGDW7o_WnnW1Ji7qgb4uKijvb4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmVi/YXlpbWcuY29tL2lt/YWdlcy9nL3Zjb0FB/T1N3RXcxbHBYWXEv/cy1sOTYwLndlYnA'),
(29, '17801-21050', 'Filtro de aire Toyota Yaris IV', 'Filtro de aire panel para motor Toyota 1.5 Hybrid M15A Yaris IV', 'https://imgs.search.brave.com/vEFhtrkdSHTFw1cmUirWuta8rh4Rp3KoOFlcSJpz5IA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL0kv/NjE0bGlxKzRNQ0wu/anBn'),
(30, '04465-52310', 'Pastillas de freno delanteras Toyota Yaris IV', 'Pastillas delanteras originales Toyota para Yaris IV 2020 en adelante', 'https://imgs.search.brave.com/uxBv-eqAwd7NvPW7_uKat-hgDsc085RhYYxILqGZAII/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9odHRw/Mi5tbHN0YXRpYy5j/b20vRF9RX05QXzJY/XzkzOTQ0OS1NTEM4/MjgyMjc2MDY3OV8w/MzIwMjUtVi1wYXN0/aWxsYXMtZGVsYW50/ZXJhcy1wYXJhLXRv/eW90YS15YXJpcy0x/NS0xNn'),
(31, '28303-21020', 'Inversor/convertidor DC-DC Toyota Yaris Hybrid', 'Módulo convertidor DC-DC del sistema híbrido Toyota Yaris IV', 'https://imgs.search.brave.com/Xy0y454yq7d5lK8V_EGXYwgJ4yyPp-H7h8heVWmLoFE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmVi/YXlpbWcuY29tL2lt/YWdlcy9nL2ZpQUFB/T1N3STZCYzRuLTcv/cy1sMzAwLmpwZw'),
(32, 'JX6Z-6731-B', 'Filtro de aceite Ford Focus 1.5 EcoBlue', 'Filtro de aceite para motor Ford EcoBlue 1.5 diésel 4 cilindros', 'https://imgs.search.brave.com/MKDxbhmMzswcap8-ZVIJbvOWA2qwUUEtcDEHSPxFFcM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zaG9w/LmZvcmQuZXMvY2Ru/L3Nob3AvZmlsZXMv/OTVlYjM4MDctYzBk/Yy00ZjJlLWEzM2Yt/NmFmYjg2NzZlYzRi/LmpwZz92PTE3NjUy/MzYyNzMmd2lkdGg9/NzIw'),
(33, 'F1FZ-9601-A', 'Filtro de aire Ford Focus MK4 1.5 EcoBlue', 'Filtro de aire de recambio para Ford Focus MK4 motor diésel EcoBlue', 'https://imgs.search.brave.com/g99r_BovQ7zFD0BbfOnwui1uHPNEJTpzgpI-fAfBbX4/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5hdXRvZG9jLmRl/LzM2MF9waG90b3Mv/MTM4OTgzNzkvaC1w/cmV2aWV3LmpwZw'),
(34, 'CV6Z-2001-A', 'Pastillas de freno delanteras Ford Focus MK4', 'Pastillas delanteras EBC para Ford Focus MK4 gasolina y diésel', 'https://imgs.search.brave.com/esWOEu34Ljd0DvW3ex5LjydOzg5CPPTOfvrVocshWoc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/YXV0b3RlaWxlcHJv/ZmkuZGUvdGh1bWIv/YXNzZXRzL3ByZi9l/cnNhdHpfY2F0ZWdv/cmllcy8xOS5qcGc'),
(35, 'JX6Z-6K682-A', 'Kit de correa de distribución Ford 1.5 EcoBlue', 'Kit completo correa distribución + tensores para Ford EcoBlue 1.5d', 'https://images.unsplash.com/photo-1504222490345-c075b626e313?w=500'),
(36, 'CM5G-6714-BA', 'Filtro de aceite Ford Fiesta 1.0 EcoBoost', 'Filtro de aceite cartucho para Ford Fiesta MK8 motor EcoBoost 1.0', 'https://images.unsplash.com/photo-1600661653561-629509216228?w=500'),
(37, '1758893', 'Filtro de aire Ford Fiesta MK8 1.0', 'Filtro de aire panel para Ford Fiesta MK8 1.0 EcoBoost 100 CV', 'https://images.unsplash.com/photo-1601362840469-51e4d8d58785?w=500'),
(38, '2115285', 'Pastillas de freno delanteras Ford Fiesta MK8', 'Pastillas delanteras para Ford Fiesta MK8 1.0 EcoBoost desde 2017', 'https://images.unsplash.com/photo-1617886322168-72b886573c35?w=500'),
(39, '04C 115 562', 'Filtro de aceite Seat Ibiza 1.0 MPI', 'Filtro de aceite para motor Seat/VW 1.0 MPI tres cilindros CHYB', 'https://images.unsplash.com/photo-1622187679297-bc5ce5e0ba98?w=500'),
(40, '6F0 129 620', 'Filtro de aire Seat Ibiza MK5 1.0 MPI', 'Filtro de aire para Seat Ibiza MK5 motor 1.0 MPI 80 CV', 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=500'),
(41, '6Q0 698 151 F', 'Pastillas de freno delanteras Seat Ibiza MK5', 'Pastillas delanteras Ferodo para Seat Ibiza MK5 1.0 MPI y 1.6 TDI', 'https://images.unsplash.com/photo-1636207543865-acf3ad382295?w=500'),
(42, '6F0 955 651', 'Escobillas limpiaparabrisas Seat Ibiza MK5', 'Par de escobillas planas para Seat Ibiza MK5 (2017 en adelante)', 'https://images.unsplash.com/photo-1590674899484-d5640e854abe?w=500'),
(43, '04L 115 562 C', 'Filtro de aceite Audi A3 8Y 30 TDI', 'Filtro de aceite para motor Audi/VW 1.6 TDI y 2.0 TDI EA288 (DGTE)', 'https://images.unsplash.com/photo-1599256871679-6a9e5ba04f2c?w=500'),
(44, '8W0 129 620 B', 'Filtro de aire Audi A3 8Y / A4 B9', 'Filtro de aire original para Audi A3 8Y y A4 B9 motores TDI', 'https://images.unsplash.com/photo-1607860108855-64acf2078ed9?w=500'),
(45, '8V0 698 151 G', 'Kit frenos delanteros Audi A3 8Y', 'Discos + pastillas delanteros para Audi A3 8Y 30 TDI y 35 TFSI', 'https://images.unsplash.com/photo-1611545678786-f3d8916e8a64?w=500'),
(46, '8W0 615 301 M', 'Disco de freno delantero Audi A4 B9 / A3 8Y', 'Disco ventilado delantero para Audi A3 8Y y A4 B9 todos los motores', 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500'),
(47, '06K 115 562', 'Filtro de aceite Golf VIII 2.0 TFSI EA888', 'Filtro de aceite cartucho para motor 2.0 TFSI generación 3 EA888', 'https://images.unsplash.com/photo-1598965402089-897ce52e8355?w=500'),
(48, '5Q0 611 701 C', 'Manguera de freno delantera VW Golf VIII', 'Manguera flexible de freno delantera para Golf VIII y León IV', 'https://images.unsplash.com/photo-1606577924006-27d39b132ae2?w=500'),
(49, '5H0 915 105', 'Batería AGM 60Ah Seat León IV', 'Batería start-stop AGM para Seat León IV 1.5 TSI y 2.0 TDI', 'https://images.unsplash.com/photo-1609767905372-b9a40be58dbc?w=500'),
(50, '5H0 145 962', 'Manguito de turbo Seat León IV 2.0 TDI', 'Manguito de presión entre turbo e intercooler León IV 2.0 TDI', 'https://images.unsplash.com/photo-1617788138017-80ad40651399?w=500');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutorial`
--

CREATE TABLE `tutorial` (
  `id` int(11) NOT NULL,
  `pieza_id` int(11) NOT NULL,
  `motorizacion_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tutorial`
--

INSERT INTO `tutorial` (`id`, `pieza_id`, `motorizacion_id`, `titulo`, `imagen`) VALUES
(1, 3, 1, 'Cómo cambiar kit de distribución 1.6 TDI CLHA Golf MK7 (paso a paso)', 'https://imgs.search.brave.com/grJKT60Hh1HltlS7NHA32gC8A22Or_hdmg2OaM_3WBA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnl0/aW1nLmNvbS92aS9M/NHFRbklJWk52by9t/cWRlZmF1bHQuanBn'),
(2, 1, 1, 'Reemplazo de brazos de suspensión delantera Golf VII 1.6 TDI', 'https://imgs.search.brave.com/J8wGrJkKTOvMd34O6s_rulCBYrV-3EMLVjKjdt75rKQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mdmlw/YnRibS5maWxlcm9i/b3QuY29tL21jMTIv/NTkuMDEuMjMvNTku/MDEuMjMuMDguMTUu/MDMucG5n'),
(3, 2, 2, 'Cambio de bomba de aceite en motor 2.0 TDI EA288 - Golf 2015-2019', 'https://imgs.search.brave.com/U2jt2vqKJWTGssamuFXqkn7f2_ypkersUF3_8diGNFk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9ibG9n/LnRvdGFsZW5lcmdp/ZXMuZXMvd3AtY29u/dGVudC91cGxvYWRz/LzIwMjUvMDYvaVN0/b2NrLTExNjUzMTQy/NjYtMzAweDIwMC5q/cGc'),
(4, 5, 1, 'Instalación de batería AGM start-stop en Volkswagen Golf VII', 'https://imgs.search.brave.com/GRUOzEIaPXKOdoyj7qX7079GA_9aRQ1jWHzaEkwghlw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mdmlw/YnRibS5maWxlcm9i/b3QuY29tL21jMTIv/NTUuMDEuMTMvNTUu/MDEuMTMuMDMuMDMu/MDIucG5n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasenia` varchar(255) NOT NULL,
  `rol` enum('principiante','entusiasta','profesional') NOT NULL DEFAULT 'principiante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `email`, `contrasenia`, `rol`) VALUES
(1, 'Juan Pérez', 'juan.mecanico@gmail.com', '$2y$10$ejemploHasheado1234567890', 'principiante'),
(2, 'Ana López', 'ana.tunefix@hotmail.com', '$2y$10$otroHashSeguroAqui..', 'profesional'),
(3, 'Carlos Gómez', 'carlos88@yahoo.es', '$2y$10$hashDePruebaParaInsert', 'entusiasta');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

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
