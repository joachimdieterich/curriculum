-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Aug 2013 um 19:29
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
--  Tabellenstruktur für Tabelle `files`
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
(122, 5, '', '01a_art.png', 2, '2012-12-30 21:04:34', '.png', -1, -1, -1, NULL, 'Kunst'),
(123, 5, '', '16b_music.png', 2, '2012-12-30 21:08:06', '.png', -1, -1, -1, NULL, 'Musik'),
(124, 5, '', 'undefined.png', 2, '2012-12-30 21:28:39', '.png', -1, -1, -1, NULL, 'Informatik'),
(125, 5, '', '14b_math.png', 2, '2012-12-30 21:42:18', '.png', -1, -1, -1, NULL, 'Mathematik'),
(126, 5, '', '12c_ict.png', 2, '2012-12-30 21:43:55', '.png', -1, -1, -1, NULL, 'Informatik'),
(127, 5, '', '09b_geography.png', 2, '2012-12-30 21:45:23', '.png', -1, -1, -1, NULL, 'Geografie'),
(128, 5, '', '09c_geography.png', 2, '2012-12-30 21:46:42', '.png', -1, -1, -1, NULL, 'Geografie'),
(129, 5, '', '15_mlf.png', 2, '2012-12-30 21:47:45', '.png', -1, -1, -1, NULL, 'Geschichte'),
(130, 5, '', '19c_science.png', 2, '2012-12-30 21:49:21', '.png', -1, -1, -1, NULL, 'Chemie'),
(131, 5, '', '19d_science.png', 2, '2012-12-30 21:49:53', '.png', -1, -1, -1, NULL, 'Physik'),
(132, 5, '', '19e_science.png', 2, '2012-12-30 21:50:18', '.png', -1, -1, -1, NULL, 'Biologie');

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
(1, '1. Klasse', '1. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 2, 55),
(2, '2. Klasse', '2. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 2, 55),
(3, '3. Klasse', '3. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 2, 55),
(4, '4. Klasse', '4. Klassenstufe (Grundschule)', '0000-00-00 00:00:00', 2, 55),
(5, '5. Klasse', '5. Klassenstufe (Orientierungsstufe)', '0000-00-00 00:00:00', 2, 55),
(6, '6. Klasse', '6. Klassenstufe (Orientierungsstufe)', '0000-00-00 00:00:00', 2, 55),
(7, '7. Klasse', '7. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 2, 55),
(8, '8. Klasse', '8. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 2, 55),
(9, '9. Klasse', '9. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 2, 55),
(10, '10. Klasse', '10. Klassenstufe (Sek. I)', '0000-00-00 00:00:00', 2, 55),
(11, '11. Klasse', '11. Klassenstufe (Sek. II)', '0000-00-00 00:00:00', 2, 55),
(12, '12. Klasse', '12. Klassenstufe (Sek. II)', '0000-00-00 00:00:00', 2, 55),
(13, '13. Klasse', '13. Klassenstufe (Sek. II)', '0000-00-00 00:00:00', 2, 55);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=207 ;

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
(306, 4, 'user:dedicate', 0, '2014-02-06 10:41:32', -1),
(307, 3, 'menu:readGroup', 1, '2013-10-14 11:12:49', -1),
(308, 3, 'menu:readGrade', 1, '2013-10-14 11:12:49', -1),
(309, 3, 'menu:readSubject', 1, '2013-10-14 11:12:49', -1),
(310, 3, 'menu:readSemester', 1, '2013-10-14 11:12:49', -1),
(311, 3, 'menu:readConfirm', 0, '2013-10-14 11:12:49', -1),
(312, 3, 'menu:readInstitutionConfig', 1, '2013-10-14 11:12:49', -1),
(313, 3, 'menu:readlogmenu', 1, '2013-10-14 11:12:49', -1);

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
(1, 'Realschule plus', 'Realschule plus', 'DE', 11, '0000-00-00 00:00:00', 2),
(2, 'IGS', 'Integrierte Gesamtschule', 'DE', 11, '0000-00-00 00:00:00', 2),
(3, 'Gymnasium', 'Gymnasium', 'DE', 11, '0000-00-00 00:00:00', 2),
(5, 'FÃ¶rderschule', '', 'DE', 11, '0000-00-00 00:00:00', 2),
(6, 'Hauptschule', 'Hauptschule', 'DE', 11, '0000-00-00 00:00:00', 2),
(8, 'UniversitÃ¤t', 'Bildungswissenschaften', 'DE', 11, '0000-00-00 00:00:00', 2);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
(1, 'Mathematik', 'Unterrichtsfach Mathematik', '2013-08-09 07:05:42', 2, 55, 'MA'),
(2, 'Deutsch', 'Unterrichtsfach Deutsch', '2013-08-09 07:05:42', 2, 55, 'DE'),
(3, 'Englisch', 'Unterrichtsfach Englisch', '2013-08-09 07:05:42', 2, 55, 'EN'),
(16, 'FranzÃ¶sisch', 'Unterrichtsfach FranzÃ¶sisch', '2013-08-09 07:05:42', 2, 55, 'FR'),
(5, 'Musik', 'Unterrichtsfach Musik', '2013-08-09 07:05:42', 2, 55, 'MU'),
(6, 'Physik', 'Unterrichtsfach Physik', '2013-08-09 07:05:42', 2, 55, 'PH'),
(7, 'Biologie', 'Unterrichtsfach Biologie', '2013-08-09 07:05:42', 2, 55, 'BIO'),
(8, 'Chemie', 'Unterrichtsfach Chemie', '2013-08-09 07:05:42', 2, 55, 'CH'),
(9, 'Erdkunde', 'Unterrichtsfach Erdkunde', '2013-08-09 07:05:42', 2, 55, 'EK'),
(10, 'Sozialkunde', 'Unterrichtsfach Sozialkunde', '2013-08-09 07:05:42', 2, 55, 'SOZ'),
(11, 'Geschichte', 'Unterrichtsfach Geschichte', '2013-08-09 07:05:42', 2, 55, 'G'),
(12, 'Informatik', 'Unterrichtsfach Informatik', '2013-08-09 07:05:42', 2, 55, 'INF'),
(13, 'Kunst', 'Unterrichtsfach Bildende Kunst', '2013-08-09 07:05:42', 2, 55, 'BK'),
(14, 'Sport', 'Unterrichtsfach Sport', '2013-08-09 07:05:42', 2, 55, 'SP'),
(17, 'Erdkunde - Geschichte - Sozialkunde', 'Gemeinsames Unterrichtsfach Erdkunde - Geschichte - Sozialkunde', '2013-08-09 07:05:42', 2, 55, 'EGS'),
(36, 'Naturwissenschaften', 'Gemeinsames Unterrichtsfach Biologie - Physik - Chemie', '2013-08-17 15:06:20', 2, 55, 'NAWI');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;



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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

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
(1, 0, 'Student', 'Benutzer hat nur Leserechte', '2013-08-09 07:06:00', 2),
(2, 1, 'Administrator', 'Benutzer hat alle Rechte', '2013-08-09 07:06:00', 2),
(3, 2, 'Tutor', 'Benutzer darf Kompetenzraster bearbeiten', '2013-08-09 07:06:00', 2),
(4, 3, 'Lehrer', 'Benutzer darf Kompetenzraster erstellen', '2013-08-09 07:06:00', 2),
(9, 4, 'Administrator (Insitution)', 'Benutzer darf Institution / Schule verwalten', '2013-08-09 07:06:00', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
