-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-07-2023 a las 00:28:10
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_auth`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `app_jwt`
--

CREATE TABLE `app_jwt` (
  `id_app` bigint(20) NOT NULL,
  `name_app` varchar(200) NOT NULL,
  `client_id` text NOT NULL,
  `key_secret` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `clientejwt_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `app_jwt`
--

INSERT INTO `app_jwt` (`id_app`, `name_app`, `client_id`, `key_secret`, `created_at`, `clientejwt_id`, `status`) VALUES
(1, 'Sistema de ventas', 'c880c480aa7741b048bf49449b380339714624a1cd0e286c2c5731a7eaefa63e-bfc2e70cc272058a5244810a37c262bff6eeef95bdd17ab4f1955bb0d512e625', 'bfc2e70cc272058a5244810a37c262bff6eeef95bdd17ab4f1955bb0d512e625-c880c480aa7741b048bf49449b380339714624a1cd0e286c2c5731a7eaefa63e', '2023-07-24 13:43:45', 1, 1),
(2, 'Sistema clientes', 'bd43d4621a96078cbd92dcd980ede651126c4ef05a7c1b008f2cb140a6b34796-ba8311c9a869bb59fe2c4dd1f8c528c368dee985eeac5c36a49ec6e572b96c86', 'ba8311c9a869bb59fe2c4dd1f8c528c368dee985eeac5c36a49ec6e572b96c86-bd43d4621a96078cbd92dcd980ede651126c4ef05a7c1b008f2cb140a6b34796', '2023-07-24 13:48:48', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_jwt`
--

CREATE TABLE `cliente_jwt` (
  `idcliente_jwt` bigint(20) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `cliente_jwt`
--

INSERT INTO `cliente_jwt` (`idcliente_jwt`, `nombre`, `apellido`, `email`, `password`, `created_at`, `status`) VALUES
(1, 'Marcial', 'Francisco Nicolas', 'marcialf473@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '2023-07-24 11:44:47', 1),
(2, 'Dora Elena', 'Galvan Ventura', 'dora.galvan@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '2023-07-24 11:46:54', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `token_jwt`
--

CREATE TABLE `token_jwt` (
  `id_tokenjwt` bigint(20) NOT NULL,
  `clientejwt_id` bigint(20) NOT NULL,
  `app_id` bigint(20) NOT NULL,
  `access_token` text NOT NULL,
  `expires_in` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `token_jwt`
--

INSERT INTO `token_jwt` (`id_tokenjwt`, `clientejwt_id`, `app_id`, `access_token`, `expires_in`, `created_at`, `status`) VALUES
(1, 1, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpZF9hcHAiOiIxIiwibmFtZV9hcHAiOiJTaXN0ZW1hIGRlIHZlbnRhcyIsImVtYWlsIjoibWFyY2lhbGY0NzNAZ21haWwuY29tIiwiaWF0IjoxNjkwNTgxMjY2LCJleHAiOjE2OTA1ODQ4NjZ9.kBMY75JER1y8VtsPro-AXxktLC5j9Qae05Py2dY1iE93SV_SzpbN8Xxq3MvzKBBbfxcLe5ba4ufuk_tMqyOaog', '1690584866', '2023-07-28 15:54:26', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `app_jwt`
--
ALTER TABLE `app_jwt`
  ADD PRIMARY KEY (`id_app`),
  ADD KEY `clientejwt_id` (`clientejwt_id`);

--
-- Indices de la tabla `cliente_jwt`
--
ALTER TABLE `cliente_jwt`
  ADD PRIMARY KEY (`idcliente_jwt`);

--
-- Indices de la tabla `token_jwt`
--
ALTER TABLE `token_jwt`
  ADD PRIMARY KEY (`id_tokenjwt`),
  ADD KEY `clientejwt_id` (`clientejwt_id`),
  ADD KEY `app_id` (`app_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `app_jwt`
--
ALTER TABLE `app_jwt`
  MODIFY `id_app` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cliente_jwt`
--
ALTER TABLE `cliente_jwt`
  MODIFY `idcliente_jwt` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `token_jwt`
--
ALTER TABLE `token_jwt`
  MODIFY `id_tokenjwt` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `app_jwt`
--
ALTER TABLE `app_jwt`
  ADD CONSTRAINT `app_jwt_ibfk_1` FOREIGN KEY (`clientejwt_id`) REFERENCES `cliente_jwt` (`idcliente_jwt`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `token_jwt`
--
ALTER TABLE `token_jwt`
  ADD CONSTRAINT `token_jwt_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `app_jwt` (`id_app`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
