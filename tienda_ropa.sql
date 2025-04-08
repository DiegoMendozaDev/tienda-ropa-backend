-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 22-02-2025 a las 17:31:43
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
-- Base de datos: `tienda_ropa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `descripcion`) VALUES
(2, 'Camisa', 'Prenda de vestir de tela que cubre el torso, abotonada por delante, generalmente con cuello y mangas.'),
(3, 'vestido', 'Prenda o conjunto de prendas exteriores con que se cubre el cuerpo.'),
(4, 'pantalón', 'Prenda de vestir que se ajusta a la cintura y llega generalmente hasta el pie, cubriendo cada pierna separadamente. Usado también en plural con el mismo significado que en singular.'),
(5, 'zapatos', 'Calzado que no pasa del tobillo, con la parte inferior de suela y lo demás de piel, fieltro, paño u otro tejido, más o menos escotado por el empeine.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `fecha_pedido`, `estado`, `total`) VALUES
(2, 4, '2025-02-20 16:35:00', 'comprado', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `marca` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `precio`, `id_categoria`, `marca`, `foto`, `stock`) VALUES
(1, 'Pantalón Monogram de estilo skater', 'Este pantalón de estilo skater luce un motivo integral Monogram en relieve de la temporada, un botón y remaches con efecto nacarado, además de una etiqueta Damier en piel nobuk en la parte trasera. Su favorecedora silueta de color marrón lo hace elegante y fácil de combinar. Esta pieza versátil, que demuestra la maestría de Louis Vuitton, puede llevarse con la sobrecamisa a juego para crear un refinado conjunto.', 200.00, 4, 'Louis Vuitton', 'http://localhost/Proyecto_tienda_PHP/client/public/assets/imgs/pantalon_LV.jpg', 23),
(3, 'CAMISA POPELÍN LIMITED EDITION', 'Camisa regular fit confeccionada en popelín de algodón. Cuello italiano y manga larga acabada en puño con botón. Detalle de costuras acabadas con doble pespunte. Cierre frontal de botonadura.', 49.00, 2, 'Zara', 'http://localhost/Proyecto_tienda_PHP/client/public/assets/imgs/camisa_blanca.jpg', 10),
(5, 'Vestido mini volantes', 'Composición\r\nEXTERIOR\r\n100% poliéster\r\nFORRO\r\n100% poliéster\r\nQue contiene al menos:\r\n\r\nEXTERIOR\r\n100% POLIÉSTER RECICLADO CERTIFICADO RCS\r\nFORRO\r\n100% POLIÉSTER RECICLADO CERTIFICADO RCS\r\nMATERIALES CERTIFICADOS\r\nPOLIÉSTER RECICLADO CERTIFICADO RCS', 29.99, 3, 'Bershka', 'http:\\\\localhost\\Proyecto_tienda_PHP\\client\\public\\assets\\imgs\\vestido_rosa.jpg', 5),
(6, 'Nike Air Max Plus Utility', 'Inspiradas en la playa y diseñadas para la ciudad, las Nike Air Max Plus Utility se renuevan con un diseño robusto perfecto para tus aventuras urbanas. Hemos añadido una bandeleta de ante resistente a su parte superior de tejido Knit transpirable y hemos ajustado el look con un conjunto adicional de cordones con cierre regulable para ofrecer un ajuste seguro y firme. Además, las unidades Max Air visibles en el antepié y el talón proporcionan una experiencia Tuned Air que combina comodidad con un estilo desafiante.', 199.99, 5, 'Nike', 'http:\\\\localhost\\Proyecto_tienda_PHP\\client\\public\\assets\\imgs\\zapato_nike.jpg', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `direccion` varchar(255) NOT NULL,
  `codigo_postal` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `envio`(
  `id_envio` int NOT NULL,
  `id_pedido` int NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_entrega_estimada` timestamp NOT NULL,
  `fecha_entrega_real` timestamp NOT NULL,
  `estado` VARCHAR(255) NOT NULL,
)

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `roles`, `contrasena`, `fecha_registro`, `direccion`, `codigo_postal`) VALUES
(4, 'Diego', 'diego@gmail.com', '[]', '1234', '2025-02-19 19:32:34', 'C/laguna', '28025'),
(7, 'Carlos', 'carlos@gmail.com', '[]', '$2y$13$GBkhlEERk3jH6SLP1q64FOwFEP.c78/NnGZyRH9Ludqniy2i9r.o2', '2025-02-21 13:09:24', 'laguna', '28025');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `unique_nombre_categoria` (`nombre`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `fk_pedido_detalle` (`id_pedido`),
  ADD KEY `fk_producto_detalle` (`id_producto`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_usuairo_pedido` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_categoria_producto` (`id_categoria`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_unique_usuarios` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `fk_pedido_detalle` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `fk_producto_detalle` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_usuairo_pedido` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_categoria_producto` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
