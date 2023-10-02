-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Lip 13, 2023 at 03:38 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mapadb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pins`
--

CREATE TABLE `pins` (
  `id` int(11) NOT NULL,
  `loc_x` int(11) NOT NULL,
  `loc_y` int(11) NOT NULL,
  `desc` varchar(100) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `author_name` text NOT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pins`
--

INSERT INTO `pins` (`id`, `loc_x`, `loc_y`, `desc`, `author_id`, `author_name`, `group_id`) VALUES
(210, 1083, 335, 'co to flanki??', NULL, 'student 1 roku', NULL),
(211, 1048, 465, 'Kocham piwo', NULL, 'Weteran miasteczka', NULL),
(212, 1131, 587, 'Jak ja lubię jeść makaron', NULL, 'Szymon Mamoń', NULL),
(213, 1011, 731, 'Jesteśmy bardzo użyteczni i studenci nas kochają', NULL, 'WGJ', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `pins`
--
ALTER TABLE `pins`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pins`
--
ALTER TABLE `pins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
