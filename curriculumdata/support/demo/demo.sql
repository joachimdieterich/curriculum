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

--
-- Datenbank: `install`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `authenticate`
--

DROP TABLE IF EXISTS `authenticate`;
CREATE TABLE IF NOT EXISTS `authenticate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `token` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `email` text NOT NULL,
  `user_external_id` int(11) NOT NULL,
  `ws_username` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `capabilities`
--

DROP TABLE IF EXISTS `capabilities`;
CREATE TABLE IF NOT EXISTS `capabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capability` varchar(240) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `component` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Daten für Tabelle `capabilities`
--

INSERT INTO `capabilities` (`id`, `capability`, `name`, `description`, `type`, `component`) VALUES
(1, 'menu:readlogmenu', 'LogdatenmenÃ¼ anzeigen', 'Ability to see log-menu', 'read', 'curriculum'),
(2, 'menu:readInstitution', 'InstitutionsmenÃ¼ anzeigen', 'Ability to see institution menu', 'read', 'curriculum'),
(3, 'menu:readProgress', 'LernstandmenÃ¼ anzeigen', 'Ability to see progress menu inside of institution menu', 'read', 'curriculum'),
(4, 'menu:readCurricula', 'LehrplanmenÃ¼ anzeigen', 'Ability to see curricula menu inside of institution menu', 'read', 'curriculum'),
(5, 'menu:readUserAdministration', 'BenutzerverwaltungsmenÃ¼ anzeigen', 'Ability to see useradministration menu inside of institution menu', 'read', 'curriculum'),
(6, 'menu:readGrade', 'KlassenstufenmenÃ¼ anzeigen', 'Ability to see grade menu inside of institution menu', 'read', 'curriculum'),
(7, 'menu:readSubject', 'FÃ¤chermenÃ¼ anzeigen', 'Ability to see subject menu inside of institution menu', 'read', 'curriculum'),
(8, 'menu:readSemester', 'LernzeitraummenÃ¼ anzeigen', 'Ability to see semester menu inside of institution menu', 'read', 'curriculum'),
(9, 'menu:readBackup', 'BackupmenÃ¼ anzeigen', 'Ability to see backup menu inside of institution menu', 'read', 'curriculum'),
(10, 'menu:readConfirm', 'FreigabemenÃ¼ anzeigen', 'Ability to see confirm menu inside of institution menu', 'read', 'curriculum'),
(11, 'menu:readInstitutionConfig', 'Einstellungen (Institution) anzeigen', 'Ability to see Config menu (Insitution) inside of institution menu', 'read', 'curriculum'),
(12, 'menu:readProfileConfig', 'Einstellungen (Profil) anzeigen', 'Ability to see Config menu (Profile) inside of institution menu', 'read', 'curriculum'),
(13, 'menu:readMyCurricula', 'Meine LehrplÃ¤ne anzeigen', 'Ability to see My curricula menu', 'read', 'curriculum'),
(14, 'menu:readGroup', 'GruppenmenÃ¼ anzeigen', 'Ability to see group-menu', 'read', 'curriculum'),
(15, 'page:readLog', 'Logdaten anzeigen', 'Ability to see log page', 'read', 'curriculum'),
(16, 'page:showRoleForm', 'Rollen Formular anzeigen', 'Ability to see Role form', 'read', 'curriculum'),
(17, 'menu:readRoles', 'RollenmenÃ¼ anzeigen', 'Ability to see Role menu', 'read', 'curriculum'),
(20, 'user:addUser', 'Benuzter hinzufÃ¼gen', 'Ability to add user profiles', 'write', 'curriculum'),
(21, 'user:updateUser', 'Benuzter bearbeiten', 'Ability to update user profiles', 'write', 'curriculum'),
(23, 'user:updateRole', 'Benuzterrolle aktualisieren', 'Ability to update user roles', 'write', 'curriculum'),
(24, 'user:delete', 'Benuzter lÃ¶schen', 'Ability to delete users', 'write', 'curriculum'),
(25, 'user:changePassword', 'Eigenes Benutzerpasswort Ã¤ndern', 'Ability to change own userpassoword', 'write', 'curriculum'),
(26, 'user:getPassword', 'Passwort aus Datenbank abfragen (! Nur für Webservice freigeben !)', 'Ability to get password', 'read', 'curriculum'),
(27, 'user:getGroupMembers', 'Mitglieder aus einer Lerngruppe anzeigen', 'Ability to read groupmembers', 'read', 'curriculum'),
(28, 'user:listNewUsers', 'Neue Benutzer auflisten', 'Ability to list new registered users', 'read', 'curriculum'),
(29, 'user:enroleToInstitution', 'Benutzer in Institution einschreiben', 'Ability to enrole users to an institution', 'write', 'curriculum'),
(30, 'user:enroleToGroup', 'Benutzer in Lerngruppe einschreiben', 'Ability to enrole users to group', 'write', 'curriculum'),
(31, 'user:expelFromGroup', 'Benutzer in Lerngruppe ausschreiben', 'Ability to expel users from group', 'write', 'curriculum'),
(32, 'user:import', 'Benutzerliste (csv) importieren', 'Ability to import csv-userlist', 'write', 'curriculum'),
(33, 'user:userList', 'Benutzerliste anzeigen', 'Ability to see userlist', 'read', 'curriculum'),
(35, 'user:resetPassword', 'Benuzerkennwort zurÃ¼cksetzen', 'Ability to reset password', 'write', 'curriculum'),
(36, 'user:getUsers', 'Lerngruppenliste (Lehrplanbezogen) anzeigen', 'Ability to get Grouplist (depending on curriculum)', 'read', 'curriculum'),
(37, 'user:getNewUsers', 'Neue Benutzer (Institutsbezogen) anzeigen', 'Ability to get New Userlist (depending on institution)', 'read', 'curriculum'),
(38, 'user:confirmUser', 'Neue Benutzer bestÃ¤tigen', 'Ability to confirm new users', 'write', 'curriculum'),
(39, 'user:dedicate', 'Benutzer wÃ¤hrend Installationsprozess der erstellten Institution zuweisen', 'Only for installation purposes', 'write', 'curriculum');

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
-- Tabellenstruktur für Tabelle `context`
--

DROP TABLE IF EXISTS `context`;
CREATE TABLE IF NOT EXISTS `context` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(100) DEFAULT NULL,
  `context_id` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `context`
--

INSERT INTO `context` (`id`, `context`, `context_id`, `path`) VALUES
(1, 'userFiles', 1, 'userdata/'),
(2, 'curriculum', 2, 'curriculum/'),
(3, 'avatar', 3, 'avatar/'),
(4, 'userView', 4, 'solutions/'),
(5, 'subjectIcon', 5, 'subjects/');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `code` char(2) CHARACTER SET utf8 NOT NULL,
  `en` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `de` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `de` (`de`),
  KEY `en` (`en`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=244 ;

--
-- Daten für Tabelle `countries`
--

INSERT INTO `countries` (`code`, `en`, `de`, `id`, `creation_time`, `creator_id`) VALUES
('AD', 'Andorra', 'Andorra', 1, '2013-08-09 06:56:37', -1),
('AE', 'United Arab Emirates', 'Vereinigte Arabische Emirate', 2, '2013-08-09 06:56:37', -1),
('AF', 'Afghanistan', 'Afghanistan', 3, '2013-08-09 06:56:37', -1),
('AG', 'Antigua and Barbuda', 'Antigua und Barbuda', 4, '2013-08-09 06:56:37', -1),
('AI', 'Anguilla', 'Anguilla', 5, '2013-08-09 06:56:37', -1),
('AL', 'Albania', 'Albanien', 6, '2013-08-09 06:56:37', -1),
('AM', 'Armenia', 'Armenien', 7, '2013-08-09 06:56:37', -1),
('AN', 'Netherlands Antilles', 'NiederlÃ¤ndische Antillen', 8, '2013-08-09 06:56:37', -1),
('AO', 'Angola', 'Angola', 9, '2013-08-09 06:56:37', -1),
('AQ', 'Antarctica', 'Antarktis', 10, '2013-08-09 06:56:37', -1),
('AR', 'Argentina', 'Argentinien', 11, '2013-08-09 06:56:37', -1),
('AS', 'American Samoa', 'Amerikanisch-Samoa', 12, '2013-08-09 06:56:37', -1),
('AT', 'Austria', 'Ã–sterreich', 13, '2013-08-09 06:56:37', -1),
('AU', 'Australia', 'Australien', 14, '2013-08-09 06:56:37', -1),
('AW', 'Aruba', 'Aruba', 15, '2013-08-09 06:56:37', -1),
('AX', 'Aland Islands', 'Aland', 16, '2013-08-09 06:56:37', -1),
('AZ', 'Azerbaijan', 'Aserbaidschan', 17, '2013-08-09 06:56:37', -1),
('BA', 'Bosnia and Herzegovina', 'Bosnien und Herzegowina', 18, '2013-08-09 06:56:37', -1),
('BB', 'Barbados', 'Barbados', 19, '2013-08-09 06:56:37', -1),
('BD', 'Bangladesh', 'Bangladesch', 20, '2013-08-09 06:56:37', -1),
('BE', 'Belgium', 'Belgien', 21, '2013-08-09 06:56:37', -1),
('BF', 'Burkina Faso', 'Burkina Faso', 22, '2013-08-09 06:56:37', -1),
('BG', 'Bulgaria', 'Bulgarien', 23, '2013-08-09 06:56:37', -1),
('BH', 'Bahrain', 'Bahrain', 24, '2013-08-09 06:56:37', -1),
('BI', 'Burundi', 'Burundi', 25, '2013-08-09 06:56:37', -1),
('BJ', 'Benin', 'Benin', 26, '2013-08-09 06:56:37', -1),
('BM', 'Bermuda', 'Bermuda', 27, '2013-08-09 06:56:37', -1),
('BN', 'Brunei', 'Brunei Darussalam', 28, '2013-08-09 06:56:37', -1),
('BO', 'Bolivia', 'Bolivien', 29, '2013-08-09 06:56:37', -1),
('BR', 'Brazil', 'Brasilien', 30, '2013-08-09 06:56:37', -1),
('BS', 'Bahamas', 'Bahamas', 31, '2013-08-09 06:56:37', -1),
('BT', 'Bhutan', 'Bhutan', 32, '2013-08-09 06:56:37', -1),
('BV', 'Bouvet Island', 'Bouvetinsel', 33, '2013-08-09 06:56:37', -1),
('BW', 'Botswana', 'Botswana', 34, '2013-08-09 06:56:37', -1),
('BY', 'Belarus', 'Belarus (WeiÃŸrussland)', 35, '2013-08-09 06:56:37', -1),
('BZ', 'Belize', 'Belize', 36, '2013-08-09 06:56:37', -1),
('CA', 'Canada', 'Kanada', 37, '2013-08-09 06:56:37', -1),
('CC', 'Cocos (Keeling) Islands', 'Kokosinseln (Keelinginseln)', 38, '2013-08-09 06:56:37', -1),
('CD', 'Congo (Kinshasa)', 'Kongo', 39, '2013-08-09 06:56:37', -1),
('CF', 'Central African Republic', 'Zentralafrikanische Republik', 40, '2013-08-09 06:56:37', -1),
('CG', 'Congo (Brazzaville)', 'Republik Kongo', 41, '2013-08-09 06:56:37', -1),
('CH', 'Switzerland', 'Schweiz', 42, '2013-08-09 06:56:37', -1),
('CI', 'Ivory Coast', 'ElfenbeinkÃ¼ste', 43, '2013-08-09 06:56:37', -1),
('CK', 'Cook Islands', 'Cookinseln', 44, '2013-08-09 06:56:37', -1),
('CL', 'Chile', 'Chile', 45, '2013-08-09 06:56:37', -1),
('CM', 'Cameroon', 'Kamerun', 46, '2013-08-09 06:56:37', -1),
('CN', 'China', 'China, Volksrepublik', 47, '2013-08-09 06:56:37', -1),
('CO', 'Colombia', 'Kolumbien', 48, '2013-08-09 06:56:37', -1),
('CR', 'Costa Rica', 'Costa Rica', 49, '2013-08-09 06:56:37', -1),
('CS', 'Serbia And Montenegro', 'Serbien und Montenegro', 50, '2013-08-09 06:56:37', -1),
('CU', 'Cuba', 'Kuba', 51, '2013-08-09 06:56:37', -1),
('CV', 'Cape Verde', 'Kap Verde', 52, '2013-08-09 06:56:37', -1),
('CX', 'Christmas Island', 'Weihnachtsinsel', 53, '2013-08-09 06:56:37', -1),
('CY', 'Cyprus', 'Zypern', 54, '2013-08-09 06:56:37', -1),
('CZ', 'Czech Republic', 'Tschechische Republik', 55, '2013-08-09 06:56:37', -1),
('DE', 'Germany', 'Deutschland', 56, '2013-08-09 06:56:37', -1),
('DJ', 'Djibouti', 'Dschibuti', 57, '2013-08-09 06:56:37', -1),
('DK', 'Denmark', 'DÃ¤nemark', 58, '2013-08-09 06:56:37', -1),
('DM', 'Dominica', 'Dominica', 59, '2013-08-09 06:56:37', -1),
('DO', 'Dominican Republic', 'Dominikanische Republik', 60, '2013-08-09 06:56:37', -1),
('DZ', 'Algeria', 'Algerien', 61, '2013-08-09 06:56:37', -1),
('EC', 'Ecuador', 'Ecuador', 62, '2013-08-09 06:56:37', -1),
('EE', 'Estonia', 'Estland (Reval)', 63, '2013-08-09 06:56:37', -1),
('EG', 'Egypt', 'Ã„gypten', 64, '2013-08-09 06:56:37', -1),
('EH', 'Western Sahara', 'Westsahara', 65, '2013-08-09 06:56:37', -1),
('ER', 'Eritrea', 'Eritrea', 66, '2013-08-09 06:56:37', -1),
('ES', 'Spain', 'Spanien', 67, '2013-08-09 06:56:37', -1),
('ET', 'Ethiopia', 'Ã„thiopien', 68, '2013-08-09 06:56:37', -1),
('FI', 'Finland', 'Finnland', 69, '2013-08-09 06:56:37', -1),
('FJ', 'Fiji', 'Fidschi', 70, '2013-08-09 06:56:37', -1),
('FK', 'Falkland Islands', 'Falklandinseln (Malwinen)', 71, '2013-08-09 06:56:37', -1),
('FM', 'Micronesia', 'Mikronesien', 72, '2013-08-09 06:56:37', -1),
('FO', 'Faroe Islands', 'FÃ¤rÃ¶er', 73, '2013-08-09 06:56:37', -1),
('FR', 'France', 'Frankreich', 74, '2013-08-09 06:56:37', -1),
('GA', 'Gabon', 'Gabun', 75, '2013-08-09 06:56:37', -1),
('GB', 'United Kingdom', 'GroÃŸbritannien und Nordirland', 76, '2013-08-09 06:56:37', -1),
('GD', 'Grenada', 'Grenada', 77, '2013-08-09 06:56:37', -1),
('GE', 'Georgia', 'Georgien', 78, '2013-08-09 06:56:37', -1),
('GF', 'French Guiana', 'FranzÃ¶sisch-Guayana', 79, '2013-08-09 06:56:37', -1),
('GG', 'Guernsey', 'Guernsey (Kanalinsel)', 80, '2013-08-09 06:56:37', -1),
('GH', 'Ghana', 'Ghana', 81, '2013-08-09 06:56:37', -1),
('GI', 'Gibraltar', 'Gibraltar', 82, '2013-08-09 06:56:37', -1),
('GL', 'Greenland', 'GrÃ¶nland', 83, '2013-08-09 06:56:37', -1),
('GM', 'Gambia', 'Gambia', 84, '2013-08-09 06:56:37', -1),
('GN', 'Guinea', 'Guinea', 85, '2013-08-09 06:56:37', -1),
('GP', 'Guadeloupe', 'Guadeloupe', 86, '2013-08-09 06:56:37', -1),
('GQ', 'Equatorial Guinea', 'Ã„quatorialguinea', 87, '2013-08-09 06:56:37', -1),
('GR', 'Greece', 'Griechenland', 88, '2013-08-09 06:56:37', -1),
('GS', 'South Georgia and the South Sandwich Islands', 'SÃ¼dgeorgien und die SÃ¼dl. Sandwichinseln', 89, '2013-08-09 06:56:37', -1),
('GT', 'Guatemala', 'Guatemala', 90, '2013-08-09 06:56:37', -1),
('GU', 'Guam', 'Guam', 91, '2013-08-09 06:56:37', -1),
('GW', 'Guinea-Bissau', 'Guinea-Bissau', 92, '2013-08-09 06:56:37', -1),
('GY', 'Guyana', 'Guyana', 93, '2013-08-09 06:56:37', -1),
('HK', 'Hong Kong S.A.R., China', 'Hongkong', 94, '2013-08-09 06:56:37', -1),
('HM', 'Heard Island and McDonald Islands', 'Heard- und McDonald-Inseln', 95, '2013-08-09 06:56:37', -1),
('HN', 'Honduras', 'Honduras', 96, '2013-08-09 06:56:37', -1),
('HR', 'Croatia', 'Kroatien', 97, '2013-08-09 06:56:37', -1),
('HT', 'Haiti', 'Haiti', 98, '2013-08-09 06:56:37', -1),
('HU', 'Hungary', 'Ungarn', 99, '2013-08-09 06:56:37', -1),
('ID', 'Indonesia', 'Indonesien', 100, '2013-08-09 06:56:37', -1),
('IE', 'Ireland', 'Irland', 101, '2013-08-09 06:56:37', -1),
('IL', 'Israel', 'Israel', 102, '2013-08-09 06:56:37', -1),
('IM', 'Isle of Man', 'Insel Man', 103, '2013-08-09 06:56:37', -1),
('IN', 'India', 'Indien', 104, '2013-08-09 06:56:37', -1),
('IO', 'British Indian Ocean Territory', 'Britisches Territorium im Indischen Ozean', 105, '2013-08-09 06:56:37', -1),
('IQ', 'Iraq', 'Irak', 106, '2013-08-09 06:56:37', -1),
('IR', 'Iran', 'Iran', 107, '2013-08-09 06:56:37', -1),
('IS', 'Iceland', 'Island', 108, '2013-08-09 06:56:37', -1),
('IT', 'Italy', 'Italien', 109, '2013-08-09 06:56:37', -1),
('JE', 'Jersey', 'Jersey (Kanalinsel)', 110, '2013-08-09 06:56:37', -1),
('JM', 'Jamaica', 'Jamaika', 111, '2013-08-09 06:56:37', -1),
('JO', 'Jordan', 'Jordanien', 112, '2013-08-09 06:56:37', -1),
('JP', 'Japan', 'Japan', 113, '2013-08-09 06:56:37', -1),
('KE', 'Kenya', 'Kenia', 114, '2013-08-09 06:56:37', -1),
('KG', 'Kyrgyzstan', 'Kirgisistan', 115, '2013-08-09 06:56:37', -1),
('KH', 'Cambodia', 'Kambodscha', 116, '2013-08-09 06:56:37', -1),
('KI', 'Kiribati', 'Kiribati', 117, '2013-08-09 06:56:37', -1),
('KM', 'Comoros', 'Komoren', 118, '2013-08-09 06:56:37', -1),
('KN', 'Saint Kitts and Nevis', 'St. Kitts und Nevis', 119, '2013-08-09 06:56:37', -1),
('KP', 'North Korea', 'Nordkorea', 120, '2013-08-09 06:56:37', -1),
('KR', 'South Korea', 'SÃ¼dkorea', 121, '2013-08-09 06:56:37', -1),
('KW', 'Kuwait', 'Kuwait', 122, '2013-08-09 06:56:37', -1),
('KY', 'Cayman Islands', 'Kaimaninseln', 123, '2013-08-09 06:56:37', -1),
('KZ', 'Kazakhstan', 'Kasachstan', 124, '2013-08-09 06:56:37', -1),
('LA', 'Laos', 'Laos', 125, '2013-08-09 06:56:37', -1),
('LB', 'Lebanon', 'Libanon', 126, '2013-08-09 06:56:37', -1),
('LC', 'Saint Lucia', 'St. Lucia', 127, '2013-08-09 06:56:37', -1),
('LI', 'Liechtenstein', 'Liechtenstein', 128, '2013-08-09 06:56:37', -1),
('LK', 'Sri Lanka', 'Sri Lanka', 129, '2013-08-09 06:56:37', -1),
('LR', 'Liberia', 'Liberia', 130, '2013-08-09 06:56:37', -1),
('LS', 'Lesotho', 'Lesotho', 131, '2013-08-09 06:56:37', -1),
('LT', 'Lithuania', 'Litauen', 132, '2013-08-09 06:56:37', -1),
('LU', 'Luxembourg', 'Luxemburg', 133, '2013-08-09 06:56:37', -1),
('LV', 'Latvia', 'Lettland', 134, '2013-08-09 06:56:37', -1),
('LY', 'Libya', 'Libyen', 135, '2013-08-09 06:56:37', -1),
('MA', 'Morocco', 'Marokko', 136, '2013-08-09 06:56:37', -1),
('MC', 'Monaco', 'Monaco', 137, '2013-08-09 06:56:37', -1),
('MD', 'Moldova', 'Moldawien', 138, '2013-08-09 06:56:37', -1),
('MG', 'Madagascar', 'Madagaskar', 139, '2013-08-09 06:56:37', -1),
('MH', 'Marshall Islands', 'Marshallinseln', 140, '2013-08-09 06:56:37', -1),
('MK', 'Macedonia', 'Mazedonien', 141, '2013-08-09 06:56:37', -1),
('ML', 'Mali', 'Mali', 142, '2013-08-09 06:56:37', -1),
('MM', 'Myanmar', 'Myanmar (Burma)', 143, '2013-08-09 06:56:37', -1),
('MN', 'Mongolia', 'Mongolei', 144, '2013-08-09 06:56:37', -1),
('MO', 'Macao S.A.R., China', 'Macao', 145, '2013-08-09 06:56:37', -1),
('MP', 'Northern Mariana Islands', 'NÃ¶rdliche Marianen', 146, '2013-08-09 06:56:37', -1),
('MQ', 'Martinique', 'Martinique', 147, '2013-08-09 06:56:37', -1),
('MR', 'Mauritania', 'Mauretanien', 148, '2013-08-09 06:56:37', -1),
('MS', 'Montserrat', 'Montserrat', 149, '2013-08-09 06:56:37', -1),
('MT', 'Malta', 'Malta', 150, '2013-08-09 06:56:37', -1),
('MU', 'Mauritius', 'Mauritius', 151, '2013-08-09 06:56:37', -1),
('MV', 'Maldives', 'Malediven', 152, '2013-08-09 06:56:37', -1),
('MW', 'Malawi', 'Malawi', 153, '2013-08-09 06:56:37', -1),
('MX', 'Mexico', 'Mexiko', 154, '2013-08-09 06:56:37', -1),
('MY', 'Malaysia', 'Malaysia', 155, '2013-08-09 06:56:37', -1),
('MZ', 'Mozambique', 'Mosambik', 156, '2013-08-09 06:56:37', -1),
('NA', 'Namibia', 'Namibia', 157, '2013-08-09 06:56:37', -1),
('NC', 'New Caledonia', 'Neukaledonien', 158, '2013-08-09 06:56:37', -1),
('NE', 'Niger', 'Niger', 159, '2013-08-09 06:56:37', -1),
('NF', 'Norfolk Island', 'Norfolkinsel', 160, '2013-08-09 06:56:37', -1),
('NG', 'Nigeria', 'Nigeria', 161, '2013-08-09 06:56:37', -1),
('NI', 'Nicaragua', 'Nicaragua', 162, '2013-08-09 06:56:37', -1),
('NL', 'Netherlands', 'Niederlande', 163, '2013-08-09 06:56:37', -1),
('NO', 'Norway', 'Norwegen', 164, '2013-08-09 06:56:37', -1),
('NP', 'Nepal', 'Nepal', 165, '2013-08-09 06:56:37', -1),
('NR', 'Nauru', 'Nauru', 166, '2013-08-09 06:56:37', -1),
('NU', 'Niue', 'Niue', 167, '2013-08-09 06:56:37', -1),
('NZ', 'New Zealand', 'Neuseeland', 168, '2013-08-09 06:56:37', -1),
('OM', 'Oman', 'Oman', 169, '2013-08-09 06:56:37', -1),
('PA', 'Panama', 'Panama', 170, '2013-08-09 06:56:37', -1),
('PE', 'Peru', 'Peru', 171, '2013-08-09 06:56:37', -1),
('PF', 'French Polynesia', 'FranzÃ¶sisch-Polynesien', 172, '2013-08-09 06:56:37', -1),
('PG', 'Papua New Guinea', 'Papua-Neuguinea', 173, '2013-08-09 06:56:37', -1),
('PH', 'Philippines', 'Philippinen', 174, '2013-08-09 06:56:37', -1),
('PK', 'Pakistan', 'Pakistan', 175, '2013-08-09 06:56:37', -1),
('PL', 'Poland', 'Polen', 176, '2013-08-09 06:56:37', -1),
('PM', 'Saint Pierre and Miquelon', 'St. Pierre und Miquelon', 177, '2013-08-09 06:56:37', -1),
('PN', 'Pitcairn', 'Pitcairninseln', 178, '2013-08-09 06:56:37', -1),
('PR', 'Puerto Rico', 'Puerto Rico', 179, '2013-08-09 06:56:37', -1),
('PS', 'Palestinian Territory', 'PalÃ¤stinensische Autonomiegebiete', 180, '2013-08-09 06:56:37', -1),
('PT', 'Portugal', 'Portugal', 181, '2013-08-09 06:56:37', -1),
('PW', 'Palau', 'Palau', 182, '2013-08-09 06:56:37', -1),
('PY', 'Paraguay', 'Paraguay', 183, '2013-08-09 06:56:37', -1),
('QA', 'Qatar', 'Katar', 184, '2013-08-09 06:56:37', -1),
('RE', 'Reunion', 'RÃ©union', 185, '2013-08-09 06:56:37', -1),
('RO', 'Romania', 'RumÃ¤nien', 186, '2013-08-09 06:56:37', -1),
('RU', 'Russia', 'Russische FÃ¶deration', 187, '2013-08-09 06:56:37', -1),
('RW', 'Rwanda', 'Ruanda', 188, '2013-08-09 06:56:37', -1),
('SA', 'Saudi Arabia', 'Saudi-Arabien', 189, '2013-08-09 06:56:37', -1),
('SB', 'Solomon Islands', 'Salomonen', 190, '2013-08-09 06:56:37', -1),
('SC', 'Seychelles', 'Seychellen', 191, '2013-08-09 06:56:37', -1),
('SD', 'Sudan', 'Sudan', 192, '2013-08-09 06:56:37', -1),
('SE', 'Sweden', 'Schweden', 193, '2013-08-09 06:56:37', -1),
('SG', 'Singapore', 'Singapur', 194, '2013-08-09 06:56:37', -1),
('SH', 'Saint Helena', 'St. Helena', 195, '2013-08-09 06:56:37', -1),
('SI', 'Slovenia', 'Slowenien', 196, '2013-08-09 06:56:37', -1),
('SJ', 'Svalbard and Jan Mayen', 'Svalbard und Jan Mayen', 197, '2013-08-09 06:56:37', -1),
('SK', 'Slovakia', 'Slowakei', 198, '2013-08-09 06:56:37', -1),
('SL', 'Sierra Leone', 'Sierra Leone', 199, '2013-08-09 06:56:37', -1),
('SM', 'San Marino', 'San Marino', 200, '2013-08-09 06:56:37', -1),
('SN', 'Senegal', 'Senegal', 201, '2013-08-09 06:56:37', -1),
('SO', 'Somalia', 'Somalia', 202, '2013-08-09 06:56:37', -1),
('SR', 'Suriname', 'Suriname', 203, '2013-08-09 06:56:37', -1),
('ST', 'Sao Tome and Principe', 'SÃ£o TomÃ© und PrÃ­Â­ncipe', 204, '2013-08-09 06:56:37', -1),
('SV', 'El Salvador', 'El Salvador', 205, '2013-08-09 06:56:37', -1),
('SY', 'Syria', 'Syrien', 206, '2013-08-09 06:56:37', -1),
('SZ', 'Swaziland', 'Swasiland', 207, '2013-08-09 06:56:37', -1),
('TC', 'Turks and Caicos Islands', 'Turks- und Caicosinseln', 208, '2013-08-09 06:56:37', -1),
('TD', 'Chad', 'Tschad', 209, '2013-08-09 06:56:37', -1),
('TF', 'French Southern Territories', 'FranzÃ¶sische SÃ¼d- und Antarktisgebiete', 210, '2013-08-09 06:56:37', -1),
('TG', 'Togo', 'Togo', 211, '2013-08-09 06:56:37', -1),
('TH', 'Thailand', 'Thailand', 212, '2013-08-09 06:56:37', -1),
('TJ', 'Tajikistan', 'Tadschikistan', 213, '2013-08-09 06:56:37', -1),
('TK', 'Tokelau', 'Tokelau', 214, '2013-08-09 06:56:37', -1),
('TL', 'East Timor', 'Timor-Leste', 215, '2013-08-09 06:56:37', -1),
('TM', 'Turkmenistan', 'Turkmenistan', 216, '2013-08-09 06:56:37', -1),
('TN', 'Tunisia', 'Tunesien', 217, '2013-08-09 06:56:37', -1),
('TO', 'Tonga', 'Tonga', 218, '2013-08-09 06:56:37', -1),
('TR', 'Turkey', 'TÃ¼rkei', 219, '2013-08-09 06:56:37', -1),
('TT', 'Trinidad and Tobago', 'Trinidad und Tobago', 220, '2013-08-09 06:56:37', -1),
('TV', 'Tuvalu', 'Tuvalu', 221, '2013-08-09 06:56:37', -1),
('TW', 'Taiwan', 'Taiwan', 222, '2013-08-09 06:56:37', -1),
('TZ', 'Tanzania', 'Tansania', 223, '2013-08-09 06:56:37', -1),
('UA', 'Ukraine', 'Ukraine', 224, '2013-08-09 06:56:37', -1),
('UG', 'Uganda', 'Uganda', 225, '2013-08-09 06:56:37', -1),
('UM', 'United States Minor Outlying Islands', 'Amerikanisch-Ozeanien', 226, '2013-08-09 06:56:37', -1),
('US', 'United States', 'Vereinigte Staaten von Amerika', 227, '2013-08-09 06:56:37', -1),
('UY', 'Uruguay', 'Uruguay', 228, '2013-08-09 06:56:37', -1),
('UZ', 'Uzbekistan', 'Usbekistan', 229, '2013-08-09 06:56:37', -1),
('VA', 'Vatican', 'Vatikanstadt', 230, '2013-08-09 06:56:37', -1),
('VC', 'Saint Vincent and the Grenadines', 'St. Vincent und die Grenadinen', 231, '2013-08-09 06:56:37', -1),
('VE', 'Venezuela', 'Venezuela', 232, '2013-08-09 06:56:37', -1),
('VG', 'British Virgin Islands', 'Britische Jungferninseln', 233, '2013-08-09 06:56:37', -1),
('VI', 'U.S. Virgin Islands', 'Amerikanische Jungferninseln', 234, '2013-08-09 06:56:37', -1),
('VN', 'Vietnam', 'Vietnam', 235, '2013-08-09 06:56:37', -1),
('VU', 'Vanuatu', 'Vanuatu', 236, '2013-08-09 06:56:37', -1),
('WF', 'Wallis and Futuna', 'Wallis und Futuna', 237, '2013-08-09 06:56:37', -1),
('WS', 'Samoa', 'Samoa', 238, '2013-08-09 06:56:37', -1),
('YE', 'Yemen', 'Jemen', 239, '2013-08-09 06:56:37', -1),
('YT', 'Mayotte', 'Mayotte', 240, '2013-08-09 06:56:37', -1),
('ZA', 'South Africa', 'SÃ¼dafrika', 241, '2013-08-09 06:56:37', -1),
('ZM', 'Zambia', 'Sambia', 242, '2013-08-09 06:56:37', -1),
('ZW', 'Zimbabwe', 'Simbabwe', 243, '2013-08-09 06:56:37', -1);

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

--
--  Daten für Tabelle `enablingObjectives`
--

INSERT INTO `enablingObjectives` (`id`, `enabling_objective`, `description`, `curriculum_id`, `terminal_objective_id`, `creation_time`, `creator_id`, `repeat_interval`, `order_id`) VALUES
(488, 'sich artikuliert, verstÃ¤ndlich, sach- und situationsangemessen Ã¤uÃŸern', '', 94, 188, '2013-04-04 06:22:52', 0, -1, 0),
(490, 'Ã¼ber einen umfangreichen und differenzierten Wortschatz verfÃ¼gen', '', 94, 188, '2013-04-04 06:27:05', 0, -1, 0),
(491, 'verschiedene Formen mÃ¼ndlicher Darstellung unterscheiden und anwenden, insbesondere erzÃ¤hlen, berichten, informieren, beschreiben, schildern, appellieren, argumentieren, erÃ¶rtern', '', 94, 188, '2013-04-04 06:29:06', 0, -1, 0),
(492, 'Wirkungen der Redeweise kennen, beachten und situations- sowie adressatengerecht anwenden: LautstÃ¤rke, Betonung, Sprechtempo, Klangfarbe, StimmfÃ¼hrung; KÃ¶rpersprache (Gestik, Mimik)', '', 94, 188, '2013-04-04 06:29:18', 0, -1, 0),
(493, 'unterschiedliche Sprechsituationen gestalten, insbesondere VorstellungsgesprÃ¤ch/ BewerbungsgesprÃ¤ch; Antragstellung, Beschwerde, Entschuldigung; GesprÃ¤chsleitung.', '', 94, 188, '2013-04-04 06:29:30', 0, -1, 0),
(494, 'Texte sinngebend und gestaltend vorlesen und (frei) vortragen', '', 94, 190, '2013-04-04 06:29:56', 0, -1, 0),
(495, 'lÃ¤ngere freie RedebeitrÃ¤ge leisten, Kurzdarstellungen und Referate frei vortragen: ggf. mit Hilfe eines Stichwortzettels/einer Gliederung,', '', 94, 190, '2013-04-04 06:30:06', 0, -1, 0),
(496, 'verschiedene Medien fÃ¼r die Darstellung von Sachverhalten nutzen (PrÃ¤sentationstechniken): z.B. Tafel, Folie, Plakat, Moderationskarten', '', 94, 190, '2013-04-04 06:30:24', 0, -1, 0),
(497, 'sich konstruktiv an einem GesprÃ¤ch beteiligen', '', 94, 191, '2013-04-04 07:04:59', 0, -1, 0),
(498, 'durch gezieltes Fragen notwendige Informationen beschaffen', '', 94, 191, '2013-04-04 07:05:10', 0, -1, 0),
(499, 'GesprÃ¤chsregeln einhalten', '', 94, 191, '2013-04-04 07:05:52', 0, -1, 0),
(500, 'die eigene Meinung begrÃ¼ndet und nachvollziehbar vertreten', '', 94, 191, '2013-04-04 07:06:02', 0, -1, 0),
(501, 'auf Gegenpositionen sachlich und argumentierend eingehen', '', 94, 191, '2013-04-04 07:06:12', 0, -1, 0),
(502, 'kriterienorientiert das eigene GesprÃ¤chsverhalten und das anderer beobachten, reflektieren und bewerten', '', 94, 191, '2013-04-04 07:06:22', 0, -1, 0),
(503, 'GesprÃ¤chsbeitrÃ¤ge anderer verfolgen und aufnehmen', '', 94, 192, '2013-04-04 07:06:53', 0, -1, 0),
(504, 'wesentliche Aussagen aus umfangreichen gesprochenen Texten verstehen, diese Informationen sichern und wiedergeben', '', 94, 192, '2013-04-04 07:07:07', 0, -1, 0),
(505, 'Aufmerksamkeit fÃ¼r verbale und nonverbale Ã„uÃŸerungen (z.B. StimmfÃ¼hrung, KÃ¶rpersprache) entwickeln', '', 94, 192, '2013-04-04 07:07:32', 0, -1, 0),
(506, 'eigene Erlebnisse, Haltungen, Situationen szenisch darstellen', '', 94, 193, '2013-04-04 07:08:30', 0, -1, 0),
(507, 'Texte (medial unterschiedlich vermittelt) szenisch gestalten', '', 94, 193, '2013-04-04 07:08:41', 0, -1, 0),
(508, 'verschiedene GesprÃ¤chsformen praktizieren, z.B. Dialoge, StreitgesprÃ¤che, Diskussionen, Rollendiskussionen, Debatten vorbereiten und durchfÃ¼hren', '', 94, 194, '2013-04-04 07:09:23', 0, -1, 0),
(509, 'GesprÃ¤chsformen moderieren, leiten, beobachten, reflektieren', '', 94, 194, '2013-04-04 07:09:41', 0, -1, 0),
(510, 'Redestrategien einsetzen: z.B. FÃ¼nfsatz, AnknÃ¼pfungen formulieren, rhetorische Mittel verwenden', '', 94, 194, '2013-04-04 07:09:52', 0, -1, 0),
(511, 'sich gezielt sachgerechte StichwÃ¶rter aufschreiben', '', 94, 194, '2013-04-04 07:10:01', 0, -1, 0),
(512, 'eine Mitschrift anfertigen', '', 94, 194, '2013-04-04 07:10:10', 0, -1, 0),
(513, 'Notizen selbststÃ¤ndig strukturieren und Notizen zur Reproduktion des GehÃ¶rten nutzen, dabei sachlogische sprachliche VerknÃ¼pfungen herstellen', '', 94, 194, '2013-04-04 07:10:24', 0, -1, 0),
(514, 'Video-Feedback nutzen', '', 94, 194, '2013-04-04 07:10:35', 0, -1, 0),
(515, 'Portfolio (Sammlung und Vereinbarungen Ã¼ber GesprÃ¤chsregeln, Kriterienlisten, Stichwortkonzepte, SelbsteinschÃ¤tzungen, BeobachtungsbÃ¶gen von anderen, vereinbarte Lernziele etc.) nutzen.', '', 94, 194, '2013-04-04 07:11:00', 0, -1, 0),
(516, 'Texte in gut lesbarer handschriftlicher Form und in einem der Situation entsprechenden Tempo schreiben', '', 94, 195, '2013-04-04 07:11:45', 0, -1, 0),
(517, 'Texte dem Zweck entsprechend und adressatengerecht gestalten, sinnvoll aufbauen und strukturieren: z.B. Blattaufteilung, Rand, AbsÃ¤tze', '', 94, 195, '2013-04-04 07:11:57', 0, -1, 0),
(518, 'Textverarbeitungsprogramme und ihre MÃ¶glichkeiten nutzen: z.B. Formatierung, PrÃ¤sentation', '', 94, 195, '2013-04-04 07:12:06', 0, -1, 0),
(519, 'Formulare ausfÃ¼llen', '', 94, 195, '2013-04-04 07:12:16', 0, -1, 0),
(520, 'Grundregeln der Rechtschreibung und Zeichensetzung sicher beherrschen und hÃ¤ufig vorkommende WÃ¶rter, Fachbegriffe und FremdwÃ¶rter richtig schreiben', '', 94, 196, '2013-04-04 07:12:47', 0, -1, 0),
(521, 'individuelle Fehlerschwerpunkte erkennen und mit Hilfe von Rechtschreibstrategien abbauen, insbesondere Nachschlagen, Ableiten, Wortverwandtschaften suchen, grammatisches Wissen anwenden', '', 94, 196, '2013-04-04 07:13:07', 0, -1, 0),
(522, 'gemÃ¤ÃŸ den Aufgaben und der Zeitvorgabe einen Schreibplan erstellen, sich fÃ¼r die angemessene Textsorte entscheiden und Texte ziel-, adressaten- und situationsbezogen, ggf. materialorientiert konzipieren', '', 94, 197, '2013-04-04 07:14:30', 0, -1, 0),
(523, 'Informationsquellen gezielt nutzen, insbesondere Bibliotheken, Nachschlagewerke, Zeitungen, Internet', '', 94, 197, '2013-04-04 07:14:39', 0, -1, 0),
(524, 'Stoffsammlung erstellen, ordnen und eine Gliederung anfertigen: z.B. numerische Gliederung, Cluster, Ideenstern, Mindmap, Flussdiagramm', '', 94, 197, '2013-04-04 07:14:50', 0, -1, 0),
(525, 'formalisierte lineare Texte/nichtlineare Texte verfassen: z.B. sachlicher Brief, Lebenslauf, Bewerbung, Bewerbungsschreiben, Protokoll, Annonce/AusfÃ¼llen von Formularen, Diagramm, Schaubild, Statistik', '', 94, 198, '2013-04-04 07:15:49', 0, -1, 0),
(526, 'zentrale Schreibformen beherrschen und sachgerecht nutzen: informierende (berichten, beschreiben, schildern), argumentierende (erÃ¶rtern, kommentieren), appellierende, untersuchende (analysieren, interpretieren), gestaltende (erzÃ¤hlen, kreativ schreiben)', '', 94, 198, '2013-04-04 07:16:17', 0, -1, 0),
(527, 'produktive Schreibformen nutzen: z.B. umschreiben, weiterschreiben, ausgestalten', '', 94, 198, '2013-04-04 07:16:30', 0, -1, 0),
(528, 'Ergebnisse einer Textuntersuchung darstellen: z.B. â€“ Inhalte auch lÃ¤ngerer und komplexerer Texte verkÃ¼rzt und abstrahierend wiedergeben', '', 94, 198, '2013-04-04 07:17:03', 0, -1, 0),
(529, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Informationen aus linearen und nichtlinearen Texten zusammenfassen und so wiedergeben, dass insgesamt eine kohÃ¤rente Darstellung entsteht', '', 94, 198, '2013-04-04 07:18:50', 0, -1, 0),
(530, 'Ergebnisse einer Textuntersuchung darstellen: z.B. formale und sprachlich stilistische Gestaltungsmittel und ihre Wirkungsweise an Beispielen darstellen', '', 94, 198, '2013-04-04 07:19:08', 0, -1, 0),
(531, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Textdeutungen begrÃ¼nden', '', 94, 198, '2013-04-04 07:19:25', 0, -1, 0),
(532, 'Ergebnisse einer Textuntersuchung darstellen: z.B. sprachliche Bilder deuten', '', 94, 198, '2013-04-04 07:19:40', 0, -1, 0),
(533, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Thesen formulieren', '', 94, 198, '2013-04-04 07:22:25', 0, -1, 0),
(534, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Argumente zu einer Argumentationskette verknÃ¼pfen', '', 94, 198, '2013-04-04 07:22:46', 0, -1, 0),
(535, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Gegenargumente formulieren, Ã¼berdenken und einbeziehen', '', 94, 198, '2013-04-04 07:23:02', 0, -1, 0),
(536, 'Ergebnisse einer Textuntersuchung darstellen: z.B. Argumente gewichten und SchlÃ¼sse ziehen', '', 94, 198, '2013-04-04 07:23:14', 0, -1, 0),
(537, 'Ergebnisse einer Textuntersuchung darstellen: z.B. begrÃ¼ndet Stellung nehmen', '', 94, 198, '2013-04-04 07:23:27', 0, -1, 0),
(538, 'Texte sprachlich gestalten â€“ strukturiert, verstÃ¤ndlich, sprachlich variabel und stilistisch stimmig zur Aussage schreiben', '', 94, 198, '2013-04-04 07:23:45', 0, -1, 0),
(539, 'Texte sprachlich gestalten â€“ sprachliche Mittel gezielt einsetzen: z.B. Vergleiche, Bilder, Wiederholung,', '', 94, 198, '2013-04-04 07:24:11', 0, -1, 0),
(540, 'Texte mit Hilfe von neuen Medien verfassen: z.B. E-Mails, Chatroom', '', 94, 198, '2013-04-04 07:24:27', 0, -1, 0),
(541, 'Aufbau, Inhalt und Formulierungen eigener Texte hinsichtlich der Aufgabenstellung Ã¼berprÃ¼fen (Schreibsituation, Schreibanlass)', '', 94, 199, '2013-04-04 07:25:15', 0, -1, 0),
(542, 'Strategien zur ÃœberprÃ¼fung der sprachlichen Richtigkeit und Rechtschreibung anwenden', '', 94, 199, '2013-04-04 07:25:29', 0, -1, 0),
(543, 'Vorgehensweise aus Aufgabenstellung herleiten', '', 94, 200, '2013-04-04 07:25:58', 0, -1, 0),
(544, 'ArbeitsplÃ¤ne/Konzepte entwerfen, Arbeitsschritte festlegen: Informationen sammeln, ordnen, ergÃ¤nzen', '', 94, 200, '2013-04-04 07:26:09', 0, -1, 0),
(545, 'Fragen und Arbeitshypothesen formulieren', '', 94, 200, '2013-04-04 07:26:19', 0, -1, 0),
(546, 'Texte inhaltlich und sprachlich Ã¼berarbeiten: z. B. Textpassagen umstellen, Wirksamkeit und Angemessenheit sprachlicher Gestaltungsmittel prÃ¼fen', '', 94, 200, '2013-04-04 07:26:38', 0, -1, 0),
(547, 'Zitate in den eigenen Text integrieren', '', 94, 200, '2013-04-04 07:26:48', 0, -1, 0),
(548, 'Einhaltung orthografischer und grammatischer Normen kontrollieren', '', 94, 200, '2013-04-04 07:26:59', 0, -1, 0),
(549, 'mit Textverarbeitungsprogrammen umgehen', '', 94, 200, '2013-04-04 07:27:09', 0, -1, 0),
(550, 'Schreibkonferenzen/ Schreibwerkstatt durchfÃ¼hren', '', 94, 200, '2013-04-04 07:27:21', 0, -1, 0),
(551, 'Portfolio (selbst verfasste und fÃ¼r gut befundene Texte, Kriterienlisten, Stichwortkonzepte, SelbsteinschÃ¤tzungen, BeobachtungsbÃ¶gen von anderen, vereinbarte Lernziele etc.) anlegen und nutzen', '', 94, 200, '2013-04-04 07:27:36', 0, -1, 0),
(552, 'Ã¼ber grundlegende Lesefertigkeiten verfÃ¼gen: flÃ¼ssig, sinnbezogen, Ã¼berfliegend, selektiv, navigierend (z.B. Bild-Ton-Text integrierend) lesen', '', 94, 201, '2013-04-04 07:28:38', 0, -1, 0),
(553, 'Leseerwartungen und -erfahrungen bewusst nutzen', '', 94, 202, '2013-04-04 07:29:07', 0, -1, 0),
(554, 'Wortbedeutungen klÃ¤ren', '', 94, 202, '2013-04-04 07:29:17', 0, -1, 0),
(555, 'Textschemata erfassen: z.B. Textsorte, Aufbau des Textes', '', 94, 202, '2013-04-04 07:29:26', 0, -1, 0),
(556, 'Verfahren zur Textstrukturierung kennen und selbststÃ¤ndig anwenden: z.B. ZwischenÃ¼berschriften formulieren, wesentliche Textstellen kennzeichnen, BezÃ¼ge zwischen Textteilen herstellen, Fragen aus dem Text ableiten und beantworten', '', 94, 202, '2013-04-04 07:29:42', 0, -1, 0),
(557, 'Verfahren zur Textaufnahme kennen und nutzen: z.B. Aussagen erklÃ¤ren und konkretisieren, StichwÃ¶rter formulieren, Texte und Textabschnitte zusammenfassen', '', 94, 202, '2013-04-04 07:29:59', 0, -1, 0),
(558, 'ein Spektrum altersangemessener Werke â€“ auch Jugendliteratur â€“ bedeutender Autorinnen und Autoren kennen', '', 94, 203, '2013-04-04 07:31:29', 0, -1, 0),
(559, 'epische, lyrische, dramatische Texte unterscheiden, insbesondere epische Kleinformen, Novelle, lÃ¤ngere ErzÃ¤hlung, Kurzgeschichte, Roman, Schauspiel, Gedichte', '', 94, 203, '2013-04-04 07:31:50', 0, -1, 0),
(560, 'ZusammenhÃ¤nge zwischen Text, Entstehungszeit und Leben des Autors/der Autorin bei der Arbeit an Texten aus Gegenwart und Vergangenheit herstellen', '', 94, 203, '2013-04-04 07:32:07', 0, -1, 0),
(561, 'zentrale Inhalte erschlieÃŸen', '', 94, 203, '2013-04-04 07:32:19', 0, -1, 0),
(562, 'wesentliche Elemente eines Textes erfassen: z.B. Figuren, Raum- und Zeitdarstellung, Konfliktverlauf', '', 94, 203, '2013-04-04 07:32:35', 0, -1, 0),
(563, 'wesentliche Fachbegriffe zur ErschlieÃŸung von Literatur kennen und anwenden, insbesondere ErzÃ¤hler, ErzÃ¤hlperspektive, Monolog, Dialog, sprachliche Bilder, Metapher, Reim, lyrisches Ich', '', 94, 203, '2013-04-04 07:32:47', 0, -1, 0),
(564, 'sprachliche Gestaltungsmittel in ihren WirkungszusammenhÃ¤ngen und in ihrer historischen Bedingtheit erkennen: z.B. Wort-, Satz- und Gedankenfiguren, Bildsprache (Metaphern),', '', 94, 203, '2013-04-04 07:33:02', 0, -1, 0),
(565, 'eigene Deutungen des Textes entwickeln, am Text belegen und sich mit anderen darÃ¼ber verstÃ¤ndigen', '', 94, 203, '2013-04-04 07:33:20', 0, -1, 0),
(566, 'analytische Methoden anwenden: z.B. Texte untersuchen, vergleichen, kommentieren', '', 94, 203, '2013-04-04 07:33:31', 0, -1, 0),
(567, 'produktive Methoden anwenden: z.B. Perspektivenwechsel: innerer Monolog, Brief in der Rolle einer literarischen Figur; szenische Umsetzung, Paralleltext, weiterschreiben, in eine andere Textsorte umschreiben', '', 94, 203, '2013-04-04 07:33:45', 0, -1, 0),
(568, 'Handlungen, Verhaltensweisen und Verhaltensmotive bewerten', '', 94, 203, '2013-04-04 07:33:56', 0, -1, 0),
(569, 'verschiedene Textfunktionen und Textsorten unterscheiden: z.B. informieren: Nachricht; appellieren: Kommentar, Rede; regulieren: Gesetz, Vertrag; instruieren: Gebrauchsanweisung,', '', 94, 204, '2013-04-04 07:34:35', 0, -1, 0),
(570, 'ein breites Spektrum auch lÃ¤ngerer und komplexerer Texte verstehen und im Detail erfassen', '', 94, 204, '2013-04-04 07:34:44', 0, -1, 0),
(571, 'Informationen zielgerichtet entnehmen, ordnen, vergleichen, prÃ¼fen und ergÃ¤nzen', '', 94, 204, '2013-04-04 07:34:55', 0, -1, 0),
(572, 'nichtlineare Texte auswerten: z.B. Schaubilder', '', 94, 204, '2013-04-04 07:35:05', 0, -1, 0),
(573, 'ntention(en) eines Textes erkennen, insbesondere Zusammenhang zwischen Autorintention(en), Textmerkmalen, Leseerwartungen und Wirkungen', '', 94, 204, '2013-04-04 07:35:14', 0, -1, 0),
(574, 'aus Sach- und Gebrauchstexten begrÃ¼ndete Schlussfolgerungen ziehen', '', 94, 204, '2013-04-04 07:35:27', 0, -1, 0),
(575, 'Information und Wertung in Texten unterscheiden', '', 94, 204, '2013-04-04 07:35:37', 0, -1, 0),
(576, 'Informations- und Unterhaltungsfunktion unterscheiden', '', 94, 205, '2013-04-04 07:36:02', 0, -1, 0),
(577, 'medienspezifische Formen kennen: z.B. Print- und Online-Zeitungen, Infotainment, Hypertexte, Werbekommunikation, Film', '', 94, 205, '2013-04-04 07:36:13', 0, -1, 0),
(578, 'Intentionen und Wirkungen erkennen und bewerten', '', 94, 205, '2013-04-04 07:36:22', 0, -1, 0),
(579, 'wesentliche Darstellungsmittel kennen und deren Wirkungen einschÃ¤tzen', '', 94, 205, '2013-04-04 07:36:32', 0, -1, 0),
(580, 'zwischen eigentlicher Wirklichkeit und virtuellen Welten in Medien unterscheiden: z.B. Fernsehserien, Computerspiele', '', 94, 205, '2013-04-04 07:36:46', 0, -1, 0),
(581, 'InformationsmÃ¶glichkeiten nutzen: z.B. Informationen zu einem Thema/ Problem in unterschiedlichen Medien suchen, vergleichen, auswÃ¤hlen und bewerten (Suchstrategien)', '', 94, 205, '2013-04-04 07:37:04', 0, -1, 0),
(582, 'Medien zur PrÃ¤sentation und Ã¤sthetischen Produktion nutzen', '', 94, 205, '2013-04-04 07:37:18', 0, -1, 0),
(583, 'Exzerpieren, Zitieren, Quellen angeben', '', 94, 206, '2013-04-04 07:38:02', 0, -1, 0),
(584, 'Wesentliches hervorheben und ZusammenhÃ¤nge verdeutlichen', '', 94, 206, '2013-04-04 07:38:11', 0, -1, 0),
(585, 'Nachschlagewerke zur KlÃ¤rung von Fachbegriffen, FremdwÃ¶rtern und Sachfragen heranziehen', '', 94, 206, '2013-04-04 07:38:20', 0, -1, 0),
(586, 'Texte zusammenfassen: z.B. im Nominalstil, mit Hilfe von StichwÃ¶rtern, Symbolen, Farbmarkierungen, Unterstreichungen', '', 94, 206, '2013-04-04 07:38:30', 0, -1, 0),
(587, 'Inhalte mit eigenen Worten wiedergeben, Randbemerkungen setzen', '', 94, 206, '2013-04-04 07:38:41', 0, -1, 0),
(588, 'Texte gliedern und TeilÃ¼berschriften finden', '', 94, 206, '2013-04-04 07:38:58', 0, -1, 0),
(589, 'Inhalte veranschaulichen: z. B. durch Mindmap, Flussdiagramm', '', 94, 206, '2013-04-04 07:39:10', 0, -1, 0),
(590, 'PrÃ¤sentationstechniken anwenden: Medien zielgerichtet und sachbezogen einsetzen: z.B. Tafel, Folie, Plakat, PC-PrÃ¤sentationsprogramm', '', 94, 206, '2013-04-04 07:48:57', 0, -1, 0),
(592, 'nutzen sinntragende Vorstellungen von rationalen Zahlen, insbesonÂ­dere von natÃ¼rlichen, ganzen und gebrochenen Zahlen entsprechend der Verwendungsnotwendigkeit', '', 89, 207, '2013-04-04 09:10:53', 0, -1, 0),
(593, 'tellen Zahlen der Situation angemessen dar, unter anderem in ZehÂ­nerpotenzschreibweise', '', 89, 207, '2013-04-04 09:11:26', 0, -1, 0),
(594, 'begrÃ¼nden die Notwendigkeit von Zahlbereichserweiterungen an Beispielen', '', 89, 207, '2013-04-04 09:11:37', 0, -1, 0),
(595, 'nutzen Rechengesetze, auch zum vorteilhaften Rechnen', '', 89, 207, '2013-04-04 09:11:46', 0, -1, 0),
(596, 'nutzen zur Kontrolle Ãœberschlagsrechnungen und andere Verfahren', '', 89, 207, '2013-04-04 09:11:55', 0, -1, 0),
(597, 'runden Rechenergebnisse entsprechend dem Sachverhalt sinnvoll', '', 89, 207, '2013-04-04 09:12:06', 0, -1, 0),
(598, 'verwenden Prozent- und Zinsrechnung sachgerecht', '', 89, 207, '2013-04-04 09:12:20', 0, -1, 0),
(599, 'erlÃ¤utern an Beispielen den Zusammenhang zwischen RechenoperaÂ­tionen und deren Umkehrungen und nutzen diese ZusammenhÃ¤nge', '', 89, 207, '2013-04-04 09:12:33', 0, -1, 0),
(600, 'wÃ¤hlen, beschreiben und bewerten Vorgehensweisen und Verfahren, denen Algorithmen bzw. KalkÃ¼le zu Grunde liegen', '', 89, 207, '2013-04-04 09:12:44', 0, -1, 0),
(601, 'fÃ¼hren in konkreten Situationen kombinatorische Ãœberlegungen durch, um die Anzahl der jeweiligen MÃ¶glichkeiten zu bestimmen', '', 89, 207, '2013-04-04 09:12:55', 0, -1, 0),
(602, 'prÃ¼fen und interpretieren Ergebnisse in Sachsituationen unter EinbeÂ­ziehung einer kritischen EinschÃ¤tzung des gewÃ¤hlten Modells und seiner Bearbeitung', '', 89, 207, '2013-04-04 09:13:08', 0, -1, 0),
(603, 'nutzen das Grundprinzip des Messens, insbesondere bei der LÃ¤ngen-, FlÃ¤chen- und Volumenmessung, auch in Naturwissenschaften und in anderen Bereichen', '', 89, 208, '2013-04-04 09:14:04', 0, -1, 0),
(604, 'wÃ¤hlen Einheiten von GrÃ¶ÃŸen situationsgerecht aus (insbesondere fÃ¼r Zeit, Masse, Geld, LÃ¤nge, FlÃ¤che, Volumen und Winkel)', '', 89, 208, '2013-04-04 09:14:54', 0, -1, 0),
(605, 'schÃ¤tzen GrÃ¶ÃŸen mit Hilfe von Vorstellungen Ã¼ber geeignete ReprÃ¤Â­sentanten', '', 89, 208, '2013-04-04 09:15:05', 0, -1, 0),
(606, 'berechnen FlÃ¤cheninhalt und Umfang von Rechteck, Dreieck und Kreis sowie daraus zusammengesetzten Figuren', '', 89, 208, '2013-04-04 09:15:14', 0, -1, 0),
(607, 'berechnen Volumen und OberflÃ¤cheninhalt von Prisma, Pyramide, Zylinder, Kegel und Kugel sowie daraus zusammengesetzten KÃ¶rÂ­pern', '', 89, 208, '2013-04-04 09:15:24', 0, -1, 0),
(608, 'berechnen StreckenlÃ¤ngen und WinkelgrÃ¶ÃŸen, auch unter Nutzung von trigonometrischen Beziehungen und Ã„hnlichkeitsbeziehungen', '', 89, 208, '2013-04-04 09:15:33', 0, -1, 0),
(609, 'nehmen in ihrer Umwelt gezielt Messungen vor, entnehmen MaÃŸangaÂ­ben aus Quellenmaterial, fÃ¼hren damit Berechnungen durch und beÂ­werten die Ergebnisse sowie den gewÃ¤hlten Weg in Bezug auf die Sachsituation', '', 89, 208, '2013-04-04 09:15:50', 0, -1, 0),
(610, 'erkennen und beschreiben geometrische Strukturen in der Umwelt', '', 89, 209, '2013-04-04 09:16:40', 0, -1, 0),
(611, 'operieren gedanklich mit Strecken, FlÃ¤chen und KÃ¶rpern', '', 89, 209, '2013-04-04 09:16:53', 0, -1, 0),
(612, 'stellen geometrische Figuren im kartesischen Koordinatensystem dar', '', 89, 209, '2013-04-04 09:17:01', 0, -1, 0),
(613, 'stellen KÃ¶rper (z. B. als Netz, SchrÃ¤gbild oder Modell) dar und erkenÂ­nen KÃ¶rper aus ihren entsprechenden Darstellungen', '', 89, 209, '2013-04-04 09:17:13', 0, -1, 0),
(614, 'analysieren und klassifizieren geometrische Objekte der Ebene und des Raumes', '', 89, 209, '2013-04-04 09:17:22', 0, -1, 0),
(615, 'beschreiben und begrÃ¼nden Eigenschaften und Beziehungen geometÂ­rischer Objekte (wie Symmetrie, Kongruenz, Ã„hnlichkeit, LagebezieÂ­hungen) und nutzen diese im Rahmen des ProblemlÃ¶sens zur Analyse von SachzusammenhÃ¤ngen', '', 89, 209, '2013-04-04 09:17:41', 0, -1, 0),
(616, 'wenden SÃ¤tze der ebenen Geometrie bei Konstruktionen, BerechnunÂ­gen und Beweisen an, insbesondere den Satz des Pythagoras und den Satz des Thales', '', 89, 209, '2013-04-04 09:18:01', 0, -1, 0),
(617, 'zeichnen und konstruieren geometrische Figuren unter Verwendung angemessener Hilfsmittel wie Zirkel, Lineal, Geodreieck oder dynaÂ­mische Geometriesoftware,', '', 89, 209, '2013-04-04 09:18:15', 0, -1, 0),
(618, 'untersuchen Fragen der LÃ¶sbarkeit und LÃ¶sungsvielfalt von KonÂ­struktionsaufgaben und formulieren diesbezÃ¼glich Aussagen', '', 89, 209, '2013-04-04 09:18:29', 0, -1, 0),
(619, 'setzen geeignete Hilfsmittel beim explorativen Arbeiten und ProbÂ­lemlÃ¶sen ein', '', 89, 209, '2013-04-04 09:18:49', 0, -1, 0),
(620, 'nutzen Funktionen als Mittel zur Beschreibung quantitativer ZusamÂ­ menhÃ¤nge', '', 89, 210, '2013-04-04 09:19:32', 0, -1, 0),
(621, 'erkennen und beschreiben funktionale ZusammenhÃ¤nge und stellen diese in sprachlicher, tabellarischer oder graphischer Form sowie geÂ­gebenenfalls als Term dar', '', 89, 210, '2013-04-04 09:20:02', 0, -1, 0),
(622, 'analysieren, interpretieren und vergleichen unterschiedliche DarstelÂ­lungen funktionaler ZusammenhÃ¤nge (wie lineare, proportionale und antiproportionale)', '', 89, 210, '2013-04-04 09:20:23', 0, -1, 0),
(623, 'lÃ¶sen realitÃ¤tsnahe Probleme im Zusammenhang mit linearen, proÂ­portionalen und antiproportionalen Zuordnungen', '', 89, 210, '2013-04-04 09:20:37', 0, -1, 0),
(624, 'interpretieren lineare Gleichungssysteme graphisch', '', 89, 210, '2013-04-04 09:20:50', 0, -1, 0),
(625, 'lÃ¶sen Gleichungen, und lineare Gleichungssysteme kalkÃ¼lmÃ¤ÃŸig bzw. algorithmisch, auch unter Einsatz geeigneter Software, und vergleiÂ­chen ggf. die EffektivitÃ¤t ihres Vorgehens mit anderen LÃ¶sungsverÂ­fahren (wie mit inhaltlichem LÃ¶sen oder LÃ¶sen durch systematisches Probieren)', '', 89, 210, '2013-04-04 09:21:09', 0, -1, 0),
(626, 'untersuchen Fragen der LÃ¶sbarkeit und LÃ¶sungsvielfalt von linearen und quadratischen Gleichungen sowie linearen Gleichungssystemen und formulieren diesbezÃ¼glich Aussagen', '', 89, 210, '2013-04-04 09:21:19', 0, -1, 0),
(627, 'bestimmen kennzeichnende Merkmale von Funktionen und stellen Beziehungen zwischen Funktionsterm und Graph her', '', 89, 210, '2013-04-04 09:21:30', 0, -1, 0),
(628, 'wenden insbesondere lineare und quadratische Funktionen sowie ExÂ­ponentialfunktionen bei der Beschreibung und Bearbeitung von Problemen an', '', 89, 210, '2013-04-04 09:21:44', 0, -1, 0),
(629, 'verwenden die Sinusfunktion zur Beschreibung von periodischen VorgÃ¤ngen', '', 89, 210, '2013-04-04 09:21:54', 0, -1, 0),
(630, 'beschreiben VerÃ¤nderungen von GrÃ¶ÃŸen mittels Funktionen, auch unÂ­ter Verwendung eines Tabellenkalkulationsprogramms', '', 89, 210, '2013-04-04 09:22:08', 0, -1, 0),
(631, 'geben zu vorgegebenen Funktionen Sachsituationen an, die mit Hilfe dieser Funktion beschrieben werden kÃ¶nnen.', '', 89, 210, '2013-04-04 09:22:18', 0, -1, 0),
(632, 'werten graphische Darstellungen und Tabellen von statistischen Erhebungen aus', '', 89, 211, '2013-04-04 09:22:55', 0, -1, 0),
(633, 'planen statistische Erhebungen', '', 89, 211, '2013-04-04 09:23:04', 0, -1, 0),
(634, 'ammeln systematisch Daten, erfassen sie in Tabellen und stellen sie graphisch dar, auch unter Verwendung geeigneter Hilfsmittel (wie Software)', '', 89, 211, '2013-04-04 09:23:13', 0, -1, 0),
(635, 'interpretieren Daten unter Verwendung von KenngrÃ¶ÃŸen', '', 89, 211, '2013-04-04 09:23:19', 0, -1, 0),
(636, 'reflektieren und bewerten Argumente, die auf einer Datenanalyse baÂ­sieren', '', 89, 211, '2013-04-04 09:23:27', 0, -1, 0),
(637, 'beschreiben Zufallserscheinungen in alltÃ¤glichen Situationen', '', 89, 211, '2013-04-04 09:23:34', 0, -1, 0),
(638, 'bestimmen Wahrscheinlichkeiten bei Zufallsexperimenten', '', 89, 211, '2013-04-04 09:23:43', 0, -1, 0),
(639, 'Fragen stellen, die fÃ¼r die Mathematik charakteristisch sind (â€žGibt es ...?â€œ, â€žWie verÃ¤ndert sich...?â€œ, â€žIst das immer so ...?â€œ) und VermutunÂ­gen begrÃ¼ndet Ã¤uÃŸern', '', 89, 212, '2013-04-04 09:25:36', 0, -1, 0),
(640, 'mathematische Argumentationen entwickeln (wie ErlÃ¤uterungen, BeÂ­ grÃ¼ndungen, Beweise)', '', 89, 212, '2013-04-04 09:25:47', 0, -1, 0),
(641, 'LÃ¶sungswege beschreiben und begrÃ¼nden', '', 89, 212, '2013-04-04 09:25:57', 0, -1, 0),
(642, 'vorgegebene und selbst formulierte Probleme bearbeiten', '', 89, 213, '2013-04-04 09:26:56', 0, -1, 0),
(643, 'geeignete heuristische Hilfsmittel, Strategien und Prinzipien zum ProblemlÃ¶sen auswÃ¤hlen und anwenden,', '', 89, 213, '2013-04-04 09:27:32', 0, -1, 0),
(644, 'die PlausibilitÃ¤t der Ergebnisse Ã¼berprÃ¼fen sowie das Finden von LÃ¶sungsideen und die LÃ¶sungswege reflektieren', '', 89, 213, '2013-04-04 09:27:45', 0, -1, 0),
(645, 'den Bereich oder die Situation, die modelliert werden soll, in matheÂ­matische Begriffe, Strukturen und Relationen Ã¼bersetzen', '', 89, 214, '2013-04-04 09:28:25', 0, -1, 0),
(646, 'den Bereich oder die Situation, die modelliert werden soll, in mathematische Begriffe, Strukturen und Relationen Ã¼bersetzen', '', 89, 214, '2013-04-04 09:29:01', 0, -1, 0),
(647, 'Ergebnisse in dem entsprechenden Bereich oder der entsprechenden Situation interpretieren und prÃ¼fen', '', 89, 214, '2013-04-04 09:29:10', 0, -1, 0),
(648, 'verschiedene Formen der Darstellung von mathematischen Objekten und Situationen anwenden, interpretieren und unterscheiden', '', 89, 215, '2013-04-04 09:29:38', 0, -1, 0),
(649, 'Beziehungen zwischen Darstellungsformen erkennen', '', 89, 215, '2013-04-04 09:29:46', 0, -1, 0),
(650, 'unterschiedliche Darstellungsformen je nach Situation und Zweck auswÃ¤hlen und zwischen ihnen wechseln', '', 89, 215, '2013-04-04 09:29:51', 0, -1, 0),
(651, 'mit Variablen, Termen, Gleichungen, Funktionen, Diagrammen, Tabellen arbeiten', '', 89, 216, '2013-04-04 09:30:34', 0, -1, 0),
(652, 'symbolische und formale Sprache in natÃ¼rliche Sprache Ã¼bersetzen und umgekehrt', '', 89, 216, '2013-04-04 09:30:41', 0, -1, 0),
(653, 'LÃ¶sungs- und Kontrollverfahren ausfÃ¼hren', '', 89, 216, '2013-04-04 09:30:48', 0, -1, 0),
(654, 'mathematische Werkzeuge (wie Formelsammlungen, Taschenrechner, Software) sinnvoll und verstÃ¤ndig einsetzen', '', 89, 216, '2013-04-04 09:31:23', 0, -1, 0),
(655, 'Ìˆberlegungen, LÃ¶sungswege bzw. Ergebnisse dokumentieren, verstÃ¤ndlich darstellen und prÃ¤sentieren, auch unter Nutzung geeigneter Medien', '', 89, 217, '2013-04-04 09:31:33', 0, -1, 0),
(656, 'die Fachsprache adressatengerecht verwenden', '', 89, 217, '2013-04-04 09:31:44', 0, -1, 0),
(657, 'Ã„uÃŸerungen von anderen und Texte zu mathematischen Inhalten verstehen und Ã¼berprÃ¼fen', '', 89, 217, '2013-04-04 09:31:53', 0, -1, 0);

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
-- Tabellenstruktur für Tabelle `files_backup`
--

DROP TABLE IF EXISTS `files_backup`;
CREATE TABLE IF NOT EXISTS `files_backup` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL DEFAULT '',
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `curriculum_id` int(10) DEFAULT NULL,
  `creator_id` int(10) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

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

--
-- Daten für Tabelle `log`
--

INSERT INTO `log` (`id`, `creation_time`, `user_id`, `ip`, `action`, `url`, `info`) VALUES
(207, '2013-09-07 07:49:21', 77, '::1', 'view', 'http://localhost/curriculum/public/index.php?action=dashboard', 'dashboard'),
(208, '2013-09-07 16:34:49', 77, '::1', 'view', 'http://localhost/curriculum/public/index.php?action=dashboard', 'dashboard'),
(209, '2013-09-07 16:55:39', 77, '::1', 'view', 'http://localhost/curriculum/public/index.php?action=adminLog', 'adminLog');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `receiver_id` int(11) NOT NULL DEFAULT '0',
  `subject` text,
  `message` mediumtext,
  `status` smallint(1) DEFAULT '0' COMMENT '0 = ungelesen, 1 = gelesen, -1 = gelöscht',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `repeat_interval`
--

DROP TABLE IF EXISTS `repeat_interval`;
CREATE TABLE IF NOT EXISTS `repeat_interval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repeat_interval` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `repeat_interval`
--

INSERT INTO `repeat_interval` (`id`, `repeat_interval`) VALUES
(1, 1),
(2, 7),
(3, 30),
(4, 182),
(5, 365);


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role_capabilities`
--

DROP TABLE IF EXISTS `role_capabilities`;
CREATE TABLE IF NOT EXISTS `role_capabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `capability` varchar(240) NOT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=307 ;

--
-- Daten für Tabelle `role_capabilities`
--

INSERT INTO `role_capabilities` (`id`, `role_id`, `capability`, `permission`, `creation_time`, `creator_id`) VALUES
(1, 1, 'menu:readlogmenu', 1, '2013-10-07 17:47:49', -1),
(2, 0, 'menu:readlogmenu', 0, '2013-10-14 10:44:35', 102),
(3, 1, 'menu:readMyCurricula', 1, '2013-10-14 10:44:35', -1),
(4, 2, 'menu:readMyCurricula', 1, '2013-10-14 10:44:35', -1),
(5, 3, 'menu:readMyCurricula', 1, '2013-10-14 10:44:35', -1),
(6, 4, 'menu:readMyCurricula', 1, '2013-10-14 10:44:35', -1),
(7, 0, 'menu:readlogmenu', 0, '2013-10-14 11:01:07', 102),
(8, 1, 'menu:readInstitution', 1, '2013-10-14 11:01:07', -1),
(9, 2, 'menu:readInstitution', 1, '2013-10-14 11:01:07', -1),
(10, 3, 'menu:readInstitution', 1, '2013-10-14 11:01:07', -1),
(11, 4, 'menu:readInstitution', 1, '2013-10-14 11:01:07', -1),
(12, 1, 'menu:readProgress', 1, '2013-10-14 11:03:03', -1),
(13, 3, 'menu:readProgress', 1, '2013-10-14 11:03:03', -1),
(14, 4, 'menu:readProgress', 1, '2013-10-14 11:03:03', -1),
(15, 1, 'menu:readCurricula', 1, '2013-10-14 11:09:50', -1),
(16, 3, 'menu:readCurricula', 1, '2013-10-14 11:09:50', -1),
(17, 4, 'menu:readCurricula', 1, '2013-10-14 11:09:50', -1),
(18, 1, 'menu:readGroup', 1, '2013-10-14 11:12:49', -1),
(19, 4, 'menu:readGroup', 1, '2013-10-14 11:12:49', -1),
(20, 1, 'menu:readUserAdministration', 1, '2013-10-14 11:33:23', -1),
(21, 3, 'menu:readUserAdministration', 1, '2013-10-14 11:33:23', -1),
(22, 4, 'menu:readUserAdministration', 1, '2013-10-14 11:33:23', -1),
(23, 1, 'menu:readGrade', 1, '2013-10-14 11:34:07', -1),
(24, 4, 'menu:readGrade', 1, '2013-10-14 11:34:07', -1),
(25, 1, 'menu:readSubject', 1, '2013-10-14 11:36:37', -1),
(26, 4, 'menu:readSubject', 1, '2013-10-14 11:36:37', -1),
(27, 1, 'menu:readSemester', 1, '2013-10-14 11:37:27', -1),
(28, 4, 'menu:readSemester', 1, '2013-10-14 11:37:27', -1),
(29, 1, 'menu:readBackup', 1, '2013-10-14 11:37:53', -1),
(30, 3, 'menu:readBackup', 1, '2013-10-14 11:37:53', -1),
(31, 4, 'menu:readBackup', 1, '2013-10-14 11:37:53', -1),
(32, 1, 'menu:readConfirm', 1, '2013-10-14 11:39:46', -1),
(33, 4, 'menu:readConfirm', 1, '2013-10-14 11:39:46', -1),
(34, 1, 'menu:readInstitutionConfig', 1, '2013-10-14 11:43:08', -1),
(35, 4, 'menu:readInstitutionConfig', 1, '2013-10-14 11:43:08', -1),
(36, 0, 'menu:readlogmenu', 0, '2013-10-14 11:43:52', 102),
(37, 1, 'menu:readProfileConfig', 1, '2013-10-14 11:43:52', -1),
(38, 2, 'menu:readProfileConfig', 1, '2013-10-14 11:43:52', -1),
(39, 3, 'menu:readProfileConfig', 1, '2013-10-14 11:43:52', -1),
(40, 4, 'menu:readProfileConfig', 1, '2013-10-14 11:43:52', -1),
(41, 0, 'menu:readlogmenu', 0, '2013-10-25 06:50:56', 102),
(42, 1, 'page:readLog', 1, '2013-10-25 06:50:56', -1),
(43, 2, 'page:readLog', 1, '2013-10-25 06:50:56', -1),
(44, 3, 'page:readLog', 1, '2013-10-25 06:50:56', -1),
(45, 4, 'page:readLog', 1, '2013-10-25 06:50:56', -1),
(46, 0, 'menu:readlogmenu', 0, '2013-11-17 16:24:34', 102),
(47, 1, 'page:showRoleForm', 1, '2013-11-17 16:24:34', -1),
(48, 2, 'page:showRoleForm', 0, '2013-11-17 16:24:34', -1),
(49, 3, 'page:showRoleForm', 0, '2013-11-17 16:24:34', -1),
(50, 4, 'page:showRoleForm', 0, '2013-11-17 16:24:34', -1),
(51, 0, 'menu:readlogmenu', 0, '2013-11-17 19:09:23', 102),
(52, 1, 'menu:readRoles', 1, '2013-11-17 19:09:23', -1),
(53, 2, 'menu:readRoles', 0, '2013-11-17 19:09:23', -1),
(54, 3, 'menu:readRoles', 0, '2013-11-17 19:09:23', -1),
(55, 4, 'menu:readRoles', 0, '2013-11-17 19:09:23', -1),
(191, 0, 'menu:readInstitution', 0, '2013-11-26 23:45:20', 102),
(192, 0, 'menu:readProgress', 0, '2013-11-26 23:45:20', 102),
(193, 0, 'menu:readCurricula', 0, '2013-11-26 23:45:20', 102),
(194, 0, 'menu:readUserAdministration', 0, '2013-11-26 23:45:20', 102),
(195, 0, 'menu:readGrade', 0, '2013-11-26 23:45:20', 102),
(196, 0, 'menu:readSubject', 0, '2013-11-26 23:45:20', 102),
(197, 0, 'menu:readSemester', 0, '2013-11-26 23:45:20', 102),
(198, 0, 'menu:readBackup', 0, '2013-11-26 23:45:20', 102),
(199, 0, 'menu:readConfirm', 0, '2013-11-26 23:45:20', 102),
(200, 0, 'menu:readInstitutionConfig', 0, '2013-11-26 23:45:20', 102),
(201, 0, 'menu:readProfileConfig', 0, '2013-11-26 23:45:20', 102),
(202, 0, 'menu:readMyCurricula', 1, '2013-11-26 23:45:20', 102),
(203, 0, 'menu:readGroup', 0, '2013-11-26 23:45:20', 102),
(204, 0, 'page:readLog', 0, '2013-11-26 23:45:20', 102),
(205, 0, 'page:showRoleForm', 0, '2013-11-26 23:45:20', 102),
(206, 0, 'menu:readRoles', 0, '2013-11-26 23:45:20', 102),
(207, 0, 'profile:updateMyProfile', 1, '2014-01-28 13:14:53', -1),
(208, 1, 'profile:updateMyProfile', 1, '2014-01-28 13:14:53', -1),
(209, 2, 'profile:updateMyProfile', 1, '2014-01-28 13:14:53', -1),
(210, 3, 'profile:updateMyProfile', 1, '2014-01-28 13:14:53', -1),
(211, 4, 'profile:updateMyProfile', 1, '2014-01-28 13:14:53', -1),
(212, 0, 'profile:updateProfile', 0, '2014-01-29 13:59:27', -1),
(213, 1, 'profile:updateProfile', 1, '2014-01-29 13:59:27', -1),
(214, 2, 'profile:updateProfile', 0, '2014-01-29 13:59:27', -1),
(215, 3, 'profile:updateProfile', 0, '2014-01-29 13:59:27', -1),
(216, 4, 'profile:updateProfile', 0, '2014-01-29 13:59:27', -1),
(217, 0, 'user:addUser', 0, '2014-02-02 18:23:35', -1),
(218, 1, 'user:addUser', 1, '2014-02-02 18:23:35', -1),
(219, 2, 'user:addUser', 0, '2014-02-02 18:23:35', -1),
(220, 3, 'user:addUser', 1, '2014-02-02 18:23:35', -1),
(221, 4, 'user:addUser', 1, '2014-02-02 18:23:35', -1),
(222, 0, 'user:updateUser', 0, '2014-02-02 18:26:20', -1),
(223, 1, 'user:updateUser', 1, '2014-02-02 18:26:20', -1),
(224, 2, 'user:updateUser', 0, '2014-02-02 18:26:20', -1),
(225, 3, 'user:updateUser', 1, '2014-02-02 18:26:20', -1),
(226, 4, 'user:updateUser', 1, '2014-02-02 18:26:20', -1),
(227, 0, 'user:updateRole', 0, '2014-02-06 08:54:58', -1),
(228, 1, 'user:updateRole', 1, '2014-02-06 08:54:58', -1),
(229, 2, 'user:updateRole', 0, '2014-02-06 08:54:58', -1),
(230, 3, 'user:updateRole', 1, '2014-02-06 08:54:58', -1),
(231, 4, 'user:updateRole', 1, '2014-02-06 08:54:58', -1),
(232, 0, 'user:delete', 0, '2014-02-06 08:58:59', -1),
(233, 1, 'user:delete', 1, '2014-02-06 08:58:59', -1),
(234, 2, 'user:delete', 0, '2014-02-06 08:58:59', -1),
(235, 3, 'user:delete', 1, '2014-02-06 08:58:59', -1),
(236, 4, 'user:delete', 1, '2014-02-06 08:58:59', -1),
(237, 0, 'user:changePassword', 1, '2014-02-06 09:14:03', -1),
(238, 1, 'user:changePassword', 1, '2014-02-06 09:14:03', -1),
(239, 2, 'user:changePassword', 1, '2014-02-06 09:14:03', -1),
(240, 3, 'user:changePassword', 1, '2014-02-06 09:14:03', -1),
(241, 4, 'user:changePassword', 1, '2014-02-06 09:14:03', -1),
(242, 0, 'user:getPassword', 0, '2014-02-06 09:20:38', -1),
(243, 1, 'user:getPassword', 1, '2014-02-06 09:20:38', -1),
(244, 2, 'user:getPassword', 0, '2014-02-06 09:20:38', -1),
(245, 3, 'user:getPassword', 0, '2014-02-06 09:20:38', -1),
(246, 4, 'user:getPassword', 0, '2014-02-06 09:20:38', -1),
(247, 0, 'user:getGroupMembers', 1, '2014-02-06 09:24:55', -1),
(248, 1, 'user:getGroupMembers', 1, '2014-02-06 09:24:55', -1),
(249, 2, 'user:getGroupMembers', 1, '2014-02-06 09:24:55', -1),
(250, 3, 'user:getGroupMembers', 1, '2014-02-06 09:24:55', -1),
(251, 4, 'user:getGroupMembers', 1, '2014-02-06 09:24:55', -1),
(252, 0, 'user:listNewUsers', 0, '2014-02-06 09:30:33', -1),
(253, 1, 'user:listNewUsers', 1, '2014-02-06 09:30:33', -1),
(254, 2, 'user:listNewUsers', 0, '2014-02-06 09:30:33', -1),
(255, 3, 'user:listNewUsers', 1, '2014-02-06 09:30:33', -1),
(256, 4, 'user:listNewUsers', 1, '2014-02-06 09:30:33', -1),
(257, 0, 'user:enroleToInstitution', 0, '2014-02-06 09:37:11', -1),
(258, 1, 'user:enroleToInstitution', 1, '2014-02-06 09:37:11', -1),
(259, 2, 'user:enroleToInstitution', 0, '2014-02-06 09:37:11', -1),
(260, 3, 'user:enroleToInstitution', 0, '2014-02-06 09:37:11', -1),
(261, 4, 'user:enroleToInstitution', 1, '2014-02-06 09:37:11', -1),
(262, 0, 'user:enroleToGroup', 0, '2014-02-06 09:41:28', -1),
(263, 1, 'user:enroleToGroup', 1, '2014-02-06 09:41:28', -1),
(264, 2, 'user:enroleToGroup', 0, '2014-02-06 09:41:28', -1),
(265, 3, 'user:enroleToGroup', 1, '2014-02-06 09:41:28', -1),
(266, 4, 'user:enroleToGroup', 1, '2014-02-06 09:41:28', -1),
(267, 0, 'user:expelFromGroup', 0, '2014-02-06 09:44:04', -1),
(268, 1, 'user:expelFromGroup', 1, '2014-02-06 09:44:04', -1),
(269, 2, 'user:expelFromGroup', 0, '2014-02-06 09:44:04', -1),
(270, 3, 'user:expelFromGroup', 1, '2014-02-06 09:44:04', -1),
(271, 4, 'user:expelFromGroup', 1, '2014-02-06 09:44:04', -1),
(272, 0, 'user:import', 0, '2014-02-06 09:46:27', -1),
(273, 1, 'user:import', 1, '2014-02-06 09:46:27', -1),
(274, 2, 'user:import', 0, '2014-02-06 09:46:27', -1),
(275, 3, 'user:import', 1, '2014-02-06 09:46:27', -1),
(276, 4, 'user:import', 1, '2014-02-06 09:46:27', -1),
(277, 0, 'user:userList', 1, '2014-02-06 09:56:33', -1),
(278, 1, 'user:userList', 1, '2014-02-06 09:56:33', -1),
(279, 2, 'user:userList', 1, '2014-02-06 09:56:33', -1),
(280, 3, 'user:userList', 1, '2014-02-06 09:56:33', -1),
(281, 4, 'user:userList', 1, '2014-02-06 09:56:33', -1),
(282, 0, 'user:resetPassword', 0, '2014-02-06 10:16:43', -1),
(283, 1, 'user:resetPassword', 1, '2014-02-06 10:16:43', -1),
(284, 2, 'user:resetPassword', 1, '2014-02-06 10:16:43', -1),
(285, 3, 'user:resetPassword', 1, '2014-02-06 10:16:43', -1),
(286, 4, 'user:resetPassword', 1, '2014-02-06 10:16:43', -1),
(287, 0, 'user:getUsers', 0, '2014-02-06 10:26:57', -1),
(288, 1, 'user:getUsers', 1, '2014-02-06 10:26:57', -1),
(289, 2, 'user:getUsers', 0, '2014-02-06 10:26:57', -1),
(290, 3, 'user:getUsers', 1, '2014-02-06 10:26:57', -1),
(291, 4, 'user:getUsers', 1, '2014-02-06 10:26:57', -1),
(292, 0, 'user:getNewUsers', 0, '2014-02-06 10:32:03', -1),
(293, 1, 'user:getNewUsers', 1, '2014-02-06 10:32:03', -1),
(294, 2, 'user:getNewUsers', 0, '2014-02-06 10:32:03', -1),
(295, 3, 'user:getNewUsers', 1, '2014-02-06 10:32:03', -1),
(296, 4, 'user:getNewUsers', 1, '2014-02-06 10:32:03', -1),
(297, 0, 'user:confirmUser', 0, '2014-02-06 10:37:18', -1),
(298, 1, 'user:confirmUser', 1, '2014-02-06 10:37:18', -1),
(299, 2, 'user:confirmUser', 0, '2014-02-06 10:37:18', -1),
(300, 3, 'user:confirmUser', 1, '2014-02-06 10:37:18', -1),
(301, 4, 'user:confirmUser', 1, '2014-02-06 10:37:18', -1),
(302, 0, 'user:dedicate', 0, '2014-02-06 10:41:32', -1),
(303, 1, 'user:dedicate', 0, '2014-02-06 10:41:32', -1),
(304, 2, 'user:dedicate', 0, '2014-02-06 10:41:32', -1),
(305, 3, 'user:dedicate', 0, '2014-02-06 10:41:32', -1),
(306, 4, 'user:dedicate', 0, '2014-02-06 10:41:32', -1);

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
-- Tabellenstruktur für Tabelle `state`
--

DROP TABLE IF EXISTS `state`;
CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(200) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Daten für Tabelle `state`
--

INSERT INTO `state` (`id`, `state`, `country_code`, `creation_time`, `creator_id`) VALUES
(1, '---', '--', '2013-08-09 07:05:18', -1),
(2, 'Bayern', 'DE', '2013-08-09 07:05:18', -1),
(3, 'Berlin', 'DE', '2013-08-09 07:05:18', -1),
(4, 'Brandenburg', 'DE', '2013-08-09 07:05:18', -1),
(5, 'Bremen', 'DE', '2013-08-09 07:05:18', -1),
(6, 'Hamburg', 'DE', '2013-08-09 07:05:18', -1),
(7, 'Hessen', 'DE', '2013-08-09 07:05:18', -1),
(8, 'Mecklenburg-Vorpommern', 'DE', '2013-08-09 07:05:18', -1),
(9, 'Niedersachsen', 'DE', '2013-08-09 07:05:18', -1),
(10, 'Nordrhein-Westfalen', 'DE', '2013-08-09 07:05:18', -1),
(11, 'Rheinland-Pfalz', 'DE', '2013-08-09 07:05:18', -1),
(12, 'Saarland', 'DE', '2013-08-09 07:05:18', -1),
(13, 'Sachsen', 'DE', '2013-08-09 07:05:18', -1),
(14, 'Sachsen-Anhalt', 'DE', '2013-08-09 07:05:18', -1),
(15, 'Schleswig-Holstein', 'DE', '2013-08-09 07:05:18', -1),
(17, 'Thueringen', 'DE', '2013-08-09 07:05:18', -1),
(18, 'Baden-Wuerttemberg', 'DE', '2013-08-09 07:05:18', -1),
(19, 'Burgenland', 'AT', '2013-08-09 07:05:18', -1),
(20, 'KÃ¤rnten', 'AT', '2013-08-09 07:05:18', -1),
(21, 'NiederÃ¶sterreich', 'AT', '2013-08-09 07:05:18', -1),
(22, 'OberÃ¶sterreich', 'AT', '2013-08-09 07:05:18', -1),
(23, 'Salzburg', 'AT', '2013-08-09 07:05:18', -1),
(24, 'Steiermark', 'AT', '2013-08-09 07:05:18', -1),
(25, 'Tirol', 'AT', '2013-08-09 07:05:18', -1),
(26, 'Voralberg', 'AT', '2013-08-09 07:05:18', -1),
(27, 'Wien', 'AT', '2013-08-09 07:05:18', -1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `institution_id` int(10) DEFAULT NULL,
  `subject_short` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

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

--
-- Daten für Tabelle `terminalObjectives`
--

INSERT INTO `terminalObjectives` (`id`, `terminal_objective`, `description`, `curriculum_id`, `creation_time`, `creator_id`, `order_id`, `repeat_interval`) VALUES
(190, 'Sprechen und ZuhÃ¶ren - vor anderen sprechen', '', 94, '2013-04-04 06:29:46', 0, 0, -1),
(188, 'Sprechen und ZuhÃ¶ren - zu anderen sprechen', '', 94, '2013-04-04 06:14:53', 0, 0, -1),
(191, 'Sprechen und ZuhÃ¶ren - mit anderen sprechen', '', 94, '2013-04-04 07:04:44', 0, 0, -1),
(192, 'Sprechen und ZuhÃ¶ren - verstehende zuhÃ¶ren', '', 94, '2013-04-04 07:06:45', 0, 0, -1),
(193, 'Sprechen und ZuhÃ¶ren - szenisch spielen', '', 94, '2013-04-04 07:08:16', 0, 0, -1),
(194, 'Sprechen und ZuhÃ¶ren - Methoden und Arbeitstechniken', '', 94, '2013-04-04 07:09:10', 0, 0, -1),
(195, 'Schreiben - Ã¼ber Schreibfertigkeiten verfÃ¼gen', '', 94, '2013-04-04 07:11:25', 0, 0, -1),
(196, 'Schreiben - richtig schreiben', '', 94, '2013-04-04 07:12:28', 0, 0, -1),
(197, 'Schreiben - einen Schreibprozess eigenverantwortlich gestalten - Texte planen und entwerfen', '', 94, '2013-04-04 07:14:00', 0, 0, -1),
(198, 'Schreiben - einen Schreibprozess eigenverantwortlich gestalten - Texte schreiben', '', 94, '2013-04-04 07:15:32', 0, 0, -1),
(199, 'Schreiben - einen Schreibprozess eigenverantwortlich gestalten - Texte Ã¼berarbeiten', '', 94, '2013-04-04 07:25:02', 0, 0, -1),
(200, 'Schreiben - Methoden und Arbeitstechniken', '', 94, '2013-04-04 07:25:49', 0, 0, -1),
(201, 'Lesen - mit Texten und Medien umgehen - verschiedene Lesetechniken beherrschen', '', 94, '2013-04-04 07:28:24', 0, 0, -1),
(202, 'Lesen - mit Texten und Medien umgehen - Strategien zum Leseverstehen kennen und anwenden', '', 94, '2013-04-04 07:28:58', 0, 0, -1),
(203, 'Lesen - mit Texten und Medien umgehen - literarische Texte verstehen und nutzen', '', 94, '2013-04-04 07:30:58', 0, 0, -1),
(204, 'Lesen - mit Texten und Medien umgehen - Sach- und Gebrauchstexte verstehen und nutzen', '', 94, '2013-04-04 07:34:16', 0, 0, -1),
(205, 'Lesen - mit Texten und Medien umgehen - Medien verstehen und nutzen', '', 94, '2013-04-04 07:35:53', 0, 0, -1),
(206, 'Lesen - Methoden und Arbeitstechniken', '', 94, '2013-04-04 07:37:52', 0, 0, -1),
(207, 'L1 Leitidee Zahl (inhaltsbezogene mathematische Kompetenzen): Â Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:09:18', 0, 0, -1),
(208, 'L2 Leitidee Messen (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:13:46', 0, 0, -1),
(209, 'L3 Leitidee Raum und Forum (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:16:17', 0, 0, -1),
(210, 'L4 Leitidee Funktionaler Zusammenhang (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:19:21', 0, 0, -1),
(211, 'L5 Leitidee Daten und Zufall (inhaltsbezogene mathematische Kompetenzen): Die SchÃ¼lerinnen und SchÃ¼ler...', '', 89, '2013-04-04 09:22:42', 0, 0, -1),
(212, 'K1 Mathematisch argumentieren (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:24:58', 0, 0, -1),
(213, 'K2 Probleme mathematisch lÃ¶sen (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:26:21', 0, 0, -1),
(214, 'K3 Mathematisch modellieren (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:28:12', 0, 0, -1),
(215, 'K4 Mathematische Darstellungen verwenden (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:29:29', 0, 0, -1),
(216, 'K5 Mit symbolische, formalen und technischen Elementen der Mathematik umgehen (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:30:21', 0, 0, -1),
(217, 'K6 Kommunizieren (allg. mathematische Kompetenzen)', '', 89, '2013-04-04 09:31:12', 0, 0, -1);

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
-- Tabellenstruktur für Tabelle `user_accomplished`
--

DROP TABLE IF EXISTS `user_accomplished`;
CREATE TABLE IF NOT EXISTS `user_accomplished` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabling_objectives_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `accomplished_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `role` varchar(250) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

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
