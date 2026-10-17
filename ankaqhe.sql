-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2026 a las 02:24:36
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
-- Base de datos: `ankaqhe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'activo',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `titulo`, `categoria`, `descripcion`, `imagen`, `estado`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'AEA', '123', 'dawdwad', 'img/cursos/1792195863_AbsoluteMeow.jpg', 'activo', 1, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(2, 'poto', '111', 'adwada', 'img/cursos/1792196556_images1.jpeg', 'activo', 1, '2026-10-17 05:22:36', '2026-10-17 05:22:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso_user`
--

CREATE TABLE `curso_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `progreso` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `curso_user`
--

INSERT INTO `curso_user` (`id`, `user_id`, `curso_id`, `progreso`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50, '2026-10-17 05:11:08', '2026-10-17 05:11:50'),
(2, 1, 2, 100, '2026-10-17 05:22:43', '2026-10-17 05:23:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `nota_minima` int(11) NOT NULL DEFAULT 14,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id`, `curso_id`, `titulo`, `nota_minima`, `created_at`, `updated_at`) VALUES
(1, 1, 'ad', 14, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(2, 2, 'wadwdwadaw', 14, '2026-10-17 05:22:36', '2026-10-17 05:22:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos_examen`
--

CREATE TABLE `intentos_examen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `examen_id` bigint(20) UNSIGNED NOT NULL,
  `puntaje` int(11) NOT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT 0,
  `completado_el` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intentos_examen`
--

INSERT INTO `intentos_examen` (`id`, `user_id`, `examen_id`, `puntaje`, `aprobado`, `completado_el`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 20, 1, '2026-10-17 05:23:24', '2026-10-17 05:23:24', '2026-10-17 05:23:24'),
(2, 1, 2, 20, 1, '2026-10-17 05:23:30', '2026-10-17 05:23:30', '2026-10-17 05:23:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lecciones`
--

CREATE TABLE `lecciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modulo_id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leccion_usuario`
--

CREATE TABLE `leccion_usuario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `leccion_id` bigint(20) UNSIGNED NOT NULL,
  `completado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_15_231240_create_cursos_table', 1),
(5, '2026_10_15_231241_create_modulos_table', 1),
(6, '2026_10_15_231242_create_examenes_table', 1),
(7, '2026_10_15_231242_create_lecciones_table', 1),
(8, '2026_10_15_231242_create_preguntas_table', 1),
(9, '2026_10_15_231242_create_respuestas_table', 1),
(10, '2026_10_15_231243_create_intentos_examen_table', 1),
(11, '2026_10_15_231243_create_leccion_usuario_table', 1),
(12, '2026_10_16_212029_add_video_to_modulos_table', 1),
(13, '2026_10_16_223805_create_modulo_usuario_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curso_id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `curso_id`, `titulo`, `video`, `orden`, `created_at`, `updated_at`) VALUES
(1, 1, 'adwad', 'videos/cursos/1792195863_m1_TikVid.io7257727521834274090-hd.mp4', 1, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(2, 2, 'dawdadwad', 'videos/cursos/1792196556_m1_Download1.mp4', 1, '2026-10-17 05:22:36', '2026-10-17 05:22:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_usuario`
--

CREATE TABLE `modulo_usuario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `modulo_id` bigint(20) UNSIGNED NOT NULL,
  `completado_el` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulo_usuario`
--

INSERT INTO `modulo_usuario` (`id`, `user_id`, `modulo_id`, `completado_el`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-10-17 05:11:50', '2026-10-17 05:11:50', '2026-10-17 05:11:50'),
(2, 1, 2, '2026-10-17 05:23:17', '2026-10-17 05:23:17', '2026-10-17 05:23:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `examen_id` bigint(20) UNSIGNED NOT NULL,
  `pregunta` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `examen_id`, `pregunta`, `created_at`, `updated_at`) VALUES
(1, 1, 'dwad', '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(2, 2, 'pinga', '2026-10-17 05:22:36', '2026-10-17 05:22:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pregunta_id` bigint(20) UNSIGNED NOT NULL,
  `respuesta` varchar(255) NOT NULL,
  `es_correcta` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id`, `pregunta_id`, `respuesta`, `es_correcta`, `created_at`, `updated_at`) VALUES
(1, 1, 'ea', 1, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(2, 1, 'wda', 0, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(3, 1, 'wda', 0, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(4, 1, 'wad', 0, '2026-10-17 05:11:03', '2026-10-17 05:11:03'),
(5, 2, 'este', 1, '2026-10-17 05:22:36', '2026-10-17 05:22:36'),
(6, 2, 'dawdadwa', 0, '2026-10-17 05:22:36', '2026-10-17 05:22:36'),
(7, 2, 'o estaaaa', 0, '2026-10-17 05:22:36', '2026-10-17 05:22:36'),
(8, 2, 'pinga', 0, '2026-10-17 05:22:36', '2026-10-17 05:22:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('joro3iSe4aGSfXGYXPWmt5bhVITHaMfTNjbx7lHt', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidzlreXY5aEJBMVVLZnJ1RXBmdFc0aEFscGpDVDY3WW9LVTJpdWJHZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXJzb3MvMj9leGFtZW49MSI7czo1OiJyb3V0ZSI7czoxMjoiYWx1bW5vLmN1cnNvIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiZjkyMmM0NDBlYmE2ODgzNmM2YmNmYTE2MzEyOGZmYWYwYmEyMmZkOGRiOTU2NWFiNWRlZmZiNzgzOTQ0NmE2NSI7fQ==', 1792196611);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'alumno',
  `cargo` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `cargo`, `area`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ana Martinez', 'ana@anka.com', NULL, '$2y$12$EkJvfoKHJhKXcBMp5fluTeiI05sV383wTuU076FoB9mWBq5ygHRr6', 'alumno', NULL, NULL, NULL, '2026-10-16 23:58:26', '2026-10-16 23:58:26'),
(2, 'Administrador', 'admin@anka.com', NULL, '$2y$12$EkJvfoKHJhKXcBMp5fluTeiI05sV383wTuU076FoB9mWBq5ygHRr6', 'admin', NULL, NULL, NULL, '2026-10-16 23:58:26', '2026-10-16 23:58:26'),
(3, 'Operador Principal', 'operador@anka.com', NULL, '$2y$12$EkJvfoKHJhKXcBMp5fluTeiI05sV383wTuU076FoB9mWBq5ygHRr6', 'operador', NULL, NULL, NULL, '2026-10-16 23:58:26', '2026-10-16 23:58:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `curso_user`
--
ALTER TABLE `curso_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curso_user_user_id_curso_id_unique` (`user_id`,`curso_id`),
  ADD KEY `fk_curso_user_curso` (`curso_id`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examenes_curso_id_foreign` (`curso_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `intentos_examen`
--
ALTER TABLE `intentos_examen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intentos_examen_user_id_foreign` (`user_id`),
  ADD KEY `intentos_examen_examen_id_foreign` (`examen_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lecciones`
--
ALTER TABLE `lecciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecciones_modulo_id_foreign` (`modulo_id`);

--
-- Indices de la tabla `leccion_usuario`
--
ALTER TABLE `leccion_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leccion_usuario_user_id_foreign` (`user_id`),
  ADD KEY `leccion_usuario_leccion_id_foreign` (`leccion_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modulos_curso_id_foreign` (`curso_id`);

--
-- Indices de la tabla `modulo_usuario`
--
ALTER TABLE `modulo_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modulo_usuario_user_id_modulo_id_unique` (`user_id`,`modulo_id`),
  ADD KEY `modulo_usuario_modulo_id_foreign` (`modulo_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `preguntas_examen_id_foreign` (`examen_id`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `respuestas_pregunta_id_foreign` (`pregunta_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `curso_user`
--
ALTER TABLE `curso_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intentos_examen`
--
ALTER TABLE `intentos_examen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lecciones`
--
ALTER TABLE `lecciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `leccion_usuario`
--
ALTER TABLE `leccion_usuario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `modulo_usuario`
--
ALTER TABLE `modulo_usuario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `curso_user`
--
ALTER TABLE `curso_user`
  ADD CONSTRAINT `fk_curso_user_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_curso_user_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `intentos_examen`
--
ALTER TABLE `intentos_examen`
  ADD CONSTRAINT `intentos_examen_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `intentos_examen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lecciones`
--
ALTER TABLE `lecciones`
  ADD CONSTRAINT `lecciones_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `leccion_usuario`
--
ALTER TABLE `leccion_usuario`
  ADD CONSTRAINT `leccion_usuario_leccion_id_foreign` FOREIGN KEY (`leccion_id`) REFERENCES `lecciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leccion_usuario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modulo_usuario`
--
ALTER TABLE `modulo_usuario`
  ADD CONSTRAINT `modulo_usuario_modulo_id_foreign` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `modulo_usuario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_examen_id_foreign` FOREIGN KEY (`examen_id`) REFERENCES `examenes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
