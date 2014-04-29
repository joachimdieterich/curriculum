-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 07. Sep 2013 um 18:56
-- Server Version: 5.5.29
-- PHP-Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Datenbank: `install`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `config_institution`
--

DROP TABLE IF EXISTS `config_institution`;
CREATE TABLE IF NOT EXISTS `config_institution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_id` int(10) DEFAULT NULL,
  `institution_filepath` varchar(250) DEFAULT NULL,
  `institution_paginator_limit` smallint(6) DEFAULT NULL,
  `institution_standard_role` smallint(6) DEFAULT NULL,
  `institution_standard_country` smallint(6) DEFAULT NULL,
  `institution_standard_state` smallint(6) DEFAULT NULL,
  `institution_csv_size` int(10) DEFAULT NULL,
  `institution_avatar_size` int(10) DEFAULT NULL,
  `institution_material_size` int(10) DEFAULT NULL,
  `institution_acc_days` smallint(6) DEFAULT NULL,
  `institution_language` varchar(10) DEFAULT NULL,
  `institution_timeout` int(11) NOT NULL DEFAULT '10',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `config_institution`
--

INSERT INTO `config_institution` (`id`, `institution_id`, `institution_filepath`, `institution_paginator_limit`, `institution_standard_role`, `institution_standard_country`, `institution_standard_state`, `institution_csv_size`, `institution_avatar_size`, `institution_material_size`, `institution_acc_days`, `institution_language`, `institution_timeout`, `creation_time`, `update_time`) VALUES
(5, 56, '', 30, 0, 56, 11, 1048576, 1048576, 1048576, 7, 'de', 10, '2013-09-07 07:48:50', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `config_user`
--

DROP TABLE IF EXISTS `config_user`;
CREATE TABLE IF NOT EXISTS `config_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `user_filepath` varchar(250) DEFAULT NULL,
  `user_paginator_limit` smallint(6) DEFAULT NULL,
  `user_acc_days` smallint(6) DEFAULT NULL,
  `user_language` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `config_user`
--

INSERT INTO `config_user` (`id`, `user_id`, `user_filepath`, `user_paginator_limit`, `user_acc_days`, `user_language`) VALUES
(138, 77, NULL, 30, 7, 'de'),
(139, 78, NULL, 30, 7, 'de'),
(140, 79, NULL, 30, 7, 'de'),
(141, 80, NULL, 30, 7, 'de'),
(142, 81, NULL, 30, 7, 'de'),
(143, 82, NULL, 30, 7, 'de'),
(144, 83, NULL, 30, 7, 'de'),
(145, 84, NULL, 30, 7, 'de'),
(146, 85, NULL, 30, 7, 'de'),
(147, 86, NULL, 30, 7, 'de'),
(148, 87, NULL, 30, 7, 'de'),
(149, 88, NULL, 30, 7, 'de'),
(150, 89, NULL, 30, 7, 'de'),
(151, 90, NULL, 30, 7, 'de'),
(152, 91, NULL, 30, 7, 'de'),
(153, 92, NULL, 30, 7, 'de'),
(154, 93, NULL, 30, 7, 'de'),
(155, 94, NULL, 30, 7, 'de'),
(156, 95, NULL, 30, 7, 'de'),
(157, 96, NULL, 30, 7, 'de'),
(158, 97, NULL, 30, 7, 'de'),
(159, 98, NULL, 30, 7, 'de'),
(160, 99, NULL, 30, 7, 'de'),
(161, 100, NULL, 30, 7, 'de'),
(162, 101, NULL, 30, 7, 'de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cronjobs`
--

DROP TABLE IF EXISTS `cronjobs`;
CREATE TABLE IF NOT EXISTS `cronjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cronjob` varchar(200) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `log` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `cronjobs`
--

INSERT INTO `cronjobs` (`id`, `cronjob`, `creation_time`, `creator_id`, `log`) VALUES
(1, 'detectExpiredObjective', '2013-09-07 07:49:21', -1, 'DB auf abgelaufene Ziele Ã¼berprÃ¼ft.'),
(2, 'detectExpiredObjective', '2013-09-07 16:34:49', -1, 'DB auf abgelaufene Ziele Ã¼berprÃ¼ft.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `curriculum`
--

DROP TABLE IF EXISTS `curriculum`;
CREATE TABLE IF NOT EXISTS `curriculum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curriculum` varchar(200) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `schooltype_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `country_id` char(2) NOT NULL DEFAULT 'DE',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `icon_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `curriculum`
--

INSERT INTO `curriculum` (`id`, `curriculum`, `grade_id`, `subject_id`, `schooltype_id`, `state_id`, `description`, `country_id`, `creation_time`, `creator_id`, `icon_id`) VALUES
(94, 'Deutsch', 9, 2, 1, 11, 'Bildungsstandards ', '56', '2013-09-07 16:42:16', 77, 124),
(89, 'Mathematik', 9, 1, 1, 11, 'Bildungsstandards', '56', '2013-09-07 16:44:55', 77, 125);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `curriculum_enrolments`
--

DROP TABLE IF EXISTS `curriculum_enrolments`;
CREATE TABLE IF NOT EXISTS `curriculum_enrolments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `curriculum_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expel_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `curriculum_enrolments`
--

INSERT INTO `curriculum_enrolments` (`id`, `status`, `curriculum_id`, `group_id`, `creation_time`, `expel_time`, `creator_id`) VALUES
(3, 1, 94, 94, '2013-09-07 16:54:10', '0000-00-00 00:00:00', 77),
(4, 1, 89, 94, '2013-09-07 16:54:13', '0000-00-00 00:00:00', 77);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `enablingObjectives`
--

DROP TABLE IF EXISTS `enablingObjectives`;
CREATE TABLE IF NOT EXISTS `enablingObjectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabling_objective` varchar(400) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `curriculum_id` int(11) DEFAULT NULL,
  `terminal_objective_id` int(11) NOT NULL DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `repeat_interval` int(11) NOT NULL DEFAULT '-1',
  `order_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=658 ;

-- --------------------------------------------------------

--
--  Daten für Tabelle `enablingObjectives`
--

INSERT INTO `enablingObjectives` (`id`, `enabling_objective`, `description`, `curriculum_id`, `terminal_objective_id`, `creation_time`, `creator_id`, `repeat_interval`, `order_id`) VALUES
(488, 'sich artikuliert, verstÃ¤ndlich, sach- und situationsangemessen Ã¤uÃŸern', '', 94, 188, '2013-04-04 06:22:52', 0, -1, 0),
(490, 'Ã¼ber einen umfangreichen und differenzierten Wortschatz verfÃ¼gen', '', 94, 188, '2013-04-04 06:27:05', 0, -1, 1),
(491, 'verschiedene Formen mÃ¼ndlicher Darstellung unterscheiden und anwenden, insbesondere erzÃ¤hlen, berichten, informieren, beschreiben, schildern, appellieren, argumentieren, erÃ¶rtern', '', 94, 188, '2013-04-04 06:29:06', 0, -1, 2),
(492, 'Wirkungen der Redeweise kennen, beachten und situations- sowie adressatengerecht anwenden: LautstÃ¤rke, Betonung, Sprechtempo, Klangfarbe, StimmfÃ¼hrung; KÃ¶rpersprache (Gestik, Mimik)', '', 94, 188, '2013-04-04 06:29:18', 0, -1, 3),
(493, 'unterschiedliche Sprechsituationen gestalten, insbesondere VorstellungsgesprÃ¤ch/ BewerbungsgesprÃ¤ch; Antragstellung, Beschwerde, Entschuldigung; GesprÃ¤chsleitung.', '', 94, 188, '2013-04-04 06:29:30', 0, -1, 4),
(494, 'Texte sinngebend und gestaltend vorlesen und (frei) vortragen', '', 94, 190, '2013-04-04 06:29:56', 0, -1, 0),
(495, 'lÃ¤ngere freie RedebeitrÃ¤ge leisten, Kurzdarstellungen und Referate frei vortragen: ggf. mit Hilfe eines Stichwortzettels/einer Gliederung,', '', 94, 190, '2013-04-04 06:30:06', 0, -1, 1),
(496, 'verschiedene Medien fÃ¼r die Darstellung von Sachverhalten nutzen (PrÃ¤sentationstechniken): z.B. Tafel, Folie, Plakat, Moderationskarten', '', 94, 190, '2013-04-04 06:30:24', 0, -1, 2),
(497, 'sich konstruktiv an einem GesprÃ¤ch beteiligen', '', 94, 191, '2013-04-04 07:04:59', 0, -1, 3),
(498, 'durch gezieltes Fragen notwendige Informationen beschaffen', '', 94, 191, '2013-04-04 07:05:10', 0, -1, 4),
(499, 'GesprÃ¤chsregeln einhalten', '', 94, 191, '2013-04-04 07:05:52', 0, -1, 5),
(500, 'die eigene Meinung begrÃ¼ndet und nachvollziehbar vertreten', '', 94, 191, '2013-04-04 07:06:02', 0, -1, 6),
(501, 'auf Gegenpositionen sachlich und argumentierend eingehen', '', 94, 191, '2013-04-04 07:06:12', 0, -1, 7),
(502, 'kriterienorientiert das eigene GesprÃ¤chsverhalten und das anderer beobachten, reflektieren und bewerten', '', 94, 191, '2013-04-04 07:06:22', 0, -1, 8),
(503, 'GesprÃ¤chsbeitrÃ¤ge anderer verfolgen und aufnehmen', '', 94, 192, '2013-04-04 07:06:53', 0, -1, 0),
(504, 'wesentliche Aussagen aus umfangreichen gesprochenen Texten verstehen, diese Informationen sichern und wiedergeben', '', 94, 192, '2013-04-04 07:07:07', 0, -1, 1),
(505, 'Aufmerksamkeit fÃ¼r verbale und nonverbale Ã„uÃŸerungen (z.B. StimmfÃ¼hrung, KÃ¶rpersprache) entwickeln', '', 94, 192, '2013-04-04 07:07:32', 0, -1, 2),
(506, 'eigene Erlebnisse, Haltungen, Situationen szenisch darstellen', '', 94, 193, '2013-04-04 07:08:30', 0, -1, 3),
(507, 'Texte (medial unterschiedlich vermittelt) szenisch gestalten', '', 94, 193, '2013-04-04 07:08:41', 0, -1, 4),
(508, 'verschiedene GesprÃ¤chsformen praktizieren, z.B. Dialoge, StreitgesprÃ¤che, Diskussionen, Rollendiskussionen, Debatten vorbereiten und durchfÃ¼hren', '', 94, 194, '2013-04-04 07:09:23', 0, -1, 1),
(509, 'GesprÃ¤chsformen moderieren, leiten, beobachten, reflektieren', '', 94, 194, '2013-04-04 07:09:41', 0, -1, 1),
(510, 'Redestrategien einsetzen: z.B. FÃ¼nfsatz, AnknÃ¼pfungen formulieren, rhetorische Mittel verwenden', '', 94, 194, '2013-04-04 07:09:52', 0, -1, 2),
(511, 'sich gezielt sachgerechte StichwÃ¶rter aufschreiben', '', 94, 194, '2013-04-04 07:10:01', 0, -1, 3),
(512, 'eine Mitschrift anfertigen', '', 94, 194, '2013-04-04 07:10:10', 0, -1, 4),
(513, 'Notizen selbststÃ¤ndig strukturieren und Notizen zur Reproduktion des GehÃ¶rten nutzen, dabei sachlogische sprachliche VerknÃ¼pfungen herstellen', '', 94, 194, '2013-04-04 07:10:24', 0, -1, 5),
(514, 'Video-Feedback nutzen', '', 94, 194, '2013-04-04 07:10:35', 0, -1, 6),
(515, 'Portfolio (Sammlung und Vereinbarungen Ã¼ber GesprÃ¤chsregeln, Kriterienlisten, Stichwortkonzepte, SelbsteinschÃ¤tzungen, BeobachtungsbÃ¶gen von anderen, vereinbarte Lernziele etc.) nutzen.', '', 94, 194, '2013-04-04 07:11:00', 0, -1, 7),
(516, 'Texte in gut lesbarer handschriftlicher Form und in einem der Situation entsprechenden Tempo schreiben', '', 94, 195, '2013-04-04 07:11:45', 0, -1, 0),
(517, 'Texte dem Zweck entsprechend und adressatengerecht gestalten, sinnvoll aufbauen und strukturieren: z.B. Blattaufteilung, Rand, AbsÃ¤tze', '', 94, 195, '2013-04-04 07:11:57', 0, -1, 1),
(518, 'Textverarbeitungsprogramme und ihre MÃ¶glichkeiten nutzen: z.B. Formatierung, PrÃ¤sentation', '', 94, 195, '2013-04-04 07:12:06', 0, -1, 2),
(519, 'Formulare ausfÃ¼llen', '', 94, 195, '2013-04-04 07:12:16', 0, -1, 3),
(520, 'Grundregeln der Rechtschreibung und Zeichensetzung sicher beherrschen und hÃ¤ufig vorkommende WÃ¶rter, Fachbegriffe und FremdwÃ¶rter richtig schreiben', '', 94, 196, '2013-04-04 07:12:47', 0, -1, 0),
(521, 'individuelle Fehlerschwerpunkte erkennen und mit Hilfe von Rechtschreibstrategien abbauen, insbesondere Nachschlagen, Ableiten, Wortverwandtschaften suchen, grammatisches Wissen anwenden', '', 94, 196, '2013-04-04 07:13:07', 0, -1, 1),
(522, 'gemÃ¤ÃŸ den Aufgaben und der Zeitvorgabe einen Schreibplan erstellen, sich fÃ¼r die angemessene Textsorte entscheiden und Texte ziel-, adressaten- und situationsbezogen, ggf. materialorientiert konzipieren', '', 94, 197, '2013-04-04 07:14:30', 0, -1, 0),
(523, 'Informationsquellen gezielt nutzen, insbesondere Bibliotheken, Nachschlagewerke, Zeitungen, Internet', '', 94, 197, '2013-04-04 07:14:39', 0, -1, 1),
(524, 'Stoffsammlung erstellen, ordnen und eine Gliederung anfertigen: z.B. numerische Gliederung, Cluster, Ideenstern, Mindmap, Flussdiagramm', '', 94, 197, '2013-04-04 07:14:50', 0, -1, 2),
(525, 'formalisierte lineare Texte/nichtlineare Texte verfassen: z.B. sachlicher Brief, Lebenslauf, Bewerbung, Bewerbungsschreiben, Protokoll, Annonce/AusfÃ¼llen von Formularen, Diagramm, Schaubild, Statistik', '', 94, 198, '2013-04-04 07:15:49', 0, -1, 0),
(526, 'zentrale Schreibformen beherrschen und sachgerecht nutzen: informierende (berichten, beschreiben, schildern), argumentierende (erÃ¶rtern, kommentieren), appellierende, untersuchende (analysieren, interpretieren), gestaltende (erzÃ¤hlen, kreativ schreiben)', '', 94, 198, '2013-04-04 07:16:17', 0, -1, 1),
(527, 'produktive Schreibformen nutzen: z.B. umschreiben, weiterschreiben, ausgestalten', '', 94, 198, '2013-04-04 07:16:30', 0, -1, 2),
(528, 'Ergebnisse einer Textuntersuchung darstellen: z.B. â€“ Inhalte auch lÃ¤ngerer und komplexerer Texte verkÃ¼rzt und abstrahierend wiedergeben', '', 94, 198, '2013-04-04 07:17:03', 0, -1, 3),
(529, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Informationen aus linearen und nichtlinearen Texten zusammenfassen und so wiedergeben, dass insgesamt eine kohÃ¤rente Darstellung entsteht', '', 94, 198, '2013-04-04 07:18:50', 0, -1, 4),
(530, 'Ergebnisse einer Textuntersuchung darstellen: z.B. formale und sprachlich stilistische Gestaltungsmittel und ihre Wirkungsweise an Beispielen darstellen', '', 94, 198, '2013-04-04 07:19:08', 0, -1, 5),
(531, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Textdeutungen begrÃ¼nden', '', 94, 198, '2013-04-04 07:19:25', 0, -1, 6),
(532, 'Ergebnisse einer Textuntersuchung darstellen: z.B. sprachliche Bilder deuten', '', 94, 198, '2013-04-04 07:19:40', 0, -1, 7),
(533, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Thesen formulieren', '', 94, 198, '2013-04-04 07:22:25', 0, -1, 8),
(534, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Argumente zu einer Argumentationskette verknÃ¼pfen', '', 94, 198, '2013-04-04 07:22:46', 0, -1, 9),
(535, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Gegenargumente formulieren, Ã¼berdenken und einbeziehen', '', 94, 198, '2013-04-04 07:23:02', 0, -1, 10),
(536, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Argumente gewichten und SchlÃ¼sse ziehen', '', 94, 198, '2013-04-04 07:23:14', 0, -1, 11),
(537, 'Ergebnisse einer Textuntersuchung darstellen: z.B. begrÃ¼ndet Stellung nehmen', '', 94, 198, '2013-04-04 07:23:27', 0, -1, 12),
(538, 'Texte sprachlich gestalten â€“ strukturiert, verstÃ¤ndlich, sprachlich variabel und stilistisch stimmig zur Aussage schreiben', '', 94, 198, '2013-04-04 07:23:45', 0, -1, 13),
(539, 'Texte sprachlich gestalten â€“ sprachliche Mittel gezielt einsetzen: z.B. Vergleiche, Bilder, Wiederholung,', '', 94, 198, '2013-04-04 07:24:11', 0, -1, 14),
(540, 'Texte mit Hilfe von neuen Medien verfassen: z.B. E-Mails, Chatroom', '', 94, 198, '2013-04-04 07:24:27', 0, -1, 15),
(541, 'Aufbau, Inhalt und Formulierungen eigener Texte hinsichtlich der Aufgabenstellung Ã¼berprÃ¼fen (Schreibsituation, Schreibanlass)', '', 94, 199, '2013-04-04 07:25:15', 0, -1, 0),
(542, 'Strategien zur ÃœberprÃ¼fung der sprachlichen Richtigkeit und Rechtschreibung anwenden', '', 94, 199, '2013-04-04 07:25:29', 0, -1, 1),
(543, 'Vorgehensweise aus Aufgabenstellung herleiten', '', 94, 200, '2013-04-04 07:25:58', 0, -1, 0),
(544, 'ArbeitsplÃ¤ne/Konzepte entwerfen, Arbeitsschritte festlegen: Informationen sammeln, ordnen, ergÃ¤nzen', '', 94, 200, '2013-04-04 07:26:09', 0, -1, 1),
(545, 'Fragen und Arbeitshypothesen formulieren', '', 94, 200, '2013-04-04 07:26:19', 0, -1, 2),
(546, 'Texte inhaltlich und sprachlich Ã¼berarbeiten: z. B. Textpassagen umstellen, Wirksamkeit und Angemessenheit sprachlicher Gestaltungsmittel prÃ¼fen', '', 94, 200, '2013-04-04 07:26:38', 0, -1, 3),
(547, 'Zitate in den eigenen Text integrieren', '', 94, 200, '2013-04-04 07:26:48', 0, -1, 4),
(548, 'Einhaltung orthografischer und grammatischer Normen kontrollieren', '', 94, 200, '2013-04-04 07:26:59', 0, -1, 5),
(549, 'mit Textverarbeitungsprogrammen umgehen', '', 94, 200, '2013-04-04 07:27:09', 0, -1, 6),
(550, 'Schreibkonferenzen/ Schreibwerkstatt durchfÃ¼hren', '', 94, 200, '2013-04-04 07:27:21', 0, -1, 7),
(551, 'Portfolio (selbst verfasste und fÃ¼r gut befundene Texte, Kriterienlisten, Stichwortkonzepte, SelbsteinschÃ¤tzungen, BeobachtungsbÃ¶gen von anderen, vereinbarte Lernziele etc.) anlegen und nutzen', '', 94, 200, '2013-04-04 07:27:36', 0, -1, 8),
(552, 'Ã¼ber grundlegende Lesefertigkeiten verfÃ¼gen: flÃ¼ssig, sinnbezogen, Ã¼berfliegend, selektiv, navigierend (z.B. Bild-Ton-Text integrierend) lesen', '', 94, 201, '2013-04-04 07:28:38', 0, -1, 0),
(553, 'Leseerwartungen und -erfahrungen bewusst nutzen', '', 94, 202, '2013-04-04 07:29:07', 0, -1, 0),
(554, 'Wortbedeutungen klÃ¤ren', '', 94, 202, '2013-04-04 07:29:17', 0, -1, 1),
(555, 'Textschemata erfassen: z.B. Textsorte, Aufbau des Textes', '', 94, 202, '2013-04-04 07:29:26', 0, -1, 2),
(556, 'Verfahren zur Textstrukturierung kennen und selbststÃ¤ndig anwenden: z.B. ZwischenÃ¼berschriften formulieren, wesentliche Textstellen kennzeichnen, BezÃ¼ge zwischen Textteilen herstellen, Fragen aus dem Text ableiten und beantworten', '', 94, 202, '2013-04-04 07:29:42', 0, -1, 3),
(557, 'Verfahren zur Textaufnahme kennen und nutzen: z.B. Aussagen erklÃ¤ren und konkretisieren, StichwÃ¶rter formulieren, Texte und Textabschnitte zusammenfassen', '', 94, 202, '2013-04-04 07:29:59', 0, -1, 4),
(558, 'ein Spektrum altersangemessener Werke â€“ auch Jugendliteratur â€“ bedeutender Autorinnen und Autoren kennen', '', 94, 203, '2013-04-04 07:31:29', 0, -1, 0),
(559, 'epische, lyrische, dramatische Texte unterscheiden, insbesondere epische Kleinformen, Novelle, lÃ¤ngere ErzÃ¤hlung, Kurzgeschichte, Roman, Schauspiel, Gedichte', '', 94, 203, '2013-04-04 07:31:50', 0, -1, 1),
(560, 'ZusammenhÃ¤nge zwischen Text, Entstehungszeit und Leben des Autors/der Autorin bei der Arbeit an Texten aus Gegenwart und Vergangenheit herstellen', '', 94, 203, '2013-04-04 07:32:07', 0, -1, 2),
(561, 'zentrale Inhalte erschlieÃŸen', '', 94, 203, '2013-04-04 07:32:19', 0, -1, 3),
(562, 'wesentliche Elemente eines Textes erfassen: z.B. Figuren, Raum- und Zeitdarstellung, Konfliktverlauf', '', 94, 203, '2013-04-04 07:32:35', 0, -1, 4),
(563, 'wesentliche Fachbegriffe zur ErschlieÃŸung von Literatur kennen und anwenden, insbesondere ErzÃ¤hler, ErzÃ¤hlperspektive, Monolog, Dialog, sprachliche Bilder, Metapher, Reim, lyrisches Ich', '', 94, 203, '2013-04-04 07:32:47', 0, -1, 5),
(564, 'sprachliche Gestaltungsmittel in ihren WirkungszusammenhÃ¤ngen und in ihrer historischen Bedingtheit erkennen: z.B. Wort-, Satz- und Gedankenfiguren, Bildsprache (Metaphern),', '', 94, 203, '2013-04-04 07:33:02', 0, -1, 6),
(565, 'eigene Deutungen des Textes entwickeln, am Text belegen und sich mit anderen darÃ¼ber verstÃ¤ndigen', '', 94, 203, '2013-04-04 07:33:20', 0, -1, 7),
(566, 'analytische Methoden anwenden: z.B. Texte untersuchen, vergleichen, kommentieren', '', 94, 203, '2013-04-04 07:33:31', 0, -1, 8),
(567, 'produktive Methoden anwenden: z.B. Perspektivenwechsel: innerer Monolog, Brief in der Rolle einer literarischen Figur; szenische Umsetzung, Paralleltext, weiterschreiben, in eine andere Textsorte umschreiben', '', 94, 203, '2013-04-04 07:33:45', 0, -1, 9),
(568, 'Handlungen, Verhaltensweisen und Verhaltensmotive bewerten', '', 94, 203, '2013-04-04 07:33:56', 0, -1, 10),
(569, 'verschiedene Textfunktionen und Textsorten unterscheiden: z.B. informieren: Nachricht; appellieren: Kommentar, Rede; regulieren: Gesetz, Vertrag; instruieren: Gebrauchsanweisung,', '', 94, 204, '2013-04-04 07:34:35', 0, -1, 0),
(570, 'ein breites Spektrum auch lÃ¤ngerer und komplexerer Texte verstehen und im Detail erfassen', '', 94, 204, '2013-04-04 07:34:44', 0, -1, 1),
(571, 'Informationen zielgerichtet entnehmen, ordnen, vergleichen, prÃ¼fen und ergÃ¤nzen', '', 94, 204, '2013-04-04 07:34:55', 0, -1, 2),
(572, 'nichtlineare Texte auswerten: z.B. Schaubilder', '', 94, 204, '2013-04-04 07:35:05', 0, -1, 3),
(573, 'ntention(en) eines Textes erkennen, insbesondere Zusammenhang zwischen Autorintention(en), Textmerkmalen, Leseerwartungen und Wirkungen', '', 94, 204, '2013-04-04 07:35:14', 0, -1, 4),
(574, 'aus Sach- und Gebrauchstexten begrÃ¼ndete Schlussfolgerungen ziehen', '', 94, 204, '2013-04-04 07:35:27', 0, -1, 5),
(575, 'Information und Wertung in Texten unterscheiden', '', 94, 204, '2013-04-04 07:35:37', 0, -1, 6),
(576, 'Informations- und Unterhaltungsfunktion unterscheiden', '', 94, 205, '2013-04-04 07:36:02', 0, -1, 0),
(577, 'medienspezifische Formen kennen: z.B. Print- und Online-Zeitungen, Infotainment, Hypertexte, Werbekommunikation, Film', '', 94, 205, '2013-04-04 07:36:13', 0, -1, 1),
(578, 'Intentionen und Wirkungen erkennen und bewerten', '', 94, 205, '2013-04-04 07:36:22', 0, -1, 2),
(579, 'wesentliche Darstellungsmittel kennen und deren Wirkungen einschÃ¤tzen', '', 94, 205, '2013-04-04 07:36:32', 0, -1, 3),
(580, 'zwischen eigentlicher Wirklichkeit und virtuellen Welten in Medien unterscheiden: z.B. Fernsehserien, Computerspiele', '', 94, 205, '2013-04-04 07:36:46', 0, -1, 4),
(581, 'InformationsmÃ¶glichkeiten nutzen: z.B. Informationen zu einem Thema/ Problem in unterschiedlichen Medien suchen, vergleichen, auswÃ¤hlen und bewerten (Suchstrategien)', '', 94, 205, '2013-04-04 07:37:04', 0, -1, 5),
(582, 'Medien zur PrÃ¤sentation und Ã¤sthetischen Produktion nutzen', '', 94, 205, '2013-04-04 07:37:18', 0, -1, 6),
(583, 'Exzerpieren, Zitieren, Quellen angeben', '', 94, 206, '2013-04-04 07:38:02', 0, -1, 0),
(584, 'Wesentliches hervorheben und ZusammenhÃ¤nge verdeutlichen', '', 94, 206, '2013-04-04 07:38:11', 0, -1, 1),
(585, 'Nachschlagewerke zur KlÃ¤rung von Fachbegriffen, FremdwÃ¶rtern und Sachfragen heranziehen', '', 94, 206, '2013-04-04 07:38:20', 0, -1, 2),
(586, 'Texte zusammenfassen: z.B. im Nominalstil, mit Hilfe von StichwÃ¶rtern, Symbolen, Farbmarkierungen, Unterstreichungen', '', 94, 206, '2013-04-04 07:38:30', 0, -1, 3),
(587, 'Inhalte mit eigenen Worten wiedergeben, Randbemerkungen setzen', '', 94, 206, '2013-04-04 07:38:41', 0, -1, 4),
(588, 'Texte gliedern und TeilÃ¼berschriften finden', '', 94, 206, '2013-04-04 07:38:58', 0, -1, 5),
(589, 'Inhalte veranschaulichen: z. B. durch Mindmap, Flussdiagramm', '', 94, 206, '2013-04-04 07:39:10', 0, -1, 6),
(590, 'PrÃ¤sentationstechniken anwenden: Medien zielgerichtet und sachbezogen einsetzen: z.B. Tafel, Folie, Plakat, PC-PrÃ¤sentationsprogramm', '', 94, 206, '2013-04-04 07:48:57', 0, -1, 7),
(592, 'nutzen sinntragende Vorstellungen von rationalen Zahlen, insbesonÂ­dere von natÃ¼rlichen, ganzen und gebrochenen Zahlen entsprechend der Verwendungsnotwendigkeit', '', 89, 207, '2013-04-04 09:10:53', 0, -1, 0),
(593, 'tellen Zahlen der Situation angemessen dar, unter anderem in ZehÂ­nerpotenzschreibweise', '', 89, 207, '2013-04-04 09:11:26', 0, -1, 1),
(594, 'begrÃ¼nden die Notwendigkeit von Zahlbereichserweiterungen an Beispielen', '', 89, 207, '2013-04-04 09:11:37', 0, -1, 2),
(595, 'nutzen Rechengesetze, auch zum vorteilhaften Rechnen', '', 89, 207, '2013-04-04 09:11:46', 0, -1, 3),
(596, 'nutzen zur Kontrolle Ãœberschlagsrechnungen und andere Verfahren', '', 89, 207, '2013-04-04 09:11:55', 0, -1, 4),
(597, 'runden Rechenergebnisse entsprechend dem Sachverhalt sinnvoll', '', 89, 207, '2013-04-04 09:12:06', 0, -1, 5),
(598, 'verwenden Prozent- und Zinsrechnung sachgerecht', '', 89, 207, '2013-04-04 09:12:20', 0, -1, 6),
(599, 'erlÃ¤utern an Beispielen den Zusammenhang zwischen RechenoperaÂ­tionen und deren Umkehrungen und nutzen diese ZusammenhÃ¤nge', '', 89, 207, '2013-04-04 09:12:33', 0, -1, 7),
(600, 'wÃ¤hlen, beschreiben und bewerten Vorgehensweisen und Verfahren, denen Algorithmen bzw. KalkÃ¼le zu Grunde liegen', '', 89, 207, '2013-04-04 09:12:44', 0, -1, 8),
(601, 'fÃ¼hren in konkreten Situationen kombinatorische Ãœberlegungen durch, um die Anzahl der jeweiligen MÃ¶glichkeiten zu bestimmen', '', 89, 207, '2013-04-04 09:12:55', 0, -1, 9),
(602, 'prÃ¼fen und interpretieren Ergebnisse in Sachsituationen unter EinbeÂ­ziehung einer kritischen EinschÃ¤tzung des gewÃ¤hlten Modells und seiner Bearbeitung', '', 89, 207, '2013-04-04 09:13:08', 0, -1, 10),
(603, 'nutzen das Grundprinzip des Messens, insbesondere bei der LÃ¤ngen-, FlÃ¤chen- und Volumenmessung, auch in Naturwissenschaften und in anderen Bereichen', '', 89, 208, '2013-04-04 09:14:04', 0, -1, 0),
(604, 'wÃ¤hlen Einheiten von GrÃ¶ÃŸen situationsgerecht aus (insbesondere fÃ¼r Zeit, Masse, Geld, LÃ¤nge, FlÃ¤che, Volumen und Winkel)', '', 89, 208, '2013-04-04 09:14:54', 0, -1, 1),
(605, 'schÃ¤tzen GrÃ¶ÃŸen mit Hilfe von Vorstellungen Ã¼ber geeignete ReprÃ¤Â­sentanten', '', 89, 208, '2013-04-04 09:15:05', 0, -1, 2),
(606, 'berechnen FlÃ¤cheninhalt und Umfang von Rechteck, Dreieck und Kreis sowie daraus zusammengesetzten Figuren', '', 89, 208, '2013-04-04 09:15:14', 0, -1, 3),
(607, 'berechnen Volumen und OberflÃ¤cheninhalt von Prisma, Pyramide, Zylinder, Kegel und Kugel sowie daraus zusammengesetzten KÃ¶rÂ­pern', '', 89, 208, '2013-04-04 09:15:24', 0, -1, 4),
(608, 'berechnen StreckenlÃ¤ngen und WinkelgrÃ¶ÃŸen, auch unter Nutzung von trigonometrischen Beziehungen und Ã„hnlichkeitsbeziehungen', '', 89, 208, '2013-04-04 09:15:33', 0, -1, 5),
(609, 'nehmen in ihrer Umwelt gezielt Messungen vor, entnehmen MaÃŸangaÂ­ben aus Quellenmaterial, fÃ¼hren damit Berechnungen durch und beÂ­werten die Ergebnisse sowie den gewÃ¤hlten Weg in Bezug auf die Sachsituation', '', 89, 208, '2013-04-04 09:15:50', 0, -1, 6),
(610, 'erkennen und beschreiben geometrische Strukturen in der Umwelt', '', 89, 209, '2013-04-04 09:16:40', 0, -1, 0),
(611, 'operieren gedanklich mit Strecken, FlÃ¤chen und KÃ¶rpern', '', 89, 209, '2013-04-04 09:16:53', 0, -1, 1),
(612, 'stellen geometrische Figuren im kartesischen Koordinatensystem dar', '', 89, 209, '2013-04-04 09:17:01', 0, -1, 0),
(613, 'stellen KÃ¶rper (z. B. als Netz, SchrÃ¤gbild oder Modell) dar und erkenÂ­nen KÃ¶rper aus ihren entsprechenden Darstellungen', '', 89, 209, '2013-04-04 09:17:13', 0, -1, 2),
(614, 'analysieren und klassifizieren geometrische Objekte der Ebene und des Raumes', '', 89, 209, '2013-04-04 09:17:22', 0, -1, 3),
(615, 'beschreiben und begrÃ¼nden Eigenschaften und Beziehungen geometÂ­rischer Objekte (wie Symmetrie, Kongruenz, Ã„hnlichkeit, LagebezieÂ­hungen) und nutzen diese im Rahmen des ProblemlÃ¶sens zur Analyse von SachzusammenhÃ¤ngen', '', 89, 209, '2013-04-04 09:17:41', 0, -1, 4),
(616, 'wenden SÃ¤tze der ebenen Geometrie bei Konstruktionen, BerechnunÂ­gen und Beweisen an, insbesondere den Satz des Pythagoras und den Satz des Thales', '', 89, 209, '2013-04-04 09:18:01', 0, -1, 5),
(617, 'zeichnen und konstruieren geometrische Figuren unter Verwendung angemessener Hilfsmittel wie Zirkel, Lineal, Geodreieck oder dynaÂ­mische Geometriesoftware,', '', 89, 209, '2013-04-04 09:18:15', 0, -1, 6),
(618, 'untersuchen Fragen der LÃ¶sbarkeit und LÃ¶sungsvielfalt von KonÂ­struktionsaufgaben und formulieren diesbezÃ¼glich Aussagen', '', 89, 209, '2013-04-04 09:18:29', 0, -1, 7),
(619, 'setzen geeignete Hilfsmittel beim explorativen Arbeiten und ProbÂ­lemlÃ¶sen ein', '', 89, 209, '2013-04-04 09:18:49', 0, -1, 8),
(620, 'nutzen Funktionen als Mittel zur Beschreibung quantitativer ZusamÂ­ menhÃ¤nge', '', 89, 210, '2013-04-04 09:19:32', 0, -1, 0),
(621, 'erkennen und beschreiben funktionale ZusammenhÃ¤nge und stellen diese in sprachlicher, tabellarischer oder graphischer Form sowie geÂ­gebenenfalls als Term dar', '', 89, 210, '2013-04-04 09:20:02', 0, -1, 1),
(622, 'analysieren, interpretieren und vergleichen unterschiedliche DarstelÂ­lungen funktionaler ZusammenhÃ¤nge (wie lineare, proportionale und antiproportionale)', '', 89, 210, '2013-04-04 09:20:23', 0, -1, 2),
(623, 'lÃ¶sen realitÃ¤tsnahe Probleme im Zusammenhang mit linearen, proÂ­portionalen und antiproportionalen Zuordnungen', '', 89, 210, '2013-04-04 09:20:37', 0, -1, 3),
(624, 'interpretieren lineare Gleichungssysteme graphisch', '', 89, 210, '2013-04-04 09:20:50', 0, -1, 4),
(625, 'lÃ¶sen Gleichungen, und lineare Gleichungssysteme kalkÃ¼lmÃ¤ÃŸig bzw. algorithmisch, auch unter Einsatz geeigneter Software, und vergleiÂ­chen ggf. die EffektivitÃ¤t ihres Vorgehens mit anderen LÃ¶sungsverÂ­fahren (wie mit inhaltlichem LÃ¶sen oder LÃ¶sen durch systematisches Probieren)', '', 89, 210, '2013-04-04 09:21:09', 0, -1, 5),
(626, 'untersuchen Fragen der LÃ¶sbarkeit und LÃ¶sungsvielfalt von linearen und quadratischen Gleichungen sowie linearen Gleichungssystemen und formulieren diesbezÃ¼glich Aussagen', '', 89, 210, '2013-04-04 09:21:19', 0, -1, 6),
(627, 'bestimmen kennzeichnende Merkmale von Funktionen und stellen Beziehungen zwischen Funktionsterm und Graph her', '', 89, 210, '2013-04-04 09:21:30', 0, -1, 7),
(628, 'wenden insbesondere lineare und quadratische Funktionen sowie ExÂ­ponentialfunktionen bei der Beschreibung und Bearbeitung von Problemen an', '', 89, 210, '2013-04-04 09:21:44', 0, -1, 8),
(629, 'verwenden die Sinusfunktion zur Beschreibung von periodischen VorgÃ¤ngen', '', 89, 210, '2013-04-04 09:21:54', 0, -1, 9),
(630, 'beschreiben VerÃ¤nderungen von GrÃ¶ÃŸen mittels Funktionen, auch unÂ­ter Verwendung eines Tabellenkalkulationsprogramms', '', 89, 210, '2013-04-04 09:22:08', 0, -1, 10),
(631, 'geben zu vorgegebenen Funktionen Sachsituationen an, die mit Hilfe dieser Funktion beschrieben werden kÃ¶nnen.', '', 89, 210, '2013-04-04 09:22:18', 0, -1, 11),
(632, 'werten graphische Darstellungen und Tabellen von statistischen Erhebungen aus', '', 89, 211, '2013-04-04 09:22:55', 0, -1, 0),
(633, 'planen statistische Erhebungen', '', 89, 211, '2013-04-04 09:23:04', 0, -1, 1),
(634, 'ammeln systematisch Daten, erfassen sie in Tabellen und stellen sie graphisch dar, auch unter Verwendung geeigneter Hilfsmittel (wie Software)', '', 89, 211, '2013-04-04 09:23:13', 0, -1, 2),
(635, 'interpretieren Daten unter Verwendung von KenngrÃ¶ÃŸen', '', 89, 211, '2013-04-04 09:23:19', 0, -1, 3),
(636, 'reflektieren und bewerten Argumente, die auf einer Datenanalyse baÂ­sieren', '', 89, 211, '2013-04-04 09:23:27', 0, -1, 4),
(637, 'beschreiben Zufallserscheinungen in alltÃ¤glichen Situationen', '', 89, 211, '2013-04-04 09:23:34', 0, -1, 5),
(638, 'bestimmen Wahrscheinlichkeiten bei Zufallsexperimenten', '', 89, 211, '2013-04-04 09:23:43', 0, -1, 6),
(639, 'Fragen stellen, die fÃ¼r die Mathematik charakteristisch sind (â€žGibt es ...?â€œ, â€žWie verÃ¤ndert sich...?â€œ, â€žIst das immer so ...?â€œ) und VermutunÂ­gen begrÃ¼ndet Ã¤uÃŸern', '', 89, 212, '2013-04-04 09:25:36', 0, -1, 0),
(640, 'mathematische Argumentationen entwickeln (wie ErlÃ¤uterungen, BeÂ­ grÃ¼ndungen, Beweise)', '', 89, 212, '2013-04-04 09:25:47', 0, -1, 1),
(641, 'LÃ¶sungswege beschreiben und begrÃ¼nden', '', 89, 212, '2013-04-04 09:25:57', 0, -1, 2),
(642, 'vorgegebene und selbst formulierte Probleme bearbeiten', '', 89, 213, '2013-04-04 09:26:56', 0, -1, 3),
(643, 'geeignete heuristische Hilfsmittel, Strategien und Prinzipien zum ProblemlÃ¶sen auswÃ¤hlen und anwenden,', '', 89, 213, '2013-04-04 09:27:32', 0, -1, 4),
(644, 'die PlausibilitÃ¤t der Ergebnisse Ã¼berprÃ¼fen sowie das Finden von LÃ¶sungsideen und die LÃ¶sungswege reflektieren', '', 89, 213, '2013-04-04 09:27:45', 0, -1, 5),
(645, 'den Bereich oder die Situation, die modelliert werden soll, in matheÂ­matische Begriffe, Strukturen und Relationen Ã¼bersetzen', '', 89, 214, '2013-04-04 09:28:25', 0, -1, 0),
(646, 'den Bereich oder die Situation, die modelliert werden soll, in mathematische Begriffe, Strukturen und Relationen Ã¼bersetzen', '', 89, 214, '2013-04-04 09:29:01', 0, -1, 1),
(647, 'Ergebnisse in dem entsprechenden Bereich oder der entsprechenden Situation interpretieren und prÃ¼fen', '', 89, 214, '2013-04-04 09:29:10', 0, -1, 2),
(648, 'verschiedene Formen der Darstellung von mathematischen Objekten und Situationen anwenden, interpretieren und unterscheiden', '', 89, 215, '2013-04-04 09:29:38', 0, -1, 0),
(649, 'Beziehungen zwischen Darstellungsformen erkennen', '', 89, 215, '2013-04-04 09:29:46', 0, -1, 1),
(650, 'unterschiedliche Darstellungsformen je nach Situation und Zweck auswÃ¤hlen und zwischen ihnen wechseln', '', 89, 215, '2013-04-04 09:29:51', 0, -1, 2),
(651, 'mit Variablen, Termen, Gleichungen, Funktionen, Diagrammen, Tabellen arbeiten', '', 89, 216, '2013-04-04 09:30:34', 0, -1, 0),
(652, 'symbolische und formale Sprache in natÃ¼rliche Sprache Ã¼bersetzen und umgekehrt', '', 89, 216, '2013-04-04 09:30:41', 0, -1, 1),
(653, 'LÃ¶sungs- und Kontrollverfahren ausfÃ¼hren', '', 89, 216, '2013-04-04 09:30:48', 0, -1, 2),
(654, 'mathematische Werkzeuge (wie Formelsammlungen, Taschenrechner, Software) sinnvoll und verstÃ¤ndig einsetzen', '', 89, 216, '2013-04-04 09:31:23', 0, -1, 3),
(655, 'Ìˆberlegungen, LÃ¶sungswege bzw. Ergebnisse dokumentieren, verstÃ¤ndlich darstellen und prÃ¤sentieren, auch unter Nutzung geeigneter Medien', '', 89, 217, '2013-04-04 09:31:33', 0, -1, 0),
(656, 'die Fachsprache adressatengerecht verwenden', '', 89, 217, '2013-04-04 09:31:44', 0, -1, 1),
(657, 'Ã„uÃŸerungen von anderen und Texte zu mathematischen Inhalten verstehen und Ã¼berprÃ¼fen', '', 89, 217, '2013-04-04 09:31:53', 0, -1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `context_id` int(10) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `creator_id` int(10) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(5) DEFAULT NULL,
  `cur_id` int(11) NOT NULL DEFAULT '-1',
  `ena_id` int(11) DEFAULT '-1',
  `ter_id` int(11) DEFAULT '-1',
  `description` varchar(2048) DEFAULT NULL,
  `title` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=149 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `files`
--

INSERT INTO `files` (`id`, `context_id`, `path`, `filename`, `creator_id`, `creation_time`, `type`, `cur_id`, `ena_id`, `ter_id`, `description`, `title`) VALUES
(122, 5, '', '01a_art.png', 77, '2012-12-30 21:04:34', '.png', -1, -1, -1, NULL, 'Kunst'),
(123, 5, '', '16b_music.png', 77, '2012-12-30 21:08:06', '.png', -1, -1, -1, NULL, 'Musik'),
(124, 5, '', 'undefined.png', 77, '2012-12-30 21:28:39', '.png', -1, -1, -1, NULL, 'Informatik'),
(125, 5, '', '14b_math.png', 77, '2012-12-30 21:42:18', '.png', -1, -1, -1, NULL, 'Mathematik'),
(126, 5, '', '12c_ict.png', 77, '2012-12-30 21:43:55', '.png', -1, -1, -1, NULL, 'Informatik'),
(127, 5, '', '09b_geography.png', 77, '2012-12-30 21:45:23', '.png', -1, -1, -1, NULL, 'Geografie'),
(128, 5, '', '09c_geography.png', 77, '2012-12-30 21:46:42', '.png', -1, -1, -1, NULL, 'Geografie'),
(129, 5, '', '15_mlf.png', 77, '2012-12-30 21:47:45', '.png', -1, -1, -1, NULL, 'Geschichte'),
(130, 5, '', '19c_science.png', 77, '2012-12-30 21:49:21', '.png', -1, -1, -1, NULL, 'Chemie'),
(131, 5, '', '19d_science.png', 77, '2012-12-30 21:49:53', '.png', -1, -1, -1, NULL, 'Physik'),
(132, 5, '', '19e_science.png', 77, '2012-12-30 21:50:18', '.png', -1, -1, -1, NULL, 'Biologie');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `institution_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `grade`
--

INSERT INTO `grade` (`id`, `grade`, `description`, `creation_time`, `creator_id`, `institution_id`) VALUES
(1, '1. Klasse', '1. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 77, 56),
(2, '2. Klasse', '2. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 77, 56),
(3, '3. Klasse', '3. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 77, 56),
(4, '4. Klasse', '4. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 77, 56),
(5, '5. Klasse', '5. Klassenstufe (Orientierungsstufe)', '0000-00-00 00:00:00', 77, 56),
(6, '6. Klasse', '6. Klassenstufe (Orientierungsstufe)', '0000-00-00 00:00:00', 77, 56),
(7, '7. Klasse', '7. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 77, 56),
(8, '8. Klasse', '8. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 77, 56),
(9, '9. Klasse', '9. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 77, 56),
(10, '10. Klasse', '10. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 77, 56),
(11, '11. Klasse', '11. Klassenstufe (Sek. II)', '0000-00-00 00:00:00', 77, 56),
(12, '12. Klasse', '12. Klassenstufe (Sek. II)', '0000-00-00 00:00:00', 77, 56),
(13, '13. Klasse', '13. Klassenstufe (Sek. II)', '0000-00-00 00:00:00', 77, 56);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groups` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `grade_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `groups`
--

INSERT INTO `groups` (`id`, `groups`, `description`, `grade_id`, `semester_id`, `institution_id`, `creation_time`, `creator_id`) VALUES
(94, 'Laptopklasse 9D', 'Realschulprofil Klasse', 9, 4, 56, '2013-09-07 16:54:03', 77);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groups_enrolments`
--

DROP TABLE IF EXISTS `groups_enrolments`;
CREATE TABLE IF NOT EXISTS `groups_enrolments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expel_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `groups_enrolments`
--

INSERT INTO `groups_enrolments` (`id`, `status`, `group_id`, `user_id`, `creation_time`, `expel_time`, `creator_id`) VALUES
(19, 1, 94, 91, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(20, 1, 94, 83, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(21, 1, 94, 88, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(22, 1, 94, 90, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(23, 1, 94, 79, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(24, 1, 94, 97, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(25, 1, 94, 87, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(26, 1, 94, 89, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(27, 1, 94, 78, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(28, 1, 94, 95, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(29, 1, 94, 85, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(30, 1, 94, 98, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(31, 1, 94, 99, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(32, 1, 94, 93, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(33, 1, 94, 92, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(34, 1, 94, 81, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(35, 1, 94, 96, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(36, 1, 94, 80, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(37, 1, 94, 82, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(38, 1, 94, 86, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(39, 1, 94, 94, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(40, 1, 94, 101, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(41, 1, 94, 84, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77),
(42, 1, 94, 100, '2013-09-07 16:54:46', '0000-00-00 00:00:00', 77);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `institution`
--

DROP TABLE IF EXISTS `institution`;
CREATE TABLE IF NOT EXISTS `institution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `confirmed` int(11) NOT NULL DEFAULT '1',
  `institution` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `schooltype_id` int(11) NOT NULL,
  `country_id` char(2) NOT NULL DEFAULT 'DE',
  `state_id` int(11) NOT NULL DEFAULT '11',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `institution`
--

INSERT INTO `institution` (`id`, `confirmed`, `institution`, `description`, `schooltype_id`, `country_id`, `state_id`, `creation_time`, `creator_id`) VALUES
(56, 1, 'Testschule', 'curriculum DEMO', 3, '56', 11, '2013-09-07 07:48:50', 77);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `institution_enrolments`
--

DROP TABLE IF EXISTS `institution_enrolments`;
CREATE TABLE IF NOT EXISTS `institution_enrolments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `institution_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expeled_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_INSTITUTION_ID` (`institution_id`),
  KEY `IDX_USER_ID` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=126 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `institution_enrolments`
--

INSERT INTO `institution_enrolments` (`id`, `status`, `institution_id`, `user_id`, `creation_time`, `expeled_time`, `creator_id`) VALUES
(101, 0, 56, 77, '2013-09-07 07:49:12', '0000-00-00 00:00:00', -1),
(102, 0, 56, 78, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(103, 0, 56, 79, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(104, 0, 56, 80, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(105, 0, 56, 81, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(106, 0, 56, 82, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(107, 0, 56, 83, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(108, 0, 56, 84, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(109, 0, 56, 85, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(110, 0, 56, 86, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(111, 0, 56, 87, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(112, 0, 56, 88, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(113, 0, 56, 89, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(114, 0, 56, 90, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(115, 0, 56, 91, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(116, 0, 56, 92, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(117, 0, 56, 93, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(118, 0, 56, 94, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(119, 0, 56, 95, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(120, 0, 56, 96, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(121, 0, 56, 97, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(122, 0, 56, 98, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(123, 0, 56, 99, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(124, 0, 56, 100, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77),
(125, 0, 56, 101, '2013-09-07 07:49:36', '0000-00-00 00:00:00', 77);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `action` varchar(40) NOT NULL,
  `url` varchar(250) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=210 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `log`
--

INSERT INTO `log` (`id`, `creation_time`, `user_id`, `ip`, `action`, `url`, `info`) VALUES
(207, '2013-09-07 07:49:21', 77, '::1', 'view', 'http://localhost/curriculum/public/index.php?action=dashboard', 'dashboard'),
(208, '2013-09-07 16:34:49', 77, '::1', 'view', 'http://localhost/curriculum/public/index.php?action=dashboard', 'dashboard'),
(209, '2013-09-07 16:55:39', 77, '::1', 'view', 'http://localhost/curriculum/public/index.php?action=Log', 'Log');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schooltype`
--

DROP TABLE IF EXISTS `schooltype`;
CREATE TABLE IF NOT EXISTS `schooltype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schooltype` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `country_id` char(2) NOT NULL DEFAULT 'DE',
  `state_id` int(11) NOT NULL DEFAULT '11',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `schooltype`
--

INSERT INTO `schooltype` (`id`, `schooltype`, `description`, `country_id`, `state_id`, `creation_time`, `creator_id`) VALUES
(1, 'Realschule plus', 'Realschule plus', 'DE', 11, '0000-00-00 00:00:00', 77),
(2, 'IGS', 'Integrierte Gesamtschule', 'DE', 11, '0000-00-00 00:00:00', 77),
(3, 'Gymnasium', 'Gymnasium', 'DE', 11, '0000-00-00 00:00:00', 77),
(5, 'FÃ¶rderschule', '', 'DE', 11, '0000-00-00 00:00:00', 77),
(6, 'Hauptschule', 'Hauptschule', 'DE', 11, '0000-00-00 00:00:00', 77),
(8, 'UniversitÃ¤t', 'Bildungswissenschaften', 'DE', 11, '0000-00-00 00:00:00', 77);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `semester`
--

DROP TABLE IF EXISTS `semester`;
CREATE TABLE IF NOT EXISTS `semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester` varchar(64) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `begin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `institution_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `semester`
--

INSERT INTO `semester` (`id`, `semester`, `description`, `begin`, `end`, `creation_time`, `creator_id`, `institution_id`) VALUES
(4, 'Schuljahr 2013-2014', 'Schuljahr 2013-2014', '2013-08-19 16:35:23', '2014-08-18 16:35:33', '2013-09-07 16:35:46', 77, 56),
(5, 'Wintersemester 2013-2014', 'Wintersemester 2013-2014', '2013-10-01 16:36:09', '2014-03-31 16:36:26', '2013-09-07 16:36:34', 77, 56);

-- --------------------------------------------------------

--
-- Daten für Tabelle `subjects`
--

INSERT INTO `subjects` (`id`, `subject`, `description`, `creation_time`, `creator_id`, `institution_id`, `subject_short`) VALUES
(1, 'Mathematik', 'Unterrichtsfach Mathematik', '2013-08-09 07:05:42', 77, 56, 'MA'),
(2, 'Deutsch', 'Unterrichtsfach Deutsch', '2013-08-09 07:05:42', 77, 56, 'DE'),
(3, 'Englisch', 'Unterrichtsfach Englisch', '2013-08-09 07:05:42', 77, 56, 'EN'),
(16, 'FranzÃ¶sisch', 'Unterrichtsfach FranzÃ¶sisch', '2013-08-09 07:05:42', 77, 56, 'FR'),
(5, 'Musik', 'Unterrichtsfach Musik', '2013-08-09 07:05:42', 77, 56, 'MU'),
(6, 'Physik', 'Unterrichtsfach Physik', '2013-08-09 07:05:42', 77, 56, 'PH'),
(7, 'Biologie', 'Unterrichtsfach Biologie', '2013-08-09 07:05:42', 77, 56, 'BIO'),
(8, 'Chemie', 'Unterrichtsfach Chemie', '2013-08-09 07:05:42', 77, 56, 'CH'),
(9, 'Erdkunde', 'Unterrichtsfach Erdkunde', '2013-08-09 07:05:42', 77, 56, 'EK'),
(10, 'Sozialkunde', 'Unterrichtsfach Sozialkunde', '2013-08-09 07:05:42', 77, 56, 'SOZ'),
(11, 'Geschichte', 'Unterrichtsfach Geschichte', '2013-08-09 07:05:42', 77, 56, 'G'),
(12, 'Informatik', 'Unterrichtsfach Informatik', '2013-08-09 07:05:42', 77, 56, 'INF'),
(13, 'Kunst', 'Unterrichtsfach Bildende Kunst', '2013-08-09 07:05:42', 77, 56, 'BK'),
(14, 'Sport', 'Unterrichtsfach Sport', '2013-08-09 07:05:42', 77, 56, 'SP'),
(17, 'Erdkunde - Geschichte - Sozialkunde', 'Gemeinsames Unterrichtsfach Erdkunde - Geschichte - Sozialkunde', '2013-08-09 07:05:42', 77, 56, 'EGS'),
(36, 'Naturwissenschaften', 'Gemeinsames Unterrichtsfach Biologie - Physik - Chemie', '2013-08-17 15:06:20', 77, 56, 'NAWI');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `terminalObjectives`
--

DROP TABLE IF EXISTS `terminalObjectives`;
CREATE TABLE IF NOT EXISTS `terminalObjectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `terminal_objective` varchar(400) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `curriculum_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `order_id` tinyint(4) NOT NULL DEFAULT '0',
  `repeat_interval` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=218 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `terminalObjectives`
--

INSERT INTO `terminalObjectives` (`id`, `terminal_objective`, `description`, `curriculum_id`, `creation_time`, `creator_id`, `order_id`, `repeat_interval`) VALUES
(190, 'Sprechen und ZuhÃ¶ren - vor anderen sprechen', '', 94, '2013-04-04 06:29:46', 0, 0, -1),
(188, 'Sprechen und ZuhÃ¶ren - zu anderen sprechen', '', 94, '2013-04-04 06:14:53', 0, 1, -1),
(191, 'Sprechen und ZuhÃ¶ren - mit anderen sprechen', '', 94, '2013-04-04 07:04:44', 0, 2, -1),
(192, 'Sprechen und ZuhÃ¶ren - verstehende zuhÃ¶ren', '', 94, '2013-04-04 07:06:45', 0, 3, -1),
(193, 'Sprechen und ZuhÃ¶ren - szenisch spielen', '', 94, '2013-04-04 07:08:16', 0, 4, -1),
(194, 'Sprechen und ZuhÃ¶ren - Methoden und Arbeitstechniken', '', 94, '2013-04-04 07:09:10', 0, 5, -1),
(195, 'Schreiben - Ã¼ber Schreibfertigkeiten verfÃ¼gen', '', 94, '2013-04-04 07:11:25', 0, 6, -1),
(196, 'Schreiben - richtig schreiben', '', 94, '2013-04-04 07:12:28', 0, 7, -1),
(197, 'Schreiben - einen Schreibprozess eigenverantwortlich gestalten - Texte planen und entwerfen', '', 94, '2013-04-04 07:14:00', 0, 8, -1),
(198, 'Schreiben - einen Schreibprozess eigenverantwortlich gestalten - Texte schreiben', '', 94, '2013-04-04 07:15:32', 0, 9, -1),
(199, 'Schreiben - einen Schreibprozess eigenverantwortlich gestalten - Texte Ã¼berarbeiten', '', 94, '2013-04-04 07:25:02', 0, 10, -1),
(200, 'Schreiben - Methoden und Arbeitstechniken', '', 94, '2013-04-04 07:25:49', 0, 11, -1),
(201, 'Lesen - mit Texten und Medien umgehen - verschiedene Lesetechniken beherrschen', '', 94, '2013-04-04 07:28:24', 0, 12, -1),
(202, 'Lesen - mit Texten und Medien umgehen - Strategien zum Leseverstehen kennen und anwenden', '', 94, '2013-04-04 07:28:58', 0, 13, -1),
(203, 'Lesen - mit Texten und Medien umgehen - literarische Texte verstehen und nutzen', '', 94, '2013-04-04 07:30:58', 0, 14, -1),
(204, 'Lesen - mit Texten und Medien umgehen - Sach- und Gebrauchstexte verstehen und nutzen', '', 94, '2013-04-04 07:34:16', 0, 15, -1),
(205, 'Lesen - mit Texten und Medien umgehen - Medien verstehen und nutzen', '', 94, '2013-04-04 07:35:53', 0, 16, -1),
(206, 'Lesen - Methoden und Arbeitstechniken', '', 94, '2013-04-04 07:37:52', 0, 17, -1),
(207, 'L1 Leitidee Zahl (inhaltsbezogene mathematische Kompetenzen): Â Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:09:18', 0, 0, -1),
(208, 'L2 Leitidee Messen (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:13:46', 0, 1, -1),
(209, 'L3 Leitidee Raum und Forum (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:16:17', 0, 2, -1),
(210, 'L4 Leitidee Funktionaler Zusammenhang (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:19:21', 0, 3, -1),
(211, 'L5 Leitidee Daten und Zufall (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:22:42', 0, 4, -1),
(212, 'K1 Mathematisch argumentieren (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:24:58', 0, 5, -1),
(213, 'K2 Probleme mathematisch lÃ¶sen (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:26:21', 0, 6, -1),
(214, 'K3 Mathematisch modellieren (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:28:12', 0, 7, -1),
(215, 'K4 Mathematische Darstellungen verwenden (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:29:29', 0, 8, -1),
(216, 'K5 Mit symbolische, formalen und technischen Elementen der Mathematik umgehen (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:30:21', 0, 9, -1),
(217, 'K6 Kommunizieren (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:31:12', 0, 10, -1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL,
  `email` text NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `postalcode` text,
  `city` text,
  `state_id` text,
  `country_id` text,
  `avatar` text,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_USER_ID` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role_id`, `last_login`, `email`, `confirmed`, `firstname`, `lastname`, `postalcode`, `city`, `state_id`, `country_id`, `avatar`, `creation_time`, `creator_id`) VALUES
(78, 'michaellang', '663f59787220364dff25287148c63cb5', 0, NULL, 'michaellang@joachimdieterich.de', 3, 'Michael', 'Lang', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(79, 'nataschajessen', '663f59787220364dff25287148c63cb5', 0, NULL, 'nataschajessen@joachimdieterich.de', 3, 'Natascha', 'Jessen', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(80, 'elkesieburg', '663f59787220364dff25287148c63cb5', 0, NULL, 'elkesieburg@joachimdieterich.de', 3, 'Elke', 'Sieburg', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(81, 'guentherrohe', '663f59787220364dff25287148c63cb5', 0, NULL, 'guentherrohe@joachimdieterich.de', 3, 'GÃ¼nther', 'Rohe', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(82, 'margarethesixtus', '663f59787220364dff25287148c63cb5', 0, NULL, 'margarethesixtus@joachimdieterich.de', 3, 'Margarethe', 'Sixtus', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(83, 'mathiasaustrup', '663f59787220364dff25287148c63cb5', 0, NULL, 'mathiasaustrup@joachimdieterich.de', 3, 'Mathias', 'Austrup', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(84, 'baerbeltretter', '663f59787220364dff25287148c63cb5', 0, NULL, 'baerbeltretter@joachimdieterich.de', 3, 'BÃ¤rbel', 'Tretter', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(85, 'miriammengel', '663f59787220364dff25287148c63cb5', 0, NULL, 'miriammengel@joachimdieterich.de', 3, 'Miriam', 'Mengel', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(86, 'hellastoffers', '663f59787220364dff25287148c63cb5', 0, NULL, 'hellastoffers@joachimdieterich.de', 3, 'Hella', 'Stoffers', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(87, 'gretakreimer', '663f59787220364dff25287148c63cb5', 0, NULL, 'gretakreimer@joachimdieterich.de', 3, 'Greta', 'Kreimer', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(88, 'steffenbierig', '663f59787220364dff25287148c63cb5', 0, NULL, 'steffenbierig@joachimdieterich.de', 3, 'Steffen', 'Bierig', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(89, 'moniquekunert', '663f59787220364dff25287148c63cb5', 0, NULL, 'moniquekunert@joachimdieterich.de', 3, 'Monique', 'Kunert', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(90, 'tabeafrohn', '663f59787220364dff25287148c63cb5', 0, NULL, 'tabeafrohn@joachimdieterich.de', 3, 'Tabea', 'Frohn', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(91, 'kathrinarendt', '663f59787220364dff25287148c63cb5', 0, NULL, 'kathrinarendt@joachimdieterich.de', 3, 'Kathrin', 'Arendt', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(92, 'helgaroehr', '663f59787220364dff25287148c63cb5', 0, NULL, 'helgaroehr@joachimdieterich.de', 3, 'Helga', 'RÃ¶hr', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(93, 'stevenpape', '663f59787220364dff25287148c63cb5', 0, NULL, 'stevenpape@joachimdieterich.de', 3, 'Steven', 'Pape', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(94, 'wilhelmstrehlow', '663f59787220364dff25287148c63cb5', 0, NULL, 'wilhelmstrehlow@joachimdieterich.de', 3, 'Wilhelm', 'Strehlow', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(95, 'kristamellinghoff', '663f59787220364dff25287148c63cb5', 0, NULL, 'kristamellinghoff@joachimdieterich.de', 3, 'Krista', 'Mellinghoff', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(96, 'franzrothermel', '663f59787220364dff25287148c63cb5', 0, NULL, 'franzrothermel@joachimdieterich.de', 3, 'Franz', 'Rothermel', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(97, 'heinzjochmann', '663f59787220364dff25287148c63cb5', 0, NULL, 'heinzjochmann@joachimdieterich.de', 3, 'Heinz', 'Jochmann', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(98, 'anjamutschler', '663f59787220364dff25287148c63cb5', 0, NULL, 'anjamutschler@joachimdieterich.de', 3, 'Anja', 'Mutschler', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(99, 'jasperneufeld', '663f59787220364dff25287148c63cb5', 0, NULL, 'jasperneufeld@joachimdieterich.de', 3, 'Jasper', 'Neufeld', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(100, 'timonwilliams', '663f59787220364dff25287148c63cb5', 0, NULL, 'timonwilliams@joachimdieterich.de', 3, 'Timon', 'Williams', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77),
(101, 'brittastuber', '663f59787220364dff25287148c63cb5', 0, NULL, 'brittastuber@joachimdieterich.de', 3, 'Britta', 'Stuber', '', '', '11', '56', 'noprofile.jpg', '2013-09-07 07:49:36', 77);

-- --------------------------------------------------------

--
-- Daten für Tabelle `user_roles`
--

INSERT INTO `user_roles` (`id`, `role_id`, `role`, `description`, `creation_time`, `creator_id`) VALUES
(1, 0, 'Student', 'Benutzer hat nur Leserechte', '2013-08-09 07:06:00', 77),
(2, 1, 'Administrator', 'Benutzer hat alle Rechte', '2013-08-09 07:06:00', 77),
(3, 2, 'Tutor', 'Benutzer darf Kompetenzraster bearbeiten', '2013-08-09 07:06:00', 77),
(4, 3, 'Lehrer', 'Benutzer darf Kompetenzraster erstellen', '2013-08-09 07:06:00', 77),
(9, 4, 'Administrator (Insitution)', 'Benutzer darf Institution / Schule verwalten', '2013-08-09 07:06:00', 77);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;