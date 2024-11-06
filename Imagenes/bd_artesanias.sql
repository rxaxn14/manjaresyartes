-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2024 a las 16:02:15
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
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `ID_usuario` int(11) NOT NULL,
  `permisos` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artesano`
--

CREATE TABLE `artesano` (
  `ID_usuario` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_comunidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `artesano`
--

INSERT INTO `artesano` (`ID_usuario`, `descripcion`, `id_comunidad`) VALUES
(3, NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ID_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ID_usuario`) VALUES
(1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `ID_comentario` int(11) NOT NULL,
  `ID_producto` int(11) NOT NULL,
  `ID_cliente` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `valoracion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidad`
--

CREATE TABLE `comunidad` (
  `id_comunidad` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `region` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunidad`
--

INSERT INTO `comunidad` (`id_comunidad`, `nombre`, `descripcion`, `region`) VALUES
(1, 'Tarabuco', 'Tarabuco es una comunidad ubicada en el departamento de Chuquisaca. Es reconocida a nivel nacional e internacional por sus tradicionales tejidos, los cuales son elaborados mediante técnicas ancestrales que reflejan la historia y cosmovisión de los Yampara, un grupo indígena de la región. Estos tejidos son usados tanto en vestimenta como en decoración.', 'Chuquisaca'),
(2, 'Capinota', 'Capinota, ubicada en el departamento de Cochabamba, es famosa por la elaboración de cerámicas artesanales. La región se caracteriza por su producción de alfarería, donde los artesanos trabajan el barro local para crear jarras, platos y esculturas que reflejan la identidad cultural de los valles bolivianos.', 'Cochabamba'),
(3, 'Los Yungas', 'Los Yungas es una región montañosa tropical en el departamento de La Paz, conocida por su biodiversidad y clima húmedo. Es una de las principales zonas productoras de café en Bolivia, y los artesanos locales también se dedican a la elaboración de productos derivados de la hoja de coca, además de tejidos y tallados.', 'La Paz'),
(4, 'Potosí', 'Potosí, famoso por su historia minera y su patrimonio cultural, es también un centro importante para la producción de sal rosada. En las áreas rurales, los artesanos de la región utilizan esta sal única para crear productos artesanales que incluyen tanto decoraciones como productos culinarios especializados.', 'Potosí'),
(5, 'Uyuni', 'Uyuni, hogar del famoso Salar de Uyuni, es conocido por su producción de quinoa real, considerada una de las mejores del mundo por sus propiedades nutritivas. Los agricultores y artesanos locales han desarrollado productos basados en la quinoa, que incluyen alimentos y derivados utilizados tanto en la gastronomía como en la cosmética.', 'Potosí'),
(6, 'Camiri', 'Camiri, ubicada en la región del Chaco boliviano, es conocida por su producción textil artesanal a base de lana de alpaca y oveja. Los tejidos, que incluyen mantas, ponchos y otras prendas, son elaborados por comunidades indígenas que han preservado sus técnicas durante generaciones.', 'Santa Cruz');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delivery`
--

CREATE TABLE `delivery` (
  `ID_usuario` int(11) NOT NULL,
  `tipo_vehiculo` varchar(255) DEFAULT NULL,
  `disponibilidad` tinyint(1) NOT NULL,
  `valoracion` decimal(2,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `delivery`
--

INSERT INTO `delivery` (`ID_usuario`, `tipo_vehiculo`, `disponibilidad`, `valoracion`) VALUES
(5, 'Moto', 1, 5.0),
(6, 'Moto', 1, 4.5),
(7, 'Bicicleta', 1, 4.8),
(8, 'Camioneta', 1, 4.9),
(9, 'Bicicleta', 1, 4.7),
(10, 'Auto', 0, 4.9),
(11, 'Bicicleta', 1, 4.7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `ID_detallePedido` int(11) NOT NULL,
  `ID_pedido` int(11) NOT NULL,
  `ID_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrega`
--

CREATE TABLE `entrega` (
  `id_entrega` int(11) NOT NULL,
  `id_delivery` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `direccion` text DEFAULT NULL,
  `estado_entrega` varchar(50) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `ID_pedido` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_comprador` int(11) DEFAULT NULL,
  `id_delivery` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`ID_pedido`, `total`, `metodo_pago`, `fecha`, `id_comprador`, `id_delivery`) VALUES
(1, 20.00, 'No seleccionado', '2024-10-22 09:49:13', 1, NULL),
(2, 20.00, 'No seleccionado', '2024-10-22 11:16:40', 1, NULL),
(6, 50.00, 'No seleccionado', '2024-10-22 19:58:26', 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pertenece`
--

CREATE TABLE `pertenece` (
  `ID_usuario` int(11) NOT NULL,
  `id_comunidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(13, 'Bolsa de Yute', 'Bolsa de yute artesanal hecha a mano.', 0, 20.00, 3, 'Accesorios', 'Yute 100%', '30x40 cm', 'Beige, Marrón', '10%', 'Envío estándar, Envío rápido', 'Garantía de 1 año', 'Eco-friendly, Hecho a mano', 'Certificación ecológica'),
(14, 'Café de los Yungas', 'Café de los Yungas tostado y molido.', 2, 15.00, 3, 'Alimentos', 'Café 100% orgánico', '500g', 'Negro', '15%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Comercio justo', 'Certificación orgánica'),
(15, 'Cerámica de Capinota', 'Cerámica artesanal de la región de Capinota.', 9, 45.00, 3, 'Decoración', 'Arcilla', '20x20 cm', 'Terracota', '20%', 'Envío estándar, Envío rápido', 'Garantía de 5 años', 'Hecho a mano, Tradicional', 'Certificación artesanal'),
(16, 'Chullos Andinos', 'Chullos andinos hechos con lana de alpaca.', 6, 25.00, 3, 'Ropa', 'Lana de alpaca', 'Talla única', 'Varios colores', '15%', 'Envío estándar', 'Garantía de 1 año', 'Caliente, Tradicional', 'Certificación artesanal'),
(17, 'Manteca de Cacao', 'Manteca de cacao orgánica para uso cosmético.', 30, 10.00, 3, 'Cosméticos', 'Cacao 100% orgánico', '200g', 'Blanco', '10%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Hecho a mano', 'Certificación orgánica'),
(18, 'Mermelada de Copoazú', 'Mermelada natural hecha con frutos de copoazú.', 15, 8.00, 3, 'Alimentos', 'Copoazú, azúcar de caña', '300g', 'Marrón claro', '5%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Hecho a mano', 'Certificación orgánica'),
(19, 'Miel de Yungas', 'Miel pura de las abejas de los Yungas.', 6, 12.00, 3, 'Alimentos', 'Miel 100% natural', '500g', 'Dorado', '10%', 'Envío estándar', 'Garantía de frescura', 'Natural, Hecho a mano', 'Certificación orgánica'),
(20, 'Quinua Real', 'Quinua Real de los salares de Uyuni.', 25, 18.00, 3, 'Alimentos', 'Quinua 100%', '1kg', 'Blanco, Rojo', '5%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Comercio justo', 'Certificación orgánica'),
(21, 'Sal Rosada de Uyuni', 'Sal rosada de las salinas de Uyuni.', 50, 5.00, 3, 'Alimentos', 'Sal 100% natural', '500g', 'Rosado claro', '5%', 'Envío estándar', 'Garantía de frescura', 'Natural, Hecho a mano', 'Certificación orgánica'),
(22, 'Sombrero de Saó', 'Sombrero artesanal hecho con Saó.', 10, 30.00, 3, 'Accesorios', 'Saó 100%', 'Talla única', 'Natural', '20%', 'Envío estándar', 'Garantía de 2 años', 'Hecho a mano, Tradicional', 'Certificación artesanal'),
(23, 'Té de Coca', 'Té de coca orgánico para infusiones.', 40, 7.00, 3, 'Alimentos', 'Coca 100% orgánica', '50g', 'Verde', '10%', 'Envío estándar', 'Garantía de frescura', 'Orgánico, Comercio justo', 'Certificación orgánica'),
(24, 'Tejidos de Tarabuco', 'Tejidos artesanales de la región de Tarabuco.', 9, 35.00, 3, 'Decoración', 'Lana 100% natural', '1m x 2m', 'Multicolor', '15%', 'Envío estándar', 'Garantía de 5 años', 'Hecho a mano, Tradicional', 'Certificación artesanal'),
(25, 'Queso de cabra', 'Queso artesanal de cabra, suave y cremoso, elaborado con leche fresca y natural.', 30, 50.00, 3, 'Lácteos', 'Leche de cabra, cuajo natural, sal.', '500g (Peso del producto)', 'Blanco', NULL, 'Envío local, entrega a domicilio.', 'Garantía de frescura, devolución dentro de 7 días si no está satisfecho.', NULL, NULL),
(26, 'Cebada', 'cebada del altiplano', 12, 50.00, 3, 'Harinas', 'cebada', '50 gm', '-', NULL, 'Envío local, entrega a domicilio.', '-', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `direccion` text DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `Rol` varchar(50) NOT NULL,
  `Verificado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_usuario`, `nombre`, `apellidos`, `telefono`, `correo_electronico`, `contrasena`, `direccion`, `token`, `Rol`, `Verificado`) VALUES
(1, 'roxana', '', '69966077', 'rxaxn2003@gmail.com', '$2y$10$woZ9BPsTeyh1eu.CnpIDV.yCORWs9ZTdU4COgPC./IE8Yttob1sz2', '21 de junio', NULL, 'cliente', 1),
(3, 'Benjamin ', 'Callisaya Choque', '69966077', 'rcastillom@fcpn.edu.bo', '$2y$10$RDyTZ3rnRCfAkd3FxMyIEu0/lnRs6R/YXrHQJPeqrHZECiNJIV6ve', '21 de junio', NULL, 'artesano', 1),
(4, 'Roxana', 'Castillo', '99999999', 'admin@example.com', '$2y$10$Is0t3JIHWr8cPRtpcl1B2OtPngG1yPzb62T4OXRIjKElu0yIdSy0S', NULL, NULL, 'administrador', 1),
(5, 'Juan', 'Perez', '69999999', 'juan.perez@delivery.com', '$2y$10$/Lo98zW6635Rl3NlRoOViOoHP8l25KwI/cWEeTW8t7/z4ossw2H4.', 'Calle Falsa 123', NULL, 'delivery', 1),
(6, 'Carlos', 'Lopez', '698765432', 'carlos.delivery1@logistica.com', '$2y$10$a7Fb/Tjp/TBQHiAxg8hs1OMtSJVEr4AXtTLpc92v1iUHSIm37w2uq', 'Avenida Transporte 456', NULL, 'delivery', 1),
(7, 'Andrea', 'Morales', '691234567', 'andrea.delivery2@logistica.com', '$2y$10$a7Fb/Tjp/TBQHiAxg8hs1OMtSJVEr4AXtTLpc92v1iUHSIm37w2uq', 'Calle Central 789', NULL, 'delivery', 1),
(8, 'Roberto', 'Martínez', '677654321', 'roberto.delivery4@logistica.com', '$2y$10$a7Fb/Tjp/TBQHiAxg8hs1OMtSJVEr4AXtTLpc92v1iUHSIm37w2uq', 'Calle Libertad 789', NULL, 'delivery', 1),
(9, 'Luis', 'González', '644987321', 'luis.delivery5@logistica.com', '$2y$10$a7Fb/Tjp/TBQHiAxg8hs1OMtSJVEr4AXtTLpc92v1iUHSIm37w2uq', 'Calle Esperanza 654', NULL, 'delivery', 1),
(10, 'Santiago', 'Martínez', '612345678', 'santiago.delivery6@logistica.com', '$2y$10$a7Fb/Tjp/TBQHiAxg8hs1OMtSJVEr4AXtTLpc92v1iUHSIm37w2uq', 'Calle Luna 987', NULL, 'delivery', 1),
(11, 'Lucía', 'Valverde', '631234567', 'lucia.delivery7@logistica.com', '$2y$10$a7Fb/Tjp/TBQHiAxg8hs1OMtSJVEr4AXtTLpc92v1iUHSIm37w2uq', 'Calle Estrella 321', NULL, 'delivery', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ID_usuario`);

--
-- Indices de la tabla `artesano`
--
ALTER TABLE `artesano`
  ADD PRIMARY KEY (`ID_usuario`),
  ADD KEY `id_comunidad` (`id_comunidad`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ID_usuario`);

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`ID_comentario`),
  ADD KEY `ID_producto` (`ID_producto`),
  ADD KEY `ID_cliente` (`ID_cliente`);

--
-- Indices de la tabla `comunidad`
--
ALTER TABLE `comunidad`
  ADD PRIMARY KEY (`id_comunidad`);

--
-- Indices de la tabla `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`ID_usuario`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`ID_detallePedido`),
  ADD KEY `ID_pedido` (`ID_pedido`),
  ADD KEY `ID_producto` (`ID_producto`);

--
-- Indices de la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD PRIMARY KEY (`id_entrega`),
  ADD KEY `id_delivery` (`id_delivery`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`ID_pedido`),
  ADD KEY `id_comprador` (`id_comprador`),
  ADD KEY `fk_pedido_delivery` (`id_delivery`);

--
-- Indices de la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD PRIMARY KEY (`ID_usuario`,`id_comunidad`),
  ADD KEY `id_comunidad` (`id_comunidad`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_producto`),
  ADD KEY `id_artesano` (`id_artesano`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `ID_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comunidad`
--
ALTER TABLE `comunidad`
  MODIFY `id_comunidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `ID_detallePedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entrega`
--
ALTER TABLE `entrega`
  MODIFY `id_entrega` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `ID_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `artesano`
--
ALTER TABLE `artesano`
  ADD CONSTRAINT `artesano_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `artesano_ibfk_2` FOREIGN KEY (`id_comunidad`) REFERENCES `comunidad` (`id_comunidad`) ON DELETE SET NULL;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`ID_producto`) REFERENCES `producto` (`ID_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentario_ibfk_2` FOREIGN KEY (`ID_cliente`) REFERENCES `cliente` (`ID_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`ID_pedido`) REFERENCES `pedido` (`ID_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`ID_producto`) REFERENCES `producto` (`ID_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `entrega`
--
ALTER TABLE `entrega`
  ADD CONSTRAINT `entrega_ibfk_1` FOREIGN KEY (`id_delivery`) REFERENCES `delivery` (`ID_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `entrega_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`ID_pedido`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_delivery` FOREIGN KEY (`id_delivery`) REFERENCES `delivery` (`ID_usuario`),
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_comprador`) REFERENCES `cliente` (`ID_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pertenece`
--
ALTER TABLE `pertenece`
  ADD CONSTRAINT `pertenece_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `pertenece_ibfk_2` FOREIGN KEY (`id_comunidad`) REFERENCES `comunidad` (`id_comunidad`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_artesano`) REFERENCES `artesano` (`ID_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
