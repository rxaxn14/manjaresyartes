-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-10-2024 a las 17:19:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_artesanias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_producto` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `stock` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_artesano` int(11) NOT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `materiales` varchar(255) DEFAULT NULL,
  `dimensiones` varchar(255) DEFAULT NULL,
  `colores_disponibles` varchar(255) DEFAULT NULL,
  `descuentos` varchar(50) DEFAULT NULL,
  `metodos_envio` varchar(255) DEFAULT NULL,
  `garantia` text DEFAULT NULL,
  `etiquetas` varchar(255) DEFAULT NULL,
  `certificaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_producto`, `nombre`, `descripcion`, `stock`, `precio`, `id_artesano`, `categoria`, `materiales`, `dimensiones`, `colores_disponibles`, `descuentos`, `metodos_envio`, `garantia`, `etiquetas`, `certificaciones`) VALUES
(13, 'Bolsa de Yute', 'Bolsa de yute artesanal hecha a mano.', 3, 20.00, 3, 'Accesorios', 'Yute 100%', '30x40 cm', 'Beige, Marrón', '10%', 'Envío estándar, Envío rápido', 'Garantía de 1 año', 'Eco-friendly, Hecho a mano', 'Certificación ecológica'),
(14, 'Café de los Yungas', 'Café de los Yungas tostado y molido.', 18, 15.00, 3, 'Alimentos', 'Café 100% orgánico', '500g', 'Negro', '15%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Comercio justo', 'Certificación orgánica'),
(15, 'Cerámica de Capinota', 'Cerámica artesanal de la región de Capinota.', 12, 45.00, 3, 'Decoración', 'Arcilla', '20x20 cm', 'Terracota', '20%', 'Envío estándar, Envío rápido', 'Garantía de 5 años', 'Hecho a mano, Tradicional', 'Certificación artesanal'),
(16, 'Chullos Andinos', 'Chullos andinos hechos con lana de alpaca.', 8, 25.00, 3, 'Ropa', 'Lana de alpaca', 'Talla única', 'Varios colores', '15%', 'Envío estándar', 'Garantía de 1 año', 'Caliente, Tradicional', 'Certificación artesanal'),
(17, 'Manteca de Cacao', 'Manteca de cacao orgánica para uso cosmético.', 30, 10.00, 3, 'Cosméticos', 'Cacao 100% orgánico', '200g', 'Blanco', '10%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Hecho a mano', 'Certificación orgánica'),
(18, 'Mermelada de Copoazú', 'Mermelada natural hecha con frutos de copoazú.', 15, 8.00, 3, 'Alimentos', 'Copoazú, azúcar de caña', '300g', 'Marrón claro', '5%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Hecho a mano', 'Certificación orgánica'),
(19, 'Miel de Yungas', 'Miel pura de las abejas de los Yungas.', 6, 12.00, 3, 'Alimentos', 'Miel 100% natural', '500g', 'Dorado', '10%', 'Envío estándar', 'Garantía de frescura', 'Natural, Hecho a mano', 'Certificación orgánica'),
(20, 'Quinua Real', 'Quinua Real de los salares de Uyuni.', 25, 18.00, 3, 'Alimentos', 'Quinua 100%', '1kg', 'Blanco, Rojo', '5%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Comercio justo', 'Certificación orgánica'),
(21, 'Sal Rosada de Uyuni', 'Sal rosada de las salinas de Uyuni.', 50, 5.00, 3, 'Alimentos', 'Sal 100% natural', '500g', 'Rosado claro', '5%', 'Envío estándar', 'Garantía de frescura', 'Natural, Hecho a mano', 'Certificación orgánica'),
(22, 'Sombrero de Saó', 'Sombrero artesanal hecho con Saó.', 10, 30.00, 3, 'Accesorios', 'Saó 100%', 'Talla única', 'Natural', '20%', 'Envío estándar', 'Garantía de 2 años', 'Hecho a mano, Tradicional', 'Certificación artesanal'),
(23, 'Té de Coca', 'Té de coca orgánico para infusiones.', 40, 7.00, 3, 'Alimentos', 'Coca 100% orgánica', '50g', 'Verde', '10%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Comercio justo', 'Certificación orgánica'),
(24, 'Tejidos de Tarabuco', 'Tejidos artesanales de la región de Tarabuco.', 9, 35.00, 3, 'Decoración', 'Lana 100% natural', '1m x 2m', 'Multicolor', '15%', 'Envío estándar', 'Garantía de 5 años', 'Hecho a mano, Tradicional', 'Certificación artesanal');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_producto`),
  ADD KEY `id_artesano` (`id_artesano`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_artesano`) REFERENCES `artesano` (`ID_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
