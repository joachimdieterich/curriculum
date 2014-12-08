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
-- Tabellenstruktur fÃ¼r Tabelle `authenticate`
--

DROP TABLE IF EXISTS `authenticate`;
CREATE TABLE `authenticate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `ip` int(10) unsigned DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `firstname` text,
  `lastname` text,
  `email` text,
  `user_external_id` int(11) DEFAULT NULL,
  `ws_username` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `capabilities`
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
-- Daten fÃ¼r Tabelle `capabilities`
--

INSERT INTO `capabilities` (`id`, `capability`, `name`, `description`, `type`, `component`) VALUES
(2, 'menu:readInstitution', 'InstitutionsmenÃ¼ anzeigen', 'Ability to see institution menu', 'read', 'curriculum'),
(3, 'menu:readObjectives', 'LernstandmenÃ¼ anzeigen', 'Ability to see progress menu inside of institution menu', 'read', 'curriculum'),
(4, 'menu:readCurriculum', 'LehrplanmenÃ¼ anzeigen', 'Ability to see curricula menu inside of institution menu', 'read', 'curriculum'),
(5, 'menu:readUser', 'BenutzerverwaltungsmenÃ¼ anzeigen', 'Ability to see useradministration menu inside of institution menu', 'read', 'curriculum'),
(6, 'menu:readGrade', 'KlassenstufenmenÃ¼ anzeigen', 'Ability to see grade menu inside of institution menu', 'read', 'curriculum'),
(7, 'menu:readSubject', 'FÃ¤chermenÃ¼ anzeigen', 'Ability to see subject menu inside of institution menu', 'read', 'curriculum'),
(8, 'menu:readSemester', 'LernzeitraummenÃ¼ anzeigen', 'Ability to see semester menu inside of institution menu', 'read', 'curriculum'),
(9, 'menu:readBackup', 'BackupmenÃ¼ anzeigen', 'Ability to see backup menu inside of institution menu', 'read', 'curriculum'),
(10, 'menu:readConfirm', 'FreigabemenÃ¼ anzeigen', 'Ability to see confirm menu inside of institution menu', 'read', 'curriculum'),
(11, 'menu:readInstitutionConfig', 'Einstellungen (Institution) anzeigen', 'Ability to see Config menu (Insitution) inside of institution menu', 'read', 'curriculum'),
(12, 'menu:readConfig', 'Einstellungen (Profil) anzeigen', 'Ability to see Config menu (Profile) inside of institution menu', 'read', 'curriculum'),
(13, 'menu:readMyCurricula', 'Meine LehrplÃ¤ne anzeigen', 'Ability to see My curricula menu', 'read', 'curriculum'),
(14, 'menu:readGroup', 'GruppenmenÃ¼ anzeigen', 'Ability to see group-menu', 'read', 'curriculum'),
(15, 'menu:readLog', 'Logdaten anzeigen', 'Ability to see log page', 'read', 'curriculum'),
(16, 'page:showRoleForm', 'Rollen Formular anzeigen', 'Ability to see Role form', 'read', 'curriculum'),
(17, 'menu:readRole', 'RollenmenÃ¼ anzeigen', 'Ability to see Role menu', 'read', 'curriculum'),
(20, 'user:addUser', 'Benuzter hinzufÃ¼gen', 'Ability to add user profiles', 'write', 'curriculum'),
(21, 'user:updateUser', 'Benuzter bearbeiten', 'Ability to update user profiles', 'write', 'curriculum'),
(23, 'user:updateRole', 'Benuzterrolle aktualisieren', 'Ability to update user roles', 'write', 'curriculum'),
(24, 'user:delete', 'Benuzter lÃ¶schen', 'Ability to delete users', 'write', 'curriculum'),
(25, 'user:changePassword', 'Eigenes Benutzerpasswort Ã¤ndern', 'Ability to change own userpassoword', 'write', 'curriculum'),
(26, 'user:getPassword', 'Passwort aus Datenbank abfragen (! Nur fÃ¼r Webservice freigeben !)', 'Ability to get password', 'read', 'curriculum'),
(27, 'user:getGroupMembers', 'Mitglieder aus einer Lerngruppe anzeigen', 'Ability to read groupmembers', 'read', 'curriculum'),
(28, 'user:listNewUsers', 'Neue Benutzer auflisten', 'Ability to list new registered users', 'read', 'curriculum'),
(29, 'user:enroleToInstitution', 'Benutzer in Institution einschreiben', 'Ability to enrole users to an institution', 'write', 'curriculum'),
(30, 'user:enroleToGroup', 'Benutzer in Lerngruppe einschreiben', 'Ability to enrole users to group', 'write', 'curriculum'),
(31, 'user:expelFromGroup', 'Benutzer in Lerngruppe ausschreiben', 'Ability to expel users from group', 'write', 'curriculum'),
(32, 'menu:readuserImport', 'Benutzerliste (csv) importieren', 'Ability to import csv-userlist', 'write', 'curriculum'),
(33, 'user:userList', 'Benutzerliste anzeigen', 'Ability to see userlist', 'read', 'curriculum'),
(35, 'user:resetPassword', 'Benuzerkennwort zurÃ¼cksetzen', 'Ability to reset password', 'write', 'curriculum'),
(36, 'user:getUsers', 'Lerngruppenliste (Lehrplanbezogen) anzeigen', 'Ability to get Grouplist (depending on curriculum)', 'read', 'curriculum'),
(37, 'user:getNewUsers', 'Neue Benutzer (Institutsbezogen) anzeigen', 'Ability to get New Userlist (depending on institution)', 'read', 'curriculum'),
(38, 'user:confirmUser', 'Neue Benutzer bestÃ¤tigen', 'Ability to confirm new users', 'write', 'curriculum'),
(39, 'user:dedicate', 'Benutzer wÃ¤hrend Installationsprozess der erstellten Institution zuweisen', 'Only for installation purposes', 'write', 'curriculum'),
(40, 'mail:loadMail', 'Emails laden', 'Ability to load messages', 'read', 'mail'),
(41, 'mail:postMail', 'Emails schreiben', 'Ability to write messages', 'write', 'mail'),
(42, 'mail:loadInbox', 'Posteingang anzeigen ', 'Ability to load the inbox(mails)', 'read', 'mail'),
(43, 'mail:loadOutbox', 'Postausgang anzeigen ', 'Ability to load the outbox(mails)', 'read', 'mail'),
(44, 'mail:loadDeletedMessages', 'GelÃ¶schte Mails anzeigen ', 'Ability to load deleted mails', 'read', 'mail'),
(45, 'file:solutionUpload', 'LÃ¶sungen einreichen ', 'Ability to upload solutions', 'write', 'file'),
(46, 'file:loadMaterial', 'Material laden ', 'Ability to see materials', 'read', 'file'),
(47, 'backup:addBackup', 'Backup erstellen ', 'Ability to add backup', 'write', 'curriculum'),
(48, 'backup:loadBackup', 'Backup laden ', 'Ability to load backup', 'read', 'curriculum'),
(49, 'backup:deleteBackup', 'Backup lÃ¶schen ', 'Ability to delete backup', 'write', 'curriculum'),
(50, 'objectives:setStatus', 'Lernstand setzen ', 'Ability to set status of objectives', 'write', 'curriculum'),
(51, 'file:upload', 'Dateien hochladen', 'Ability to upload files', 'write', 'curriculum'),
(53, 'file:uploadURL', 'URL hochladen', 'Ability to upload URLs', 'write', 'curriculum'),
(54, 'file:lastFiles', 'Zuletzt hochgeladene Dateien anzeigen', 'Ability to see last uploaded files', 'read', 'curriculum'),
(55, 'file:curriculumFiles', 'Dateien des Lehrplanes anzeigen', 'Ability to see files of current curriculum', 'read', 'curriculum'),
(56, 'file:solution', 'LÃ¶sungen im Dateifenster anzeigen', 'Ability to see solutionfiles', 'read', 'curriculum'),
(57, 'file:myFiles', 'Meine Dateien im Dateifenster anzeigen', 'Ability to see my files', 'read', 'curriculum'),
(58, 'file:myAvatars', 'Meine Avatars im Dateifenster anzeigen', 'Ability to see my avatar files', 'read', 'curriculum'),
(59, 'objectives:addTerminalObjective', 'Themen hinzufÃ¼gen', 'Ability to add terminal Objectives', 'write', 'curriculum'),
(60, 'objectives:orderTerminalObjectives', 'Themen sortieren', 'Ability to sort terminal Objectives', 'write', 'curriculum'),
(61, 'objectives:updateTerminalObjectives', 'Themen bearbeiten', 'Ability to edit and update terminal Objectives', 'write', 'curriculum'),
(62, 'objectives:deleteTerminalObjectives', 'Themen lÃ¶schen', 'Ability to delete terminal Objectives', 'write', 'curriculum'),
(63, 'objectives:addEnablingObjective', 'Ziele hinzufÃ¼gen', 'Ability to add enabling Objectives', 'write', 'curriculum'),
(64, 'objectives:updateEnablingObjectives', 'Ziele bearbeiten', 'Ability to edit and update enabling Objectives', 'write', 'curriculum'),
(65, 'objectives:deleteEnablingObjectives', 'Ziele lÃ¶schen', 'Ability to delete enabling Objectives', 'write', 'curriculum'),
(66, 'subject:add', 'Fach hinzufÃ¼gen', 'Ability to add subjects', 'write', 'curriculum'),
(67, 'subject:update', 'Fach editieren', 'Ability to edit and update subjects', 'write', 'curriculum'),
(68, 'subject:delete', 'Fach lÃ¶schen', 'Ability to delete subjects', 'write', 'curriculum'),
(69, 'semester:add', 'Lernzeitrum hinzufÃ¼gen', 'Ability to add semester', 'write', 'curriculum'),
(70, 'semester:update', 'Lernzeitrum editieren', 'Ability to edit and update semester', 'write', 'curriculum'),
(71, 'semester:delete', 'Lernzeitrum lÃ¶schen', 'Ability to delete semester', 'write', 'curriculum'),
(72, 'schooltype:add', 'Schultyp hinzufÃ¼gen', 'Ability to add schooltype', 'write', 'curriculum'),
(73, 'schooltype:update', 'Schultyp Ã¤ndern', 'Ability to edit and update schooltype', 'write', 'curriculum'),
(74, 'schooltype:delete', 'Schultyp lÃ¶schen', 'Ability to delete schooltype', 'write', 'curriculum'),
(75, 'log:getLogs', 'Log-Daten einsehen', 'Ability to see log data', 'read', 'curriculum'),
(76, 'institution:add', 'Institution hinzufÃ¼gen', 'Ability to add institution', 'write', 'curriculum'),
(77, 'institution:delete', 'Institution lÃ¶schen', 'Ability to delete institution', 'write', 'curriculum'),
(78, 'institution:update', 'Institution Ã¤ndern', 'Ability to edit and update institution', 'write', 'curriculum'),
(79, 'groups:add', 'Gruppe hinzufÃ¼gen', 'Ability to add group', 'write', 'curriculum'),
(80, 'groups:update', 'Gruppe Ã¤ndern', 'Ability to edit and update group', 'write', 'curriculum'),
(81, 'groups:delete', 'Gruppe lÃ¶schen', 'Ability to delete groups', 'write', 'curriculum'),
(82, 'groups:expel', 'Lerngruppe aus Lehrplan ausschreiben ', 'Ability to enrole groups', 'write', 'curriculum'),
(83, 'groups:enrol', 'Lerngruppe in Lehrplan einschreiben ', 'Ability to enrole groups', 'write', 'curriculum'),
(84, 'groups:changeSemester', 'Lernzeitrum der Lerngruppe Ã¤ndern ', 'Ability to change semster of groups', 'write', 'curriculum'),
(85, 'grade:add', 'Klassenstufe hinzufÃ¼gen ', 'Ability to add grade', 'write', 'curriculum'),
(86, 'grade:update', 'Klassenstufe Ã¤ndern ', 'Ability to edit and update grade', 'write', 'curriculum'),
(87, 'grade:delete', 'Klassenstufe lÃ¶schen ', 'Ability to delete grade', 'write', 'curriculum'),
(88, 'file:update', 'Datei(beschreibungen)en Ã¤ndern', 'Ability to edit files', 'write', 'curriculum'),
(89, 'file:delete', 'Dateien lÃ¶schen', 'Ability to delete files', 'write', 'curriculum'),
(90, 'file:getSolutions', 'BenutzerlÃ¶sungen einsehen', 'Ability to see user solutions', 'read', 'curriculum'),
(91, 'curriculum:add', 'Lehrplan anlegen', 'Ability to add curriculum', 'write', 'curriculum'),
(92, 'curriculum:update', 'Lehrplan Ã¤ndern', 'Ability to edit curriculum', 'write', 'curriculum'),
(93, 'curriculum:delete', 'Lehrplan lÃ¶schen', 'Ability to delete curriculum', 'write', 'curriculum'),
(94, 'role:add', 'Rolle hinzufÃ¼gen', 'Ability to add user role', 'write', 'curriculum'),
(95, 'role:update', 'Rolle Ã¤ndern', 'Ability to edit and update user role', 'write', 'curriculum'),
(96, 'role:delete', 'Rolle lÃ¶schen', 'Ability to delete user role', 'write', 'curriculum'),
(97, 'backup:getMyBackups', 'Backups meiner Kurse anzeigen', 'Ability to get my backups', 'read', 'curriculum'),
(98, 'backup:getAllBackups', 'Backups aller Kurse anzeigen', 'Ability to get all backups', 'read', 'curriculum'),
(99, 'config:mySettings', 'Meine Einstellungen bearbeiten', 'Ability to edit my settings', 'write', 'curriculum'),
(100, 'config:Institution', 'Einstellungen von Institutionen bearbeiten', 'Ability to edit institutional settings', 'write', 'curriculum'),
(101, 'dashboard:globalAdmin', 'Informationen fÃ¼r globale Administratoren anzeigen', 'Ability to see Info for global Admins', 'read', 'curriculum'),
(102, 'dashboard:institutionalAdmin', 'Informationen fÃ¼r Administratoren (Institution) anzeigen', 'Ability to see Info for institutional Admins', 'read', 'curriculum'),
(103, 'menu:readCertificate', 'MenÃ¼ Zertifikate einrichten anzeigen', 'Ability to see certificate menu', 'read', 'curriculum'),
(104, 'user:getGroups', 'Lerngruppe eines Benuzters  anzeigen', 'Ability to see group Groups of a user', 'read', 'curriculum'),
(105, 'user:getCurricula', 'LehrplÃ¤ne eines Benuzters  anzeigen', 'Ability to see curiccula of a user', 'read', 'curriculum'),
(106, 'menu:readProfile', 'Eigene Benutzerprofilseite anzeigen', 'Ability to see profile page', 'read', 'menu'),
(107, 'menu:readPassword', 'Passwort Ã¼ndern Seite anzeigen', 'Ability to see password page', 'read', 'menu'),
(108, 'menu:readMessages', 'Mitteilungen (Postfach) anzeigen', 'Ability to see message page', 'read', 'menu'),
(109, 'page:showAdminDocu', 'Dokumentation zur Plattform auf Startseite anzeigen', 'Ability to see docu pdf on dasboard', 'read', 'page'),
(110, 'page:showStudentDocu', 'Dokumentation zur Plattform auf Startseite anzeigen', 'Ability to see docu pdf on dasboard', 'read', 'page'),
(111, 'page:showTeacherDocu', 'Dokumentation zur Plattform auf Startseite anzeigen', 'Ability to see docu pdf on dasboard', 'read', 'page'),
(112, 'page:showCronjob', 'Zeigt auf der Startseite an, wann cronjobs zuletzt gestartet wurden', 'Ability to see cronjob execution time on dasboard', 'read', 'page'),
(113, 'menu:readProfileAdmin', 'Benutzerprofilseite anzeigen', 'Ability to see profile page', 'read', 'menu'),
(114, 'curriculum:addObjectives', 'Ziele zum Lehrplan hinzufÃ¼gen', 'Ability to add objectives', 'write', 'curriculum'),
(115, 'user:getHelp', 'Benutzer anzeigen, die Ziel erfolgreich abgschlossen haben', 'Ability to see user who accomplished objective', 'read', 'curriculum'),
(116, 'groups:showAccomplished', 'Anzeigen wie viele Gruppenteilnehmer ein Ziel abgeschlossen haben', 'Ability to see percentage of users who has accomplished a objective', 'read', 'curriculum'),
(117, 'file:editMaterial', 'Material editieren', 'Ability to edit material', 'write', 'curriculum'),
(118, 'groups:showCurriculumEnrolments', 'LehrplÃ¤ne der Lerngruppe anzeigen', 'Show curricula of groups', 'read', 'curriculum'),
(120, 'user:confirmUserSidewide', 'Benutzer global freigeben', 'Confirm user sidewide', 'write', 'curriculum'),
(122, 'menu:readMyInstitution', 'MenÃ¼block "Meine Institution" anzeigen', 'Read menublock "my institutions"', 'read', 'curriculum'),
(123, 'user:expelFromInstitution', 'Benutzer aus Institution ausschreiben', 'Expel user from institution', 'write', 'curriculum'),
(124, 'user:userListComplete', 'Alle Benutzer (Instanz!) sehen', 'See all user', 'read', 'curriculum'),
(125, 'user:getInstitution', 'InstitutionszugehÃ¶rigkeit eines Benuzters anzeigen', 'See institution enrolments of a user', 'read', 'curriculum'),
(126, 'menu:readMyPortfolio', 'MenÃ¼block "Mein Portfolio" anzeigen', 'Show menublock "My Portfolio"', 'read', 'curriculum'),
(127, 'menu:readPortfolio', 'Portfolio MenÃ¼ anzeigen', 'Show Portfoliomenu', 'read', 'curriculum'),
(128, 'mail:delete', 'Nachrichten lÃ¶schen', 'Delete messages', 'write', 'curriculum');


-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `config_institution`
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
-- Tabellenstruktur fÃ¼r Tabelle `config_user`
--

DROP TABLE IF EXISTS `config_user`;
CREATE TABLE IF NOT EXISTS `config_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `user_filepath` varchar(250) DEFAULT NULL,
  `user_paginator_limit` smallint(6) DEFAULT NULL,
  `user_acc_days` smallint(6) DEFAULT NULL,
  `user_language` varchar(10) DEFAULT NULL,
  `user_semester` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `context`
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
-- Daten fÃ¼r Tabelle `context`
--

INSERT INTO `context` (`id`, `context`, `context_id`, `path`) VALUES
(1, 'userFiles', 1, 'userdata/'),
(2, 'curriculum', 2, 'curriculum/'),
(3, 'avatar', 3, 'avatar/'),
(4, 'userView', 4, 'solutions/'),
(5, 'subjectIcon', 5, 'subjects/');

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `countries`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=244 ;

--
-- Daten fÃ¼r Tabelle `countries`
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
-- Tabellenstruktur fÃ¼r Tabelle `cronjobs`
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
-- Tabellenstruktur fÃ¼r Tabelle `curriculum`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `curriculum_enrolments`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `enablingObjectives`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
--  Tabellenstruktur fÃ¼r Tabelle `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `context_id` int(10) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `licence` varchar(255) NOT NULL DEFAULT '',
  `creator_id` int(10) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(5) DEFAULT NULL,
  `cur_id` int(11) NOT NULL DEFAULT '-1',
  `ena_id` int(11) DEFAULT '-1',
  `ter_id` int(11) DEFAULT '-1',
  `description` varchar(2048) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=149 ;

--
-- Daten fÃ¼r Tabelle `files`
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
-- Tabellenstruktur fÃ¼r Tabelle `files_backup`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `grade`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Daten fÃ¼r Tabelle `grade`
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
-- Tabellenstruktur fÃ¼r Tabelle `groups`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=94 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `groups_enrolments`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `institution`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `institution_enrolments`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `log`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=207 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `receiver_id` int(11) NOT NULL DEFAULT '0',
  `subject` text,
  `message` mediumtext,
  `sender_status` smallint(1) DEFAULT '0' COMMENT '0 = ungelesen, 1 = gelesen, -1 = gelÃ¶scht',
  `receiver_status` smallint(1) DEFAULT '0' COMMENT '0 = ungelesen, 1 = gelesen, -1 = gelÃ¶scht',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `repeat_interval`
--

DROP TABLE IF EXISTS `repeat_interval`;
CREATE TABLE IF NOT EXISTS `repeat_interval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `repeat_interval` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten fÃ¼r Tabelle `repeat_interval`
--

INSERT INTO `repeat_interval` (`id`, `repeat_interval`) VALUES
(1, 1),
(2, 7),
(3, 30),
(4, 182),
(5, 365);

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `role_capabilities`
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
(3, 1, 'menu:readMyCurricula', 1, '2013-10-14 08:44:35', 90),
(8, 1, 'menu:readInstitution', 1, '2013-10-14 09:01:07', 90),
(12, 1, 'menu:readObjectives', 1, '2013-10-14 09:03:03', 90),
(15, 1, 'menu:readCurriculum', 1, '2013-10-14 09:09:50', 90),
(18, 1, 'menu:readGroup', 1, '2013-10-14 09:12:49', 90),
(20, 1, 'menu:readUser', 1, '2013-10-14 09:33:23', 90),
(23, 1, 'menu:readGrade', 1, '2013-10-14 09:34:07', 90),
(25, 1, 'menu:readSubject', 1, '2013-10-14 09:36:37', 90),
(27, 1, 'menu:readSemester', 1, '2013-10-14 09:37:27', 90),
(29, 1, 'menu:readBackup', 1, '2013-10-14 09:37:53', 90),
(32, 1, 'menu:readConfirm', 1, '2013-10-14 09:39:46', 90),
(34, 1, 'menu:readInstitutionConfig', 1, '2013-10-14 09:43:08', 90),
(37, 1, 'menu:readConfig', 1, '2013-10-14 09:43:52', 90),
(42, 1, 'menu:readLog', 1, '2013-10-25 04:50:56', 90),
(47, 1, 'page:showRoleForm', 1, '2013-11-17 15:24:34', 90),
(52, 1, 'menu:readRole', 1, '2013-11-17 18:09:23', 90),
(191, 0, 'menu:readInstitution', 0, '2013-11-26 22:45:20', 102),
(192, 0, 'menu:readObjectives', 0, '2013-11-26 22:45:20', 102),
(193, 0, 'menu:readCurriculum', 0, '2013-11-26 22:45:20', 102),
(194, 0, 'menu:readUser', 0, '2013-11-26 22:45:20', 102),
(195, 0, 'menu:readGrade', 0, '2013-11-26 22:45:20', 102),
(196, 0, 'menu:readSubject', 0, '2013-11-26 22:45:20', 102),
(197, 0, 'menu:readSemester', 0, '2013-11-26 22:45:20', 102),
(198, 0, 'menu:readBackup', 0, '2013-11-26 22:45:20', 102),
(199, 0, 'menu:readConfirm', 0, '2013-11-26 22:45:20', 102),
(200, 0, 'menu:readInstitutionConfig', 0, '2013-11-26 22:45:20', 102),
(201, 0, 'menu:readConfig', 1, '2013-11-26 22:45:20', 102),
(202, 0, 'menu:readMyCurricula', 1, '2013-11-26 22:45:20', 102),
(203, 0, 'menu:readGroup', 0, '2013-11-26 22:45:20', 102),
(204, 0, 'menu:readLog', 0, '2013-11-26 22:45:20', 102),
(205, 0, 'page:showRoleForm', 0, '2013-11-26 22:45:20', 102),
(206, 0, 'menu:readRole', 0, '2013-11-26 22:45:20', 102),
(207, 0, 'profile:updateMyProfile', 1, '2014-01-28 12:14:53', -1),
(208, 1, 'profile:updateMyProfile', 1, '2014-01-28 12:14:53', -1),
(212, 0, 'profile:updateProfile', 0, '2014-01-29 12:59:27', -1),
(213, 1, 'profile:updateProfile', 1, '2014-01-29 12:59:27', -1),
(217, 0, 'user:addUser', 0, '2014-02-02 17:23:35', 102),
(218, 1, 'user:addUser', 1, '2014-02-02 17:23:35', 90),
(222, 0, 'user:updateUser', 0, '2014-02-02 17:26:20', 102),
(223, 1, 'user:updateUser', 1, '2014-02-02 17:26:20', 90),
(227, 0, 'user:updateRole', 0, '2014-02-06 07:54:58', 102),
(228, 1, 'user:updateRole', 1, '2014-02-06 07:54:58', 90),
(232, 0, 'user:delete', 0, '2014-02-06 07:58:59', 102),
(233, 1, 'user:delete', 1, '2014-02-06 07:58:59', 90),
(237, 0, 'user:changePassword', 1, '2014-02-06 08:14:03', 102),
(238, 1, 'user:changePassword', 1, '2014-02-06 08:14:03', 90),
(242, 0, 'user:getPassword', 0, '2014-02-06 08:20:38', 102),
(243, 1, 'user:getPassword', 1, '2014-02-06 08:20:38', 90),
(247, 0, 'user:getGroupMembers', 1, '2014-02-06 08:24:55', 102),
(248, 1, 'user:getGroupMembers', 1, '2014-02-06 08:24:55', 90),
(252, 0, 'user:listNewUsers', 0, '2014-02-06 08:30:33', 102),
(253, 1, 'user:listNewUsers', 1, '2014-02-06 08:30:33', 90),
(257, 0, 'user:enroleToInstitution', 0, '2014-02-06 08:37:11', 102),
(258, 1, 'user:enroleToInstitution', 1, '2014-02-06 08:37:11', 90),
(262, 0, 'user:enroleToGroup', 0, '2014-02-06 08:41:28', 102),
(263, 1, 'user:enroleToGroup', 1, '2014-02-06 08:41:28', 90),
(267, 0, 'user:expelFromGroup', 0, '2014-02-06 08:44:04', 102),
(268, 1, 'user:expelFromGroup', 1, '2014-02-06 08:44:04', 90),
(272, 0, 'menu:readuserImport', 0, '2014-02-06 08:46:27', 102),
(273, 1, 'menu:readuserImport', 1, '2014-02-06 08:46:27', 90),
(277, 0, 'user:userList', 1, '2014-02-06 08:56:33', 102),
(278, 1, 'user:userList', 1, '2014-02-06 08:56:33', 90),
(282, 0, 'user:resetPassword', 0, '2014-02-06 09:16:43', 102),
(283, 1, 'user:resetPassword', 1, '2014-02-06 09:16:43', 90),
(287, 0, 'user:getUsers', 0, '2014-02-06 09:26:57', 102),
(288, 1, 'user:getUsers', 1, '2014-02-06 09:26:57', 90),
(292, 0, 'user:getNewUsers', 0, '2014-02-06 09:32:03', 102),
(293, 1, 'user:getNewUsers', 1, '2014-02-06 09:32:03', 90),
(297, 0, 'user:confirmUser', 0, '2014-02-06 09:37:18', 102),
(298, 1, 'user:confirmUser', 1, '2014-02-06 09:37:18', 90),
(302, 0, 'user:dedicate', 0, '2014-02-06 09:41:32', 102),
(303, 1, 'user:dedicate', 0, '2014-02-06 09:41:32', 90),
(324, 0, 'mail:loadMail', 1, '2014-04-02 14:59:25', 102),
(325, 1, 'mail:loadMail', 1, '2014-04-02 14:59:25', 90),
(329, 0, 'mail:postMail', 1, '2014-04-02 16:45:34', 102),
(330, 1, 'mail:postMail', 1, '2014-04-02 16:45:34', 90),
(334, 0, 'mail:loadInbox', 1, '2014-04-02 16:53:56', 102),
(335, 1, 'mail:loadInbox', 1, '2014-04-02 16:53:56', 90),
(339, 0, 'mail:loadOutbox', 1, '2014-04-02 16:56:10', 102),
(340, 1, 'mail:loadOutbox', 1, '2014-04-02 16:56:10', 90),
(344, 0, 'mail:loadDeletedMessages', 1, '2014-04-02 16:57:13', 102),
(345, 1, 'mail:loadDeletedMessages', 1, '2014-04-02 16:57:13', 90),
(349, 0, 'file:solutionUpload', 1, '2014-04-03 13:11:13', 102),
(350, 1, 'file:solutionUpload', 1, '2014-04-03 13:11:13', 90),
(354, 0, 'file:loadMaterial', 1, '2014-04-03 13:22:20', 102),
(355, 1, 'file:loadMaterial', 1, '2014-04-03 13:22:20', 90),
(359, 0, 'backup:addBackup', 0, '2014-04-06 13:18:21', 102),
(360, 1, 'backup:addBackup', 1, '2014-04-06 13:18:21', 90),
(364, 0, 'backup:loadBackup', 0, '2014-04-06 13:20:28', 102),
(365, 1, 'backup:loadBackup', 1, '2014-04-06 13:20:28', 90),
(369, 0, 'backup:deleteBackup', 0, '2014-04-06 13:24:09', 102),
(370, 1, 'backup:deleteBackup', 1, '2014-04-06 13:24:09', 90),
(374, 0, 'objectives:setStatus', 0, '2014-04-06 16:37:30', 102),
(375, 1, 'objectives:setStatus', 1, '2014-04-06 16:37:30', 90),
(379, 0, 'file:upload', 1, '2014-04-16 06:03:40', 102),
(380, 1, 'file:upload', 1, '2014-04-16 06:03:40', 90),
(384, 0, 'file:uploadURL', 1, '2014-04-16 06:04:37', 102),
(385, 1, 'file:uploadURL', 1, '2014-04-16 06:04:37', 90),
(389, 0, 'file:lastFiles', 1, '2014-04-16 06:12:35', 102),
(390, 1, 'file:lastFiles', 1, '2014-04-16 06:12:35', 90),
(394, 0, 'file:curriculumFiles', 0, '2014-04-16 06:20:37', 102),
(395, 1, 'file:curriculumFiles', 1, '2014-04-16 06:20:37', 90),
(399, 0, 'file:solution', 1, '2014-04-16 06:24:42', 102),
(400, 1, 'file:solution', 1, '2014-04-16 06:24:42', 90),
(404, 0, 'file:myFiles', 1, '2014-04-16 06:26:26', 102),
(405, 1, 'file:myFiles', 1, '2014-04-16 06:26:26', 90),
(409, 0, 'file:myAvatars', 1, '2014-04-16 06:28:40', 102),
(410, 1, 'file:myAvatars', 1, '2014-04-16 06:28:40', 90),
(414, 0, 'objectives:addTerminalObjective', 0, '2014-04-16 07:48:53', 102),
(415, 1, 'objectives:addTerminalObjective', 1, '2014-04-16 07:48:53', 90),
(419, 0, 'objectives:orderTerminalObjectives', 0, '2014-04-16 07:55:13', 102),
(420, 1, 'objectives:orderTerminalObjectives', 1, '2014-04-16 07:55:13', 90),
(424, 0, 'objectives:updateTerminalObjectives', 0, '2014-04-16 07:56:18', 102),
(425, 1, 'objectives:updateTerminalObjectives', 1, '2014-04-16 07:56:18', 90),
(429, 0, 'objectives:deleteTerminalObjectives', 0, '2014-04-16 07:57:53', 102),
(430, 1, 'objectives:deleteTerminalObjectives', 1, '2014-04-16 07:57:53', 90),
(434, 0, 'objectives:addEnablingObjective', 0, '2014-04-16 08:03:38', 102),
(435, 1, 'objectives:addEnablingObjective', 1, '2014-04-16 08:03:38', 90),
(439, 0, 'objectives:updateEnablingObjectives', 0, '2014-04-16 08:07:16', 102),
(440, 1, 'objectives:updateEnablingObjectives', 1, '2014-04-16 08:07:16', 90),
(444, 0, 'objectives:deleteEnablingObjectives', 0, '2014-04-16 08:09:18', 102),
(445, 1, 'objectives:deleteEnablingObjectives', 1, '2014-04-16 08:09:18', 90),
(449, 0, 'objectives:orderEnablingObjectives', 0, '2014-04-16 08:11:25', -1),
(450, 1, 'objectives:orderEnablingObjectives', 1, '2014-04-16 08:11:25', -1),
(454, 0, 'subject:add', 0, '2014-04-16 08:46:18', 102),
(455, 1, 'subject:add', 1, '2014-04-16 08:46:18', 90),
(459, 0, 'subject:update', 0, '2014-04-16 08:47:09', 102),
(460, 1, 'subject:update', 1, '2014-04-16 08:47:09', 90),
(464, 0, 'subject:delete', 0, '2014-04-16 12:10:43', 102),
(465, 1, 'subject:delete', 1, '2014-04-16 12:10:43', 90),
(469, 0, 'semester:add', 0, '2014-04-16 12:16:56', 102),
(470, 1, 'semester:add', 1, '2014-04-16 12:16:56', 90),
(474, 0, 'semester:update', 0, '2014-04-16 12:18:31', 102),
(475, 1, 'semester:update', 1, '2014-04-16 12:18:31', 90),
(479, 0, 'semester:delete', 0, '2014-04-16 12:20:19', 102),
(480, 1, 'semester:delete', 1, '2014-04-16 12:20:19', 90),
(484, 0, 'schooltype:add', 0, '2014-04-16 12:23:38', 102),
(485, 1, 'schooltype:add', 1, '2014-04-16 12:23:38', 90),
(489, 0, 'schooltype:update', 0, '2014-04-16 12:24:23', 102),
(490, 1, 'schooltype:update', 1, '2014-04-16 12:24:23', 90),
(494, 0, 'schooltype:delete', 0, '2014-04-16 12:25:27', 102),
(495, 1, 'schooltype:delete', 1, '2014-04-16 12:25:27', 90),
(499, 0, 'log:getLogs', 0, '2014-04-16 12:28:38', 102),
(500, 1, 'log:getLogs', 1, '2014-04-16 12:28:38', 90),
(504, 0, 'institution:add', 0, '2014-04-16 12:31:57', 102),
(505, 1, 'institution:add', 1, '2014-04-16 12:31:57', 90),
(509, 0, 'institution:delete', 0, '2014-04-16 12:32:43', 102),
(510, 1, 'institution:delete', 1, '2014-04-16 12:32:43', 90),
(514, 0, 'institution:update', 0, '2014-04-16 12:35:27', 102),
(515, 1, 'institution:update', 1, '2014-04-16 12:35:27', 90),
(519, 0, 'groups:add', 0, '2014-04-16 12:39:56', -1),
(520, 1, 'groups:add', 1, '2014-04-16 12:39:56', -1),
(524, 0, 'groups:update', 0, '2014-04-16 12:41:44', 102),
(525, 1, 'groups:update', 1, '2014-04-16 12:41:44', 90),
(529, 0, 'groups:delete', 0, '2014-04-16 12:44:35', 102),
(530, 1, 'groups:delete', 1, '2014-04-16 12:44:35', 90),
(534, 0, 'groups:expel', 0, '2014-04-16 12:47:34', 102),
(535, 1, 'groups:expel', 1, '2014-04-16 12:47:34', 90),
(539, 0, 'groups:enrol', 0, '2014-04-16 12:48:53', 102),
(540, 1, 'groups:enrol', 1, '2014-04-16 12:48:53', 90),
(544, 0, 'groups:changeSemester', 0, '2014-04-16 12:51:09', 102),
(545, 1, 'groups:changeSemester', 1, '2014-04-16 12:51:09', 90),
(549, 0, 'grade:add', 0, '2014-04-16 12:54:58', 102),
(550, 1, 'grade:add', 1, '2014-04-16 12:54:58', 90),
(554, 0, 'grade:update', 0, '2014-04-16 12:55:53', 102),
(555, 1, 'grade:update', 1, '2014-04-16 12:55:53', 90),
(559, 0, 'grade:delete', 0, '2014-04-16 12:57:13', 102),
(560, 1, 'grade:delete', 1, '2014-04-16 12:57:13', 90),
(564, 0, 'file:update', 1, '2014-04-16 13:01:04', 102),
(565, 1, 'file:update', 1, '2014-04-16 13:01:04', 90),
(569, 0, 'file:delete', 1, '2014-04-16 13:02:54', 102),
(570, 1, 'file:delete', 1, '2014-04-16 13:02:54', 90),
(574, 0, 'file:getSolutions', 1, '2014-04-16 13:06:03', 102),
(575, 1, 'file:getSolutions', 1, '2014-04-16 13:06:03', 90),
(579, 0, 'curriculum:add', 0, '2014-04-16 13:09:35', 102),
(580, 1, 'curriculum:add', 1, '2014-04-16 13:09:35', 90),
(584, 0, 'curriculum:update', 0, '2014-04-16 13:11:48', 102),
(585, 1, 'curriculum:update', 1, '2014-04-16 13:11:48', 90),
(589, 0, 'curriculum:delete', 0, '2014-04-16 13:13:01', 102),
(590, 1, 'curriculum:delete', 1, '2014-04-16 13:13:01', 90),
(594, 0, 'role:add', 0, '2014-04-17 05:41:02', 102),
(595, 1, 'role:add', 1, '2014-04-17 05:41:02', 90),
(599, 0, 'role:delete', 0, '2014-04-17 05:43:37', 102),
(600, 1, 'role:delete', 1, '2014-04-17 05:43:37', 90),
(604, 0, 'backup:getMyBackups', 0, '2014-04-17 05:51:17', 102),
(605, 1, 'backup:getMyBackups', 0, '2014-04-17 05:51:17', 90),
(609, 0, 'backup:getAllBackups', 0, '2014-04-17 05:53:10', 102),
(610, 1, 'backup:getAllBackups', 1, '2014-04-17 05:53:10', 90),
(614, 0, 'config:mySettings', 1, '2014-04-17 06:03:32', 102),
(615, 1, 'config:mySettings', 1, '2014-04-17 06:03:32', 90),
(619, 0, 'config:Institution', 0, '2014-04-17 06:04:59', 102),
(620, 1, 'config:Institution', 1, '2014-04-17 06:04:59', 90),
(624, 0, 'dashboard:globalAdmin', 0, '2014-04-17 06:16:12', 102),
(625, 1, 'dashboard:globalAdmin', 1, '2014-04-17 06:16:12', 90),
(629, 0, 'dashboard:institutionalAdmin', 0, '2014-04-17 06:18:00', 102),
(630, 1, 'dashboard:institutionalAdmin', 0, '2014-04-17 06:18:00', 90),
(730, 0, 'role:update', 0, '2014-04-27 08:09:51', 102),
(731, 1, 'role:update', 1, '2014-04-27 08:09:51', 90),
(732, 0, 'groups:add', 0, '2014-06-12 21:01:20', 102),
(733, 0, 'curriculum:import', 0, '2014-08-03 12:04:37', -1),
(734, 1, 'curriculum:import', 1, '2014-08-03 12:04:37', -1),
(735, 2, 'curriculum:import', 0, '2014-08-03 12:04:37', -1),
(736, 3, 'curriculum:import', 0, '2014-08-03 12:04:37', -1),
(737, 4, 'curriculum:import', 1, '2014-08-03 12:04:37', -1),
(738, 1, 'groups:add', 0, '2014-09-14 12:34:51', 90),
(739, 3, 'backup:addBackup', 0, '2014-10-03 11:23:42', 102),
(740, 3, 'backup:deleteBackup', 0, '2014-10-03 11:23:42', 102),
(741, 3, 'backup:getAllBackups', 0, '2014-10-03 11:23:42', 102),
(742, 3, 'backup:getMyBackups', 0, '2014-10-03 11:23:42', 102),
(743, 3, 'backup:loadBackup', 0, '2014-10-03 11:23:42', 102),
(744, 3, 'config:Institution', 0, '2014-10-03 11:23:42', 102),
(745, 3, 'config:mySettings', 0, '2014-10-03 11:23:42', 102),
(746, 3, 'curriculum:add', 0, '2014-10-03 11:23:42', 102),
(747, 3, 'curriculum:delete', 0, '2014-10-03 11:23:42', 102),
(748, 3, 'curriculum:update', 0, '2014-10-03 11:23:42', 102),
(749, 3, 'dashboard:globalAdmin', 0, '2014-10-03 11:23:42', 102),
(750, 3, 'dashboard:institutionalAdmin', 0, '2014-10-03 11:23:42', 102),
(751, 3, 'file:curriculumFiles', 1, '2014-10-03 11:23:42', 102),
(752, 3, 'file:delete', 0, '2014-10-03 11:23:42', 102),
(753, 3, 'file:getSolutions', 1, '2014-10-03 11:23:42', 102),
(754, 3, 'file:lastFiles', 1, '2014-10-03 11:23:42', 102),
(755, 3, 'file:loadMaterial', 1, '2014-10-03 11:23:42', 102),
(756, 3, 'file:myAvatars', 1, '2014-10-03 11:23:42', 102),
(757, 3, 'file:myFiles', 1, '2014-10-03 11:23:42', 102),
(758, 3, 'file:solution', 1, '2014-10-03 11:23:42', 102),
(759, 3, 'file:solutionUpload', 1, '2014-10-03 11:23:42', 102),
(760, 3, 'file:update', 1, '2014-10-03 11:23:42', 102),
(761, 3, 'file:upload', 1, '2014-10-03 11:23:42', 102),
(762, 3, 'file:uploadURL', 1, '2014-10-03 11:23:42', 102),
(763, 3, 'grade:add', 0, '2014-10-03 11:23:42', 102),
(764, 3, 'grade:delete', 0, '2014-10-03 11:23:42', 102),
(765, 3, 'grade:update', 0, '2014-10-03 11:23:42', 102),
(766, 3, 'groups:add', 1, '2014-10-03 11:23:42', 102),
(767, 3, 'groups:changeSemester', 0, '2014-10-03 11:23:42', 102),
(768, 3, 'groups:delete', 0, '2014-10-03 11:23:42', 102),
(769, 3, 'groups:enrol', 1, '2014-10-03 11:23:42', 102),
(770, 3, 'groups:expel', 1, '2014-10-03 11:23:42', 102),
(771, 3, 'groups:update', 0, '2014-10-03 11:23:42', 102),
(772, 3, 'institution:add', 0, '2014-10-03 11:23:42', 102),
(773, 3, 'institution:delete', 0, '2014-10-03 11:23:42', 102),
(774, 3, 'institution:update', 0, '2014-10-03 11:23:42', 102),
(775, 3, 'log:getLogs', 0, '2014-10-03 11:23:42', 102),
(776, 3, 'mail:loadDeletedMessages', 0, '2014-10-03 11:23:42', 102),
(777, 3, 'mail:loadInbox', 1, '2014-10-03 11:23:42', 102),
(778, 3, 'mail:loadMail', 1, '2014-10-03 11:23:42', 102),
(779, 3, 'mail:loadOutbox', 1, '2014-10-03 11:23:42', 102),
(780, 3, 'mail:postMail', 1, '2014-10-03 11:23:42', 102),
(781, 3, 'menu:readBackup', 0, '2014-10-03 11:23:42', 102),
(782, 3, 'menu:readConfirm', 0, '2014-10-03 11:23:42', 102),
(783, 3, 'menu:readCurriculum', 1, '2014-10-03 11:23:42', 102),
(784, 3, 'menu:readGrade', 0, '2014-10-03 11:23:42', 102),
(785, 3, 'menu:readGroup', 1, '2014-10-03 11:23:42', 102),
(786, 3, 'menu:readInstitution', 1, '2014-10-03 11:23:42', 102),
(787, 3, 'menu:readInstitutionConfig', 1, '2014-10-03 11:23:42', 102),
(788, 3, 'menu:readLog', 0, '2014-10-03 11:23:42', 102),
(789, 3, 'menu:readMyCurricula', 1, '2014-10-03 11:23:42', 102),
(790, 3, 'menu:readConfig', 1, '2014-10-03 11:23:42', 102),
(791, 3, 'menu:readObjectives', 1, '2014-10-03 11:23:42', 102),
(792, 3, 'menu:readRole', 0, '2014-10-03 11:23:42', 102),
(793, 3, 'menu:readSemester', 0, '2014-10-03 11:23:42', 102),
(794, 3, 'menu:readSubject', 0, '2014-10-03 11:23:42', 102),
(795, 3, 'menu:readUser', 1, '2014-10-03 11:23:42', 102),
(796, 3, 'objectives:addEnablingObjective', 1, '2014-10-03 11:23:42', 102),
(797, 3, 'objectives:addTerminalObjective', 1, '2014-10-03 11:23:42', 102),
(798, 3, 'objectives:deleteEnablingObjectives', 1, '2014-10-03 11:23:42', 102),
(799, 3, 'objectives:deleteTerminalObjectives', 1, '2014-10-03 11:23:42', 102),
(800, 3, 'objectives:orderTerminalObjectives', 1, '2014-10-03 11:23:42', 102),
(801, 3, 'objectives:setStatus', 1, '2014-10-03 11:23:42', 102),
(802, 3, 'objectives:updateEnablingObjectives', 1, '2014-10-03 11:23:42', 102),
(803, 3, 'objectives:updateTerminalObjectives', 1, '2014-10-03 11:23:42', 102),
(804, 3, 'page:showRoleForm', 0, '2014-10-03 11:23:42', 102),
(805, 3, 'role:add', 0, '2014-10-03 11:23:42', 102),
(806, 3, 'role:delete', 0, '2014-10-03 11:23:42', 102),
(807, 3, 'role:update', 0, '2014-10-03 11:23:42', 102),
(808, 3, 'schooltype:add', 0, '2014-10-03 11:23:42', 102),
(809, 3, 'schooltype:delete', 0, '2014-10-03 11:23:42', 102),
(810, 3, 'schooltype:update', 0, '2014-10-03 11:23:42', 102),
(811, 3, 'semester:add', 0, '2014-10-03 11:23:42', 102),
(812, 3, 'semester:delete', 0, '2014-10-03 11:23:42', 102),
(813, 3, 'semester:update', 0, '2014-10-03 11:23:42', 102),
(814, 3, 'subject:add', 0, '2014-10-03 11:23:42', 102),
(815, 3, 'subject:delete', 0, '2014-10-03 11:23:42', 102),
(816, 3, 'subject:update', 0, '2014-10-03 11:23:42', 102),
(817, 3, 'user:addUser', 1, '2014-10-03 11:23:42', 102),
(818, 3, 'user:changePassword', 1, '2014-10-03 11:23:42', 102),
(819, 3, 'user:confirmUser', 0, '2014-10-03 11:23:42', 102),
(820, 3, 'user:dedicate', 0, '2014-10-03 11:23:42', 102),
(821, 3, 'user:delete', 0, '2014-10-03 11:23:42', 102),
(822, 3, 'user:enroleToGroup', 1, '2014-10-03 11:23:42', 102),
(823, 3, 'user:enroleToInstitution', 0, '2014-10-03 11:23:42', 102),
(824, 3, 'user:expelFromGroup', 1, '2014-10-03 11:23:42', 102),
(825, 3, 'user:getGroupMembers', 1, '2014-10-03 11:23:42', 102),
(826, 3, 'user:getNewUsers', 1, '2014-10-03 11:23:42', 102),
(827, 3, 'user:getPassword', 0, '2014-10-03 11:23:42', 102),
(828, 3, 'user:getUsers', 1, '2014-10-03 11:23:42', 102),
(829, 3, 'menu:readuserImport', 0, '2014-10-03 11:23:42', 102),
(830, 3, 'user:listNewUsers', 1, '2014-10-03 11:23:42', 102),
(831, 3, 'user:resetPassword', 1, '2014-10-03 11:23:42', 102),
(832, 3, 'user:updateRole', 0, '2014-10-03 11:23:42', 102),
(833, 3, 'user:updateUser', 1, '2014-10-03 11:23:42', 102),
(834, 3, 'user:userList', 1, '2014-10-03 11:23:42', 102),
(835, 0, 'menu:readCertificate', 0, '2014-10-03 11:43:49', -1),
(836, 1, 'menu:readCertificate', 1, '2014-10-03 11:43:49', -1),
(837, 2, 'menu:readCertificate', 0, '2014-10-03 11:43:49', -1),
(838, 3, 'menu:readCertificate', 1, '2014-10-03 11:43:49', 102),
(839, 4, 'menu:readCertificate', 1, '2014-10-03 11:43:49', 102),
(840, 0, 'user:getGroups', 0, '2014-10-03 13:36:55', -1),
(841, 1, 'user:getGroups', 1, '2014-10-03 13:36:55', -1),
(842, 2, 'user:getGroups', 0, '2014-10-03 13:36:55', -1),
(843, 3, 'user:getGroups', 1, '2014-10-03 13:36:55', 102),
(844, 4, 'user:getGroups', 1, '2014-10-03 13:36:55', 102),
(845, 0, 'user:getCurricula', 0, '2014-10-03 13:41:35', -1),
(846, 1, 'user:getCurricula', 1, '2014-10-03 13:41:35', -1),
(847, 2, 'user:getCurricula', 0, '2014-10-03 13:41:35', -1),
(848, 3, 'user:getCurricula', 1, '2014-10-03 13:41:35', 102),
(849, 4, 'user:getCurricula', 1, '2014-10-03 13:41:35', 102),
(850, 0, 'menu:readProfile', 1, '2014-10-03 15:26:27', -1),
(851, 1, 'menu:readProfile', 1, '2014-10-03 15:26:27', -1),
(852, 2, 'menu:readProfile', 1, '2014-10-03 15:26:27', -1),
(853, 3, 'menu:readProfile', 1, '2014-10-03 15:26:27', 102),
(854, 4, 'menu:readProfile', 1, '2014-10-03 15:26:27', 102),
(855, 0, 'menu:readPassword', 1, '2014-10-03 15:28:50', -1),
(856, 1, 'menu:readPassword', 1, '2014-10-03 15:28:50', -1),
(857, 2, 'menu:readPassword', 1, '2014-10-03 15:28:50', -1),
(858, 3, 'menu:readPassword', 1, '2014-10-03 15:28:50', 102),
(859, 4, 'menu:readPassword', 1, '2014-10-03 15:28:50', 102),
(860, 0, 'menu:readMessages', 1, '2014-10-03 15:29:12', -1),
(861, 1, 'menu:readMessages', 1, '2014-10-03 15:29:12', -1),
(862, 2, 'menu:readMessages', 1, '2014-10-03 15:29:12', -1),
(863, 3, 'menu:readMessages', 1, '2014-10-03 15:29:12', 102),
(864, 4, 'menu:readMessages', 1, '2014-10-03 15:29:12', 102),
(865, 4, 'backup:addBackup', 1, '2014-10-03 18:14:43', 102),
(866, 4, 'backup:deleteBackup', 1, '2014-10-03 18:14:43', 102),
(867, 4, 'backup:getAllBackups', 0, '2014-10-03 18:14:43', 102),
(868, 4, 'backup:getMyBackups', 1, '2014-10-03 18:14:43', 102),
(869, 4, 'backup:loadBackup', 1, '2014-10-03 18:14:43', 102),
(870, 4, 'config:Institution', 1, '2014-10-03 18:14:43', 102),
(871, 4, 'config:mySettings', 1, '2014-10-03 18:14:43', 102),
(872, 4, 'curriculum:add', 1, '2014-10-03 18:14:43', 102),
(873, 4, 'curriculum:delete', 1, '2014-10-03 18:14:43', 102),
(874, 4, 'curriculum:update', 1, '2014-10-03 18:14:43', 102),
(875, 4, 'dashboard:globalAdmin', 0, '2014-10-03 18:14:43', 102),
(876, 4, 'dashboard:institutionalAdmin', 1, '2014-10-03 18:14:43', 102),
(877, 4, 'file:curriculumFiles', 1, '2014-10-03 18:14:43', 102),
(878, 4, 'file:delete', 1, '2014-10-03 18:14:43', 102),
(879, 4, 'file:getSolutions', 1, '2014-10-03 18:14:43', 102),
(880, 4, 'file:lastFiles', 1, '2014-10-03 18:14:43', 102),
(881, 4, 'file:loadMaterial', 1, '2014-10-03 18:14:43', 102),
(882, 4, 'file:myAvatars', 1, '2014-10-03 18:14:43', 102),
(883, 4, 'file:myFiles', 1, '2014-10-03 18:14:43', 102),
(884, 4, 'file:solution', 1, '2014-10-03 18:14:43', 102),
(885, 4, 'file:solutionUpload', 1, '2014-10-03 18:14:43', 102),
(886, 4, 'file:update', 1, '2014-10-03 18:14:43', 102),
(887, 4, 'file:upload', 1, '2014-10-03 18:14:43', 102),
(888, 4, 'file:uploadURL', 1, '2014-10-03 18:14:43', 102),
(889, 4, 'grade:add', 1, '2014-10-03 18:14:43', 102),
(890, 4, 'grade:delete', 1, '2014-10-03 18:14:43', 102),
(891, 4, 'grade:update', 1, '2014-10-03 18:14:43', 102),
(892, 4, 'groups:add', 1, '2014-10-03 18:14:43', 102),
(893, 4, 'groups:changeSemester', 1, '2014-10-03 18:14:44', 102),
(894, 4, 'groups:delete', 0, '2014-10-03 18:14:44', 102),
(895, 4, 'groups:enrol', 1, '2014-10-03 18:14:44', 102),
(896, 4, 'groups:expel', 1, '2014-10-03 18:14:44', 102),
(897, 4, 'groups:update', 1, '2014-10-03 18:14:44', 102),
(898, 4, 'institution:add', 0, '2014-10-03 18:14:44', 102),
(899, 4, 'institution:delete', 0, '2014-10-03 18:14:44', 102),
(900, 4, 'institution:update', 0, '2014-10-03 18:14:44', 102),
(901, 4, 'log:getLogs', 0, '2014-10-03 18:14:44', 102),
(902, 4, 'mail:loadDeletedMessages', 1, '2014-10-03 18:14:44', 102),
(903, 4, 'mail:loadInbox', 1, '2014-10-03 18:14:44', 102),
(904, 4, 'mail:loadMail', 1, '2014-10-03 18:14:44', 102),
(905, 4, 'mail:loadOutbox', 1, '2014-10-03 18:14:44', 102),
(906, 4, 'mail:postMail', 1, '2014-10-03 18:14:44', 102),
(907, 4, 'menu:readBackup', 1, '2014-10-03 18:14:44', 102),
(908, 4, 'menu:readCertificate', 1, '2014-10-03 18:14:44', 102),
(909, 4, 'menu:readConfig', 1, '2014-10-03 18:14:44', 102),
(910, 4, 'menu:readConfirm', 0, '2014-10-03 18:14:44', 102),
(911, 4, 'menu:readCurriculum', 1, '2014-10-03 18:14:44', 102),
(912, 4, 'menu:readGrade', 1, '2014-10-03 18:14:44', 102),
(913, 4, 'menu:readGroup', 1, '2014-10-03 18:14:44', 102),
(914, 4, 'menu:readInstitution', 1, '2014-10-03 18:14:44', 102),
(915, 4, 'menu:readInstitutionConfig', 1, '2014-10-03 18:14:44', 102),
(916, 4, 'menu:readLog', 0, '2014-10-03 18:14:44', 102),
(917, 4, 'menu:readMessages', 1, '2014-10-03 18:14:44', 102),
(918, 4, 'menu:readMyCurricula', 1, '2014-10-03 18:14:44', 102),
(919, 4, 'menu:readObjectives', 1, '2014-10-03 18:14:44', 102),
(920, 4, 'menu:readPassword', 1, '2014-10-03 18:14:44', 102),
(921, 4, 'menu:readProfile', 1, '2014-10-03 18:14:44', 102),
(922, 4, 'menu:readRole', 0, '2014-10-03 18:14:44', 102),
(923, 4, 'menu:readSemester', 1, '2014-10-03 18:14:44', 102),
(924, 4, 'menu:readSubject', 1, '2014-10-03 18:14:44', 102),
(925, 4, 'menu:readUser', 1, '2014-10-03 18:14:44', 102),
(926, 4, 'menu:readuserImport', 1, '2014-10-03 18:14:44', 102),
(927, 4, 'objectives:addEnablingObjective', 1, '2014-10-03 18:14:44', 102),
(928, 4, 'objectives:addTerminalObjective', 1, '2014-10-03 18:14:44', 102),
(929, 4, 'objectives:deleteEnablingObjectives', 1, '2014-10-03 18:14:44', 102),
(930, 4, 'objectives:deleteTerminalObjectives', 1, '2014-10-03 18:14:44', 102),
(931, 4, 'objectives:orderTerminalObjectives', 1, '2014-10-03 18:14:44', 102),
(932, 4, 'objectives:setStatus', 1, '2014-10-03 18:14:44', 102),
(933, 4, 'objectives:updateEnablingObjectives', 1, '2014-10-03 18:14:44', 102),
(934, 4, 'objectives:updateTerminalObjectives', 1, '2014-10-03 18:14:44', 102),
(935, 4, 'page:showRoleForm', 0, '2014-10-03 18:14:44', 102),
(936, 4, 'role:add', 0, '2014-10-03 18:14:44', 102),
(937, 4, 'role:delete', 0, '2014-10-03 18:14:44', 102),
(938, 4, 'role:update', 0, '2014-10-03 18:14:44', 102),
(939, 4, 'schooltype:add', 0, '2014-10-03 18:14:44', 102),
(940, 4, 'schooltype:delete', 0, '2014-10-03 18:14:44', 102),
(941, 4, 'schooltype:update', 0, '2014-10-03 18:14:44', 102),
(942, 4, 'semester:add', 0, '2014-10-03 18:14:44', 102),
(943, 4, 'semester:delete', 0, '2014-10-03 18:14:44', 102),
(944, 4, 'semester:update', 0, '2014-10-03 18:14:44', 102),
(945, 4, 'subject:add', 1, '2014-10-03 18:14:44', 102),
(946, 4, 'subject:delete', 1, '2014-10-03 18:14:44', 102),
(947, 4, 'subject:update', 1, '2014-10-03 18:14:44', 102),
(948, 4, 'user:addUser', 1, '2014-10-03 18:14:44', 102),
(949, 4, 'user:changePassword', 1, '2014-10-03 18:14:44', 102),
(950, 4, 'user:confirmUser', 0, '2014-10-03 18:14:44', 102),
(951, 4, 'user:dedicate', 0, '2014-10-03 18:14:44', 102),
(952, 4, 'user:delete', 1, '2014-10-03 18:14:44', 102),
(953, 4, 'user:enroleToGroup', 1, '2014-10-03 18:14:44', 102),
(954, 4, 'user:enroleToInstitution', 1, '2014-10-03 18:14:44', 102),
(955, 4, 'user:expelFromGroup', 1, '2014-10-03 18:14:44', 102),
(956, 4, 'user:getCurricula', 1, '2014-10-03 18:14:44', 102),
(957, 4, 'user:getGroupMembers', 1, '2014-10-03 18:14:44', 102),
(958, 4, 'user:getGroups', 1, '2014-10-03 18:14:44', 102),
(959, 4, 'user:getNewUsers', 1, '2014-10-03 18:14:44', 102),
(960, 4, 'user:getPassword', 0, '2014-10-03 18:14:44', 102),
(961, 4, 'user:getUsers', 1, '2014-10-03 18:14:44', 102),
(962, 4, 'user:listNewUsers', 1, '2014-10-03 18:14:44', 102),
(963, 4, 'user:resetPassword', 1, '2014-10-03 18:14:44', 102),
(964, 4, 'user:updateRole', 1, '2014-10-03 18:14:45', 102),
(965, 4, 'user:updateUser', 1, '2014-10-03 18:14:45', 102),
(966, 4, 'user:userList', 1, '2014-10-03 18:14:45', 102),
(967, 0, 'page:showAdminDocu', 0, '2014-10-03 18:34:19', -1),
(968, 1, 'page:showAdminDocu', 1, '2014-10-03 18:34:19', -1),
(969, 2, 'page:showAdminDocu', 0, '2014-10-03 18:34:19', -1),
(970, 3, 'page:showAdminDocu', 0, '2014-10-03 18:34:19', 102),
(971, 4, 'page:showAdminDocu', 1, '2014-10-03 18:34:19', 102),
(972, 0, 'page:showTeacherDocu', 0, '2014-10-03 18:37:49', -1),
(973, 1, 'page:showTeacherDocu', 1, '2014-10-03 18:37:49', -1),
(974, 2, 'page:showTeacherDocu', 1, '2014-10-03 18:37:49', -1),
(975, 3, 'page:showTeacherDocu', 1, '2014-10-03 18:37:49', 102),
(976, 4, 'page:showTeacherDocu', 1, '2014-10-03 18:37:49', 102),
(977, 0, 'page:showStudentDocu', 1, '2014-10-03 18:38:16', -1),
(978, 1, 'page:showStudentDocu', 1, '2014-10-03 18:38:16', -1),
(979, 2, 'page:showStudentDocu', 1, '2014-10-03 18:38:16', -1),
(980, 3, 'page:showStudentDocu', 1, '2014-10-03 18:38:16', 102),
(981, 4, 'page:showStudentDocu', 1, '2014-10-03 18:38:16', 102),
(982, 0, 'page:showCronjob', 0, '2014-10-03 18:42:03', -1),
(983, 1, 'page:showCronjob', 1, '2014-10-03 18:42:03', -1),
(984, 2, 'page:showCronjob', 0, '2014-10-03 18:42:03', -1),
(985, 3, 'page:showCronjob', 0, '2014-10-03 18:42:03', 102),
(986, 4, 'page:showCronjob', 1, '2014-10-03 18:42:03', 102),
(987, 0, 'menu:readProfileAdmin', 0, '2014-10-03 18:50:48', -1),
(988, 1, 'menu:readProfileAdmin', 1, '2014-10-03 18:50:48', -1),
(989, 2, 'menu:readProfileAdmin', 1, '2014-10-03 18:50:48', -1),
(990, 3, 'menu:readProfileAdmin', 1, '2014-10-03 18:50:48', 102),
(991, 4, 'menu:readProfileAdmin', 1, '2014-10-03 18:50:48', -1),
(992, 0, 'curriculum:addObjectives', 0, '2014-10-12 08:32:05', -1),
(993, 1, 'curriculum:addObjectives', 1, '2014-10-12 08:32:05', -1),
(994, 2, 'curriculum:addObjectives', 1, '2014-10-12 08:32:05', -1),
(995, 3, 'curriculum:addObjectives', 1, '2014-10-12 08:32:05', 102),
(996, 4, 'curriculum:addObjectives', 1, '2014-10-12 08:32:05', -1),
(997, 0, 'user:getHelp', 1, '2014-10-12 14:43:04', -1),
(998, 1, 'user:getHelp', 1, '2014-10-12 14:43:04', -1),
(999, 2, 'user:getHelp', 1, '2014-10-12 14:43:04', -1),
(1000, 3, 'user:getHelp', 1, '2014-10-12 14:43:04', 102),
(1001, 4, 'user:getHelp', 1, '2014-10-12 14:43:04', -1),
(1002, 0, 'groups:showAccomplished', 1, '2014-10-12 14:49:22', -1),
(1003, 1, 'groups:showAccomplished', 1, '2014-10-12 14:49:22', -1),
(1004, 2, 'groups:showAccomplished', 1, '2014-10-12 14:49:22', -1),
(1005, 3, 'groups:showAccomplished', 0, '2014-10-12 14:49:22', 102),
(1006, 4, 'groups:showAccomplished', 1, '2014-10-12 14:49:22', -1),
(1007, 0, 'file:editMaterial', 0, '2014-10-12 15:03:36', -1),
(1008, 1, 'file:editMaterial', 1, '2014-10-12 15:03:36', -1),
(1009, 2, 'file:editMaterial', 0, '2014-10-12 15:03:36', -1),
(1010, 3, 'file:editMaterial', 1, '2014-10-12 15:03:36', 102),
(1011, 4, 'file:editMaterial', 1, '2014-10-12 15:03:36', -1),
(1012, 0, 'groups:showCurriculumEnrolments', 0, '2014-10-12 15:46:56', -1),
(1013, 1, 'groups:showCurriculumEnrolmentsl', 1, '2014-10-12 15:46:56', -1),
(1014, 2, 'groups:showCurriculumEnrolments', 0, '2014-10-12 15:46:56', -1),
(1015, 3, 'groups:showCurriculumEnrolments', 1, '2014-10-12 15:46:56', 102),
(1016, 4, 'groups:showCurriculumEnrolments', 1, '2014-10-12 15:46:56', -1),
(1022, 0, 'user:confirmUserSidewide', 0, '2014-10-12 18:22:22', -1),
(1023, 1, 'user:confirmUserSidewide', 1, '2014-10-12 18:22:22', -1),
(1024, 2, 'user:confirmUserSidewide', 0, '2014-10-12 18:22:22', -1),
(1025, 3, 'user:confirmUserSidewide', 0, '2014-10-12 18:22:22', 102),
(1026, 4, 'user:confirmUserSidewide', 0, '2014-10-12 18:22:22', -1),
(1027, 3, 'confirmUserSidewide', 0, '2014-10-12 18:30:26', 102),
(1033, 0, 'menu:readMyInstitution', 0, '2014-10-13 09:26:41', -1),
(1034, 1, 'menu:readMyInstitution', 1, '2014-10-13 09:26:41', -1),
(1035, 2, 'menu:readMyInstitution', 1, '2014-10-13 09:26:41', -1),
(1036, 3, 'menu:readMyInstitution', 1, '2014-10-13 09:26:41', 102),
(1037, 4, 'menu:readMyInstitution', 1, '2014-10-13 09:26:41', -1),
(1038, 0, 'user:expelFromInstitution', 0, '2014-10-14 08:00:06', -1),
(1039, 1, 'user:expelFromInstitution', 1, '2014-10-14 08:00:06', -1),
(1040, 2, 'user:expelFromInstitution', 0, '2014-10-14 08:00:06', -1),
(1041, 3, 'user:expelFromInstitution', 0, '2014-10-14 08:00:06', -1),
(1042, 4, 'user:expelFromInstitution', 0, '2014-10-14 08:00:06', -1),
(1043, 0, 'user:userListComplete', 0, '2014-10-14 08:40:44', -1),
(1044, 1, 'user:userListComplete', 1, '2014-10-14 08:40:44', -1),
(1045, 2, 'user:userListComplete', 0, '2014-10-14 08:40:44', -1),
(1046, 3, 'user:userListComplete', 0, '2014-10-14 08:40:44', -1),
(1047, 4, 'user:userListComplete', 0, '2014-10-14 08:40:44', -1),
(1048, 0, 'user:getInstitution', 0, '2014-10-14 08:53:25', -1),
(1049, 1, 'user:getInstitution', 1, '2014-10-14 08:53:25', -1),
(1050, 2, 'user:getInstitution', 0, '2014-10-14 08:53:25', -1),
(1051, 3, 'user:getInstitution', 0, '2014-10-14 08:53:25', -1),
(1052, 4, 'user:getInstitution', 0, '2014-10-14 08:53:25', -1),
(1053, 0, 'menu:readMyPortfolio', 0, '2014-10-14 10:29:46', -1),
(1054, 1, 'menu:readMyPortfolio', 1, '2014-10-14 10:29:46', -1),
(1055, 2, 'menu:readMyPortfolio', 0, '2014-10-14 10:29:46', -1),
(1056, 3, 'menu:readMyPortfolio', 0, '2014-10-14 10:29:46', -1),
(1057, 4, 'menu:readMyPortfolio', 0, '2014-10-14 10:29:46', -1),
(1058, 0, 'menu:readPortfolio', 0, '2014-10-14 10:30:12', -1),
(1059, 1, 'menu:readPortfolio', 1, '2014-10-14 10:30:12', -1),
(1060, 2, 'menu:readPortfolio', 0, '2014-10-14 10:30:12', -1),
(1061, 3, 'menu:readPortfolio', 0, '2014-10-14 10:30:12', -1),
(1062, 4, 'menu:readPortfolio', 0, '2014-10-14 10:30:12', -1),
(1063, -1, 'backup:addBackup', 1, '2014-10-20 14:46:34', 102),
(1064, -1, 'backup:deleteBackup', 1, '2014-10-20 14:46:34', 102),
(1065, -1, 'backup:getAllBackups', 1, '2014-10-20 14:46:34', 102),
(1066, -1, 'backup:getMyBackups', 1, '2014-10-20 14:46:34', 102),
(1067, -1, 'backup:loadBackup', 1, '2014-10-20 14:46:34', 102),
(1068, -1, 'config:Institution', 1, '2014-10-20 14:46:34', 102),
(1069, -1, 'config:mySettings', 1, '2014-10-20 14:46:34', 102),
(1070, -1, 'curriculum:add', 1, '2014-10-20 14:46:34', 102),
(1071, -1, 'curriculum:addObjectives', 1, '2014-10-20 14:46:34', 102),
(1072, -1, 'curriculum:delete', 1, '2014-10-20 14:46:34', 102),
(1073, -1, 'curriculum:update', 1, '2014-10-20 14:46:34', 102),
(1074, -1, 'dashboard:globalAdmin', 1, '2014-10-20 14:46:34', 102),
(1075, -1, 'dashboard:institutionalAdmin', 1, '2014-10-20 14:46:34', 102),
(1076, -1, 'file:curriculumFiles', 1, '2014-10-20 14:46:34', 102),
(1077, -1, 'file:delete', 1, '2014-10-20 14:46:34', 102),
(1078, -1, 'file:editMaterial', 1, '2014-10-20 14:46:34', 102),
(1079, -1, 'file:getSolutions', 1, '2014-10-20 14:46:34', 102),
(1080, -1, 'file:lastFiles', 1, '2014-10-20 14:46:34', 102),
(1081, -1, 'file:loadMaterial', 1, '2014-10-20 14:46:34', 102),
(1082, -1, 'file:myAvatars', 1, '2014-10-20 14:46:34', 102),
(1083, -1, 'file:myFiles', 1, '2014-10-20 14:46:34', 102),
(1084, -1, 'file:solution', 1, '2014-10-20 14:46:34', 102),
(1085, -1, 'file:solutionUpload', 1, '2014-10-20 14:46:34', 102),
(1086, -1, 'file:update', 1, '2014-10-20 14:46:34', 102),
(1087, -1, 'file:upload', 1, '2014-10-20 14:46:34', 102),
(1088, -1, 'file:uploadURL', 1, '2014-10-20 14:46:34', 102),
(1089, -1, 'grade:add', 1, '2014-10-20 14:46:34', 102),
(1090, -1, 'grade:delete', 1, '2014-10-20 14:46:34', 102),
(1091, -1, 'grade:update', 1, '2014-10-20 14:46:34', 102),
(1092, -1, 'groups:add', 1, '2014-10-20 14:46:34', 102),
(1093, -1, 'groups:changeSemester', 1, '2014-10-20 14:46:34', 102),
(1094, -1, 'groups:delete', 1, '2014-10-20 14:46:34', 102),
(1095, -1, 'groups:enrol', 1, '2014-10-20 14:46:34', 102),
(1096, -1, 'groups:expel', 1, '2014-10-20 14:46:34', 102),
(1097, -1, 'groups:showAccomplished', 1, '2014-10-20 14:46:34', 102),
(1098, -1, 'groups:showCurriculumEnrolments', 1, '2014-10-20 14:46:34', 102),
(1099, -1, 'groups:update', 1, '2014-10-20 14:46:34', 102),
(1100, -1, 'institution:add', 1, '2014-10-20 14:46:34', 102),
(1101, -1, 'institution:delete', 1, '2014-10-20 14:46:34', 102),
(1102, -1, 'institution:update', 1, '2014-10-20 14:46:34', 102),
(1103, -1, 'log:getLogs', 1, '2014-10-20 14:46:34', 102),
(1104, -1, 'mail:loadDeletedMessages', 1, '2014-10-20 14:46:34', 102),
(1105, -1, 'mail:loadInbox', 1, '2014-10-20 14:46:34', 102),
(1106, -1, 'mail:loadMail', 1, '2014-10-20 14:46:34', 102),
(1107, -1, 'mail:loadOutbox', 1, '2014-10-20 14:46:34', 102),
(1108, -1, 'mail:postMail', 1, '2014-10-20 14:46:34', 102),
(1109, -1, 'menu:readBackup', 1, '2014-10-20 14:46:34', 102),
(1110, -1, 'menu:readCertificate', 1, '2014-10-20 14:46:34', 102),
(1111, -1, 'menu:readConfig', 1, '2014-10-20 14:46:34', 102),
(1112, -1, 'menu:readConfirm', 1, '2014-10-20 14:46:34', 102),
(1113, -1, 'menu:readCurriculum', 1, '2014-10-20 14:46:34', 102),
(1114, -1, 'menu:readGrade', 1, '2014-10-20 14:46:34', 102),
(1115, -1, 'menu:readGroup', 1, '2014-10-20 14:46:34', 102),
(1116, -1, 'menu:readInstitution', 1, '2014-10-20 14:46:34', 102),
(1117, -1, 'menu:readInstitutionConfig', 1, '2014-10-20 14:46:34', 102),
(1118, -1, 'menu:readLog', 1, '2014-10-20 14:46:34', 102),
(1119, -1, 'menu:readMessages', 1, '2014-10-20 14:46:34', 102),
(1120, -1, 'menu:readMyCurricula', 1, '2014-10-20 14:46:34', 102),
(1121, -1, 'menu:readMyInstitution', 1, '2014-10-20 14:46:34', 102),
(1122, -1, 'menu:readMyPortfolio', 1, '2014-10-20 14:46:34', 102),
(1123, -1, 'menu:readObjectives', 1, '2014-10-20 14:46:34', 102),
(1124, -1, 'menu:readPassword', 1, '2014-10-20 14:46:34', 102),
(1125, -1, 'menu:readPortfolio', 1, '2014-10-20 14:46:34', 102),
(1126, -1, 'menu:readProfile', 1, '2014-10-20 14:46:34', 102),
(1127, -1, 'menu:readProfileAdmin', 1, '2014-10-20 14:46:34', 102),
(1128, -1, 'menu:readRole', 1, '2014-10-20 14:46:34', 102),
(1129, -1, 'menu:readSemester', 1, '2014-10-20 14:46:34', 102),
(1130, -1, 'menu:readSubject', 1, '2014-10-20 14:46:34', 102),
(1131, -1, 'menu:readUser', 1, '2014-10-20 14:46:34', 102),
(1132, -1, 'menu:readuserImport', 1, '2014-10-20 14:46:34', 102),
(1133, -1, 'objectives:addEnablingObjective', 1, '2014-10-20 14:46:34', 102),
(1134, -1, 'objectives:addTerminalObjective', 1, '2014-10-20 14:46:34', 102),
(1135, -1, 'objectives:deleteEnablingObjectives', 1, '2014-10-20 14:46:34', 102),
(1136, -1, 'objectives:deleteTerminalObjectives', 1, '2014-10-20 14:46:34', 102),
(1137, -1, 'objectives:orderTerminalObjectives', 1, '2014-10-20 14:46:34', 102),
(1138, -1, 'objectives:setStatus', 1, '2014-10-20 14:46:34', 102),
(1139, -1, 'objectives:updateEnablingObjectives', 1, '2014-10-20 14:46:34', 102),
(1140, -1, 'objectives:updateTerminalObjectives', 1, '2014-10-20 14:46:34', 102),
(1141, -1, 'page:showAdminDocu', 1, '2014-10-20 14:46:34', 102),
(1142, -1, 'page:showCronjob', 1, '2014-10-20 14:46:34', 102),
(1143, -1, 'page:showRoleForm', 1, '2014-10-20 14:46:34', 102),
(1144, -1, 'page:showStudentDocu', 1, '2014-10-20 14:46:34', 102),
(1145, -1, 'page:showTeacherDocu', 1, '2014-10-20 14:46:34', 102),
(1146, -1, 'role:add', 1, '2014-10-20 14:46:34', 102),
(1147, -1, 'role:delete', 1, '2014-10-20 14:46:34', 102),
(1148, -1, 'role:update', 1, '2014-10-20 14:46:34', 102),
(1149, -1, 'schooltype:add', 1, '2014-10-20 14:46:34', 102),
(1150, -1, 'schooltype:delete', 1, '2014-10-20 14:46:34', 102),
(1151, -1, 'schooltype:update', 1, '2014-10-20 14:46:34', 102),
(1152, -1, 'semester:add', 1, '2014-10-20 14:46:34', 102),
(1153, -1, 'semester:delete', 1, '2014-10-20 14:46:34', 102),
(1154, -1, 'semester:update', 1, '2014-10-20 14:46:34', 102),
(1155, -1, 'subject:add', 1, '2014-10-20 14:46:34', 102),
(1156, -1, 'subject:delete', 1, '2014-10-20 14:46:34', 102),
(1157, -1, 'subject:update', 1, '2014-10-20 14:46:34', 102),
(1158, -1, 'user:addUser', 1, '2014-10-20 14:46:34', 102),
(1159, -1, 'user:changePassword', 1, '2014-10-20 14:46:34', 102),
(1160, -1, 'user:confirmUser', 1, '2014-10-20 14:46:34', 102),
(1161, -1, 'user:confirmUserSidewide', 1, '2014-10-20 14:46:34', 102),
(1162, -1, 'user:dedicate', 1, '2014-10-20 14:46:34', 102),
(1163, -1, 'user:delete', 1, '2014-10-20 14:46:34', 102),
(1164, -1, 'user:enroleToGroup', 1, '2014-10-20 14:46:34', 102),
(1165, -1, 'user:enroleToInstitution', 1, '2014-10-20 14:46:34', 102),
(1166, -1, 'user:expelFromGroup', 1, '2014-10-20 14:46:34', 102),
(1167, -1, 'user:expelFromInstitution', 1, '2014-10-20 14:46:34', 102),
(1168, -1, 'user:getCurricula', 1, '2014-10-20 14:46:34', 102),
(1169, -1, 'user:getGroupMembers', 1, '2014-10-20 14:46:34', 102),
(1170, -1, 'user:getGroups', 1, '2014-10-20 14:46:34', 102),
(1171, -1, 'user:getHelp', 1, '2014-10-20 14:46:34', 102),
(1172, -1, 'user:getInstitution', 1, '2014-10-20 14:46:34', 102),
(1173, -1, 'user:getNewUsers', 1, '2014-10-20 14:46:34', 102),
(1174, -1, 'user:getPassword', 1, '2014-10-20 14:46:34', 102),
(1175, -1, 'user:getUsers', 1, '2014-10-20 14:46:34', 102),
(1176, -1, 'user:listNewUsers', 1, '2014-10-20 14:46:34', 102),
(1177, -1, 'user:resetPassword', 1, '2014-10-20 14:46:34', 102),
(1178, -1, 'user:updateRole', 1, '2014-10-20 14:46:34', 102),
(1179, -1, 'user:updateUser', 1, '2014-10-20 14:46:34', 102),
(1180, -1, 'user:userList', 1, '2014-10-20 14:46:34', 102),
(1181, -1, 'user:userListComplete', 1, '2014-10-20 14:46:34', 102),
(1182, 0, 'mail:delete', 1, '2014-10-20 14:46:34', 102),
(1183, 1, 'mail:delete', 1, '2014-10-20 14:46:34', 102),
(1184, 2, 'mail:delete', 1, '2014-10-20 14:46:34', 102),
(1185, 3, 'mail:delete', 1, '2014-10-20 14:46:34', 102),
(1186, 4, 'mail:delete', 1, '2014-10-20 14:46:34', 102);


-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `schooltype`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Daten fÃ¼r Tabelle `schooltype`
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
-- Tabellenstruktur fÃ¼r Tabelle `semester`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `state`
--

DROP TABLE IF EXISTS `state`;
CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(200) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Daten fÃ¼r Tabelle `state`
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
-- Tabellenstruktur fÃ¼r Tabelle `subjects`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Daten fÃ¼r Tabelle `subjects`
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
-- Tabellenstruktur fÃ¼r Tabelle `terminalObjectives`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;



-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  `email` text NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `postalcode` text,
  `city` text,
  `state_id` text,
  `country_id` text,
  `avatar_id` int(11) DEFAULT '',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_USER_ID` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `user_accomplished`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur fÃ¼r Tabelle `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `role` varchar(250) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Daten fÃ¼r Tabelle `roles`
--


INSERT INTO `roles` (`id`, `role_id`, `role`, `description`, `creation_time`, `creator_id`) VALUES
(1, 0, 'Student', 'Benutzer hat nur Leserechte', '2013-08-09 07:06:00', 102),
(2, 1, 'Administrator', 'Benutzer hat alle Rechte', '2013-08-09 07:06:00', 102),
(3, 2, 'Tutor', 'Benutzer darf Kompetenzraster bearbeiten', '2013-08-09 07:06:00', 102),
(4, 3, 'Lehrer', 'Benutzer darf Kompetenzraster erstellen', '2013-08-09 07:06:00', 102),
(5, 4, 'Administrator (Insitution)', 'Benutzer darf Institution / Schule verwalten', '2013-08-09 07:06:00', 102),
(6, 5, 'install', 'system', '2014-04-27 08:01:14', 102);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
