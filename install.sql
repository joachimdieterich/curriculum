# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.33)
# Datenbank: cur
# Erstellt am: 2017-01-29 13:05:52 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle accept_terms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accept_terms`;

CREATE TABLE `accept_terms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `status` int(11) unsigned DEFAULT '0' COMMENT '0 = abgelehnt / noch nicht akzeptiert; 1 = akzeptiert',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rel_ac_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle block
# ------------------------------------------------------------

DROP TABLE IF EXISTS `block`;

CREATE TABLE `block` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `block` varchar(40) NOT NULL DEFAULT '',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='contains all installed blocks';

LOCK TABLES `block` WRITE;
/*!40000 ALTER TABLE `block` DISABLE KEYS */;

INSERT INTO `block` (`id`, `block`, `visible`)
VALUES
	(1,'html',1),
	(2,'moodle',1);

/*!40000 ALTER TABLE `block` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle block_instances
# ------------------------------------------------------------

DROP TABLE IF EXISTS `block_instances`;

CREATE TABLE `block_instances` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `block_id` bigint(10) NOT NULL DEFAULT '0',
  `name` varchar(40) NOT NULL DEFAULT '',
  `context_id` bigint(10) NOT NULL,
  `region` varchar(16) NOT NULL DEFAULT '',
  `weight` bigint(10) NOT NULL,
  `configdata` longtext,
  `institution_id` int(11) unsigned NOT NULL COMMENT '0 == all institutions',
  `role_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table stores block instances.';

LOCK TABLES `block_instances` WRITE;
/*!40000 ALTER TABLE `block_instances` DISABLE KEYS */;

INSERT INTO `block_instances` (`id`, `block_id`, `name`, `context_id`, `region`, `weight`, `configdata`, `institution_id`, `role_id`)
VALUES
	(3,1,'Allgmeine Informationen',11,'',3,'<strong>Datenschutzerklärung und Nutzungsbedingungen</strong><br>Die Datenschutzerklärung und Nutzungsbedingungen für diese Lernplattform können Sie <a href=\"http://www.curriculumonline.de\">hier</a> einsehen. <br><br><strong>Ansprechpartner</strong><br>Die Ansprechpartner für diese Zertifizierungsplattform können Sie unter folgender Emailadresse mail@joachimdieterich.de erreichen.<br> <br><strong>Impressum</strong><br>Das Impressum dieses System können Sie <a href=\"http://joachimdieterich.de/index.php/impressum\">hier</a> einsehen.',0,0),
	(4,1,'Hilfe',11,'',0,'<div class=\"text-center\"> <a href=\"https://vimeo.com/user48533307/collections\" target=\"_blank\">Video-Tutorials</a>\n</div>',0,0);

/*!40000 ALTER TABLE `block_instances` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle bulletinBoard
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bulletinBoard`;

CREATE TABLE `bulletinBoard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) unsigned NOT NULL,
  `title` varchar(400) DEFAULT NULL,
  `text` text,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_bb_user` (`creator_id`),
  KEY `rel_bb_institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `bulletinBoard` WRITE;
/*!40000 ALTER TABLE `bulletinBoard` DISABLE KEYS */;

INSERT INTO `bulletinBoard` (`id`, `institution_id`, `title`, `text`, `creation_time`, `creator_id`)
VALUES
	(1,56,'Digitale Unterstützung bei der Kompetenzorientierung an Schule und Hochschule','<div>\n<div>\n<div>\n<div>\n<p>Halle 1 | school@LEARNTEC-Forum<br>\n27.01.2016 15:30 - 16:00 Uhr</p>\n</div>\n</div>\n\n<div>\n<p> </p>\n\n<p>Wie lassen sich Kompetenzen dokumentieren und zertifizieren? In diesem Beitrag wird die Plattform curriculum (OpenSource) vorgestellt die genau das versucht. Mit ihr lassen sich einfache Kompetenzraster erstellen. Die Plattform bietet Lerner und Lehrperson eine Übersicht über den aktuellen Kompetenzstand einer Lerngruppe bzw. eines Lernenden. Noch nicht erworbene Kompetenzen können somit klar benannt und besser gefördert werden. Die einzelnen Kompetenzfelder / Ziele können zudem mit Materialien - z. B. aus bestehenden Lernplattformen - verknüpft werden. Curriculum wurde im Rahmen einer Laptopklasse entwickelt und erprobt. Daneben wurde die Plattform auch an der Universität Landau eingesetzt.</p>\n\n<p> </p>\n</div>\n</div>\n</div>\n\n<p> </p>\n','2016-06-09 10:09:13',532);

/*!40000 ALTER TABLE `bulletinBoard` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle capabilities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `capabilities`;

CREATE TABLE `capabilities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `capability` varchar(240) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `component` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `capabilities` WRITE;
/*!40000 ALTER TABLE `capabilities` DISABLE KEYS */;

INSERT INTO `capabilities` (`id`, `capability`, `name`, `description`, `type`, `component`)
VALUES
	(2,'menu:readInstitution','Institutionsmenü anzeigen','Ability to see institution menu','read','curriculum'),
	(3,'menu:readObjectives','Lernstandmenü anzeigen','Ability to see progress menu inside of institution menu','read','curriculum'),
	(4,'menu:readCurriculum','Lehrplanmenü anzeigen','Ability to see curricula menu inside of institution menu','read','curriculum'),
	(5,'menu:readUser','Benutzerverwaltungsmenü anzeigen','Ability to see useradministration menu inside of institution menu','read','curriculum'),
	(6,'menu:readGrade','Klassenstufenmenü anzeigen','Ability to see grade menu inside of institution menu','read','curriculum'),
	(7,'menu:readSubject','Fächermenü anzeigen','Ability to see subject menu inside of institution menu','read','curriculum'),
	(8,'menu:readSemester','Lernzeitraummenü anzeigen','Ability to see semester menu inside of institution menu','read','curriculum'),
	(9,'menu:readBackup','Backupmenü anzeigen','Ability to see backup menu inside of institution menu','read','curriculum'),
	(10,'menu:readConfirm','Freigabemenü anzeigen','Ability to see confirm menu inside of institution menu','read','curriculum'),
	(14,'menu:readGroup','Gruppenmenü anzeigen','Ability to see group-menu','read','curriculum'),
	(15,'menu:readLog','Logdaten anzeigen','Ability to see log page','read','curriculum'),
	(17,'menu:readRole','Rollenmenü anzeigen','Ability to see Role menu','read','curriculum'),
	(20,'user:addUser','Benuzter hinzufügen','Ability to add user profiles','write','curriculum'),
	(21,'user:updateUser','Benuzter bearbeiten','Ability to update user profiles','write','curriculum'),
	(23,'user:updateRole','Benuzterrolle aktualisieren','Ability to update user roles','write','curriculum'),
	(24,'user:delete','Benuzter löschen','Ability to delete users','write','curriculum'),
	(25,'user:changePassword','Eigenes Benutzerpasswort ändern','Ability to change own userpassoword','write','curriculum'),
	(27,'user:getGroupMembers','Mitglieder aus einer Lerngruppe anzeigen','Ability to read groupmembers','read','curriculum'),
	(28,'user:listNewUsers','Neue Benutzer auflisten','Ability to list new registered users','read','curriculum'),
	(29,'user:enroleToInstitution','Benutzer in Institution einschreiben','Ability to enrole users to an institution','write','curriculum'),
	(30,'user:enroleToGroup','Benutzer in Lerngruppe einschreiben','Ability to enrole users to group','write','curriculum'),
	(31,'user:expelFromGroup','Benutzer in Lerngruppe ausschreiben','Ability to expel users from group','write','curriculum'),
	(32,'menu:readuserImport','Benutzerliste (csv) importieren','Ability to import csv-userlist','write','curriculum'),
	(33,'user:userList','Benutzerliste anzeigen','Ability to see userlist','read','curriculum'),
	(35,'user:resetPassword','Benuzerkennwort zurücksetzen','Ability to reset password','write','curriculum'),
	(36,'user:getUsers','Lerngruppenliste (Lehrplanbezogen) anzeigen','Ability to get Grouplist (depending on curriculum)','read','curriculum'),
	(38,'user:confirmUser','Neue Benutzer bestätigen','Ability to confirm new users','write','curriculum'),
	(39,'install:dedicate','Nur für die Installation! ','Only for installation purposes','write','curriculum'),
	(40,'mail:loadMail','Emails laden','Ability to load messages','read','mail'),
	(41,'mail:postMail','Emails schreiben','Ability to write messages','write','mail'),
	(42,'mail:loadInbox','Posteingang anzeigen ','Ability to load the inbox(mails)','read','mail'),
	(43,'mail:loadOutbox','Postausgang anzeigen ','Ability to load the outbox(mails)','read','mail'),
	(44,'mail:loadDeletedMessages','Gelöschte Mails anzeigen ','Ability to load deleted mails','read','mail'),
	(45,'file:solutionUpload','Lösungen einreichen ','Ability to upload solutions','write','file'),
	(46,'file:loadMaterial','Material laden ','Ability to see materials','read','file'),
	(47,'backup:add','Backup erstellen ','Ability to add backup','write','curriculum'),
	(49,'backup:delete','Backup löschen ','Ability to delete backup','write','curriculum'),
	(50,'objectives:setStatus','Lernstand setzen ','Ability to set status of objectives','write','curriculum'),
	(51,'file:upload','Dateien hochladen','Ability to upload files','write','curriculum'),
	(53,'file:uploadURL','URL hochladen','Ability to upload URLs','write','curriculum'),
	(54,'file:lastFiles','Zuletzt hochgeladene Dateien anzeigen','Ability to see last uploaded files','read','curriculum'),
	(55,'file:curriculumFiles','Dateien des Lehrplanes anzeigen','Ability to see files of current curriculum','read','curriculum'),
	(56,'file:solution','Lösungen im Dateifenster anzeigen','Ability to see solutionfiles','read','curriculum'),
	(57,'file:myFiles','Meine Dateien im Dateifenster anzeigen','Ability to see my files','read','curriculum'),
	(58,'file:myAvatars','Meine Avatars im Dateifenster anzeigen','Ability to see my avatar files','read','curriculum'),
	(59,'objectives:addTerminalObjective','Themen hinzufügen','Ability to add terminal Objectives','write','curriculum'),
	(61,'objectives:updateTerminalObjectives','Themen bearbeiten','Ability to edit and update terminal Objectives','write','curriculum'),
	(62,'objectives:deleteTerminalObjectives','Themen löschen','Ability to delete terminal Objectives','write','curriculum'),
	(63,'objectives:addEnablingObjective','Ziele hinzufügen','Ability to add enabling Objectives','write','curriculum'),
	(64,'objectives:updateEnablingObjectives','Ziele bearbeiten','Ability to edit and update enabling Objectives','write','curriculum'),
	(65,'objectives:deleteEnablingObjectives','Ziele löschen','Ability to delete enabling Objectives','write','curriculum'),
	(66,'subject:add','Fach hinzufügen','Ability to add subjects','write','curriculum'),
	(67,'subject:update','Fach editieren','Ability to edit and update subjects','write','curriculum'),
	(68,'subject:delete','Fach löschen','Ability to delete subjects','write','curriculum'),
	(69,'semester:add','Lernzeitrum hinzufügen','Ability to add semester','write','curriculum'),
	(70,'semester:update','Lernzeitrum editieren','Ability to edit and update semester','write','curriculum'),
	(71,'semester:delete','Lernzeitrum löschen','Ability to delete semester','write','curriculum'),
	(72,'schooltype:add','Schultyp hinzufügen','Ability to add schooltype','write','curriculum'),
	(73,'schooltype:update','Schultyp ändern','Ability to edit and update schooltype','write','curriculum'),
	(74,'schooltype:delete','Schultyp löschen','Ability to delete schooltype','write','curriculum'),
	(75,'log:getLogs','Log-Daten einsehen','Ability to see log data','read','curriculum'),
	(76,'institution:add','Institution hinzufügen','Ability to add institution','write','curriculum'),
	(77,'institution:delete','Institution löschen','Ability to delete institution','write','curriculum'),
	(78,'institution:update','Institution ändern','Ability to edit and update institution','write','curriculum'),
	(79,'groups:add','Gruppe hinzufügen','Ability to add group','write','curriculum'),
	(80,'groups:update','Gruppe ändern','Ability to edit and update group','write','curriculum'),
	(81,'groups:delete','Gruppe löschen','Ability to delete groups','write','curriculum'),
	(82,'groups:expel','Lerngruppe aus Lehrplan ausschreiben ','Ability to enrole groups','write','curriculum'),
	(83,'groups:enrol','Lerngruppe in Lehrplan einschreiben ','Ability to enrole groups','write','curriculum'),
	(84,'groups:changeSemester','Lernzeitrum der Lerngruppe ändern ','Ability to change semster of groups','write','curriculum'),
	(85,'grade:add','Klassenstufe hinzufügen ','Ability to add grade','write','curriculum'),
	(86,'grade:update','Klassenstufe ändern ','Ability to edit and update grade','write','curriculum'),
	(87,'grade:delete','Klassenstufe löschen ','Ability to delete grade','write','curriculum'),
	(88,'file:update','Datei(beschreibungen)en ändern','Ability to edit files','write','curriculum'),
	(89,'file:delete','Dateien löschen','Ability to delete files','write','curriculum'),
	(90,'file:getSolutions','Benutzerlösungen einsehen','Ability to see user solutions','read','curriculum'),
	(91,'curriculum:add','Lehrplan anlegen','Ability to add curriculum','write','curriculum'),
	(92,'curriculum:update','Lehrplan ändern','Ability to edit curriculum','write','curriculum'),
	(93,'curriculum:delete','Lehrplan löschen','Ability to delete curriculum','write','curriculum'),
	(94,'role:add','Rolle hinzufügen','Ability to add user role','write','curriculum'),
	(95,'role:update','Rolle ändern','Ability to edit and update user role','write','curriculum'),
	(96,'role:delete','Rolle löschen','Ability to delete user role','write','curriculum'),
	(97,'backup:getMyBackups','Backups meiner Kurse anzeigen','Ability to get my backups','read','curriculum'),
	(98,'backup:getAllBackups','Backups aller Kurse anzeigen','Ability to get all backups','read','curriculum'),
	(101,'dashboard:globalAdmin','Informationen für globale Administratoren anzeigen','Ability to see Info for global Admins','read','curriculum'),
	(102,'dashboard:institutionalAdmin','Informationen für Administratoren (Institution) anzeigen','Ability to see Info for institutional Admins','read','curriculum'),
	(103,'menu:readCertificate','Menü Zertifikate einrichten anzeigen','Ability to see certificate menu','read','curriculum'),
	(104,'user:getGroups','Lerngruppe eines Benutzers  anzeigen','Ability to see group Groups of a user','read','curriculum'),
	(105,'user:getCurricula','Lehrpläne eines Benuzters  anzeigen','Ability to see curiccula of a user','read','curriculum'),
	(108,'menu:readMessages','Mitteilungen (Postfach) anzeigen','Ability to see message page','read','menu'),
	(109,'page:showAdminDocu','Dokumentation zur Plattform auf Startseite anzeigen','Ability to see docu pdf on dasboard','read','page'),
	(110,'page:showStudentDocu','Dokumentation zur Plattform auf Startseite anzeigen','Ability to see docu pdf on dasboard','read','page'),
	(111,'page:showTeacherDocu','Dokumentation zur Plattform auf Startseite anzeigen','Ability to see docu pdf on dasboard','read','page'),
	(112,'page:showCronjob','Zeigt auf der Startseite an, wann cronjobs zuletzt gestartet wurden','Ability to see cronjob execution time on dasboard','read','page'),
	(114,'curriculum:addObjectives','Ziele zum Lehrplan hinzufügen','Ability to add objectives','write','curriculum'),
	(115,'user:getHelp','Benutzer anzeigen, die Ziel erfolgreich abgschlossen haben','Ability to see user who accomplished objective','read','curriculum'),
	(116,'groups:showAccomplished','Anzeigen wie viele Gruppenteilnehmer ein Ziel abgeschlossen haben','Ability to see percentage of users who has accomplished a objective','read','curriculum'),
	(117,'file:editMaterial','Material editieren','Ability to edit material','write','curriculum'),
	(118,'groups:showCurriculumEnrolments','Lehrpläne der Lerngruppe anzeigen','Show curricula of groups','read','curriculum'),
	(120,'user:confirmUserSidewide','Benutzer global freigeben','Confirm user sidewide','write','curriculum'),
	(122,'menu:readMyInstitution','Menüblock \"Meine Institution\" anzeigen','Read menublock \"my institutions\"','read','curriculum'),
	(123,'user:expelFromInstitution','Benutzer aus Institution ausschreiben','Expel user from institution','write','curriculum'),
	(124,'user:userListComplete','Alle Benutzer (Instanz!) sehen','See all user','read','curriculum'),
	(125,'user:getInstitution','Institutionszugehörigkeit eines Benuzters anzeigen','See institution enrolments of a user','read','curriculum'),
	(128,'mail:delete','Mitteilungen löschen','Delete messages','write','curriculum'),
	(129,'certificate:add','Vorlage eines Zertifikates hinzufügen','Ability to add certificate template','write','curriculum'),
	(130,'certificate:update','Vorlage eines Zertifikates editieren','Ability to update certificate templates','write','certificate'),
	(131,'certificate:delete','Vorlage eines Zertifikates löschen','Ability to delete certificate templates','write','certificate'),
	(132,'menu:readbadgeview','Badges-Ansicht anzeigen','Ability to see badgeview','read','menu'),
	(133,'file:load','Benutzer darf Dateien laden',NULL,'read','file'),
	(134,'user:update','Eigenes Benutzerprofil bearbeiten','Eigenes Benutzerprofil bearbeiten','write','user'),
	(135,'course:setAccomplishedStatus','Lernstand setzen','Lernstand eines Benutzers setzen','write','course'),
	(136,'user:userListInstitution','Alle Benutzer der Institution anzeigen','','read','curriculum'),
	(137,'user:userListGroup','Alle Benutzer der eigenen Lerngruppen anzeigen','','read','curriculum'),
	(138,'file:uploadAvatar','Profilbilder hochladen','Nutzer darf Profilbilder hochladen','write','curriculum'),
	(141,'menu:readOnlineUsers','Benutzer online','Anzahl der Benutzer anzeigen, die gerade online sind','read','menu'),
	(142,'plugin:useEmbeddableGoogleDocumentViewer','Benutzer darf Dateien über Google Document Embedder einbinden ','Dateien werden direkt im Browser über den Google Document Embedder eingebunden','read','plugin'),
	(143,'file:showHits','Benutzer darf sehen, wie oft eine Datei abgerufen wurde','Benutzer darf sehen, wie oft eine Datei abgerufen wurde','read','file'),
	(144,'quiz:showQuiz','Benutzer darf Quiz sehen','Benutzer darf Quiz sehen','read','quiz'),
	(145,'dashboard:editBulletinBoard','Benutzer darf Pinnwand editieren','Benutzer darf Pinnwand editieren','write','dashboard'),
	(146,'curriculum:import','Benutzer darf Lehrpläne importieren','Benutzer darf Lehrpläne importieren','write','curriculum'),
	(147,'curriculum:showAll','Benutzer darf alle Lehrpläne einsehen','Benutzer','read','curriculum'),
	(148,'menu:readCourseBook','Kursbuch anzeigen','Ability to see coursebook menu inside of institution menu','read','curriculum'),
	(149,'coursebook:add','Kursbucheintrag hinzufügen','Add coursebook entry','write','coursebook'),
	(150,'coursebook:update','Kursbucheintrag editieren','Edit coursebook entry','write','coursebook'),
	(151,'event:add','Termin hinzufügen','Add event','write','coursebook'),
	(152,'event:update','Termin editieren','Edit event','write','coursebook'),
	(153,'task:add','Aufgabe hinzufügen','Add task','write','task'),
	(154,'task:update','Aufgabe editieren','Edit task','write','task'),
	(155,'task:delete','Aufgabe löschen','Delete task','write','task'),
	(156,'event:delete','Termin löschen','Delete event','write','coursebook'),
	(157,'coursebook:delete','Kursbucheintrag löschen','Delete coursebook entry','write','coursebook'),
	(158,'task:enrol','Aufgabe zuweisen','Subscribe task','write','task'),
	(159,'absent:add','Abwesenheitsliste eintragen','Add absent persons','write','curriculum'),
	(160,'absent:update','Abwesenheitsliste bearbeiten','Edit absent list','write','curriculum'),
	(161,'block:add','Blöcke hinzufügen','Add blocks','write','block'),
	(162,'block:update','Blöcke editieren','Edit blocks','write','block'),
	(163,'help:add','Hilfe-Dateien anlegen','Add help files','write','help'),
	(164,'help:update','Hilfe-Dateien bearbeiten','Edit help files','write','help'),
	(165,'help:delete','Hilfe-Dateien löschen','Delete help files','write','help'),
	(166,'course:selfAssessment','Selbsteinschätzung abgeben','Benutzer darf sich selbst einschäten','write','course'),
	(167,'content:add','Inhaltsseiten hinzufügen','Add Content','write','content'),
	(168,'content:update','Inhaltsseiten bearbeiten','Update Content','write','content'),
	(169,'content:delete','Inhaltsseiten löschen','Delete Content','write','content'),
	(170,'wallet:add','Sammelmappe anlegen','Add wallet','write','wallet'),
	(171,'wallet:update','Sammelmappe bearbeiten','Edit wallet','write','wallet'),
	(172,'wallet:delete','Sammelmappe löschen','Delete wallet','write','wallet'),
	(173,'menu:readWallet','Sammelmappe im Menü anzeigen','Show wallen in menü','read','menu'),
	(174,'wallet:workon','Sammelmappeninhalt bearbeiten','Work on wallet','write','wallet'),
	(175,'wallet:share','Sammelmappeninhalt teilen','Share wallet','write','wallet'),
	(176,'comment:add','Kommentare schreiben','Write comments','write','comment'),
	(177,'comment:update','Kommentare bearbeiten','Update comments','write','comment'),
	(178,'comment:delete','Kommentare löschen','Delete comments','write','comment');

/*!40000 ALTER TABLE `capabilities` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle certificate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `certificate`;

CREATE TABLE `certificate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `certificate` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `template` mediumtext,
  `logo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `institution_id` int(11) unsigned NOT NULL,
  `curriculum_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rel_ct_logo` (`logo_id`),
  KEY `rel_ct_user` (`creator_id`),
  KEY `rel_ct_institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `certificate` WRITE;
/*!40000 ALTER TABLE `certificate` DISABLE KEYS */;

INSERT INTO `certificate` (`id`, `certificate`, `description`, `template`, `logo_id`, `creation_time`, `creator_id`, `institution_id`, `curriculum_id`)
VALUES
	(1,'Medienkompass','Vorlage f. Medienkompass Sek. I','<div class=\"center cleaner\" style=\"text-align: center;\"><img src=\"../share/accessfile.php?file=badges/logo.png\" alt=\"\" width=\"150px\" /></div>\r\n<div class=\"center cleaner\" style=\"text-align: center;\">curriculumonline.de</div>\r\n<div class=\"center cleaner\" style=\"text-align: center;\">&nbsp;</div><div class=\"section\">\r\n<div class=\"section\">\r\n<div class=\"layoutArea\">\r\n<div class=\"column\">\r\n<p style=\"text-align: center;\">&nbsp;</p>\r\n<h2>Medienkompass&nbsp;Sek I</h2>\r\n<p>&nbsp;</p>\r\n<h3><!--Vorname--> <!--Nachname--></h3>\r\n<p>hat erfolgreich die folgenden Ziele des Lehrplanes abgeschlossen.</p>\r\n<!--Start-->\r\n<table style=\"width: 100%; padding-bottom: 10px;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 70%; border-bottom: 1px solid silver;\">Ich kann&nbsp;</td>\r\n<td style=\"width: 15%; border-bottom: 1px solid silver;\">mit Hilfe</td>\r\n<td style=\"width: 15%; border-bottom: 1px solid silver;\">selbstst&auml;ndig</td>\r\n</tr>\r\n<tr>\r\n<td><strong><!--Thema--></strong></td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<!--Ziel_Start-->\r\n<tr style=\"border-bottom: 1px solid silver;\">\r\n<td style=\"width: 70%; border-bottom: 1px solid silver;\"><!--Ziel--></td>\r\n<td style=\"width: 15%; border-bottom: 1px solid silver;\"><!--Ziel_mit_Hilfe_erreicht--></td>\r\n<td style=\"width: 15%; border-bottom: 1px solid silver;\"><!--Ziel_erreicht--></td>\r\n</tr>\r\n<!--Ziel_Ende--></tbody>\r\n</table>\r\n<!--Ende--></div>\r\n</div>\r\n</div>\r\n</div>\r\n<p>&nbsp;</p>\r\n<div class=\"column\">\r\n<p>Meine Stadt, <!--Datum--></p>\r\n<p>&nbsp;</p>\r\n<p>__________________________________</p>\r\n<p>Mein Name</p>\r\n</div>',0,'2015-01-02 08:27:35',532,56,0),
	(4,'Medienkompass farbig','Farbiges Zertifikat','<div class=\"center cleaner\" style=\"text-align: center;\"><img src=\"../share/accessfile.php?file=badges/logo.png\" alt=\"\" width=\"150px\" /></div>\r\n<div class=\"center cleaner\" style=\"text-align: center;\">curriculumonline.de</div>\r\n<div class=\"center cleaner\" style=\"text-align: center;\">&nbsp;</div>\r\n<h2>MEDIENKOMP@SS</h2>\r\n<p>Sekundarstufe I</p>\r\n<h3><!--Vorname--> <!--Nachname--></h3>\r\n<p>hat erfolgreich die folgenden Module des Medienkom@sses abgeschlossen.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<!--Start-->\r\n<div class=\"topic\"><!--Thema--></div>\r\n<!--Ziel_Start-->\n<ziel status=\"1\" class=\"objective_green row\"></ziel>\n<ziel status=\"2\" class=\"objective_orange row\"></ziel>\n<ziel status=\"3\" class=\"row\"></ziel>\n<ziel status=\"0\" class=\"objective_red row\"></ziel>\r\n<!--Ziel_Ende--><br><!--Ende-->\r\n<p>Landau, <!--Datum--></p>\r\n<p>&nbsp;</p>\r\n<div>__________________________________</div>\r\n<div><!--Unterschrift--></div>',0,'2015-01-19 15:46:16',532,56,0),
	(5,'Medienkompass Badges neu','Medienkompass','<div class=\"center cleaner\" style=\"text-align: center;\"><img src=\"../share/accessfile.php?file=badges/logo.png\" alt=\"\" width=\"150px\" /></div>\r\n<div class=\"center cleaner\" style=\"text-align: center;\">curriculumonline.de</div>\r\n<div class=\"center cleaner\" style=\"text-align: center;\">&nbsp;</div>\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2 style=\"text-align:center\"><strong><!--Vorname-->&nbsp;<!--Nachname--></strong></h2>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align:center\">hat erfolgreich die folgenden Module des Medienkomp@ss Sek I. (Erwartungshorizont 8. Klasse) abgeschlossen.</p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n<!--Start-->\r\n\r\n<p style=\"text-align:center\"><!--Bereich{324,325,326,327}--><img alt=\"\" src=\"../share/accessfile.php?file=badges/Badge01ortho.png\" style=\"width:100px\" /><!--/Bereich--><!--Bereich{328,329,330,331}--><img alt=\"\" src=\"../share/accessfile.php?file=badges/Badge02ortho.png\" style=\"width:100px\" /><!--/Bereich--><!--Bereich{332,333,334,335}--><img alt=\"\" src=\"../share/accessfile.php?file=badges/Badge03ortho.png\" style=\"width:100px\" /><!--/Bereich--><!--Bereich{336,337,338,339}--><img alt=\"\" src=\"../share/accessfile.php?file=badges/Badge04ortho.png\" style=\"width:100px\" /><!--/Bereich--><!--Bereich{340,341,342,343}--><img alt=\"\" src=\"../share/accessfile.php?file=badges/Badge05ortho.png\" style=\"width:100px\" /><!--/Bereich--></p>\r\n<!--Ende-->\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p style=\"text-align:center\">Landau, den <!--Datum--></p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p style=\"text-align:center\">&nbsp;</p>\r\n\r\n<p style=\"text-align:left\">_________________________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;_________________________</p>\r\n\r\n<p style=\"text-align:left\">(Klassenleitung) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;(Schulleitung) &nbsp; &nbsp;&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Weitere Informationen zu diesem Zertifikate erhalten sie online &uuml;ber den QR-Code.<img alt=\"\" src=\"../share/accessfile.php?file=badges/qr-code.png\" style=\"float:right; height:60px; width:60px\" /></p>\r\n',0,'2015-05-18 19:57:04',532,56,0);

/*!40000 ALTER TABLE `certificate` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reference_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `text` text,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config`;

CREATE TABLE `config` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='curriculum configuration variables';

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;

INSERT INTO `config` (`id`, `name`, `value`, `context_id`, `reference_id`, `timestamp`)
VALUES
	(1, 'auth', 'shibboleth', 19, 0, '2017-01-30 08:53:02'),
	(2, 'repository', 'omega', 19, 0, '2017-01-30 08:53:04'),
	(3, 'template', 'Bootflat-2.0.4', 19, 0, '2017-01-30 08:53:16');

/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle config_plugins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `config_plugins`;

CREATE TABLE `config_plugins` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `plugin` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `confplug_plunam_uix` (`plugin`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='curriculum modules and plugins configuration variables';

LOCK TABLES `config_plugins` WRITE;
/*!40000 ALTER TABLE `config_plugins` DISABLE KEYS */;

INSERT INTO `config_plugins` (`id`, `plugin`, `name`, `value`)
VALUES
	(1133,'auth/shibboleth','user_attribute','uusername'),
	(1134,'auth/shibboleth','organization_selection','urn:mace:organization1:providerID, Example Organization 1\r\nhttps://another.idp-id.com/shibboleth, Other Example Organization, /Shibboleth.sso/DS/SWITCHaai\r\nurn:mace:organization2:providerID, Example Organization 2, /Shibboleth.sso/WAYF/SWITCHaai'),
	(1135,'auth/shibboleth','logout_handler',''),
	(1136,'auth/shibboleth','logout_return_url',''),
	(1137,'auth/shibboleth','login_name','Shibboleth Login'),
	(1138,'auth/shibboleth','convert_data',''),
	(1139,'auth/shibboleth','auth_instructions','Klicken Sie auf den Shibboleth-Button um sich über diesen Dienst anzumelden, wenn Ihr Unternehmen dies unterstützt. <br />Sonst verwenden Sie das normale hier angezeigte Loginformular.'),
	(1140,'auth/shibboleth','changepasswordurl',''),
	(1141,'auth/shibboleth','field_map_firstname','ufirstname'),
	(1142,'auth/shibboleth','field_updatelocal_firstname','oncreate'),
	(1143,'auth/shibboleth','field_lock_firstname','unlocked'),
	(1144,'auth/shibboleth','field_map_lastname','ulastname'),
	(1145,'auth/shibboleth','field_updatelocal_lastname','oncreate'),
	(1146,'auth/shibboleth','field_lock_lastname','unlocked'),
	(1147,'auth/shibboleth','field_map_email','uemail'),
	(1148,'auth/shibboleth','field_updatelocal_email','oncreate'),
	(1149,'auth/shibboleth','field_lock_email','unlocked'),
	(1168,'auth/shibboleth','field_map_institution','uinstitution'),
	(1169,'auth/shibboleth','field_updatelocal_institution','oncreate'),
	(1170,'auth/shibboleth','field_lock_institution','unlocked');

/*!40000 ALTER TABLE `config_plugins` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `content`;

CREATE TABLE `content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `timecreated` timestamp NULL DEFAULT NULL,
  `timemodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `creator_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;

INSERT INTO `content` (`id`, `title`, `content`, `timecreated`, `timemodified`, `creator_id`)
VALUES
	(1,'Datenschutzerklärung und Nutzungsbedingungen für curriculum (Nutzungsvereinbarung)','<p><h3>1 Datenschutzerklärung</h3></p>\r<p><h4>1.1 Hinweise zum Datenschutz und Einwilligung zur Verarbeitung personenbezogener Daten.</h4></p>\rDie Nutzung von curriculum ist freiwillig. Auf curriculum werden ab der Registrierung als Nutzerin oder Nutzer von Ihnen eingegebene oder mit Ihrer Nutzung anfallende Daten automatisch verarbeitet. Soweit diese auf Ihre Person und nicht nur auf eine fingierte Identität verweisen, handelt es sich um personenbezogene Daten gemäß § 3 BDSG (Bundesdatenschutzgesetz). Darum gelten auch für curriculum datenschutzrechtliche Regelungen des BDSG, die die automatische Verarbeitung personenbezogener Daten beinhalten (§ 4, §6 und §13 BDSG). Diese verlangen die eingehende Information der Betroffenen über Art und Umfang der Erhebung ihrer personenbezogenen Daten und die Art und Weise ihrer weiteren Verarbeitung.\rÜber die in der Anmeldung angegebenen, teils automatisch anfallenden, teils vom Nutzer zusätzlich eingegebenen Informationen hinaus protokolliert curriculum in einer Datenbank, zu welcher Zeit welche Nutzer/innen auf welche Bestandteile der Lehrangebote bzw. Profile anderer Nutzer/innen zugreifen. Protokolliert wird ferner unter anderem, je nach Ausgestaltung des einzelnen Lehrangebots, ob Teilnehmerinnen und Teilnehmern gestellte Aufgaben erledigt haben.\rAll diese Daten sind der Administration von curriculum und der Leitung der jeweiligen Institution/ Lerngruppe zugänglich, nicht jedoch anderen Nutzerinnen und Nutzern. Sie dienen der Durchführung des jeweiligen Kompetenz-Zertifizierung.\rSofern im Rahmen von curriculum auf externe Quellen (z. B. Mediensammlungen) zugegriffen wird, können diesbezüglich anonymisierte Nutzungsdaten zu statistischen Zwecken erhoben werden. Eine darüber hinaus gehende Datenweitergabe findet nicht statt.\r<p><h4>1.2 Einwilligung</h4></p>\rMit der Registrierung und Nutzung von curriculum auf curriculumonline.de (kurz curriculum) geben Sie, in Kenntnis dieser Erläuterungen, Ihre Einwilligung zu der bezeichneten Datenerhebung und - verwendung. Diese Einwilligung ist jederzeit frei widerruflich durch entsprechende Erklärung gegenüber dem Betreuer von curriculum an der jeweiligen Bildungseinrichtung. Bei einem Widerruf wird Ihr Nutzungszugang auf curriculumonline.de gelöscht.\rTeilnehmer räumen mit der Unterzeichnung dieser Erklärung ihrer Institution das Nutzungsrecht an ihren Lösungseinreichungen etc. ein.\r<p><h3>2 Benutzerordnung</h3></p>\rDiese Nutzerordnung regelt die Arbeit mit curriculum auf curriculumonline.de\r<p><h4>2.1 Geltungsbereich und Inkrafttreten</h4></p>\rDiese Nutzungsordnung tritt am 01.12.2015 in Kraft. Sie gilt für die Gesamtheit aller mit curriculum verbundenen Systeme und Dienste.\rNutzungs- und Weisungsberechtigung\rNutzungsberechtigt sind Lehrerinnen und Lehrer, Schülerinnen und Schüler sowie mit der Betreuung des Systems beauftragte Personen, die sich bei curriculum mit ihrem echten Vor- und Nachnamen sowie unter Angabe einer gültigen E-Mail- Adresse registriert oder von Ihrer Bildungseinrichtung einen entsprechenden Zugang erhalten haben. Der Zugang zu den Lernbereichen wird durch die Administrator(en) / Manager geregelt. Weisungsberechtigt sind die jeweiligen Manager sowie die mit der Administration der Plattform beauftragten Personen. In Ausnahmefällen können verantwortungsbewusste Teilnehmerinnen oder Teilnehmer von einem Manager als zusätzliche Trainer eingesetzt werden.\r<p><h4>2.2 Arbeiten auf curriculum</h4></p>\rDie Lernplattform und sämtliche dort zugänglichen Dienste und Dateien dürfen nur ohne finanzielle\nund politische Interessen bzw. Absichten im Rahmen von Bildungskontexten genutzt werden und grundsätzlich nicht über den Teilnehmerkreis hinaus verbreitet werden.\rInsbesondere dürfen Kopien von Dateien und Medien oder selbsterstellte Materialien, die in wesentlichen Teilen auf diesen Dateien beruhen, nicht Personen außerhalb des Nutzerkreises von curriculum zur Verfügung gestellt, öffentlich vorgeführt oder veröffentlicht werden (Das Veröffentlichungsverbot betrifft damit Plattformen, wie z.B. YouTube, ebenso wie Lehrertauschbörsen und öffentlich zugängliche Schulhomepages.)\rWerden Inhalte oder Ausschnitte von Dateien in eigenen Werken (z.B. Präsentationen oder Arbeitsblättern) genutzt, so ist die Quelle (möglichst Herausgeber bzw. Urheber, Jahr, Titel, zumindest aber Titel, Link-URL und Zeitsignatur) anzugeben.\rVeränderungen der Installation und Konfiguration curriculum auf curriculumonline.de sowie Manipulationen an der Serversoftware sind grundsätzlich untersagt.\r<p><h5>2.2.1 Benutzerkonten und Profile</h5></p>\rEine Nutzerin oder ein Nutzer hat sich auf curriculum nur unter dem ihr/ihm zugewiesenen Nutzernamen anzumelden. Der Nutzer ist für alle Aktivitäten, die unter diesem Nutzernamen ablaufen, verantwortlich. Die Arbeitsstation, an der sich eine Nutzerin oder ein Nutzer bei curriculum angemeldet hat, darf nicht von diesem unbeaufsichtigt gelassen werden. Nach dem Beenden der Nutzung hat sich eine Nutzerin/ein Nutzer von curriculum abzumelden.\rDie Benutzerkonten sind durch sinnvoll gewählte Passwörter, die den Standardvorgaben entsprechen, gegen unbefugten Zugriff zu sichern. Die Passwörter sind geheim zu halten. Jede Nutzerin/jeder Nutzer ist dafür verantwortlich, dass nur er oder sie alleine seine/ihre persönlichen Passwörter kennt und zugewiesene Passwörter nicht weitergibt.\rDas Ausprobieren, das Ausforschen und die Benutzung fremder Zugriffsberechtigungen und sonstiger Authentifizierungsmittel sind, wie der Zugriff auf fremde Lernbereiche und Daten, unzulässig. Der Einsatz von sog. \"Spyware\" (z.B. Sniffer) oder Schadsoftware (z.B. Viren, Würmer) ist auf curriculum strengstens untersagt.\r<p><h5>2.2.2 Umgang mit E-Mail</h5></p>\rJede Nutzerin bzw. jeder Nutzer ist selbst für den Erhalt und die Verarbeitung von Nachrichten in curriculum verantwortlich. Die Angabe einer ungültigen E-Mail-Adresse ist nicht zulässig. Das Abschalten der E-Mail-Funktion in den Profileinstellungen entbindet nicht von der Pflicht, sich selbständig über alle aktuellen Vorgänge im Lernbereich / auf curriculum und Anweisungen der Manager / Trainer / Administration zu informieren. In der BETA Version erfolgt kein Versand von Emails. Es wird vorerst ausschließlich das interne Nachrichtensystem angeboten.\r<p><h5>2.2.3 Lehrpläne und Trainer</h5></p>\rAlle Trainer (Lehrerinnen und Lehrer) können in ihren Lernbereichen die Daten der Nutzerinnen und Nutzer einsehen und sind ihnen gegenüber auf Nachfrage darüber auskunftspflichtig (§19 BDSG). Sie geben derartige Daten zu keinem Zeitpunkt an Dritte weiter und nutzen diese ausschließlich zu pädagogischen Zwecken im vorgesehenen dienstlichen Kontext.\r<p><h5>2.2.4 Datenschutz und Datensicherheit</h5></p>\rAlle auf curriculum befindlichen Daten unterliegen dem Zugriff der Systemadministratoren von curriculumonline.de. Diese können bei dringendem Handlungsbedarf unangemeldet Daten einsehen, löschen oder verändern. Die Nutzerin bzw. der Nutzer wird über einen solchen Eingriff - notfalls nachträglich - angemessen informiert. Die Kontaktdaten der Administratoren sind über die Startseite (Impressum) zu erfahren.\rDavon unberührt besteht der Rechtsanspruch auf den Schutz persönlicher Daten vor unbefugten Zugriffen.\rEin Rechtsanspruch auf die Sicherung, Speicherung und Verfügbarkeit persönlicher Daten (auch:\r\nLehrpläne oder Teile hiervon) besteht gegenüber dem Betreiber nicht.\r<p><h5>2.2.5 Informationsübertragung in das Internet</h5></p>\rDer Trainer (Lehrer und Lehrerin) ist verantwortlich für das Angebot in seinem/ihrem Lernbereich. Eine Geheimhaltung von Daten, die über das Internet übertragen werden, wird über die derzeit technisch möglichen Sicherheitsmechanismen (z.B. verschlüsselte Übertragung per https) vom Betreiber gewährleistet.\rEs ist untersagt, curriculum zur Verbreitung von Informationen zu verwenden, die dazu geeignet sind, dem Ansehen der Plattform curriculum Schaden zuzufügen.\rEs ist verboten, Informationen zur Verfügung zu stellen, die rechtlichen Grundsätzen widersprechen. Dies gilt insbesondere für rassistische, ehrverletzende, beleidigende oder aus anderen Gründen gegen geltendes Recht verstoßende Inhalte. Die Bestimmungen des Bundesdatenschutzgesetzes sind einzuhalten. Dies gilt insbesondere für die Bekanntgabe von Namen und Adressdaten oder die Veröffentlichung von Fotografien ohne die ausdrückliche Genehmigung der davon betroffenen Personen bzw. des Rechteinhabers. Grundsätze, wie sie beispielhaft in der Netiquette, dem „Knigge“ im Bereich der Datenkommunikation, beschrieben sind, sind einzuhalten.\r<p><h5>2.2.6 Sonstige Regelungen</h5></p>\rDer Zugang zu fragwürdigen Informationen im Internet kann aus verschiedenen Gründen nicht immer verhindert werden. Die Trainer (Lehrerin oder Lehrer) kommen ihrer Aufsichtspflicht gegenüber Minderjährigen durch regelmäßige Kontrolle in angemessenen Zeitabständen der in ihren Lernbereich zur Verfügung gestellten Module (Lehrpläne etc.) nach. Sie haben die Verpflichtung, bei Bekanntwerden von Regelverstößen die Regelverletzung unverzüglich zu beenden.\rDie Nutzung der Plattform zum Tauschen von oder Verlinken auf lizenzrechtlich geschützte Daten und Dateien ist verboten, soweit nicht die Rechte für den jeweiligen Nutzungskontext und den im jeweiligen Kurs agierenden Nutzerkreis erworben wurden bzw. durch Dritte gewährt werden.\rEs ist verboten, Daten (auch Links), die rechtlichen Grundsätzen in der Bundesrepublik Deutschland widersprechen, auf curriculum zur Verfügung zu stellen. Das gilt insbesondere für Daten mit Gewalt verherrlichendem, pornographischem oder nationalsozialistischem Inhalt.\r<p><h4>2.3 Datenvolumen</h4></p>\rUnnötiges Datenaufkommen durch Laden und Versenden von großen Dateien (z.B. Grafiken, Videos oder Audiodateien) über curriculum ist zu vermeiden. Sollte eine Nutzerin oder ein Nutzer unberechtigt größere Datenmengen in seinem Arbeitsbereich ablegen, so sind die Administratorinnen und Administratoren nach Vorankündigung berechtigt, diese Daten zu löschen. Lehrkräften wird empfohlen größere Mediendateien soweit als möglich über Spezialplattformen zu veröffentlichen bzw. über die entsprechenden Funktionen in curriculum einzubinden.\rZuwiderhandlungen gegen diese Nutzungsbedingungen oder ein Missbrauch des Zugangs zu curriculum können, neben dem Entzug der Nutzungsberechtigung für die curriculum, auch Zivil- und strafrechtliche Konsequenzen nach sich ziehen.\r<p><h3>3 Schlussbedingung</h3></p>\rSollten einzelne Bestimmungen dieser Nutzungsbedingungen ganz oder teilweise unwirksam sein oder werden, berührt dies nicht die Wirksamkeit der übrigen Bestimmungen.<br>Stand: 01.12.2015',NULL,'2017-01-29 13:10:33',532);

/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle content_subscriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `content_subscriptions`;

CREATE TABLE `content_subscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `context_id` int(11) NOT NULL,
  `file_context` int(11) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `timecreated` timestamp NULL DEFAULT NULL,
  `timemodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `creator_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `content_subscriptions` WRITE;
/*!40000 ALTER TABLE `content_subscriptions` DISABLE KEYS */;

INSERT INTO `content_subscriptions` (`id`, `content_id`, `context_id`, `file_context`, `reference_id`, `timecreated`, `timemodified`, `status`, `creator_id`)
VALUES
	(1,1,14,1,0,NULL,'2017-01-29 13:33:56',1,532);

/*!40000 ALTER TABLE `content_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle context
# ------------------------------------------------------------

DROP TABLE IF EXISTS `context`;

CREATE TABLE `context` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `context` varchar(100) DEFAULT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `context` WRITE;
/*!40000 ALTER TABLE `context` DISABLE KEYS */;

INSERT INTO `context` (`id`, `context`, `context_id`, `path`)
VALUES
	(1, 'userFiles', 1, 'user/'),
	(2, 'curriculum', 2, 'curriculum/'),
	(3, 'avatar', 3, 'user/'),
	(4, 'solution', 4, 'solutions/'),
	(5, 'subjectIcon', 5, 'subjects/'),
	(6, 'badge', 6, 'badges/'),
	(7, 'editor', 7, 'user/'),
	(8, 'backup', 8, 'backups/'),
	(9, 'institution', 9, 'institution/'),
	(10, 'courseBook', 10, 'coursebook/'),
	(11, 'dashboard', 11, NULL),
	(12, 'enabling_objective', 12, NULL),
	(13, 'task', 13, NULL),
	(14, 'terms', 14, NULL),
	(15, 'content', 15, NULL),
	(16, 'group', 16, NULL),
	(17, 'course', 17, NULL),
	(18, 'wallet', 18, NULL),
	(19, 'config', 19, NULL);

/*!40000 ALTER TABLE `context` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `code` char(2) NOT NULL,
  `en` varchar(50) NOT NULL DEFAULT '',
  `de` varchar(50) NOT NULL DEFAULT '',
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `de` (`de`),
  KEY `en` (`en`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`code`, `en`, `de`, `id`)
VALUES
	('AD','Andorra','Andorra',1),
	('AE','United Arab Emirates','Vereinigte Arabische Emirate',2),
	('AF','Afghanistan','Afghanistan',3),
	('AG','Antigua and Barbuda','Antigua und Barbuda',4),
	('AI','Anguilla','Anguilla',5),
	('AL','Albania','Albanien',6),
	('AM','Armenia','Armenien',7),
	('AN','Netherlands Antilles','Niederländische Antillen',8),
	('AO','Angola','Angola',9),
	('AQ','Antarctica','Antarktis',10),
	('AR','Argentina','Argentinien',11),
	('AS','American Samoa','Amerikanisch-Samoa',12),
	('AT','Austria','Österreich',13),
	('AU','Australia','Australien',14),
	('AW','Aruba','Aruba',15),
	('AX','Aland Islands','Aland',16),
	('AZ','Azerbaijan','Aserbaidschan',17),
	('BA','Bosnia and Herzegovina','Bosnien und Herzegowina',18),
	('BB','Barbados','Barbados',19),
	('BD','Bangladesh','Bangladesch',20),
	('BE','Belgium','Belgien',21),
	('BF','Burkina Faso','Burkina Faso',22),
	('BG','Bulgaria','Bulgarien',23),
	('BH','Bahrain','Bahrain',24),
	('BI','Burundi','Burundi',25),
	('BJ','Benin','Benin',26),
	('BM','Bermuda','Bermuda',27),
	('BN','Brunei','Brunei Darussalam',28),
	('BO','Bolivia','Bolivien',29),
	('BR','Brazil','Brasilien',30),
	('BS','Bahamas','Bahamas',31),
	('BT','Bhutan','Bhutan',32),
	('BV','Bouvet Island','Bouvetinsel',33),
	('BW','Botswana','Botswana',34),
	('BY','Belarus','Belarus (Weißrussland)',35),
	('BZ','Belize','Belize',36),
	('CA','Canada','Kanada',37),
	('CC','Cocos (Keeling) Islands','Kokosinseln (Keelinginseln)',38),
	('CD','Congo (Kinshasa)','Kongo',39),
	('CF','Central African Republic','Zentralafrikanische Republik',40),
	('CG','Congo (Brazzaville)','Republik Kongo',41),
	('CH','Switzerland','Schweiz',42),
	('CI','Ivory Coast','Elfenbeinküste',43),
	('CK','Cook Islands','Cookinseln',44),
	('CL','Chile','Chile',45),
	('CM','Cameroon','Kamerun',46),
	('CN','China','China, Volksrepublik',47),
	('CO','Colombia','Kolumbien',48),
	('CR','Costa Rica','Costa Rica',49),
	('CS','Serbia And Montenegro','Serbien und Montenegro',50),
	('CU','Cuba','Kuba',51),
	('CV','Cape Verde','Kap Verde',52),
	('CX','Christmas Island','Weihnachtsinsel',53),
	('CY','Cyprus','Zypern',54),
	('CZ','Czech Republic','Tschechische Republik',55),
	('DE','Germany','Deutschland',56),
	('DJ','Djibouti','Dschibuti',57),
	('DK','Denmark','Dänemark',58),
	('DM','Dominica','Dominica',59),
	('DO','Dominican Republic','Dominikanische Republik',60),
	('DZ','Algeria','Algerien',61),
	('EC','Ecuador','Ecuador',62),
	('EE','Estonia','Estland (Reval)',63),
	('EG','Egypt','Ägypten',64),
	('EH','Western Sahara','Westsahara',65),
	('ER','Eritrea','Eritrea',66),
	('ES','Spain','Spanien',67),
	('ET','Ethiopia','Äthiopien',68),
	('FI','Finland','Finnland',69),
	('FJ','Fiji','Fidschi',70),
	('FK','Falkland Islands','Falklandinseln (Malwinen)',71),
	('FM','Micronesia','Mikronesien',72),
	('FO','Faroe Islands','Färöer',73),
	('FR','France','Frankreich',74),
	('GA','Gabon','Gabun',75),
	('GB','United Kingdom','Großbritannien und Nordirland',76),
	('GD','Grenada','Grenada',77),
	('GE','Georgia','Georgien',78),
	('GF','French Guiana','Französisch-Guayana',79),
	('GG','Guernsey','Guernsey (Kanalinsel)',80),
	('GH','Ghana','Ghana',81),
	('GI','Gibraltar','Gibraltar',82),
	('GL','Greenland','Grönland',83),
	('GM','Gambia','Gambia',84),
	('GN','Guinea','Guinea',85),
	('GP','Guadeloupe','Guadeloupe',86),
	('GQ','Equatorial Guinea','Äquatorialguinea',87),
	('GR','Greece','Griechenland',88),
	('GS','South Georgia and the South Sandwich Islands','Südgeorgien und die Südl. Sandwichinseln',89),
	('GT','Guatemala','Guatemala',90),
	('GU','Guam','Guam',91),
	('GW','Guinea-Bissau','Guinea-Bissau',92),
	('GY','Guyana','Guyana',93),
	('HK','Hong Kong S.A.R., China','Hongkong',94),
	('HM','Heard Island and McDonald Islands','Heard- und McDonald-Inseln',95),
	('HN','Honduras','Honduras',96),
	('HR','Croatia','Kroatien',97),
	('HT','Haiti','Haiti',98),
	('HU','Hungary','Ungarn',99),
	('ID','Indonesia','Indonesien',100),
	('IE','Ireland','Irland',101),
	('IL','Israel','Israel',102),
	('IM','Isle of Man','Insel Man',103),
	('IN','India','Indien',104),
	('IO','British Indian Ocean Territory','Britisches Territorium im Indischen Ozean',105),
	('IQ','Iraq','Irak',106),
	('IR','Iran','Iran',107),
	('IS','Iceland','Island',108),
	('IT','Italy','Italien',109),
	('JE','Jersey','Jersey (Kanalinsel)',110),
	('JM','Jamaica','Jamaika',111),
	('JO','Jordan','Jordanien',112),
	('JP','Japan','Japan',113),
	('KE','Kenya','Kenia',114),
	('KG','Kyrgyzstan','Kirgisistan',115),
	('KH','Cambodia','Kambodscha',116),
	('KI','Kiribati','Kiribati',117),
	('KM','Comoros','Komoren',118),
	('KN','Saint Kitts and Nevis','St. Kitts und Nevis',119),
	('KP','North Korea','Nordkorea',120),
	('KR','South Korea','Südkorea',121),
	('KW','Kuwait','Kuwait',122),
	('KY','Cayman Islands','Kaimaninseln',123),
	('KZ','Kazakhstan','Kasachstan',124),
	('LA','Laos','Laos',125),
	('LB','Lebanon','Libanon',126),
	('LC','Saint Lucia','St. Lucia',127),
	('LI','Liechtenstein','Liechtenstein',128),
	('LK','Sri Lanka','Sri Lanka',129),
	('LR','Liberia','Liberia',130),
	('LS','Lesotho','Lesotho',131),
	('LT','Lithuania','Litauen',132),
	('LU','Luxembourg','Luxemburg',133),
	('LV','Latvia','Lettland',134),
	('LY','Libya','Libyen',135),
	('MA','Morocco','Marokko',136),
	('MC','Monaco','Monaco',137),
	('MD','Moldova','Moldawien',138),
	('MG','Madagascar','Madagaskar',139),
	('MH','Marshall Islands','Marshallinseln',140),
	('MK','Macedonia','Mazedonien',141),
	('ML','Mali','Mali',142),
	('MM','Myanmar','Myanmar (Burma)',143),
	('MN','Mongolia','Mongolei',144),
	('MO','Macao S.A.R., China','Macao',145),
	('MP','Northern Mariana Islands','Nördliche Marianen',146),
	('MQ','Martinique','Martinique',147),
	('MR','Mauritania','Mauretanien',148),
	('MS','Montserrat','Montserrat',149),
	('MT','Malta','Malta',150),
	('MU','Mauritius','Mauritius',151),
	('MV','Maldives','Malediven',152),
	('MW','Malawi','Malawi',153),
	('MX','Mexico','Mexiko',154),
	('MY','Malaysia','Malaysia',155),
	('MZ','Mozambique','Mosambik',156),
	('NA','Namibia','Namibia',157),
	('NC','New Caledonia','Neukaledonien',158),
	('NE','Niger','Niger',159),
	('NF','Norfolk Island','Norfolkinsel',160),
	('NG','Nigeria','Nigeria',161),
	('NI','Nicaragua','Nicaragua',162),
	('NL','Netherlands','Niederlande',163),
	('NO','Norway','Norwegen',164),
	('NP','Nepal','Nepal',165),
	('NR','Nauru','Nauru',166),
	('NU','Niue','Niue',167),
	('NZ','New Zealand','Neuseeland',168),
	('OM','Oman','Oman',169),
	('PA','Panama','Panama',170),
	('PE','Peru','Peru',171),
	('PF','French Polynesia','Französisch-Polynesien',172),
	('PG','Papua New Guinea','Papua-Neuguinea',173),
	('PH','Philippines','Philippinen',174),
	('PK','Pakistan','Pakistan',175),
	('PL','Poland','Polen',176),
	('PM','Saint Pierre and Miquelon','St. Pierre und Miquelon',177),
	('PN','Pitcairn','Pitcairninseln',178),
	('PR','Puerto Rico','Puerto Rico',179),
	('PS','Palestinian Territory','Palästinensische Autonomiegebiete',180),
	('PT','Portugal','Portugal',181),
	('PW','Palau','Palau',182),
	('PY','Paraguay','Paraguay',183),
	('QA','Qatar','Katar',184),
	('RE','Reunion','Reunion',185),
	('RO','Romania','Rumänien',186),
	('RU','Russia','Russische Föderation',187),
	('RW','Rwanda','Ruanda',188),
	('SA','Saudi Arabia','Saudi-Arabien',189),
	('SB','Solomon Islands','Salomonen',190),
	('SC','Seychelles','Seychellen',191),
	('SD','Sudan','Sudan',192),
	('SE','Sweden','Schweden',193),
	('SG','Singapore','Singapur',194),
	('SH','Saint Helena','St. Helena',195),
	('SI','Slovenia','Slowenien',196),
	('SJ','Svalbard and Jan Mayen','Svalbard und Jan Mayen',197),
	('SK','Slovakia','Slowakei',198),
	('SL','Sierra Leone','Sierra Leone',199),
	('SM','San Marino','San Marino',200),
	('SN','Senegal','Senegal',201),
	('SO','Somalia','Somalia',202),
	('SR','Suriname','Suriname',203),
	('ST','Sao Tome and Principe','Sao Tome und PrÃincipe',204),
	('SV','El Salvador','El Salvador',205),
	('SY','Syria','Syrien',206),
	('SZ','Swaziland','Swasiland',207),
	('TC','Turks and Caicos Islands','Turks- und Caicosinseln',208),
	('TD','Chad','Tschad',209),
	('TF','French Southern Territories','Französische Süd- und Antarktisgebiete',210),
	('TG','Togo','Togo',211),
	('TH','Thailand','Thailand',212),
	('TJ','Tajikistan','Tadschikistan',213),
	('TK','Tokelau','Tokelau',214),
	('TL','East Timor','Timor-Leste',215),
	('TM','Turkmenistan','Turkmenistan',216),
	('TN','Tunisia','Tunesien',217),
	('TO','Tonga','Tonga',218),
	('TR','Turkey','Türkei',219),
	('TT','Trinidad and Tobago','Trinidad und Tobago',220),
	('TV','Tuvalu','Tuvalu',221),
	('TW','Taiwan','Taiwan',222),
	('TZ','Tanzania','Tansania',223),
	('UA','Ukraine','Ukraine',224),
	('UG','Uganda','Uganda',225),
	('UM','United States Minor Outlying Islands','Amerikanisch-Ozeanien',226),
	('US','United States','Vereinigte Staaten von Amerika',227),
	('UY','Uruguay','Uruguay',228),
	('UZ','Uzbekistan','Usbekistan',229),
	('VA','Vatican','Vatikanstadt',230),
	('VC','Saint Vincent and the Grenadines','St. Vincent und die Grenadinen',231),
	('VE','Venezuela','Venezuela',232),
	('VG','British Virgin Islands','Britische Jungferninseln',233),
	('VI','U.S. Virgin Islands','Amerikanische Jungferninseln',234),
	('VN','Vietnam','Vietnam',235),
	('VU','Vanuatu','Vanuatu',236),
	('WF','Wallis and Futuna','Wallis und Futuna',237),
	('WS','Samoa','Samoa',238),
	('YE','Yemen','Jemen',239),
	('YT','Mayotte','Mayotte',240),
	('ZA','South Africa','Südafrika',241),
	('ZM','Zambia','Sambia',242),
	('ZW','Zimbabwe','Simbabwe',243);

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle course_book
# ------------------------------------------------------------

DROP TABLE IF EXISTS `course_book`;

CREATE TABLE `course_book` (
  `cb_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic` text,
  `description` text,
  `creation_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned NOT NULL COMMENT 'id of curriculum_entrolment',
  `timestart` timestamp NULL DEFAULT NULL,
  `timeend` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cb_id`),
  KEY `rel_cb_user` (`creator_id`),
  KEY `rel_cb_course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle cronjobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cronjobs`;

CREATE TABLE `cronjobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cronjob` varchar(200) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `log` varchar(2048) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle curriculum
# ------------------------------------------------------------

DROP TABLE IF EXISTS `curriculum`;

CREATE TABLE `curriculum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `curriculum` text,
  `grade_id` int(11) unsigned NOT NULL,
  `subject_id` int(11) unsigned NOT NULL,
  `schooltype_id` int(11) unsigned NOT NULL,
  `state_id` int(11) unsigned NOT NULL,
  `description` text,
  `country_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `icon_id` int(11) unsigned NOT NULL DEFAULT '0',
  `color` char(9) NOT NULL DEFAULT '#3c8dbc99',
  PRIMARY KEY (`id`),
  KEY `rel_cu_user` (`creator_id`),
  KEY `rel_cu_icon` (`icon_id`),
  KEY `rel_cu_country` (`country_id`),
  KEY `rel_cu_state` (`state_id`),
  KEY `rel_cu_schooltype` (`schooltype_id`),
  KEY `rel_cu_subject` (`subject_id`),
  KEY `rel_cu_grade` (`grade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `curriculum` WRITE;
/*!40000 ALTER TABLE `curriculum` DISABLE KEYS */;

INSERT INTO `curriculum` (`id`, `curriculum`, `grade_id`, `subject_id`, `schooltype_id`, `state_id`, `description`, `country_id`, `creation_time`, `creator_id`, `icon_id`, `color`)
VALUES
	(109,'Medienkompass Sek I',48,12,1,11,'Erwartungshorizont Klasse 8',56,'2016-01-14 20:13:29',532,124,'#3c8dbc99');

/*!40000 ALTER TABLE `curriculum` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle curriculum_enrolments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `curriculum_enrolments`;

CREATE TABLE `curriculum_enrolments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'used by course book',
  `status` int(11) NOT NULL DEFAULT '0',
  `curriculum_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expel_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ce_curriculum` (`curriculum_id`),
  KEY `rel_ce_group` (`group_id`),
  KEY `rel_ce_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `curriculum_enrolments` WRITE;
/*!40000 ALTER TABLE `curriculum_enrolments` DISABLE KEYS */;

INSERT INTO `curriculum_enrolments` (`id`, `status`, `curriculum_id`, `group_id`, `creation_time`, `expel_time`, `creator_id`)
VALUES
	(1,1,109,1,'2016-12-15 22:15:45','0000-00-00 00:00:00',102);

/*!40000 ALTER TABLE `curriculum_enrolments` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle curriculum_niveaus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `curriculum_niveaus`;

CREATE TABLE `curriculum_niveaus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `description` text,
  `base_curriculum_id` int(11) unsigned NOT NULL,
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `curriculum_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle enablingObjectives
# ------------------------------------------------------------

DROP TABLE IF EXISTS `enablingObjectives`;

CREATE TABLE `enablingObjectives` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enabling_objective` text,
  `description` text,
  `curriculum_id` int(11) unsigned NOT NULL,
  `terminal_objective_id` int(11) unsigned NOT NULL DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `repeat_interval` int(11) unsigned NOT NULL DEFAULT '0',
  `order_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rel_eo_curriculum` (`curriculum_id`),
  KEY `rel_eo_terminal_objective` (`terminal_objective_id`),
  KEY `rel_eo_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `enablingObjectives` WRITE;
/*!40000 ALTER TABLE `enablingObjectives` DISABLE KEYS */;

INSERT INTO `enablingObjectives` (`id`, `enabling_objective`, `description`, `curriculum_id`, `terminal_objective_id`, `creation_time`, `creator_id`, `repeat_interval`, `order_id`)
VALUES
	(1,'vorgegebene Lesetechniken und –strategien anwenden und Ergebnisse mit digitalen Werkzeugen strukturieren und sichern','<p>z.B. navigierendes Lesen, kursorisches Lesen; Tag Clouds, Votings, Likes auswerten und Ergebnisse festhalten, tempor&auml;re Lernnetzwerke gezielt nutzen und auswerten</p>',109,324,'2016-01-14 20:13:29',532,0,1),
	(2,'unter Anleitung Suchstrategien beim Umgang mit digitalen Quellen anwenden','<p>z.B. erweiterte Suchoptionen von Suchmaschinen nutzen, Aufgaben in Lernportalen (z.B Planet Schule, lernen online.rlp) bearbeiten</p>',109,324,'2016-01-14 20:13:29',532,0,2),
	(3,'digitale Wissensquellen kennen und begleitet mit ihnen umgehen','<p class=\"p1\"><span class=\"s1\">z.B. digitale, interaktiv und multi-<span class=\"Apple-converted-space\">&nbsp; </span>sowie cross-medial gestaltete Angebote; digitale Lernmaterialien (z.B. auf dem OMEGA-Server),<span class=\"Apple-converted-space\">&nbsp; </span>Internetdienste, Lernportale,&hellip;</span></p>',109,324,'2016-01-14 20:13:29',532,0,3),
	(4,'die Glaubwürdigkeit und Seriosität digitaler Quellen anhand vorgegebener Kriterien bewerten  und relevant erscheinende neue Quellen nach diesen Kriterien erschließen','<p class=\"p1\"><span class=\"s1\">z.B. Votings, Likes, Kommentarfunktionen und Bewertungstools bei digitalen Quellen, Blogs und Foren kritisch bewerten; Impressumsangaben kritisch lesen</span></p>',109,324,'2016-01-14 20:13:29',532,0,4),
	(5,'Grundzüge des Urheberrechts benennen und beachten','<p class=\"p1\"><span class=\"s1\">Umgang mit (digitalen) Texten, T&ouml;nen, Bildern, Filmzitaten (z.B. Teaser, Trailer)</span></p>',109,325,'2016-01-14 20:13:29',532,0,1),
	(6,'Nach Vorgabe digitale Informationsquellen und Zitate korrekt kennzeichnen','',109,325,'2016-01-14 20:13:29',532,0,2),
	(7,'Folgen der Urheberrechtsverletzung korrekt einschätzen und danach handeln','<p class=\"p1\"><span class=\"s1\">klicksafe: Nicht alles, was geht, ist auch erlaubt; respect copyrights</span></p>',109,325,'2016-01-14 20:13:29',532,0,3),
	(8,'Auf der Grundlage eines Beurteilungsrasters spezifische Eigenschaften verschiedener, insbesondere digitaler Informationsquellen kennen und benennen','<p class=\"p1\"><span class=\"s1\">z.B. St&auml;rken, Grenzen, Tendenzen digitaler Informationsangebote einordnen </span></p>',109,326,'2016-01-14 20:13:29',532,0,1),
	(9,'Funktionen von digitalen Informationsquellen und medialen Texten  anhand von Kriterien erkennen, benennen und angemessen beurteilen','<p class=\"p1\"><span class=\"s1\">z.B. Nachrichtenvergleich (z.B. Second Screen), Formen der Inszenierung bzgl. Sachinformation, Unterhaltung, Kommentar, Werbung&hellip;</span></p>',109,326,'2016-01-14 20:13:29',532,0,2),
	(10,'Sich unter vorgegebenen Leitfragen kritisch mit Wirkungen der Mediengestaltung und der Konstruktion von Wirklichkeit  auseinandersetzen','<p class=\"p1\"><span class=\"s1\">z.B. Pr&auml;sentationsformen, Effekte, Zielrichtung, Adressaten wie z.B. beim Aufbau von Webseiten</span></p>',109,326,'2016-01-14 20:13:29',532,0,3),
	(11,'Angeleitet aus vorgegebenen digitalen Informationsquellen und in angemessener Zeit Informationen gewinnen, Aussagen wiedergeben, Fakten zielgerichtet auswählen','<p class=\"p1\"><span class=\"s1\">s. TK2 (Zitate korrekt kennzeichnen)</span></p>',109,327,'2016-01-14 20:13:29',532,0,1),
	(12,'Die getroffene Auswahl an Informationen mit Blick auf die Ziel- bzw. Aufgabenstellung begründen','',109,327,'2016-01-14 20:13:29',532,0,2),
	(13,'Informationen mit Hilfestellung neu anordnen und kategorisieren, mit eigenen Worten wiedergeben','',109,327,'2016-01-14 20:13:29',532,0,3),
	(14,'Bei der Aufbereitung  anhand vorgegebener Kriterien Quellenangaben, Bild- und Persönlichkeitsrechte  beachten','',109,327,'2016-01-14 20:13:29',532,0,4),
	(15,'Eine begründete Auswahl aus synchronen und asynchronen elektronischen Kommunikationswegen treffen, Vor- und Nachteile benennen','<p>z.B. E-Mail, Messenger, Chat, Foren, Wikis, Blogs&hellip;; konkrete Anwendung s. TK 3</p>',109,328,'2016-01-14 20:13:29',532,0,1),
	(16,'Elektronische Kommunikationswege zum Austausch von Informationen angeleitet nutzen','<p>z.B. auch&nbsp; innerhalb digitaler Lernumgebungen</p>',109,328,'2016-01-14 20:13:29',532,0,2),
	(17,'Anhand von Leifragen Ablauf und Ergebnisse der Kommunikation kritisch reflektieren und angemessen darstellen','',109,328,'2016-01-14 20:13:29',532,0,3),
	(18,'Vorgegebene Lernsoftware, Internetdienste, Portalangebote, Podcasts, Wikis, Learning-Apps, Knowledge-Blogs, RSS-Feeds etc. zum Wissenserwerb und zur Wissenserweiterung für sich und andere nutzen','<p>z.B. Mobile Learning, Gamebased Learning, gesicherte Clouds einbeziehen</p>',109,328,'2016-01-14 20:13:29',532,0,4),
	(19,'Einstellungen zu Berechtigungen oder zur Privatsphäre festlegen und ändern','<p>Informationen in sozialen Netzwerken anzeigen oder verbergen, Schreibzugriff, Einladungen oder Anfragen annehmen und ablehnen</p>',109,329,'2016-01-14 20:13:29',532,0,1),
	(20,'Bei unerwünschten Informationen und Datenmissbrauch situationsgerecht handeln','<p>z.B. Newsletter, Werbemails abbestellen</p>',109,329,'2016-01-14 20:13:29',532,0,2),
	(21,'die virtuelle Identität präsentieren und  mitteilen (Identitätsmanagement)','',109,329,'2016-01-14 20:13:29',532,0,3),
	(22,'private Daten als schützenswert erkennen und behandeln','',109,329,'2016-01-14 20:13:29',532,0,4),
	(23,'beachten, dass private Daten zu unterschiedlichen Zwecken  erhoben, verarbeitet, weitergegeben werden, welche Folgen sich daraus ergeben können und sich entsprechend verhalten','<p>Tracking, Scoring, Profilbildung</p>',109,329,'2016-01-14 20:13:29',532,0,5),
	(24,'erkennen, welche Folgen sich aus der Veröffentlichung privater Daten ergeben können','',109,329,'2016-01-14 20:13:29',532,0,6),
	(25,'Mit Hilfestellungen Inhalte gliedern, nach ihrer Relevanz ordnen und sie mit eigenen Worten beschreiben','<p>z.B. Audioscript, Storyboard</p>',109,330,'2016-01-14 20:13:29',532,0,1),
	(26,'Verschiedene Gestaltungsvarianten persönlicher Mitteilungen erschließen und Wirkungsabsichten berücksichtigen','<p>z.B. Instant Messaging; Postings in Sozialen Netzwerken;&nbsp; s. TK1;</p>\r\n<p>s. TK1, TK2 &bdquo;Produzieren und Pr&auml;sentieren&ldquo;</p>',109,330,'2016-01-14 20:13:29',532,0,2),
	(27,'Rechtliche Grundlage der freien Meinungsäußerung definieren und dem eigenen Handeln zugrunde legen','<p>z.B. n&auml;here Betrachtung Art. 5 GG</p>',109,330,'2016-01-14 20:13:29',532,0,3),
	(28,'Einen respektvollen Umgang bei der digitalen Kommunikation miteinander pflegen (Medienethik)','<p>z.B. Netiquette, Chatiquette, ad&auml;quate Darstellungsformen beurteilen bzw. selbst w&auml;hlen; in angemessener Zeit reagieren</p>',109,331,'2016-01-14 20:13:29',532,0,1),
	(29,'Adressatengerechte Botschaften verfassen','<p>z.B. Mail-Adresse in passender Form, unangemessene K&uuml;rzel, Symbole; korrekte Rechtschreibung</p>',109,331,'2016-01-14 20:13:29',532,0,2),
	(30,'Kommunikationsanlässe und Verfasserabsichten erkennen und angemessen reagieren','',109,331,'2016-01-14 20:13:29',532,0,3),
	(31,'Planvoll an ein überschaubares, begleitetes  Produktionsvorhaben herangehen und gegebenenfalls eine digitale Veröffentlichung planen','<p>vorgegebene Arbeitsabl&auml;ufe und Umsetzungsschritte ber&uuml;cksichtigen und festhalten (siehe auch TK4), Rollenverteilung definieren,&nbsp; Inhalte und Dramaturgie skizzieren und festhalten (s.a. TK3 &bdquo;Kommunizieren und Kooperieren&ldquo;),&nbsp;</p>',109,332,'2016-01-14 20:13:29',532,0,1),
	(32,'Möglichkeiten und Grenzen der geplanten Produktion realistisch einschätzen und das eigene Vorgehen begründen','<p>z.B. Ressourcen pr&uuml;fen (Zeit, Personal, Technik)</p>',109,332,'2016-01-14 20:13:29',532,0,2),
	(33,'Die Planungsschritte einer überschaubaren Medienproduktion und -präsentation im vorgegebenen Rahmen festlegen und einhalten','<p>z.B. f&uuml;r ein digitales Portfolio</p>',109,333,'2016-01-14 20:13:29',532,0,1),
	(34,'Vorgegebene formale, ästhetische und informationstechnische Kriterien anwenden','',109,333,'2016-01-14 20:13:29',532,0,2),
	(35,'Einfache Medienelemente erstellen, bearbeiten und einbinden','<p>z.B. Bildbearbeitung, einfache, m&ouml;glichst lizenzfreie Audio- und Videobearbeitung;</p>\r\n<p>s. TK 4 &bdquo;Bedienen und Anwenden&ldquo;)</p>',109,333,'2016-01-14 20:13:29',532,0,3),
	(36,'Verschiedene Gestaltungsvarianten erschließen und ausprobieren, die Wirkungsabsichten der Auswahl begründen','',109,333,'2016-01-14 20:13:30',532,0,4),
	(37,'Inhalte ergänzend kommentieren, erläutern, veranschaulichen','<p>nicht 1:1 vorlesen; z.B. Projektion/Standbild/Still mit &bdquo;Live&ldquo;-Kommentierung, dynamischer Ablauf und interaktive Bild-Text-Pr&auml;sentation, z.B. mithilfe eines Interaktiven Whiteboards</p>',109,334,'2016-01-14 20:13:30',532,0,1),
	(38,'Zielgruppengerecht präsentieren und selbstständig Feedback geben, wahrnehmen und einholen','<p>z.B. Reaktionen aufnehmen, sachliche R&uuml;ckmeldung geben</p>',109,334,'2016-01-14 20:13:30',532,0,2),
	(39,'Rhetorische, mimische und gestische Gestaltungsmöglichkeiten einsetzen','',109,334,'2016-01-14 20:13:30',532,0,3),
	(40,'Die zum Produkt passende Veröffentlichungsform unter Anleitung auswählen und gemäß der Publikationsbestimmungen handeln','<p>z.B. neben externen Publikationswegen auch schuleigene M&ouml;glichkeiten, wie etwa eine Lernplattform oder die Homepage, nutzen (z.B. Audio -&gt; Podcasting, Film-/Videobeitrag &rarr; Online-Plattformen, &hellip;)</p>',109,335,'2016-01-14 20:13:30',532,0,1),
	(41,'Zwischen kostenpflichtigen und freien Lizenzierungsmodellen anhand vorgegebener Informationsquellen und Unterlagen unterscheiden können, sie passend auswählen und Bedingungen bzgl. Freigabe, Weitergabe und Reichweite beachten','<p>z.B. Open Content, CC-Lizenzen</p>',109,335,'2016-01-14 20:13:30',532,0,2),
	(42,'Einfluss und Bedeutung digitaler Medien und ihrer spezifischen Darstellungsformen im Alltag beschreiben','<p>insbesondere Massenmedien und cross-medial gestaltete Angebote</p>',109,336,'2016-01-14 20:13:30',532,0,1),
	(43,'Die eigene Medienbiografie reflektieren und beschreiben sowie Motive der Mediennutzung (auch der eigenen) benennen und reflektieren','',109,336,'2016-01-14 20:13:30',532,0,2),
	(44,'Stilmittel der Bild- und Filmsprache erkennen','<p>z.B. Einstellungsgr&ouml;&szlig;en, Kameraperspektiven, Tonebene &hellip;</p>',109,336,'2016-01-14 20:13:30',532,0,3),
	(45,'Veränderungen der Medienwelt analysieren und in Beziehung zu Schule, Berufs- und Lebenswelt setzen','<p>z.B. Wechselwirkungen von technischer und gesellschaftlicher Entwicklung</p>',109,336,'2016-01-14 20:13:30',532,0,4),
	(46,'Nach vorgegebenen Kriterien Manipulationsmechanismen beschreiben, kritisch bewerten und einschätzen','<p>z.B. Intentionen von Medienanbietern und &bdquo;Medienmachern&ldquo; bestimmter Medienformate, Web-Services, medialer Dienstleitungen</p>',109,337,'2016-01-14 20:13:30',532,0,1),
	(47,'Vor- und Leitbilder der Medien beschreiben, Stereotype erkennen, untersuchen und bewerten','<p>z.B. in Casting-Shows; Idole und Trivialmythen</p>',109,337,'2016-01-14 20:13:30',532,0,2),
	(48,'Inszenierungsformen erkennen und benennen','<p>Wie wird mir was pr&auml;sentiert? Z.B. Bildausschnitt, Perspektive</p>',109,337,'2016-01-14 20:13:30',532,0,3),
	(49,'Den eigenen Wechsel zwischen virtueller und realer Welt erkennen und den Einfluss die  eigene Person reflektieren und beschreiben','<p>z.B. auchSelbstinszenierung und deren Risiken</p>',109,338,'2016-01-14 20:13:30',532,0,1),
	(50,'Chancen und Risiken der rezeptiven und produktiven Mediennutzung einschätzen, beschreiben und bewerten','<p>z.B. hoher Verbreitungsgrad, versteckte Kosten, backtracking/zielgruppen- und nutzerorientierte Werbung, &bdquo;Datenkraken&ldquo;, In-App-K&auml;ufe</p>',109,338,'2016-01-14 20:13:30',532,0,2),
	(51,'Kritisch zu problematischen Inhalten und Darstellungen Stellung nehmen','<p>z.B. Extremismus, Pornographie, Gewalt (p&auml;dagogisch begleitet)</p>',109,338,'2016-01-14 20:13:30',532,0,3),
	(52,'Die wichtigsten Anlaufstellen und Beratungsangebote kennen und im Bedarfsfall nutzen ','',109,338,'2016-01-14 20:13:30',532,0,4),
	(53,'Informationen zu Technisierung, Kommerzialisierung und Virtualisierung des Alltags beschreiben und bewerten','<p>z.B. Schule/Beruf, Lebenswelt</p>',109,339,'2016-01-14 20:13:30',532,0,1),
	(54,'Veränderungen von Berufsbildern kennen und kritisch einschätzen','<p>z.B. Mechanisierung und Beschleunigung von Arbeitsabl&auml;ufen, IT-Anwendungen im Einzelhandel, Abbau von Arbeitspl&auml;tzen; Bezug zur Berufsorientierung</p>',109,339,'2016-01-14 20:13:30',532,0,2),
	(55,'Nach entsprechender Einführung Berufsbilder im Medienbereich kennen und beschreiben','<p>z.B. Arbeit von Online-Redaktionen, Medienberufe in Presse, Film und Fernsehen/H&ouml;rfunk; Bezug zur Berufsorientierung</p>',109,339,'2016-01-14 20:13:30',532,0,3),
	(56,'Die Möglichkeiten der demokratischen Teilhabe durch Medien einschätzen, deren Unüberschaubarkeit und Manipulationsmechanismen mit Blick auf die eigenen altersspezifischen Mediengewohnheiten angemessen einschätzen','',109,339,'2016-01-14 20:13:30',532,0,4),
	(57,'Kinoproduktionen und Filme als Teil kulturellen und gesellschaftlichen Lebens beschreiben und beurteilen','<p>z. B. transportierte Normen- und Wertesysteme, Distributionswege und Verwertungsformen, &nbsp;</p>',109,339,'2016-01-14 20:13:30',532,0,5),
	(58,'Mit Hilfestellung Systembestandteile und deren Funktionsweisen (Festplatte, Arbeitsspeicher, Prozessor, Laufwerke ...) kennen und benennen','',109,340,'2016-01-14 20:13:30',532,0,1),
	(59,'ein vorkonfiguriertes Betriebssystem verwenden (Standard und erweiterte Funktionen, z.B. sich im vorgegebenen Menü bewegen, Symbolleisten und Verzeichnisstrukturen kennen und der Arbeit zugrunde legen….)','',109,340,'2016-01-14 20:13:30',532,0,2),
	(60,'Browsereinstellungen kennen und nach Anleitung anpassen (Cookies, Cache, Favoriten, Verlauf, Pop Ups…)','',109,340,'2016-01-14 20:13:30',532,0,3),
	(61,'nach Vorgaben Installationen, Erweiterungen und Anpassungen vornehmen (z.B. Software installieren, Funktionalitäten nutzerorientiert einschränken, Dateiverwaltung anlegen und verwenden…)','',109,340,'2016-01-14 20:13:30',532,0,4),
	(62,'Daten in vorgegebenen Ordnerstrukturen verwalten','',109,340,'2016-01-14 20:13:30',532,0,5),
	(63,'sich in einem vorgegebenen Rahmen mit den Eigenschaften, Möglichkeiten und Grenzen mobiler und virtueller Datenspeicherung vertraut machen, diese anwenden und z.B. Daten übertragen und sichern','',109,340,'2016-01-14 20:13:30',532,0,6),
	(64,'unter Anleitung stationäre und mobile Endgeräte in ein Netzwerk einbinden (LAN und W-LAN) und Sicherheitsregeln beachten','',109,340,'2016-01-14 20:13:30',532,0,7),
	(65,'einfache Hardwareprobleme (Anschluss und Verkabelung, Papierstau etc.) erkennen und beheben','',109,340,'2016-01-14 20:13:30',532,0,8),
	(66,'Hilfsfunktionen und Vorlagen verwenden und gestalten (z.B Tastenkombinationen, Assistenten, Layoutvorlagen…)','',109,341,'2016-01-14 20:13:30',532,0,1),
	(67,'Formale Einstellungen vornehmen (Rahmen, Absätze, Seitenränder, Umbrüche, Einzüge und Spalten, Gliederung, Nummerierung  …)','',109,341,'2016-01-14 20:13:30',532,0,2),
	(68,'Tabellen in Dokumente einfügen','',109,341,'2016-01-14 20:13:30',532,0,3),
	(69,'Serienbriefe aus (selbst erstellten) Datenquellen erzeugen (9/10)','',109,341,'2016-01-14 20:13:30',532,0,4),
	(70,'normierte Schreiben (DIN 5008) erstellen','',109,341,'2016-01-14 20:13:30',532,0,5),
	(71,'benutzerdefinierte Animationen in eine Präsentation einbinden ','',109,341,'2016-01-14 20:13:30',532,0,6),
	(72,'selbst erstellte Objekte in Präsentationen einbinden','',109,341,'2016-01-14 20:13:30',532,0,7),
	(73,'Formatierung eines Tabellenblatts vornehmen und den Druckbereich einstellen','',109,342,'2016-01-14 20:13:30',532,0,1),
	(74,'Einbindung von (selbst erstellten) Objekten vornehmen ','',109,342,'2016-01-14 20:13:30',532,0,2),
	(75,'den relativen und absoluten Zellbezug herstellen ','',109,342,'2016-01-14 20:13:30',532,0,3),
	(76,'mathematische Grundfunktionen beherrschen (und diese anwenden)','',109,342,'2016-01-14 20:13:30',532,0,4),
	(77,'Basisfunktionen (erweiterte Funktionen) digitaler Aufzeichnungs- und Wiedergabegeräte beherrschen (z.B. Tablets, Foto- und Videokamera, Voice-Recorder, Handys und Smartphones als multimediale Werkzeuge im definierten unterrichtlichen Kontext kompetent einsetzen...)','',109,342,'2016-01-14 20:13:30',532,0,5),
	(78,'mit (freier) Text-, Bild-, Ton-, Videobearbeitungssoftware arbeiten und einfache Präsentationen, Mindmaps und andere mediale Produkte erstellen','',109,342,'2016-01-14 20:13:30',532,0,6),
	(3870,'<p>Buchstaben schreiben</p>\n','',203,1504,'2016-11-16 13:37:01',532,0,1),
	(3871,'<p>Buchstaben lesen</p>\n','',203,1504,'2016-11-16 13:37:06',532,0,2),
	(3872,'<p>Buchstaben aussprechen</p>\n','',203,1504,'2016-11-16 13:37:13',532,0,3),
	(3873,'<p>Alphabet kennen</p>\n','',203,1504,'2016-11-16 13:37:19',532,0,4),
	(3874,'<p>Individuelle Handschrift</p>\n','',203,1504,'2016-11-16 13:37:28',532,0,5),
	(3875,'<p>Regeln des ordentlichen, sauberen und gegliederten Schreibens anwenden</p>\n','',203,1504,'2016-11-16 13:37:39',532,0,6),
	(3883,'<p>Ich kann vertraute Wörter und ganz einfache Sätze verstehen.</p>\n','',203,1505,'2016-11-16 13:40:13',532,0,1),
	(3884,'<p>Ich kann alltägliche Äußerungen, die sich auf einfache und konkrete alltägliche Bedürfnisse beziehen, verstehen, wenn langsam, deutlich und mit Wiederholungen gesprochen wird.</p>\n','',203,1505,'2016-11-16 13:40:22',532,0,2),
	(3886,'<p>Ich kann einem Gespräch folgen, wenn sehr langsam und deutlich gesprochen wird und wenn lange Pausen es mir ermöglichen, das Gesagte zu verstehen.</p>\n','',203,1505,'2016-11-16 13:40:29',532,0,3),
	(3888,'<p>Ich kann Fragen und Anweisungen verstehen und kurzen, einfachen Weisungen folgen.</p>\n','',203,1505,'2016-11-16 13:40:40',532,0,4),
	(3889,'<p>Ich kann Zahlen, Preise und Zeitangaben verstehen.</p>\n','',203,1505,'2016-11-16 13:40:47',532,0,5),
	(3891,'<p>Ich kann Gefühle und Reaktionen beschreiben.</p>\n','',203,1506,'2016-11-16 13:41:17',532,0,1),
	(3893,'<p>Ich verstehe das Wesentliche von kurzen, klaren und einfachen Mitteilungen und Durchsagen.</p>\n','',203,1506,'2016-11-16 13:41:26',532,0,2),
	(3897,'<p>Ich kann unkomplizierte Sachinformationen über Themen aus dem persönlichen und gesellschaftlichen Bereich sowie dem Bildungsbereich und Einzelinformationen verstehen, wenn deutlich und in Standardsprache gesprochen wird.</p>\n','',203,1507,'2016-11-16 13:42:00',532,0,1),
	(3898,'<p>Ich kann längeren Redebeiträgen in Diskussionen in den Hauptpunkten folgen.</p>\n','',203,1507,'2016-11-16 13:42:07',532,0,2),
	(3900,'<p>Ich kann den Informationsgehalt kurzer Vorträge sowie medialer Sendungen vom persönlichem Interesse verstehen und wiedergeben.</p>\n','',203,1507,'2016-11-16 13:42:17',532,0,3),
	(3901,'<p>Ich kann über Erfahrungen und Erlebnisse berichten.</p>\n','',203,1507,'2016-11-16 13:42:25',532,0,4),
	(3902,'<p>Ich kann einzelne Sätze und die gebräuchlichsten Wö rter verstehen, wenn es um für ihn/sie wichtige Dinge geht (z.B. sehr einfache Inform ationen zur Person und zur Familie, Einkaufen, Arbeit, nähere Umgebung).</p>\n','',203,1507,'2016-11-16 13:42:35',532,0,5),
	(3903,'<p>Ich kann im direkten Kontakt und in den Medien Gespräche, längere Redebeiträge und Vorträge, denen er im privaten, offiziellen, beruflichen Bereich sowie in der Ausbildung begegnet, verstehen, wenn Standardsprache gesprochen wird.</p>\n','',203,1508,'2016-11-16 13:43:18',532,0,1),
	(3904,'<p>Ich kann auch komplexer Argumentation folgen, wenn das Thema vertraut ist.</p>\n','',203,1508,'2016-11-16 13:43:28',532,0,2),
	(3905,'<p>Ich kann mich auf einfache Art verständigen.</p>\n','',203,1509,'2016-11-16 13:46:40',532,0,1),
	(3906,'<p>Ich kann einfache Fragen stellen und beantworten.</p>\n','',203,1509,'2016-11-16 13:46:46',532,0,2),
	(3907,'<p>Ich kann einfache Wendungen und Sätze gebrauchen, um Leute, die ich kenne, zu beschreiben und um zu beschreiben, wo ich wohne.</p>\n','',203,1509,'2016-11-16 13:46:56',532,0,3),
	(3908,'<p>Ich kann Fragen zur Person stellen und auf entsprechende Fragen Antwort geben. Kann sich auf einfache Art verständigen, doch ist die Kommunikation völlig davon abhängig, dass etwas langsamer wiederholt, umformuliert oder korrigiert wird.</p>\n','',203,1509,'2016-11-16 13:47:07',532,0,4),
	(3909,'<p>Ich kann ganz kurze, isolierte, weitgehend vorgefertigte Äußerungen benutzen; braucht viele Pausen, um nach Ausdrücken zu suchen, weniger vertraute Wörter zu artikulieren oder um Verständigungsprobleme zu beheben.</p>\n','',203,1509,'2016-11-16 13:47:21',532,0,5),
	(3910,'<p>Ich habe ein sehr begrenztes Repertoire an Wörtern und Wendungen, die sich auf Informationen zur Person und einzelne konkrete Situationen beziehen.</p>\n','',203,1509,'2016-11-16 13:47:32',532,0,6),
	(3911,'<p>Ich kann mich in einfachen, routinemäßigen Situationen verständigen, in denen es um einen einfachen und direkten Austausch von Informat ionen über vertraute und geläufige Dinge geht.</p>\n','',203,1511,'2016-11-16 13:48:01',532,0,1),
	(3912,'<p>Ich kann mit einfachen Mitteln die eigene Herkunft und Ausbildung, die direkte Umgebung und Dinge im Zusammenhang mit unmittelbaren Bedürfnissen beschreiben.</p>\n','',203,1511,'2016-11-16 13:48:13',532,0,2),
	(3913,'<p>Ich kann ein sehr kurzes Kontaktgespräch führen, versteht aber normalerweise nicht genug, um selbst das Gespräch in Gang zu halten.</p>\n','',203,1511,'2016-11-16 13:48:21',532,0,3),
	(3914,'<p>Ich kann mit einer Reihe von Sätzen und mit einfachen Mitteln z.B. seine/ihre Familie, andere Leute, seine/ihre Wohnsituation, seine/ihre Ausbildung und seine/ihre gegenwärtige oder letzt berufliche Tätigkeit beschreiben.</p>\n','',203,1511,'2016-11-16 13:49:00',532,0,4),
	(3919,'<p>Ich kann relativ flüssig und ohne Vorbereitung ein Gespräch mit Muttersprachlern zu vertrauten Themen beginnen, in Gang halten und beenden.</p>\n','',203,1512,'2016-11-16 13:50:18',532,0,1),
	(3920,'<p>Ich kann an formellen Diskussionen zu Themen des alltäglichen, beruflichen und öffentlichen Lebensbereiches sowie aus dem eigenen Interessen- und Fachgebiet teilnehmen, Sachinformationen austauschen und Lösungen für praktische Probleme diskutieren.</p>\n','',203,1512,'2016-11-16 13:50:27',532,0,2),
	(3921,'<p>Über eigene Erfahrungen kann ich detailliert berichten und Gefühle und Reaktionen beschreiben.</p>\n','',203,1512,'2016-11-16 13:50:37',532,0,3),
	(3922,'<p>Meine Aussprache ist gut verständlich.</p>\n','',203,1512,'2016-11-16 13:50:44',532,0,4),
	(3923,'<p>Ich kann mich spontan und fließend verständigen, so dass ein normales Gespräch mit Muttersprachlern ohne größere Anstrengung gut möglich ist.</p>\n','',203,1513,'2016-11-16 13:51:11',532,0,1),
	(3924,'<p>Ich kann mich zu einem breiten Themenspektrum klar und deutlich ausdrücken, Standpunkte erläutern und begründen, Vor- und Nachteile angeben und sich an Diskussionen und längeren Gesprächen über die meisten Themen von allgemeinem Interesse aktiv beteiligen und über Themen des eigenen Fachgebietes sprechen.</p>\n','',203,1513,'2016-11-16 13:51:28',532,0,2),
	(3925,'<p>Ich kann meine Gesprächspartner verstehen, wenn Standardsprache gesprochen wird.</p>\n','',203,1513,'2016-11-16 13:51:37',532,0,3),
	(3926,'<p>Ich verfüge über eine klare, natürliche Aussprache.</p>\n','',203,1513,'2016-11-16 13:51:59',532,0,4),
	(3927,'<p>Ich kann Sätze und häufig gebrauchte Ausdrücke verstehen, die mit Bereichen von ganz unmittelbarer Bedeutung zusammenhängen (z.B. Informationen zur Person und zur Familie, Einkaufen, Arbeit, nähere Umgebung).</p>\n','',203,1513,'2016-11-16 13:52:12',532,0,5),
	(3928,'<p>Ich kann kurze einfach Texte schreiben/ etwas auf Formularen eintragen.</p>\n','',203,1514,'2016-11-16 13:52:49',532,0,1),
	(3929,'<p>Ich kann einfache Mitteilungen an Freunde schreiben.</p>\n','',203,1514,'2016-11-16 13:52:55',532,0,2),
	(3930,'<p>Ich kann beschreiben, wo ich wohne.</p>\n','',203,1514,'2016-11-16 13:53:03',532,0,3),
	(3931,'<p>Ich kann auf Formularen meine persönlichen Daten eintragen.</p>\n','',203,1514,'2016-11-16 13:53:12',532,0,4),
	(3932,'<p>Ich kann einzelne, einfache Ausdrücke und Sätze schreiben.</p>\n','',203,1514,'2016-11-16 13:53:19',532,0,5),
	(3933,'<p>Ich kann eine kurze, einfache Postkarte schreiben.</p>\n','',203,1514,'2016-11-16 13:53:26',532,0,6),
	(3934,'<p>Ich kann mit Hilfe eines Wörterbuches kurze Briefe und Mitteilungen schreiben.</p>\n','',203,1514,'2016-11-16 13:53:33',532,0,7),
	(3935,'<p>Ich kann kurze, einfache Notizen und Mitteilungen schreiben.</p>\n','',203,1516,'2016-11-16 13:53:59',532,0,1),
	(3936,'<p>Ich kann einen ganz einfachen persönlichen Brief schreiben, z.B. um sich für etwas zu bedanken.</p>\n','',203,1516,'2016-11-16 13:54:05',532,0,2),
	(3937,'<p>Ich kann unkomplizierte, zusammenhängende Texte zu Themen von allgemeinem Interesse und zu Themen aus seinen Interessengebieten schreiben.</p>\n','',203,1517,'2016-11-16 13:54:35',532,0,1),
	(3938,'<p>Ich kann Textteile linear verbinden.</p>\n','',203,1517,'2016-11-16 13:54:42',532,0,2),
	(3939,'<p>Ich kann Sachinformationen darstellen.</p>\n','',203,1517,'2016-11-16 13:54:48',532,0,3),
	(3940,'<p>Ich kann und Vor- und Nachteile darlegen und Stellung dazu beziehen.</p>\n','',203,1517,'2016-11-16 13:54:57',532,0,4),
	(3941,'<p>Ich kann über eine Vielzahl von Themen aus seinen Interessengebieten klare und detaillierte Texte schreiben.</p>\n','',203,1518,'2016-11-16 13:55:23',532,0,1),
	(3942,'<p>Ich kann in einem Aufsatz oder Bericht Informationen zu einem wissenschaftsorientierten Kontext zusammenhängend und strukturiert wiedergeben, Argumente darlegen, etwas systematisch erörtern und Problemlösungen gegeneinander abwägen.</p>\n','',203,1518,'2016-11-16 13:55:31',532,0,2),
	(3943,'<p>Ich kann Informationen und Argumente aus verschiedenen Quellen zusammenführen und dazu Stellung nehmen.</p>\n','',203,1518,'2016-11-16 13:55:42',532,0,3),
	(3944,'<p>Ich kann Wörter oder Wortgruppen durch einfache Konnektoren wie \"und\" oder \"dann\" verbinden.</p>\n','',203,1519,'2016-11-16 13:56:16',532,0,1),
	(3945,'<p>Ich zeige nur eine begrenzte Beherrschung von einigen wenigen einfachen grammatischen Strukturen und Satzmustern in einem auswendig gelernten Repertoire.</p>\n','',203,1519,'2016-11-16 13:56:25',532,0,2),
	(3946,'<p>Ich Ich kann ein sehr kurzes Kontaktgespräch führen, verstehe aber normalerweise nicht genug, um selbst das Gespräch in Gang zu halten.</p>\n','',203,1520,'2016-11-16 13:56:49',532,0,1),
	(3947,'<p>Ich verfüge über eine gute Beherrschung der grammatischen Strukturen und des Grundwortschatzes im mündlichen und schriftlichen Sprachgebrauch.</p>\n','',203,1521,'2016-11-16 13:57:14',532,0,1),
	(3948,'<p>Ich erkenne und verwende grammatische Regeln und kann nach grammatischen Prinzipien Ausdrücke und Sätze ausreichend korrekt produzieren.</p>\n','',203,1521,'2016-11-16 13:57:24',532,0,2),
	(3949,'<p>Ich erkenne und verwende grammatische Regeln und kann nach grammatischen Prinzipien Ausdrücke und Sätze korrekt produzieren.</p>\n','',203,1522,'2016-11-16 13:57:49',532,0,1),
	(3950,'<p>Ich beherrsche die Grammatik im mündlichen und schriftlichen Sprachgebrauch.</p>\n','',203,1522,'2016-11-16 13:58:02',532,0,2),
	(3951,'<p>Ich kann die Grundaussage eines einfachen Informationstextes und kurzer einfacher Beschreibungen verstehen, insbesondere wenn diese Bilder enthalten, die den Text erklären.</p>\n','',203,1523,'2016-11-16 13:58:35',532,0,1),
	(3952,'<p>Ich kann sehr kurze, einfache Texte mit bekannten Namen, Wörtern und grundlegenden Redewendungen verstehen, wenn ich zum Beispiel Teile des Textes noch einmal lese.</p>\n','',203,1523,'2016-11-16 13:58:44',532,0,2),
	(3953,'<p>Ich kann kurzen, einfach geschriebenen Anleitungen folgen, insbesondere wenn sie Bilder enthalten.</p>\n','',203,1523,'2016-11-16 13:58:51',532,0,3),
	(3954,'<p>Ich kann bekannte Namen, Wörter und sehr einfache Redewendungen in einfachen Mitteilungen in den häufigsten Alltagssituationen erkennen.</p>\n','',203,1523,'2016-11-16 13:59:04',532,0,4),
	(3955,'<p>Ich kann kurze, einfache Mitteilungen, z. B. auf Postkarten, verstehen.</p>\n','',203,1523,'2016-11-16 13:59:12',532,0,5),
	(3956,'<p>Ich kann einzelne vertraute Namen, Wörter und ganz einfache Sätze verstehen, z. B. auf Schildern, Plakaten oder in Katalogen.</p>\n','',203,1523,'2016-11-16 13:59:24',532,0,6),
	(3957,'<p>Ich kann ganz kurze, einfache Texte lesen.</p>\n','',203,1524,'2016-11-16 13:59:47',532,0,1),
	(3958,'<p>Ich kann in einfachen Alltagstexten (z.B. Anzeigen, Prospekt en, Speisekarten oder Fahrplänen) konkrete, vorhersehbare Informationen auffinden und k ann kurze, einfache persönliche Briefe verstehen.</p>\n','',203,1524,'2016-11-16 14:00:00',532,0,2),
	(3959,'<p>Ich kann unkomplizierte Sachtexte zu konkreten und abstrakten Themen in den Hauptpunkten und in Einzelinformationen verstehen sowie die Textstruktur und die Textgliederung erkennen.</p>\n','',203,1525,'2016-11-16 14:00:27',532,0,1),
	(3960,'<p>Ich kann aus längeren, klar strukturierten Texten zu Themen des alltäglichen, beruflichen und gesellschaftlichen Lebensbereiches sowie aus dem eigenen Interessen- und Fachgebiet schnell wichtige Einzelinformationen zusammentragen, bestimmte Aufgaben dazu lösen, Kernaussagen zuordnen und treffende Überschriften formulieren.</p>\n','',203,1525,'2016-11-16 14:00:37',532,0,2),
	(3961,'<p>Ich kann Hauptinhalte komplexer Texte zu konkreten und abstrakten Themen verstehen, Fragen dazu beantworten und die Textstruktur und die Textgliederung erkennen.</p>\n','',203,1526,'2016-11-16 14:01:04',532,0,1),
	(3962,'<p>Ich kann aus längeren und komplexen Texten schnell wichtige Einzelinformationen finden und den Inhalt erfassen.</p>\n','',203,1526,'2016-11-16 14:01:11',532,0,2),
	(3963,'<p>Teilnahme am Regelunterricht in der Klasse</p>\n','',203,1527,'2016-11-16 14:01:35',532,0,1),
	(3964,'<p>Teilnahme an AGs</p>\n','',203,1527,'2016-11-16 14:01:40',532,0,2),
	(3976,'<p>K1: Mathematische Argumentationen entwickeln </p>\n','',202,1528,'2016-11-16 14:18:40',532,0,1),
	(3977,'<p>K3: Den Bereich oder die Situation, die modelliert werden soll, in mathematische Begriffe, Strukturen und Relationen übersetzen </p>\n','',202,1528,'2016-11-16 14:19:11',532,0,2),
	(3978,'<p>K3: Ergebnisse in dem entsprechenden Bereich oder der entsprechenden Situation interpretieren und prüfen </p>\n','',202,1528,'2016-11-16 14:19:32',532,0,3),
	(3979,'<p>K6: Die Fachsprache adressatengerecht verwenden </p>\n','',202,1528,'2016-11-16 14:19:50',532,0,4),
	(3980,'<p>K1: Mathematische Argumentationen entwickeln </p>\n','',202,1528,'2016-11-16 14:20:07',532,0,5),
	(3981,'<p>K6: Überlegungen, Lösungswege und Ergebnisse dokumentieren, verständlich darstellen und präsentieren, auch unter Nutzung geeigneter Medien </p>\n','',202,1528,'2016-11-16 14:20:36',532,0,6),
	(3982,'<p>K4: Beziehungen zwischen Darstellungsformen erkennen und zwischen ihnen wechseln </p>\n','',202,1529,'2016-11-16 14:24:52',532,0,1),
	(3983,'<p>K5: Mathematische Werkzeuge sinnvoll und verständig einsetzen </p>\n','',202,1529,'2016-11-16 14:25:05',532,0,2),
	(4106,'<p style=\"text-align:center;\">Männlich</p>\n\n<p style=\"text-align:center;\">ذكر</p>\n\n<p style=\"text-align:center;\">male</p>\n','',205,1573,'2016-11-16 20:50:24',532,0,1),
	(4107,'<p style=\"text-align:center;\">Weiblich</p>\n\n<p style=\"text-align:center;\">أنثى</p>\n\n<p style=\"text-align:center;\">female</p>\n','',205,1573,'2016-11-16 20:50:45',532,0,2),
	(4108,'<p style=\"text-align:center;\">1998</p>\n','',205,1574,'2016-11-16 20:51:36',532,0,1),
	(4109,'<p style=\"text-align:center;\">1999</p>\n','',205,1574,'2016-11-16 20:51:47',532,0,2),
	(4110,'<p style=\"text-align:center;\">2000</p>\n','',205,1574,'2016-11-16 20:51:59',532,0,3),
	(4111,'<p style=\"text-align:center;\">2001</p>\n','',205,1574,'2016-11-16 20:52:12',532,0,4),
	(4112,'<p style=\"text-align:center;\">2002</p>\n','',205,1574,'2016-11-16 20:52:22',532,0,5),
	(4113,'<p style=\"text-align:center;\">2003</p>\n','',205,1574,'2016-11-16 20:52:30',532,0,6),
	(4114,'<p style=\"text-align:center;\">2004</p>\n','',205,1574,'2016-11-16 20:52:40',532,0,7),
	(4115,'<p style=\"text-align:center;\">2005</p>\n','',205,1574,'2016-11-16 20:52:49',532,0,8),
	(4116,'<p style=\"text-align:center;\">2016</p>\n','',205,1575,'2016-11-16 20:53:37',532,0,1),
	(4117,'<p style=\"text-align:center;\">2015</p>\n','',205,1575,'2016-11-16 20:53:48',532,0,2),
	(4118,'<p style=\"text-align:center;\">2014</p>\n','',205,1575,'2016-11-16 20:53:57',532,0,3),
	(4119,'<p style=\"text-align:center;\">2013</p>\n','',205,1575,'2016-11-16 20:54:06',532,0,4),
	(4120,'<p>Syrien</p>\n','',205,1576,'2016-11-16 21:04:47',532,0,1),
	(4121,'<p>Irak (Nordirak)</p>\n','',205,1576,'2016-11-16 21:04:56',532,0,2),
	(4122,'<p>Irak</p>\n','',205,1576,'2016-11-16 21:05:02',532,0,3),
	(4123,'<p>Afghanistan</p>\n','',205,1576,'2016-11-16 21:05:10',532,0,4),
	(4124,'<p>Eritrea</p>\n','',205,1576,'2016-11-16 21:05:18',532,0,5),
	(4125,'<p>Armenien</p>\n','',205,1576,'2016-11-16 21:05:26',532,0,6),
	(4126,'<p>Pakistan</p>\n','',205,1576,'2016-11-16 21:05:33',532,0,7),
	(4127,'<p>Türkei</p>\n','',205,1576,'2016-11-16 21:05:56',532,0,8),
	(4128,'<p>Iran</p>\n','',205,1576,'2016-11-16 21:06:03',532,0,9),
	(4129,'<p>Tschetschenien</p>\n','',205,1576,'2016-11-16 21:06:09',532,0,10),
	(4130,'<p>Bulgarien</p>\n','',205,1576,'2016-11-16 21:06:15',532,0,11),
	(4131,'<p>Kosovo</p>\n','',205,1576,'2016-11-16 21:06:22',532,0,12),
	(4132,'<p>Mutter</p>\n','',205,1577,'2016-11-16 21:06:57',532,0,1),
	(4133,'<p>Vater</p>\n','',205,1577,'2016-11-16 21:07:02',532,0,2),
	(4134,'<p>Familie</p>\n','',205,1577,'2016-11-16 21:07:09',532,0,3),
	(4135,'<p>Geschwister</p>\n','',205,1577,'2016-11-16 21:07:15',532,0,4),
	(4136,'<p>Allein</p>\n','',205,1577,'2016-11-16 21:07:21',532,0,5),
	(4137,'<p>Cousine</p>\n','',205,1577,'2016-11-16 21:07:26',532,0,6),
	(4138,'<p>Cousin</p>\n','',205,1577,'2016-11-16 21:07:32',532,0,7),
	(4139,'<p>Arabisch - Syrien</p>\n','',205,1578,'2016-11-16 21:08:04',532,0,1),
	(4140,'<p>Arabisch - Irak</p>\n','',205,1578,'2016-11-16 21:08:26',532,0,2),
	(4141,'<p>Arabisch - Ägypten</p>\n','',205,1578,'2016-11-16 21:08:33',532,0,3),
	(4142,'<p>Arabisch - andere Länder</p>\n','',205,1578,'2016-11-16 21:08:40',532,0,4),
	(4143,'<p>Urdu</p>\n','',205,1578,'2016-11-16 21:08:46',532,0,5),
	(4144,'<p>Dari</p>\n','',205,1578,'2016-11-16 21:08:53',532,0,6),
	(4145,'<p>Persisch</p>\n','',205,1578,'2016-11-16 21:08:59',532,0,7),
	(4146,'<p>Armenisch</p>\n','',205,1578,'2016-11-16 21:09:05',532,0,8),
	(4147,'<p>Türkisch</p>\n','',205,1578,'2016-11-16 21:09:12',532,0,9),
	(4148,'<p>Kurdisch - Irak</p>\n','',205,1578,'2016-11-16 21:09:19',532,0,10),
	(4149,'<p>Kurdisch - Syrien</p>\n','',205,1578,'2016-11-16 21:09:28',532,0,11),
	(4150,'<p>Italienisch</p>\n','',205,1578,'2016-11-16 21:09:36',532,0,12),
	(4151,'<p>Russisch</p>\n','',205,1578,'2016-11-16 21:09:43',532,0,13),
	(4152,'<p>Albanisch</p>\n','',205,1579,'2016-11-16 21:09:54',532,0,1),
	(4153,'<p>Arabisch</p>\n','',205,1580,'2016-11-16 21:10:21',532,0,1),
	(4154,'<p>Englisch</p>\n','',205,1580,'2016-11-16 21:10:26',532,0,2),
	(4155,'<p>Französisch</p>\n','',205,1580,'2016-11-16 21:10:32',532,0,3),
	(4156,'<p>Italienisch</p>\n','',205,1580,'2016-11-16 21:10:40',532,0,4),
	(4157,'<p>Türkisch</p>\n','',205,1580,'2016-11-16 21:10:46',532,0,5),
	(4158,'<p>Grundschule 1 Jahr</p>\n','',205,1581,'2016-11-16 21:11:10',532,0,1),
	(4159,'<p>Grundschule 2 Jahre</p>\n','',205,1581,'2016-11-16 21:11:17',532,0,2),
	(4160,'<p>Grundschule 3 Jahre</p>\n','',205,1581,'2016-11-16 21:11:24',532,0,3),
	(4161,'<p>Grundschule 4 Jahre</p>\n','',205,1581,'2016-11-16 21:11:32',532,0,4),
	(4162,'<p>Sek 1 - 1 Jahr</p>\n','',205,1581,'2016-11-16 21:11:38',532,0,5),
	(4163,'<p>Sek 1 - 2 Jahre</p>\n','',205,1581,'2016-11-16 21:11:44',532,0,6),
	(4164,'<p>Sek 1 - 3 Jahre</p>\n','',205,1581,'2016-11-16 21:11:53',532,0,7),
	(4165,'<p>Sek 1 - 4 Jahre</p>\n','',205,1581,'2016-11-16 21:11:59',532,0,8),
	(4166,'<p>Sek 1 - 5 Jahre</p>\n','',205,1581,'2016-11-16 21:12:08',532,0,9),
	(4167,'<p>Sek 1 - 6 Jahre</p>\n','',205,1581,'2016-11-16 21:12:15',532,0,10),
	(4168,'<p>Sek 2 - 1 Jahr</p>\n','',205,1581,'2016-11-16 21:12:23',532,0,11),
	(4169,'<p>Sek 2 - 2 Jahre</p>\n','',205,1581,'2016-11-16 21:12:30',532,0,12),
	(4170,'<p>Lateinisch</p>\n','',205,1582,'2016-11-16 21:13:05',532,0,1),
	(4171,'<p>Arabisch</p>\n','',205,1582,'2016-11-16 21:13:10',532,0,2),
	(4172,'<p>Kyrillisch</p>\n','',205,1582,'2016-11-16 21:13:17',532,0,3),
	(4173,'<p>Armenisch</p>\n','',205,1582,'2016-11-16 21:13:24',532,0,4);

/*!40000 ALTER TABLE `enablingObjectives` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event` varchar(2048) DEFAULT NULL,
  `description` text,
  `course_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `repeat_id` int(11) DEFAULT NULL,
  `context_id` int(11) unsigned NOT NULL DEFAULT '1',
  `timestart` timestamp NULL DEFAULT NULL,
  `timeend` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '0 = disabled, 1 = active',
  `creator_id` int(11) unsigned NOT NULL,
  `sequence` smallint(11) DEFAULT NULL,
  `creation_time` timestamp NULL DEFAULT NULL,
  `reminder_interval` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ev_creator` (`creator_id`),
  KEY `rel_ev_context` (`context_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle event_subscriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_subscriptions`;

CREATE TABLE `event_subscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `course_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NULL DEFAULT NULL,
  `event_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_es_course` (`course_id`),
  KEY `rel_es_group` (`group_id`),
  KEY `rel_es_use` (`user_id`),
  KEY `rel_es_event` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle file_context
# ------------------------------------------------------------

DROP TABLE IF EXISTS `file_context`;

CREATE TABLE `file_context` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `context` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `file_context` WRITE;
/*!40000 ALTER TABLE `file_context` DISABLE KEYS */;

INSERT INTO `file_context` (`id`, `context`, `description`)
VALUES
	(1,'curriculum','Globale Dateien'),
	(2,'institution','Dateien meiner Instution(en)'),
	(3,'group','Dateien meiner Gruppe(n)'),
	(4,'user','Meine Dateien');

/*!40000 ALTER TABLE `file_context` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle file_license
# ------------------------------------------------------------

DROP TABLE IF EXISTS `file_license`;

CREATE TABLE `file_license` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `license` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `file_license` WRITE;
/*!40000 ALTER TABLE `file_license` DISABLE KEYS */;

INSERT INTO `file_license` (`id`, `license`)
VALUES
	(1,'Sonstige'),
	(2,'Alle Rechte vorbehalten'),
	(3,'Public Domain'),
	(4,'CC'),
	(5,'CC - keine Bearbeitung'),
	(6,'CC - keine kommerzielle Nutzung - keine Bearbeitung'),
	(7,'CC - keine kommerzielle Nutzung'),
	(8,'CC - keine kommerzielle Nutzung - Weitergabe unter gleichen Bedingungen'),
	(9,'CC - Weitergabe unter gleichen Bedingungen');

/*!40000 ALTER TABLE `file_license` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle file_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `file_token`;

CREATE TABLE `file_token` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(10) unsigned NOT NULL,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ft` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `context_id` int(10) unsigned DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `license` int(11) unsigned NOT NULL,
  `creator_id` int(10) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(6) DEFAULT NULL COMMENT 'Dateierweiterung kann 5 Zeichen + Punkt lang sein',
  `cur_id` int(11) unsigned DEFAULT NULL,
  `ena_id` int(11) unsigned DEFAULT NULL,
  `ter_id` int(11) unsigned DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `title` text,
  `file_context` int(11) unsigned NOT NULL DEFAULT '1',
  `hits` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;

INSERT INTO `files` (`id`, `context_id`, `path`, `filename`, `author`, `license`, `creator_id`, `creation_time`, `type`, `cur_id`, `ena_id`, `ter_id`, `description`, `title`, `file_context`, `hits`)
VALUES
	(0,3,'','noprofile.jpg','Joachim Dieterich',2,532,'2015-11-11 12:55:22','.jpg',0,0,0,'Platzhalter','Standard',1,0),
	(122,5,'','Colouring_pencils__MichaelMaggs_CC-BY-SA_3.0.jpg','',2,532,'2012-12-30 22:04:34','.png',0,0,0,NULL,'Kunst',1,0),
	(123,5,'','PianoKeyboard4_CC0.jpg','',2,532,'2012-12-30 22:08:06','.jpg',0,0,0,NULL,'Musik',1,0),
	(124,5,'','Informatik.png','',2,532,'2012-12-30 22:28:39','.png',0,0,0,NULL,'Informatik',1,0),
	(125,5,'','Calculator.jpg','',2,532,'2012-12-30 22:42:18','.jpg',0,0,0,NULL,'Mathematik',1,0),
	(126,5,'','Informatik.png','Joachim Dieterich',4,532,'2012-12-30 22:43:55','.png',0,0,0,NULL,'Informatik',1,0),
	(127,5,'','Reichstagsgebäude_-_Westansicht.jpg','',2,532,'2012-12-30 22:45:23','.jpg',0,0,0,NULL,'Deutsch',1,0),
	(128,5,'','Dorogobuj_Globe.JPG','',2,532,'2012-12-30 22:46:42','.jpg',0,0,0,NULL,'Geografie',1,0),
	(129,5,'','column-head-1696163_1920.jpg','',2,532,'2012-12-30 22:47:45','.jpg',0,0,0,NULL,'Geschichte',1,0),
	(130,5,'','test-214185_1280.jpg','',2,532,'2012-12-30 22:49:21','.jpg',0,0,0,NULL,'Chemie',1,0),
	(131,5,'','gears-1666499_1920.jpg','',2,532,'2012-12-30 22:49:53','.jpg',0,0,0,NULL,'Physik',1,0),
	(132,5,'','Dna-163466.jpg','',2,532,'2012-12-30 22:50:18','.jpg',0,0,0,NULL,'Biologie',1,0),
	(391,6,'/','RP_HKS__MBWJK__PLI_cmyk.jpg','Joachim Dieterich',2,532,'2015-05-18 19:50:48','.jpg',0,0,0,'PL Logo','PL Logo',1,0),
	(392,6,'/','zertlogo.png','Joachim Dieterich',2,532,'2015-05-18 19:52:51','.png',0,0,0,'logo','zertifikat logo',1,0),
	(393,6,'/','Badge01ortho.png','Joachim Dieterich',2,532,'2015-05-18 19:55:31','.png',0,0,0,'b1','B1',1,0),
	(394,6,'/','Badge02ortho.png','Joachim Dieterich',2,532,'2015-05-18 19:55:52','.png',0,0,0,'b2','b2',1,0),
	(395,6,'/','Badge03ortho.png','Joachim Dieterich',2,532,'2015-05-18 19:56:12','.png',0,0,0,'b3','b3',1,0),
	(396,6,'/','Badge04ortho.png','Joachim Dieterich',2,532,'2015-05-18 19:56:30','.png',0,0,0,'b4','b4',1,0),
	(397,6,'/','Badge05ortho.png','Joachim Dieterich',2,532,'2015-05-18 19:56:49','.png',0,0,0,'b5','b5',1,0),
	(398,6,'/','qr-code.png','Joachim Dieterich',2,532,'2015-05-18 20:06:43','.png',0,0,0,'qr','qr',1,0),
	(566,6,'/','Badge01ortho.png','Joachim Dieterich',2,532,'2015-08-16 17:22:00','.png',0,0,0,'Badge01','Badge1',1,0),
	(576,6,'/','Badge01ortho-1.png','Joachim Dieterich',2,532,'2015-08-17 10:21:37','.png',0,0,0,'TEst2','Testbadge',1,0),
	(2207,5,'','London-Eye-2009.JPG','',2,532,'2012-12-30 22:46:42','.jpg',0,0,0,NULL,'Englisch',1,0);

/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle grade
# ------------------------------------------------------------

DROP TABLE IF EXISTS `grade`;

CREATE TABLE `grade` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `grade` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `institution_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_gr_creator` (`creator_id`),
  KEY `rel_gr_institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `grade` WRITE;
/*!40000 ALTER TABLE `grade` DISABLE KEYS */;

INSERT INTO `grade` (`id`, `grade`, `description`, `creation_time`, `creator_id`, `institution_id`)
VALUES
	(1,'1. Klasse','1. Klassenstufe (Grundschule)','0000-00-00 00:00:00',532,56),
	(2,'2. Klasse','2. Klassenstufe (Grundschule)','0000-00-00 00:00:00',532,56),
	(3,'3. Klasse','3. Klassenstufe (Grundschule)','0000-00-00 00:00:00',532,56),
	(4,'4. Klasse','4. Klassenstufe (Grundschule)','0000-00-00 00:00:00',532,56),
	(5,'5. Klasse','5. Klassenstufe (Orientierungsstufe)','0000-00-00 00:00:00',532,56),
	(6,'6. Klasse','6. Klassenstufe (Orientierungsstufe)','0000-00-00 00:00:00',532,56),
	(7,'7. Klasse','7. Klassenstufe (Sek. I)','0000-00-00 00:00:00',532,56),
	(8,'8. Klasse','8. Klassenstufe (Sek. I)','0000-00-00 00:00:00',532,56),
	(9,'9. Klasse','9. Klassenstufe (Sek. I)','0000-00-00 00:00:00',532,56),
	(10,'10. Klasse','10. Klassenstufe (Sek. I)','0000-00-00 00:00:00',532,56),
	(11,'11. Klasse','11. Klassenstufe (Sek. II)','0000-00-00 00:00:00',532,56),
	(12,'12. Klasse','12. Klassenstufe (Sek. II)','0000-00-00 00:00:00',532,56),
	(13,'13. Klasse','13. Klassenstufe (Sek. II)','0000-00-00 00:00:00',532,56),
	(48,'Sekundarstufe 1','Sek I. ','2015-03-09 08:47:49',532,56),
	(53,'Uni','Uni','2016-08-10 13:42:03',532,56),
	(145,'Erwachsenenbildung','Eb','2016-10-14 08:12:43',532,56);

/*!40000 ALTER TABLE `grade` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groups` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `grade_id` int(11) unsigned NOT NULL,
  `semester_id` int(11) unsigned NOT NULL,
  `institution_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_gp_grade` (`grade_id`),
  KEY `rel_gp_semester` (`semester_id`),
  KEY `rel_gp_institution` (`institution_id`),
  KEY `rel_gp_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `groups`, `description`, `grade_id`, `semester_id`, `institution_id`, `creation_time`, `creator_id`)
VALUES
	(1,'10D','RS+ Landau',10,30,56,'2016-01-20 08:52:35',532);

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle groups_enrolments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups_enrolments`;

CREATE TABLE `groups_enrolments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expel_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ge_group` (`group_id`),
  KEY `rel_ge_user` (`user_id`),
  KEY `rel_ge_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `groups_enrolments` WRITE;
/*!40000 ALTER TABLE `groups_enrolments` DISABLE KEYS */;

INSERT INTO `groups_enrolments` (`id`, `status`, `group_id`, `user_id`, `creation_time`, `expel_time`, `creator_id`)
VALUES
	(2,1,1,374,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(3,1,1,375,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(4,1,1,376,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(5,1,1,377,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(6,1,1,378,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(7,1,1,379,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(8,1,1,380,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(9,1,1,381,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(10,1,1,382,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(11,1,1,383,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(12,1,1,384,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(13,1,1,385,'2016-12-15 22:15:55','0000-00-00 00:00:00',532),
	(14,1,1,386,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(15,1,1,387,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(16,1,1,388,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(17,1,1,389,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(18,1,1,390,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(19,1,1,391,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(20,1,1,392,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(21,1,1,393,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(22,1,1,394,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(23,1,1,395,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(24,1,1,396,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(25,1,1,397,'2016-12-15 22:15:56','0000-00-00 00:00:00',532),
	(26,1,1,531,'2016-12-15 22:38:01','0000-00-00 00:00:00',532);

/*!40000 ALTER TABLE `groups_enrolments` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle help
# ------------------------------------------------------------

DROP TABLE IF EXISTS `help`;

CREATE TABLE `help` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `description` text,
  `category` tinytext,
  `file_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle institution
# ------------------------------------------------------------

DROP TABLE IF EXISTS `institution`;

CREATE TABLE `institution` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `confirmed` int(11) NOT NULL DEFAULT '1',
  `institution` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `schooltype_id` int(11) unsigned NOT NULL,
  `country_id` char(2) NOT NULL DEFAULT 'DE',
  `state_id` int(11) unsigned NOT NULL DEFAULT '11',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `paginator_limit` smallint(6) DEFAULT NULL,
  `std_role` smallint(6) DEFAULT NULL,
  `csv_size` int(10) DEFAULT NULL,
  `avatar_size` int(10) DEFAULT NULL,
  `material_size` int(10) DEFAULT NULL,
  `acc_days` smallint(6) DEFAULT NULL,
  `timeout` int(11) NOT NULL DEFAULT '10',
  `semester_id` int(10) unsigned DEFAULT NULL,
  `file_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ins_schooltype` (`schooltype_id`),
  KEY `rel_ins_state` (`state_id`),
  KEY `rel_ins_creator` (`creator_id`),
  KEY `rel_ins_semester` (`semester_id`),
  KEY `rel_ins_file` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `institution` WRITE;
/*!40000 ALTER TABLE `institution` DISABLE KEYS */;

INSERT INTO `institution` (`id`, `confirmed`, `institution`, `description`, `schooltype_id`, `country_id`, `state_id`, `creation_time`, `creator_id`, `paginator_limit`, `std_role`, `csv_size`, `avatar_size`, `material_size`, `acc_days`, `timeout`, `semester_id`, `file_id`)
VALUES
	(0,1,'globaler Datensatz','globaler Datensatz',0,'DE',11,'2016-10-26 10:38:07',532,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,NULL),
	(56,1,'UIINSTIUTION','STraße',1,'56',11,'2013-09-07 09:48:50',532,10,0,1048576,1048576,1048576,7,7,30,126);

/*!40000 ALTER TABLE `institution` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle institution_enrolments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `institution_enrolments`;

CREATE TABLE `institution_enrolments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `institution_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expeled_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creator_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_INSTITUTION_ID` (`institution_id`),
  KEY `IDX_USER_ID` (`user_id`),
  KEY `rel_ie_creator` (`creator_id`),
  KEY `rel_ie_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `institution_enrolments` WRITE;
/*!40000 ALTER TABLE `institution_enrolments` DISABLE KEYS */;

INSERT INTO `institution_enrolments` (`id`, `status`, `institution_id`, `user_id`, `creation_time`, `expeled_time`, `creator_id`, `role_id`)
VALUES
	(774,1,56,374,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(775,1,56,375,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(776,1,56,376,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(777,1,56,377,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(778,1,56,379,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(779,1,56,380,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(780,1,56,381,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(781,1,56,382,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(782,1,56,383,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(783,1,56,384,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(784,1,56,385,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(785,1,56,386,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(786,1,56,387,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(787,1,56,388,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(788,1,56,389,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(789,1,56,390,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(790,1,56,391,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(791,1,56,392,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(792,1,56,393,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(793,1,56,394,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(794,1,56,395,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(795,1,56,396,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(796,1,56,397,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(797,1,56,378,'2016-12-15 22:16:27','0000-00-00 00:00:00',532,0),
	(798,1,56,531,'2016-12-15 22:37:57','0000-00-00 00:00:00',532,8),
	(799,1,56,532,'2017-01-27 13:31:20','0000-00-00 00:00:00',532,1);

/*!40000 ALTER TABLE `institution_enrolments` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `ip` varchar(45) NOT NULL,
  `action` varchar(40) NOT NULL,
  `url` varchar(250) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_lo_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) unsigned NOT NULL DEFAULT '0',
  `receiver_id` int(11) unsigned NOT NULL DEFAULT '0',
  `subject` text,
  `message` mediumtext,
  `sender_status` smallint(1) DEFAULT '0' COMMENT '0 = ungelesen, 1 = gelesen, -1 = gelöscht',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver_status` smallint(1) DEFAULT '0' COMMENT '0 = ungelesen, 1 = gelesen, -1 = gelöscht',
  PRIMARY KEY (`id`),
  KEY `rel_me_sender` (`sender_id`),
  KEY `rel_me_receiver` (`receiver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle quiz_answers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quiz_answers`;

CREATE TABLE `quiz_answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `answer` mediumtext,
  `correct` tinyint(1) DEFAULT '0',
  `question_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_qa_question` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle quiz_questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quiz_questions`;

CREATE TABLE `quiz_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` mediumtext,
  `type` int(11) DEFAULT NULL COMMENT '0 = true/false; 1 = Multiple choice; 2=shortanswer',
  `objective_id` int(11) unsigned DEFAULT NULL,
  `objective_type` int(10) unsigned DEFAULT NULL COMMENT '0 = terminal objective; 1= enabling objective',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle repeat_interval
# ------------------------------------------------------------

DROP TABLE IF EXISTS `repeat_interval`;

CREATE TABLE `repeat_interval` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `repeat_interval` int(11) unsigned NOT NULL DEFAULT '1',
  `description` varchar(240) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `repeat_interval` WRITE;
/*!40000 ALTER TABLE `repeat_interval` DISABLE KEYS */;

INSERT INTO `repeat_interval` (`id`, `repeat_interval`, `description`)
VALUES
	(1,1,'täglich'),
	(2,7,'wöchentlich'),
	(3,30,'jeden Monat'),
	(4,182,'jedes Halbjahr'),
	(5,365,'jedes Jahr'),
	(6,0,'keine Wiederholung');

/*!40000 ALTER TABLE `repeat_interval` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle role_capabilities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_capabilities`;

CREATE TABLE `role_capabilities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL,
  `capability` varchar(240) NOT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_rc_role` (`role_id`),
  KEY `rel_rc_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `role_capabilities` WRITE;
/*!40000 ALTER TABLE `role_capabilities` DISABLE KEYS */;

INSERT INTO `role_capabilities` (`id`, `role_id`, `capability`, `permission`, `creation_time`, `creator_id`)
VALUES
	(8,1,'menu:readInstitution',1,'2013-10-14 11:01:07',532),
	(12,1,'menu:readObjectives',1,'2013-10-14 11:03:03',532),
	(15,1,'menu:readCurriculum',1,'2013-10-14 11:09:50',532),
	(18,1,'menu:readGroup',1,'2013-10-14 11:12:49',532),
	(20,1,'menu:readUser',1,'2013-10-14 11:33:23',532),
	(23,1,'menu:readGrade',1,'2013-10-14 11:34:07',532),
	(25,1,'menu:readSubject',1,'2013-10-14 11:36:37',532),
	(27,1,'menu:readSemester',1,'2013-10-14 11:37:27',532),
	(29,1,'menu:readBackup',1,'2013-10-14 11:37:53',532),
	(42,1,'menu:readLog',1,'2013-10-25 06:50:56',532),
	(52,1,'menu:readRole',1,'2013-11-17 19:09:23',532),
	(218,1,'user:addUser',1,'2014-02-02 18:23:35',532),
	(223,1,'user:updateUser',1,'2014-02-02 18:26:20',532),
	(228,1,'user:updateRole',1,'2014-02-06 08:54:58',532),
	(233,1,'user:delete',1,'2014-02-06 08:58:59',532),
	(238,1,'user:changePassword',1,'2014-02-06 09:14:03',532),
	(248,1,'user:getGroupMembers',1,'2014-02-06 09:24:55',532),
	(253,1,'user:listNewUsers',1,'2014-02-06 09:30:33',532),
	(258,1,'user:enroleToInstitution',1,'2014-02-06 09:37:11',532),
	(263,1,'user:enroleToGroup',1,'2014-02-06 09:41:28',532),
	(268,1,'user:expelFromGroup',1,'2014-02-06 09:44:04',532),
	(273,1,'menu:readuserImport',1,'2014-02-06 09:46:27',532),
	(278,1,'user:userList',1,'2014-02-06 09:56:33',532),
	(283,1,'user:resetPassword',1,'2014-02-06 10:16:43',532),
	(288,1,'user:getUsers',1,'2014-02-06 10:26:57',532),
	(298,1,'user:confirmUser',1,'2014-02-06 10:37:18',532),
	(303,1,'user:dedicate',0,'2014-02-06 10:41:32',532),
	(325,1,'mail:loadMail',1,'2014-04-02 16:59:25',532),
	(330,1,'mail:postMail',1,'2014-04-02 18:45:34',532),
	(335,1,'mail:loadInbox',1,'2014-04-02 18:53:56',532),
	(340,1,'mail:loadOutbox',1,'2014-04-02 18:56:10',532),
	(345,1,'mail:loadDeletedMessages',1,'2014-04-02 18:57:13',532),
	(350,1,'file:solutionUpload',1,'2014-04-03 15:11:13',532),
	(355,1,'file:loadMaterial',1,'2014-04-03 15:22:20',532),
	(360,1,'backup:add',1,'2014-04-06 15:18:21',532),
	(370,1,'backup:delete',1,'2014-04-06 15:24:09',532),
	(375,1,'objectives:setStatus',1,'2014-04-06 18:37:30',532),
	(380,1,'file:upload',1,'2014-04-16 08:03:40',532),
	(385,1,'file:uploadURL',1,'2014-04-16 08:04:37',532),
	(390,1,'file:lastFiles',1,'2014-04-16 08:12:35',532),
	(395,1,'file:curriculumFiles',1,'2014-04-16 08:20:37',532),
	(400,1,'file:solution',1,'2014-04-16 08:24:42',532),
	(405,1,'file:myFiles',1,'2014-04-16 08:26:26',532),
	(410,1,'file:myAvatars',1,'2014-04-16 08:28:40',532),
	(415,1,'objectives:addTerminalObjective',1,'2014-04-16 09:48:53',532),
	(425,1,'objectives:updateTerminalObjectives',1,'2014-04-16 09:56:18',532),
	(430,1,'objectives:deleteTerminalObjectives',1,'2014-04-16 09:57:53',532),
	(435,1,'objectives:addEnablingObjective',1,'2014-04-16 10:03:38',532),
	(440,1,'objectives:updateEnablingObjectives',1,'2014-04-16 10:07:16',532),
	(445,1,'objectives:deleteEnablingObjectives',1,'2014-04-16 10:09:18',532),
	(455,1,'subject:add',1,'2014-04-16 10:46:18',532),
	(460,1,'subject:update',1,'2014-04-16 10:47:09',532),
	(465,1,'subject:delete',1,'2014-04-16 14:10:43',532),
	(470,1,'semester:add',1,'2014-04-16 14:16:56',532),
	(475,1,'semester:update',1,'2014-04-16 14:18:31',532),
	(480,1,'semester:delete',1,'2014-04-16 14:20:19',532),
	(485,1,'schooltype:add',1,'2014-04-16 14:23:38',532),
	(490,1,'schooltype:update',1,'2014-04-16 14:24:23',532),
	(495,1,'schooltype:delete',1,'2014-04-16 14:25:27',532),
	(500,1,'log:getLogs',1,'2014-04-16 14:28:38',532),
	(505,1,'institution:add',1,'2014-04-16 14:31:57',532),
	(510,1,'institution:delete',1,'2014-04-16 14:32:43',532),
	(515,1,'institution:update',1,'2014-04-16 14:35:27',532),
	(520,1,'groups:add',1,'2014-04-16 14:39:56',532),
	(525,1,'groups:update',1,'2014-04-16 14:41:44',532),
	(530,1,'groups:delete',1,'2014-04-16 14:44:35',532),
	(535,1,'groups:expel',1,'2014-04-16 14:47:34',532),
	(540,1,'groups:enrol',1,'2014-04-16 14:48:53',532),
	(545,1,'groups:changeSemester',1,'2014-04-16 14:51:09',532),
	(550,1,'grade:add',1,'2014-04-16 14:54:58',532),
	(555,1,'grade:update',1,'2014-04-16 14:55:53',532),
	(560,1,'grade:delete',1,'2014-04-16 14:57:13',532),
	(565,1,'file:update',1,'2014-04-16 15:01:04',532),
	(570,1,'file:delete',1,'2014-04-16 15:02:54',532),
	(575,1,'file:getSolutions',1,'2014-04-16 15:06:03',532),
	(580,1,'curriculum:add',1,'2014-04-16 15:09:35',532),
	(585,1,'curriculum:update',1,'2014-04-16 15:11:48',532),
	(590,1,'curriculum:delete',1,'2014-04-16 15:13:01',532),
	(595,1,'role:add',1,'2014-04-17 07:41:02',532),
	(600,1,'role:delete',1,'2014-04-17 07:43:37',532),
	(605,1,'backup:getMyBackups',1,'2014-04-17 07:51:17',532),
	(610,1,'backup:getAllBackups',1,'2014-04-17 07:53:10',532),
	(625,1,'dashboard:globalAdmin',1,'2014-04-17 08:16:12',532),
	(630,1,'dashboard:institutionalAdmin',1,'2014-04-17 08:18:00',532),
	(731,1,'role:update',1,'2014-04-27 10:09:51',532),
	(738,1,'groups:add',1,'2014-09-14 14:34:51',532),
	(836,1,'menu:readCertificate',1,'2014-10-03 13:43:49',532),
	(841,1,'user:getGroups',1,'2014-10-03 15:36:55',532),
	(846,1,'user:getCurricula',1,'2014-10-03 15:41:35',532),
	(861,1,'menu:readMessages',1,'2014-10-03 17:29:12',532),
	(968,1,'page:showAdminDocu',1,'2014-10-03 20:34:19',532),
	(973,1,'page:showTeacherDocu',1,'2014-10-03 20:37:49',532),
	(978,1,'page:showStudentDocu',1,'2014-10-03 20:38:16',532),
	(983,1,'page:showCronjob',1,'2014-10-03 20:42:03',532),
	(993,1,'curriculum:addObjectives',1,'2014-10-12 10:32:05',532),
	(998,1,'user:getHelp',1,'2014-10-12 16:43:04',532),
	(1003,1,'groups:showAccomplished',1,'2014-10-12 16:49:22',532),
	(1008,1,'file:editMaterial',1,'2014-10-12 17:03:36',532),
	(1023,1,'user:confirmUserSidewide',1,'2014-10-12 20:22:22',532),
	(1034,1,'menu:readMyInstitution',1,'2014-10-13 11:26:41',532),
	(1039,1,'user:expelFromInstitution',1,'2014-10-14 10:00:06',532),
	(1044,1,'user:userListComplete',1,'2014-10-14 10:40:44',532),
	(1049,1,'user:getInstitution',1,'2014-10-14 10:53:25',532),
	(1182,1,'groups:showCurriculumEnrolments',1,'2014-10-21 14:13:11',532),
	(1184,1,'mail:delete',1,'2014-12-08 10:54:23',532),
	(1189,1,'certificate:add',1,'2014-12-28 09:55:04',532),
	(1194,1,'certificate:update',1,'2015-01-02 08:41:45',532),
	(1199,1,'certificate:delete',1,'2015-01-02 08:42:44',532),
	(1203,5,'backup:add',0,'2015-03-10 11:07:26',532),
	(1204,5,'backup:delete',0,'2015-03-10 11:07:26',532),
	(1205,5,'backup:getAllBackups',0,'2015-03-10 11:07:26',532),
	(1206,5,'backup:getMyBackups',0,'2015-03-10 11:07:26',532),
	(1208,5,'certificate:add',1,'2015-03-10 11:07:26',532),
	(1209,5,'certificate:delete',1,'2015-03-10 11:07:26',532),
	(1210,5,'certificate:update',1,'2015-03-10 11:07:26',532),
	(1213,5,'curriculum:add',1,'2015-03-10 11:07:26',532),
	(1214,5,'curriculum:addObjectives',1,'2015-03-10 11:07:26',532),
	(1215,5,'curriculum:delete',1,'2015-03-10 11:07:26',532),
	(1216,5,'curriculum:update',1,'2015-03-10 11:07:26',532),
	(1217,5,'dashboard:globalAdmin',1,'2015-03-10 11:07:26',532),
	(1218,5,'dashboard:institutionalAdmin',1,'2015-03-10 11:07:26',532),
	(1219,5,'file:curriculumFiles',1,'2015-03-10 11:07:26',532),
	(1220,5,'file:delete',1,'2015-03-10 11:07:26',532),
	(1221,5,'file:editMaterial',1,'2015-03-10 11:07:26',532),
	(1222,5,'file:getSolutions',1,'2015-03-10 11:07:26',532),
	(1223,5,'file:lastFiles',1,'2015-03-10 11:07:26',532),
	(1224,5,'file:loadMaterial',1,'2015-03-10 11:07:26',532),
	(1225,5,'file:myAvatars',1,'2015-03-10 11:07:26',532),
	(1226,5,'file:myFiles',1,'2015-03-10 11:07:26',532),
	(1227,5,'file:solution',1,'2015-03-10 11:07:26',532),
	(1228,5,'file:solutionUpload',1,'2015-03-10 11:07:26',532),
	(1229,5,'file:update',1,'2015-03-10 11:07:26',532),
	(1230,5,'file:upload',1,'2015-03-10 11:07:26',532),
	(1231,5,'file:uploadURL',1,'2015-03-10 11:07:26',532),
	(1232,5,'grade:add',1,'2015-03-10 11:07:26',532),
	(1233,5,'grade:delete',1,'2015-03-10 11:07:26',532),
	(1234,5,'grade:update',1,'2015-03-10 11:07:26',532),
	(1235,5,'groups:add',1,'2015-03-10 11:07:26',532),
	(1236,5,'groups:changeSemester',1,'2015-03-10 11:07:26',532),
	(1237,5,'groups:delete',1,'2015-03-10 11:07:26',532),
	(1238,5,'groups:enrol',1,'2015-03-10 11:07:26',532),
	(1239,5,'groups:expel',1,'2015-03-10 11:07:26',532),
	(1240,5,'groups:showAccomplished',1,'2015-03-10 11:07:26',532),
	(1241,5,'groups:showCurriculumEnrolments',1,'2015-03-10 11:07:26',532),
	(1242,5,'groups:update',1,'2015-03-10 11:07:26',532),
	(1243,5,'institution:add',1,'2015-03-10 11:07:26',532),
	(1244,5,'institution:delete',0,'2015-03-10 11:07:26',532),
	(1245,5,'institution:update',1,'2015-03-10 11:07:26',532),
	(1246,5,'log:getLogs',0,'2015-03-10 11:07:26',532),
	(1247,5,'mail:delete',1,'2015-03-10 11:07:26',532),
	(1248,5,'mail:loadDeletedMessages',1,'2015-03-10 11:07:26',532),
	(1249,5,'mail:loadInbox',1,'2015-03-10 11:07:26',532),
	(1250,5,'mail:loadMail',1,'2015-03-10 11:07:26',532),
	(1251,5,'mail:loadOutbox',1,'2015-03-10 11:07:26',532),
	(1252,5,'mail:postMail',1,'2015-03-10 11:07:26',532),
	(1253,5,'menu:readBackup',0,'2015-03-10 11:07:26',532),
	(1254,5,'menu:readCertificate',1,'2015-03-10 11:07:26',532),
	(1257,5,'menu:readCurriculum',1,'2015-03-10 11:07:26',532),
	(1258,5,'menu:readGrade',1,'2015-03-10 11:07:26',532),
	(1259,5,'menu:readGroup',1,'2015-03-10 11:07:26',532),
	(1260,5,'menu:readInstitution',0,'2015-03-10 11:07:26',532),
	(1262,5,'menu:readLog',0,'2015-03-10 11:07:26',532),
	(1263,5,'menu:readMessages',1,'2015-03-10 11:07:26',532),
	(1265,5,'menu:readMyInstitution',1,'2015-03-10 11:07:26',532),
	(1267,5,'menu:readObjectives',1,'2015-03-10 11:07:26',532),
	(1272,5,'menu:readRole',0,'2015-03-10 11:07:26',532),
	(1273,5,'menu:readSemester',1,'2015-03-10 11:07:26',532),
	(1274,5,'menu:readSubject',1,'2015-03-10 11:07:26',532),
	(1275,5,'menu:readUser',1,'2015-03-10 11:07:26',532),
	(1276,5,'menu:readuserImport',1,'2015-03-10 11:07:26',532),
	(1277,5,'objectives:addEnablingObjective',1,'2015-03-10 11:07:26',532),
	(1278,5,'objectives:addTerminalObjective',1,'2015-03-10 11:07:26',532),
	(1279,5,'objectives:deleteEnablingObjectives',1,'2015-03-10 11:07:26',532),
	(1280,5,'objectives:deleteTerminalObjectives',1,'2015-03-10 11:07:26',532),
	(1282,5,'objectives:setStatus',1,'2015-03-10 11:07:26',532),
	(1283,5,'objectives:updateEnablingObjectives',1,'2015-03-10 11:07:26',532),
	(1284,5,'objectives:updateTerminalObjectives',1,'2015-03-10 11:07:26',532),
	(1285,5,'page:showAdminDocu',1,'2015-03-10 11:07:26',532),
	(1286,5,'page:showCronjob',1,'2015-03-10 11:07:26',532),
	(1288,5,'page:showStudentDocu',1,'2015-03-10 11:07:26',532),
	(1289,5,'page:showTeacherDocu',1,'2015-03-10 11:07:26',532),
	(1290,5,'role:add',0,'2015-03-10 11:07:26',532),
	(1291,5,'role:delete',0,'2015-03-10 11:07:26',532),
	(1292,5,'role:update',0,'2015-03-10 11:07:26',532),
	(1293,5,'schooltype:add',1,'2015-03-10 11:07:26',532),
	(1294,5,'schooltype:delete',1,'2015-03-10 11:07:26',532),
	(1295,5,'schooltype:update',1,'2015-03-10 11:07:26',532),
	(1296,5,'semester:add',1,'2015-03-10 11:07:26',532),
	(1297,5,'semester:delete',1,'2015-03-10 11:07:26',532),
	(1298,5,'semester:update',1,'2015-03-10 11:07:26',532),
	(1299,5,'subject:add',1,'2015-03-10 11:07:26',532),
	(1300,5,'subject:delete',1,'2015-03-10 11:07:26',532),
	(1301,5,'subject:update',1,'2015-03-10 11:07:26',532),
	(1302,5,'user:addUser',1,'2015-03-10 11:07:26',532),
	(1303,5,'user:changePassword',1,'2015-03-10 11:07:26',532),
	(1304,5,'user:confirmUser',1,'2015-03-10 11:07:26',532),
	(1305,5,'user:confirmUserSidewide',1,'2015-03-10 11:07:26',532),
	(1306,5,'user:dedicate',1,'2015-03-10 11:07:26',532),
	(1307,5,'user:delete',1,'2015-03-10 11:07:26',532),
	(1308,5,'user:enroleToGroup',1,'2015-03-10 11:07:26',532),
	(1309,5,'user:enroleToInstitution',1,'2015-03-10 11:07:26',532),
	(1310,5,'user:expelFromGroup',1,'2015-03-10 11:07:26',532),
	(1311,5,'user:expelFromInstitution',1,'2015-03-10 11:07:26',532),
	(1312,5,'user:getCurricula',1,'2015-03-10 11:07:26',532),
	(1313,5,'user:getGroupMembers',1,'2015-03-10 11:07:26',532),
	(1314,5,'user:getGroups',1,'2015-03-10 11:07:26',532),
	(1315,5,'user:getHelp',1,'2015-03-10 11:07:26',532),
	(1316,5,'user:getInstitution',1,'2015-03-10 11:07:26',532),
	(1319,5,'user:getUsers',1,'2015-03-10 11:07:26',532),
	(1320,5,'user:listNewUsers',1,'2015-03-10 11:07:26',532),
	(1321,5,'user:resetPassword',1,'2015-03-10 11:07:26',532),
	(1322,5,'user:updateRole',1,'2015-03-10 11:07:26',532),
	(1323,5,'user:updateUser',1,'2015-03-10 11:07:26',532),
	(1324,5,'user:userList',1,'2015-03-10 11:07:26',532),
	(1325,5,'user:userListComplete',0,'2015-03-10 11:07:26',532),
	(1326,6,'backup:add',0,'2015-03-10 11:07:57',532),
	(1327,6,'backup:delete',0,'2015-03-10 11:07:57',532),
	(1328,6,'backup:getAllBackups',0,'2015-03-10 11:07:57',532),
	(1329,6,'backup:getMyBackups',0,'2015-03-10 11:07:57',532),
	(1331,6,'certificate:add',0,'2015-03-10 11:07:57',532),
	(1332,6,'certificate:delete',0,'2015-03-10 11:07:57',532),
	(1333,6,'certificate:update',0,'2015-03-10 11:07:57',532),
	(1336,6,'curriculum:add',0,'2015-03-10 11:07:57',532),
	(1337,6,'curriculum:addObjectives',0,'2015-03-10 11:07:57',532),
	(1338,6,'curriculum:delete',0,'2015-03-10 11:07:57',532),
	(1339,6,'curriculum:update',0,'2015-03-10 11:07:57',532),
	(1340,6,'dashboard:globalAdmin',0,'2015-03-10 11:07:57',532),
	(1341,6,'dashboard:institutionalAdmin',0,'2015-03-10 11:07:57',532),
	(1342,6,'file:curriculumFiles',0,'2015-03-10 11:07:57',532),
	(1343,6,'file:delete',1,'2015-03-10 11:07:57',532),
	(1344,6,'file:editMaterial',0,'2015-03-10 11:07:57',532),
	(1345,6,'file:getSolutions',1,'2015-03-10 11:07:57',532),
	(1346,6,'file:lastFiles',1,'2015-03-10 11:07:57',532),
	(1347,6,'file:loadMaterial',1,'2015-03-10 11:07:57',532),
	(1348,6,'file:myAvatars',1,'2015-03-10 11:07:57',532),
	(1349,6,'file:myFiles',1,'2015-03-10 11:07:57',532),
	(1350,6,'file:solution',1,'2015-03-10 11:07:57',532),
	(1351,6,'file:solutionUpload',0,'2015-03-10 11:07:57',532),
	(1352,6,'file:update',1,'2015-03-10 11:07:57',532),
	(1353,6,'file:upload',0,'2015-03-10 11:07:57',532),
	(1354,6,'file:uploadURL',1,'2015-03-10 11:07:57',532),
	(1355,6,'grade:add',0,'2015-03-10 11:07:57',532),
	(1356,6,'grade:delete',0,'2015-03-10 11:07:57',532),
	(1357,6,'grade:update',0,'2015-03-10 11:07:57',532),
	(1358,6,'groups:add',0,'2015-03-10 11:07:57',532),
	(1359,6,'groups:changeSemester',0,'2015-03-10 11:07:57',532),
	(1360,6,'groups:delete',0,'2015-03-10 11:07:57',532),
	(1361,6,'groups:enrol',0,'2015-03-10 11:07:57',532),
	(1362,6,'groups:expel',0,'2015-03-10 11:07:57',532),
	(1363,6,'groups:showAccomplished',1,'2015-03-10 11:07:57',532),
	(1364,6,'groups:showCurriculumEnrolments',0,'2015-03-10 11:07:57',532),
	(1365,6,'groups:update',0,'2015-03-10 11:07:57',532),
	(1366,6,'institution:add',0,'2015-03-10 11:07:57',532),
	(1367,6,'institution:delete',0,'2015-03-10 11:07:57',532),
	(1368,6,'institution:update',0,'2015-03-10 11:07:57',532),
	(1369,6,'log:getLogs',0,'2015-03-10 11:07:57',532),
	(1370,6,'mail:delete',1,'2015-03-10 11:07:57',532),
	(1371,6,'mail:loadDeletedMessages',1,'2015-03-10 11:07:57',532),
	(1372,6,'mail:loadInbox',1,'2015-03-10 11:07:57',532),
	(1373,6,'mail:loadMail',1,'2015-03-10 11:07:57',532),
	(1374,6,'mail:loadOutbox',1,'2015-03-10 11:07:57',532),
	(1375,6,'mail:postMail',1,'2015-03-10 11:07:57',532),
	(1376,6,'menu:readBackup',0,'2015-03-10 11:07:57',532),
	(1377,6,'menu:readCertificate',0,'2015-03-10 11:07:57',532),
	(1380,6,'menu:readCurriculum',0,'2015-03-10 11:07:57',532),
	(1381,6,'menu:readGrade',1,'2015-03-10 11:07:57',532),
	(1382,6,'menu:readGroup',1,'2015-03-10 11:07:57',532),
	(1383,6,'menu:readInstitution',0,'2015-03-10 11:07:57',532),
	(1385,6,'menu:readLog',0,'2015-03-10 11:07:57',532),
	(1386,6,'menu:readMessages',1,'2015-03-10 11:07:57',532),
	(1388,6,'menu:readMyInstitution',1,'2015-03-10 11:07:57',532),
	(1390,6,'menu:readObjectives',1,'2015-03-10 11:07:57',532),
	(1395,6,'menu:readRole',0,'2015-03-10 11:07:57',532),
	(1396,6,'menu:readSemester',0,'2015-03-10 11:07:57',532),
	(1397,6,'menu:readSubject',0,'2015-03-10 11:07:57',532),
	(1398,6,'menu:readUser',1,'2015-03-10 11:07:57',532),
	(1399,6,'menu:readuserImport',1,'2015-03-10 11:07:57',532),
	(1400,6,'objectives:addEnablingObjective',0,'2015-03-10 11:07:57',532),
	(1401,6,'objectives:addTerminalObjective',0,'2015-03-10 11:07:57',532),
	(1402,6,'objectives:deleteEnablingObjectives',0,'2015-03-10 11:07:57',532),
	(1403,6,'objectives:deleteTerminalObjectives',0,'2015-03-10 11:07:57',532),
	(1405,6,'objectives:setStatus',1,'2015-03-10 11:07:57',532),
	(1406,6,'objectives:updateEnablingObjectives',0,'2015-03-10 11:07:57',532),
	(1407,6,'objectives:updateTerminalObjectives',0,'2015-03-10 11:07:57',532),
	(1408,6,'page:showAdminDocu',1,'2015-03-10 11:07:57',532),
	(1409,6,'page:showCronjob',0,'2015-03-10 11:07:57',532),
	(1411,6,'page:showStudentDocu',1,'2015-03-10 11:07:57',532),
	(1412,6,'page:showTeacherDocu',0,'2015-03-10 11:07:57',532),
	(1413,6,'role:add',0,'2015-03-10 11:07:57',532),
	(1414,6,'role:delete',0,'2015-03-10 11:07:57',532),
	(1415,6,'role:update',0,'2015-03-10 11:07:57',532),
	(1416,6,'schooltype:add',0,'2015-03-10 11:07:57',532),
	(1417,6,'schooltype:delete',0,'2015-03-10 11:07:57',532),
	(1418,6,'schooltype:update',0,'2015-03-10 11:07:57',532),
	(1419,6,'semester:add',0,'2015-03-10 11:07:57',532),
	(1420,6,'semester:delete',0,'2015-03-10 11:07:57',532),
	(1421,6,'semester:update',0,'2015-03-10 11:07:57',532),
	(1422,6,'subject:add',0,'2015-03-10 11:07:57',532),
	(1423,6,'subject:delete',0,'2015-03-10 11:07:57',532),
	(1424,6,'subject:update',0,'2015-03-10 11:07:57',532),
	(1425,6,'user:addUser',1,'2015-03-10 11:07:57',532),
	(1426,6,'user:changePassword',1,'2015-03-10 11:07:57',532),
	(1427,6,'user:confirmUser',0,'2015-03-10 11:07:57',532),
	(1428,6,'user:confirmUserSidewide',0,'2015-03-10 11:07:57',532),
	(1429,6,'user:dedicate',0,'2015-03-10 11:07:57',532),
	(1430,6,'user:delete',1,'2015-03-10 11:07:57',532),
	(1431,6,'user:enroleToGroup',1,'2015-03-10 11:07:57',532),
	(1432,6,'user:enroleToInstitution',1,'2015-03-10 11:07:57',532),
	(1433,6,'user:expelFromGroup',0,'2015-03-10 11:07:57',532),
	(1434,6,'user:expelFromInstitution',0,'2015-03-10 11:07:57',532),
	(1435,6,'user:getCurricula',1,'2015-03-10 11:07:57',532),
	(1436,6,'user:getGroupMembers',1,'2015-03-10 11:07:57',532),
	(1437,6,'user:getGroups',1,'2015-03-10 11:07:57',532),
	(1438,6,'user:getHelp',0,'2015-03-10 11:07:57',532),
	(1439,6,'user:getInstitution',0,'2015-03-10 11:07:57',532),
	(1442,6,'user:getUsers',1,'2015-03-10 11:07:57',532),
	(1443,6,'user:listNewUsers',1,'2015-03-10 11:07:57',532),
	(1444,6,'user:resetPassword',1,'2015-03-10 11:07:57',532),
	(1445,6,'user:updateRole',1,'2015-03-10 11:07:57',532),
	(1446,6,'user:updateUser',1,'2015-03-10 11:07:57',532),
	(1447,6,'user:userList',1,'2015-03-10 11:07:57',532),
	(1448,6,'user:userListComplete',0,'2015-03-10 11:07:57',532),
	(1449,7,'backup:add',0,'2015-03-10 11:08:32',532),
	(1450,7,'backup:delete',0,'2015-03-10 11:08:32',532),
	(1451,7,'backup:getAllBackups',0,'2015-03-10 11:08:32',532),
	(1452,7,'backup:getMyBackups',0,'2015-03-10 11:08:32',532),
	(1454,7,'certificate:add',0,'2015-03-10 11:08:32',532),
	(1455,7,'certificate:delete',0,'2015-03-10 11:08:32',532),
	(1456,7,'certificate:update',0,'2015-03-10 11:08:32',532),
	(1459,7,'curriculum:add',0,'2015-03-10 11:08:32',532),
	(1460,7,'curriculum:addObjectives',0,'2015-03-10 11:08:32',532),
	(1461,7,'curriculum:delete',0,'2015-03-10 11:08:32',532),
	(1462,7,'curriculum:update',0,'2015-03-10 11:08:32',532),
	(1463,7,'dashboard:globalAdmin',0,'2015-03-10 11:08:32',532),
	(1464,7,'dashboard:institutionalAdmin',1,'2015-03-10 11:08:32',532),
	(1465,7,'file:curriculumFiles',1,'2015-03-10 11:08:32',532),
	(1466,7,'file:delete',1,'2015-03-10 11:08:32',532),
	(1467,7,'file:editMaterial',1,'2015-03-10 11:08:32',532),
	(1468,7,'file:getSolutions',1,'2015-03-10 11:08:32',532),
	(1469,7,'file:lastFiles',1,'2015-03-10 11:08:32',532),
	(1470,7,'file:loadMaterial',1,'2015-03-10 11:08:32',532),
	(1471,7,'file:myAvatars',1,'2015-03-10 11:08:32',532),
	(1472,7,'file:myFiles',1,'2015-03-10 11:08:32',532),
	(1473,7,'file:solution',1,'2015-03-10 11:08:32',532),
	(1474,7,'file:solutionUpload',1,'2015-03-10 11:08:32',532),
	(1475,7,'file:update',1,'2015-03-10 11:08:32',532),
	(1476,7,'file:upload',1,'2015-03-10 11:08:32',532),
	(1477,7,'file:uploadURL',1,'2015-03-10 11:08:32',532),
	(1478,7,'grade:add',0,'2015-03-10 11:08:32',532),
	(1479,7,'grade:delete',0,'2015-03-10 11:08:32',532),
	(1480,7,'grade:update',0,'2015-03-10 11:08:32',532),
	(1481,7,'groups:add',0,'2015-03-10 11:08:32',532),
	(1482,7,'groups:changeSemester',0,'2015-03-10 11:08:32',532),
	(1483,7,'groups:delete',0,'2015-03-10 11:08:32',532),
	(1484,7,'groups:enrol',0,'2015-03-10 11:08:32',532),
	(1485,7,'groups:expel',0,'2015-03-10 11:08:32',532),
	(1486,7,'groups:showAccomplished',1,'2015-03-10 11:08:32',532),
	(1487,7,'groups:showCurriculumEnrolments',0,'2015-03-10 11:08:32',532),
	(1488,7,'groups:update',0,'2015-03-10 11:08:32',532),
	(1489,7,'institution:add',0,'2015-03-10 11:08:32',532),
	(1490,7,'institution:delete',0,'2015-03-10 11:08:32',532),
	(1491,7,'institution:update',0,'2015-03-10 11:08:32',532),
	(1492,7,'log:getLogs',0,'2015-03-10 11:08:32',532),
	(1493,7,'mail:delete',1,'2015-03-10 11:08:32',532),
	(1494,7,'mail:loadDeletedMessages',1,'2015-03-10 11:08:32',532),
	(1495,7,'mail:loadInbox',1,'2015-03-10 11:08:32',532),
	(1496,7,'mail:loadMail',1,'2015-03-10 11:08:32',532),
	(1497,7,'mail:loadOutbox',1,'2015-03-10 11:08:32',532),
	(1498,7,'mail:postMail',1,'2015-03-10 11:08:32',532),
	(1499,7,'menu:readBackup',0,'2015-03-10 11:08:32',532),
	(1500,7,'menu:readCertificate',0,'2015-03-10 11:08:32',532),
	(1503,7,'menu:readCurriculum',0,'2015-03-10 11:08:32',532),
	(1504,7,'menu:readGrade',0,'2015-03-10 11:08:32',532),
	(1505,7,'menu:readGroup',0,'2015-03-10 11:08:32',532),
	(1506,7,'menu:readInstitution',0,'2015-03-10 11:08:32',532),
	(1508,7,'menu:readLog',0,'2015-03-10 11:08:32',532),
	(1509,7,'menu:readMessages',1,'2015-03-10 11:08:32',532),
	(1511,7,'menu:readMyInstitution',1,'2015-03-10 11:08:32',532),
	(1513,7,'menu:readObjectives',1,'2015-03-10 11:08:32',532),
	(1518,7,'menu:readRole',0,'2015-03-10 11:08:32',532),
	(1519,7,'menu:readSemester',0,'2015-03-10 11:08:32',532),
	(1520,7,'menu:readSubject',0,'2015-03-10 11:08:32',532),
	(1521,7,'menu:readUser',1,'2015-03-10 11:08:32',532),
	(1522,7,'menu:readuserImport',0,'2015-03-10 11:08:32',532),
	(1523,7,'objectives:addEnablingObjective',0,'2015-03-10 11:08:32',532),
	(1524,7,'objectives:addTerminalObjective',0,'2015-03-10 11:08:32',532),
	(1525,7,'objectives:deleteEnablingObjectives',0,'2015-03-10 11:08:32',532),
	(1526,7,'objectives:deleteTerminalObjectives',0,'2015-03-10 11:08:32',532),
	(1528,7,'objectives:setStatus',1,'2015-03-10 11:08:32',532),
	(1529,7,'objectives:updateEnablingObjectives',0,'2015-03-10 11:08:32',532),
	(1530,7,'objectives:updateTerminalObjectives',0,'2015-03-10 11:08:32',532),
	(1531,7,'page:showAdminDocu',0,'2015-03-10 11:08:32',532),
	(1532,7,'page:showCronjob',0,'2015-03-10 11:08:32',532),
	(1534,7,'page:showStudentDocu',1,'2015-03-10 11:08:32',532),
	(1535,7,'page:showTeacherDocu',1,'2015-03-10 11:08:32',532),
	(1536,7,'role:add',0,'2015-03-10 11:08:32',532),
	(1537,7,'role:delete',0,'2015-03-10 11:08:32',532),
	(1538,7,'role:update',0,'2015-03-10 11:08:32',532),
	(1539,7,'schooltype:add',0,'2015-03-10 11:08:32',532),
	(1540,7,'schooltype:delete',0,'2015-03-10 11:08:32',532),
	(1541,7,'schooltype:update',0,'2015-03-10 11:08:32',532),
	(1542,7,'semester:add',0,'2015-03-10 11:08:32',532),
	(1543,7,'semester:delete',0,'2015-03-10 11:08:32',532),
	(1544,7,'semester:update',0,'2015-03-10 11:08:32',532),
	(1545,7,'subject:add',0,'2015-03-10 11:08:32',532),
	(1546,7,'subject:delete',0,'2015-03-10 11:08:32',532),
	(1547,7,'subject:update',0,'2015-03-10 11:08:32',532),
	(1548,7,'user:addUser',0,'2015-03-10 11:08:32',532),
	(1549,7,'user:changePassword',1,'2015-03-10 11:08:32',532),
	(1550,7,'user:confirmUser',0,'2015-03-10 11:08:32',532),
	(1551,7,'user:confirmUserSidewide',0,'2015-03-10 11:08:32',532),
	(1552,7,'user:dedicate',0,'2015-03-10 11:08:32',532),
	(1553,7,'user:delete',0,'2015-03-10 11:08:32',532),
	(1554,7,'user:enroleToGroup',0,'2015-03-10 11:08:32',532),
	(1555,7,'user:enroleToInstitution',0,'2015-03-10 11:08:32',532),
	(1556,7,'user:expelFromGroup',0,'2015-03-10 11:08:32',532),
	(1557,7,'user:expelFromInstitution',0,'2015-03-10 11:08:32',532),
	(1558,7,'user:getCurricula',1,'2015-03-10 11:08:32',532),
	(1559,7,'user:getGroupMembers',1,'2015-03-10 11:08:32',532),
	(1560,7,'user:getGroups',1,'2015-03-10 11:08:32',532),
	(1561,7,'user:getHelp',0,'2015-03-10 11:08:32',532),
	(1562,7,'user:getInstitution',1,'2015-03-10 11:08:32',532),
	(1565,7,'user:getUsers',1,'2015-03-10 11:08:32',532),
	(1566,7,'user:listNewUsers',1,'2015-03-10 11:08:32',532),
	(1567,7,'user:resetPassword',1,'2015-03-10 11:08:32',532),
	(1568,7,'user:updateRole',0,'2015-03-10 11:08:32',532),
	(1569,7,'user:updateUser',1,'2015-03-10 11:08:32',532),
	(1570,7,'user:userList',1,'2015-03-10 11:08:32',532),
	(1571,7,'user:userListComplete',0,'2015-03-10 11:08:32',532),
	(1580,1,'file:load',1,'2015-08-15 22:24:40',532),
	(1581,5,'file:load',1,'2015-08-15 22:24:40',532),
	(1582,6,'file:load',1,'2015-08-15 22:24:40',532),
	(1583,7,'file:load',1,'2015-08-15 22:24:40',532),
	(1586,1,'user:update',1,'2015-08-16 09:40:01',532),
	(1587,5,'user:update',1,'2015-08-16 09:40:01',532),
	(1588,6,'user:update',1,'2015-08-16 09:40:01',532),
	(1589,7,'user:update',1,'2015-08-16 09:40:01',532),
	(1592,7,'menu:readConfirm',0,'2015-08-16 17:40:56',532),
	(1715,5,'menu:readConfirm',0,'2015-08-17 09:13:50',532),
	(1716,5,'course:setAccomplishedStatus',1,'2015-08-17 09:13:50',532),
	(1718,1,'course:setAccomplishedStatus',1,'2015-08-17 09:13:50',532),
	(1719,6,'course:setAccomplishedStatus',1,'2015-08-17 09:13:50',532),
	(1720,7,'course:setAccomplishedStatus',1,'2015-08-17 09:13:50',532),
	(1722,1,'user:userListInstitution',1,'2015-08-16 20:47:01',532),
	(1723,5,'user:userListInstitution',1,'2015-08-16 20:47:01',532),
	(1724,6,'user:userListInstitution',0,'2015-08-16 20:47:01',532),
	(1725,7,'user:userListInstitution',0,'2015-08-16 20:47:01',532),
	(1727,1,'user:userListGroup',1,'2015-08-16 20:47:01',532),
	(1728,5,'user:userListGroup',1,'2015-08-16 20:47:01',532),
	(1729,6,'user:userListGroup',1,'2015-08-16 20:47:01',532),
	(1730,7,'user:userListGroup',1,'2015-08-16 20:47:01',532),
	(1731,6,'menu:readUser',1,'2015-03-10 11:08:32',532),
	(1732,6,'menu:readMyInstitution',1,'2015-03-10 11:08:32',532),
	(1733,6,'menu:readMyInstitution',1,'2015-03-10 11:07:26',532),
	(1735,1,'file:uploadAvatar',1,'2015-03-10 11:07:26',532),
	(1736,5,'file:uploadAvatar',1,'2015-03-10 11:07:26',532),
	(1737,6,'file:uploadAvatar',1,'2015-03-10 11:07:26',532),
	(1738,7,'file:uploadAvatar',1,'2015-03-10 11:07:26',532),
	(1756,1,'plugin:useEmbeddableGoogleDocumentViewer',1,'2015-03-10 11:07:26',532),
	(1757,5,'plugin:useEmbeddableGoogleDocumentViewer',1,'2015-03-10 11:07:26',532),
	(1758,6,'plugin:useEmbeddableGoogleDocumentViewer',1,'2015-03-10 11:07:26',532),
	(1759,1,'file:showHits',1,'2015-03-10 11:07:26',532),
	(1761,5,'file:showHits',0,'2015-03-10 11:07:26',532),
	(1762,6,'file:showHits',0,'2015-03-10 11:07:26',532),
	(1763,7,'file:showHits',1,'2015-03-10 11:07:26',532),
	(1764,1,'menu:readConfirm',0,'2015-12-20 13:17:40',532),
	(1766,1,'quiz:showQuiz',1,'0000-00-00 00:00:00',532),
	(1767,5,'quiz:showQuiz',0,'0000-00-00 00:00:00',532),
	(1768,6,'quiz:showQuiz',0,'0000-00-00 00:00:00',532),
	(1769,7,'quiz:showQuiz',1,'0000-00-00 00:00:00',532),
	(1771,1,'dashboard:editBulletinBoard',1,'0000-00-00 00:00:00',532),
	(1772,5,'dashboard:editBulletinBoard',1,'0000-00-00 00:00:00',532),
	(1773,6,'dashboard:editBulletinBoard',1,'0000-00-00 00:00:00',532),
	(1774,7,'dashboard:editBulletinBoard',1,'0000-00-00 00:00:00',532),
	(1776,1,'curriculum:import',1,'0000-00-00 00:00:00',532),
	(1777,5,'curriculum:import',1,'0000-00-00 00:00:00',532),
	(1778,6,'curriculum:import',0,'0000-00-00 00:00:00',532),
	(1779,7,'curriculum:import',0,'0000-00-00 00:00:00',532),
	(1781,1,'curriculum:showAll',1,'0000-00-00 00:00:00',532),
	(1782,5,'curriculum:showAll',0,'0000-00-00 00:00:00',532),
	(1783,6,'curriculum:showAll',0,'0000-00-00 00:00:00',532),
	(1784,7,'curriculum:showAll',0,'0000-00-00 00:00:00',532),
	(1785,7,'menu:readCourseBook',0,'0000-00-00 00:00:00',532),
	(1786,1,'menu:readCourseBook',1,'0000-00-00 00:00:00',532),
	(1788,5,'menu:readCourseBook',1,'0000-00-00 00:00:00',532),
	(1789,6,'menu:readCourseBook',1,'0000-00-00 00:00:00',532),
	(1790,1,'coursebook:add',1,'0000-00-00 00:00:00',532),
	(1792,5,'coursebook:add',1,'0000-00-00 00:00:00',532),
	(1793,6,'coursebook:add',1,'0000-00-00 00:00:00',532),
	(1794,7,'coursebook:add',1,'0000-00-00 00:00:00',532),
	(1795,1,'coursebook:update',1,'0000-00-00 00:00:00',532),
	(1797,5,'coursebook:update',1,'0000-00-00 00:00:00',532),
	(1798,6,'coursebook:update',1,'0000-00-00 00:00:00',532),
	(1799,7,'coursebook:update',1,'0000-00-00 00:00:00',532),
	(1800,7,'event:add',1,'0000-00-00 00:00:00',532),
	(1802,1,'event:add',1,'0000-00-00 00:00:00',532),
	(1803,5,'event:add',1,'0000-00-00 00:00:00',532),
	(1804,6,'event:add',1,'0000-00-00 00:00:00',532),
	(1806,1,'event:update',1,'0000-00-00 00:00:00',532),
	(1807,5,'event:update',1,'0000-00-00 00:00:00',532),
	(1808,6,'event:update',1,'0000-00-00 00:00:00',532),
	(1809,7,'event:update',1,'0000-00-00 00:00:00',532),
	(1811,1,'task:add',1,'0000-00-00 00:00:00',532),
	(1812,5,'task:add',1,'0000-00-00 00:00:00',532),
	(1813,6,'task:add',1,'0000-00-00 00:00:00',532),
	(1814,7,'task:add',1,'0000-00-00 00:00:00',532),
	(1816,1,'task:update',1,'0000-00-00 00:00:00',532),
	(1817,5,'task:update',1,'0000-00-00 00:00:00',532),
	(1818,6,'task:update',1,'0000-00-00 00:00:00',532),
	(1819,7,'task:update',1,'0000-00-00 00:00:00',532),
	(1827,1,'coursebook:delete',1,'0000-00-00 00:00:00',532),
	(1828,5,'coursebook:delete',1,'0000-00-00 00:00:00',532),
	(1829,6,'coursebook:delete',1,'0000-00-00 00:00:00',532),
	(1830,7,'coursebook:delete',1,'0000-00-00 00:00:00',532),
	(1832,1,'event:delete',1,'0000-00-00 00:00:00',532),
	(1833,5,'event:delete',1,'0000-00-00 00:00:00',532),
	(1834,6,'event:delete',1,'0000-00-00 00:00:00',532),
	(1835,7,'event:delete',1,'0000-00-00 00:00:00',532),
	(1837,1,'task:delete',1,'0000-00-00 00:00:00',532),
	(1838,5,'task:delete',1,'0000-00-00 00:00:00',532),
	(1839,6,'task:delete',1,'0000-00-00 00:00:00',532),
	(1840,7,'task:delete',1,'0000-00-00 00:00:00',532),
	(1841,1,'task:enrol',1,'0000-00-00 00:00:00',532),
	(1842,5,'task:enrol',1,'0000-00-00 00:00:00',532),
	(1843,6,'task:enrol',1,'0000-00-00 00:00:00',532),
	(1845,7,'task:enrol',1,'0000-00-00 00:00:00',532),
	(1848,1,'absent:add',1,'2016-05-25 10:16:24',532),
	(1849,5,'absent:add',1,'2016-05-25 10:16:24',532),
	(1850,6,'absent:add',1,'2016-05-25 10:16:24',532),
	(1851,7,'absent:add',1,'2016-05-25 10:16:24',532),
	(1852,0,'absent:update',0,'2016-05-25 10:16:24',532),
	(1853,1,'absent:update',1,'2016-05-25 10:16:24',532),
	(1854,5,'absent:update',1,'2016-05-25 10:16:24',532),
	(1855,6,'absent:update',1,'2016-05-25 10:16:24',532),
	(1856,7,'absent:update',1,'2016-05-25 10:16:24',532),
	(1857,1,'block:add',1,'2016-06-17 10:56:40',532),
	(1858,5,'block:add',1,'2016-06-17 10:56:40',532),
	(1859,6,'block:add',1,'2016-06-17 10:56:40',532),
	(1860,7,'block:add',1,'2016-06-17 10:56:40',532),
	(1861,8,'block:add',0,'2016-06-17 10:56:40',532),
	(1862,8,'block:update',0,'2016-06-17 10:56:40',532),
	(1863,1,'block:update',1,'2016-06-17 10:56:40',532),
	(1864,5,'block:update',1,'2016-06-17 10:56:40',532),
	(1865,6,'block:update',1,'2016-06-17 10:56:40',532),
	(1866,7,'block:update',1,'2016-06-17 10:56:40',532),
	(1868,0,'mail:loadOutbox',1,'2015-08-16 20:47:01',532),
	(1869,0,'mail:loadMail',1,'2015-08-16 20:47:01',532),
	(1870,0,'mail:loadInbox',1,'2015-08-16 20:47:01',532),
	(1871,0,'mail:loadDeletedMessages',0,'2015-08-16 20:47:01',532),
	(1872,0,'mail:delete',1,'2015-08-16 20:47:01',532),
	(1873,0,'log:getLogs',0,'2015-08-16 20:47:01',532),
	(1874,0,'institution:update',0,'2015-08-16 20:47:01',532),
	(1875,0,'institution:delete',0,'2015-08-16 20:47:01',532),
	(1876,0,'institution:add',0,'2015-08-16 20:47:01',532),
	(1877,0,'groups:update',0,'2015-08-16 20:47:01',532),
	(1878,0,'groups:showCurriculumEnrolments',0,'2015-08-16 20:47:01',532),
	(1879,0,'groups:showAccomplished',1,'2015-08-16 20:47:01',532),
	(1880,0,'groups:expel',0,'2015-08-16 20:47:01',532),
	(1881,0,'groups:enrol',0,'2015-08-16 20:47:01',532),
	(1882,0,'mail:postMail',1,'2015-08-16 20:47:01',532),
	(1883,0,'menu:readBackup',0,'2015-08-16 20:47:01',532),
	(1887,0,'menu:readObjectives',0,'2015-08-16 20:47:01',532),
	(1888,0,'menu:readMyInstitution',0,'2015-08-16 20:47:01',532),
	(1890,0,'menu:readMessages',1,'2015-08-16 20:47:01',532),
	(1891,0,'menu:readLog',0,'2015-08-16 20:47:01',532),
	(1892,0,'menu:readInstitution',0,'2015-08-16 20:47:01',532),
	(1893,0,'menu:readGroup',0,'2015-08-16 20:47:01',532),
	(1894,0,'menu:readGrade',0,'2015-08-16 20:47:01',532),
	(1895,0,'menu:readCurriculum',0,'2015-08-16 20:47:01',532),
	(1896,0,'page:showTeacherDocu',0,'2015-08-16 20:47:01',532),
	(1897,0,'menu:readConfirm',0,'2015-08-16 20:47:01',532),
	(1898,0,'menu:readCertificate',0,'2015-08-16 20:47:01',532),
	(1899,0,'groups:delete',0,'2015-08-16 20:47:01',532),
	(1900,0,'groups:changeSemester',0,'2015-08-16 20:47:01',532),
	(1901,0,'groups:add',0,'2015-08-16 20:47:01',532),
	(1902,0,'dashboard:globalAdmin',0,'2015-08-16 20:47:01',532),
	(1903,0,'curriculum:update',0,'2015-08-16 20:47:01',532),
	(1904,0,'curriculum:delete',0,'2015-08-16 20:47:01',532),
	(1905,0,'curriculum:addObjectives',0,'2015-08-16 20:47:01',532),
	(1906,0,'curriculum:add',0,'2015-08-16 20:47:01',532),
	(1907,0,'certificate:update',0,'2015-08-16 20:47:01',532),
	(1908,0,'certificate:delete',0,'2015-08-16 20:47:01',532),
	(1909,0,'certificate:add',0,'2015-08-16 20:47:01',532),
	(1911,0,'backup:getMyBackups',0,'2015-08-16 20:47:01',532),
	(1912,0,'backup:getAllBackups',0,'2015-08-16 20:47:01',532),
	(1913,0,'backup:deleteBackup',0,'2015-08-16 20:47:01',532),
	(1914,0,'backup:addBackup',0,'2015-08-16 20:47:01',532),
	(1915,0,'user:update',1,'2015-08-16 09:40:01',532),
	(1916,0,'dashboard:institutionalAdmin',0,'2015-08-16 20:47:01',532),
	(1917,0,'file:curriculumFiles',1,'2015-08-16 20:47:01',532),
	(1918,0,'file:delete',0,'2015-08-16 20:47:01',532),
	(1919,0,'grade:update',0,'2015-08-16 20:47:01',532),
	(1920,0,'grade:delete',0,'2015-08-16 20:47:01',532),
	(1921,0,'grade:add',0,'2015-08-16 20:47:01',532),
	(1922,0,'file:uploadURL',1,'2015-08-16 20:47:01',532),
	(1923,0,'file:upload',1,'2015-08-16 20:47:01',532),
	(1924,0,'file:update',1,'2015-08-16 20:47:01',532),
	(1925,0,'file:solutionUpload',1,'2015-08-16 20:47:01',532),
	(1926,0,'file:solution',1,'2015-08-16 20:47:01',532),
	(1927,0,'file:myFiles',1,'2015-08-16 20:47:01',532),
	(1928,0,'file:myAvatars',0,'2015-08-16 20:47:01',532),
	(1929,0,'file:loadMaterial',1,'2015-08-16 20:47:01',532),
	(1930,0,'file:lastFiles',1,'2015-08-16 20:47:01',532),
	(1931,0,'file:getSolutions',1,'2015-08-16 20:47:01',532),
	(1932,0,'file:editMaterial',0,'2015-08-16 20:47:01',532),
	(1933,0,'file:load',1,'2015-08-15 22:24:40',532),
	(1934,0,'user:dedicate',0,'2015-08-16 20:47:01',532),
	(1936,0,'user:getInstitution',0,'2015-08-16 20:47:01',532),
	(1937,0,'user:getHelp',1,'2015-08-16 20:47:01',532),
	(1938,0,'user:getGroups',0,'2015-08-16 20:47:01',532),
	(1939,0,'user:getGroupMembers',1,'2015-08-16 20:47:01',532),
	(1940,0,'user:getCurricula',0,'2015-08-16 20:47:01',532),
	(1941,0,'user:expelFromInstitution',0,'2015-08-16 20:47:01',532),
	(1942,0,'user:expelFromGroup',0,'2015-08-16 20:47:01',532),
	(1943,0,'user:enroleToInstitution',0,'2015-08-16 20:47:01',532),
	(1944,0,'user:enroleToGroup',0,'2015-08-16 20:47:01',532),
	(1945,0,'user:delete',0,'2015-08-16 20:47:01',532),
	(1946,0,'user:confirmUserSidewide',0,'2015-08-16 20:47:01',532),
	(1947,0,'user:getUsers',1,'2015-08-16 20:47:01',532),
	(1948,0,'user:listNewUsers',0,'2015-08-16 20:47:01',532),
	(1949,0,'user:resetPassword',1,'2015-08-16 20:47:01',532),
	(1950,0,'plugin:useEmbeddableGoogleDocumentViewer',0,'2015-03-10 11:07:26',532),
	(1954,0,'file:uploadAvatar',1,'2015-03-10 11:07:26',532),
	(1955,0,'user:userListGroup',0,'2015-08-16 20:47:01',532),
	(1956,0,'user:userListInstitution',0,'2015-08-16 20:47:01',532),
	(1957,0,'course:setAccomplishedStatus',0,'2015-08-17 09:13:50',532),
	(1958,0,'user:userListComplete',0,'2015-08-16 20:47:01',532),
	(1959,0,'user:userList',1,'2015-08-16 20:47:01',532),
	(1960,0,'user:updateUser',1,'2015-08-16 20:47:01',532),
	(1961,0,'user:updateRole',0,'2015-08-16 20:47:01',532),
	(1962,0,'user:confirmUser',0,'2015-08-16 20:47:01',532),
	(1963,0,'user:changePassword',1,'2015-08-16 20:47:01',532),
	(1964,0,'menu:readRole',0,'2015-08-16 20:47:01',532),
	(1965,0,'page:showAdminDocu',0,'2015-08-16 20:47:01',532),
	(1966,0,'objectives:updateTerminalObjectives',0,'2015-08-16 20:47:01',532),
	(1967,0,'objectives:updateEnablingObjectives',0,'2015-08-16 20:47:01',532),
	(1968,0,'objectives:setStatus',0,'2015-08-16 20:47:01',532),
	(1970,0,'objectives:deleteTerminalObjectives',0,'2015-08-16 20:47:01',532),
	(1971,0,'objectives:deleteEnablingObjectives',0,'2015-08-16 20:47:01',532),
	(1972,0,'objectives:addTerminalObjective',0,'2015-08-16 20:47:01',532),
	(1973,0,'objectives:addEnablingObjective',0,'2015-08-16 20:47:01',532),
	(1974,0,'menu:readuserImport',0,'2015-08-16 20:47:01',532),
	(1975,0,'menu:readUser',0,'2015-08-16 20:47:01',532),
	(1976,0,'menu:readSubject',0,'2015-08-16 20:47:01',532),
	(1977,0,'menu:readSemester',0,'2015-08-16 20:47:01',532),
	(1978,0,'page:showCronjob',0,'2015-08-16 20:47:01',532),
	(1979,0,'page:showStudentDocu',1,'2015-08-16 20:47:01',532),
	(1980,0,'subject:update',0,'2015-08-16 20:47:01',532),
	(1981,0,'semester:update',0,'2015-08-16 20:47:01',532),
	(1982,0,'user:addUser',0,'2015-08-16 20:47:01',532),
	(1983,0,'semester:delete',0,'2015-08-16 20:47:01',532),
	(1984,0,'semester:add',0,'2015-08-16 20:47:01',532),
	(1985,0,'subject:delete',0,'2015-08-16 20:47:01',532),
	(1986,0,'schooltype:update',0,'2015-08-16 20:47:01',532),
	(1987,0,'schooltype:delete',0,'2015-08-16 20:47:01',532),
	(1988,0,'schooltype:add',0,'2015-08-16 20:47:01',532),
	(1989,0,'role:update',0,'2015-08-16 20:47:01',532),
	(1990,0,'role:delete',0,'2015-08-16 20:47:01',532),
	(1991,0,'role:add',0,'2015-08-16 20:47:01',532),
	(1992,0,'subject:add',0,'2015-08-16 20:47:01',532),
	(2119,7,'plugin:useEmbeddableGoogleDocumentViewer',1,'2015-03-10 11:07:26',532),
	(2120,1,'help:add',1,'2016-10-24 08:27:14',532),
	(2121,1,'help:delete',1,'2016-10-24 08:27:14',532),
	(2122,1,'help:update',1,'2016-10-24 08:27:14',532),
	(2123,8,'file:loadMaterial',1,'2016-11-13 13:23:01',532),
	(2124,8,'groups:showAccomplished',0,'2016-11-13 13:23:01',532),
	(2125,1,'course:selfAssessment',1,'2016-11-14 13:34:52',532),
	(2126,0,'course:selfAssessment',1,'2016-11-15 08:22:13',532),
	(2127,1,'content:add',1,'2016-11-11 11:47:07',532),
	(2128,1,'content:update',1,'2016-11-11 11:47:07',532),
	(2129,1,'content:delete',1,'2016-11-11 11:47:07',532),
	(2130,5,'content:add',1,'2016-11-11 11:47:07',532),
	(2131,5,'content:update',1,'2016-11-11 11:47:07',532),
	(2132,5,'content:delete',1,'2016-11-11 11:47:07',532),
	(2133,0,'wallet:add',0,'2016-11-11 11:47:07',532),
	(2134,8,'wallet:add',0,'2016-11-11 11:47:07',532),
	(2135,1,'wallet:add',1,'2016-11-11 11:47:07',532),
	(2136,5,'wallet:add',1,'2016-11-11 11:47:07',532),
	(2137,6,'wallet:add',1,'2016-11-11 11:47:07',532),
	(2138,7,'wallet:add',1,'2016-11-11 11:47:07',532),
	(2139,0,'wallet:update',0,'2016-11-11 11:47:07',532),
	(2140,8,'wallet:update',0,'2016-11-11 11:47:07',532),
	(2141,1,'wallet:update',1,'2016-11-11 11:47:07',532),
	(2142,5,'wallet:update',1,'2016-11-11 11:47:07',532),
	(2143,6,'wallet:update',1,'2016-11-11 11:47:07',532),
	(2144,7,'wallet:update',1,'2016-11-11 11:47:07',532),
	(2145,0,'wallet:delete',0,'2016-11-11 11:47:07',532),
	(2146,8,'wallet:delete',0,'2016-11-11 11:47:07',532),
	(2147,1,'wallet:delete',1,'2016-11-11 11:47:07',532),
	(2148,5,'wallet:delete',0,'2016-11-11 11:47:07',532),
	(2149,6,'wallet:delete',0,'2016-11-11 11:47:07',532),
	(2150,7,'wallet:delete',0,'2016-11-11 11:47:07',532),
	(2151,0,'wallet:workon',1,'2016-11-11 11:47:07',532),
	(2152,8,'wallet:workon',0,'2016-11-11 11:47:07',532),
	(2153,1,'wallet:workon',1,'2016-11-11 11:47:07',532),
	(2154,5,'wallet:workon',1,'2016-11-11 11:47:07',532),
	(2155,6,'wallet:workon',1,'2016-11-11 11:47:07',532),
	(2156,7,'wallet:workon',1,'2016-11-11 11:47:07',532),
	(2157,0,'menu:readWallet',1,'2016-11-11 11:47:07',532),
	(2158,1,'menu:readWallet',1,'2016-11-11 11:47:07',532),
	(2159,5,'menu:readWallet',1,'2016-11-11 11:47:07',532),
	(2160,6,'menu:readWallet',1,'2016-11-11 11:47:07',532),
	(2161,7,'menu:readWallet',1,'2016-11-11 11:47:07',532),
	(2162,8,'menu:readWallet',1,'2016-11-11 11:47:07',532),
	(2163,1,'wallet:share',1,'2016-11-11 11:47:07',532),
	(2164,1,'comment:add',1,'2016-11-11 11:47:07',532),
	(2165,1,'comment:update',1,'2016-11-11 11:47:07',532),
	(2166,1,'comment:delete',1,'2016-11-11 11:47:07',532),
	(2167,1,'install:dedicate',1,'2016-11-11 11:47:07',532);

/*!40000 ALTER TABLE `role_capabilities` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(250) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `order_id` tinyint(4) unsigned DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `rel_ro_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `role`, `description`, `creation_time`, `creator_id`, `order_id`)
VALUES
	(0,'Schüler','Schülerrolle','2015-08-14 19:40:11',532,4),
	(1,'Administrator','Benutzer hat alle Rechte','2013-08-09 07:06:00',532,0),
	(5,'Creator','Erstellt Lehrpläne','2015-03-10 11:07:26',532,1),
	(6,'Schuladmin','Verwaltet Zugänge an der eigenen Institution','2015-03-10 11:07:57',532,2),
	(7,'Lehrer','Lehrer an einer Institution','2015-03-10 11:08:32',532,3),
	(8,'Gast','Gast','2016-11-13 13:23:01',532,99);

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle schooltype
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schooltype`;

CREATE TABLE `schooltype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `schooltype` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `country_id` char(2) NOT NULL DEFAULT 'DE',
  `state_id` int(11) unsigned NOT NULL DEFAULT '11',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_sc_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `schooltype` WRITE;
/*!40000 ALTER TABLE `schooltype` DISABLE KEYS */;

INSERT INTO `schooltype` (`id`, `schooltype`, `description`, `country_id`, `state_id`, `creation_time`, `creator_id`)
VALUES
	(1,'Realschule plus','Realschule plus','DE',11,'0000-00-00 00:00:00',532),
	(2,'IGS','Integrierte Gesamtschule','DE',11,'0000-00-00 00:00:00',532),
	(3,'Gymnasium','Gymnasium','DE',11,'0000-00-00 00:00:00',532),
	(5,'Förderschule','','DE',11,'0000-00-00 00:00:00',532),
	(6,'Hauptschule','Hauptschule','DE',11,'0000-00-00 00:00:00',532),
	(8,'Universität','Koblenz-Landau','DE',11,'0000-00-00 00:00:00',532),
	(9,'Pädagogisches Landesinstitut','PL','DE',11,'2015-08-14 20:42:56',532),
	(10,'Gesamtschule','Gesamtschule','DE',11,'2016-01-25 14:46:41',532),
	(12,'Einrichtung','Medienbildung','DE',11,'2016-07-07 09:20:42',532),
	(13,'Grundschule','Grundschule','DE',11,'2016-11-13 13:57:12',532);

/*!40000 ALTER TABLE `schooltype` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle semester
# ------------------------------------------------------------

DROP TABLE IF EXISTS `semester`;

CREATE TABLE `semester` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `semester` varchar(64) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `begin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `institution_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_se_institution` (`institution_id`),
  KEY `rel_se_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `semester` WRITE;
/*!40000 ALTER TABLE `semester` DISABLE KEYS */;

INSERT INTO `semester` (`id`, `semester`, `description`, `begin`, `end`, `creation_time`, `creator_id`, `institution_id`)
VALUES
	(30,'Schuljahr 2015/16','Medienkompass','2015-08-27 00:00:00','2016-02-24 00:00:00','2015-08-16 21:34:23',532,56);

/*!40000 ALTER TABLE `semester` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle state
# ------------------------------------------------------------

DROP TABLE IF EXISTS `state`;

CREATE TABLE `state` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(200) DEFAULT '',
  `country_code` char(2) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rel_st_country` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `state` WRITE;
/*!40000 ALTER TABLE `state` DISABLE KEYS */;

INSERT INTO `state` (`id`, `state`, `country_code`, `creation_time`)
VALUES
	(2,'Bayern','DE','2013-08-09 09:05:18'),
	(3,'Berlin','DE','2013-08-09 09:05:18'),
	(4,'Brandenburg','DE','2013-08-09 09:05:18'),
	(5,'Bremen','DE','2013-08-09 09:05:18'),
	(6,'Hamburg','DE','2013-08-09 09:05:18'),
	(7,'Hessen','DE','2013-08-09 09:05:18'),
	(8,'Mecklenburg-Vorpommern','DE','2013-08-09 09:05:18'),
	(9,'Niedersachsen','DE','2013-08-09 09:05:18'),
	(10,'Nordrhein-Westfalen','DE','2013-08-09 09:05:18'),
	(11,'Rheinland-Pfalz','DE','2013-08-09 09:05:18'),
	(12,'Saarland','DE','2013-08-09 09:05:18'),
	(13,'Sachsen','DE','2013-08-09 09:05:18'),
	(14,'Sachsen-Anhalt','DE','2013-08-09 09:05:18'),
	(15,'Schleswig-Holstein','DE','2013-08-09 09:05:18'),
	(17,'Thueringen','DE','2013-08-09 09:05:18'),
	(18,'Baden-Wuerttemberg','DE','2013-08-09 09:05:18'),
	(19,'Burgenland','AT','2013-08-09 09:05:18'),
	(20,'Kärnten','AT','2013-08-09 09:05:18'),
	(21,'Niederösterreich','AT','2013-08-09 09:05:18'),
	(22,'Oberösterreich','AT','2013-08-09 09:05:18'),
	(23,'Salzburg','AT','2013-08-09 09:05:18'),
	(24,'Steiermark','AT','2013-08-09 09:05:18'),
	(25,'Tirol','AT','2013-08-09 09:05:18'),
	(26,'Voralberg','AT','2013-08-09 09:05:18'),
	(27,'Wien','AT','2013-08-09 09:05:18');

/*!40000 ALTER TABLE `state` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle subjects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `subjects`;

CREATE TABLE `subjects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `institution_id` int(10) unsigned NOT NULL,
  `subject_short` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_su_creator` (`creator_id`),
  KEY `rel_su_institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;

INSERT INTO `subjects` (`id`, `subject`, `description`, `creation_time`, `creator_id`, `institution_id`, `subject_short`)
VALUES
	(1,'Mathematik','Unterrichtsfach Mathematik','2013-08-09 09:05:42',532,56,'MA'),
	(2,'Deutsch','Unterrichtsfach Deutsch','2013-08-09 09:05:42',532,56,'DE'),
	(3,'Englisch','Unterrichtsfach Englisch','2013-08-09 09:05:42',532,56,'EN'),
	(5,'Musik','Unterrichtsfach Musik','2013-08-09 09:05:42',532,56,'MU'),
	(6,'Physik','Unterrichtsfach Physik','2013-08-09 09:05:42',532,56,'PH'),
	(7,'Biologie','Unterrichtsfach Biologie','2013-08-09 09:05:42',532,56,'BIO'),
	(8,'Chemie','Unterrichtsfach Chemie','2013-08-09 09:05:42',532,56,'CH'),
	(9,'Erdkunde','Unterrichtsfach Erdkunde','2013-08-09 09:05:42',532,56,'EK'),
	(10,'Sozialkunde','Unterrichtsfach Sozialkunde','2013-08-09 09:05:42',532,56,'SOZ'),
	(11,'Geschichte','Unterrichtsfach Geschichte','2013-08-09 09:05:42',532,56,'G'),
	(12,'Medienbildung','Medienkompass Rheinland-Pfalz','2013-08-09 09:05:42',532,56,'INF'),
	(13,'Kunst','Unterrichtsfach Bildende Kunst','2013-08-09 09:05:42',532,56,'BK'),
	(14,'Sport','Unterrichtsfach Sport','2013-08-09 09:05:42',532,56,'SP'),
	(16,'Französisch','Unterrichtsfach Französisch','2013-08-09 09:05:42',532,56,'FR'),
	(17,'Erdkunde - Geschichte - Sozialkunde','Gemeinsames Unterrichtsfach Erdkunde - Geschichte - Sozialkunde','2013-08-09 09:05:42',532,56,'EGS'),
	(36,'Naturwissenschaften','Gemeinsames Unterrichtsfach Biologie - Physik - Chemie','2013-08-17 17:06:20',532,56,'NAWI');

/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task` varchar(2048) DEFAULT '',
  `description` text,
  `creation_time` timestamp NULL DEFAULT NULL,
  `timestart` timestamp NULL DEFAULT NULL,
  `timeend` timestamp NULL DEFAULT NULL,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ta_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle task_enrolments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task_enrolments`;

CREATE TABLE `task_enrolments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `creator_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_te_task` (`task_id`),
  KEY `rel_te_contex` (`context_id`),
  KEY `rel_te_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle terminalObjectives
# ------------------------------------------------------------

DROP TABLE IF EXISTS `terminalObjectives`;

CREATE TABLE `terminalObjectives` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `terminal_objective` text,
  `description` text,
  `curriculum_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `order_id` tinyint(4) NOT NULL DEFAULT '0',
  `repeat_interval` int(11) unsigned NOT NULL DEFAULT '0',
  `color` char(9) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_to_curriculum` (`curriculum_id`),
  KEY `rel_to_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `terminalObjectives` WRITE;
/*!40000 ALTER TABLE `terminalObjectives` DISABLE KEYS */;

INSERT INTO `terminalObjectives` (`id`, `terminal_objective`, `description`, `curriculum_id`, `creation_time`, `creator_id`, `order_id`, `repeat_interval`, `color`)
VALUES
	(324,'Informieren und Recherchieren  - TK 1 Fundierte Medienrecherchen durchführen und neue Informationsquellen erschließen','<p>TK 1 Fundierte Medienrecherchen durchf&uuml;hren und neue Informationsquellen erschlie&szlig;en</p>\r\n<p>- Rahmenplan IB: nutzen gezielt verschiedene Dienste im Internet, stellen Entstehung, Strukturen und aktuelle Entwicklungen des Internets dar. - erl&auml;utern technische Grundvoraussetzungen des Internetzugangs.(B&amp;A)</p>',109,'2016-01-14 20:13:29',532,1,0,'#4A88CB'),
	(325,'Informieren und Recherchieren - TK 2 Unter Beachtung des Urheberrechtes korrekt mit Zitaten und Quellen umgehen','<p>TK 2 Unter Beachtung des Urheberrechtes korrekt mit Zitaten und Quellen umgehen</p>',109,'2016-01-14 20:13:29',532,2,0,'#4a88cb'),
	(326,'Informieren und Recherchieren - TK 3 Inhalt, Struktur, Darstellung und Zielrichtung von digitalen Informationsquellen vergleichen, analysieren und bewerten','<p>TK 3 Inhalt, Struktur, Darstellung und Zielrichtung von digitalen Informationsquellen vergleichen, analysieren und bewerten</p>\r\n<p>Bezug: Rahmenplan IB RLP 7/8</p>',109,'2016-01-14 20:13:29',532,3,0,'#4a88cb'),
	(327,'Informieren und Recherchieren - TK 4 Themenrelevante Informationen filtern, strukturieren und unter Beachtung der Rechte aufbereiten','<p>TK 4 Themenrelevante Informationen filtern, strukturieren und unter Beachtung der Rechte aufbereiten</p>',109,'2016-01-14 20:13:29',532,4,0,'#4a88cb'),
	(328,'Kommunizieren und Kooperieren - TK 1 Verschiedene Kommunikationswege und –werkzeuge kennen und für die eigenen Zwecke und Ziele nutzen','<p>TK 1 Verschiedene Kommunikationswege und &ndash;werkzeuge kennen und f&uuml;r die eigenen Zwecke und Ziele nutzen</p>',109,'2016-01-14 20:13:29',532,5,0,'#DBECD0'),
	(329,'Kommunizieren und Kooperieren - TK 2 Empfehlungen und Regeln zum Schutz der eigenen Daten und zur Achtung von Persönlichkeitsrechten anwenden','<p>TK 2 Empfehlungen und Regeln zum Schutz der eigenen Daten und zur Achtung von Pers&ouml;nlichkeitsrechten anwenden</p>',109,'2016-01-14 20:13:29',532,6,0,'#DBECD0'),
	(330,'Kommunizieren und Kooperieren TK 3 Nachrichten und komplexe Botschaften verfassen und versenden / veröffentlichen','<p>TK 3 Nachrichten und komplexe Botschaften verfassen und versenden / ver&ouml;ffentlichen</p>',109,'2016-01-14 20:13:29',532,7,0,'#DBECD0'),
	(331,'Kommunizieren und Kooperieren TK 4 Kommunikationsregeln anwenden, Botschaften auswerten und angemessen Rückmeldung geben.','<p>TK 4 Kommunikationsregeln anwenden, Botschaften auswerten und angemessen R&uuml;ckmeldung geben.</p>',109,'2016-01-14 20:13:29',532,8,0,'#DBECD0'),
	(332,'Produzieren und Präsentieren - TK 1 Medienproduktionen angeleitet planen','<p>TK 1 Medienproduktionen angeleitet planen</p>',109,'2016-01-14 20:13:29',532,9,0,'#F4BE9C'),
	(333,'Produzieren und Präsentieren TK 2 Ein Medienprodukt angeleitet erstellen und unterschiedliche Gestaltungselemente bewusst und zielgruppenorientiert einsetzen','<p>TK 2 Ein Medienprodukt angeleitet erstellen und unterschiedliche Gestaltungselemente bewusst und zielgruppenorientiert einsetzen</p>',109,'2016-01-14 20:13:29',532,10,0,'#F4BE9C'),
	(334,'Produzieren und Präsentieren TK 3 Passende Präsentationsform aus einem vorgegebenen Repertoire auswählen und anwenden, Präsentationsregeln beachten','<p>TK 3 Passende Pr&auml;sentationsform aus einem vorgegebenen Repertoire ausw&auml;hlen und anwenden, Pr&auml;sentationsregeln beachten</p>',109,'2016-01-14 20:13:30',532,11,0,'#F4BE9C'),
	(335,'Produzieren und Präsentieren TK 4 Möglichkeiten einer digitalen Veröffentlichung kennen und mit Hilfestellung nutzen','<p>TK 4 M&ouml;glichkeiten einer digitalen Ver&ouml;ffentlichung kennen und mit Hilfestellung nutzen</p>',109,'2016-01-14 20:13:30',532,12,0,'#F4BE9C'),
	(336,'Analysieren und Reflektieren TK 1 Einfluss und Wirkung typischer Darstellungsformen und Stilmittel analysieren und bewerten','<p>TK 1 Einfluss und Wirkung typischer Darstellungsformen und Stilmittel analysieren und bewerten</p>',109,'2016-01-14 20:13:30',532,13,0,'#99C87B'),
	(337,'Analysieren und Reflektieren TK 2 Durch Medien vermittelte Werte, Rollen- und Wirklichkeitsvorstellungen analysieren und hinterfragen','<p>TK 2 Durch Medien vermittelte Werte, Rollen- und Wirklichkeitsvorstellungen analysieren und hinterfragen</p>',109,'2016-01-14 20:13:30',532,14,0,'#99C87B'),
	(338,'Analysieren und Reflektieren TK 3 Sich die eigene Mediennutzung bewusst machen, hinterfragen, einordnen und im Bedarfsfall Beratungsangebote nutzen','<p>TK 3 Sich die eigene Mediennutzung bewusst machen, hinterfragen, einordnen und im Bedarfsfall Beratungsangebote nutzen</p>',109,'2016-01-14 20:13:30',532,15,0,'#99C87B'),
	(339,'Analysieren und Reflektieren TK 4 Entwicklung und wirtschaftliche, gesellschaftliche und politische Bedeutung der Massenmedien und reflektieren','<p>TK 4 Entwicklung und wirtschaftliche, gesellschaftliche und politische Bedeutung der Massenmedien und reflektieren</p>',109,'2016-01-14 20:13:30',532,16,0,'#99C87B'),
	(340,'Bedienen und Anwenden TK 1 Ein Betriebssystem bedienen und konfigurieren (Installation von Software, Dateiverwaltung)','<p>TK 1 Ein Betriebssystem bedienen und konfigurieren (Installation von Software, Dateiverwaltung)</p>',109,'2016-01-14 20:13:30',532,17,0,'#D0D0D0'),
	(341,'Bedienen und Anwenden TK 2 Erweiterte Funktionen von Textverarbeitungs-, Präsentations- und Bildbearbeitungsprogrammen anwenden','<p>TK 2 Erweiterte Funktionen von Textverarbeitungs-, Pr&auml;sentations- und Bildbearbeitungsprogrammen anwenden</p>\r\n<p>Bezug: Rahmenplan IB RLP 7/8</p>',109,'2016-01-14 20:13:30',532,18,0,'#D0D0D0'),
	(342,'Bedienen und Anwenden TK 3 Tabellenkalkulationsprogramme anwenden','<p>TK 3 Tabellenkalkulationsprogramme anwenden</p>\r\n<p>Bezug: Rahmenplan IB RLP 7/8</p>',109,'2016-01-14 20:13:30',532,19,0,'#D0D0D0');

/*!40000 ALTER TABLE `terminalObjectives` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle user_absent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_absent`;

CREATE TABLE `user_absent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cb_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `reason` varchar(2048) DEFAULT '',
  `done` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ub_coursebook` (`cb_id`),
  KEY `rel_ub_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle user_accomplished
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_accomplished`;

CREATE TABLE `user_accomplished` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reference_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `status_id` char(2) DEFAULT NULL COMMENT '0=rot, 1 = grün, 2 = orange, 3 = weiß',
  `accomplished_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ua_enabling_objective` (`reference_id`),
  KEY `rel_ua_user` (`user_id`),
  KEY `rel_ua_creator` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle user_list
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_list`;

CREATE TABLE `user_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reference_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 = not present; 1 = present / active',
  `context_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rel_ul_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  `email` text NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = User kann sich Anmelden 2 = nicht benutzt 3 = Passwort ändern  4 = User noch nicht von Admin freigegeben',
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `postalcode` text,
  `city` text,
  `state_id` int(11) unsigned DEFAULT NULL,
  `country_id` int(11) unsigned DEFAULT NULL,
  `avatar_id` int(11) unsigned DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  `paginator_limit` smallint(6) DEFAULT NULL,
  `acc_days` smallint(6) DEFAULT NULL,
  `semester_id` int(10) unsigned DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_USER_ID` (`id`),
  KEY `rel_us_state` (`state_id`),
  KEY `rel_us_country` (`country_id`),
  KEY `rel_us_avatar` (`avatar_id`),
  KEY `rel_us_creator` (`creator_id`),
  KEY `rel_us_semester` (`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `username`, `password`, `last_login`, `last_action`, `email`, `confirmed`, `firstname`, `lastname`, `postalcode`, `city`, `state_id`, `country_id`, `avatar_id`, `creation_time`, `creator_id`, `paginator_limit`, `acc_days`, `semester_id`, `token`)
VALUES
	(374,'michaellang','PW will be set during install','2016-11-16 09:17:52','2016-11-16 08:55:10','michaellang@joachimdieterich.de',1,'Michaeld','Lang','','',11,56,0,'2016-01-26 22:53:17',532,30,7,NULL,'32CFB961B757452731EDEA1299816CDD'),
	(375,'nataschajessen','PW will be set during install','2016-12-14 19:42:07','2016-12-14 19:36:17','nataschajessen@joachimdieterich.de',1,'Natascha','Jessen','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,30,'86B34781658993C8928C6FD87BF06978'),
	(376,'elkesieburg','PW will be set during install',NULL,NULL,'elkesieburg@joachimdieterich.de',1,'Elke','Sieburg','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(377,'guentherrohe','PW will be set during install',NULL,NULL,'guentherrohe@joachimdieterich.de',1,'Günther','Rohe','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(378,'margarethesixtus','PW will be set during install',NULL,NULL,'margarethesixtus@joachimdieterich.de',1,'Margarethe','Sixtus','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(379,'mathiasaustrup','PW will be set during install',NULL,NULL,'mathiasaustrup@joachimdieterich.de',1,'Mathias','Austrup','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(380,'baerbeltretter','PW will be set during install',NULL,NULL,'baerbeltretter@joachimdieterich.de',1,'Bärbel','Tretter','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(381,'miriammengel','PW will be set during install',NULL,NULL,'miriammengel@joachimdieterich.de',1,'Miriam','Mengel','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(382,'hellastoffers','PW will be set during install','2016-11-16 09:27:14','2016-11-16 09:31:36','hellastoffers@joachimdieterich.de',1,'Hella','Stoffers','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,'6F6D1C09C158587B8E65F0D4B6D6A276'),
	(383,'gretakreimer','PW will be set during install',NULL,NULL,'gretakreimer@joachimdieterich.de',1,'Greta','Kreimer','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(384,'steffenbierig','PW will be set during install','2016-05-26 08:49:24','2016-05-26 08:49:58','steffenbierig@joachimdieterich.de',1,'Steffen','Bierig','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,'37FA708EB28E13806994DEDFC609ED64'),
	(385,'moniquekunert','PW will be set during install',NULL,NULL,'moniquekunert@joachimdieterich.de',1,'Monique','Kunert','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(386,'tabeafrohn','PW will be set during install','2016-03-01 12:49:02','2016-03-01 12:49:02','tabeafrohn@joachimdieterich.de',1,'Tabea','Frohn','','',11,56,0,'2016-03-25 10:23:17',532,30,7,30,'99077633691B05F26DDA41C768559612'),
	(387,'kathrinarendt','PW will be set during install',NULL,NULL,'kathrinarendt@joachimdieterich.de',1,'Kathrin','Arendt','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(388,'helgaroehr','PW will be set during install',NULL,NULL,'helgaroehr@joachimdieterich.de',1,'Helga','Röhr','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(389,'stevenpape','PW will be set during install','2016-12-07 09:34:29','2016-12-07 09:07:03','stevenpape@joachimdieterich.de',1,'Steven','Pape','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,30,'FDFAAF2CFA32712680519E9F5E3D259B'),
	(390,'wilhelmstrehlow','PW will be set during install',NULL,NULL,'wilhelmstrehlow@joachimdieterich.de',1,'Wilhelm','Strehlow','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(391,'kristamellinghoff','PW will be set during install','2016-12-07 09:39:47','2016-12-07 09:11:16','kristamellinghoff@joachimdieterich.de',1,'Krista','Mellinghoff','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,30,'3AEA235EA11636700307193C7EBFCAAE'),
	(392,'franzrothermel','PW will be set during install',NULL,NULL,'franzrothermel@joachimdieterich.de',1,'Franz','Rothermel','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(393,'heinzjochmann','PW will be set during install',NULL,NULL,'heinzjochmann@joachimdieterich.de',1,'Heinz','Jochmann','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(394,'anjamutschler','PW will be set during install',NULL,NULL,'anjamutschler@joachimdieterich.de',1,'Anja','Mutschler','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(395,'jasperneufeld','PW will be set during install',NULL,NULL,'jasperneufeld@joachimdieterich.de',1,'Jasper','Neufeld','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(396,'timonwilliams','PW will be set during install',NULL,NULL,'timonwilliams@joachimdieterich.de',1,'Timon','Williams','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(397,'brittastuber','PW will be set during install',NULL,NULL,'brittastuber@joachimdieterich.de',1,'Britta','Stuber','','',11,56,0,'2016-01-26 22:53:17',532,NULL,NULL,NULL,NULL),
	(531,'gast','PW will be set during install','2016-12-15 22:38:05','2016-12-15 22:31:33','test@joachimdieterich.de',1,'Demo','Zugang','','',11,56,0,'2016-11-13 13:24:38',532,30,7,NULL,'F5E634A88A22435F5E7D46F34C0BC17D');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle wallet
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wallet`;

CREATE TABLE `wallet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(255) DEFAULT NULL,
  `description` text,
  `file_id` int(11) unsigned DEFAULT NULL,
  `course_id` int(11) unsigned DEFAULT NULL,
  `user_list_id` int(11) unsigned DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timestart` timestamp NULL DEFAULT NULL,
  `timeend` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle wallet_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wallet_content`;

CREATE TABLE `wallet_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(25) DEFAULT NULL,
  `wallet_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL,
  `width_class` char(255) NOT NULL DEFAULT 'col-xs-12',
  `position` char(255) NOT NULL,
  `order_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle wallet_objectives
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wallet_objectives`;

CREATE TABLE `wallet_objectives` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Export von Tabelle wallet_sharing
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wallet_sharing`;

CREATE TABLE `wallet_sharing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned NOT NULL,
  `permission` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0 = read; 1= comment; 2 = write',
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timestart` timestamp NULL DEFAULT NULL,
  `timeend` timestamp NULL DEFAULT NULL,
  `creator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
