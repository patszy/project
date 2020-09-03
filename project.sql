-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 03 Wrz 2020, 10:07
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
  `content` text NOT NULL,
  `url_post_img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `posts`
--

INSERT INTO `posts` (`id_post`, `id_user`, `date`, `title`, `category`, `content`, `url_post_img`) VALUES
(1, 5, '2020-07-23 18:20:52', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(2, 5, '2020-07-23 18:20:54', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(3, 5, '2020-07-23 18:20:55', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(4, 5, '2020-07-23 18:20:55', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(5, 5, '2020-07-23 18:20:57', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(6, 5, '2020-07-23 18:20:58', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(7, 5, '2020-07-23 18:20:58', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(8, 5, '2020-07-23 18:20:59', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(9, 5, '2020-07-23 18:21:09', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(10, 5, '2020-07-23 18:21:11', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(11, 5, '2020-07-24 13:25:22', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(12, 5, '2020-07-24 13:25:52', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(13, 5, '2020-07-24 13:40:01', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(14, 1, '2020-07-30 19:59:18', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(15, 3, '2020-07-31 10:49:39', 'Budowa', 'usługi budowlane', 'Zrealizuję projekt budowy domu.', ''),
(16, 1, '2020-07-31 10:53:35', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(17, 1, '2020-07-31 10:54:11', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(18, 1, '2020-07-31 10:56:40', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(19, 1, '2020-07-31 11:06:45', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(20, 5, '2020-07-31 11:08:11', 'VoksWagen', 'usługi samochodowe', 'Sprzedam Passata.', ''),
(21, 5, '2020-08-13 18:21:23', 'Witaj', 'szukam partnera', 'Miło cię poznać.', ''),
(22, 4, '2020-08-14 11:39:55', 'wakacje', 'wakacje i podróże', 'Wiesz już coś co z tym bonem?\r\n', ''),
(23, 4, '2020-08-14 11:39:55', 'wakacje', 'wakacje i podróże', 'Wiesz już coś co z tym bonem?\r\n', ''),
(24, 5, '2020-08-14 11:44:30', 'Wakacje', 'wakacje i podróże', 'Nie wiem co z tym bonem.', ''),
(28, 5, '2020-08-26 15:00:10', 'VolksWagen Passat', 'usługi samochodowe', 'Sprzedam Volkswagen Passat 1.9 TDI', './assets/img/posts_img/2020-08-26 15-00-10_post_img.jpg');

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
  `permission` tinyint(1) DEFAULT NULL,
  `url_portrait` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_user`, `login`, `email`, `password`, `date`, `city`, `permission`, `url_portrait`) VALUES
(1, 'gejusz', 'gejusz.pl@gmail.com', '$2y$10$Yh6mvWYSWjtNzGSMUVSANeZtyIz85dGG2fPE/F9LhcS9tYBY8rQrm', 1968, 'katowice', 1, './assets/img/portraits/undraw_male_avatar_323b.svg'),
(2, 'Lanqpl', 'lanqpl@interia.pl', '$2y$10$uSAbHI4q/bQHYYO94HbLhe69EPwVR2OTOrpgGcsbWtjSZkzpl0agO', 1997, 'Warszawa', 0, './assets/img/portraits/undraw_male_avatar_323b.svg'),
(3, 'sannit', 'sannit@interia.pl', '$2y$10$QX.F3alpZzLV6BNyvkeq8uu3xB3/1HCWTEiZzDjzi/pCNonCEYjvu', 2004, 'Warszawa', 0, './assets/img/portraits/undraw_male_avatar_323b.svg'),
(4, 'Atena98', 'aneta98kramarczyk@interia.pl', '$2y$10$U6UBbRldzXLmsXM0LN5Jqe5gRpxK9WdTMacuMq71fzYFOWSJbtvRW', 1998, 'poznań', 0, './assets/img/portraits/4_portrait.jpg'),
(5, 'PatSzy', 'patszy97@interia.pl', '$2y$10$RKH3hTpoyshnRAA6sOdRbusuPCpAKwKy7JVz2ibuPvfdbwnQDtJ8y', 1997, 'warszawa', 0, './assets/img/portraits/5_portrait.jpg');

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
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
