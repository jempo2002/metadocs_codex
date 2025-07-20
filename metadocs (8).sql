-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2025 a las 18:41:55
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
-- Base de datos: `metadocs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_visualizacion` datetime DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `tipo_actividad` enum('reporte','tarea') DEFAULT NULL,
  `mensaje` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area_acceso`
--

CREATE TABLE `area_acceso` (
  `id_area` int(11) NOT NULL,
  `nombre` enum('administracion','logistica','contabilidad','otro') DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `area_acceso`
--

INSERT INTO `area_acceso` (`id_area`, `nombre`, `fecha_creacion`) VALUES
(1, 'logistica', '2024-12-31 10:33:15'),
(2, 'contabilidad', '2025-01-05 08:59:58'),
(3, 'administracion', '2025-01-03 20:34:03'),
(4, 'otro', '2025-01-03 20:53:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contraseña_resets`
--

CREATE TABLE `contraseña_resets` (
  `reset_id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_en` datetime NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contraseña_resets`
--

INSERT INTO `contraseña_resets` (`reset_id`, `id_usuario`, `token`, `expira_en`, `creado_en`) VALUES
(32, 26, 'ae36ca2a68779bedfc670946528f910fa5093a291f611385f21f37f9151412c9', '2025-03-13 19:41:29', '2025-03-13 12:41:29'),
(34, 26, '9e5d77c4fcb42feb413cbf455bf3cdcf6ced38d19f96d93b08d136f1279a6b53', '2025-05-05 03:26:27', '2025-05-04 19:26:27'),
(38, 26, 'c6c2f6cd1ca58b3ea5a9d1111a1d5509adb77036b0e490b72ee57f89a26eb440', '2025-05-21 05:08:37', '2025-05-20 21:08:37'),
(40, 26, '69a93d8ec30e56f0e80744351ce30fd6476b564ab5d3febee2cc591bb429a25a', '2025-05-22 19:34:53', '2025-05-22 11:34:53'),
(45, 26, '008210ea09d069807e41ea355d3e4360b793a1d541bd96fb0f4048c01dceb30a', '2025-05-22 20:06:45', '2025-05-22 12:06:45'),
(47, 26, '2e44bb7cdb786c628e0ace06886da751a05cfa62fefd2330ced54e35801d0565', '2025-05-24 04:02:19', '2025-05-23 20:02:19'),
(49, 26, '91fceb0fe5da9462fa16b7a4796ba5c0399172c52a9c3f4fa4ac59c676f764bf', '2025-05-28 04:38:24', '2025-05-27 20:38:24'),
(50, 26, '2d18d2e286982609e6b3bf4612af1871521998c98de27e395e6879b604eb15ca', '2025-05-28 05:09:55', '2025-05-27 21:09:55'),
(54, 26, 'a943fc12258e1dbfcb99c5dba386c8d06609f16abf4f0b9307e30d26b45fbf16', '2025-05-28 05:38:02', '2025-05-27 21:38:02'),
(55, 26, '82a322b3a0d17c6aacca7001e047784e9a1d5b368efbd74d5789c13956fdf93f', '2025-05-28 05:38:06', '2025-05-27 21:38:06'),
(59, 26, '2640ca95770e6a2fbda458c8b397705856e1836855354bc330b4de1c97844636', '2025-06-03 01:11:56', '2025-06-02 17:11:56'),
(61, 26, 'dd6b809983fa711534ef38e6561e080290434fe80ec957664d4f366ce62447ed', '2025-06-03 01:27:13', '2025-06-02 17:27:13'),
(62, 51, '5621162622945354cae37c87e3c9bba36880d26f5eb34c7de78a30b2f2296784', '2025-06-05 04:02:21', '2025-06-04 20:02:21'),
(63, 26, 'd9065108967cf0bf08ffe4a86dd3fc00a8bcfa6e9951c126a58afd400e885e8a', '2025-06-10 20:59:55', '2025-06-10 12:59:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL,
  `fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `id_expediente` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `titulo` text NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `estado` enum('aprobado','revision','rechazado') DEFAULT NULL,
  `autor` int(255) DEFAULT NULL,
  `estado_retencion` enum('archivado','activo') DEFAULT NULL,
  `id_retencion` int(11) DEFAULT NULL,
  `fin_retencion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id_documento`, `fecha_creacion`, `id_expediente`, `id_area`, `titulo`, `path`, `tipo`, `estado`, `autor`, `estado_retencion`, `id_retencion`, `fin_retencion`) VALUES
(67, '2025-06-17', 78, 4, 'Formato IEEE Adso-05', '../../uploads/68520cf98c816_Formato IEEE Adso-05.docx', 'docx', 'aprobado', 40, 'activo', 1, '2026-11-17'),
(68, '2025-06-17', 78, 4, 'Captura de pantalla 2022-10-29 a la(s) 10.07.20 a.m.', '../../uploads/68520d6e67530_Captura de pantalla 2022-10-29 a la(s) 10.07.20 a.m..pdf', 'pdf', 'aprobado', 40, 'activo', 1, '2026-11-17'),
(69, '2025-06-17', 78, 4, 'ojeras - Búsqueda de Google', '../../uploads/685212700496d_ojeras - Búsqueda de Google.pdf', 'pdf', 'revision', 40, 'activo', 1, '2026-11-17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes`
--

CREATE TABLE `expedientes` (
  `id_expediente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_creacion` date DEFAULT current_timestamp(),
  `expediente_padre` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL,
  `estado` enum('aprobado','revision','rechazado') DEFAULT NULL,
  `autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `expedientes`
--

INSERT INTO `expedientes` (`id_expediente`, `nombre`, `descripcion`, `fecha_creacion`, `expediente_padre`, `id_area`, `estado`, `autor`) VALUES
(78, 'Expedienté hecho en celular ', 'Aprobar expediente por favor ', '2025-06-17', 0, 4, 'aprobado', 40),
(79, 'Prueba 2', 'Hola ', '2025-06-17', 0, 4, 'aprobado', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pista_auditoria`
--

CREATE TABLE `pista_auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `accion` text NOT NULL,
  `fecha_accion` datetime NOT NULL DEFAULT current_timestamp(),
  `entidad` enum('documento','expediente','tarea','reporte') DEFAULT NULL,
  `entidad_id` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `retencion`
--

CREATE TABLE `retencion` (
  `id_retencion` int(11) NOT NULL,
  `categoria` enum('Estratégicos','Operativos','Soporte','Legales','Financieros','Correspondencia') DEFAULT NULL,
  `duracion_año` int(11) DEFAULT NULL,
  `duracion_mes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `retencion`
--

INSERT INTO `retencion` (`id_retencion`, `categoria`, `duracion_año`, `duracion_mes`) VALUES
(1, 'Estratégicos', 1, 5),
(2, 'Operativos', 10, 0),
(3, 'Soporte', 1, 0),
(4, 'Legales', 1, 0),
(5, 'Financieros', 1, 0),
(6, 'Correspondencia', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_fisico`
--

CREATE TABLE `ubicacion_fisico` (
  `id_ubicacion` int(11) NOT NULL,
  `tipo_ubicacion` enum('Archivo','Estante','Caja','Bóveda','Otro') DEFAULT NULL,
  `id_documento` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `edificio` enum('principal','anexo_a','anexo_b','deposito','archivo_central') DEFAULT NULL,
  `piso` enum('sotano','planta_baja','primer_piso','segundo_piso','tercer_piso','cuarto_piso') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicacion_fisico`
--

INSERT INTO `ubicacion_fisico` (`id_ubicacion`, `tipo_ubicacion`, `id_documento`, `observaciones`, `edificio`, `piso`) VALUES
(8, 'Archivo', 67, 'Aprobar por favor ', 'principal', 'sotano'),
(9, 'Archivo', 68, 'Ap', 'principal', 'sotano'),
(10, 'Archivo', 69, 'Hola', 'principal', 'sotano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `rol` enum('administrador','visualizador','documentador','auditor') NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_area` int(11) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `rol`, `nombres`, `apellidos`, `correo`, `contraseña`, `fecha_creacion`, `fecha_actualizacion`, `id_area`, `cedula`, `telefono`, `estado`) VALUES
(11, 'administrador', 'juanes', 'montenegro', 'juanes@gmail.com', '21e88dca84b8346fdbc455e332de9ee5', '2025-06-20 00:03:06', '2025-06-20 00:03:06', 4, '1006429235', '3172323357', 'activo'),
(26, 'auditor', 'jorge xd', 'Galeano', 'jorgemulato206@gmail.com', 'e0a0bbdf18ef381b4c5924026a79bb06', '2025-01-03 20:34:03', '2025-06-15 16:57:14', 4, '1114240641', '3145062530', 'activo'),
(40, 'documentador', 'metadocs pruebas', 'Bv', 'metadocs7@gmail.com', 'e13453ceb91a91816509a2b74ff97785', '2025-01-11 17:14:45', '2025-06-05 17:53:19', 4, '159', '3145062530', 'activo'),
(51, 'administrador', 'Jorge Admin', 'Admin', 'dg244049@gmail.com', '5a0f035db329cea241ae3509ad2b824f', '2025-06-02 17:15:57', '2025-06-15 10:08:06', 3, '14445454', '314506253', 'activo'),
(52, 'visualizador', 'root', 'admin', 'pruebaroot@hotmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2025-06-03 07:21:45', '2025-06-05 17:49:48', 3, '1444464664', '3201542078', 'activo'),
(53, 'documentador', 'yelen', 'yelencio', 'yelen@gmail.com', '3b7aab6f0b5bb0d8855e5acc6c6d7eb2', '2025-06-15 10:13:55', '2025-06-15 16:37:52', 1, '1444464664', '123456789', 'activo'),
(54, 'administrador', 'Daniel Alejandro', 'xd', 'daniel@gmail.com', 'b5ea8985533defbf1d08d5ed2ac8fe9b', '2025-06-17 11:05:51', '2025-06-17 11:05:51', 3, '1444464664', '3201542078', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `area_acceso`
--
ALTER TABLE `area_acceso`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `contraseña_resets`
--
ALTER TABLE `contraseña_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_expediente` (`id_expediente`),
  ADD KEY `id_area` (`id_area`),
  ADD KEY `fk_autor_documento` (`autor`),
  ADD KEY `fk_retencion_doc` (`id_retencion`);

--
-- Indices de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD PRIMARY KEY (`id_expediente`),
  ADD KEY `fk_expediente_area` (`id_area`),
  ADD KEY `fk_autor_usuario` (`autor`);

--
-- Indices de la tabla `pista_auditoria`
--
ALTER TABLE `pista_auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD UNIQUE KEY `id_area` (`id_area`),
  ADD KEY `fk_pista_usuario` (`id_usuario`);

--
-- Indices de la tabla `retencion`
--
ALTER TABLE `retencion`
  ADD PRIMARY KEY (`id_retencion`);

--
-- Indices de la tabla `ubicacion_fisico`
--
ALTER TABLE `ubicacion_fisico`
  ADD PRIMARY KEY (`id_ubicacion`),
  ADD KEY `fk_documento2` (`id_documento`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_area_acceso_usuarios` (`id_area`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `area_acceso`
--
ALTER TABLE `area_acceso`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `contraseña_resets`
--
ALTER TABLE `contraseña_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  MODIFY `id_expediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `pista_auditoria`
--
ALTER TABLE `pista_auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `retencion`
--
ALTER TABLE `retencion`
  MODIFY `id_retencion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ubicacion_fisico`
--
ALTER TABLE `ubicacion_fisico`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `contraseña_resets`
--
ALTER TABLE `contraseña_resets`
  ADD CONSTRAINT `contraseña_resets_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_4` FOREIGN KEY (`id_expediente`) REFERENCES `expedientes` (`id_expediente`),
  ADD CONSTRAINT `documentos_ibfk_5` FOREIGN KEY (`id_area`) REFERENCES `area_acceso` (`id_area`),
  ADD CONSTRAINT `fk_autor_documento` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_retencion_doc` FOREIGN KEY (`id_retencion`) REFERENCES `retencion` (`id_retencion`);

--
-- Filtros para la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD CONSTRAINT `fk_autor_usuario` FOREIGN KEY (`autor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_expediente_area` FOREIGN KEY (`id_area`) REFERENCES `area_acceso` (`id_area`);

--
-- Filtros para la tabla `pista_auditoria`
--
ALTER TABLE `pista_auditoria`
  ADD CONSTRAINT `fk_pista_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pista_auditoria_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `area_acceso` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ubicacion_fisico`
--
ALTER TABLE `ubicacion_fisico`
  ADD CONSTRAINT `fk_documento2` FOREIGN KEY (`id_documento`) REFERENCES `documentos` (`id_documento`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_area_acceso_usuarios` FOREIGN KEY (`id_area`) REFERENCES `area_acceso` (`id_area`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
