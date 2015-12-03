-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 06. März 2015 um 10:10
-- Server Version: 5.5.8
-- PHP-Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `schulsanidienst`
--

CREATE DATABASE IF NOT EXISTS `SchulSanidienst`;
USE SchulSanidienst;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `anwesenheit`
--

CREATE TABLE IF NOT EXISTS `anwesenheit` (
  `Kalenderwoche` int(11) NOT NULL DEFAULT '0',
  `SanitaeterID` int(11) NOT NULL DEFAULT '0',
  `Montag` tinyint(1) DEFAULT '0',
  `Dienstag` tinyint(1) DEFAULT '0',
  `Mittwoch` tinyint(1) DEFAULT '0',
  `Donnerstag` tinyint(1) DEFAULT '0',
  `Freitag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Kalenderwoche`,`SanitaeterID`),
  KEY `SanitaeterID` (`SanitaeterID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `anwesenheit`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dienst`
--

CREATE TABLE IF NOT EXISTS `dienst` (
  `Kalenderwoche` int(11) NOT NULL DEFAULT '0',
  `SanitaeterID` int(11) NOT NULL DEFAULT '0',
  `Montag` tinyint(1) DEFAULT '0',
  `Dienstag` tinyint(1) DEFAULT '0',
  `Mittwoch` tinyint(1) DEFAULT '0',
  `Donnerstag` tinyint(1) DEFAULT '0',
  `Freitag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Kalenderwoche`,`SanitaeterID`),
  KEY `SanitaeterID` (`SanitaeterID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `dienst`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `erweiterung`
--

CREATE TABLE IF NOT EXISTS `erweiterung` (
  `ErweiterungID` int(11) NOT NULL AUTO_INCREMENT,
  `WERT` text,
  `SanitaeterID` int(11) NOT NULL,
  PRIMARY KEY (`ErweiterungID`),
  KEY `SanitaeterID` (`SanitaeterID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `erweiterung`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `handys`
--

CREATE TABLE IF NOT EXISTS `handys` (
  `HandyID` int(11) NOT NULL AUTO_INCREMENT,
  `Handynummer` varchar(15) DEFAULT NULL,
  `SanitaeterID` int(11) DEFAULT NULL,
  PRIMARY KEY (`HandyID`),
  KEY `SanitaeterID` (`SanitaeterID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `handys`
--

INSERT INTO `handys` (`HandyID`, `Handynummer`, `SanitaeterID`) VALUES
(1, '0176 4186475838', NULL),
(2, '0176 4474568856', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollen`
--

CREATE TABLE IF NOT EXISTS `rollen` (
  `RollenID` int(11) NOT NULL AUTO_INCREMENT,
  `BEZEICHNUNG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`RollenID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `rollen`
--

INSERT INTO `rollen` (`RollenID`, `BEZEICHNUNG`) VALUES
(1, 'Sanitäter'),
(2, 'Dienstleiter'),
(3, 'Sekretärin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `unfaelle`
--

CREATE TABLE IF NOT EXISTS `unfaelle` (
  `UnfallID` int(11) NOT NULL DEFAULT '0',
  `SanitaeterID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UnfallID`,`SanitaeterID`),
  KEY `SanitaeterID` (`SanitaeterID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `unfaelle`
--

INSERT INTO `unfaelle` (`UnfallID`, `SanitaeterID`) VALUES
(1, 1),
(2, 1),
(3, 1),
(2, 2),
(4, 2),
(10, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `unfalldetails`
--

CREATE TABLE IF NOT EXISTS `unfalldetails` (
  `UnfallID` int(11) NOT NULL DEFAULT '0',
  `Zeitpunkt` datetime DEFAULT NULL,
  `Unfallart` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`UnfallID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `unfalldetails`
--

INSERT INTO `unfalldetails` (`UnfallID`, `Zeitpunkt`, `Unfallart`) VALUES
(1, '2015-02-28 13:10:27', 'Amoklauf'),
(2, '2012-04-05 14:20:27', 'Suizidversuch'),
(3, '2010-10-10 08:59:59', 'Terroranschlag'),
(4, '1989-12-31 03:04:00', 'Vom Auto angefahren'),
(10, '1921-07-12 13:10:27', 'Sportverletzung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `SanitaeterID` int(11) NOT NULL AUTO_INCREMENT,
  `Vorname` varchar(40) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Klasse` varchar(40) DEFAULT NULL,
  `Telefonnummer` varchar(20) DEFAULT NULL,
  `EMail` varchar(80) NOT NULL,
  `Vorbildung` text,
  `Passwort` varchar(32) DEFAULT NULL,
  `RollenID` int(11) DEFAULT NULL,
  PRIMARY KEY (`SanitaeterID`),
  KEY `RollenID` (`RollenID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`SanitaeterID`, `Vorname`, `Name`, `Klasse`, `Telefonnummer`, `EMail`, `Vorbildung`, `Passwort`, `RollenID`) VALUES
(1, 'Wolli', 'Kieler', 'ISE12', NULL, '', NULL, NULL, NULL),
(2, 'Sven', 'Ulrich', 'IFA12A', NULL, 'sven.urlich@bs-erlangen.de', NULL, 'aa5b2280e9c317c907b12ee083faeb3c', 1),
(3, 'Bana', 'Ghebreab', 'WSK11A', NULL, '', NULL, NULL, NULL),
(4, 'Max', 'Bischoff', 'DBF11A', NULL, '', NULL, NULL, NULL),
(5, 'Andreas', 'Reiter', 'TEM11A', NULL, '', NULL, NULL, NULL),
(6, 'Alexandra', 'Haagen', 'EGS11A', NULL, '', NULL, NULL, NULL),
(7, 'Darius', 'Rey', 'IF10D', NULL, '', NULL, NULL, NULL),
(8, 'Alina', 'Erban', 'WZF11A', NULL, '', NULL, NULL, NULL),
(9, 'Bamo', 'Mampaka', 'WEH10A', NULL, '', NULL, NULL, NULL),
(10, 'Nina', 'Zeitler', 'WEH10B', NULL, '', NULL, NULL, NULL),
(11, 'Bonnie', 'Heidingsfeld', 'WMF10A', NULL, '', NULL, NULL, NULL),
(12, 'Ruth', 'Klein', 'WMF12A', NULL, '', NULL, NULL, NULL),
(13, 'Katharina', 'Rüdel', 'WMF12A', NULL, '', NULL, NULL, NULL),
(14, 'Patrik', 'Müller', 'TEM11B', NULL, '', NULL, NULL, NULL),
(15, 'Konstantin', 'Kolb', 'WVA11C', NULL, '', NULL, NULL, NULL),
(16, 'Tobias', 'Karius', 'WKD10A', NULL, '', NULL, NULL, NULL),
(17, 'Sascha', 'Chladek', 'ISE10B', NULL, '', NULL, NULL, NULL),
(18, 'Erik', 'Denk', 'ISE10A', NULL, '', NULL, NULL, NULL),
(20, 'Hans', 'Daniel', 'IFA12A', NULL, 'hans.daniel@bs-erlangen.de', NULL, 'aa5b2280e9c317c907b12ee083faeb3c', 2),
(24, 'Julian', 'Gall', 'IFA11A', '', 'j.gall@bs-erlangen.de', 'miep', 'aa5b2280e9c317c907b12ee083faeb3c', 1),
(25, 'Sekretariat', 'BS-Erlangen', NULL, NULL, 'sekretariat@bs-erlangen.de', NULL, 'aa5b2280e9c317c907b12ee083faeb3c', 3);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `anwesenheit`
--
ALTER TABLE `anwesenheit`
  ADD CONSTRAINT `anwesenheit_ibfk_1` FOREIGN KEY (`SanitaeterID`) REFERENCES `user` (`SanitaeterID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `dienst`
--
ALTER TABLE `dienst`
  ADD CONSTRAINT `dienst_ibfk_1` FOREIGN KEY (`SanitaeterID`) REFERENCES `user` (`SanitaeterID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `erweiterung`
--
ALTER TABLE `erweiterung`
  ADD CONSTRAINT `erweiterung_ibfk_1` FOREIGN KEY (`SanitaeterID`) REFERENCES `user` (`SanitaeterID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `handys`
--
ALTER TABLE `handys`
  ADD CONSTRAINT `handys_ibfk_1` FOREIGN KEY (`SanitaeterID`) REFERENCES `user` (`SanitaeterID`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `unfaelle`
--
ALTER TABLE `unfaelle`
  ADD CONSTRAINT `unfaelle_ibfk_1` FOREIGN KEY (`SanitaeterID`) REFERENCES `user` (`SanitaeterID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `unfalldetails`
--
ALTER TABLE `unfalldetails`
  ADD CONSTRAINT `unfalldetails_ibfk_1` FOREIGN KEY (`UnfallID`) REFERENCES `unfaelle` (`UnfallID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`RollenID`) REFERENCES `rollen` (`RollenID`) ON UPDATE CASCADE;


--GRANT SELECT, INSERT, UPDATE ON schulsanidienst.* TO 'user'@'localhost';
