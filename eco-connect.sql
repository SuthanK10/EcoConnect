-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2026 at 07:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eco-connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ngo_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `media_path` varchar(255) DEFAULT NULL,
  `media_type` enum('image','video') DEFAULT 'image',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `ngo_id`, `project_id`, `content`, `status`, `media_path`, `media_type`, `created_at`) VALUES
(1, 11, NULL, NULL, 'Today in my first ever cleanup drive!!!', 'approved', 'uploads/gallery/1766654106_694d009a2322d.jpg', 'image', '2025-12-25 09:15:06'),
(2, 8, 1, NULL, 'Our first successful drive with the community❤️', 'approved', 'uploads/gallery/1766654210_694d0102923e1.jpg', 'image', '2025-12-25 09:16:50'),
(3, 13, NULL, NULL, 'Hello, this is a test 123', 'rejected', 'uploads/gallery/1768657679_696b930ff226b.jpg', 'image', '2026-01-17 13:47:59'),
(4, 8, 1, NULL, 'Our Second Cleanup Drive', 'approved', NULL, 'image', '2026-01-18 11:56:27');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_media`
--

CREATE TABLE `community_post_media` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `media_type` enum('image','video') DEFAULT 'image',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_post_media`
--

INSERT INTO `community_post_media` (`id`, `post_id`, `media_path`, `media_type`, `created_at`) VALUES
(1, 1, 'uploads/gallery/1766654106_694d009a2322d.jpg', 'image', '2026-01-18 11:52:09'),
(2, 2, 'uploads/gallery/1766654210_694d0102923e1.jpg', 'image', '2026-01-18 11:52:09'),
(3, 3, 'uploads/gallery/1768657679_696b930ff226b.jpg', 'image', '2026-01-18 11:52:09'),
(4, 4, 'uploads/gallery/1768737387_696cca6b61416.png', 'image', '2026-01-18 11:56:27'),
(5, 4, 'uploads/gallery/1768737387_696cca6b61e10.png', 'image', '2026-01-18 11:56:27'),
(6, 4, 'uploads/gallery/1768737387_696cca6b6240b.jpg', 'image', '2026-01-18 11:56:27');

-- --------------------------------------------------------

--
-- Table structure for table `community_proposals`
--

CREATE TABLE `community_proposals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `proposed_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','converted') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_proposals`
--

INSERT INTO `community_proposals` (`id`, `user_id`, `title`, `description`, `location`, `latitude`, `longitude`, `proposed_date`, `status`, `created_at`) VALUES
(1, 11, 'Freedom Lane Drive', 'this lane buns bruh, come clean it asap smh. stop pmo', 'Freedom Lane', 6.93698560, 79.86544640, '2025-12-28', 'converted', '2025-12-25 08:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `deleted_accounts`
--

CREATE TABLE `deleted_accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'LKR',
  `stripe_session_id` varchar(255) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drive_attendance`
--

CREATE TABLE `drive_attendance` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `points_earned` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drive_attendance`
--

INSERT INTO `drive_attendance` (`id`, `project_id`, `user_id`, `check_in_time`, `check_out_time`, `points_earned`, `created_at`) VALUES
(1, 1, 7, '2025-12-24 20:10:56', '2025-12-24 20:11:33', 10, '2025-12-24 14:40:56'),
(3, 2, 7, '2025-12-24 20:27:15', '2025-12-24 20:27:56', 10, '2025-12-24 14:57:15'),
(4, 2, 10, '2025-12-24 23:04:28', '2025-12-24 23:04:41', 10, '2025-12-24 17:34:28'),
(5, 1, 11, '2025-12-25 00:28:33', '2025-12-25 00:28:44', 10, '2025-12-24 18:58:33'),
(6, 2, 11, '2025-12-25 00:29:07', '2025-12-25 00:29:24', 10, '2025-12-24 18:59:07'),
(7, 10, 11, '2025-12-25 04:17:51', '2025-12-25 04:18:02', 10, '2025-12-24 22:47:51'),
(8, 11, 11, '2025-12-25 04:25:24', '2025-12-25 04:25:36', 10, '2025-12-24 22:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `drive_qr_tokens`
--

CREATE TABLE `drive_qr_tokens` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `type` enum('checkin','checkout') NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drive_qr_tokens`
--

INSERT INTO `drive_qr_tokens` (`id`, `project_id`, `type`, `token`, `expires_at`, `created_at`) VALUES
(20, 1, 'checkout', '73d8478aec6bdfcc31ba091159f70b98', '2025-12-25 19:58:40', '2025-12-24 18:58:40'),
(21, 2, 'checkin', '370543d6741ab467689e65c2907296db', '2025-12-25 19:59:01', '2025-12-24 18:59:01'),
(22, 2, 'checkout', '23015b56d967c04cc82bc9f5810b58d8', '2025-12-25 19:59:21', '2025-12-24 18:59:21'),
(25, 9, 'checkin', 'dbe416e372634659f853f8a87063ea40', '2025-12-26 04:12:32', '2025-12-24 22:42:32'),
(26, 10, 'checkin', 'a158ecfacc24de0ae8afe10dc118358f', '2025-12-26 04:17:11', '2025-12-24 22:47:11'),
(27, 10, 'checkout', '0755c000f933ba7c92d0aafbfa15f220', '2025-12-26 04:17:59', '2025-12-24 22:47:59'),
(28, 11, 'checkin', '640c188281e25afc9dd826c86941bd63', '2025-12-26 04:24:43', '2025-12-24 22:54:43'),
(29, 11, 'checkout', 'ea6b184bb7eb013672e12f1d7370695d', '2025-12-26 04:25:32', '2025-12-24 22:55:32'),
(30, 1, 'checkin', '90f6fbb3dcce41d96c640a2f6c8bd71c', '2025-12-26 14:18:00', '2025-12-25 08:48:00'),
(31, 12, 'checkin', '3c1f77deaea35e49069541a6bf88ea6a', '2025-12-27 18:02:34', '2025-12-27 12:27:34'),
(32, 14, 'checkin', '0b1a75886c64461576c53ae7c71b2080', '2026-01-17 12:44:24', '2026-01-17 07:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `event_comments`
--

CREATE TABLE `event_comments` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_comments`
--

INSERT INTO `event_comments` (`id`, `project_id`, `user_id`, `comment_text`, `created_at`) VALUES
(1, 3, 1, 'HI', '2025-11-26 07:16:15'),
(2, 3, 1, 'Hello is this working now?', '2025-11-26 07:31:19'),
(3, 3, 1, 'YAYYYYYYYY ITS WORKING RAHHHHH', '2025-11-26 07:31:41'),
(4, 3, 4, 'Yoooo nice', '2025-11-26 07:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `event_rating` int(1) NOT NULL,
  `ngo_helpfulness` int(1) NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finalized_months`
--

CREATE TABLE `finalized_months` (
  `month_year` varchar(7) NOT NULL,
  `finalized_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finalized_months`
--

INSERT INTO `finalized_months` (`month_year`, `finalized_at`) VALUES
('2025-12', '2025-12-27 11:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard_history`
--

CREATE TABLE `leaderboard_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `month_year` varchar(7) DEFAULT NULL,
  `finalized_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `ip_address` varchar(45) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(1, 11, 8, 'Hi bro', 1, '2026-01-17 08:13:09'),
(2, 8, 11, 'hi bro', 0, '2026-01-17 08:13:31'),
(3, 8, 11, 'enna katha?', 0, '2026-01-17 08:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_points`
--

CREATE TABLE `monthly_points` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `month_year` char(7) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_points`
--

INSERT INTO `monthly_points` (`id`, `user_id`, `month_year`, `points`) VALUES
(1, 10, '2025-12', 10),
(2, 11, '2025-12', 40);

-- --------------------------------------------------------

--
-- Table structure for table `ngos`
--

CREATE TABLE `ngos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `verification_link` varchar(255) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_activity_at` datetime DEFAULT current_timestamp(),
  `whatsapp_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngos`
--

INSERT INTO `ngos` (`id`, `user_id`, `name`, `verification_link`, `logo_path`, `website`, `description`, `logo`, `status`, `created_at`, `updated_at`, `last_activity_at`, `whatsapp_link`) VALUES
(1, 8, 'Chelsea', 'https://www.chelseafc.com/en', 'uploads/logos/logo_1766835754_1.png', 'https://www.chelseafc.com/en', 'Chelsea is a dedicated environmental organization focused on preserving Sri Lanka\'s beautiful coastlines. Founded in 2023, we empower local communities to take action against plastic pollution through organized beach cleanups, educational workshops, and sustainable waste management advocacy.', NULL, 'approved', '2025-12-23 16:43:18', '2026-01-18 11:55:03', '2026-01-18 17:25:03', 'https://chat.whatsapp.com/D6geTerXuWGFUZZNzDEsiN');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `type` enum('sponsor','ngo','brand','other') NOT NULL DEFAULT 'other',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo`, `short_description`, `link`, `type`, `created_at`) VALUES
(1, 'Pearl Protectors', 'assets/pearl.png', 'The Pearl Protectors is a volunteer-based organization that advocates towards protecting the marine environment of Sri Lanka. The initiative behind this organization is to create awareness of the imminent threat posed to the marine life.', 'https://pearlprotectors.org/', 'other', '2025-11-26 04:39:54'),
(2, 'Climate Action Now Sri Lanka', 'assets/climate.png', 'Climate Action Now is a volunteer-driven Sri Lankan initiative that connects individuals and organizations that care about the environment and raises public awareness on the urgency of climate action.', 'https://www.climateactionnow.com/', 'other', '2025-11-26 05:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(3, 'suthankuruvayooran10@gmail.com', '0a60ae5fc3b287aebb6b411186b9a949fa872a6179e8f5ee8ed2526303462adc', '0000-00-00 00:00:00', '2025-12-27 12:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `ngo_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) DEFAULT 'General Cleanup',
  `image_path` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `district` varchar(50) DEFAULT 'Colombo',
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `event_time` time NOT NULL,
  `points_reward` int(11) NOT NULL DEFAULT 0,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `ngo_id`, `title`, `description`, `category`, `image_path`, `location`, `district`, `event_date`, `start_time`, `end_time`, `event_time`, `points_reward`, `status`, `created_at`, `updated_at`, `latitude`, `longitude`) VALUES
(1, 1, 'Project Chelsea', 'Chelsea is a community-driven non-governmental organization focused on environmental protection and sustainability. The NGO organizes cleanup drives to reduce pollution in public areas such as streets, parks, beaches, and waterways. Through active volunteer participation, awareness programs, and cooperation with local authorities, Chelsea works to promote responsible waste management and environmental awareness. These initiatives aim not only to clean surroundings but also to educate communities on the importance of maintaining a healthy and sustainable environment.', 'General Cleanup', 'uploads/events/1766510261_clean up 2.jpg', 'Colombo 02', 'Colombo', '2025-12-30', '09:00:00', '13:00:00', '00:00:00', 10, 'open', '2025-12-23 17:17:41', '2025-12-23 17:17:41', 6.91973826, 79.86124992),
(2, 1, '2.0', 'Fqgqgqg', 'General Cleanup', 'uploads/events/1766588217_clean up 1.jpg', 'Galle Face', 'Galle', '2025-12-25', '11:00:00', '14:00:00', '00:00:00', 30, 'closed', '2025-12-24 14:56:57', '2025-12-29 15:34:15', 6.92423015, 79.84481667),
(4, 1, 'Trial', 'Trial', 'General Cleanup', 'uploads/events/1766603112_clean up 1.jpg', 'Kiddies Play Area - A. E. Gunasinghe Playground', 'Colombo', '2025-12-25', '00:35:00', '00:36:00', '00:00:00', 0, 'closed', '2025-12-24 19:05:12', '2025-12-24 21:32:15', 6.93759267, 79.85653277),
(8, 1, 'Sugathadasa Stadium Event', 'Sugathadasa Stadium Event 12345', 'General Cleanup', 'uploads/events/1766608841_clean up 5.jpg', 'Sugathadasa Stadium', 'Colombo', '2025-12-25', '03:15:00', '04:15:00', '00:00:00', 10, 'closed', '2025-12-24 20:40:41', '2025-12-29 15:34:11', 6.94862278, 79.86893448),
(9, 1, 'Test 404', '1234567', 'General Cleanup', 'uploads/events/1766615836_waste management.jpg', 'Colombo 05', 'Colombo', '2025-12-25', '04:09:00', '04:12:00', '00:00:00', 0, 'closed', '2025-12-24 22:37:16', '2025-12-29 15:34:08', 6.88267510, 79.86790146),
(10, 1, 'Test 505', 'FQFQWFQ', 'General Cleanup', 'uploads/events/1766616418_recycling workshop.jpg', 'Havelock City Mall', 'Colombo', '2025-12-25', '04:17:00', '04:18:00', '00:00:00', 0, 'closed', '2025-12-24 22:46:58', '2025-12-29 15:34:06', 6.88269640, 79.86789073),
(11, 1, 'Test 506', 'CQKBFIQBFIJQF', 'General Cleanup', 'uploads/events/1766616876_maunawili-trail-head.jpg', 'St Peter\'s College Swimming Pool', 'Colombo', '2025-12-25', '04:25:00', '04:26:00', '00:00:00', 0, 'open', '2025-12-24 22:54:36', '2025-12-24 22:54:36', 6.88180611, 79.86223297),
(12, 1, 'Freedom Lane Drive', 'this lane buns bruh, come clean it asap smh. stop pmo', 'General Cleanup', 'uploads/events/1766653109_enviromental awareness.png', 'Freedom Lane', 'Colombo', '2025-12-28', '13:30:00', '16:30:00', '00:00:00', 30, 'open', '2025-12-25 08:58:29', '2025-12-25 08:58:29', 6.93698560, 79.86544640),
(13, 1, 'Test Trial', 'Walls Lane Description', 'General Cleanup', 'uploads/events/1767025097_Sesame_seed_hamburger_buns.jpg', 'Walls Lane', 'Colombo', '2025-12-29', '22:51:00', '23:51:00', '00:00:00', 10, 'open', '2025-12-29 16:18:17', '2025-12-29 16:18:17', 6.95832504, 79.86514987),
(14, 1, 'Test 101', 'CLEAN CUH', 'General Cleanup', 'uploads/events/1768633714_Gemini_Generated_Image_3s4nlx3s4nlx3s4n.png', 'Sugathadasa Stadium', 'Colombo', '2026-01-18', '15:00:00', '18:00:00', '00:00:00', 30, 'open', '2026-01-17 07:08:34', '2026-01-17 07:08:34', 6.94807362, 79.86773854),
(15, 1, 'Galle Face', 'Galle Face Cleanup', 'Beach & Coastal Cleanups', 'uploads/events/1768638503_clean up 1.jpg', 'Galle Face', 'Colombo', '2026-01-18', '08:00:00', '10:00:00', '00:00:00', 20, 'open', '2026-01-17 08:28:23', '2026-01-17 08:28:23', 6.92592359, 79.84373306),
(16, 1, 'Crow Island Cleanup', 'Crow Island Cleanup', 'Beach & Coastal Cleanups', 'uploads/events/1768638589_clean up 5.jpg', 'Crow Island', 'Colombo', '2026-01-17', '16:00:00', '18:00:00', '00:00:00', 20, 'open', '2026-01-17 08:29:49', '2026-01-17 08:29:49', 6.97318791, 79.86922055),
(17, 1, 'Preethipura Beach Cleanup Drive', 'Preethipura Beach Cleanup', 'Beach & Coastal Cleanups', 'uploads/events/1768638683_Senegal-pollution_Alamy_2BJDDN5-scaled.jpg', 'Preethipura Beach', 'Colombo', '2026-01-19', '18:00:00', '22:00:00', '00:00:00', 40, 'open', '2026-01-17 08:31:23', '2026-01-17 08:31:23', 6.99687664, 79.86734411),
(18, 1, 'Wellawatte Beach Cleanup', 'Wellawatte Beach Cleanup', 'Beach & Coastal Cleanups', 'uploads/events/1768638753_delftsouthpark-chrisgilili-20231024mg_20231006_145522.jpg', 'Wellawatte Beach', 'Colombo', '2026-01-20', '07:00:00', '10:00:00', '00:00:00', 30, 'open', '2026-01-17 08:32:33', '2026-01-17 08:32:33', 6.87697492, 79.85671843);

-- --------------------------------------------------------

--
-- Table structure for table `reactivation_appeals`
--

CREATE TABLE `reactivation_appeals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reactivation_appeals`
--

INSERT INTO `reactivation_appeals` (`id`, `user_id`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 'Requesting account reactivation.', 'approved', '2025-12-25 08:44:01', '2025-12-25 08:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `points_cost` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `partner_name` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `stock_count` int(11) DEFAULT 100,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`id`, `title`, `description`, `points_cost`, `category`, `partner_name`, `image_path`, `stock_count`, `is_active`, `created_at`) VALUES
(1, 'Rs. 1,000 Keells Super Voucher', 'Get Rs. 1,000 off your next grocery bill at any Keells outlet.', 500, 'Supermarket', 'Keells Super', 'assets/rewards/keells.jpg', 99, 1, '2025-12-24 21:02:28'),
(2, 'Odel Fashion Discount (20%)', 'Enjoy 20% off on your entire purchase at Odel.', 350, 'Fashion', 'Odel', 'assets/rewards/odel.jpg', 100, 1, '2025-12-24 21:02:28'),
(3, 'Arpico Supercentre Rs. 500 Coupon', 'Valid for all household items at Arpico Supercentre.', 250, 'Supermarket', 'Arpico', 'assets/rewards/arpico.jpg', 99, 1, '2025-12-24 21:02:28'),
(4, 'Dilmah Tea Lounge - Free High Tea', 'Exclusive high tea experience for two at Dilmah Tea Lounge.', 600, 'Dining', 'Dilmah', 'assets/rewards/dilmah.jpg', 99, 1, '2025-12-24 21:02:28'),
(5, 'Cargills Food City Cash Voucher', 'Rs. 1,500 voucher for groceries and pharmacy items.', 700, 'Supermarket', 'Cargills', 'assets/rewards/cargills.jpg', 98, 1, '2025-12-24 21:02:28'),
(6, 'Spa Ceylon Luxury Set', 'Redeem a luxury wellness set from Spa Ceylon.', 800, 'Wellness', 'Spa Ceylon', 'assets/rewards/spaceylon.jpg', 96, 1, '2025-12-24 21:02:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','ngo','admin') NOT NULL DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `points` int(11) DEFAULT 0,
  `lifetime_points` int(11) NOT NULL DEFAULT 0,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `last_active_at` datetime DEFAULT current_timestamp(),
  `last_reengagement_email_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `city`, `is_active`, `created_at`, `updated_at`, `admin`, `points`, `lifetime_points`, `latitude`, `longitude`, `last_active_at`, `last_reengagement_email_at`) VALUES
(6, 'Admin', 'admin@gmail.com', '$2y$10$KLhnELz1xQRypcvKELr0auN5kHLpxSnHNnMX.q.woo4560Oq.jwUq', 'admin', NULL, NULL, 1, '2025-11-26 08:20:41', '2026-01-18 12:02:43', 0, 0, 0, NULL, NULL, '2026-01-18 17:32:43', NULL),
(8, 'Chelsea', 'chelsea@gmail.com', '$2y$10$xFwSLSABcm3H2mzK0Ut/8u.CUbQJW4.Vjvhip1bWzzZ3S07wpng/m', 'ngo', NULL, NULL, 1, '2025-12-23 16:43:18', '2026-01-18 11:56:32', 0, 0, 0, NULL, NULL, '2026-01-18 17:26:32', NULL),
(10, 'Raghav', 'raghav@gmail.com', '$2y$10$QEjTldHXWuvwU3lvI6TbBukzxhiFJ04uWGNbk6AWMH1DITRB9knQ6', 'user', NULL, NULL, 1, '2025-12-24 17:33:07', '2026-01-17 13:18:00', 0, 35, 35, NULL, NULL, '2025-12-01 12:00:00', '2026-01-17 18:35:57'),
(11, 'Suthan', 'suthankuruvayooran10@gmail.com', '$2y$10$fVRL46QNYbph2e0ZA1ITlOCkHeXUjBBwCsyWMDbr6xm7oV5cEWwqe', 'user', '', 'Freedom Lane, Colombo 15', 1, '2025-12-24 18:55:19', '2026-01-18 11:42:46', 0, 90, 90, 6.95684646, 79.86274391, '2026-01-18 17:12:46', NULL),
(13, 'Test', 'test@gmail.com', '$2y$10$QSfJdMQvakUKu2a5XjsDiuB5wffz0twBP2xDEF/aEBMWtx30gkT6i', 'user', NULL, 'Wattala', 1, '2025-12-24 21:14:20', '2026-01-17 13:48:05', 0, 0, 5000, 6.98918626, 79.89308238, '2026-01-17 19:18:05', NULL),
(14, 'Vidwa', 'vidwa@gmail.com', '$2y$10$dRiRWOcMDWfmtW2oDBJtlOlEjpfcY4Wr2aFcyekPn.SnwN/CpzWJm', 'user', NULL, 'Panadura', 1, '2025-12-27 21:28:52', '2025-12-27 21:28:52', 0, 0, 0, 6.93698560, 79.86216960, '2026-01-17 18:21:27', NULL),
(16, 'Bunnis', 'onlygpt67@gmail.com', '$2y$10$jEv.vHVRkPJRqAko.F8iquMOtgw1RBs2XMYVnDS3KgteF2AwVdo7i', 'user', NULL, 'Freedom Lane, Colombo 15', 1, '2025-11-17 12:58:02', '2026-01-17 13:06:01', 0, 0, 0, 6.95813119, 79.86423962, '2025-11-17 18:28:02', '2026-01-17 18:36:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_redemptions`
--

CREATE TABLE `user_redemptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_id` int(11) NOT NULL,
  `points_spent` int(11) NOT NULL,
  `redemption_code` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_redemptions`
--

INSERT INTO `user_redemptions` (`id`, `user_id`, `reward_id`, `points_spent`, `redemption_code`, `status`, `created_at`) VALUES
(1, 13, 5, 700, 'E4DDA39A09', 'available', '2025-12-24 21:14:55'),
(2, 13, 3, 250, '7A7D423BB4', 'available', '2025-12-24 21:27:37'),
(3, 13, 5, 700, '542AF5087B', 'available', '2026-01-17 13:22:33'),
(4, 13, 6, 800, 'C45C3FEA7F', 'available', '2026-01-17 13:22:39'),
(5, 13, 4, 600, '26B0E6B64B', 'available', '2026-01-17 13:22:41'),
(6, 13, 1, 500, '6948839457', 'available', '2026-01-17 13:22:42'),
(7, 13, 6, 800, 'D5D680FB81', 'available', '2026-01-17 13:22:45'),
(8, 13, 6, 800, '8681A26C48', 'available', '2026-01-17 13:22:47'),
(9, 13, 6, 800, 'F50E888B14', 'available', '2026-01-17 13:22:48');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_applications`
--

CREATE TABLE `volunteer_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` enum('applied','accepted','completed','cancelled') NOT NULL DEFAULT 'applied',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reminder_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_applications`
--

INSERT INTO `volunteer_applications` (`id`, `user_id`, `project_id`, `status`, `applied_at`, `reminder_sent`) VALUES
(10, 11, 8, 'applied', '2025-12-24 20:40:54', 1),
(11, 10, 2, 'completed', '2025-12-24 22:52:26', 0),
(12, 11, 11, 'completed', '2025-12-24 22:55:05', 0),
(13, 11, 12, 'applied', '2025-12-27 11:04:17', 0),
(14, 11, 13, 'applied', '2025-12-29 16:18:39', 1),
(15, 11, 14, 'applied', '2026-01-17 07:09:12', 0),
(16, 11, 16, 'applied', '2026-01-17 08:33:35', 1),
(17, 11, 15, 'applied', '2026-01-17 08:33:43', 0),
(18, 11, 18, 'applied', '2026-01-17 10:08:27', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ngo_id` (`ngo_id`);

--
-- Indexes for table `community_post_media`
--
ALTER TABLE `community_post_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `community_proposals`
--
ALTER TABLE `community_proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `deleted_accounts`
--
ALTER TABLE `deleted_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `drive_attendance`
--
ALTER TABLE `drive_attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`user_id`);

--
-- Indexes for table `drive_qr_tokens`
--
ALTER TABLE `drive_qr_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_comments`
--
ALTER TABLE `event_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_feedback_user` (`user_id`),
  ADD KEY `fk_feedback_project` (`project_id`);

--
-- Indexes for table `finalized_months`
--
ALTER TABLE `finalized_months`
  ADD PRIMARY KEY (`month_year`);

--
-- Indexes for table `leaderboard_history`
--
ALTER TABLE `leaderboard_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`ip_address`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_msg_sender` (`sender_id`),
  ADD KEY `fk_msg_receiver` (`receiver_id`);

--
-- Indexes for table `monthly_points`
--
ALTER TABLE `monthly_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`month_year`);

--
-- Indexes for table `ngos`
--
ALTER TABLE `ngos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ngos_user` (`user_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projects_ngo` (`ngo_id`);

--
-- Indexes for table `reactivation_appeals`
--
ALTER TABLE `reactivation_appeals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_redemptions`
--
ALTER TABLE `user_redemptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reward_id` (`reward_id`);

--
-- Indexes for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_va_user` (`user_id`),
  ADD KEY `fk_va_project` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `community_post_media`
--
ALTER TABLE `community_post_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `community_proposals`
--
ALTER TABLE `community_proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deleted_accounts`
--
ALTER TABLE `deleted_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drive_attendance`
--
ALTER TABLE `drive_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `drive_qr_tokens`
--
ALTER TABLE `drive_qr_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `event_comments`
--
ALTER TABLE `event_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaderboard_history`
--
ALTER TABLE `leaderboard_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `monthly_points`
--
ALTER TABLE `monthly_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ngos`
--
ALTER TABLE `ngos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reactivation_appeals`
--
ALTER TABLE `reactivation_appeals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_redemptions`
--
ALTER TABLE `user_redemptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD CONSTRAINT `community_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `community_posts_ibfk_2` FOREIGN KEY (`ngo_id`) REFERENCES `ngos` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `community_proposals`
--
ALTER TABLE `community_proposals`
  ADD CONSTRAINT `community_proposals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_msg_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `monthly_points`
--
ALTER TABLE `monthly_points`
  ADD CONSTRAINT `monthly_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ngos`
--
ALTER TABLE `ngos`
  ADD CONSTRAINT `fk_ngos_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_projects_ngo` FOREIGN KEY (`ngo_id`) REFERENCES `ngos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reactivation_appeals`
--
ALTER TABLE `reactivation_appeals`
  ADD CONSTRAINT `reactivation_appeals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_redemptions`
--
ALTER TABLE `user_redemptions`
  ADD CONSTRAINT `user_redemptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_redemptions_ibfk_2` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`id`);

--
-- Constraints for table `volunteer_applications`
--
ALTER TABLE `volunteer_applications`
  ADD CONSTRAINT `fk_va_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_va_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
