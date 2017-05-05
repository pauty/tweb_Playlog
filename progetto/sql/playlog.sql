-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 05, 2017 alle 22:33
-- Versione del server: 5.7.14
-- Versione PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `playlog`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `followers`
--

CREATE TABLE `followers` (
  `follower_id` int(10) UNSIGNED NOT NULL,
  `followed_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `followers`
--

INSERT INTO `followers` (`follower_id`, `followed_id`) VALUES
(2, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `games`
--

CREATE TABLE `games` (
  `id` int(20) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `cover_url` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `games`
--

INSERT INTO `games` (`id`, `title`, `cover_url`) VALUES
(123, 'World of Warcraft', 'osspygfgdohniipayzeu'),
(181, 'Grim Fandango', 'l7mtkr6stxqq3pqeaelp'),
(218, 'MDK2', 'orcvy3auf0idwgz3zfz9'),
(260, 'The Sims 3', 'hqzznk6ymytltzc2ogzz'),
(500, 'Batman: Arkham Asylum', 'bahc2tpuaavdn0rth03a'),
(1025, 'Zelda II: The Adventure of Link', 'apo9ih6n8f7saseeftmg'),
(1026, 'The Legend of Zelda: A Link to the Past', 'uh5fxmxlxqpgtck4peao'),
(1034, 'The Legend of Zelda: Four Swords Adventures', 'anchyms2cmzxi9vk0j3u'),
(1036, 'The Legend of Zelda: Twilight Princess', 'as5ynbrxuoqrxoha1dsa'),
(1068, 'Super Mario Bros. 3', 'ogguvec9vhrsml4uxqrg'),
(1070, 'Super Mario World', 'rbdpnsh7jkx4cvejygkc'),
(1077, 'Super Mario Galaxy', 'tzhmiarlwidopzrzohd5'),
(1078, 'Super Mario Galaxy 2', 'wyk6wqgo2fjd4g6xdntx'),
(1284, 'Lollipop Chainsaw', 'jnljgvdqnqo0jzqnvcx3'),
(1331, 'Limbo', 'zrgtcrizlaeo8axliehq'),
(1352, 'Journey', 'mjqpr06rbwnraky2un7a'),
(1354, 'Flower', 'htbhy8lvitxikc7vquef'),
(1368, 'Sniper: Ghost Warrior 2', 'qwhcrzelmg9lx2opv8lm'),
(1591, 'ZombiU', 'hgwqn3l2sfnztlfuzuqf'),
(1594, 'Gran Turismo', 'xahv7e1pfk4ptdiybzfz'),
(1958, 'Alone in the Dark', 'beosp3v4xcbw7rs6dld2'),
(1960, 'Alone in the Dark: The New Nightmare', 'chivzcacubc2mzmlhcxn'),
(2135, 'Bayonetta 2', 'qocwccspgmgamvhaplgp'),
(2255, 'Mario Strikers Charged', 'rvkrv6vnrlzql6yis7ns'),
(2343, 'Mario Kart: Super Circuit', 'm8sdu5wj1mcfhey2pe6u'),
(2987, 'Wolfenstein: Enemy Territory', 'hrkmbfigukxvtpwvkruz'),
(3105, 'Mario Bros.', 'k72rncu6357fmlevus3t'),
(3193, 'Need for Speed: Most Wanted', 'onlimcrgz1zsngrffmql'),
(3340, 'Paper Mario', 'l9nprmc4yn1yvnhheeuy'),
(3403, 'Mario Golf: Toadstool Tour', 'sg6npqgqy48ahgpyi31l'),
(4194, 'Taz: Wanted', 'rjjfkrqsfeetulixkibx'),
(4254, 'Mad Dog McCree', 'jbue1pogfric0p7ietr6'),
(5199, 'Super Mario All-Stars', 'nl9vdgf0yei8xzdloza1'),
(5265, 'We Love Golf!', 'qlmhenlvqyhdvoe1jqsx'),
(5322, 'Sonic Boom: Rise of Lyric', 'hgu5wijnnjc7hge34skm'),
(5379, 'Ecco: The Tides of Time', 'b2rz067kdrolzvupjkbt'),
(5502, 'Transport Tycoon Deluxe', 'lhy5xnsvece4inaopscf'),
(5540, 'Moebius: Empire Rising', 'fjx7a8fork6kmqb3hwd2'),
(6505, 'Mario vs. Donkey Kong', 'wrakcsrllq6mhoi4tcy4'),
(6530, 'Pac-Man World', 'oaykvhec0eodtrayltxg'),
(7337, 'Captain Toad: Treasure Tracker', 'zxoauzd1g7yhsgvgcqfc'),
(7339, 'Super Mario Maker', 'dp2l6fpd1yfj0sxcjsi8'),
(8534, 'Zelda\'s Adventure', 'qn63rdqtsrw6adygheyo'),
(8682, 'Grim Fandango Remastered', 'xfdt9435wacmowbxho4o'),
(9083, 'Tetris 2', 'jzfuwyalganoepmzufkc'),
(9602, 'Super Smash Bros. for Wii U', 't59mxa13lrt98iyi2xol'),
(11078, 'Shantae: Half-Genie Hero', 'nl0xylkr12ta44sufduv'),
(11133, 'Dark Souls III', 'ofu6ewg0tzdt5vmzcj9q'),
(11194, 'The Legend of Zelda: Tri Force Heroes', 'dtj80vlbzqbywzcdcdiv'),
(18017, 'The Legend of Zelda: Twilight Princess HD', 'klebtdw8yafmdcyc8ljl'),
(18651, 'Miner 2049er', 'h12s90zgabxtyl32l76f'),
(22943, 'L.A. Noire: Nicholson Electroplating', 'u2wi7u2frbzi8hjsnzmm'),
(24400, 'Dissidia Final Fantasy Opera Omnia', 'yqflho0ikk5l0hd2rjtj');

-- --------------------------------------------------------

--
-- Struttura della tabella `ownership`
--

CREATE TABLE `ownership` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `game_id` int(20) UNSIGNED NOT NULL,
  `state` varchar(15) NOT NULL,
  `platforms` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `ownership`
--

INSERT INTO `ownership` (`user_id`, `game_id`, `state`, `platforms`) VALUES
(1, 181, 'playing', 'PC (Microsoft Windows)'),
(1, 1025, 'finished', 'Nintendo GameCube,Game Boy Advance'),
(1, 1068, 'playing', 'Super Nintendo Entertainment System (SNES)'),
(1, 1070, 'owned', 'Super Nintendo Entertainment System (SNES)'),
(1, 1077, 'finished', 'Wii'),
(1, 1078, 'finished', 'Wii'),
(1, 1331, 'wishlist', 'PC (Microsoft Windows),Linux'),
(1, 1352, 'wishlist', 'PlayStation 4'),
(1, 1354, 'wishlist', 'PlayStation 4'),
(1, 2343, 'playing', 'Virtual Console (Nintendo)'),
(1, 3193, 'dropped', 'Xbox 360'),
(1, 3403, 'playing', 'Nintendo GameCube'),
(1, 4254, 'wishlist', 'PlayStation Network,Nintendo 3DS,iOS,Wii,3DO Interactive Multiplayer,PC (Microsoft Windows),Sega CD,Arcade,PC DOS,Mac,Other'),
(1, 5199, 'finished', 'Wii'),
(1, 6505, 'dropped', 'Game Boy Advance'),
(1, 6530, 'playing', 'Game Boy Advance,PlayStation Network'),
(1, 7339, 'finished', 'Wii U'),
(1, 8682, 'finished', 'Linux'),
(2, 123, 'owned', 'PC (Microsoft Windows)'),
(2, 500, 'finished', 'PlayStation 3,PC (Microsoft Windows)'),
(2, 1368, 'wishlist', 'Xbox 360,Other'),
(2, 2987, 'playing', 'Linux,PC (Microsoft Windows)'),
(2, 4194, 'finished', 'PC (Microsoft Windows)'),
(2, 5502, 'wishlist', 'PlayStation'),
(2, 5540, 'dropped', 'PC (Microsoft Windows)'),
(2, 9602, 'finished', 'Wii U'),
(2, 24400, 'playing', 'Android');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`) VALUES
(1, 'test@test.it', 'nopenope', '6b12bed9c52bd1e89b79f26c0cbedefc'),
(2, 'test2@test2.it', 'yepyep', '6b12bed9c52bd1e89b79f26c0cbedefc');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `following` (`follower_id`,`followed_id`),
  ADD KEY `followed` (`followed_id`);

--
-- Indici per le tabelle `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indici per le tabelle `ownership`
--
ALTER TABLE `ownership`
  ADD PRIMARY KEY (`user_id`,`game_id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ownership`
--
ALTER TABLE `ownership`
  ADD CONSTRAINT `ownership_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ownership_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
