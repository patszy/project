-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 17 Sie 2020, 17:25
-- Wersja serwera: 10.4.13-MariaDB
-- Wersja PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `project`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `title` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `posts`
--

INSERT INTO `posts` (`id_post`, `id_user`, `date`, `title`, `category`, `content`) VALUES
(1, 5, '2020-07-23 18:20:52', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(2, 5, '2020-07-23 18:20:54', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(3, 5, '2020-07-23 18:20:55', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(4, 5, '2020-07-23 18:20:55', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(5, 5, '2020-07-23 18:20:57', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(6, 5, '2020-07-23 18:20:58', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(7, 5, '2020-07-23 18:20:58', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(8, 5, '2020-07-23 18:20:59', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(9, 5, '2020-07-23 18:21:09', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(10, 5, '2020-07-23 18:21:11', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(11, 5, '2020-07-24 13:25:22', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(12, 5, '2020-07-24 13:25:52', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(13, 5, '2020-07-24 13:40:01', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(14, 1, '2020-07-30 19:59:18', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(15, 1, '2020-07-31 10:49:39', 'Budowa', 'Usługi', 'Zrealizuję projekt budowy domu.'),
(16, 1, '2020-07-31 10:53:35', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(17, 1, '2020-07-31 10:54:11', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(18, 1, '2020-07-31 10:56:40', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(19, 1, '2020-07-31 11:06:45', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(20, 5, '2020-07-31 11:08:11', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(21, 5, '2020-08-13 18:21:23', 'Witaj', 'Usługi', 'Miło cię poznać.'),
(22, 4, '2020-08-14 11:39:55', 'wakacje', 'Podróże', 'Wiesz już coś co z tym bonem?\r\n'),
(23, 4, '2020-08-14 11:39:55', 'wakacje', 'Podróże', 'Wiesz już coś co z tym bonem?\r\n'),
(24, 5, '2020-08-14 11:44:30', 'Wakacje', 'Podróże', 'Nie wiem co z tym bonem.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(1024) NOT NULL,
  `date` smallint(6) NOT NULL,
  `city` varchar(50) NOT NULL,
  `permission` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_user`, `login`, `email`, `password`, `date`, `city`, `permission`) VALUES
(1, 'admin', 'admin@power.pl', '$2y$10$0cvHj2/WlI92U.aXrZhCUeCyC9mh.OqXx6U.3w/eHthpXqRN1u7H2', 1968, 'Poznań', 1),
(2, 'Lanqpl', 'lanqpl@interia.pl', '$2y$10$uSAbHI4q/bQHYYO94HbLhe69EPwVR2OTOrpgGcsbWtjSZkzpl0agO', 1997, 'Warszawa', 0),
(3, 'sannit', 'sannit@interia.pl', '$2y$10$QX.F3alpZzLV6BNyvkeq8uu3xB3/1HCWTEiZzDjzi/pCNonCEYjvu', 2004, 'Warszawa', 0),
(4, 'Atena98', 'aneta98kramarczyk@interia.pl', '$2y$10$ykStMhzUU7yLf7UICyicpuI6OhMBkXHY4SCu8FoQCWaz.gq./Q3k2', 1998, 'Poznań', 0),
(5, 'PatSzy', 'patszy97@interia.pl', '$2y$10$o60DEpA51rNmZaYxa02pnuYEaky4ObfsrxRhwjE2MwAwQrsxMl7Da', 1997, 'Warszawa', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
