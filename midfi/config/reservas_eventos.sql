-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 17:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '123', 'Juanes', '2025-10-19 19:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`id`, `nombre`) VALUES
(2, 'aprobada'),
(1, 'pendiente'),
(3, 'rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido') DEFAULT 'pendiente',
  `respondido_por` varchar(50) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respondido_por`, `fecha_respuesta`) VALUES
(4, 'juan', 'juanes@gmail.com', '+57 3207456609', 'Reserva de Evento', 'hhh', '2025-10-21 09:09:38', 'pendiente', NULL, NULL),
(5, 'Santiago', 'juanes@gmail.com', '+57 3207456609', 'Consulta General', 'gvnhftg', '2025-10-21 09:15:18', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `num_invitados` int(11) DEFAULT NULL,
  `tipo_comida_id` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado_id` int(11) DEFAULT 1,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `fecha_evento`, `num_invitados`, `tipo_comida_id`, `comentarios`, `estado_id`, `fecha_reserva`, `fecha_actualizacion`) VALUES
(4, 3, '2025-12-12', 100, 3, 'hhhhh', 1, '2025-10-21 14:10:03', '2025-10-21 15:10:03'),
(5, 3, '2025-12-12', 100, 5, 'ergtr', 1, '2025-10-21 14:15:25', '2025-10-21 15:15:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'barbacoa'),
(2, 'buffet'),
(5, 'otro'),
(3, 'vegano'),
(4, 'vegetariano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ciudad`, `telefono`, `email`, `password`, `creado_en`) VALUES
(3, 'Juan', 'López', 'MEDELLIN', '+57 3207456609', 'juanes@gmail.com', '$2y$10$qd88Ib.XtWVSNRJyozQK/u0/7CxNqD7I7deF5MmPuXABQtCB.XADy', '2025-10-20 12:35:24'),
(4, 'Santiago', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiago@gmail.com', '$2y$10$oSj4IdvpLW7X4Ao.TKvKAOZWQpSjEDvpvvGbPfMxsPJbBLHyFlbDy', '2025-10-20 12:50:30'),
(6, 'Santiagoo', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiagoo@gmail.com', '$2y$10$60tRvDSpC9Y87.USlQNKbOexyhiY841sGmx7s7/vmFKXzzMf1PWQ6', '2025-10-21 15:09:16'),
(7, 'Santiago', 'Garcia', 'Medellin', '3965555554', 'santiagooo@gmail.com', '$2y$10$DiGbE.R2za5tsNlCi7e2Lel33XcIn2fX30A84VU9P01dwFgTRR0xC', '2025-10-21 15:15:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `asunto` (`asunto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados_reserva` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 17:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '123', 'Juanes', '2025-10-19 19:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`id`, `nombre`) VALUES
(2, 'aprobada'),
(1, 'pendiente'),
(3, 'rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido') DEFAULT 'pendiente',
  `respondido_por` varchar(50) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respondido_por`, `fecha_respuesta`) VALUES
(4, 'juan', 'juanes@gmail.com', '+57 3207456609', 'Reserva de Evento', 'hhh', '2025-10-21 09:09:38', 'pendiente', NULL, NULL),
(5, 'Santiago', 'juanes@gmail.com', '+57 3207456609', 'Consulta General', 'gvnhftg', '2025-10-21 09:15:18', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `num_invitados` int(11) DEFAULT NULL,
  `tipo_comida_id` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado_id` int(11) DEFAULT 1,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `fecha_evento`, `num_invitados`, `tipo_comida_id`, `comentarios`, `estado_id`, `fecha_reserva`, `fecha_actualizacion`) VALUES
(4, 3, '2025-12-12', 100, 3, 'hhhhh', 1, '2025-10-21 14:10:03', '2025-10-21 15:10:03'),
(5, 3, '2025-12-12', 100, 5, 'ergtr', 1, '2025-10-21 14:15:25', '2025-10-21 15:15:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'barbacoa'),
(2, 'buffet'),
(5, 'otro'),
(3, 'vegano'),
(4, 'vegetariano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ciudad`, `telefono`, `email`, `password`, `creado_en`) VALUES
(3, 'Juan', 'López', 'MEDELLIN', '+57 3207456609', 'juanes@gmail.com', '$2y$10$qd88Ib.XtWVSNRJyozQK/u0/7CxNqD7I7deF5MmPuXABQtCB.XADy', '2025-10-20 12:35:24'),
(4, 'Santiago', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiago@gmail.com', '$2y$10$oSj4IdvpLW7X4Ao.TKvKAOZWQpSjEDvpvvGbPfMxsPJbBLHyFlbDy', '2025-10-20 12:50:30'),
(6, 'Santiagoo', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiagoo@gmail.com', '$2y$10$60tRvDSpC9Y87.USlQNKbOexyhiY841sGmx7s7/vmFKXzzMf1PWQ6', '2025-10-21 15:09:16'),
(7, 'Santiago', 'Garcia', 'Medellin', '3965555554', 'santiagooo@gmail.com', '$2y$10$DiGbE.R2za5tsNlCi7e2Lel33XcIn2fX30A84VU9P01dwFgTRR0xC', '2025-10-21 15:15:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `asunto` (`asunto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados_reserva` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 17:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '123', 'Juanes', '2025-10-19 19:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`id`, `nombre`) VALUES
(2, 'aprobada'),
(1, 'pendiente'),
(3, 'rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido') DEFAULT 'pendiente',
  `respondido_por` varchar(50) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respondido_por`, `fecha_respuesta`) VALUES
(4, 'juan', 'juanes@gmail.com', '+57 3207456609', 'Reserva de Evento', 'hhh', '2025-10-21 09:09:38', 'pendiente', NULL, NULL),
(5, 'Santiago', 'juanes@gmail.com', '+57 3207456609', 'Consulta General', 'gvnhftg', '2025-10-21 09:15:18', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `num_invitados` int(11) DEFAULT NULL,
  `tipo_comida_id` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado_id` int(11) DEFAULT 1,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `fecha_evento`, `num_invitados`, `tipo_comida_id`, `comentarios`, `estado_id`, `fecha_reserva`, `fecha_actualizacion`) VALUES
(4, 3, '2025-12-12', 100, 3, 'hhhhh', 1, '2025-10-21 14:10:03', '2025-10-21 15:10:03'),
(5, 3, '2025-12-12', 100, 5, 'ergtr', 1, '2025-10-21 14:15:25', '2025-10-21 15:15:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'barbacoa'),
(2, 'buffet'),
(5, 'otro'),
(3, 'vegano'),
(4, 'vegetariano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ciudad`, `telefono`, `email`, `password`, `creado_en`) VALUES
(3, 'Juan', 'López', 'MEDELLIN', '+57 3207456609', 'juanes@gmail.com', '$2y$10$qd88Ib.XtWVSNRJyozQK/u0/7CxNqD7I7deF5MmPuXABQtCB.XADy', '2025-10-20 12:35:24'),
(4, 'Santiago', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiago@gmail.com', '$2y$10$oSj4IdvpLW7X4Ao.TKvKAOZWQpSjEDvpvvGbPfMxsPJbBLHyFlbDy', '2025-10-20 12:50:30'),
(6, 'Santiagoo', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiagoo@gmail.com', '$2y$10$60tRvDSpC9Y87.USlQNKbOexyhiY841sGmx7s7/vmFKXzzMf1PWQ6', '2025-10-21 15:09:16'),
(7, 'Santiago', 'Garcia', 'Medellin', '3965555554', 'santiagooo@gmail.com', '$2y$10$DiGbE.R2za5tsNlCi7e2Lel33XcIn2fX30A84VU9P01dwFgTRR0xC', '2025-10-21 15:15:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `asunto` (`asunto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados_reserva` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 17:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '123', 'Juanes', '2025-10-19 19:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`id`, `nombre`) VALUES
(2, 'aprobada'),
(1, 'pendiente'),
(3, 'rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido') DEFAULT 'pendiente',
  `respondido_por` varchar(50) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respondido_por`, `fecha_respuesta`) VALUES
(4, 'juan', 'juanes@gmail.com', '+57 3207456609', 'Reserva de Evento', 'hhh', '2025-10-21 09:09:38', 'pendiente', NULL, NULL),
(5, 'Santiago', 'juanes@gmail.com', '+57 3207456609', 'Consulta General', 'gvnhftg', '2025-10-21 09:15:18', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `num_invitados` int(11) DEFAULT NULL,
  `tipo_comida_id` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado_id` int(11) DEFAULT 1,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `fecha_evento`, `num_invitados`, `tipo_comida_id`, `comentarios`, `estado_id`, `fecha_reserva`, `fecha_actualizacion`) VALUES
(4, 3, '2025-12-12', 100, 3, 'hhhhh', 1, '2025-10-21 14:10:03', '2025-10-21 15:10:03'),
(5, 3, '2025-12-12', 100, 5, 'ergtr', 1, '2025-10-21 14:15:25', '2025-10-21 15:15:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'barbacoa'),
(2, 'buffet'),
(5, 'otro'),
(3, 'vegano'),
(4, 'vegetariano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ciudad`, `telefono`, `email`, `password`, `creado_en`) VALUES
(3, 'Juan', 'López', 'MEDELLIN', '+57 3207456609', 'juanes@gmail.com', '$2y$10$qd88Ib.XtWVSNRJyozQK/u0/7CxNqD7I7deF5MmPuXABQtCB.XADy', '2025-10-20 12:35:24'),
(4, 'Santiago', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiago@gmail.com', '$2y$10$oSj4IdvpLW7X4Ao.TKvKAOZWQpSjEDvpvvGbPfMxsPJbBLHyFlbDy', '2025-10-20 12:50:30'),
(6, 'Santiagoo', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiagoo@gmail.com', '$2y$10$60tRvDSpC9Y87.USlQNKbOexyhiY841sGmx7s7/vmFKXzzMf1PWQ6', '2025-10-21 15:09:16'),
(7, 'Santiago', 'Garcia', 'Medellin', '3965555554', 'santiagooo@gmail.com', '$2y$10$DiGbE.R2za5tsNlCi7e2Lel33XcIn2fX30A84VU9P01dwFgTRR0xC', '2025-10-21 15:15:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `asunto` (`asunto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados_reserva` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 17:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '123', 'Juanes', '2025-10-19 19:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`id`, `nombre`) VALUES
(2, 'aprobada'),
(1, 'pendiente'),
(3, 'rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido') DEFAULT 'pendiente',
  `respondido_por` varchar(50) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respondido_por`, `fecha_respuesta`) VALUES
(4, 'juan', 'juanes@gmail.com', '+57 3207456609', 'Reserva de Evento', 'hhh', '2025-10-21 09:09:38', 'pendiente', NULL, NULL),
(5, 'Santiago', 'juanes@gmail.com', '+57 3207456609', 'Consulta General', 'gvnhftg', '2025-10-21 09:15:18', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `num_invitados` int(11) DEFAULT NULL,
  `tipo_comida_id` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado_id` int(11) DEFAULT 1,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `fecha_evento`, `num_invitados`, `tipo_comida_id`, `comentarios`, `estado_id`, `fecha_reserva`, `fecha_actualizacion`) VALUES
(4, 3, '2025-12-12', 100, 3, 'hhhhh', 1, '2025-10-21 14:10:03', '2025-10-21 15:10:03'),
(5, 3, '2025-12-12', 100, 5, 'ergtr', 1, '2025-10-21 14:15:25', '2025-10-21 15:15:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'barbacoa'),
(2, 'buffet'),
(5, 'otro'),
(3, 'vegano'),
(4, 'vegetariano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ciudad`, `telefono`, `email`, `password`, `creado_en`) VALUES
(3, 'Juan', 'López', 'MEDELLIN', '+57 3207456609', 'juanes@gmail.com', '$2y$10$qd88Ib.XtWVSNRJyozQK/u0/7CxNqD7I7deF5MmPuXABQtCB.XADy', '2025-10-20 12:35:24'),
(4, 'Santiago', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiago@gmail.com', '$2y$10$oSj4IdvpLW7X4Ao.TKvKAOZWQpSjEDvpvvGbPfMxsPJbBLHyFlbDy', '2025-10-20 12:50:30'),
(6, 'Santiagoo', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiagoo@gmail.com', '$2y$10$60tRvDSpC9Y87.USlQNKbOexyhiY841sGmx7s7/vmFKXzzMf1PWQ6', '2025-10-21 15:09:16'),
(7, 'Santiago', 'Garcia', 'Medellin', '3965555554', 'santiagooo@gmail.com', '$2y$10$DiGbE.R2za5tsNlCi7e2Lel33XcIn2fX30A84VU9P01dwFgTRR0xC', '2025-10-21 15:15:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `asunto` (`asunto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados_reserva` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2025 a las 17:20:44
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservas_eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '123', 'Juanes', '2025-10-19 19:54:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_reserva`
--

INSERT INTO `estados_reserva` (`id`, `nombre`) VALUES
(2, 'aprobada'),
(1, 'pendiente'),
(3, 'rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido') DEFAULT 'pendiente',
  `respondido_por` varchar(50) DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `estado`, `respondido_por`, `fecha_respuesta`) VALUES
(4, 'juan', 'juanes@gmail.com', '+57 3207456609', 'Reserva de Evento', 'hhh', '2025-10-21 09:09:38', 'pendiente', NULL, NULL),
(5, 'Santiago', 'juanes@gmail.com', '+57 3207456609', 'Consulta General', 'gvnhftg', '2025-10-21 09:15:18', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `num_invitados` int(11) DEFAULT NULL,
  `tipo_comida_id` int(11) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `estado_id` int(11) DEFAULT 1,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `fecha_evento`, `num_invitados`, `tipo_comida_id`, `comentarios`, `estado_id`, `fecha_reserva`, `fecha_actualizacion`) VALUES
(4, 3, '2025-12-12', 100, 3, 'hhhhh', 1, '2025-10-21 14:10:03', '2025-10-21 15:10:03'),
(5, 3, '2025-12-12', 100, 5, 'ergtr', 1, '2025-10-21 14:15:25', '2025-10-21 15:15:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_comida`
--

CREATE TABLE `tipos_comida` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_comida`
--

INSERT INTO `tipos_comida` (`id`, `nombre`) VALUES
(1, 'barbacoa'),
(2, 'buffet'),
(5, 'otro'),
(3, 'vegano'),
(4, 'vegetariano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ciudad`, `telefono`, `email`, `password`, `creado_en`) VALUES
(3, 'Juan', 'López', 'MEDELLIN', '+57 3207456609', 'juanes@gmail.com', '$2y$10$qd88Ib.XtWVSNRJyozQK/u0/7CxNqD7I7deF5MmPuXABQtCB.XADy', '2025-10-20 12:35:24'),
(4, 'Santiago', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiago@gmail.com', '$2y$10$oSj4IdvpLW7X4Ao.TKvKAOZWQpSjEDvpvvGbPfMxsPJbBLHyFlbDy', '2025-10-20 12:50:30'),
(6, 'Santiagoo', 'Gonzalez Graciano', 'Medellin', '3197160522', 'santiagoo@gmail.com', '$2y$10$60tRvDSpC9Y87.USlQNKbOexyhiY841sGmx7s7/vmFKXzzMf1PWQ6', '2025-10-21 15:09:16'),
(7, 'Santiago', 'Garcia', 'Medellin', '3965555554', 'santiagooo@gmail.com', '$2y$10$DiGbE.R2za5tsNlCi7e2Lel33XcIn2fX30A84VU9P01dwFgTRR0xC', '2025-10-21 15:15:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `asunto` (`asunto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_comida_id` (`tipo_comida_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipos_comida`
--
ALTER TABLE `tipos_comida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`tipo_comida_id`) REFERENCES `tipos_comida` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `estados_reserva` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
