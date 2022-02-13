-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Jun 2021 um 14:05
-- Server-Version: 10.4.17-MariaDB
-- PHP-Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `kwm226_ue05_schickmair`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `todolist_item`
--

CREATE TABLE `todolist_item` (
  `entryid` int(11) NOT NULL,
  `creationdate` date NOT NULL,
  `creatorid` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editdate` date DEFAULT NULL,
  `text` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktiv',
  `editorid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `todolist_item`
--

INSERT INTO `todolist_item` (`entryid`, `creationdate`, `creatorid`, `title`, `editdate`, `text`, `status`, `editorid`) VALUES
(20, '2021-06-15', 3, 'PHP HÜ machen', '2021-06-15', 'bis morgen', 'abgeschlossen', 20),
(21, '2021-06-15', 16, 'Neuer Eintrag für jdoe22', '2021-06-16', 'Bearbeitet von Tutor', 'aktiv', 20),
(22, '2021-06-15', 20, 'Hausübungen kontrollieren', NULL, '', 'aktiv', NULL),
(23, '2021-06-15', 16, 'Wäsche waschen', NULL, 'Heute noch', 'aktiv', NULL),
(24, '2021-06-15', 2, 'Katzen füttern', '2021-06-15', 'Mucki Mimi und Timo füttern bis heute Abend', 'abgeschlossen', 2),
(25, '2021-06-15', 2, 'Boden wischen', NULL, '', 'aktiv', NULL),
(27, '2021-06-15', 20, 'Ein Buch lesen', NULL, 'von Sebastian Fitzek', 'aktiv', NULL),
(28, '2021-06-15', 20, 'Testfall-Eintrag', NULL, 'neuer Testfall', 'aktiv', NULL),
(29, '2021-06-16', 3, 'Babysitten', NULL, '', 'aktiv', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin1', 'e80b5017098950fc58aad83c8c14978e', 'admin@gmail.com', 1),
(2, 'user1', 'e80b5017098950fc58aad83c8c14978e', 'user1@gmail.com', 0),
(3, 'verena', 'e8dc4081b13434b45189a720b77b6818', 'verena.schickmair@hotmail.com', 0),
(12, 'newUser', 'af88a0ae641589b908fa8b31f0fcf6e1', 'newuser@gmail.com', 0),
(16, 'jdoe22', '08f2da91b74d04192c3be61fee05d560', 'jdoe@gmail.com', 0),
(20, 'tutor', 'd25d16ce50f65811a4f0126bb5d3a27c', 'tutor@gmail.com', 1),
(21, 'Testfall', '1f2194a0f87341e3e49a467d79c493f2', 'test@gmail.com', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_item`
--

CREATE TABLE `user_item` (
  `entryid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user_item`
--

INSERT INTO `user_item` (`entryid`, `userid`) VALUES
(20, 3),
(21, 16),
(22, 20),
(23, 16),
(24, 2),
(25, 2),
(27, 20),
(28, 20),
(29, 3);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `todolist_item`
--
ALTER TABLE `todolist_item`
  ADD PRIMARY KEY (`entryid`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`,`email`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indizes für die Tabelle `user_item`
--
ALTER TABLE `user_item`
  ADD PRIMARY KEY (`entryid`,`userid`),
  ADD KEY `user_item_ibfk_2` (`userid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `todolist_item`
--
ALTER TABLE `todolist_item`
  MODIFY `entryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `user_item`
--
ALTER TABLE `user_item`
  ADD CONSTRAINT `user_item_ibfk_1` FOREIGN KEY (`entryid`) REFERENCES `todolist_item` (`entryid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_item_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
