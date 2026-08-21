-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2026 at 10:39 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `camas`
--

CREATE TABLE `camas` (
  `id` bigint UNSIGNED NOT NULL,
  `habitacion_id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocupada` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `camas`
--

INSERT INTO `camas` (`id`, `habitacion_id`, `codigo`, `ocupada`, `created_at`, `updated_at`) VALUES
(1, 1, '101-A', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 1, '101-B', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 2, '102-A', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 3, '201-A', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 4, '202-A', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(6, 5, '301-A', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `cirugias`
--

CREATE TABLE `cirugias` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED NOT NULL,
  `procedimiento_id` bigint UNSIGNED NOT NULL,
  `procedimiento_2_id` bigint UNSIGNED DEFAULT NULL,
  `quirofano_id` bigint UNSIGNED NOT NULL,
  `cirujano_id` bigint UNSIGNED NOT NULL,
  `ayudante_1_id` bigint UNSIGNED DEFAULT NULL,
  `ayudante_2_id` bigint UNSIGNED DEFAULT NULL,
  `ayudante_3_id` bigint UNSIGNED DEFAULT NULL,
  `anestesista_id` bigint UNSIGNED DEFAULT NULL,
  `tipo_anestesia_id` bigint UNSIGNED NOT NULL,
  `tipo_anestesia_2_id` bigint UNSIGNED DEFAULT NULL,
  `instrumentador_id` bigint UNSIGNED DEFAULT NULL,
  `instrumentador_2_id` bigint UNSIGNED DEFAULT NULL,
  `enfermero_id` bigint UNSIGNED DEFAULT NULL,
  `enfermero_2_id` bigint UNSIGNED DEFAULT NULL,
  `urgencia` tinyint(1) NOT NULL DEFAULT '0',
  `obito` tinyint(1) NOT NULL DEFAULT '0',
  `duracion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_cirugia` date NOT NULL,
  `hora_cirugia` time NOT NULL,
  `creado_por` bigint UNSIGNED DEFAULT NULL,
  `modificado_por` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cirugias`
--

INSERT INTO `cirugias` (`id`, `paciente_id`, `especialidad_id`, `procedimiento_id`, `procedimiento_2_id`, `quirofano_id`, `cirujano_id`, `ayudante_1_id`, `ayudante_2_id`, `ayudante_3_id`, `anestesista_id`, `tipo_anestesia_id`, `tipo_anestesia_2_id`, `instrumentador_id`, `instrumentador_2_id`, `enfermero_id`, `enfermero_2_id`, `urgencia`, `obito`, `duracion`, `fecha_cirugia`, `hora_cirugia`, `creado_por`, `modificado_por`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 2, NULL, 2, 1, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:05', '2026-05-19', '11:30:00', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 4, 1, 1, NULL, 1, 1, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '03:00', '2026-05-20', '08:00:00', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 5, 3, 3, NULL, 2, 6, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '75', '2026-05-05', '09:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(4, 6, 2, 4, NULL, 3, 7, 1, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '120', '2026-05-06', '10:30:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(5, 7, 3, 5, NULL, 2, 8, 5, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 0, 0, '50', '2026-05-08', '08:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(6, 8, 4, 6, NULL, 1, 9, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '240', '2026-05-10', '14:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(7, 9, 3, 7, NULL, 2, 6, 1, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 1, 0, '45', '2026-05-11', '08:30:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(8, 10, 2, 8, NULL, 3, 11, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '90', '2026-05-12', '11:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-06-05 17:46:40'),
(9, 11, 3, 2, NULL, 2, 13, 1, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '60', '2026-05-13', '15:45:00', 1, NULL, '2026-05-20 15:28:42', '2026-06-05 17:46:40'),
(10, 12, 3, 3, NULL, 2, 9, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '80', '2026-05-14', '09:30:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(11, 13, 1, 1, NULL, 1, 6, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '210', '2026-05-15', '08:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(12, 14, 3, 5, NULL, 2, 7, 1, NULL, NULL, 2, 4, NULL, 4, NULL, 3, NULL, 0, 0, '55', '2026-05-16', '13:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(13, 15, 2, 8, NULL, 3, 12, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '95', '2026-05-17', '10:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-06-05 17:46:40'),
(14, 16, 3, 2, NULL, 2, 10, 1, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '70', '2026-05-18', '20:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-06-05 17:46:40'),
(15, 17, 3, 3, NULL, 2, 6, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '75', '2026-05-19', '16:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(16, 18, 2, 4, NULL, 3, 11, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '110', '2026-05-20', '13:30:00', 1, NULL, '2026-05-20 15:28:42', '2026-06-05 17:46:40'),
(17, 19, 3, 5, NULL, 2, 11, 1, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 0, 0, '60', '2026-05-20', '15:00:00', 1, NULL, '2026-05-20 15:28:42', '2026-06-05 17:46:40'),
(18, 20, 4, 6, NULL, 1, 9, 5, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '230', '2026-05-20', '16:30:00', 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(19, 1, 3, 2, NULL, 2, 6, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:10', '2026-01-05', '08:00:00', 1, NULL, '2026-01-05 11:00:00', '2026-01-05 11:00:00'),
(20, 2, 1, 1, NULL, 1, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '03:15', '2026-01-10', '08:00:00', 1, NULL, '2026-01-10 11:00:00', '2026-01-10 11:00:00'),
(21, 3, 2, 4, NULL, 3, 8, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '02:00', '2026-01-14', '08:00:00', 1, NULL, '2026-01-14 11:00:00', '2026-01-14 11:00:00'),
(22, 4, 3, 3, NULL, 2, 9, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:30', '2026-01-18', '08:00:00', 1, NULL, '2026-01-18 11:00:00', '2026-01-18 11:00:00'),
(23, 5, 3, 5, NULL, 2, 10, NULL, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 1, 0, '00:55', '2026-01-22', '08:00:00', 1, NULL, '2026-01-22 11:00:00', '2026-06-05 17:46:40'),
(24, 6, 4, 6, NULL, 1, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '04:10', '2026-01-28', '08:00:00', 1, NULL, '2026-01-28 11:00:00', '2026-01-28 11:00:00'),
(25, 7, 3, 7, NULL, 2, 8, NULL, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 1, 0, '00:45', '2026-02-02', '08:00:00', 1, NULL, '2026-02-02 11:00:00', '2026-02-02 11:00:00'),
(26, 8, 2, 8, NULL, 3, 9, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:45', '2026-02-06', '08:00:00', 1, NULL, '2026-02-06 11:00:00', '2026-02-06 11:00:00'),
(27, 9, 3, 2, NULL, 2, 10, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:05', '2026-02-10', '08:00:00', 1, NULL, '2026-02-10 11:00:00', '2026-06-05 17:46:40'),
(28, 10, 3, 3, NULL, 2, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:20', '2026-02-14', '08:00:00', 1, NULL, '2026-02-14 11:00:00', '2026-02-14 11:00:00'),
(29, 11, 1, 1, NULL, 1, 8, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '03:40', '2026-02-18', '08:00:00', 1, NULL, '2026-02-18 11:00:00', '2026-02-18 11:00:00'),
(30, 12, 3, 5, NULL, 2, 9, NULL, NULL, NULL, 2, 4, NULL, 4, NULL, 3, NULL, 0, 0, '01:00', '2026-02-22', '08:00:00', 1, NULL, '2026-02-22 11:00:00', '2026-02-22 11:00:00'),
(31, 13, 2, 8, NULL, 3, 6, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:50', '2026-02-26', '08:00:00', 1, NULL, '2026-02-26 11:00:00', '2026-02-26 11:00:00'),
(32, 14, 3, 2, NULL, 2, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:15', '2026-03-02', '08:00:00', 1, NULL, '2026-03-02 11:00:00', '2026-03-02 11:00:00'),
(33, 15, 3, 3, NULL, 2, 11, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:25', '2026-03-06', '08:00:00', 1, NULL, '2026-03-06 11:00:00', '2026-06-05 17:46:40'),
(34, 16, 2, 4, NULL, 3, 9, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '02:10', '2026-03-10', '08:00:00', 1, NULL, '2026-03-10 11:00:00', '2026-03-10 11:00:00'),
(35, 17, 3, 5, NULL, 2, 6, NULL, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 0, 0, '01:00', '2026-03-14', '08:00:00', 1, NULL, '2026-03-14 11:00:00', '2026-03-14 11:00:00'),
(36, 18, 4, 6, NULL, 1, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '03:50', '2026-03-18', '08:00:00', 1, NULL, '2026-03-18 11:00:00', '2026-03-18 11:00:00'),
(37, 19, 3, 7, NULL, 2, 8, NULL, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 1, 0, '00:50', '2026-03-22', '08:00:00', 1, NULL, '2026-03-22 11:00:00', '2026-03-22 11:00:00'),
(38, 20, 2, 8, NULL, 3, 9, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:30', '2026-03-25', '08:00:00', 1, NULL, '2026-03-25 11:00:00', '2026-03-25 11:00:00'),
(39, 1, 3, 2, NULL, 2, 6, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:05', '2026-03-28', '08:00:00', 1, NULL, '2026-03-28 11:00:00', '2026-03-28 11:00:00'),
(40, 2, 3, 3, NULL, 2, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:15', '2026-03-31', '08:00:00', 1, NULL, '2026-03-31 11:00:00', '2026-03-31 11:00:00'),
(41, 3, 1, 1, NULL, 1, 12, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '03:00', '2026-04-03', '08:00:00', 1, NULL, '2026-04-03 11:00:00', '2026-06-05 17:46:40'),
(42, 4, 3, 5, NULL, 2, 9, NULL, NULL, NULL, 2, 4, NULL, 4, NULL, 3, NULL, 0, 0, '00:55', '2026-04-06', '08:00:00', 1, NULL, '2026-04-06 11:00:00', '2026-04-06 11:00:00'),
(43, 5, 2, 8, NULL, 3, 6, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:40', '2026-04-10', '08:00:00', 1, NULL, '2026-04-10 11:00:00', '2026-04-10 11:00:00'),
(44, 6, 3, 2, NULL, 2, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 1, 0, '01:10', '2026-04-13', '08:00:00', 1, NULL, '2026-04-13 11:00:00', '2026-04-13 11:00:00'),
(45, 7, 3, 3, NULL, 2, 8, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:20', '2026-04-16', '08:00:00', 1, NULL, '2026-04-16 11:00:00', '2026-04-16 11:00:00'),
(46, 8, 4, 6, NULL, 1, 9, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '03:30', '2026-04-20', '08:00:00', 1, NULL, '2026-04-20 11:00:00', '2026-04-20 11:00:00'),
(47, 9, 3, 7, NULL, 2, 6, NULL, NULL, NULL, 2, 3, NULL, 4, NULL, 3, NULL, 1, 0, '00:45', '2026-04-23', '08:00:00', 1, NULL, '2026-04-23 11:00:00', '2026-04-23 11:00:00'),
(48, 10, 2, 4, NULL, 3, 7, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:50', '2026-04-26', '08:00:00', 1, NULL, '2026-04-26 11:00:00', '2026-04-26 11:00:00'),
(49, 11, 3, 2, NULL, 2, 8, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:10', '2026-04-28', '08:00:00', 1, NULL, '2026-04-28 11:00:00', '2026-04-28 11:00:00'),
(50, 12, 3, 3, NULL, 2, 9, NULL, NULL, NULL, 2, 1, NULL, 4, NULL, 3, NULL, 0, 0, '01:15', '2026-04-30', '08:00:00', 1, NULL, '2026-04-30 11:00:00', '2026-04-30 11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `codigo_postals`
--

CREATE TABLE `codigo_postals` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais_id` bigint UNSIGNED NOT NULL,
  `provincia_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `codigo_postals`
--

INSERT INTO `codigo_postals` (`id`, `codigo`, `localidad`, `pais_id`, `provincia_id`, `created_at`, `updated_at`) VALUES
(1, '1000', 'CABA', 1, 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, '5000', 'Córdoba Capital', 1, 2, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, '2000', 'Rosario', 1, 3, '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `empleados`
--

CREATE TABLE `empleados` (
  `id` bigint UNSIGNED NOT NULL,
  `dni` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais_id` bigint UNSIGNED NOT NULL,
  `provincia_id` bigint UNSIGNED NOT NULL,
  `cod_postal_id` bigint UNSIGNED NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profesion_id` bigint UNSIGNED NOT NULL,
  `matricula` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_por` bigint UNSIGNED DEFAULT NULL,
  `modificado_por` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `empleados`
--

INSERT INTO `empleados` (`id`, `dni`, `nombre`, `apellido`, `fecha_nacimiento`, `telefono`, `pais_id`, `provincia_id`, `cod_postal_id`, `direccion`, `profesion_id`, `matricula`, `creado_por`, `modificado_por`, `created_at`, `updated_at`) VALUES
(1, '20111222', 'Juan', 'Pérez', '1975-05-12', '11223344', 1, 1, 1, 'Av. Siempreviva 742', 1, 'M-12345', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, '25111222', 'Laura', 'Fernández', '1980-08-22', '11445566', 1, 1, 1, 'Calle Falsa 123', 5, 'M-67890', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, '28111222', 'Carlos', 'Rodríguez', '1982-11-02', '11667788', 1, 2, 2, 'Av. Colón 456', 3, 'E-9876', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, '30111222', 'Sofía', 'López', '1985-03-15', '11889900', 1, 3, 3, 'Bv. Oroño 789', 4, 'I-5432', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, '32111222', 'Martín', 'Sánchez', '1988-06-30', '11001122', 1, 1, 1, 'Paseo Colón 1010', 1, 'M-55555', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(6, '22333444', 'Ricardo', 'Darín', '1970-01-16', '11556677', 1, 1, 1, 'Av. Corrientes 1200', 1, 'M-88331', 1, NULL, '2026-05-20 16:27:18', '2026-05-20 16:27:18'),
(7, '25444555', 'Elena', 'Roger', '1974-10-27', '11667788', 1, 1, 1, 'Callao 300', 1, 'M-99123', 1, NULL, '2026-05-20 16:27:18', '2026-05-20 16:27:18'),
(8, '21555666', 'Guillermo', 'Francella', '1965-02-15', '11778899', 1, 1, 1, 'Av. Libertador 2400', 1, 'M-77334', 1, NULL, '2026-05-20 16:27:18', '2026-05-20 16:27:18'),
(9, '28666777', 'Natalia', 'Oreiro', '1977-05-19', '11889900', 1, 2, 2, 'Av. Colón 800', 1, 'M-66221', 1, NULL, '2026-05-20 16:27:18', '2026-05-20 16:27:18'),
(10, '11111111', 'Dr. Martina', 'Silva', '1978-03-22', '555123456', 1, 1, 1, 'Av. Central 123', 1, 'SURG001', 1, 1, '2026-06-05 17:46:40', '2026-06-05 17:46:40'),
(11, '22222222', 'Dr. Lucas', 'Rossi', '1980-07-11', '555234567', 1, 1, 1, 'Calle 45', 1, 'SURG002', 1, 1, '2026-06-05 17:46:40', '2026-06-05 17:46:40'),
(12, '33333333', 'Dra. Sofia', 'Martinez', '1985-11-05', '555345678', 1, 1, 1, 'Pasaje del Sol 78', 1, 'SURG003', 1, 1, '2026-06-05 17:46:40', '2026-06-05 17:46:40'),
(13, '44444444', 'Dr. Bruno', 'Cruz', '1972-02-14', '555456789', 1, 1, 1, 'Ruta 5 km 12', 1, 'SURG004', 1, 1, '2026-06-05 17:46:40', '2026-06-05 17:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `especialidads`
--

CREATE TABLE `especialidads` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_modalidad_imagen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `especialidads`
--

INSERT INTO `especialidads` (`id`, `nombre`, `es_modalidad_imagen`, `created_at`, `updated_at`) VALUES
(1, 'Cirugía Cardiovascular', 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Traumatología', 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Cirugía General', 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Neurocirugía', 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Rayos (X)', 1, '2026-07-16 06:25:26', '2026-07-16 08:13:26'),
(6, 'Tomografía', 1, '2026-07-16 06:25:26', '2026-07-16 06:25:26');

-- --------------------------------------------------------

--
-- Table structure for table `estudios`
--

CREATE TABLE `estudios` (
  `id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estudios`
--

INSERT INTO `estudios` (`id`, `especialidad_id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 5, 'Rayos X de cráneo', '2026-07-16 06:41:41', '2026-07-16 06:41:41'),
(2, 5, 'Rayos X de tórax', '2026-07-16 06:42:00', '2026-07-16 06:42:00'),
(3, 5, 'Rayos X de columna cervical', '2026-07-16 06:42:15', '2026-07-16 06:42:15'),
(4, 5, 'Rayos X de hombro', '2026-07-16 06:42:24', '2026-07-16 06:42:24'),
(5, 5, 'Rayos X de muslo', '2026-07-16 06:42:41', '2026-07-16 06:42:41'),
(6, 6, 'Tomografía de cráneo', '2026-07-16 06:42:50', '2026-07-16 06:42:50'),
(7, 6, 'Tomografía de órbitas', '2026-07-16 06:42:58', '2026-07-16 06:42:58'),
(8, 6, 'Tomografía de tórax', '2026-07-16 06:43:10', '2026-07-16 06:43:10'),
(9, 6, 'Tomografía de columna cervical', '2026-07-16 06:43:18', '2026-07-16 06:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `estudio_medicos`
--

CREATE TABLE `estudio_medicos` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED DEFAULT NULL,
  `estudio_id` bigint UNSIGNED DEFAULT NULL,
  `regiones` tinyint UNSIGNED DEFAULT NULL,
  `medico_solicitante_id` bigint UNSIGNED NOT NULL,
  `ia` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora_estudio` time DEFAULT NULL,
  `cont_50ml` int NOT NULL DEFAULT '0',
  `cont_100ml` int NOT NULL DEFAULT '0',
  `jeringa_prellenada` int NOT NULL DEFAULT '0',
  `descartables` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otros_agujas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resultado` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estudio_medicos`
--

INSERT INTO `estudio_medicos` (`id`, `paciente_id`, `especialidad_id`, `estudio_id`, `regiones`, `medico_solicitante_id`, `ia`, `fecha`, `hora_estudio`, `cont_50ml`, `cont_100ml`, `jeringa_prellenada`, `descartables`, `otros_agujas`, `resultado`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, 1, NULL, '2026-06-13', NULL, 0, 0, 0, NULL, NULL, 'Estructuras óseas conservadas. Campos pulmonares limpios sin infiltrados.', '2026-06-19 00:45:31', '2026-06-19 00:45:31'),
(3, 1, 5, 3, 1, 5, 'I', '2026-06-13', NULL, 2, 1, 0, NULL, NULL, 'Estructuras óseas conservadas. Campos pulmonares limpios sin infiltrados.', '2026-06-19 00:50:32', '2026-07-16 23:28:44'),
(16, 1, 6, 8, 3, 1, 'A', '2026-07-16', NULL, 5, 0, 0, 'Guantes', 'Aguja', 'ASDASDASDA', '2026-07-16 08:12:04', '2026-07-16 23:28:30'),
(17, 6, 6, 8, 1, 8, 'I', '2026-07-16', NULL, 0, 0, 0, NULL, NULL, 'Y YA LO VE Y YA LO VE EL QUE NO SALTA ES UN INGLES', '2026-07-16 08:17:28', '2026-07-16 23:27:15'),
(19, 3, 5, 4, 1, 6, 'A', '2026-07-16', NULL, 0, 0, 0, 'wda', 'daw', 'dsawd', '2026-07-16 22:21:39', '2026-07-16 22:21:55'),
(20, 20, 5, 3, 3, 10, 'A', '2026-07-16', NULL, 0, 0, 1, 'Guantes', 'Otros', 'EL QUE NO SALTA ES UN INGLES', '2026-07-16 22:31:25', '2026-07-16 22:32:19'),
(21, 11, 5, 5, 1, 13, 'A', '2026-07-16', NULL, 1, 2, 2, NULL, NULL, NULL, '2026-07-17 02:42:12', '2026-07-17 02:42:12'),
(22, 17, 5, 1, 1, 6, 'A', '2026-07-16', NULL, 1, 1, 1, NULL, NULL, NULL, '2026-07-17 02:43:08', '2026-07-17 02:43:08'),
(23, 15, 5, 5, 1, 8, 'A', '2026-07-16', NULL, 1, 1, 1, NULL, NULL, NULL, '2026-07-17 02:44:18', '2026-07-17 02:44:18'),
(24, 11, 5, 3, 1, 5, 'A', '2026-07-16', NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-17 02:58:59', '2026-07-17 02:58:59'),
(25, 17, 6, 9, 1, 8, 'I', '2026-07-16', NULL, 0, 3, 0, NULL, NULL, NULL, '2026-07-17 03:00:42', '2026-07-17 03:00:42'),
(26, 16, 5, 4, 1, 6, 'A', '2026-07-17', NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-17 03:05:59', '2026-07-17 03:05:59'),
(27, 18, 5, 5, 1, 11, 'I', '2026-08-01', NULL, 5, 0, 0, NULL, NULL, NULL, '2026-08-01 22:11:29', '2026-08-01 22:11:29'),
(30, 11, 5, 1, 1, 13, 'A', '2026-08-13', NULL, 0, 0, 0, NULL, NULL, NULL, '2026-08-13 18:56:05', '2026-08-13 18:58:43'),
(32, 11, 5, 1, 4, 13, 'A', '2026-08-13', NULL, 8, 0, 0, 'Guantes', NULL, NULL, '2026-08-13 18:57:58', '2026-08-13 18:57:58'),
(33, 11, 5, 1, 1, 13, 'A', '2026-08-13', '21:50:00', 1, 1, 1, NULL, NULL, NULL, '2026-08-13 19:50:35', '2026-08-13 19:50:35'),
(34, 11, 5, 1, 1, 2, 'I', '2026-08-13', '18:29:00', 0, 0, 0, NULL, NULL, NULL, '2026-08-13 21:29:42', '2026-08-13 21:29:42'),
(35, 14, 5, 3, 1, 12, 'A', '2026-08-15', '22:17:00', 2, 2, 2, NULL, NULL, NULL, '2026-08-16 01:18:16', '2026-08-16 01:18:16'),
(37, 11, 6, NULL, 1, 8, 'A', '2026-08-20', '20:35:00', 0, 0, 0, NULL, NULL, 'hola', '2026-08-20 23:35:40', '2026-08-20 23:35:40'),
(38, 11, 5, NULL, 1, 2, 'A', '2026-08-20', '20:36:00', 0, 0, 0, NULL, NULL, 'holax2', '2026-08-20 23:36:24', '2026-08-20 23:36:24'),
(39, 11, 5, NULL, 1, 13, 'A', '2026-08-20', '20:53:00', 0, 0, 0, NULL, NULL, 'hola', '2026-08-20 23:53:51', '2026-08-20 23:53:51'),
(40, 16, 6, NULL, 1, 13, 'A', '2026-08-20', '20:54:00', 0, 0, 0, NULL, NULL, NULL, '2026-08-20 23:54:36', '2026-08-20 23:54:36'),
(41, 11, 6, NULL, 1, 8, 'A', '2026-08-20', '21:10:00', 0, 0, 0, NULL, NULL, NULL, '2026-08-21 00:10:31', '2026-08-21 00:10:31'),
(42, 11, 5, NULL, 1, 8, 'A', '2026-08-20', '21:11:00', 0, 0, 0, NULL, NULL, 'vddsvsdvsdvsdvsdvsdvsdvsdv', '2026-08-21 00:11:51', '2026-08-21 00:11:51'),
(43, 11, 6, 9, 1, 12, 'A', '2026-08-21', '07:19:00', 0, 0, 0, NULL, NULL, NULL, '2026-08-21 10:19:43', '2026-08-21 10:20:20');

-- --------------------------------------------------------

--
-- Table structure for table `habitacions`
--

CREATE TABLE `habitacions` (
  `id` bigint UNSIGNED NOT NULL,
  `sala_id` bigint UNSIGNED NOT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `habitacions`
--

INSERT INTO `habitacions` (`id`, `sala_id`, `numero`, `created_at`, `updated_at`) VALUES
(1, 1, '101', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 1, '102', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 2, '201', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 2, '202', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 3, '301', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `historial_stocks`
--

CREATE TABLE `historial_stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_id` bigint UNSIGNED NOT NULL,
  `estudio_medico_id` bigint UNSIGNED DEFAULT NULL,
  `cantidad` int NOT NULL,
  `fecha` datetime NOT NULL,
  `empleado_id` bigint UNSIGNED DEFAULT NULL,
  `paciente_id` bigint UNSIGNED DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `creado_por` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `historial_stocks`
--

INSERT INTO `historial_stocks` (`id`, `stock_id`, `estudio_medico_id`, `cantidad`, `fecha`, `empleado_id`, `paciente_id`, `comentario`, `creado_por`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 150, '2026-05-10 09:00:00', 3, NULL, 'Carga inicial de farmacia', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 1, NULL, -10, '2026-05-19 18:00:00', 3, 1, 'Suministro analgesia paciente Pedro García', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 1, NULL, -20, '2026-05-20 08:30:00', 3, 3, 'Suministro analgesia paciente Lucía Díaz', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 2, NULL, 50, '2026-05-15 10:00:00', 2, NULL, 'Recepción de proveedor', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 2, NULL, -5, '2026-05-20 07:00:00', 2, 4, 'Anestesia cirugía urgencia', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(6, 3, NULL, 50, '2026-05-01 08:00:00', 3, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(7, 3, NULL, -5, '2026-05-03 10:15:00', 3, 1, 'Administración post-cirugía', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(8, 3, NULL, -10, '2026-05-06 14:30:00', 3, 5, 'Tratamiento dolor agudo', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(9, 3, NULL, -8, '2026-05-09 11:00:00', 3, 6, 'Administración indicada', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(10, 3, NULL, -12, '2026-05-12 16:45:00', 3, 7, 'Control de inflamación', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(11, 3, NULL, -5, '2026-05-15 09:30:00', 3, 8, 'Tratamiento post-trauma', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(12, 3, NULL, -10, '2026-05-18 15:20:00', 3, 9, 'Administración de rutina', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(13, 4, NULL, 20, '2026-05-01 08:15:00', 2, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(14, 4, NULL, -2, '2026-05-04 09:00:00', 2, 1, 'Anestesia quirúrgica', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(15, 4, NULL, -4, '2026-05-07 11:30:00', 2, 3, 'Inducción anestésica cirugía', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(16, 4, NULL, -3, '2026-05-11 08:00:00', 2, 8, 'Procedimiento cardiológico', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(17, 4, NULL, -5, '2026-05-14 15:00:00', 2, 11, 'Anestesia general', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(18, 4, NULL, -6, '2026-05-18 10:20:00', 2, 13, 'Cirugía alta complejidad', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(19, 5, NULL, 300, '2026-05-01 08:30:00', 3, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(20, 5, NULL, -30, '2026-05-02 10:00:00', 3, 2, 'Entrega a enfermería de piso', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(21, 5, NULL, -50, '2026-05-05 16:00:00', 3, 10, 'Consumo general de guardia', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(22, 5, NULL, -40, '2026-05-08 09:15:00', 3, 12, 'Suministro diario pacientes', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(23, 5, NULL, -60, '2026-05-11 11:30:00', 3, 14, 'Reposición botiquín ala oeste', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(24, 5, NULL, -50, '2026-05-15 14:00:00', 3, 16, 'Distribución clínica médica', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(25, 5, NULL, -70, '2026-05-19 17:45:00', 3, 18, 'Entrega a consultorios externos', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(26, 6, NULL, 15, '2026-05-01 08:45:00', 2, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(27, 6, NULL, -3, '2026-05-04 18:30:00', 2, 4, 'Control de dolor oncológico', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(28, 6, NULL, -2, '2026-05-08 20:00:00', 2, 9, 'Sedación analgesia UTI', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(29, 6, NULL, -4, '2026-05-12 22:15:00', 2, 15, 'Postoperatorio de neurocirugía', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(30, 6, NULL, -3, '2026-05-16 02:00:00', 2, 19, 'Trauma severo por guardia', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(31, 6, NULL, -3, '2026-05-19 05:30:00', 2, 20, 'Manejo del dolor agudo', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(32, 7, NULL, 40, '2026-05-01 09:00:00', 2, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(33, 7, NULL, -5, '2026-05-03 08:00:00', 2, 2, 'Premedicación anestésica', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(34, 7, NULL, -8, '2026-05-07 14:00:00', 2, 7, 'Sedación para procedimiento', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(35, 7, NULL, -10, '2026-05-11 10:30:00', 2, 12, 'Procedimientos quirúrgicos', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(36, 7, NULL, -12, '2026-05-15 16:00:00', 2, 17, 'Inducción de anestesia', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(37, 7, NULL, -5, '2026-05-18 11:15:00', 2, 18, 'Sedación complementaria', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(38, 8, NULL, 500, '2026-05-01 09:15:00', 3, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(39, 8, NULL, -50, '2026-05-02 09:00:00', 3, 3, 'Distribución diaria de salas', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(40, 8, NULL, -100, '2026-05-05 13:00:00', 3, 6, 'Entrega guardia de pediatría', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(41, 8, NULL, -80, '2026-05-09 15:30:00', 3, 9, 'Suministro pabellón de clínica', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(42, 8, NULL, -120, '2026-05-12 10:00:00', 3, 13, 'Reposición farmacia interna', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(43, 8, NULL, -70, '2026-05-16 14:45:00', 3, 15, 'Tratamiento antipirético general', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(44, 8, NULL, -80, '2026-05-19 11:20:00', 3, 17, 'Suministro pabellón traumatología', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(45, 9, NULL, 100, '2026-05-01 09:30:00', 3, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(46, 9, NULL, -10, '2026-05-03 12:00:00', 3, 4, 'Manejo del dolor muscular', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(47, 9, NULL, -20, '2026-05-06 17:00:00', 3, 8, 'Tratamiento antiinflamatorio', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(48, 9, NULL, -15, '2026-05-10 10:30:00', 3, 10, 'Postoperatorio traumatología', 1, '2026-05-20 15:30:34', '2026-05-20 15:30:34'),
(49, 9, NULL, -25, '2026-05-13 15:00:00', 3, 14, 'Suministro guardia general', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(50, 9, NULL, -10, '2026-05-16 11:00:00', 3, 16, 'Postoperatorio cirugía general', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(51, 9, NULL, -20, '2026-05-19 14:10:00', 3, 20, 'Analgesia pacientes internados', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(52, 10, NULL, 80, '2026-05-01 09:45:00', 3, NULL, 'Carga inicial de stock', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(53, 10, NULL, -10, '2026-05-04 10:00:00', 3, 5, 'Tratamiento antibiótico endovenoso', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(54, 10, NULL, -15, '2026-05-08 16:30:00', 3, 9, 'Profilaxis antibiótica prequirúrgica', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(55, 10, NULL, -20, '2026-05-12 11:15:00', 3, 11, 'Tratamiento infección respiratoria', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(56, 10, NULL, -25, '2026-05-16 14:00:00', 3, 15, 'Esquema de tratamiento UTI', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(57, 10, NULL, -10, '2026-05-19 09:45:00', 3, 18, 'Profilaxis quirúrgica', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(58, 2, NULL, 15, '2026-05-05 08:30:00', 2, NULL, 'Recepción de proveedor (extra)', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(59, 2, NULL, -5, '2026-05-07 09:10:00', 2, 6, 'Anestesia cirugía general', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(60, 2, NULL, -10, '2026-05-14 11:30:00', 2, 10, 'Procedimiento anestésico programado', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(61, 1, NULL, 30, '2026-05-05 08:00:00', 3, NULL, 'Recepción de proveedor (extra)', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(62, 1, NULL, -15, '2026-05-08 14:00:00', 3, 12, 'Control de fiebre en internación', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(63, 1, NULL, -15, '2026-05-15 15:30:00', 3, 16, 'Suministro general clínica', 1, '2026-05-20 15:30:35', '2026-05-20 15:30:35'),
(64, 14, NULL, 1000, '2026-07-16 00:00:00', NULL, NULL, 'Carga inicial de stock', 1, '2026-07-16 08:05:05', '2026-07-16 08:05:05'),
(65, 15, NULL, 1000, '2026-07-16 00:00:00', NULL, NULL, 'Carga inicial de stock', 1, '2026-07-16 08:05:53', '2026-07-16 08:05:53'),
(66, 16, NULL, 1000, '2026-07-16 00:00:00', NULL, NULL, 'Carga inicial de stock', 1, '2026-07-16 08:06:33', '2026-07-16 08:06:33'),
(67, 15, 16, -3, '2026-07-16 00:00:00', 1, 1, 'Consumido en estudio médico #16 (Contraste 50ml)', 1, '2026-07-16 08:12:04', '2026-07-16 08:12:04'),
(68, 16, 16, -2, '2026-07-16 00:00:00', 1, 1, 'Consumido en estudio médico #16 (Contraste 100ml)', 1, '2026-07-16 08:12:04', '2026-07-16 08:12:04'),
(69, 14, 16, -1, '2026-07-16 00:00:00', 1, 1, 'Consumido en estudio médico #16 (Jeringa Prellenada)', 1, '2026-07-16 08:12:04', '2026-07-16 08:12:04'),
(70, 15, 17, -2, '2026-07-16 00:00:00', 8, 6, 'Consumido en estudio médico #17 (Contraste 50ml)', 1, '2026-07-16 08:17:28', '2026-07-16 08:17:28'),
(71, 16, 17, -1, '2026-07-16 00:00:00', 8, 6, 'Consumido en estudio médico #17 (Contraste 100ml)', 1, '2026-07-16 08:17:28', '2026-07-16 08:17:28'),
(72, 14, 17, -1, '2026-07-16 00:00:00', 8, 6, 'Consumido en estudio médico #17 (Jeringa Prellenada)', 1, '2026-07-16 08:17:28', '2026-07-16 08:17:28'),
(73, 15, 16, 3, '2026-07-16 00:00:00', 1, 1, 'Devolución automática (Edición) por estudio médico #16 (Contraste 50ml)', 1, '2026-07-16 18:42:49', '2026-07-16 18:42:49'),
(74, 15, NULL, -3, '2026-07-16 00:00:00', 6, 15, 'Consumido en estudio médico #18 (Contraste 50ml)', 1, '2026-07-16 18:44:33', '2026-07-16 18:44:33'),
(75, 16, NULL, -2, '2026-07-16 00:00:00', 6, 15, 'Consumido en estudio médico #18 (Contraste 100ml)', 1, '2026-07-16 18:44:33', '2026-07-16 18:44:33'),
(76, 14, NULL, -3, '2026-07-16 00:00:00', 6, 15, 'Consumido en estudio médico #18 (Jeringa Prellenada)', 1, '2026-07-16 18:44:33', '2026-07-16 18:44:33'),
(77, 15, NULL, 3, '2026-07-16 00:00:00', 6, 15, 'Devolución automática (Edición) por estudio médico #18 (Contraste 50ml)', 1, '2026-07-16 18:45:02', '2026-07-16 18:45:02'),
(78, 16, NULL, 2, '2026-07-16 00:00:00', 6, 15, 'Devolución automática (Edición) por estudio médico #18 (Contraste 100ml)', 1, '2026-07-16 18:45:02', '2026-07-16 18:45:02'),
(79, 14, NULL, 3, '2026-07-16 00:00:00', 6, 15, 'Devolución automática (Edición) por estudio médico #18 (Jeringa Prellenada)', 1, '2026-07-16 18:45:02', '2026-07-16 18:45:02'),
(80, 15, 19, -1, '2026-07-16 00:00:00', 6, 3, 'Consumido en estudio médico #19 (Contraste 50ml)', 1, '2026-07-16 22:21:39', '2026-07-16 22:21:39'),
(81, 16, 19, -1, '2026-07-16 00:00:00', 6, 3, 'Consumido en estudio médico #19 (Contraste 100ml)', 1, '2026-07-16 22:21:39', '2026-07-16 22:21:39'),
(82, 14, 19, -1, '2026-07-16 00:00:00', 6, 3, 'Consumido en estudio médico #19 (Jeringa Prellenada)', 1, '2026-07-16 22:21:39', '2026-07-16 22:21:39'),
(83, 15, 19, 1, '2026-07-16 00:00:00', 6, 3, 'Devolución automática (Edición) por estudio médico #19 (Contraste 50ml)', 1, '2026-07-16 22:21:55', '2026-07-16 22:21:55'),
(84, 16, 19, 1, '2026-07-16 00:00:00', 6, 3, 'Devolución automática (Edición) por estudio médico #19 (Contraste 100ml)', 1, '2026-07-16 22:21:55', '2026-07-16 22:21:55'),
(85, 14, 19, 1, '2026-07-16 00:00:00', 6, 3, 'Devolución automática (Edición) por estudio médico #19 (Jeringa Prellenada)', 1, '2026-07-16 22:21:55', '2026-07-16 22:21:55'),
(86, 16, 20, -1, '2026-07-16 00:00:00', 10, 20, 'Consumido en estudio médico #20 (Contraste 100ml)', 1, '2026-07-16 22:31:25', '2026-07-16 22:31:25'),
(87, 14, 20, -1, '2026-07-16 00:00:00', 10, 20, 'Consumido en estudio médico #20 (Jeringa Prellenada)', 1, '2026-07-16 22:31:25', '2026-07-16 22:31:25'),
(88, 16, 20, 1, '2026-07-16 00:00:00', 10, 20, 'Devolución automática (Edición) por estudio médico #20 (Contraste 100ml)', 1, '2026-07-16 22:32:19', '2026-07-16 22:32:19'),
(89, 15, 16, 2, '2026-07-16 00:00:00', 1, 1, 'Devolución por corrección en estudio médico #16 (Contraste 50ml)', 1, '2026-07-16 23:26:50', '2026-07-16 23:26:50'),
(90, 16, 16, 1, '2026-07-16 00:00:00', 1, 1, 'Devolución por corrección en estudio médico #16 (Contraste 100ml)', 1, '2026-07-16 23:26:50', '2026-07-16 23:26:50'),
(91, 15, 17, 3, '2026-07-16 00:00:00', 8, 6, 'Devolución por corrección en estudio médico #17 (Contraste 50ml)', 1, '2026-07-16 23:27:15', '2026-07-16 23:27:15'),
(92, 16, 17, 1, '2026-07-16 00:00:00', 8, 6, 'Devolución por corrección en estudio médico #17 (Contraste 100ml)', 1, '2026-07-16 23:27:15', '2026-07-16 23:27:15'),
(93, 14, 17, 1, '2026-07-16 00:00:00', 8, 6, 'Devolución por corrección en estudio médico #17 (Jeringa Prellenada)', 1, '2026-07-16 23:27:15', '2026-07-16 23:27:15'),
(94, 15, 16, -3, '2026-07-16 00:00:00', 1, 1, 'Consumido en estudio médico #16 (Contraste 50ml)', 1, '2026-07-16 23:28:30', '2026-07-16 23:28:30'),
(95, 16, 16, 1, '2026-07-16 00:00:00', 1, 1, 'Devolución por corrección en estudio médico #16 (Contraste 100ml)', 1, '2026-07-16 23:28:30', '2026-07-16 23:28:30'),
(96, 14, 16, 1, '2026-07-16 00:00:00', 1, 1, 'Devolución por corrección en estudio médico #16 (Jeringa Prellenada)', 1, '2026-07-16 23:28:30', '2026-07-16 23:28:30'),
(97, 14, 3, 1, '2026-07-16 00:00:00', 5, 1, 'Devolución por corrección en estudio médico #3 (Jeringa Prellenada)', 1, '2026-07-16 23:28:44', '2026-07-16 23:28:44'),
(98, 15, NULL, -4, '2026-07-16 00:00:00', 4, 16, 'Consumido en estudio médico #6 (Contraste 50ml)', 1, '2026-07-16 23:33:19', '2026-07-16 23:33:19'),
(99, 16, NULL, -2, '2026-07-16 00:00:00', 4, 16, 'Consumido en estudio médico #6 (Contraste 100ml)', 1, '2026-07-16 23:33:19', '2026-07-16 23:33:19'),
(100, 14, NULL, -2, '2026-07-16 00:00:00', 4, 16, 'Consumido en estudio médico #6 (Jeringa Prellenada)', 1, '2026-07-16 23:33:19', '2026-07-16 23:33:19'),
(101, 15, NULL, 4, '2026-07-16 00:00:00', 4, 16, 'Devolución por corrección en estudio médico #6 (Contraste 50ml)', 1, '2026-07-16 23:33:29', '2026-07-16 23:33:29'),
(102, 16, NULL, 2, '2026-07-16 00:00:00', 4, 16, 'Devolución por corrección en estudio médico #6 (Contraste 100ml)', 1, '2026-07-16 23:33:29', '2026-07-16 23:33:29'),
(103, 14, NULL, 2, '2026-07-16 00:00:00', 4, 16, 'Devolución por corrección en estudio médico #6 (Jeringa Prellenada)', 1, '2026-07-16 23:33:29', '2026-07-16 23:33:29'),
(104, 15, 21, -1, '2026-07-16 00:00:00', 13, 11, 'Consumido en estudio médico #21 (Contraste 50ml)', 1, '2026-07-17 02:42:12', '2026-07-17 02:42:12'),
(105, 16, 21, -2, '2026-07-16 00:00:00', 13, 11, 'Consumido en estudio médico #21 (Contraste 100ml)', 1, '2026-07-17 02:42:12', '2026-07-17 02:42:12'),
(106, 14, 21, -2, '2026-07-16 00:00:00', 13, 11, 'Consumido en estudio médico #21 (Jeringa Prellenada)', 1, '2026-07-17 02:42:12', '2026-07-17 02:42:12'),
(107, 15, 22, -1, '2026-07-16 00:00:00', 6, 17, 'Consumido en estudio médico #22 (Contraste 50ml)', 1, '2026-07-17 02:43:08', '2026-07-17 02:43:08'),
(108, 16, 22, -1, '2026-07-16 00:00:00', 6, 17, 'Consumido en estudio médico #22 (Contraste 100ml)', 1, '2026-07-17 02:43:08', '2026-07-17 02:43:08'),
(109, 14, 22, -1, '2026-07-16 00:00:00', 6, 17, 'Consumido en estudio médico #22 (Jeringa Prellenada)', 1, '2026-07-17 02:43:08', '2026-07-17 02:43:08'),
(110, 15, 23, -1, '2026-07-16 00:00:00', 8, 15, 'Consumido en estudio médico #23 (Contraste 50ml)', 1, '2026-07-17 02:44:18', '2026-07-17 02:44:18'),
(111, 16, 23, -1, '2026-07-16 00:00:00', 8, 15, 'Consumido en estudio médico #23 (Contraste 100ml)', 1, '2026-07-17 02:44:18', '2026-07-17 02:44:18'),
(112, 14, 23, -1, '2026-07-16 00:00:00', 8, 15, 'Consumido en estudio médico #23 (Jeringa Prellenada)', 1, '2026-07-17 02:44:18', '2026-07-17 02:44:18'),
(113, 16, 25, -3, '2026-07-17 00:00:00', 8, 17, 'Consumido en estudio médico #25 (Contraste 100ml)', 1, '2026-07-17 03:00:42', '2026-07-17 03:00:42'),
(114, 15, 27, -5, '2026-08-01 00:00:00', 11, 18, 'Consumido en estudio médico #27 (Contraste 50ml)', 1, '2026-08-01 22:11:29', '2026-08-01 22:11:29'),
(115, 15, NULL, -3, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #28 (Contraste 50ml)', 1, '2026-08-13 18:44:52', '2026-08-13 18:44:52'),
(116, 16, NULL, -2, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #28 (Contraste 100ml)', 1, '2026-08-13 18:44:52', '2026-08-13 18:44:52'),
(117, 14, NULL, -1, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #28 (Jeringa Prellenada)', 1, '2026-08-13 18:44:52', '2026-08-13 18:44:52'),
(118, 15, 30, -980, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #30 (Contraste 50ml)', 1, '2026-08-13 18:56:05', '2026-08-13 18:56:05'),
(119, 15, 32, -8, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #32 (Contraste 50ml)', 1, '2026-08-13 18:57:58', '2026-08-13 18:57:58'),
(120, 15, 30, 980, '2026-08-13 00:00:00', 13, 11, 'Devolución automática (Edición) por estudio médico #30 (Contraste 50ml)', 1, '2026-08-13 18:58:43', '2026-08-13 18:58:43'),
(121, 15, 33, -1, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #33 (Contraste 50ml)', 1, '2026-08-13 19:50:35', '2026-08-13 19:50:35'),
(122, 16, 33, -1, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #33 (Contraste 100ml)', 1, '2026-08-13 19:50:35', '2026-08-13 19:50:35'),
(123, 14, 33, -1, '2026-08-13 00:00:00', 13, 11, 'Consumido en estudio médico #33 (Jeringa Prellenada)', 1, '2026-08-13 19:50:35', '2026-08-13 19:50:35'),
(124, 15, 35, -2, '2026-08-15 00:00:00', 12, 14, 'Consumido en estudio médico #35 (Contraste 50ml)', 1, '2026-08-16 01:18:16', '2026-08-16 01:18:16'),
(125, 16, 35, -2, '2026-08-15 00:00:00', 12, 14, 'Consumido en estudio médico #35 (Contraste 100ml)', 1, '2026-08-16 01:18:16', '2026-08-16 01:18:16'),
(126, 14, 35, -2, '2026-08-15 00:00:00', 12, 14, 'Consumido en estudio médico #35 (Jeringa Prellenada)', 1, '2026-08-16 01:18:16', '2026-08-16 01:18:16'),
(127, 15, NULL, 3, '2026-08-15 00:00:00', 13, 11, 'Devolución automática (Edición) por estudio médico #28 (Contraste 50ml)', 1, '2026-08-16 01:18:38', '2026-08-16 01:18:38'),
(128, 16, NULL, 2, '2026-08-15 00:00:00', 13, 11, 'Devolución automática (Edición) por estudio médico #28 (Contraste 100ml)', 1, '2026-08-16 01:18:38', '2026-08-16 01:18:38'),
(129, 14, NULL, 1, '2026-08-15 00:00:00', 13, 11, 'Devolución automática (Edición) por estudio médico #28 (Jeringa Prellenada)', 1, '2026-08-16 01:18:38', '2026-08-16 01:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `imagenes_estudio`
--

CREATE TABLE `imagenes_estudio` (
  `id` bigint UNSIGNED NOT NULL,
  `estudio_medico_id` bigint UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicamentos`
--

INSERT INTO `medicamentos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Dipirona 500mg', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Propofol 1% 20ml', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Ketoprofeno 100mg', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Fentanilo 0.1mg 2ml', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Amoxicilina 1g', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(6, 'Ibuprofeno 400mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(7, 'Morfina 10mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(8, 'Midazolam 15mg/3ml', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(9, 'Paracetamol 1g', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(10, 'Diclofenac 75mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(11, 'Omeprazol 40mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(12, 'Ceftriaxona 1g', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(13, 'Ranitidina 50mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(14, 'Losartán 50mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(15, 'Atorvastatina 20mg', '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(21, 'Contraste 50ml', '2026-07-16 07:57:34', '2026-07-16 07:57:34'),
(22, 'Contraste 100ml', '2026-07-16 07:57:45', '2026-07-16 07:57:45'),
(23, 'Jeringa Prellenada', '2026-07-16 07:58:13', '2026-07-16 07:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_06_17_215032_create_estudio_medicos_table', 1),
(2, '2026_07_01_191349_add_link_imagen_to_estudio_medicos_table', 2),
(3, '2026_07_02_184642_create_imagenes_estudio_table', 3),
(4, '2026_07_02_184642_create_estudio_medico_imagenes_table', 4),
(5, '2026_07_15_100000_add_estudios_medicos_to_usuario_perfils_table', 1),
(6, '2026_07_15_100100_add_es_modalidad_imagen_to_especialidads_table', 5),
(7, '2026_07_15_100200_create_estudios_table', 6),
(8, '2026_07_15_100300_add_especialidad_and_estudio_to_estudio_medicos_table', 7),
(9, '2026_07_15_100400_add_umbrales_to_stocks_table', 8),
(10, '2026_07_15_100500_add_estudio_medico_id_to_historial_stocks_table', 9),
(11, '2014_10_12_000000_create_users_table', 1),
(12, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(13, '2019_08_19_000000_create_failed_jobs_table', 1),
(14, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(15, '2025_05_26_213932_create_usuario_perfils_table', 1),
(16, '2025_05_26_214244_create_pais_table', 1),
(17, '2025_05_26_214802_create_provincias_table', 1),
(18, '2025_05_26_215936_create_codigo_postals_table', 1),
(19, '2025_05_26_224115_create_profesions_table', 1),
(20, '2025_05_26_224510_create_empleados_table', 1),
(21, '2025_05_26_230355_create_pacientes_table', 1),
(22, '2025_05_26_231638_create_procedimientos_table', 1),
(23, '2025_05_26_231933_create_tipo_anestesias_table', 1),
(24, '2025_05_26_231957_create_cirugias_table', 1),
(25, '2025_05_27_000500_create_stocks_table', 1),
(26, '2025_05_27_001021_create_historial_stocks_table', 1),
(27, '2025_05_27_003429_create_salas_table', 1),
(28, '2025_05_27_003638_create_habitacions_table', 1),
(29, '2025_05_27_004043_create_camas_table', 1),
(30, '2025_05_27_004424_create_ocupacion_camas_table', 1),
(31, '2025_06_04_194305_create_medicamentos_table', 1),
(32, '2025_06_04_194652_add_medicamento_id_to_stocks_table', 1),
(33, '2025_06_30_150151_add_habitacion_y_cama_to_pacientes_table', 1),
(34, '2025_06_30_152514_add_ocupada_to_camas_table', 1),
(35, '2025_07_07_133549_create_quirofanos_table', 1),
(36, '2025_07_07_144547_add_quirofano_id_to_cirugias_table', 1),
(37, '2025_07_09_230519_add_genero_to_pacientes_table', 1),
(38, '2025_07_10_224209_remove_unique_lote_from_stocks_table', 1),
(39, '2025_07_10_225630_restore_unique_lote_to_stocks_table', 1),
(40, '2025_07_17_122740_add_enfermero_id_to_cirugias_table', 1),
(41, '2025_08_08_234912_add_datetime_to_cirugias_table', 1),
(42, '2025_08_09_003808_create_rol_profesions_table', 1),
(43, '2025_08_09_003926_add_rol_to_profesions_table', 1),
(44, '2025_09_18_221844_create_especialidad_table', 1),
(45, '2025_09_18_222517_add_especialidad_id_to_procedimientos_table', 1),
(46, '2025_09_18_224859_add_tipo_anestesia_id_to_procedimientos_table', 1),
(47, '2025_09_18_225929_remove_tipo_anestesia_id_from_procedimientos_table', 1),
(48, '2025_09_23_020243_add_aditional_fields_to_cirugias_table', 1),
(49, '2025_09_27_222331_add_role_to_users_table', 1),
(50, '2025_10_21_015414_add_fields_to_usuario_perfils_table', 1),
(51, '2025_10_30_225527_add_creado_por_as_foreing_key', 1),
(52, '2025_11_06_212926_add_tipo_anestesia_3_id_to_cirugias_table', 1),
(53, '2025_12_17_154000_add_servicio_to_stocks_table', 1),
(54, '2025_12_17_155000_create_servicios_and_update_stocks', 1),
(55, '2025_12_17_160000_add_servicio_id_to_users_table', 1),
(56, '2025_12_18_add_alergias_to_pacientes_table', 1),
(57, '2025_12_18_add_matricula_to_empleados_table', 1),
(58, '2026_06_03_143626_make_instrumentador_id_nullable_in_cirugias_table', 1),
(59, '2026_06_03_163648_make_anestesista_and_tipo_nullable_in_cirugias_table', 1),
(60, '2026_07_15_100600_drop_leftover_nombre_column_from_stocks_table', 10),
(61, '2026_07_16_100000_make_empleado_id_nullable_in_historial_stocks_table', 11),
(62, '2026_08_13_162938_add_hora_estudio_to_estudio_medicos_table', 12),
(63, '2026_08_21_060000_drop_tipo_estudio_from_estudio_medicos_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `ocupacion_camas`
--

CREATE TABLE `ocupacion_camas` (
  `id` bigint UNSIGNED NOT NULL,
  `cama_id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_egreso` datetime DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ocupacion_camas`
--

INSERT INTO `ocupacion_camas` (`id`, `cama_id`, `paciente_id`, `fecha_ingreso`, `fecha_egreso`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-05-18 10:00:00', NULL, 'Ingreso programado para cirugía', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 3, 2, '2026-05-19 14:30:00', NULL, 'Control post-operatorio', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 4, 3, '2026-05-17 08:00:00', NULL, 'Monitoreo general', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 6, 4, '2026-05-20 02:15:00', NULL, 'Ingreso por guardia - Trauma', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `pacientes`
--

CREATE TABLE `pacientes` (
  `id` bigint UNSIGNED NOT NULL,
  `dni` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alergias` text COLLATE utf8mb4_unicode_ci,
  `pais_id` bigint UNSIGNED NOT NULL,
  `provincia_id` bigint UNSIGNED NOT NULL,
  `cod_postal_id` bigint UNSIGNED NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creado_por` bigint UNSIGNED DEFAULT NULL,
  `modificado_por` bigint UNSIGNED DEFAULT NULL,
  `cama_id` bigint UNSIGNED DEFAULT NULL,
  `habitacion_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pacientes`
--

INSERT INTO `pacientes` (`id`, `dni`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `telefono`, `alergias`, `pais_id`, `provincia_id`, `cod_postal_id`, `direccion`, `creado_por`, `modificado_por`, `cama_id`, `habitacion_id`, `created_at`, `updated_at`) VALUES
(1, '40999888', 'Pedro', 'García', '1995-10-15', 'Masculino', '1122223333', 'Penicilina', 1, 1, 1, 'Av. San Martín 150', 1, NULL, 1, 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, '42999888', 'Ana', 'Martínez', '1998-04-20', 'Femenino', '1133334444', NULL, 1, 1, 1, 'Calle Rivadavia 2500', 1, NULL, 3, 2, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, '35999888', 'Lucía', 'Díaz', '1990-09-05', 'Femenino', '1144445555', 'Ibuprofeno', 1, 2, 2, 'Av. Patria 345', 1, NULL, 4, 3, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, '15999888', 'Roberto', 'Pérez', '1955-01-25', 'Masculino', '1155556666', 'Mariscos', 1, 3, 3, 'Urquiza 987', 1, NULL, 6, 5, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, '38123456', 'Mateo', 'Gómez', '1994-02-10', 'Masculino', '1166667777', NULL, 1, 1, 1, 'Florida 450', 1, NULL, 2, 1, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(6, '29123456', 'Sofía', 'Rodríguez', '1982-07-15', 'Femenino', '1177778888', 'Dipirona', 1, 1, 1, 'Calle Falsa 123', 1, NULL, 5, 4, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(7, '33123456', 'Diego', 'Fernández', '1987-12-05', 'Masculino', '1188889999', NULL, 1, 1, 1, 'Av. de Mayo 200', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(8, '45123456', 'Valentina', 'López', '2004-05-22', 'Femenino', '1199990000', NULL, 1, 1, 1, 'Belgrano 1200', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(9, '20123456', 'Jorge', 'Martínez', '1970-09-18', 'Masculino', '1100001111', 'Aspirina', 1, 1, 1, 'Corrientes 3400', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(10, '31123456', 'Camila', 'Sánchez', '1985-03-30', 'Femenino', '1111112222', NULL, 1, 1, 1, 'Santa Fe 2800', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(11, '22123456', 'Miguel', 'Álvarez', '1972-11-12', 'Masculino', '1122223333', NULL, 1, 1, 1, 'Córdoba 1500', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(12, '41123456', 'Isabella', 'Romero', '1998-06-25', 'Femenino', '1133334444', NULL, 1, 1, 1, 'Pueyrredón 900', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(13, '36123456', 'Lucas', 'González', '1991-01-08', 'Masculino', '1144445555', 'Penicilina', 1, 2, 2, 'San Martín 600', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(14, '30123456', 'Martina', 'Herrera', '1984-08-14', 'Femenino', '1155556666', NULL, 1, 2, 2, 'Chacabuco 800', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(15, '27123456', 'Nicolás', 'Díaz', '1979-04-02', 'Masculino', '1166667777', NULL, 1, 2, 2, 'Rondeau 120', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(16, '43123456', 'Catalina', 'Castro', '2001-10-10', 'Femenino', '1177778888', NULL, 1, 3, 3, 'Bv. Rondeau 1500', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(17, '39123456', 'Tomás', 'Ruiz', '1995-12-30', 'Masculino', '1188889999', NULL, 1, 3, 3, 'San Luis 2200', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(18, '35123456', 'Emilia', 'Silva', '1990-03-24', 'Femenino', '1199990000', 'Látex', 1, 3, 3, 'Mitre 500', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(19, '28123456', 'Bautista', 'Sosa', '1980-02-18', 'Masculino', '1100001111', NULL, 1, 3, 3, 'Pellegrini 1400', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(20, '44123456', 'Olivia', 'Ortiz', '2003-07-07', 'Femenino', '1111112222', NULL, 1, 1, 1, 'Callao 600', 1, NULL, NULL, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `pais`
--

CREATE TABLE `pais` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pais`
--

INSERT INTO `pais` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Argentina', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Uruguay', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Chile', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `procedimientos`
--

CREATE TABLE `procedimientos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_procedimiento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `especialidad_id` bigint UNSIGNED NOT NULL,
  `especialidad_2_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `procedimientos`
--

INSERT INTO `procedimientos` (`id`, `nombre_procedimiento`, `descripcion`, `especialidad_id`, `especialidad_2_id`, `created_at`, `updated_at`) VALUES
(1, 'Bypass Coronario', 'Procedimiento de revascularización cardíaca', 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Apendicectomía', 'Extirpación del apéndice', 3, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Colecistectomía', 'Extirpación de la vesícula biliar', 3, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Artroplastia de Cadera', 'Reemplazo de cadera', 2, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Herniorrafia Inguinal', 'Reparación de hernia inguinal', 3, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(6, 'Craneotomía', 'Apertura quirúrgica del cráneo', 4, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(7, 'Cesárea de Urgencia', 'Parto quirúrgico', 3, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(8, 'Osteosíntesis de Fémur', 'Fijación de fractura de fémur', 2, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `profesions`
--

CREATE TABLE `profesions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_profesion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `rol_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profesions`
--

INSERT INTO `profesions` (`id`, `nombre_profesion`, `descripcion`, `rol_id`, `created_at`, `updated_at`) VALUES
(1, 'Cirujano General', 'Especialista en cirugía general', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Cardiólogo', 'Especialista en cardiología', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Enfermero Profesional', 'Enfermería en salas de internación', 2, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Instrumentador Quirúrgico', 'Instrumentación en quirófano', 3, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Anestesiólogo', 'Administración de anestesias', 4, '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `provincias`
--

CREATE TABLE `provincias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provincias`
--

INSERT INTO `provincias` (`id`, `nombre`, `pais_id`, `created_at`, `updated_at`) VALUES
(1, 'Buenos Aires', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Córdoba', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Santa Fe', 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Montevideo', 2, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Santiago', 3, '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `quirofanos`
--

CREATE TABLE `quirofanos` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quirofanos`
--

INSERT INTO `quirofanos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Quirófano A', 'Alta complejidad cardiovascular', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Quirófano B', 'Cirugía general y laparoscópica', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Quirófano C', 'Traumatología y urgencias', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `rol_profesions`
--

CREATE TABLE `rol_profesions` (
  `id` bigint UNSIGNED NOT NULL,
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rol_profesions`
--

INSERT INTO `rol_profesions` (`id`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'Médico', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Enfermero', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Instrumentador', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Anestesista', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `salas`
--

CREATE TABLE `salas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salas`
--

INSERT INTO `salas` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Sala de Internación Hombres', 'Piso 1 - Ala Este', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Sala de Internación Mujeres', 'Piso 2 - Ala Oeste', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Terapia Intensiva', 'Piso 3 - Alta Complejidad', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Pediatría', 'Servicio de Pediatría', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Cardiología', 'Servicio de Cardiología', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Traumatología', 'Servicio de Traumatología', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Clínica Médica', 'Servicio de Clínica Médica', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Cirugía General', 'Servicio de Cirugía General', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(6, 'Diagnóstico por imágenes', 'Diagnóstico por imágenes', '2026-07-16 06:46:20', '2026-07-16 06:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `lote` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `cantidad_act` int NOT NULL,
  `umbral_aviso` int UNSIGNED DEFAULT '50',
  `umbral_critico` int UNSIGNED DEFAULT '30',
  `servicio_id` bigint UNSIGNED NOT NULL,
  `medicamento_id` bigint UNSIGNED NOT NULL,
  `creado_por` bigint UNSIGNED DEFAULT NULL,
  `modificado_por` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `lote`, `fecha_vencimiento`, `cantidad_act`, `umbral_aviso`, `umbral_critico`, `servicio_id`, `medicamento_id`, `creado_por`, `modificado_por`, `created_at`, `updated_at`) VALUES
(1, 'L-998877', '2028-12-31', 120, 50, 30, 4, 1, 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'L-112233', '2027-06-30', 45, 50, 30, 5, 2, 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'L-445566', '2028-09-15', 85, 50, 30, 4, 3, 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'L-778899', '2027-11-20', 30, 50, 30, 5, 4, 1, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'L-223344', '2028-05-10', 500, 50, 30, 4, 6, 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(6, 'L-556677', '2027-02-15', 25, 50, 30, 5, 7, 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(7, 'L-889900', '2027-09-20', 60, 50, 30, 5, 8, 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(8, 'L-112244', '2029-01-15', 800, 50, 30, 4, 9, 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(9, 'L-334455', '2028-03-30', 150, 50, 30, 4, 10, 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(10, 'L-778811', '2027-08-12', 110, 50, 30, 5, 12, 1, NULL, '2026-05-20 15:28:42', '2026-05-20 15:28:42'),
(14, '1A', '2030-12-16', 993, 100, 50, 6, 23, NULL, NULL, '2026-07-16 08:05:05', '2026-08-16 01:18:38'),
(15, '1B', '2030-12-16', 981, 200, 100, 6, 21, NULL, NULL, '2026-07-16 08:05:53', '2026-08-16 01:18:38'),
(16, '1C', '2030-10-16', 990, 150, 40, 6, 22, NULL, NULL, '2026-07-16 08:06:33', '2026-08-16 01:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_anestesias`
--

CREATE TABLE `tipo_anestesias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipo_anestesias`
--

INSERT INTO `tipo_anestesias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'General', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Inhalatoria', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Epidural', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Local', '2026-05-20 15:24:56', '2026-05-20 15:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` bigint UNSIGNED DEFAULT NULL,
  `servicio_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `servicio_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@medstats.com', NULL, '$2y$12$SEIU/RTK2uyJt6g/eYY.HOqhexU.5wXpa/TQXpP.SGkE9VjJ/h2mC', 1, NULL, 'NhNxD0DxC8gb2nblZMyoyuicQxGXyR8H1Fvx8JXcoGCxeSHZoWoJQ8RTt1oD', '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Dr. Juan Pérez', 'juan@medstats.com', NULL, './nk6HGRjedSpCz.c5BmegJa1BKm.y7auDUkJZQAcXngQrq', 2, 5, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Enf. María Gómez', 'maria@medstats.com', NULL, './nk6HGRjedSpCz.c5BmegJa1BKm.y7auDUkJZQAcXngQrq', 3, 4, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Farmacia Insumos', 'insumos@medstats.com', NULL, './nk6HGRjedSpCz.c5BmegJa1BKm.y7auDUkJZQAcXngQrq', 4, 4, NULL, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'test', 'test@hotmail.com', NULL, '$2y$12$SEIU/RTK2uyJt6g/eYY.HOqhexU.5wXpa/TQXpP.SGkE9VjJ/h2mC', NULL, NULL, NULL, '2026-06-19 00:09:08', '2026-06-19 00:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `usuario_perfils`
--

CREATE TABLE `usuario_perfils` (
  `id` bigint UNSIGNED NOT NULL,
  `perfil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `insumos` tinyint(1) NOT NULL DEFAULT '0',
  `estadisticas` tinyint(1) NOT NULL DEFAULT '0',
  `pacientes` tinyint(1) NOT NULL DEFAULT '0',
  `camas` tinyint(1) NOT NULL DEFAULT '0',
  `cirugias` tinyint(1) NOT NULL DEFAULT '0',
  `estudios_medicos` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuario_perfils`
--

INSERT INTO `usuario_perfils` (`id`, `perfil`, `admin`, `insumos`, `estadisticas`, `pacientes`, `camas`, `cirugias`, `estudios_medicos`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 1, 1, 1, 1, 1, 1, 1, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(2, 'Médico', 0, 0, 1, 1, 1, 1, 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(3, 'Enfermero', 0, 0, 0, 1, 1, 0, 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(4, 'Insumos', 0, 1, 1, 0, 0, 0, 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56'),
(5, 'Estadísticas', 0, 0, 1, 0, 0, 0, 0, '2026-05-20 15:24:56', '2026-05-20 15:24:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `camas`
--
ALTER TABLE `camas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `camas_habitacion_id_foreign` (`habitacion_id`);

--
-- Indexes for table `cirugias`
--
ALTER TABLE `cirugias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cirugias_paciente_id_foreign` (`paciente_id`),
  ADD KEY `cirugias_especialidad_id_foreign` (`especialidad_id`),
  ADD KEY `cirugias_procedimiento_id_foreign` (`procedimiento_id`),
  ADD KEY `cirugias_procedimiento_2_id_foreign` (`procedimiento_2_id`),
  ADD KEY `cirugias_quirofano_id_foreign` (`quirofano_id`),
  ADD KEY `cirugias_cirujano_id_foreign` (`cirujano_id`),
  ADD KEY `cirugias_ayudante_1_id_foreign` (`ayudante_1_id`),
  ADD KEY `cirugias_ayudante_2_id_foreign` (`ayudante_2_id`),
  ADD KEY `cirugias_ayudante_3_id_foreign` (`ayudante_3_id`),
  ADD KEY `cirugias_anestesista_id_foreign` (`anestesista_id`),
  ADD KEY `cirugias_tipo_anestesia_id_foreign` (`tipo_anestesia_id`),
  ADD KEY `cirugias_tipo_anestesia_2_id_foreign` (`tipo_anestesia_2_id`),
  ADD KEY `cirugias_instrumentador_id_foreign` (`instrumentador_id`),
  ADD KEY `cirugias_instrumentador_2_id_foreign` (`instrumentador_2_id`),
  ADD KEY `cirugias_enfermero_id_foreign` (`enfermero_id`),
  ADD KEY `cirugias_enfermero_2_id_foreign` (`enfermero_2_id`);

--
-- Indexes for table `codigo_postals`
--
ALTER TABLE `codigo_postals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `codigo_postals_pais_id_foreign` (`pais_id`),
  ADD KEY `codigo_postals_provincia_id_foreign` (`provincia_id`);

--
-- Indexes for table `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleados_pais_id_foreign` (`pais_id`),
  ADD KEY `empleados_provincia_id_foreign` (`provincia_id`),
  ADD KEY `empleados_cod_postal_id_foreign` (`cod_postal_id`),
  ADD KEY `empleados_profesion_id_foreign` (`profesion_id`);

--
-- Indexes for table `especialidads`
--
ALTER TABLE `especialidads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estudios`
--
ALTER TABLE `estudios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estudios_especialidad_id_nombre_unique` (`especialidad_id`,`nombre`);

--
-- Indexes for table `estudio_medicos`
--
ALTER TABLE `estudio_medicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudio_medicos_paciente_id_foreign` (`paciente_id`),
  ADD KEY `estudio_medicos_medico_solicitante_id_foreign` (`medico_solicitante_id`),
  ADD KEY `estudio_medicos_especialidad_id_foreign` (`especialidad_id`),
  ADD KEY `estudio_medicos_estudio_id_foreign` (`estudio_id`);

--
-- Indexes for table `habitacions`
--
ALTER TABLE `habitacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitacions_sala_id_foreign` (`sala_id`);

--
-- Indexes for table `historial_stocks`
--
ALTER TABLE `historial_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_stocks_stock_id_foreign` (`stock_id`),
  ADD KEY `historial_stocks_empleado_id_foreign` (`empleado_id`),
  ADD KEY `historial_stocks_paciente_id_foreign` (`paciente_id`),
  ADD KEY `historial_stocks_estudio_medico_id_foreign` (`estudio_medico_id`);

--
-- Indexes for table `imagenes_estudio`
--
ALTER TABLE `imagenes_estudio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imagenes_estudio_estudio_medico_id_foreign` (`estudio_medico_id`);

--
-- Indexes for table `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocupacion_camas`
--
ALTER TABLE `ocupacion_camas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ocupacion_camas_cama_id_foreign` (`cama_id`),
  ADD KEY `ocupacion_camas_paciente_id_foreign` (`paciente_id`);

--
-- Indexes for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pacientes_pais_id_foreign` (`pais_id`),
  ADD KEY `pacientes_provincia_id_foreign` (`provincia_id`),
  ADD KEY `pacientes_cod_postal_id_foreign` (`cod_postal_id`),
  ADD KEY `pacientes_cama_id_foreign` (`cama_id`),
  ADD KEY `pacientes_habitacion_id_foreign` (`habitacion_id`);

--
-- Indexes for table `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procedimientos_especialidad_id_foreign` (`especialidad_id`),
  ADD KEY `procedimientos_especialidad_2_id_foreign` (`especialidad_2_id`);

--
-- Indexes for table `profesions`
--
ALTER TABLE `profesions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesions_rol_id_foreign` (`rol_id`);

--
-- Indexes for table `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provincias_pais_id_foreign` (`pais_id`);

--
-- Indexes for table `quirofanos`
--
ALTER TABLE `quirofanos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rol_profesions`
--
ALTER TABLE `rol_profesions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_servicio_id_foreign` (`servicio_id`),
  ADD KEY `stocks_medicamento_id_foreign` (`medicamento_id`);

--
-- Indexes for table `tipo_anestesias`
--
ALTER TABLE `tipo_anestesias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_foreign` (`role`),
  ADD KEY `users_servicio_id_foreign` (`servicio_id`);

--
-- Indexes for table `usuario_perfils`
--
ALTER TABLE `usuario_perfils`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `camas`
--
ALTER TABLE `camas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cirugias`
--
ALTER TABLE `cirugias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `codigo_postals`
--
ALTER TABLE `codigo_postals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `especialidads`
--
ALTER TABLE `especialidads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `estudios`
--
ALTER TABLE `estudios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `estudio_medicos`
--
ALTER TABLE `estudio_medicos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `habitacions`
--
ALTER TABLE `habitacions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `historial_stocks`
--
ALTER TABLE `historial_stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `imagenes_estudio`
--
ALTER TABLE `imagenes_estudio`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `ocupacion_camas`
--
ALTER TABLE `ocupacion_camas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pais`
--
ALTER TABLE `pais`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `procedimientos`
--
ALTER TABLE `procedimientos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `profesions`
--
ALTER TABLE `profesions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `provincias`
--
ALTER TABLE `provincias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quirofanos`
--
ALTER TABLE `quirofanos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rol_profesions`
--
ALTER TABLE `rol_profesions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salas`
--
ALTER TABLE `salas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tipo_anestesias`
--
ALTER TABLE `tipo_anestesias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuario_perfils`
--
ALTER TABLE `usuario_perfils`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `camas`
--
ALTER TABLE `camas`
  ADD CONSTRAINT `camas_habitacion_id_foreign` FOREIGN KEY (`habitacion_id`) REFERENCES `habitacions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cirugias`
--
ALTER TABLE `cirugias`
  ADD CONSTRAINT `cirugias_anestesista_id_foreign` FOREIGN KEY (`anestesista_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_ayudante_1_id_foreign` FOREIGN KEY (`ayudante_1_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_ayudante_2_id_foreign` FOREIGN KEY (`ayudante_2_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_ayudante_3_id_foreign` FOREIGN KEY (`ayudante_3_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_cirujano_id_foreign` FOREIGN KEY (`cirujano_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cirugias_enfermero_2_id_foreign` FOREIGN KEY (`enfermero_2_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_enfermero_id_foreign` FOREIGN KEY (`enfermero_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cirugias_instrumentador_2_id_foreign` FOREIGN KEY (`instrumentador_2_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_instrumentador_id_foreign` FOREIGN KEY (`instrumentador_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cirugias_procedimiento_2_id_foreign` FOREIGN KEY (`procedimiento_2_id`) REFERENCES `procedimientos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_procedimiento_id_foreign` FOREIGN KEY (`procedimiento_id`) REFERENCES `procedimientos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cirugias_quirofano_id_foreign` FOREIGN KEY (`quirofano_id`) REFERENCES `quirofanos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cirugias_tipo_anestesia_2_id_foreign` FOREIGN KEY (`tipo_anestesia_2_id`) REFERENCES `tipo_anestesias` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cirugias_tipo_anestesia_id_foreign` FOREIGN KEY (`tipo_anestesia_id`) REFERENCES `tipo_anestesias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `codigo_postals`
--
ALTER TABLE `codigo_postals`
  ADD CONSTRAINT `codigo_postals_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `codigo_postals_provincia_id_foreign` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `empleados_cod_postal_id_foreign` FOREIGN KEY (`cod_postal_id`) REFERENCES `codigo_postals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `empleados_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `empleados_profesion_id_foreign` FOREIGN KEY (`profesion_id`) REFERENCES `profesions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `empleados_provincia_id_foreign` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estudios`
--
ALTER TABLE `estudios`
  ADD CONSTRAINT `estudios_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estudio_medicos`
--
ALTER TABLE `estudio_medicos`
  ADD CONSTRAINT `estudio_medicos_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `estudio_medicos_estudio_id_foreign` FOREIGN KEY (`estudio_id`) REFERENCES `estudios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `estudio_medicos_medico_solicitante_id_foreign` FOREIGN KEY (`medico_solicitante_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estudio_medicos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `habitacions`
--
ALTER TABLE `habitacions`
  ADD CONSTRAINT `habitacions_sala_id_foreign` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `historial_stocks`
--
ALTER TABLE `historial_stocks`
  ADD CONSTRAINT `historial_stocks_empleado_id_foreign` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_stocks_estudio_medico_id_foreign` FOREIGN KEY (`estudio_medico_id`) REFERENCES `estudio_medicos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `historial_stocks_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `historial_stocks_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `imagenes_estudio`
--
ALTER TABLE `imagenes_estudio`
  ADD CONSTRAINT `imagenes_estudio_estudio_medico_id_foreign` FOREIGN KEY (`estudio_medico_id`) REFERENCES `estudio_medicos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ocupacion_camas`
--
ALTER TABLE `ocupacion_camas`
  ADD CONSTRAINT `ocupacion_camas_cama_id_foreign` FOREIGN KEY (`cama_id`) REFERENCES `camas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ocupacion_camas_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_cama_id_foreign` FOREIGN KEY (`cama_id`) REFERENCES `camas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_cod_postal_id_foreign` FOREIGN KEY (`cod_postal_id`) REFERENCES `codigo_postals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pacientes_habitacion_id_foreign` FOREIGN KEY (`habitacion_id`) REFERENCES `habitacions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pacientes_provincia_id_foreign` FOREIGN KEY (`provincia_id`) REFERENCES `provincias` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `procedimientos`
--
ALTER TABLE `procedimientos`
  ADD CONSTRAINT `procedimientos_especialidad_2_id_foreign` FOREIGN KEY (`especialidad_2_id`) REFERENCES `especialidads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `procedimientos_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profesions`
--
ALTER TABLE `profesions`
  ADD CONSTRAINT `profesions_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rol_profesions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provincias`
--
ALTER TABLE `provincias`
  ADD CONSTRAINT `provincias_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `pais` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_medicamento_id_foreign` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamentos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_foreign` FOREIGN KEY (`role`) REFERENCES `usuario_perfils` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
