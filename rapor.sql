-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2026 at 08:56 AM
-- Server version: 11.4.7-MariaDB-0ubuntu0.25.04.1
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rapor`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2025/2026', 1, '2026-11-04 05:37:04', '2026-11-04 05:37:04'),
(2, '2026/2027', 1, '2026-11-07 03:40:58', '2026-11-07 03:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

CREATE TABLE `classrooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` varchar(255) DEFAULT NULL,
  `homeroom_teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `school_level` varchar(10) NOT NULL DEFAULT 'SD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `name`, `level`, `homeroom_teacher_id`, `created_at`, `updated_at`, `school_level`) VALUES
(1, '10 IPA', '10', 6, '2026-11-04 05:20:15', '2026-11-04 06:47:25', 'SMA'),
(2, '11 IPA', '11', 3, '2026-11-04 05:20:49', '2026-11-04 05:20:49', 'SMA'),
(3, '12 IPA', '12', 2, '2026-11-04 05:21:00', '2026-11-04 05:21:00', 'SMA'),
(4, '10 IPS', '10', 4, '2026-11-04 05:39:21', '2026-11-04 06:47:16', 'SMA'),
(5, '11 IPS', '11', 8, '2026-11-04 05:39:34', '2026-11-04 06:47:35', 'SMA'),
(6, '12 IPS', '12', 10, '2026-11-04 05:39:43', '2026-11-04 06:47:46', 'SMA'),
(8, 'Kelas 7 SMP', '7', NULL, '2026-11-08 05:22:13', '2026-11-08 05:25:04', 'SMP'),
(9, 'Kelas 8 SMP', '8', NULL, '2026-11-08 05:22:28', '2026-11-08 05:24:50', 'SMP'),
(10, 'Kelas 9 SMP', '9', NULL, '2026-11-08 05:22:38', '2026-11-08 05:24:31', 'SMP'),
(11, 'Kelas 1 SD', '1', NULL, '2026-11-08 05:22:48', '2026-11-08 05:24:00', 'SD'),
(12, 'Kelas 2 SD', '2', NULL, '2026-11-08 05:22:54', '2026-11-08 05:24:16', 'SD'),
(13, 'Kelas 3 SD', '3', NULL, '2026-11-08 05:22:59', '2026-11-08 05:25:50', 'SD'),
(14, 'Kelas 4 SD', '4', NULL, '2026-11-08 05:23:04', '2026-11-08 05:26:10', 'SD'),
(15, 'Kelas 5 SD', '5', NULL, '2026-11-08 05:23:10', '2026-11-08 05:25:35', 'SD'),
(16, 'Kelas 6 SD', '6', NULL, '2026-11-08 05:23:16', '2026-11-08 05:25:21', 'SD');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `tugas_score` decimal(5,2) DEFAULT NULL,
  `uts_score` decimal(5,2) DEFAULT NULL,
  `uas_score` decimal(5,2) DEFAULT NULL,
  `tugas_weight` decimal(5,2) NOT NULL DEFAULT 30.00,
  `uts_weight` decimal(5,2) NOT NULL DEFAULT 30.00,
  `uas_weight` decimal(5,2) NOT NULL DEFAULT 40.00,
  `knowledge_score` decimal(5,2) NOT NULL,
  `skill_score` decimal(5,2) NOT NULL,
  `final_score` decimal(5,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('draft','submitted','revision','approved','published') NOT NULL DEFAULT 'draft',
  `teacher_note` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_weights`
--

CREATE TABLE `grade_weights` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `school_level` varchar(10) NOT NULL DEFAULT 'SD',
  `tugas_weight` decimal(5,2) NOT NULL DEFAULT 30.00,
  `uts_weight` decimal(5,2) NOT NULL DEFAULT 30.00,
  `uas_weight` decimal(5,2) NOT NULL DEFAULT 40.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(9, '0001_01_01_000000_create_users_table', 1),
(10, '0001_01_01_000001_create_cache_table', 1),
(11, '0001_01_01_000002_create_jobs_table', 1),
(12, '2026_01_01_000001_create_school_master_tables', 1),
(13, '2026_01_01_000002_create_grades_table', 1),
(14, '2026_11_03_000001_add_assessment_components_and_weights', 1),
(15, '2026_11_04_000001_create_teacher_assignments_and_report_cards', 1),
(16, '2026_11_04_000002_add_profile_fields_to_users_table', 1),
(17, '2026_11_05_000001_add_avatar_path_to_users_table', 2),
(18, '2026_11_07_000001_add_school_levels_and_super_admin', 3),
(19, '2026_11_07_000002_create_dynamic_task_components', 3),
(20, '2026_11_07_000003_scope_grade_weights_by_school', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_cards`
--

CREATE TABLE `report_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `skill_score` decimal(5,2) DEFAULT NULL,
  `attitude_score` decimal(5,2) DEFAULT NULL,
  `motivation` text DEFAULT NULL,
  `suggestion` text DEFAULT NULL,
  `homeroom_teacher_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('draft','submitted','revision','approved') NOT NULL DEFAULT 'draft',
  `admin_note` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `name`, `academic_year_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ganjil', 1, 1, '2026-11-04 05:37:15', '2026-11-04 05:37:15'),
(2, 'Ganjil', 2, 1, '2026-11-07 03:40:58', '2026-11-07 03:40:58'),
(3, 'Genap', 2, 0, '2026-11-07 03:40:58', '2026-11-07 03:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('kXO66xlwWWmWToylfk4WcLESwEdhBdurneMDI6ta', NULL, '192.168.3.7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjZHUkxoVDc3aFljQmFjQWNpdFo1azV2NlY3SWtvMWxlTjZOS1FORCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xOTIuMTY4LjE1LjE1L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==', 1794992136);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'BAHASA INDONESIA', 'BIND', NULL, '2026-11-04 05:21:28', '2026-11-04 05:21:28'),
(2, 'BAHASA INGGRIS', 'BING', NULL, '2026-11-04 05:21:42', '2026-11-04 05:21:42'),
(3, 'BAHASA JAWA', 'BJAW', NULL, '2026-11-04 05:29:00', '2026-11-04 05:29:00'),
(4, 'PENDIDIKAN AGAMA ISLAM', 'PAIM', NULL, '2026-11-04 05:34:55', '2026-11-04 05:35:47'),
(5, 'MATEMATIKA', 'MTKA', NULL, '2026-11-04 05:35:08', '2026-11-04 05:36:01'),
(6, 'FISIKA', 'FSKA', NULL, '2026-11-04 05:35:22', '2026-11-04 05:35:22'),
(7, 'KIMIA', 'KMIA', NULL, '2026-11-04 05:35:34', '2026-11-04 05:35:34'),
(8, 'BIOLOGI', 'BILG', NULL, '2026-11-04 05:40:26', '2026-11-04 05:40:26');

-- --------------------------------------------------------

--
-- Table structure for table `task_components`
--

CREATE TABLE `task_components` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_components`
--

INSERT INTO `task_components` (`id`, `classroom_id`, `subject_id`, `academic_year_id`, `semester_id`, `name`, `position`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1, 1, 'Tugas 1', 1, 4, '2026-11-07 03:40:55', '2026-11-07 03:40:55'),
(2, 1, 2, 1, 1, 'Tugas 1', 1, 2, '2026-11-07 03:40:55', '2026-11-07 03:40:55'),
(3, 3, 2, 1, 1, 'Tugas 1', 1, 2, '2026-11-07 03:40:55', '2026-11-07 03:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `task_scores`
--

CREATE TABLE `task_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_component_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_scores`
--

INSERT INTO `task_scores` (`id`, `task_component_id`, `student_id`, `score`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 100.00, '2026-11-07 03:40:55', '2026-11-07 03:40:55'),
(2, 2, 23, 90.00, '2026-11-07 03:40:55', '2026-11-07 03:40:55'),
(3, 3, 83, 90.00, '2026-11-07 03:40:55', '2026-11-07 03:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'siswa',
  `classroom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nis` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `residence` text DEFAULT NULL,
  `previous_school` varchar(180) DEFAULT NULL,
  `parent_name` varchar(150) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `school_level` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `classroom_id`, `nip`, `nis`, `remember_token`, `created_at`, `updated_at`, `phone`, `residence`, `previous_school`, `parent_name`, `avatar_path`, `school_level`) VALUES
(1, 'Ketua Yayasan', 'yayasan', NULL, '$2y$12$QCTQpfH27dDAgOxwGlUoB.SpGfxdESKSVXM7cFJPepLem.E2dZW1q', 'super_admin', NULL, NULL, NULL, NULL, NULL, '2026-11-08 06:28:55', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'kepala sekolah SD', 'kepalasd', NULL, '$2y$12$9ZUtt1DlSILD7pQuDdVC..F3RilmAsq7aouqm8Get39evDkoHpSSG', 'admin', NULL, NULL, NULL, NULL, '2026-11-18 15:52:30', '2026-11-18 15:53:03', NULL, NULL, NULL, NULL, NULL, 'SD'),
(3, 'kepala sekolah SMA', 'kepalasma', NULL, '$2y$12$Z4cDtYmO5XuJOvgoQiS5XuJjcD/ub12qDCdvRMM.Inl6mjmBeTkjG', 'admin', NULL, NULL, NULL, NULL, '2026-11-18 15:52:54', '2026-11-18 15:53:59', NULL, NULL, NULL, NULL, NULL, 'SMA'),
(4, 'kepala sekolah smp', 'kepalasmp', NULL, '$2y$12$rhkqpS3V78AKJ6dksOjceu.lnTVx.pengNufz4VvsUPRlscMX7nAe', 'admin', NULL, NULL, NULL, NULL, '2026-11-18 15:54:25', '2026-11-18 15:54:25', NULL, NULL, NULL, NULL, NULL, 'SMP');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_years_name_unique` (`name`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `classrooms_name_unique` (`name`),
  ADD KEY `classrooms_homeroom_teacher_id_foreign` (`homeroom_teacher_id`),
  ADD KEY `classrooms_school_level_index` (`school_level`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grade_student_subject_period_unique` (`student_id`,`subject_id`,`academic_year_id`,`semester_id`),
  ADD KEY `grades_teacher_id_foreign` (`teacher_id`),
  ADD KEY `grades_classroom_id_foreign` (`classroom_id`),
  ADD KEY `grades_subject_id_foreign` (`subject_id`),
  ADD KEY `grades_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `grades_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `grade_weights`
--
ALTER TABLE `grade_weights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grade_weights_school_period_unique` (`academic_year_id`,`semester_id`,`school_level`),
  ADD KEY `grade_weights_academic_year_fk_idx` (`academic_year_id`),
  ADD KEY `grade_weights_semester_fk_idx` (`semester_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `report_cards`
--
ALTER TABLE `report_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_card_student_period_unique` (`student_id`,`academic_year_id`,`semester_id`),
  ADD KEY `report_cards_classroom_id_foreign` (`classroom_id`),
  ADD KEY `report_cards_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `report_cards_semester_id_foreign` (`semester_id`),
  ADD KEY `report_cards_homeroom_teacher_id_foreign` (`homeroom_teacher_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semesters_name_academic_year_id_unique` (`name`,`academic_year_id`),
  ADD KEY `semesters_academic_year_id_foreign` (`academic_year_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_code_unique` (`code`);

--
-- Indexes for table `task_components`
--
ALTER TABLE `task_components`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_component_position_unique` (`classroom_id`,`subject_id`,`academic_year_id`,`semester_id`,`position`),
  ADD KEY `task_components_subject_id_foreign` (`subject_id`),
  ADD KEY `task_components_academic_year_id_foreign` (`academic_year_id`),
  ADD KEY `task_components_semester_id_foreign` (`semester_id`),
  ADD KEY `task_components_created_by_foreign` (`created_by`);

--
-- Indexes for table `task_scores`
--
ALTER TABLE `task_scores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_score_unique` (`task_component_id`,`student_id`),
  ADD KEY `task_scores_student_id_foreign` (`student_id`);

--
-- Indexes for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_class_subject_unique` (`teacher_id`,`classroom_id`,`subject_id`),
  ADD KEY `teacher_assignments_classroom_id_foreign` (`classroom_id`),
  ADD KEY `teacher_assignments_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_classroom_id_foreign` (`classroom_id`),
  ADD KEY `users_school_level_index` (`school_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grade_weights`
--
ALTER TABLE `grade_weights`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `report_cards`
--
ALTER TABLE `report_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `task_components`
--
ALTER TABLE `task_components`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `task_scores`
--
ALTER TABLE `task_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
