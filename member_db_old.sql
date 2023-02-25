-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 20 dec 2022 om 22:03
-- Serverversie: 10.4.27-MariaDB
-- PHP-versie: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `member_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `email_adresses`
--

CREATE TABLE `email_adresses` (
  `email_adress` varchar(128) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `email_adresses`
--

INSERT INTO `email_adresses` (`email_adress`, `member_id`) VALUES
('jan@jansen.nl', 0),
('Jan@kroon.nl', 0),
('jookertje@hotmail.com', 1),
('Karel@gmail.com', 3),
('Kees@gmail.com', 4),
('koen2@gmail.com', 5),
('koen@gmail.com', 5),
('piet29@gmail.com', 6),
('pienp@gmail.com', 7),
('pieterv@gmail.com', 8),
('nijn@gmail.com', 9),
('freek@gmail.com', 10),
('Peterpan@gmail.com', 11),
('kraantje@gids.nl', 12),
('warnerb@gmail.com', 13),
('bram@gmail.com', 16);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `postal` varchar(6) NOT NULL,
  `house_number` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `members`
--

INSERT INTO `members` (`member_id`, `name`, `postal`, `house_number`) VALUES
(0, 'Jan Pietertjes   ', '2930JK', '24'),
(1, 'Joke Peter', '5859GR', '30'),
(2, 'Pieter Jansen ', '4022KD', '65'),
(3, 'Karel de Lange', '2940WK', '32'),
(4, 'Kees de Kolk', '5869LR', '33'),
(5, 'Koen Sanders  ', '4029ED', '42'),
(6, 'Piet Frederikjes ', '2230JK', '8'),
(7, 'Pien Pan', '5759GJ', '36'),
(8, 'Pieter van Vliet', '4059CD', '37'),
(9, 'Nijn Tjerp', '3930JK', '38'),
(10, 'Freek Vonk', '9191SD', '39'),
(11, 'Peter Pannekoek', '9292SH', '40'),
(12, 'Kraantje Pappie', '7681CH', '38'),
(13, 'Warners Bros', '5674KJ', '39'),
(16, 'Bram van der Vugt', '9283SK', '45');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `phone_numbers`
--

CREATE TABLE `phone_numbers` (
  `phone_number` varchar(12) NOT NULL,
  `member_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `phone_numbers`
--

INSERT INTO `phone_numbers` (`phone_number`, `member_id`) VALUES
('0693843928', 0),
('0692938201', 1),
('0610293847', 2),
('0637283928', 2),
('0693847562', 3),
('0602938201', 4),
('0602928201', 5),
('0610857829', 6),
('0610834234', 7),
('0612346436', 8),
('0613467358', 9),
('0615346738', 10),
('0635346738', 11),
('0635346739', 12),
('0685830827', 12),
('0282383888', 13),
('06543729747', 16);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `postals`
--

CREATE TABLE `postals` (
  `id` int(11) NOT NULL,
  `postal` varchar(6) NOT NULL,
  `adress` varchar(128) DEFAULT NULL,
  `residence` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `postals`
--

INSERT INTO `postals` (`id`, `postal`, `adress`, `residence`) VALUES
(0, '2930JK', 'Buttondorp 28', 'Doetinchem'),
(1, '4029ED', 'Knoopstraat 92', 'Deventer'),
(2, '5859GR', 'Janpietstraat 29', 'Arnhem'),
(3, '9283SK', 'Ruitjesweg 38', 'Groningen'),
(4, '9393SJ', 'Schevense 29', 'Leeuwarden'),
(5, '9393SD', 'Schevense 92', 'Leeuwardingen'),
(11, '3939SL', 'Zuiderweg', 'Nijmegen'),
(13, '7681CH', 'Westerweg 19', 'Beerzeveld'),
(14, '5674KJ', 'Groenloer 39', 'Groenlo');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `teams`
--

CREATE TABLE `teams` (
  `team_name` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `teams`
--

INSERT INTO `teams` (`team_name`, `description`) VALUES
('Kogelingen', 'Kogelstoten'),
('Laagspringers', 'Hordelopers'),
('Looie lopers', 'Hardlopers'),
('Ouwe knakkers', 'Poolstok'),
('Snellingers', 'Sprinters');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `team_member`
--

CREATE TABLE `team_member` (
  `team_member_id` int(11) NOT NULL,
  `team_name` varchar(32) NOT NULL,
  `member_id` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `team_member`
--

INSERT INTO `team_member` (`team_member_id`, `team_name`, `member_id`) VALUES
(2, 'Ouwe knakkers', '9'),
(21, 'Kogelingen', '3'),
(22, 'Looie lopers', '5'),
(23, 'Snellingers', '4'),
(24, 'Kogelingen', '11'),
(27, 'Laagspringers', '6');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `forename` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`forename`, `surname`, `username`, `password`) VALUES
('LOIDocent', 'LOI', 'LOIDocent', '$2y$10$ikN6/gAaI9Sxp1avmmCLAum/BHjeaGDxhdszhVTHv.aQLb5vDeydm'),
('Pleun', 'Alferink', 'pleun', '$2y$10$g5v.OtxyOgv6667RqmJzi.E91rl.FcjN8l2JuC/vFPufiSrWfLD/O');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `email_adresses`
--
ALTER TABLE `email_adresses`
  ADD PRIMARY KEY (`email_adress`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexen voor tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `postal` (`postal`);

--
-- Indexen voor tabel `phone_numbers`
--
ALTER TABLE `phone_numbers`
  ADD PRIMARY KEY (`phone_number`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexen voor tabel `postals`
--
ALTER TABLE `postals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `postal` (`postal`);

--
-- Indexen voor tabel `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_name`);

--
-- Indexen voor tabel `team_member`
--
ALTER TABLE `team_member`
  ADD PRIMARY KEY (`team_member_id`),
  ADD KEY `team_name` (`team_name`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT voor een tabel `postals`
--
ALTER TABLE `postals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `team_member`
--
ALTER TABLE `team_member`
  MODIFY `team_member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `email_adresses`
--
ALTER TABLE `email_adresses`
  ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
