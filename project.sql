-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 23 Lip 2020, 19:47
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
  `title` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `posts`
--

INSERT INTO `posts` (`id_post`, `id_user`, `date`, `title`, `category`, `content`) VALUES
(1, 1, '2020-07-23 18:20:52', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(2, 1, '2020-07-23 18:20:54', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(3, 1, '2020-07-23 18:20:55', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(4, 1, '2020-07-23 18:20:55', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(5, 1, '2020-07-23 18:20:57', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(6, 1, '2020-07-23 18:20:58', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(7, 1, '2020-07-23 18:20:58', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(8, 1, '2020-07-23 18:20:59', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(9, 1, '2020-07-23 18:21:09', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.'),
(10, 1, '2020-07-23 18:21:11', 'VoksWagen', 'Pojazdy', 'Sprzedam Passata.');

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
  `city` text NOT NULL,
  `verified` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_user`, `login`, `email`, `password`, `date`, `city`, `verified`) VALUES
(1, 'admin', 'admin@power.pl', '1234', 1968, 'Katowice', 1),
(2, 'Lanqpl', 'lanqpl@interia.pl', '$2y$10$uSAbHI4q/bQHYYO94HbLhe69EPwVR2OTOrpgGcsbWtjSZkzpl0agO', 1997, 'Warszawa', 0),
(3, 'PatSzy', 'patszy97@interia.pl', '$2y$10$Lds59iHaRggvR9FO9MTqme3N6l054UUtt/7up3c7T35Nm36o30hGe', 1997, 'Warszawa', 0);

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
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
