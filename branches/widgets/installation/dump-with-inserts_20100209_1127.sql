-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.42


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema zoolu
--

CREATE DATABASE IF NOT EXISTS zoolu;
USE zoolu;

--
-- Definition of table `zoolu`.`actions`
--

DROP TABLE IF EXISTS `zoolu`.`actions`;
CREATE TABLE  `zoolu`.`actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`actions`
--

/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
LOCK TABLES `actions` WRITE;
INSERT INTO `zoolu`.`actions` VALUES  (1,'add'),
 (2,'edit'),
 (3,'change_template'),
 (4,'change_template_id');
UNLOCK TABLES;
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`categories`
--

DROP TABLE IF EXISTS `zoolu`.`categories`;
CREATE TABLE  `zoolu`.`categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idParentCategory` bigint(20) unsigned NOT NULL DEFAULT '0',
  `idRootCategory` bigint(20) unsigned DEFAULT NULL,
  `idCategoryTypes` bigint(20) unsigned NOT NULL,
  `matchCode` varchar(255) DEFAULT NULL,
  `lft` int(10) unsigned DEFAULT NULL,
  `rgt` int(10) unsigned DEFAULT NULL,
  `depth` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `idRootCategory` (`idRootCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`categories`
--

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
LOCK TABLES `categories` WRITE;
INSERT INTO `zoolu`.`categories` VALUES  (1,0,1,1,NULL,1,12,0),
 (11,0,11,2,NULL,1,20,0),
 (12,11,11,2,NULL,2,13,1),
 (13,11,11,2,NULL,14,19,1),
 (14,13,11,2,'DESC',15,16,2),
 (15,13,11,2,'ASC',17,18,2),
 (16,12,11,2,'alpha',3,4,2),
 (17,12,11,2,'sort',5,6,2),
 (18,12,11,2,'created',7,8,2),
 (19,12,11,2,'changed',9,10,2),
 (21,0,21,3,NULL,1,8,0),
 (27,0,27,2,NULL,1,14,0),
 (28,27,27,2,'col-1',2,3,1),
 (29,27,27,2,'col-1-img',4,5,1),
 (30,27,27,2,'col-2',6,7,1),
 (31,27,27,2,'col-2-img',8,9,1),
 (35,27,27,2,'list',10,11,1),
 (36,27,27,2,'list-img',12,13,1),
 (40,12,11,2,'published',11,12,2),
 (42,0,42,2,NULL,1,4,0),
 (43,42,42,2,'similar_pages',2,3,1),
 (48,0,48,2,NULL,1,10,0),
 (49,48,48,2,NULL,2,9,1),
 (50,49,48,2,NULL,3,4,2),
 (51,49,48,2,NULL,5,6,2),
 (52,49,48,2,NULL,7,8,2),
 (53,1,1,1,NULL,6,7,1),
 (54,1,1,1,NULL,8,9,1),
 (55,1,1,1,NULL,10,11,1),
 (56,21,21,3,NULL,2,3,1),
 (60,21,21,3,NULL,4,5,1),
 (63,21,21,3,NULL,6,7,1),
 (64,0,64,2,NULL,1,6,0),
 (65,64,64,2,NULL,2,3,1),
 (66,64,64,2,NULL,4,5,1),
 (67,0,67,1,NULL,1,6,0),
 (68,67,67,1,NULL,2,3,1),
 (69,67,67,1,NULL,4,5,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`categoryTitles`
--

DROP TABLE IF EXISTS `zoolu`.`categoryTitles`;
CREATE TABLE  `zoolu`.`categoryTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idCategories` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(500) DEFAULT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idCategories` (`idCategories`),
  CONSTRAINT `categoryTitles_ibfk_1` FOREIGN KEY (`idCategories`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`categoryTitles`
--

/*!40000 ALTER TABLE `categoryTitles` DISABLE KEYS */;
LOCK TABLES `categoryTitles` WRITE;
INSERT INTO `zoolu`.`categoryTitles` VALUES  (1,1,1,'Seiten Kategorien',0,'0000-00-00 00:00:00'),
 (11,11,1,'Sortierung',0,'0000-00-00 00:00:00'),
 (12,12,1,'Sortierarten',0,'0000-00-00 00:00:00'),
 (13,13,1,'Reihenfolge',0,'0000-00-00 00:00:00'),
 (14,14,1,'absteigend',0,'0000-00-00 00:00:00'),
 (15,15,1,'aufsteigend',0,'0000-00-00 00:00:00'),
 (16,16,1,'Alphabet',0,'0000-00-00 00:00:00'),
 (17,17,1,'Sortierung',0,'0000-00-00 00:00:00'),
 (18,18,1,'Erstelldatum',0,'0000-00-00 00:00:00'),
 (19,19,1,'Änderungsdatum',0,'0000-00-00 00:00:00'),
 (21,21,1,'Seiten Etiketten',0,'0000-00-00 00:00:00'),
 (27,27,1,'Darstellungsformen',0,'0000-00-00 00:00:00'),
 (28,28,1,'1-spaltig ohne Bilder',0,'0000-00-00 00:00:00'),
 (29,29,1,'1-spaltig mit Bilder',0,'0000-00-00 00:00:00'),
 (30,30,1,'2-spaltig ohne Bilder',0,'0000-00-00 00:00:00'),
 (31,31,1,'2-spaltig mit Bilder',0,'0000-00-00 00:00:00'),
 (35,35,1,'Liste ohne Bilder',0,'0000-00-00 00:00:00'),
 (36,36,1,'Liste mit Bilder',0,'0000-00-00 00:00:00'),
 (40,40,1,'Veröffentlichungsdatum',0,'0000-00-00 00:00:00'),
 (42,42,1,'Darstellungsoptionen',0,'0000-00-00 00:00:00'),
 (43,43,1,'Ähnliche Seiten anzeigen',0,'0000-00-00 00:00:00'),
 (48,48,1,'Status',0,'0000-00-00 00:00:00'),
 (49,49,1,'Veranstaltung',0,'0000-00-00 00:00:00'),
 (50,50,1,'Anmeldung offen',0,'0000-00-00 00:00:00'),
 (51,51,1,'Wenige Restplätze',0,'0000-00-00 00:00:00'),
 (52,52,1,'Ausgebucht',0,'0000-00-00 00:00:00'),
 (53,53,1,'Test 1',0,'0000-00-00 00:00:00'),
 (54,54,1,'Test 2',0,'0000-00-00 00:00:00'),
 (55,55,1,'Test 3',0,'0000-00-00 00:00:00'),
 (56,56,1,'Test 1',0,'0000-00-00 00:00:00'),
 (59,54,2,'Test EN 2',0,'0000-00-00 00:00:00'),
 (60,55,2,'Test EN 3',0,'0000-00-00 00:00:00'),
 (61,53,2,'Test EN 1',3,'2009-06-09 19:07:59'),
 (62,53,2,'Test EN 1',3,'2009-06-09 19:07:59'),
 (63,56,2,'Test 1 EN',0,'0000-00-00 00:00:00'),
 (73,60,1,'Test 2',0,'0000-00-00 00:00:00'),
 (76,63,1,'Test 3',3,'2009-06-09 11:07:55'),
 (77,63,2,'Test 3 EN',3,'2009-06-09 11:07:52'),
 (78,60,2,'Test 2 EN',3,'2009-06-09 11:08:59'),
 (79,64,1,'Sub-Navigations-Seiten',3,'2009-06-23 10:27:54'),
 (80,65,1,'nicht miteinbeziehen',3,'2009-06-23 10:28:09'),
 (81,66,1,'miteinbeziehen',3,'2009-06-23 10:28:34'),
 (82,67,1,'Blog Kategorien',0,'2009-12-29 15:17:38'),
 (83,68,1,'Blog 1',6,'2009-12-29 15:20:31'),
 (84,69,1,'Blog 2',6,'2009-12-29 15:20:36');
UNLOCK TABLES;
/*!40000 ALTER TABLE `categoryTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`categoryTypes`
--

DROP TABLE IF EXISTS `zoolu`.`categoryTypes`;
CREATE TABLE  `zoolu`.`categoryTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`categoryTypes`
--

/*!40000 ALTER TABLE `categoryTypes` DISABLE KEYS */;
LOCK TABLES `categoryTypes` WRITE;
INSERT INTO `zoolu`.`categoryTypes` VALUES  (1,'default'),
 (2,'system'),
 (3,'label');
UNLOCK TABLES;
/*!40000 ALTER TABLE `categoryTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idContacts` bigint(20) unsigned NOT NULL,
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idContacts` (`idContacts`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `contact-DEFAULT_CONTACT-1-InstanceFiles_ibfk_1` FOREIGN KEY (`idContacts`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contact-DEFAULT_CONTACT-1-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceFiles`
--

/*!40000 ALTER TABLE `contact-DEFAULT_CONTACT-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `contact-DEFAULT_CONTACT-1-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `contact-DEFAULT_CONTACT-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idContacts` bigint(20) unsigned NOT NULL,
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idContacts` (`idContacts`),
  CONSTRAINT `contact-DEFAULT_CONTACT-1-InstanceMultiFields_ibfk_1` FOREIGN KEY (`idContacts`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`contact-DEFAULT_CONTACT-1-InstanceMultiFields`
--

/*!40000 ALTER TABLE `contact-DEFAULT_CONTACT-1-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `contact-DEFAULT_CONTACT-1-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `contact-DEFAULT_CONTACT-1-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`contact-DEFAULT_CONTACT-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`contact-DEFAULT_CONTACT-1-Instances`;
CREATE TABLE  `zoolu`.`contact-DEFAULT_CONTACT-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idContacts` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idContacts` (`idContacts`),
  CONSTRAINT `contact-DEFAULT_CONTACT-1-Instances_ibfk_1` FOREIGN KEY (`idContacts`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`contact-DEFAULT_CONTACT-1-Instances`
--

/*!40000 ALTER TABLE `contact-DEFAULT_CONTACT-1-Instances` DISABLE KEYS */;
LOCK TABLES `contact-DEFAULT_CONTACT-1-Instances` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `contact-DEFAULT_CONTACT-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`contacts`
--

DROP TABLE IF EXISTS `zoolu`.`contacts`;
CREATE TABLE  `zoolu`.`contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL COMMENT 'Person, letzte Änderung',
  `creator` bigint(20) unsigned NOT NULL,
  `idUnits` bigint(20) unsigned NOT NULL,
  `salutation` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `fname` varchar(255) NOT NULL,
  `sname` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `phone` varchar(128) DEFAULT NULL,
  `mobile` varchar(128) DEFAULT NULL,
  `fax` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `website` varchar(128) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`contacts`
--

/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
LOCK TABLES `contacts` WRITE;
INSERT INTO `zoolu`.`contacts` VALUES  (1,10,3,3,1,'','','Thomas','Schedler','','','','','','','2009-04-24 10:26:40','2009-04-24 10:26:40'),
 (2,10,3,3,1,'','','Bernd','Hepberger','','','','','','','2009-06-08 16:28:48','2009-06-08 16:28:48');
UNLOCK TABLES;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`decorators`
--

DROP TABLE IF EXISTS `zoolu`.`decorators`;
CREATE TABLE  `zoolu`.`decorators` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`decorators`
--

/*!40000 ALTER TABLE `decorators` DISABLE KEYS */;
LOCK TABLES `decorators` WRITE;
INSERT INTO `zoolu`.`decorators` VALUES  (1,'Input'),
 (2,'Template'),
 (3,'Tag'),
 (4,'Overflow'),
 (5,'Url'),
 (6,'VideoSelect'),
 (7,'Gmaps'),
 (8,'WidgetUrl');
UNLOCK TABLES;
/*!40000 ALTER TABLE `decorators` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fieldPermissions`
--

DROP TABLE IF EXISTS `zoolu`.`fieldPermissions`;
CREATE TABLE  `zoolu`.`fieldPermissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFields` bigint(20) unsigned NOT NULL,
  `idPermissions` bigint(20) unsigned NOT NULL,
  `idGroups` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fieldPermissions`
--

/*!40000 ALTER TABLE `fieldPermissions` DISABLE KEYS */;
LOCK TABLES `fieldPermissions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `fieldPermissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fieldProperties`
--

DROP TABLE IF EXISTS `zoolu`.`fieldProperties`;
CREATE TABLE  `zoolu`.`fieldProperties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFields` bigint(20) unsigned NOT NULL,
  `idProperties` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idFields` (`idFields`),
  KEY `idProperties` (`idProperties`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fieldProperties`
--

/*!40000 ALTER TABLE `fieldProperties` DISABLE KEYS */;
LOCK TABLES `fieldProperties` WRITE;
INSERT INTO `zoolu`.`fieldProperties` VALUES  (1,3,1,'2'),
 (2,4,1,'1'),
 (3,123,2,'/widget/blog/comment/delete'),
 (4,119,1,'1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `fieldProperties` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fieldTitles`
--

DROP TABLE IF EXISTS `zoolu`.`fieldTitles`;
CREATE TABLE  `zoolu`.`fieldTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFields` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(500) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `idFields` (`idFields`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fieldTitles`
--

/*!40000 ALTER TABLE `fieldTitles` DISABLE KEYS */;
LOCK TABLES `fieldTitles` WRITE;
INSERT INTO `zoolu`.`fieldTitles` VALUES  (1,1,1,'Titel',NULL),
 (2,2,1,'Beschreibung',NULL),
 (3,3,1,'Titel',NULL),
 (4,4,1,'Überschrift für den Artikel',NULL),
 (5,11,1,'Aktuelles Template',NULL),
 (6,5,1,NULL,NULL),
 (7,7,1,'',''),
 (8,8,1,NULL,NULL),
 (9,12,1,'Multiselect Test',NULL),
 (11,15,1,'Radiobuttons',NULL),
 (12,16,1,'Checkboxes',NULL),
 (13,17,1,'Titel',NULL),
 (14,20,1,'Titel',NULL),
 (15,21,1,'Tags',NULL),
 (16,22,1,'Kategorien',NULL),
 (17,24,1,'Embed Code',NULL),
 (18,23,1,'Titel',NULL),
 (19,25,1,'Titel',NULL),
 (20,26,1,'Titel',NULL),
 (21,28,1,'Verlinkte Seite',NULL),
 (22,30,1,'Titel',NULL),
 (23,31,1,'Anzahl',NULL),
 (26,37,1,'Titel',NULL),
 (29,34,1,'Nur Seiten mit Kategorie',NULL),
 (30,41,1,'Anzahl',NULL),
 (31,42,1,'Nur Seiten mit Kategorie',NULL),
 (32,43,1,'Titel',NULL),
 (33,47,1,'Sortierung nach',NULL),
 (34,48,1,'Reihenfolge',NULL),
 (35,49,1,'Sortierung nach',NULL),
 (36,50,1,'Reihenfolge',NULL),
 (37,51,1,'Sortierung nach',NULL),
 (38,52,1,'Reihenfolge',NULL),
 (39,44,1,'Anzahl',NULL),
 (40,45,1,'Navigationspunkt',NULL),
 (41,46,1,'Titel',NULL),
 (42,53,1,'Nur Seiten mit Kategorie',NULL),
 (43,54,1,'Video Service',NULL),
 (44,10,1,'Kurzbeschreibung des Artikels',NULL),
 (45,56,1,'Eigene Etiketten',NULL),
 (47,59,1,'Darstellungsform',NULL),
 (49,60,1,'Nur Seiten mit Etikett',NULL),
 (50,61,1,'Nur Seiten mit Etikett',NULL),
 (51,62,1,'Nur Seiten mit Etikett',NULL),
 (53,64,1,'Darstellungsoptionen',NULL),
 (54,65,1,'Vorname',NULL),
 (55,66,1,'Nachname',NULL),
 (56,67,1,'Datum, Zeit (Format: dd.mm.yyyy hh:mm)',NULL),
 (57,68,1,'Dauer (z.B.: 90 Minuten)',NULL),
 (58,69,1,'Strasse',NULL),
 (59,70,1,'Hausnummer',NULL),
 (60,71,1,'Postleitzahl',NULL),
 (61,72,1,'Ort',NULL),
 (62,73,1,'Schauplatz',NULL),
 (63,74,1,'Max. Teilnehmeranzahl',NULL),
 (64,75,1,'Kosten (in EUR)',NULL),
 (65,76,1,'Anrede',NULL),
 (66,77,1,'Titel',NULL),
 (67,78,1,'Funktion / Position',NULL),
 (68,79,1,'Telefon',NULL),
 (69,80,1,'Mobil',NULL),
 (70,81,1,'Fax ',NULL),
 (71,82,1,'E-Mail',NULL),
 (72,83,1,'Internet URL',NULL),
 (73,84,1,'Kontaktbilder',NULL),
 (74,85,1,'Vortragende',NULL),
 (75,86,1,'Kontakt',NULL),
 (76,87,1,'Veranstaltungsstatus',NULL),
 (77,90,1,'Titel',NULL),
 (78,91,1,'Headerbild',NULL),
 (79,92,1,'Embed Code',NULL),
 (80,93,1,'Url (z.B. http://www.getzoolu.com)',NULL),
 (81,94,1,'Titel',NULL),
 (82,95,1,'Sub-Navigations-Seiten',NULL),
 (83,97,1,'Titel',NULL),
 (84,98,1,'Beschreibung',NULL),
 (85,99,1,'Beschreibung',NULL),
 (86,100,1,'Abteilung',NULL),
 (87,101,1,'Stelle',NULL),
 (88,102,1,'Inhaltiche Verantwortung',NULL),
 (89,103,1,'Organisatorische Verantwortung',NULL),
 (90,104,1,'Aktivität',NULL),
 (91,105,1,'Beschreibung',NULL),
 (92,107,1,'Wer?',NULL),
 (93,110,1,'Beschreibung / Ursache',NULL),
 (94,111,1,'Titel',NULL),
 (95,113,1,'Präventive und korrektive Maßnahme',NULL),
 (96,112,1,'Beschreibung',NULL),
 (97,120,1,'Text',NULL),
 (98,119,1,'Titel',NULL),
 (99,122,1,'Pagination',NULL),
 (100,124,1,'Tags',NULL),
 (101,125,1,'Kategorien',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `fieldTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fieldTypeGroups`
--

DROP TABLE IF EXISTS `zoolu`.`fieldTypeGroups`;
CREATE TABLE  `zoolu`.`fieldTypeGroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fieldTypeGroups`
--

/*!40000 ALTER TABLE `fieldTypeGroups` DISABLE KEYS */;
LOCK TABLES `fieldTypeGroups` WRITE;
INSERT INTO `zoolu`.`fieldTypeGroups` VALUES  (1,'files'),
 (2,'selects'),
 (3,'multi_fields'),
 (4,'special_fields'),
 (5,'zend');
UNLOCK TABLES;
/*!40000 ALTER TABLE `fieldTypeGroups` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fieldTypes`
--

DROP TABLE IF EXISTS `zoolu`.`fieldTypes`;
CREATE TABLE  `zoolu`.`fieldTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idDecorator` bigint(20) unsigned NOT NULL,
  `sqlType` varchar(30) NOT NULL,
  `size` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `defaultValue` varchar(255) NOT NULL,
  `idFieldTypeGroup` int(10) unsigned NOT NULL DEFAULT '5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`fieldTypes`
--

/*!40000 ALTER TABLE `fieldTypes` DISABLE KEYS */;
LOCK TABLES `fieldTypes` WRITE;
INSERT INTO `zoolu`.`fieldTypes` VALUES  (1,1,'',0,'text','',5),
 (2,1,'',0,'textarea','',5),
 (3,1,'',0,'multiCheckbox','',3),
 (4,1,'',0,'radio','',5),
 (5,1,'',0,'submit','',5),
 (6,1,'',0,'button','',5),
 (7,1,'',0,'reset','',5),
 (8,1,'',0,'hidden','',5),
 (9,1,'',0,'select','',2),
 (10,1,'',0,'texteditor','',5),
 (11,2,'',0,'template','',5),
 (12,1,'',0,'media','',1),
 (13,1,'',0,'document','',1),
 (14,1,'',0,'multiselect','',2),
 (15,1,'',0,'dselect','',5),
 (16,3,'',0,'tag','',4),
 (17,4,'',0,'multiCheckboxTree','',3),
 (18,5,'',0,'url','',4),
 (19,1,'',0,'internalLink','',4),
 (20,1,'',0,'selectTree','',2),
 (21,1,'',0,'textDisplay','',5),
 (22,6,'',0,'videoSelect','',4),
 (24,1,'',0,'contact','',4),
 (25,7,'',0,'gmaps','',4),
 (26,8,'',0,'widgetUrl','',4),
 (27,1,'',0,'list','',3);
UNLOCK TABLES;
/*!40000 ALTER TABLE `fieldTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fieldValidators`
--

DROP TABLE IF EXISTS `zoolu`.`fieldValidators`;
CREATE TABLE  `zoolu`.`fieldValidators` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFields` bigint(20) unsigned NOT NULL,
  `idValidators` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fieldValidators`
--

/*!40000 ALTER TABLE `fieldValidators` DISABLE KEYS */;
LOCK TABLES `fieldValidators` WRITE;
INSERT INTO `zoolu`.`fieldValidators` VALUES  (1,1,17),
 (2,3,17),
 (3,1,2),
 (4,31,17),
 (5,32,17),
 (6,47,17),
 (7,48,17),
 (8,59,17);
UNLOCK TABLES;
/*!40000 ALTER TABLE `fieldValidators` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fields`
--

DROP TABLE IF EXISTS `zoolu`.`fields`;
CREATE TABLE  `zoolu`.`fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFieldTypes` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `idSearchFieldTypes` int(10) NOT NULL DEFAULT '1',
  `idRelationPage` bigint(20) unsigned DEFAULT NULL,
  `idCategory` bigint(20) unsigned DEFAULT NULL,
  `sqlSelect` varchar(2000) DEFAULT NULL,
  `columns` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `isCoreField` tinyint(1) NOT NULL DEFAULT '0',
  `isKeyField` tinyint(1) NOT NULL DEFAULT '0',
  `isSaveField` tinyint(1) NOT NULL DEFAULT '1',
  `isRegionTitle` tinyint(1) NOT NULL DEFAULT '0',
  `isDependentOn` bigint(20) unsigned DEFAULT NULL COMMENT 'must be an ID',
  `copyValue` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'decision if we addittionally write the value into the table (result: id and e.g. title in table)',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fields`
--

/*!40000 ALTER TABLE `fields` DISABLE KEYS */;
LOCK TABLES `fields` WRITE;
INSERT INTO `zoolu`.`fields` VALUES  (1,1,'title',1,NULL,NULL,NULL,12,0,1,1,1,0,NULL,0),
 (2,10,'description',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (3,1,'title',5,NULL,NULL,NULL,12,0,1,1,1,0,NULL,0),
 (4,1,'articletitle',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (5,12,'mainpics',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (6,10,'description',6,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (7,12,'pics',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (8,13,'docs',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (10,2,'shortdescription',5,NULL,NULL,NULL,9,0,0,0,1,0,NULL,0),
 (11,11,'template',1,NULL,NULL,NULL,12,0,0,0,0,0,NULL,0),
 (17,1,'title',1,NULL,NULL,NULL,12,0,1,1,1,0,NULL,0),
 (18,12,'block_pics',1,NULL,NULL,NULL,3,0,0,0,1,0,NULL,0),
 (19,10,'block_description',1,NULL,NULL,NULL,9,0,0,0,1,0,NULL,0),
 (20,1,'block_title',1,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (21,16,'page_tags',6,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (22,17,'category',6,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, tbl.depth AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 1 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',12,0,1,0,1,0,NULL,0),
 (23,1,'video_title',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (24,2,'video_embed_code',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (25,1,'pics_title',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (26,1,'docs_title',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (27,18,'url',3,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (28,19,'internal_link',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (29,10,'block_description',6,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (30,1,'block_title',5,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (31,1,'entry_number',1,NULL,NULL,NULL,4,0,0,0,1,0,NULL,0),
 (34,20,'entry_category',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, (tbl.depth-1) AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 1 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (37,1,'entry_title',5,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (40,21,'title',5,NULL,NULL,NULL,12,0,1,0,1,0,NULL,0),
 (41,1,'top_number',1,NULL,NULL,NULL,4,0,0,0,1,0,NULL,0),
 (42,20,'top_category',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, (tbl.depth-1) AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 1 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (43,1,'top_title',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (44,1,'entry_number',1,NULL,NULL,NULL,4,0,0,0,1,0,NULL,0),
 (45,20,'entry_nav_point',1,NULL,NULL,'SELECT folders.id, folderTitles.title, folders.depth FROM folders INNER JOIN folderTitles ON folderTitles.folderId = folders.folderId AND folderTitles.version = folders.version AND folderTitles.idLanguages = %LANGUAGE_ID% INNER JOIN rootLevels ON rootLevels.id = folders.idRootLevels INNER JOIN rootLevelTitles ON rootLevelTitles.idRootLevels = rootLevels.id WHERE folders.idRootLevels = %ROOTLEVEL_ID% ORDER BY folders.lft, folders.sortPosition ASC, folders.sortTimestamp DESC',4,0,0,0,1,0,NULL,0),
 (46,1,'entry_title',5,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (47,9,'entry_sorttype',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 12 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (48,4,'entry_sortorder',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 13 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (49,9,'top_sorttype',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 12 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (50,4,'top_sortorder',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 13 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (51,9,'entry_sorttype',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 12 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (52,4,'entry_sortorder',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 13 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (53,20,'entry_category',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, (tbl.depth-1) AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 1 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (54,22,'video',1,NULL,NULL,'SELECT tbl.id AS id, tbl.title AS title FROM videoTypes AS tbl',12,0,0,0,1,0,NULL,0),
 (55,12,'pic_shortdescription',1,NULL,NULL,NULL,3,0,0,0,1,0,NULL,0),
 (56,17,'label',6,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, tbl.depth AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 21 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',12,0,1,0,1,0,NULL,0),
 (59,9,'entry_viewtype',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 27 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (60,20,'entry_label',6,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, (tbl.depth-1) AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 21 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (61,20,'top_label',6,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, (tbl.depth-1) AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 21 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (62,20,'entry_label',6,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, (tbl.depth-1) AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 21 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (64,3,'option',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 42 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',12,0,0,0,1,0,NULL,0),
 (65,1,'fname',1,NULL,NULL,NULL,6,0,1,1,1,0,NULL,0),
 (66,1,'sname',1,NULL,NULL,NULL,6,0,1,1,1,0,NULL,0),
 (67,1,'datetime',1,NULL,NULL,NULL,6,0,1,0,1,0,NULL,0),
 (68,1,'event_duration',1,NULL,NULL,NULL,6,0,0,0,1,0,NULL,0),
 (69,1,'event_street',1,NULL,NULL,NULL,9,0,0,0,1,0,NULL,0),
 (70,1,'event_streetnr',1,NULL,NULL,NULL,3,0,0,0,1,0,NULL,0),
 (71,1,'event_plz',1,NULL,NULL,NULL,3,0,0,0,1,0,NULL,0),
 (72,1,'event_city',1,NULL,NULL,NULL,9,0,0,0,1,0,NULL,0),
 (73,1,'event_location',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (74,1,'event_max_members',1,NULL,NULL,NULL,6,0,0,0,1,0,NULL,0),
 (75,1,'event_costs',1,NULL,NULL,NULL,6,0,0,0,1,0,NULL,0),
 (76,1,'salutation',1,NULL,NULL,NULL,6,0,1,0,1,0,NULL,0),
 (77,1,'title',1,NULL,NULL,NULL,6,0,1,0,1,0,NULL,0),
 (78,1,'position',1,NULL,NULL,NULL,12,0,1,0,1,0,NULL,0),
 (79,1,'phone',1,NULL,NULL,NULL,4,0,1,0,1,0,NULL,0),
 (80,1,'mobile',1,NULL,NULL,NULL,4,0,1,0,1,0,NULL,0),
 (81,1,'fax',1,NULL,NULL,NULL,4,0,1,0,1,0,NULL,0),
 (82,1,'email',1,NULL,NULL,NULL,6,0,1,0,1,0,NULL,0),
 (83,1,'website',1,NULL,NULL,NULL,6,0,1,0,1,0,NULL,0),
 (84,12,'pics',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (85,24,'speakers',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (86,9,'contact',1,NULL,NULL,'SELECT tbl.id AS id, CONCAT(tbl.fname,\' \',tbl.sname) AS title FROM contacts AS tbl WHERE tbl.idUnits = 1 ORDER BY tbl.fname ASC',12,0,0,0,1,0,NULL,0),
 (87,9,'event_status',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 49 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN ( rootCat.lft +1 ) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',6,0,0,0,1,0,NULL,0),
 (88,12,'banner_pics',1,NULL,NULL,NULL,3,0,0,0,1,0,NULL,0),
 (89,10,'banner_description',1,NULL,NULL,NULL,9,0,0,0,1,0,NULL,0),
 (90,1,'banner_title',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (91,12,'headerpics',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (92,2,'header_embed_code',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (93,1,'external',1,NULL,NULL,NULL,12,0,1,0,1,0,NULL,0),
 (94,1,'title',1,NULL,NULL,NULL,12,0,1,1,1,0,NULL,0),
 (95,4,'entry_depth',1,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 64 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft %WHERE_ADDON% BETWEEN (rootCat.lft+1) AND rootCat.rgt ORDER BY tbl.lft, categoryTitles.title',4,0,0,0,1,0,NULL,0),
 (96,21,'title',5,NULL,NULL,NULL,12,0,1,0,1,0,NULL,0),
 (97,1,'instruction_title',5,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (98,10,'instruction_description',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (99,1,'description',5,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (100,1,'department',1,NULL,NULL,NULL,6,0,0,0,1,0,NULL,0),
 (101,1,'position',1,NULL,NULL,NULL,6,0,0,0,1,0,NULL,0),
 (102,1,'content_responsible',1,NULL,NULL,'SELECT tbl.id AS id, CONCAT(tbl.fname,\' \',tbl.sname) AS title FROM contacts AS tbl WHERE tbl.idUnits = 1 ORDER BY tbl.fname ASC',6,0,0,0,1,0,NULL,0),
 (103,1,'organizational_responsible',1,NULL,NULL,'SELECT tbl.id AS id, CONCAT(tbl.fname,\' \',tbl.sname) AS title FROM contacts AS tbl WHERE tbl.idUnits = 1 ORDER BY tbl.fname ASC',6,0,0,0,1,0,NULL,0),
 (104,1,'steps_title',1,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (105,10,'steps_text',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (106,10,'short_description',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (107,1,'steps_who',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (108,12,'process_pic',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (109,10,'process_inputs',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (110,10,'risk_description',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (111,1,'rule_title',1,NULL,NULL,NULL,12,0,0,0,1,1,NULL,0),
 (112,10,'rule_text',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (113,10,'risk_measure',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (114,10,'process_output',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (115,10,'process_indicator',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (116,10,'process_instructions',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (117,10,'process_techniques',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (118,25,'gmaps',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (119,1,'title',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (120,10,'text',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (121,26,'url',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (122,1,'prop_pagination',1,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (123,27,'test',1,NULL,NULL,'SELECT id, title FROM widget_BlogEntryComments tbl WHERE idWidget_BlogEntries = %WHERE_ADDON%',12,0,0,0,1,0,NULL,0),
 (124,16,'blog_entry_tags',6,NULL,NULL,NULL,12,0,0,0,1,0,NULL,0),
 (125,17,'category',6,NULL,NULL,'SELECT tbl.id AS id, categoryTitles.title AS title, tbl.depth AS depth FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = %LANGUAGE_ID%, categories AS rootCat WHERE rootCat.id = 67 AND tbl.idRootCategory = rootCat.idRootCategory AND tbl.lft BETWEEN (rootCat.lft + 1) AND rootCat.rgt %WHERE_ADDON% ORDER BY tbl.lft, categoryTitles.title',12,0,1,0,1,0,NULL,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `fields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fileAttributes`
--

DROP TABLE IF EXISTS `zoolu`.`fileAttributes`;
CREATE TABLE  `zoolu`.`fileAttributes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFiles` bigint(20) unsigned NOT NULL,
  `xDim` int(10) DEFAULT NULL,
  `yDim` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `fileAttributes_ibfk_1` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fileAttributes`
--

/*!40000 ALTER TABLE `fileAttributes` DISABLE KEYS */;
LOCK TABLES `fileAttributes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `fileAttributes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`filePermissions`
--

DROP TABLE IF EXISTS `zoolu`.`filePermissions`;
CREATE TABLE  `zoolu`.`filePermissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFiles` bigint(20) unsigned NOT NULL,
  `idPermissions` bigint(20) unsigned NOT NULL,
  `idGroups` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`filePermissions`
--

/*!40000 ALTER TABLE `filePermissions` DISABLE KEYS */;
LOCK TABLES `filePermissions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `filePermissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fileTitles`
--

DROP TABLE IF EXISTS `zoolu`.`fileTitles`;
CREATE TABLE  `zoolu`.`fileTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFiles` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(500) DEFAULT NULL,
  `description` text,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `fileTitles_ibfk_1` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fileTitles`
--

/*!40000 ALTER TABLE `fileTitles` DISABLE KEYS */;
LOCK TABLES `fileTitles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `fileTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`fileTypes`
--

DROP TABLE IF EXISTS `zoolu`.`fileTypes`;
CREATE TABLE  `zoolu`.`fileTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `isImage` tinyint(1) DEFAULT NULL COMMENT 'If filetyp ecan be rendered to image',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`fileTypes`
--

/*!40000 ALTER TABLE `fileTypes` DISABLE KEYS */;
LOCK TABLES `fileTypes` WRITE;
INSERT INTO `zoolu`.`fileTypes` VALUES  (1,'Work',NULL),
 (2,'Private',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `fileTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`files`
--

DROP TABLE IF EXISTS `zoolu`.`files`;
CREATE TABLE  `zoolu`.`files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fileId` varchar(32) NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `path` varchar(500) NOT NULL,
  `idParent` bigint(20) unsigned NOT NULL,
  `idParentTypes` int(10) unsigned NOT NULL,
  `isS3Stored` tinyint(4) NOT NULL DEFAULT '0',
  `isImage` tinyint(4) NOT NULL DEFAULT '0',
  `filename` varchar(500) DEFAULT NULL,
  `idFileTypes` bigint(20) unsigned DEFAULT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL COMMENT 'Filesize in KB',
  `extension` varchar(10) NOT NULL,
  `mimeType` varchar(255) NOT NULL,
  `version` int(10) NOT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`files`
--

/*!40000 ALTER TABLE `files` DISABLE KEYS */;
LOCK TABLES `files` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`folder-DEFAULT_FOLDER-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`folder-DEFAULT_FOLDER-1-Instances`;
CREATE TABLE  `zoolu`.`folder-DEFAULT_FOLDER-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folderId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `description` text,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `folderId` (`folderId`),
  CONSTRAINT `folder-DEFAULT_FOLDER-1-Instances_ibfk_1` FOREIGN KEY (`folderId`) REFERENCES `folders` (`folderId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`folder-DEFAULT_FOLDER-1-Instances`
--

/*!40000 ALTER TABLE `folder-DEFAULT_FOLDER-1-Instances` DISABLE KEYS */;
LOCK TABLES `folder-DEFAULT_FOLDER-1-Instances` WRITE;
INSERT INTO `zoolu`.`folder-DEFAULT_FOLDER-1-Instances` VALUES  (4,'4a2910cf9583d',1,1,'',3,3,'2009-06-05 14:34:23','2009-06-05 14:34:23'),
 (5,'4a2a3c746b0ba',1,1,'',3,3,'2009-06-06 11:52:52','2009-06-08 18:17:26'),
 (10,'4b599be1259d9',1,1,'',6,6,'2010-01-22 13:36:49','2010-02-09 11:17:45');
UNLOCK TABLES;
/*!40000 ALTER TABLE `folder-DEFAULT_FOLDER-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`folderPermissions`
--

DROP TABLE IF EXISTS `zoolu`.`folderPermissions`;
CREATE TABLE  `zoolu`.`folderPermissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idFolders` bigint(20) unsigned NOT NULL,
  `idPermissions` bigint(20) unsigned NOT NULL,
  `idGroups` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`folderPermissions`
--

/*!40000 ALTER TABLE `folderPermissions` DISABLE KEYS */;
LOCK TABLES `folderPermissions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `folderPermissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`folderTitles`
--

DROP TABLE IF EXISTS `zoolu`.`folderTitles`;
CREATE TABLE  `zoolu`.`folderTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folderId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `folderId` (`folderId`),
  CONSTRAINT `folderTitles_ibfk_1` FOREIGN KEY (`folderId`) REFERENCES `folders` (`folderId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`folderTitles`
--

/*!40000 ALTER TABLE `folderTitles` DISABLE KEYS */;
LOCK TABLES `folderTitles` WRITE;
INSERT INTO `zoolu`.`folderTitles` VALUES  (4,'4a2910cf9583d',1,1,'TEST',3,3,'2009-06-05 14:34:23','2009-06-05 14:34:23'),
 (5,'4a2a3c746b0ba',1,1,'TEST',3,3,'2009-06-06 11:52:52','2009-06-08 18:17:26'),
 (10,'4b599be1259d9',1,1,'Hauptpunkt 1',6,6,'2010-01-22 13:36:49','2010-02-09 11:17:45');
UNLOCK TABLES;
/*!40000 ALTER TABLE `folderTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`folderTypes`
--

DROP TABLE IF EXISTS `zoolu`.`folderTypes`;
CREATE TABLE  `zoolu`.`folderTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`folderTypes`
--

/*!40000 ALTER TABLE `folderTypes` DISABLE KEYS */;
LOCK TABLES `folderTypes` WRITE;
INSERT INTO `zoolu`.`folderTypes` VALUES  (1,'folder'),
 (2,'blog');
UNLOCK TABLES;
/*!40000 ALTER TABLE `folderTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`folders`
--

DROP TABLE IF EXISTS `zoolu`.`folders`;
CREATE TABLE  `zoolu`.`folders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) unsigned NOT NULL,
  `idFolderTypes` bigint(20) unsigned NOT NULL,
  `idParentFolder` bigint(20) unsigned DEFAULT NULL,
  `idRootLevels` bigint(20) unsigned DEFAULT NULL,
  `lft` int(10) unsigned DEFAULT NULL,
  `rgt` int(10) unsigned DEFAULT NULL,
  `depth` int(10) unsigned DEFAULT NULL,
  `idSortTypes` bigint(20) unsigned NOT NULL,
  `sortOrder` varchar(255) DEFAULT NULL,
  `sortPosition` bigint(20) unsigned NOT NULL,
  `sortTimestamp` timestamp NULL DEFAULT NULL,
  `folderId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `publisher` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published` timestamp NULL DEFAULT NULL,
  `idStatus` bigint(20) unsigned NOT NULL DEFAULT '0',
  `isUrlFolder` tinyint(1) NOT NULL DEFAULT '0',
  `showInNavigation` tinyint(1) NOT NULL DEFAULT '0',
  `isVirtualFolder` tinyint(1) NOT NULL DEFAULT '0',
  `virtualFolderType` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `folderId` (`folderId`),
  KEY `idParentFolder` (`idParentFolder`),
  KEY `idRootLevels` (`idRootLevels`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`folders`
--

/*!40000 ALTER TABLE `folders` DISABLE KEYS */;
LOCK TABLES `folders` WRITE;
INSERT INTO `zoolu`.`folders` VALUES  (4,1,1,0,3,1,2,0,0,NULL,0,'2009-06-05 14:34:23','4a2910cf9583d',1,3,3,0,'2009-06-05 14:34:23','2009-06-05 14:34:23',NULL,1,1,0,0,NULL),
 (5,1,1,0,2,1,2,0,0,NULL,0,'2009-06-06 11:52:52','4a2a3c746b0ba',1,3,3,0,'2009-06-06 11:52:52','2009-06-08 18:17:26',NULL,1,1,0,0,NULL),
 (8,1,1,0,1,1,2,0,0,NULL,1,'2010-01-22 13:36:49','4b599be1259d9',1,6,6,0,'2010-01-22 13:36:49','2010-02-09 11:17:45',NULL,2,1,1,0,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `folders` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`genericFormTabs`
--

DROP TABLE IF EXISTS `zoolu`.`genericFormTabs`;
CREATE TABLE  `zoolu`.`genericFormTabs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) NOT NULL,
  `idTabs` bigint(20) NOT NULL,
  `order` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`genericFormTabs`
--

/*!40000 ALTER TABLE `genericFormTabs` DISABLE KEYS */;
LOCK TABLES `genericFormTabs` WRITE;
INSERT INTO `zoolu`.`genericFormTabs` VALUES  (1,1,1,1),
 (2,2,2,1),
 (3,3,3,1),
 (4,4,4,1),
 (5,5,5,1),
 (6,6,6,1),
 (7,7,7,1),
 (8,8,8,1),
 (9,9,9,1),
 (10,10,10,1),
 (11,11,11,1),
 (12,12,12,1),
 (13,13,13,1),
 (14,14,14,1),
 (15,14,15,2),
 (16,14,16,3),
 (17,14,17,4),
 (18,14,18,5),
 (19,14,19,6),
 (20,15,20,1),
 (21,16,21,1),
 (22,0,0,0),
 (23,17,22,1),
 (24,16,23,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `genericFormTabs` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`genericFormTitles`
--

DROP TABLE IF EXISTS `zoolu`.`genericFormTitles`;
CREATE TABLE  `zoolu`.`genericFormTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) DEFAULT NULL,
  `idAction` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `genericFormTitles_ibfk_1` (`idGenericForms`),
  CONSTRAINT `genericFormTitles_ibfk_1` FOREIGN KEY (`idGenericForms`) REFERENCES `genericForms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`genericFormTitles`
--

/*!40000 ALTER TABLE `genericFormTitles` DISABLE KEYS */;
LOCK TABLES `genericFormTitles` WRITE;
INSERT INTO `zoolu`.`genericFormTitles` VALUES  (1,6,1,'Interne Verlinkung',1),
 (2,6,1,'Interne Verlinkung',2),
 (3,13,1,'Externe Verlinkung',1),
 (4,13,1,'Externe Verlinkung',2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `genericFormTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`genericFormTypes`
--

DROP TABLE IF EXISTS `zoolu`.`genericFormTypes`;
CREATE TABLE  `zoolu`.`genericFormTypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`genericFormTypes`
--

/*!40000 ALTER TABLE `genericFormTypes` DISABLE KEYS */;
LOCK TABLES `genericFormTypes` WRITE;
INSERT INTO `zoolu`.`genericFormTypes` VALUES  (1,'folder'),
 (2,'page'),
 (3,'category'),
 (4,'unit'),
 (5,'contact'),
 (6,'widget'),
 (7,'subwidget');
UNLOCK TABLES;
/*!40000 ALTER TABLE `genericFormTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`genericForms`
--

DROP TABLE IF EXISTS `zoolu`.`genericForms`;
CREATE TABLE  `zoolu`.`genericForms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idUsers` bigint(20) unsigned NOT NULL,
  `genericFormId` varchar(32) NOT NULL,
  `version` int(10) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idGenericFormTypes` int(10) unsigned NOT NULL,
  `mandatoryUpgrade` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`genericForms`
--

/*!40000 ALTER TABLE `genericForms` DISABLE KEYS */;
LOCK TABLES `genericForms` WRITE;
INSERT INTO `zoolu`.`genericForms` VALUES  (1,2,'DEFAULT_FOLDER',1,'2008-11-14 09:54:39','2008-11-14 09:54:39',1,0),
 (2,2,'DEFAULT_PAGE_1',1,'2009-01-29 08:27:34','2009-01-29 08:27:34',2,0),
 (5,2,'DEFAULT_CATEGORY',1,'2009-03-17 17:01:58','2009-03-17 17:01:58',3,0),
 (6,3,'DEFAULT_LINKING',1,'2009-02-11 16:37:37','2009-02-11 16:37:37',2,0),
 (7,2,'DEFAULT_OVERVIEW',1,'2009-02-17 14:30:42','2009-02-17 14:30:42',2,0),
 (8,2,'DEFAULT_STARTPAGE',1,'2009-02-27 10:51:14','2009-02-27 10:51:14',2,0),
 (9,3,'DEFAULT_UNIT',1,'2009-04-07 21:23:58','2009-04-07 21:23:58',4,0),
 (10,3,'DEFAULT_CONTACT',1,'2009-04-07 21:23:58','2009-04-07 21:23:58',5,0),
 (11,2,'DEFAULT_EVENT',1,'2009-04-09 17:04:57','2009-04-09 17:04:57',2,0),
 (12,2,'DEFAULT_EVENT_OVERVIEW',1,'2009-04-17 10:09:45','2009-04-17 10:09:45',2,0),
 (13,2,'DEFAULT_EXTERNAL',1,'2009-05-18 15:15:16','2009-02-11 16:37:37',2,0),
 (14,1,'DEFAULT_PROCESS',1,'2009-07-22 20:57:26','2009-07-22 20:57:26',2,0),
 (15,6,'W_BLOG_DEFAULT',1,'2009-08-06 14:49:42','2009-08-11 15:10:44',6,0),
 (16,7,'W_BLOG_ARTICLE',1,'2009-08-06 14:49:42','2010-01-04 19:11:34',7,0),
 (17,6,'W_BLOG_PROPERTIES',1,'2009-08-06 14:49:42','2009-08-06 14:49:42',6,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `genericForms` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`groupPermissions`
--

DROP TABLE IF EXISTS `zoolu`.`groupPermissions`;
CREATE TABLE  `zoolu`.`groupPermissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idGroups` bigint(20) unsigned NOT NULL,
  `idPermissions` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`groupPermissions`
--

/*!40000 ALTER TABLE `groupPermissions` DISABLE KEYS */;
LOCK TABLES `groupPermissions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `groupPermissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`groupTypes`
--

DROP TABLE IF EXISTS `zoolu`.`groupTypes`;
CREATE TABLE  `zoolu`.`groupTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Typen für Gruppen CMS,...';

--
-- Dumping data for table `zoolu`.`groupTypes`
--

/*!40000 ALTER TABLE `groupTypes` DISABLE KEYS */;
LOCK TABLES `groupTypes` WRITE;
INSERT INTO `zoolu`.`groupTypes` VALUES  (1,'cms'),
 (2,'extranet'),
 (3,'intranet');
UNLOCK TABLES;
/*!40000 ALTER TABLE `groupTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`groups`
--

DROP TABLE IF EXISTS `zoolu`.`groups`;
CREATE TABLE  `zoolu`.`groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `idGroupTypes` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`groups`
--

/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
LOCK TABLES `groups` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`guiTexts`
--

DROP TABLE IF EXISTS `zoolu`.`guiTexts`;
CREATE TABLE  `zoolu`.`guiTexts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idLanguanges` int(10) unsigned NOT NULL DEFAULT '1',
  `guiId` varchar(32) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idLanguanges` (`idLanguanges`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table for multilanguage GUI';

--
-- Dumping data for table `zoolu`.`guiTexts`
--

/*!40000 ALTER TABLE `guiTexts` DISABLE KEYS */;
LOCK TABLES `guiTexts` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `guiTexts` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`languages`
--

DROP TABLE IF EXISTS `zoolu`.`languages`;
CREATE TABLE  `zoolu`.`languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `languageCode` varchar(3) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`languages`
--

/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
LOCK TABLES `languages` WRITE;
INSERT INTO `zoolu`.`languages` VALUES  (1,'DE','Deutsch'),
 (2,'EN','English');
UNLOCK TABLES;
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`modules`
--

DROP TABLE IF EXISTS `zoolu`.`modules`;
CREATE TABLE  `zoolu`.`modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`modules`
--

/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
LOCK TABLES `modules` WRITE;
INSERT INTO `zoolu`.`modules` VALUES  (1,'cms'),
 (2,'media'),
 (3,'properties');
UNLOCK TABLES;
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_EVENT-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_EVENT-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_EVENT-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_EVENT-1-InstanceFiles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_EVENT-1-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_EVENT-1-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_EVENT-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_EVENT-1-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_EVENT-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_EVENT-1-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_EVENT-1-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_EVENT-1-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_EVENT-1-InstanceMultiFields_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_EVENT-1-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_EVENT-1-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_EVENT-1-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_EVENT-1-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_EVENT-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_EVENT-1-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_EVENT-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idUsers` bigint(20) unsigned NOT NULL,
  `articletitle` text NOT NULL,
  `description` text,
  `pics_title` varchar(255) DEFAULT NULL,
  `docs_title` varchar(255) DEFAULT '',
  `video_title` varchar(255) DEFAULT NULL,
  `video_embed_code` text,
  `shortdescription` text NOT NULL,
  `event_duration` varchar(255) DEFAULT NULL,
  `event_street` varchar(255) DEFAULT NULL,
  `event_streetnr` varchar(255) DEFAULT NULL,
  `event_plz` varchar(255) DEFAULT NULL,
  `event_city` varchar(255) DEFAULT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `event_max_members` varchar(255) DEFAULT NULL,
  `event_costs` varchar(255) DEFAULT NULL,
  `event_status` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_EVENT-1-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_EVENT-1-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_EVENT-1-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_EVENT-1-Instances` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_EVENT-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_EVENT_OVERVIEW-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_EVENT_OVERVIEW-1-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idUsers` bigint(20) unsigned NOT NULL,
  `shortdescription` text NOT NULL,
  `description` text NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_EVENT_OVERVIEW-1-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_EVENT_OVERVIEW-1-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_EVENT_OVERVIEW-1-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_EVENT_OVERVIEW-1-Instances` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_EVENT_OVERVIEW-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-InstanceFiles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_OVERVIEW-1-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-InstanceMultiFields_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_OVERVIEW-1-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_OVERVIEW-1-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idUsers` bigint(20) unsigned NOT NULL,
  `articletitle` varchar(255) DEFAULT NULL,
  `shortdescription` text,
  `description` text NOT NULL,
  `header_embed_code` text,
  `contact` varchar(255) DEFAULT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_OVERVIEW-1-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_OVERVIEW-1-Instances` VALUES  (5,'4b45aabb9cf71',1,1,6,'','','',NULL,NULL,6,'2010-01-07 10:35:57','2010-01-07 11:20:21');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `block_title` varchar(255) DEFAULT NULL,
  `block_description` text,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-Region14-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-Region14-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_OVERVIEW-1-Region14-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_OVERVIEW-1-Region14-Instances` VALUES  (23,'4b45aabb9cf71',1,1,1,'','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-Region14-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `entry_title` varchar(255) DEFAULT NULL,
  `entry_category` bigint(20) unsigned DEFAULT NULL,
  `entry_label` bigint(20) unsigned DEFAULT NULL,
  `entry_viewtype` bigint(20) unsigned DEFAULT NULL,
  `entry_number` bigint(20) unsigned DEFAULT NULL,
  `entry_sorttype` bigint(20) unsigned DEFAULT NULL,
  `entry_sortorder` bigint(20) unsigned DEFAULT NULL,
  `entry_depth` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_OVERVIEW-1-Region15-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-Region15-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_OVERVIEW-1-Region15-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_OVERVIEW-1-Region15-Instances` VALUES  (23,'4b45aabb9cf71',1,1,1,'Übersicht Hauptpunkt 1',0,0,30,10,18,15,66);
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_OVERVIEW-1-Region15-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_PAGE_1-1-InstanceFiles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PAGE_1-1-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_PAGE_1-1-InstanceMultiFields_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PAGE_1-1-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PAGE_1-1-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PAGE_1-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PAGE_1-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idUsers` bigint(20) unsigned NOT NULL,
  `articletitle` text NOT NULL,
  `description` text,
  `header_embed_code` text,
  `pics_title` varchar(255) DEFAULT NULL,
  `docs_title` varchar(255) DEFAULT '',
  `video_title` varchar(255) DEFAULT NULL,
  `video_embed_code` text,
  `shortdescription` text NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PAGE_1-1-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PAGE_1-1-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PAGE_1-1-Instances` VALUES  (47,'4b45aabb9cf71',1,1,6,'','',NULL,'','','','','','',6,'2010-01-07 10:34:51','2010-01-07 10:34:51'),
 (49,'4b599be130b50',1,1,6,'','',NULL,'','','','','','',6,'2010-01-22 13:36:49','2010-01-22 13:36:49'),
 (50,'4b599c44efd52',1,1,6,'Die erste Seite','<div id=\"idTextPanel\" class=\"jqDnR\">\n<p style=\"font-family: Verdana,Geneva,sans-serif; font-style: normal; font-weight: normal; font-size: 10px; letter-spacing: normal; line-height: normal; text-transform: none; text-decoration: none; text-align: left;\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,</p>\n</div>',NULL,'','','','','','',6,'2010-01-22 13:38:29','2010-02-09 10:31:00'),
 (51,'4b61ee1fbc4ae',1,1,6,'Die zweite Seite','<div id=\"idTextPanel\" class=\"jqDnR\">\n<p style=\"font-family: Verdana,Geneva,sans-serif; font-style: normal; font-weight: normal; font-size: 10px; letter-spacing: normal; line-height: normal; text-transform: none; text-decoration: none; text-align: left;\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,</p>\n</div>',NULL,'','','','','','',6,'2010-01-28 21:05:51','2010-01-29 11:59:13');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRegionInstances` bigint(20) unsigned NOT NULL,
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idRegionInstances` (`idRegionInstances`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles_ibfk_1` FOREIGN KEY (`idRegionInstances`) REFERENCES `page-DEFAULT_PAGE_1-1-Region11-Instances` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Region11-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRegionInstances` bigint(20) unsigned NOT NULL,
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idRegionInstances` (`idRegionInstances`),
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields_ibfk_1` FOREIGN KEY (`idRegionInstances`) REFERENCES `page-DEFAULT_PAGE_1-1-Region11-Instances` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Region11-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `block_title` varchar(255) DEFAULT NULL,
  `block_description` text,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_PAGE_1-1-Region11-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=283 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Region11-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PAGE_1-1-Region11-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PAGE_1-1-Region11-Instances` VALUES  (280,'4b61ee1fbc4ae',1,1,1,'',''),
 (282,'4b599c44efd52',1,1,1,'','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PAGE_1-1-Region11-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idFiles` (`idFiles`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-InstanceFiles` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PROCESS-1-InstanceFiles` VALUES  (14,'4a676ebfe3a7a',1,1,88,108);
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idUsers` bigint(20) unsigned NOT NULL,
  `description` text,
  `short_description` text,
  `department` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `content_responsible` varchar(255) DEFAULT NULL,
  `organizational_responsible` varchar(255) DEFAULT NULL,
  `process_inputs` text,
  `process_output` text,
  `process_indicator` text,
  `process_instructions` text,
  `process_techniques` text,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PROCESS-1-Instances` VALUES  (1,'4a676ebfe3a7a',1,1,3,'','<p>Hier kommt eine kurze Beschreibung</p>','Abteilung','Stelle','2','1','<p><strong>Prozessinputs / Informationen / Ressourcen</strong></p>','<p><strong>Prozessinputs / Informationen / Ressourcen</strong></p>','<p><strong>Messg&ouml;&szlig;en</strong></p>\n<p>&nbsp;</p>','<p><strong>Vorschriften / Richtlinien / Sicherheit</strong></p>','<p><strong>Methoden / Verfahren / IT Tools</strong></p>',3,'2009-07-22 22:17:46','2009-07-23 11:35:37'),
 (2,'4a676ebfe3a7a',1,2,3,'','','','','0','0',NULL,NULL,NULL,NULL,NULL,3,'2009-07-22 22:50:44','2009-07-23 08:22:47'),
 (3,'4a681b0f66d2a',1,1,3,'','','','','','','','','','','',3,'2009-07-23 10:37:38','2009-07-23 10:53:29');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `instruction_title` varchar(255) DEFAULT NULL,
  `instruction_description` text,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region27-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-Region27-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PROCESS-1-Region27-Instances` VALUES  (32,'4a676ebfe3a7a',1,2,1,'',''),
 (68,'4a681b0f66d2a',1,1,1,'',''),
 (77,'4a676ebfe3a7a',1,1,1,'Test 1 Thomas','<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.&nbsp;</p>\n<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.&nbsp;</p>\n<table border=\"0\" style=\"width: 100%;\">\n<tbody>\n<tr>\n<td>TEST</td>\n<td>TEST</td>\n<td>TEST</td>\n<td>TEST</td>\n</tr>\n<tr>\n<td>TEST</td>\n<td>TEST</td>\n<td>TEST</td>\n<td>TEST</td>\n</tr>\n<tr>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n</tr>\n<tr>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n</tr>\n<tr>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n</tr>\n</tbody>\n</table>'),
 (78,'4a676ebfe3a7a',1,1,2,'Test 2 Thomas','<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.&nbsp;</p>');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region27-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `steps_title` varchar(255) DEFAULT NULL,
  `steps_text` text,
  `steps_who` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region29-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-Region29-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PROCESS-1-Region29-Instances` VALUES  (32,'4a676ebfe3a7a',1,2,1,'','',''),
 (54,'4a681b0f66d2a',1,1,1,'','',''),
 (63,'4a676ebfe3a7a',1,1,1,'Aktivität 001','','Ich'),
 (64,'4a676ebfe3a7a',1,1,2,'Aktivität 002','','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region29-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `risk_description` text NOT NULL,
  `risk_measure` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region32-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-Region32-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PROCESS-1-Region32-Instances` VALUES  (19,'4a681b0f66d2a',1,1,1,'',''),
 (24,'4a676ebfe3a7a',1,1,1,'<p><strong>Beschreibung / Ursache</strong></p>','<p><strong>Pr&auml;ventive und korrektive Ma&szlig;nahme</strong></p>');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region32-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `rule_title` varchar(255) DEFAULT NULL,
  `rule_text` text,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region33-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_PROCESS-1-Region33-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_PROCESS-1-Region33-Instances` VALUES  (16,'4a681b0f66d2a',1,1,1,'',''),
 (21,'4a676ebfe3a7a',1,1,1,'asdfasdf','');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_PROCESS-1-Region33-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_STARTPAGE-1-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_STARTPAGE-1-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-InstanceFiles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_STARTPAGE-1-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_STARTPAGE-1-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idUsers` bigint(20) unsigned NOT NULL,
  `articletitle` varchar(255) DEFAULT NULL,
  `description` text,
  `header_embed_code` text,
  `top_title` text,
  `top_category` text,
  `top_label` text,
  `top_number` text,
  `top_sorttype` text,
  `top_sortorder` text,
  `banner_title` text,
  `banner_description` text,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_STARTPAGE-1-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_STARTPAGE-1-Instances` VALUES  (1,'4b45a967b4a62',1,1,6,'Home','<p>Das ist die Startseite.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,'2010-01-07 10:29:56','2010-01-07 10:31:20');
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles`;
CREATE TABLE  `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRegionInstances` bigint(20) unsigned NOT NULL,
  `idFiles` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idRegionInstances` (`idRegionInstances`),
  KEY `idFiles` (`idFiles`),
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE,
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles_ibfk_2` FOREIGN KEY (`idFiles`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles`
--

/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region11-InstanceFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields`;
CREATE TABLE  `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(255) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idRegionInstances` bigint(20) unsigned NOT NULL,
  `idRelation` bigint(20) unsigned NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `idRegionInstances` (`idRegionInstances`),
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields`
--

/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region11-InstanceMultiFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `block_title` varchar(255) DEFAULT NULL,
  `block_description` text,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region11-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region11-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region11-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_STARTPAGE-1-Region11-Instances` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region11-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances`
--

DROP TABLE IF EXISTS `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances`;
CREATE TABLE  `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `sortPosition` int(10) unsigned NOT NULL,
  `entry_title` varchar(255) DEFAULT NULL,
  `entry_nav_point` bigint(20) unsigned DEFAULT NULL,
  `entry_category` bigint(20) unsigned DEFAULT NULL,
  `entry_label` bigint(20) unsigned DEFAULT NULL,
  `entry_number` bigint(20) unsigned DEFAULT NULL,
  `entry_sorttype` bigint(20) unsigned DEFAULT NULL,
  `entry_sortorder` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `page-DEFAULT_STARTPAGE-1-Region17-Instances_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances`
--

/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region17-Instances` DISABLE KEYS */;
LOCK TABLES `page-DEFAULT_STARTPAGE-1-Region17-Instances` WRITE;
INSERT INTO `zoolu`.`page-DEFAULT_STARTPAGE-1-Region17-Instances` VALUES  (3,'4b45a967b4a62',1,1,1,'',0,0,0,0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `page-DEFAULT_STARTPAGE-1-Region17-Instances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageCategories`
--

DROP TABLE IF EXISTS `zoolu`.`pageCategories`;
CREATE TABLE  `zoolu`.`pageCategories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `category` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`),
  CONSTRAINT `pageCategories_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageCategories`
--

/*!40000 ALTER TABLE `pageCategories` DISABLE KEYS */;
LOCK TABLES `pageCategories` WRITE;
INSERT INTO `zoolu`.`pageCategories` VALUES  (1,'4b61ee1fbc4ae',1,1,53,6,6,'2010-01-29 11:59:13','2010-01-29 11:59:13');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageCategories` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageContacts`
--

DROP TABLE IF EXISTS `zoolu`.`pageContacts`;
CREATE TABLE  `zoolu`.`pageContacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idContacts` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`),
  KEY `pageId_2` (`pageId`,`version`),
  CONSTRAINT `pageContacts_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageContacts`
--

/*!40000 ALTER TABLE `pageContacts` DISABLE KEYS */;
LOCK TABLES `pageContacts` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageContacts` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageDatetimes`
--

DROP TABLE IF EXISTS `zoolu`.`pageDatetimes`;
CREATE TABLE  `zoolu`.`pageDatetimes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `datetime` varchar(255) DEFAULT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`),
  KEY `pageId_2` (`pageId`,`version`),
  CONSTRAINT `pageDatetimes_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageDatetimes`
--

/*!40000 ALTER TABLE `pageDatetimes` DISABLE KEYS */;
LOCK TABLES `pageDatetimes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageDatetimes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageExternals`
--

DROP TABLE IF EXISTS `zoolu`.`pageExternals`;
CREATE TABLE  `zoolu`.`pageExternals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `external` varchar(255) NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `pageExternals_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageExternals`
--

/*!40000 ALTER TABLE `pageExternals` DISABLE KEYS */;
LOCK TABLES `pageExternals` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageExternals` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageGmaps`
--

DROP TABLE IF EXISTS `zoolu`.`pageGmaps`;
CREATE TABLE  `zoolu`.`pageGmaps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`),
  CONSTRAINT `pageGmaps_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageGmaps`
--

/*!40000 ALTER TABLE `pageGmaps` DISABLE KEYS */;
LOCK TABLES `pageGmaps` WRITE;
INSERT INTO `zoolu`.`pageGmaps` VALUES  (23,'4b61ee1fbc4ae',1,1,'47.503042','9.747067',6,NULL,'2010-01-29 11:59:13'),
 (25,'4b599c44efd52',1,1,'47.503042','9.747067',6,NULL,'2010-02-09 10:31:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageGmaps` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageLabels`
--

DROP TABLE IF EXISTS `zoolu`.`pageLabels`;
CREATE TABLE  `zoolu`.`pageLabels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `label` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`),
  KEY `pageId_2` (`pageId`,`version`),
  CONSTRAINT `pageLabels_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageLabels`
--

/*!40000 ALTER TABLE `pageLabels` DISABLE KEYS */;
LOCK TABLES `pageLabels` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageLabels` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageLinks`
--

DROP TABLE IF EXISTS `zoolu`.`pageLinks`;
CREATE TABLE  `zoolu`.`pageLinks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idPages` bigint(20) unsigned NOT NULL,
  `pageId` varchar(32) NOT NULL COMMENT 'linked page',
  PRIMARY KEY (`id`),
  KEY `idPages` (`idPages`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `pageLinks_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageLinks`
--

/*!40000 ALTER TABLE `pageLinks` DISABLE KEYS */;
LOCK TABLES `pageLinks` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageLinks` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pagePermissions`
--

DROP TABLE IF EXISTS `zoolu`.`pagePermissions`;
CREATE TABLE  `zoolu`.`pagePermissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idPages` bigint(20) unsigned NOT NULL,
  `idPermissions` bigint(20) unsigned NOT NULL,
  `idGroups` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pagePermissions`
--

/*!40000 ALTER TABLE `pagePermissions` DISABLE KEYS */;
LOCK TABLES `pagePermissions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pagePermissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageRegistrations`
--

DROP TABLE IF EXISTS `zoolu`.`pageRegistrations`;
CREATE TABLE  `zoolu`.`pageRegistrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idPage` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `sname` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `plz` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `club` varchar(255) DEFAULT NULL,
  `infos` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageRegistrations`
--

/*!40000 ALTER TABLE `pageRegistrations` DISABLE KEYS */;
LOCK TABLES `pageRegistrations` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageRegistrations` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageTitles`
--

DROP TABLE IF EXISTS `zoolu`.`pageTitles`;
CREATE TABLE  `zoolu`.`pageTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  CONSTRAINT `pageTitles_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageTitles`
--

/*!40000 ALTER TABLE `pageTitles` DISABLE KEYS */;
LOCK TABLES `pageTitles` WRITE;
INSERT INTO `zoolu`.`pageTitles` VALUES  (43,'4b45a967b4a62',1,1,'Home',6,6,'2010-01-07 10:31:20','2010-01-07 10:31:20'),
 (44,'4b45aabb9cf71',1,1,'Hauptpunkt 1',6,6,'2010-01-07 10:34:51','2010-01-07 11:20:21'),
 (46,'4b599be130b50',1,1,'Hauptpunkt 1',6,6,'2010-01-22 13:36:49','2010-02-09 11:17:45'),
 (47,'4b599c44efd52',1,1,'Seite 1',6,6,'2010-01-22 13:38:29','2010-02-09 10:31:00'),
 (48,'4b61ee1fbc4ae',1,1,'Seite 2',6,6,'2010-01-28 21:05:51','2010-01-29 11:59:13');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageTypeTitles`
--

DROP TABLE IF EXISTS `zoolu`.`pageTypeTitles`;
CREATE TABLE  `zoolu`.`pageTypeTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idPageTypes` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageTypeTitles`
--

/*!40000 ALTER TABLE `pageTypeTitles` DISABLE KEYS */;
LOCK TABLES `pageTypeTitles` WRITE;
INSERT INTO `zoolu`.`pageTypeTitles` VALUES  (1,1,1,'Standardseite'),
 (2,2,1,'Interne Verlinkung'),
 (3,3,1,'Übersicht'),
 (4,4,1,'Externe Verlinkung'),
 (5,5,1,'Prozessablauf');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageTypeTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageTypes`
--

DROP TABLE IF EXISTS `zoolu`.`pageTypes`;
CREATE TABLE  `zoolu`.`pageTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `page` tinyint(1) NOT NULL DEFAULT '0',
  `startpage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page` (`page`),
  KEY `startpage` (`startpage`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pageTypes`
--

/*!40000 ALTER TABLE `pageTypes` DISABLE KEYS */;
LOCK TABLES `pageTypes` WRITE;
INSERT INTO `zoolu`.`pageTypes` VALUES  (1,'page',1,1),
 (2,'link',1,1),
 (3,'overview',0,1),
 (4,'external',1,1),
 (5,'process',1,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pageVideos`
--

DROP TABLE IF EXISTS `zoolu`.`pageVideos`;
CREATE TABLE  `zoolu`.`pageVideos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `userId` varchar(32) NOT NULL,
  `videoId` varchar(64) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `idVideoTypes` int(10) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`),
  CONSTRAINT `pageVideos_ibfk_1` FOREIGN KEY (`pageId`) REFERENCES `pages` (`pageId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`pageVideos`
--

/*!40000 ALTER TABLE `pageVideos` DISABLE KEYS */;
LOCK TABLES `pageVideos` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `pageVideos` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pages`
--

DROP TABLE IF EXISTS `zoolu`.`pages`;
CREATE TABLE  `zoolu`.`pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) unsigned NOT NULL,
  `idTemplates` bigint(20) unsigned NOT NULL,
  `idPageTypes` bigint(20) unsigned NOT NULL,
  `isStartPage` tinyint(1) NOT NULL DEFAULT '0',
  `showInNavigation` tinyint(1) NOT NULL DEFAULT '0',
  `idParent` bigint(20) unsigned NOT NULL,
  `idParentTypes` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `sortPosition` bigint(20) unsigned NOT NULL,
  `sortTimestamp` timestamp NULL DEFAULT NULL,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `publisher` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published` timestamp NULL DEFAULT NULL,
  `idStatus` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pageId` (`pageId`),
  KEY `version` (`version`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pages`
--

/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
LOCK TABLES `pages` WRITE;
INSERT INTO `zoolu`.`pages` VALUES  (1,8,3,1,1,1,1,1,6,0,'2010-01-07 10:31:20','4b45a967b4a62',1,6,6,NULL,'2010-01-07 10:33:31','1999-11-30 00:00:00',2),
 (2,7,4,3,1,1,7,2,6,0,'2010-01-07 10:34:51','4b45aabb9cf71',1,6,0,'2010-01-07 10:34:51','2010-01-07 11:20:21','2010-01-07 10:35:54',1),
 (4,2,2,1,1,1,8,2,6,0,'2010-01-22 13:36:49','4b599be130b50',1,6,0,'2010-01-22 13:36:49','2010-02-09 11:17:45',NULL,2),
 (5,2,1,1,0,1,8,2,6,1,'2010-01-22 13:38:28','4b599c44efd52',1,6,0,'2010-01-22 13:38:28','2010-02-09 10:31:00','2010-01-22 13:38:49',1),
 (6,2,1,1,0,1,8,2,6,2,'2010-01-28 21:05:51','4b61ee1fbc4ae',1,6,0,'2010-01-28 21:05:51','2010-01-29 11:59:13','2010-01-28 21:06:10',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`parentTypes`
--

DROP TABLE IF EXISTS `zoolu`.`parentTypes`;
CREATE TABLE  `zoolu`.`parentTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`parentTypes`
--

/*!40000 ALTER TABLE `parentTypes` DISABLE KEYS */;
LOCK TABLES `parentTypes` WRITE;
INSERT INTO `zoolu`.`parentTypes` VALUES  (1,'rootLevel'),
 (2,'folder'),
 (3,'widget');
UNLOCK TABLES;
/*!40000 ALTER TABLE `parentTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`pathReplacers`
--

DROP TABLE IF EXISTS `zoolu`.`pathReplacers`;
CREATE TABLE  `zoolu`.`pathReplacers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`pathReplacers`
--

/*!40000 ALTER TABLE `pathReplacers` DISABLE KEYS */;
LOCK TABLES `pathReplacers` WRITE;
INSERT INTO `zoolu`.`pathReplacers` VALUES  (1,1,'ä','ae'),
 (2,1,'ö','oe'),
 (3,1,'ü','ue'),
 (4,1,'ß','ss'),
 (5,1,'Ä','Ae'),
 (6,1,'Ö','Oe'),
 (7,1,'Ü','Ue'),
 (8,1,'&','und'),
 (9,2,'&','and');
UNLOCK TABLES;
/*!40000 ALTER TABLE `pathReplacers` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`permissions`
--

DROP TABLE IF EXISTS `zoolu`.`permissions`;
CREATE TABLE  `zoolu`.`permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`permissions`
--

/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
LOCK TABLES `permissions` WRITE;
INSERT INTO `zoolu`.`permissions` VALUES  (1,'full','Vollumfänglicher Zugang:\r\nVIEW, ADD, UPDATE, DELETE, COPY'),
 (2,'view','Der Datensatz darf visualisiert werden'),
 (3,'add','Ein neuer Datensatz darf erzeugt werden'),
 (4,'update','Der Datensatz darf verändert werden'),
 (5,'delete','Der Datensatz darf gelöscht werden'),
 (6,'copy','Der Datensatz darf kopiert werden'),
 (7,'live','Live schalten');
UNLOCK TABLES;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`properties`
--

DROP TABLE IF EXISTS `zoolu`.`properties`;
CREATE TABLE  `zoolu`.`properties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`properties`
--

/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
LOCK TABLES `properties` WRITE;
INSERT INTO `zoolu`.`properties` VALUES  (1,'Url'),
 (2,'DeleteAction');
UNLOCK TABLES;
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`regionFields`
--

DROP TABLE IF EXISTS `zoolu`.`regionFields`;
CREATE TABLE  `zoolu`.`regionFields` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idRegions` bigint(20) NOT NULL,
  `idFields` bigint(20) NOT NULL,
  `order` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idRegions` (`idRegions`),
  KEY `idFields` (`idFields`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`regionFields`
--

/*!40000 ALTER TABLE `regionFields` DISABLE KEYS */;
LOCK TABLES `regionFields` WRITE;
INSERT INTO `zoolu`.`regionFields` VALUES  (1,1,1,1),
 (2,1,1,1),
 (3,1,2,2),
 (4,2,3,1),
 (5,2,4,2),
 (6,3,5,1),
 (7,4,6,1),
 (8,5,7,2),
 (9,6,8,2),
 (10,8,10,2),
 (11,9,11,1),
 (12,10,17,1),
 (13,11,18,2),
 (14,11,19,3),
 (15,11,20,1),
 (16,9,21,5),
 (17,9,22,3),
 (18,12,23,1),
 (19,12,24,3),
 (20,5,25,1),
 (21,6,26,1),
 (22,2,27,3),
 (23,13,28,1),
 (24,14,29,2),
 (25,14,30,1),
 (26,15,31,5),
 (27,15,34,3),
 (28,15,37,1),
 (29,2,40,1),
 (30,16,41,4),
 (31,16,42,2),
 (32,16,43,1),
 (33,17,44,5),
 (34,17,45,2),
 (35,17,46,1),
 (36,15,47,6),
 (37,15,48,7),
 (38,16,49,5),
 (39,16,50,6),
 (40,17,51,6),
 (41,17,52,7),
 (42,17,53,3),
 (43,12,54,2),
 (44,8,55,1),
 (45,9,56,4),
 (46,15,59,2),
 (47,15,60,4),
 (48,16,61,3),
 (49,17,62,4),
 (50,99999999,64,2),
 (51,18,65,3),
 (52,18,66,4),
 (53,19,67,1),
 (54,19,68,2),
 (55,20,69,2),
 (56,20,70,3),
 (57,20,71,4),
 (58,20,72,5),
 (59,20,73,1),
 (60,19,74,3),
 (61,19,75,4),
 (62,18,76,1),
 (63,18,77,2),
 (64,18,78,5),
 (65,21,79,1),
 (66,21,80,2),
 (67,21,81,3),
 (68,21,82,4),
 (69,21,83,5),
 (70,21,84,6),
 (71,22,85,1),
 (72,9,86,2),
 (73,19,87,5),
 (74,23,88,2),
 (75,23,89,3),
 (76,23,90,1),
 (77,24,91,1),
 (78,24,92,2),
 (79,25,93,2),
 (80,25000,94,1),
 (81,15,95,8),
 (82,25,96,1),
 (83,26,1,1),
 (84,27,97,1),
 (85,27,98,2),
 (86,26,99,2),
 (87,26,100,3),
 (88,26,101,4),
 (89,26,102,5),
 (90,26,103,6),
 (91,28,108,1),
 (92,26,27,7),
 (93,29,104,1),
 (94,29,105,2),
 (95,30,106,1),
 (96,29,107,3),
 (97,31,109,1),
 (98,32,110,1),
 (99,32,113,2),
 (100,34,114,1),
 (101,35,115,1),
 (102,33,111,1),
 (103,33,112,1),
 (104,36,116,1),
 (105,37,117,1),
 (106,38,118,1),
 (107,39,1,1),
 (108,40,120,2),
 (109,40,119,1),
 (110,39,121,2),
 (111,41,122,1),
 (112,42,123,1),
 (113,43,124,1),
 (114,43,125,2),
 (115,40,27,3);
UNLOCK TABLES;
/*!40000 ALTER TABLE `regionFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`regionTitles`
--

DROP TABLE IF EXISTS `zoolu`.`regionTitles`;
CREATE TABLE  `zoolu`.`regionTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idRegions` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idRegions` (`idRegions`),
  CONSTRAINT `regionTitles_ibfk_1` FOREIGN KEY (`idRegions`) REFERENCES `regions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`regionTitles`
--

/*!40000 ALTER TABLE `regionTitles` DISABLE KEYS */;
LOCK TABLES `regionTitles` WRITE;
INSERT INTO `zoolu`.`regionTitles` VALUES  (1,1,1,'Allgemeine Informationen'),
 (2,2,1,'Allgemeine Informationen'),
 (3,3,1,'Titelbild'),
 (4,4,1,'Artikel'),
 (5,5,1,'Bildergalerie'),
 (6,6,1,'Dokumente'),
 (8,8,1,'Auszug'),
 (9,9,1,'Layout, Kategorien & Tags'),
 (10,10,1,'Allgemeine Informationen'),
 (11,11,1,'Textblock'),
 (12,12,1,'Video'),
 (13,13,1,'Link auf Seite im Portal'),
 (14,14,1,'Sidebar'),
 (15,15,1,'Definitionen'),
 (16,16,1,'Portal Top'),
 (17,17,1,'Artikel'),
 (18,18,1,'Allgemeine Kontaktinformationen'),
 (19,19,1,'Veranstaltungsinformationen'),
 (20,20,1,'Veranstaltungsort'),
 (21,21,1,'Kontaktdetails'),
 (22,22,1,'Vortragende'),
 (23,23,1,'Banner klein'),
 (24,24,1,'Headerbereich'),
 (25,25,1,'Externer Link'),
 (26,1,2,'General Informations'),
 (27,2,2,'General Informations'),
 (28,26,1,'Allgemeine Informationen'),
 (29,27,1,'Prozessanweisung'),
 (30,28,1,'Prozessgrafik'),
 (31,29,1,'Prozessschritt'),
 (32,30,1,'Kurzbeschreibung'),
 (33,31,1,'Prozessinputs / Informationen / Ressourcen'),
 (34,32,1,'Prozessrisiken'),
 (35,33,1,'Vorschriften'),
 (36,34,1,'Prozessoutputs / Ergebnisse / Kunden'),
 (37,35,1,'Messgößen'),
 (38,36,1,'Vorschriften / Richtlinien / Sicherheit'),
 (39,37,1,'Methoden / Verfahren / IT Tools'),
 (40,38,1,'Google Maps'),
 (41,40,1,'Blogeintrag'),
 (42,41,1,'Einstellungen');
UNLOCK TABLES;
/*!40000 ALTER TABLE `regionTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`regionTypes`
--

DROP TABLE IF EXISTS `zoolu`.`regionTypes`;
CREATE TABLE  `zoolu`.`regionTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`regionTypes`
--

/*!40000 ALTER TABLE `regionTypes` DISABLE KEYS */;
LOCK TABLES `regionTypes` WRITE;
INSERT INTO `zoolu`.`regionTypes` VALUES  (1,'content'),
 (2,'config');
UNLOCK TABLES;
/*!40000 ALTER TABLE `regionTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`regions`
--

DROP TABLE IF EXISTS `zoolu`.`regions`;
CREATE TABLE  `zoolu`.`regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idRegionTypes` bigint(20) unsigned NOT NULL,
  `columns` int(10) NOT NULL COMMENT 'size of region',
  `isTemplate` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'indicates, whether this region should be multiusable - needed later on for tempalte editor',
  `collapsable` tinyint(1) NOT NULL DEFAULT '1',
  `isCollapsed` tinyint(1) NOT NULL DEFAULT '1',
  `position` varchar(255) DEFAULT NULL,
  `isMultiply` tinyint(1) NOT NULL DEFAULT '0',
  `multiplyRegion` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`regions`
--

/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
LOCK TABLES `regions` WRITE;
INSERT INTO `zoolu`.`regions` VALUES  (1,1,9,0,0,1,NULL,0,0),
 (2,1,9,0,0,1,NULL,0,0),
 (3,1,9,0,1,1,NULL,0,0),
 (4,1,9,0,1,1,NULL,0,0),
 (5,1,9,0,1,0,NULL,0,0),
 (6,1,9,0,1,0,NULL,0,0),
 (8,1,9,0,1,0,NULL,0,0),
 (9,1,3,0,1,1,'right',0,0),
 (10,1,12,0,0,1,NULL,0,0),
 (11,1,9,0,1,0,NULL,1,1),
 (12,1,9,0,1,0,NULL,0,0),
 (13,1,12,0,0,1,NULL,0,0),
 (14,1,9,0,1,0,NULL,1,1),
 (15,2,9,0,1,0,NULL,1,1),
 (16,2,9,0,1,1,NULL,0,0),
 (17,2,9,0,1,0,NULL,1,1),
 (18,1,12,0,0,1,NULL,0,0),
 (19,1,9,0,1,1,NULL,0,0),
 (20,1,9,0,1,1,NULL,0,0),
 (21,1,12,0,1,1,NULL,0,0),
 (22,1,9,0,1,1,NULL,0,0),
 (23,1,9,0,1,0,NULL,0,0),
 (24,1,9,0,1,0,NULL,0,0),
 (25,1,12,0,0,1,NULL,0,0),
 (26,1,9,0,0,1,NULL,0,0),
 (27,1,9,0,1,1,NULL,1,0),
 (28,1,9,0,1,1,NULL,0,0),
 (29,1,9,0,1,0,NULL,1,0),
 (30,1,9,0,1,0,NULL,0,0),
 (31,1,9,0,1,0,NULL,0,0),
 (32,1,9,0,1,0,NULL,1,1),
 (33,1,9,0,1,1,NULL,1,0),
 (34,1,9,0,1,0,NULL,0,0),
 (35,1,9,0,1,0,NULL,0,0),
 (36,1,9,0,1,0,NULL,0,0),
 (37,1,9,0,1,0,NULL,0,0),
 (38,1,9,0,1,1,NULL,0,0),
 (39,1,9,0,0,1,NULL,0,0),
 (40,1,9,0,0,1,NULL,0,0),
 (41,1,9,0,0,1,NULL,0,0),
 (42,1,9,0,0,1,NULL,0,0),
 (43,1,3,0,0,1,NULL,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`renderedFiles`
--

DROP TABLE IF EXISTS `zoolu`.`renderedFiles`;
CREATE TABLE  `zoolu`.`renderedFiles` (
  `id` bigint(20) unsigned NOT NULL,
  `idFiles` bigint(20) unsigned NOT NULL,
  `folder` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`renderedFiles`
--

/*!40000 ALTER TABLE `renderedFiles` DISABLE KEYS */;
LOCK TABLES `renderedFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `renderedFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`rootLevelPermissions`
--

DROP TABLE IF EXISTS `zoolu`.`rootLevelPermissions`;
CREATE TABLE  `zoolu`.`rootLevelPermissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idRootLevels` bigint(20) unsigned NOT NULL,
  `idGroups` bigint(20) unsigned DEFAULT NULL,
  `idPermissions` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`rootLevelPermissions`
--

/*!40000 ALTER TABLE `rootLevelPermissions` DISABLE KEYS */;
LOCK TABLES `rootLevelPermissions` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `rootLevelPermissions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`rootLevelTitles`
--

DROP TABLE IF EXISTS `zoolu`.`rootLevelTitles`;
CREATE TABLE  `zoolu`.`rootLevelTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idRootLevels` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rootLevelTitles_ibfk_1` (`idRootLevels`),
  CONSTRAINT `rootLevelTitles_ibfk_1` FOREIGN KEY (`idRootLevels`) REFERENCES `rootLevels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`rootLevelTitles`
--

/*!40000 ALTER TABLE `rootLevelTitles` DISABLE KEYS */;
LOCK TABLES `rootLevelTitles` WRITE;
INSERT INTO `zoolu`.`rootLevelTitles` VALUES  (1,1,1,'Portal'),
 (2,2,1,'Bilder'),
 (3,3,1,'Dokumente'),
 (4,5,1,'Kontakte'),
 (5,4,1,'Kategorien'),
 (6,6,1,'Eigene Etiketten'),
 (7,7,1,'System Interne'),
 (8,1,2,'Portal');
UNLOCK TABLES;
/*!40000 ALTER TABLE `rootLevelTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`rootLevelTypes`
--

DROP TABLE IF EXISTS `zoolu`.`rootLevelTypes`;
CREATE TABLE  `zoolu`.`rootLevelTypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`rootLevelTypes`
--

/*!40000 ALTER TABLE `rootLevelTypes` DISABLE KEYS */;
LOCK TABLES `rootLevelTypes` WRITE;
INSERT INTO `zoolu`.`rootLevelTypes` VALUES  (1,'portals'),
 (2,'images'),
 (3,'documents'),
 (4,'categories'),
 (5,'contacts'),
 (6,'labels'),
 (7,'systeminternals');
UNLOCK TABLES;
/*!40000 ALTER TABLE `rootLevelTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`rootLevelUrls`
--

DROP TABLE IF EXISTS `zoolu`.`rootLevelUrls`;
CREATE TABLE  `zoolu`.`rootLevelUrls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idRootLevels` bigint(20) unsigned NOT NULL,
  `url` varchar(255) NOT NULL COMMENT 'without "www" in front',
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idRootLevels` (`idRootLevels`),
  CONSTRAINT `rootLevelUrls_ibfk_1` FOREIGN KEY (`idRootLevels`) REFERENCES `rootLevels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`rootLevelUrls`
--

/*!40000 ALTER TABLE `rootLevelUrls` DISABLE KEYS */;
LOCK TABLES `rootLevelUrls` WRITE;
INSERT INTO `zoolu`.`rootLevelUrls` VALUES  (6,1,'zoolu.cc',2,2,'2009-05-07 08:49:07','2009-05-07 08:49:07');
UNLOCK TABLES;
/*!40000 ALTER TABLE `rootLevelUrls` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`rootLevels`
--

DROP TABLE IF EXISTS `zoolu`.`rootLevels`;
CREATE TABLE  `zoolu`.`rootLevels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idRootLevelTypes` int(10) unsigned NOT NULL,
  `idModules` bigint(20) unsigned NOT NULL,
  `idThemes` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`rootLevels`
--

/*!40000 ALTER TABLE `rootLevels` DISABLE KEYS */;
LOCK TABLES `rootLevels` WRITE;
INSERT INTO `zoolu`.`rootLevels` VALUES  (1,1,1,1),
 (2,2,2,NULL),
 (3,3,2,NULL),
 (4,4,3,NULL),
 (5,5,3,NULL),
 (6,6,3,NULL),
 (7,7,3,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `rootLevels` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`searchFieldTypes`
--

DROP TABLE IF EXISTS `zoolu`.`searchFieldTypes`;
CREATE TABLE  `zoolu`.`searchFieldTypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`searchFieldTypes`
--

/*!40000 ALTER TABLE `searchFieldTypes` DISABLE KEYS */;
LOCK TABLES `searchFieldTypes` WRITE;
INSERT INTO `zoolu`.`searchFieldTypes` VALUES  (1,'None',NULL),
 (2,'Keyword','Keyword fields are stored and indexed, meaning that they can be searched as well as displayed in search results. They are not split up into separate words by tokenization. Enumerated database fields usually translate well to Keyword fields in Zend_Search_Lucene.'),
 (3,'UnIndexed','UnIndexed fields are not searchable, but they are returned with search hits. Database timestamps, primary keys, file system paths, and other external identifiers are good candidates for UnIndexed fields.'),
 (4,'Binary','Binary fields are not tokenized or indexed, but are stored for retrieval with search hits. They can be used to store any data encoded as a binary string, such as an image icon.'),
 (5,'Text','Text fields are stored, indexed, and tokenized. Text fields are appropriate for storing information like subjects and titles that need to be searchable as well as returned with search results.'),
 (6,'UnStored','UnStored fields are tokenized and indexed, but not stored in the index. Large amounts of text are best indexed using this type of field. Storing data creates a larger index on disk, so if you need to search but not redisplay the data, use an UnStored field. UnStored fields are practical when using a Zend_Search_Lucene index in combination with a relational database. You can index large data fields with UnStored fields for searching, and retrieve them from your relational database by using a separate field as an identifier.');
UNLOCK TABLES;
/*!40000 ALTER TABLE `searchFieldTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`sortTypes`
--

DROP TABLE IF EXISTS `zoolu`.`sortTypes`;
CREATE TABLE  `zoolu`.`sortTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`sortTypes`
--

/*!40000 ALTER TABLE `sortTypes` DISABLE KEYS */;
LOCK TABLES `sortTypes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `sortTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`status`
--

DROP TABLE IF EXISTS `zoolu`.`status`;
CREATE TABLE  `zoolu`.`status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`status`
--

/*!40000 ALTER TABLE `status` DISABLE KEYS */;
LOCK TABLES `status` WRITE;
INSERT INTO `zoolu`.`status` VALUES  (1,'test'),
 (2,'live'),
 (3,'approval');
UNLOCK TABLES;
/*!40000 ALTER TABLE `status` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`statusTitles`
--

DROP TABLE IF EXISTS `zoolu`.`statusTitles`;
CREATE TABLE  `zoolu`.`statusTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idStatus` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idStatus` (`idStatus`),
  CONSTRAINT `statusTitles_ibfk_1` FOREIGN KEY (`idStatus`) REFERENCES `status` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`statusTitles`
--

/*!40000 ALTER TABLE `statusTitles` DISABLE KEYS */;
LOCK TABLES `statusTitles` WRITE;
INSERT INTO `zoolu`.`statusTitles` VALUES  (1,1,1,'Test'),
 (2,2,1,'Veröffentlicht'),
 (3,3,1,'Approval');
UNLOCK TABLES;
/*!40000 ALTER TABLE `statusTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`subwidgetCategories`
--

DROP TABLE IF EXISTS `zoolu`.`subwidgetCategories`;
CREATE TABLE  `zoolu`.`subwidgetCategories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subwidgetId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `category` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subwidgetId` (`subwidgetId`),
  KEY `version` (`version`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`subwidgetCategories`
--

/*!40000 ALTER TABLE `subwidgetCategories` DISABLE KEYS */;
LOCK TABLES `subwidgetCategories` WRITE;
INSERT INTO `zoolu`.`subwidgetCategories` VALUES  (8,'4b4dbf01e4d7a',0,1,69,6,6,'2010-01-20 14:38:31','2010-01-20 14:38:31'),
 (9,'4b685a58ee8da',0,1,68,6,6,'2010-02-05 13:12:06','2010-02-05 13:12:06'),
 (10,'4b6c14730d23d',0,1,69,6,6,'2010-02-05 13:52:03','2010-02-05 13:52:03'),
 (11,'4b701b8336fd0',0,1,69,6,6,'2010-02-08 15:11:15','2010-02-08 15:11:15');
UNLOCK TABLES;
/*!40000 ALTER TABLE `subwidgetCategories` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`subwidgets`
--

DROP TABLE IF EXISTS `zoolu`.`subwidgets`;
CREATE TABLE  `zoolu`.`subwidgets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `subwidgetId` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `widgetInstanceId` varchar(32) COLLATE latin1_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idUsers` bigint(20) NOT NULL,
  `idWidgetTable` bigint(20) NOT NULL,
  `idParentTypes` bigint(20) NOT NULL DEFAULT '3',
  `version` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `zoolu`.`subwidgets`
--

/*!40000 ALTER TABLE `subwidgets` DISABLE KEYS */;
LOCK TABLES `subwidgets` WRITE;
INSERT INTO `zoolu`.`subwidgets` VALUES  (1,'4b71166822532','4b6859e978367','2010-02-09 09:01:44',6,1,3,0),
 (2,'4b71169b65ac3','4b6859e978367','2010-02-09 09:02:35',6,1,3,0),
 (3,'4b7118948e132','4b6859e978367','2010-02-09 09:11:00',6,1,3,0),
 (4,'4b71197e46e1d','4b6859e978367','2010-02-09 09:14:54',6,1,3,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `subwidgets` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tabRegions`
--

DROP TABLE IF EXISTS `zoolu`.`tabRegions`;
CREATE TABLE  `zoolu`.`tabRegions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idTabs` bigint(20) NOT NULL,
  `idRegions` bigint(20) NOT NULL,
  `order` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tabRegions`
--

/*!40000 ALTER TABLE `tabRegions` DISABLE KEYS */;
LOCK TABLES `tabRegions` WRITE;
INSERT INTO `zoolu`.`tabRegions` VALUES  (1,1,1,1),
 (2,2,2,1),
 (3,2,3,4),
 (4,2,4,6),
 (5,2,5,9),
 (6,2,6,10),
 (7,2,7,0),
 (8,2,8,11),
 (9,2,9,0),
 (10,5,10,1),
 (15,2,11,7),
 (16,2,12,8),
 (17,6,13,1),
 (18,7,2,1),
 (19,7,9,0),
 (20,7,14,5),
 (21,7,15,4),
 (22,8,2,1),
 (23,8,9,0),
 (24,8,11,7),
 (25,8,16,3),
 (26,8,17,8),
 (47,7,8,11),
 (48,7,3,4),
 (49,7,4,6),
 (50,9,10,1),
 (51,11,2,1),
 (52,11,3,4),
 (53,11,4,6),
 (54,11,5,9),
 (55,11,6,10),
 (56,11,7,0),
 (57,11,8,11),
 (58,11,9,0),
 (60,11,12,8),
 (61,10,18,1),
 (62,11,19,4),
 (63,11,20,5),
 (64,10,21,2),
 (65,11,22,6),
 (66,12,2,1),
 (67,12,3,4),
 (68,12,4,6),
 (69,12,8,11),
 (70,12,9,0),
 (71,8,23,5),
 (72,8,3,4),
 (73,8,4,6),
 (74,2,24,2),
 (75,8,24,2),
 (76,11,24,2),
 (77,12,24,2),
 (78,13,25,1),
 (79,14,26,1),
 (80,15,27,2),
 (81,16,28,1),
 (82,17,29,4),
 (83,14,30,2),
 (84,14,31,3),
 (85,18,32,1),
 (86,19,33,6),
 (87,14,34,4),
 (88,14,35,5),
 (89,14,36,6),
 (90,14,37,7),
 (91,2,38,3),
 (92,20,39,1),
 (93,21,40,1),
 (94,22,41,1),
 (95,23,42,2),
 (96,21,43,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tabRegions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tabTitles`
--

DROP TABLE IF EXISTS `zoolu`.`tabTitles`;
CREATE TABLE  `zoolu`.`tabTitles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idTabs` bigint(20) NOT NULL,
  `idLanguages` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tabTitles`
--

/*!40000 ALTER TABLE `tabTitles` DISABLE KEYS */;
LOCK TABLES `tabTitles` WRITE;
INSERT INTO `zoolu`.`tabTitles` VALUES  (1,14,1,'Übersicht'),
 (2,15,1,'Anweisung'),
 (3,16,1,'Ablauf'),
 (4,17,1,'Schritte'),
 (5,18,1,'Risiken'),
 (6,19,1,'Vorschriften / Richtlinien / Sicherheit'),
 (7,23,1,'Kommentare'),
 (8,21,1,'Eintrag');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tabTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tabs`
--

DROP TABLE IF EXISTS `zoolu`.`tabs`;
CREATE TABLE  `zoolu`.`tabs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `color` char(7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zoolu`.`tabs`
--

/*!40000 ALTER TABLE `tabs` DISABLE KEYS */;
LOCK TABLES `tabs` WRITE;
INSERT INTO `zoolu`.`tabs` VALUES  (1,NULL),
 (2,NULL),
 (3,NULL),
 (4,NULL),
 (5,NULL),
 (6,NULL),
 (7,NULL),
 (8,NULL),
 (9,NULL),
 (10,NULL),
 (11,NULL),
 (12,NULL),
 (13,NULL),
 (14,NULL),
 (15,NULL),
 (16,NULL),
 (17,NULL),
 (18,NULL),
 (19,NULL),
 (20,NULL),
 (21,NULL),
 (22,NULL),
 (23,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tabs` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tagFiles`
--

DROP TABLE IF EXISTS `zoolu`.`tagFiles`;
CREATE TABLE  `zoolu`.`tagFiles` (
  `idTags` bigint(20) unsigned NOT NULL,
  `fileId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idTags`,`fileId`,`version`,`idLanguages`),
  KEY `idTags` (`idTags`),
  KEY `idFiles` (`fileId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tagFiles`
--

/*!40000 ALTER TABLE `tagFiles` DISABLE KEYS */;
LOCK TABLES `tagFiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tagFiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tagFolders`
--

DROP TABLE IF EXISTS `zoolu`.`tagFolders`;
CREATE TABLE  `zoolu`.`tagFolders` (
  `idTags` bigint(20) unsigned NOT NULL,
  `folderId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idTags`,`folderId`,`version`,`idLanguages`),
  KEY `idTags` (`idTags`),
  KEY `idFolders` (`folderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tagFolders`
--

/*!40000 ALTER TABLE `tagFolders` DISABLE KEYS */;
LOCK TABLES `tagFolders` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tagFolders` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tagPages`
--

DROP TABLE IF EXISTS `zoolu`.`tagPages`;
CREATE TABLE  `zoolu`.`tagPages` (
  `idTags` bigint(20) unsigned NOT NULL,
  `pageId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idTags`,`pageId`,`version`,`idLanguages`),
  KEY `idTags` (`idTags`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tagPages`
--

/*!40000 ALTER TABLE `tagPages` DISABLE KEYS */;
LOCK TABLES `tagPages` WRITE;
INSERT INTO `zoolu`.`tagPages` VALUES  (4,'4a112157d69eb',1,1),
 (4,'4a115ca65d8bb',1,1),
 (4,'4a115ca65d8bb',1,2),
 (4,'4b0c1533498c4',1,1),
 (5,'4a112157d69eb',1,1),
 (5,'4a115ca65d8bb',1,1),
 (5,'4b0c1533498c4',1,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tagPages` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tagSubwidgets`
--

DROP TABLE IF EXISTS `zoolu`.`tagSubwidgets`;
CREATE TABLE  `zoolu`.`tagSubwidgets` (
  `idTags` bigint(20) unsigned NOT NULL,
  `subwidgetId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idTags`,`subwidgetId`,`version`,`idLanguages`) USING BTREE,
  KEY `idTags` (`idTags`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tagSubwidgets`
--

/*!40000 ALTER TABLE `tagSubwidgets` DISABLE KEYS */;
LOCK TABLES `tagSubwidgets` WRITE;
INSERT INTO `zoolu`.`tagSubwidgets` VALUES  (1,'4b685a58ee8da',0,1),
 (1,'4b6c14730d23d',0,1),
 (1,'4b6c1950e5b67',0,1),
 (1,'4b6c1a6acfcd1',0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tagSubwidgets` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tagWidgets`
--

DROP TABLE IF EXISTS `zoolu`.`tagWidgets`;
CREATE TABLE  `zoolu`.`tagWidgets` (
  `idTags` bigint(20) unsigned NOT NULL,
  `widgetId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`idTags`,`widgetId`,`version`,`idLanguages`) USING BTREE,
  KEY `idTags` (`idTags`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tagWidgets`
--

/*!40000 ALTER TABLE `tagWidgets` DISABLE KEYS */;
LOCK TABLES `tagWidgets` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tagWidgets` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`tags`
--

DROP TABLE IF EXISTS `zoolu`.`tags`;
CREATE TABLE  `zoolu`.`tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`tags`
--

/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
LOCK TABLES `tags` WRITE;
INSERT INTO `zoolu`.`tags` VALUES  (1,'asdf');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`templateExcludedFields`
--

DROP TABLE IF EXISTS `zoolu`.`templateExcludedFields`;
CREATE TABLE  `zoolu`.`templateExcludedFields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idTemplates` bigint(20) unsigned NOT NULL,
  `idFields` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `templateExcludedFields_ibfk_1` (`idTemplates`),
  CONSTRAINT `templateExcludedFields_ibfk_1` FOREIGN KEY (`idTemplates`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`templateExcludedFields`
--

/*!40000 ALTER TABLE `templateExcludedFields` DISABLE KEYS */;
LOCK TABLES `templateExcludedFields` WRITE;
INSERT INTO `zoolu`.`templateExcludedFields` VALUES  (1,1,40),
 (2,2,3),
 (3,2,4000),
 (4,4,3),
 (5,4,4000),
 (6,3,3),
 (8,5,40),
 (9,6,40),
 (10,7,40),
 (11,3,86),
 (12,4,86),
 (13,6,86),
 (14,8,3),
 (15,8,4),
 (16,8,86);
UNLOCK TABLES;
/*!40000 ALTER TABLE `templateExcludedFields` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`templateExcludedRegions`
--

DROP TABLE IF EXISTS `zoolu`.`templateExcludedRegions`;
CREATE TABLE  `zoolu`.`templateExcludedRegions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idTemplates` bigint(20) unsigned NOT NULL,
  `idRegions` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `templateExcludedRegions_ibfk_1` (`idTemplates`),
  CONSTRAINT `templateExcludedRegions_ibfk_1` FOREIGN KEY (`idTemplates`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`templateExcludedRegions`
--

/*!40000 ALTER TABLE `templateExcludedRegions` DISABLE KEYS */;
LOCK TABLES `templateExcludedRegions` WRITE;
INSERT INTO `zoolu`.`templateExcludedRegions` VALUES  (1,3,16),
 (2,3,23),
 (3,3,11),
 (4,1,24),
 (5,2,24),
 (6,3,24);
UNLOCK TABLES;
/*!40000 ALTER TABLE `templateExcludedRegions` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`templateRegionProperties`
--

DROP TABLE IF EXISTS `zoolu`.`templateRegionProperties`;
CREATE TABLE  `zoolu`.`templateRegionProperties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idTemplates` bigint(20) unsigned NOT NULL,
  `idRegions` bigint(20) unsigned NOT NULL,
  `order` int(10) DEFAULT NULL,
  `collapsable` tinyint(1) DEFAULT NULL,
  `isCollapsed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `templateRegionProperties_ibfk_1` (`idTemplates`),
  CONSTRAINT `templateRegionProperties_ibfk_1` FOREIGN KEY (`idTemplates`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`templateRegionProperties`
--

/*!40000 ALTER TABLE `templateRegionProperties` DISABLE KEYS */;
LOCK TABLES `templateRegionProperties` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `templateRegionProperties` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`templateTitles`
--

DROP TABLE IF EXISTS `zoolu`.`templateTitles`;
CREATE TABLE  `zoolu`.`templateTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idTemplates` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `templateTitles_ibfk_1` (`idTemplates`),
  CONSTRAINT `templateTitles_ibfk_1` FOREIGN KEY (`idTemplates`) REFERENCES `templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`templateTitles`
--

/*!40000 ALTER TABLE `templateTitles` DISABLE KEYS */;
LOCK TABLES `templateTitles` WRITE;
INSERT INTO `zoolu`.`templateTitles` VALUES  (1,1,1,'Template News'),
 (2,2,1,'Startseite'),
 (3,3,1,'Portal Startseite'),
 (4,4,1,'Übersicht'),
 (5,5,1,'Template Video'),
 (6,6,1,'Template Text'),
 (7,7,1,'Veranstaltung'),
 (8,8,1,'Übersicht Veranstaltung');
UNLOCK TABLES;
/*!40000 ALTER TABLE `templateTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`templateTypes`
--

DROP TABLE IF EXISTS `zoolu`.`templateTypes`;
CREATE TABLE  `zoolu`.`templateTypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idTemplates` bigint(20) unsigned NOT NULL,
  `idTypes` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`templateTypes`
--

/*!40000 ALTER TABLE `templateTypes` DISABLE KEYS */;
LOCK TABLES `templateTypes` WRITE;
INSERT INTO `zoolu`.`templateTypes` VALUES  (2,1,2),
 (3,2,1),
 (5,4,3),
 (6,3,4),
 (7,5,2),
 (8,6,2),
 (9,7,2),
 (10,8,3);
UNLOCK TABLES;
/*!40000 ALTER TABLE `templateTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`templates`
--

DROP TABLE IF EXISTS `zoolu`.`templates`;
CREATE TABLE  `zoolu`.`templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `genericFormId` varchar(32) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`templates`
--

/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
LOCK TABLES `templates` WRITE;
INSERT INTO `zoolu`.`templates` VALUES  (1,'DEFAULT_PAGE_1','template_1.php','template_01.jpg',1),
 (2,'DEFAULT_PAGE_1','template_1.php','template_01.jpg',1),
 (3,'DEFAULT_STARTPAGE','template_startpage.php','template_startpage.jpg',1),
 (4,'DEFAULT_OVERVIEW','template_overview.php','template_overview.jpg',1),
 (5,'DEFAULT_PAGE_1','template_video.php','template_video.jpg',0),
 (6,'DEFAULT_PAGE_1','template_text.php','template_text.jpg',0),
 (7,'DEFAULT_EVENT','template_event.php','template_event-detail.jpg',0),
 (8,'DEFAULT_EVENT_OVERVIEW','template_event_overview.php','template_event-overview.jpg',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`themes`
--

DROP TABLE IF EXISTS `zoolu`.`themes`;
CREATE TABLE  `zoolu`.`themes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`themes`
--

/*!40000 ALTER TABLE `themes` DISABLE KEYS */;
LOCK TABLES `themes` WRITE;
INSERT INTO `zoolu`.`themes` VALUES  (1,'Default','default',2,2,'2009-05-07 09:18:38','2009-05-07 08:48:35');
UNLOCK TABLES;
/*!40000 ALTER TABLE `themes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`types`
--

DROP TABLE IF EXISTS `zoolu`.`types`;
CREATE TABLE  `zoolu`.`types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`types`
--

/*!40000 ALTER TABLE `types` DISABLE KEYS */;
LOCK TABLES `types` WRITE;
INSERT INTO `zoolu`.`types` VALUES  (1,'startpage'),
 (2,'page'),
 (3,'overview'),
 (4,'portal_startpage');
UNLOCK TABLES;
/*!40000 ALTER TABLE `types` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`unitTitles`
--

DROP TABLE IF EXISTS `zoolu`.`unitTitles`;
CREATE TABLE  `zoolu`.`unitTitles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idUnits` bigint(20) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idUnits` (`idUnits`),
  CONSTRAINT `unitTitles_ibfk_1` FOREIGN KEY (`idUnits`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`unitTitles`
--

/*!40000 ALTER TABLE `unitTitles` DISABLE KEYS */;
LOCK TABLES `unitTitles` WRITE;
INSERT INTO `zoolu`.`unitTitles` VALUES  (1,1,1,'Massive Art');
UNLOCK TABLES;
/*!40000 ALTER TABLE `unitTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`units`
--

DROP TABLE IF EXISTS `zoolu`.`units`;
CREATE TABLE  `zoolu`.`units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) unsigned NOT NULL,
  `idParentUnit` bigint(20) unsigned NOT NULL DEFAULT '0',
  `idRootUnit` bigint(20) unsigned DEFAULT NULL,
  `lft` int(10) unsigned DEFAULT NULL,
  `rgt` int(10) unsigned DEFAULT NULL,
  `depth` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `idRootCategory` (`idRootUnit`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`units`
--

/*!40000 ALTER TABLE `units` DISABLE KEYS */;
LOCK TABLES `units` WRITE;
INSERT INTO `zoolu`.`units` VALUES  (1,9,0,1,1,2,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `units` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`urlReplacers`
--

DROP TABLE IF EXISTS `zoolu`.`urlReplacers`;
CREATE TABLE  `zoolu`.`urlReplacers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`urlReplacers`
--

/*!40000 ALTER TABLE `urlReplacers` DISABLE KEYS */;
LOCK TABLES `urlReplacers` WRITE;
INSERT INTO `zoolu`.`urlReplacers` VALUES  (1,1,'ä','ae'),
 (2,1,'ö','oe'),
 (3,1,'ü','ue'),
 (4,1,'ß','ss'),
 (5,1,'Ä','Ae'),
 (6,1,'Ö','Oe'),
 (7,1,'Ü','Ue'),
 (8,1,'&','und');
UNLOCK TABLES;
/*!40000 ALTER TABLE `urlReplacers` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`urlTypes`
--

DROP TABLE IF EXISTS `zoolu`.`urlTypes`;
CREATE TABLE  `zoolu`.`urlTypes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`urlTypes`
--

/*!40000 ALTER TABLE `urlTypes` DISABLE KEYS */;
LOCK TABLES `urlTypes` WRITE;
INSERT INTO `zoolu`.`urlTypes` VALUES  (1,'page'),
 (2,'widget'),
 (3,'subwidget');
UNLOCK TABLES;
/*!40000 ALTER TABLE `urlTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`urls`
--

DROP TABLE IF EXISTS `zoolu`.`urls`;
CREATE TABLE  `zoolu`.`urls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `relationId` varchar(32) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `idUrlTypes` int(10) unsigned NOT NULL DEFAULT '1',
  `isMain` tinyint(1) unsigned NOT NULL,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `idParent` varchar(32) DEFAULT NULL,
  `idParentTypes` int(10) unsigned DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  `creator` bigint(20) unsigned NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `relationId` (`relationId`,`version`,`idUrlTypes`,`idLanguages`),
  KEY `relationId_2` (`relationId`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `zoolu`.`urls`
--

/*!40000 ALTER TABLE `urls` DISABLE KEYS */;
LOCK TABLES `urls` WRITE;
INSERT INTO `zoolu`.`urls` VALUES  (1,'4b45a967b4a62',1,1,0,1,'0',NULL,'',3,3,'2009-04-24 10:14:16','2010-01-07 10:31:46'),
 (117,'4b599be130b50',1,1,1,1,NULL,NULL,'hauptpunkt-1/',6,6,'2010-01-22 13:36:49','2010-01-22 13:36:49'),
 (118,'4b599c44efd52',1,1,1,1,NULL,NULL,'hauptpunkt-1/die-erste-seite',6,6,'2010-01-22 13:38:29','2010-01-22 13:38:29'),
 (120,'4b61ee1fbc4ae',1,1,1,1,NULL,NULL,'hauptpunkt-1/die-zweite-seite',6,6,'2010-01-28 21:05:51','2010-01-28 21:05:51'),
 (124,'4b6859e978367',1,2,0,1,NULL,NULL,'blog/',6,6,'2010-02-02 17:59:21','2010-02-02 17:59:21'),
 (125,'4b685a58ee8da',0,2,1,1,NULL,NULL,'blog/asdf',6,6,'2010-02-02 18:01:13','2010-02-02 18:01:13'),
 (126,'4b6c120431fa3',0,2,1,1,NULL,NULL,'blog/asdfasdf',6,6,'2010-02-05 13:41:40','2010-02-05 13:41:40'),
 (127,'4b6c14730d23d',0,2,1,1,NULL,NULL,'blog/tada',6,6,'2010-02-05 13:52:03','2010-02-05 13:52:03'),
 (128,'4b6c1950e5b67',0,2,1,1,NULL,NULL,'blog/ein-neuer-blog',6,6,'2010-02-05 14:12:49','2010-02-05 14:12:49'),
 (129,'4b6c1a6acfcd1',0,3,1,1,NULL,NULL,'blog/asdf-1',6,6,'2010-02-05 14:17:30','2010-02-05 14:17:30'),
 (130,'4b71155b26acd',0,3,1,1,NULL,NULL,'blog/adf',6,6,'2010-02-09 08:57:15','2010-02-09 08:57:15'),
 (131,'4b71166822532',0,3,1,1,NULL,NULL,'blog/asdf-2',6,6,'2010-02-09 09:01:44','2010-02-09 09:01:44'),
 (132,'4b71169b65ac3',0,3,1,1,NULL,NULL,'blog/jkloe',6,6,'2010-02-09 09:02:35','2010-02-09 09:02:35'),
 (133,'4b71197e46e1d',0,3,1,1,NULL,NULL,'blog/asdfsgsegs',6,6,'2010-02-09 09:14:54','2010-02-09 09:14:54'),
 (134,'4b7136808d851',1,2,0,1,NULL,NULL,'asdf/',6,6,'2010-02-09 11:18:40','2010-02-09 11:18:40');
UNLOCK TABLES;
/*!40000 ALTER TABLE `urls` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`userGroups`
--

DROP TABLE IF EXISTS `zoolu`.`userGroups`;
CREATE TABLE  `zoolu`.`userGroups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idUsers` bigint(20) NOT NULL,
  `idGroups` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`userGroups`
--

/*!40000 ALTER TABLE `userGroups` DISABLE KEYS */;
LOCK TABLES `userGroups` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `userGroups` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`userProfiles`
--

DROP TABLE IF EXISTS `zoolu`.`userProfiles`;
CREATE TABLE  `zoolu`.`userProfiles` (
  `id` bigint(20) unsigned NOT NULL,
  `idUsers` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`userProfiles`
--

/*!40000 ALTER TABLE `userProfiles` DISABLE KEYS */;
LOCK TABLES `userProfiles` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `userProfiles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`users`
--

DROP TABLE IF EXISTS `zoolu`.`users`;
CREATE TABLE  `zoolu`.`users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idLanguages` int(10) unsigned NOT NULL DEFAULT '1',
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `sname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `zoolu`.`users` VALUES  (1,1,'rainer','163732f752aeeccdbb9630c04fe08e14','Rainer','Schönherr'),
 (2,1,'conny','3af31637b5328eb0e6a050e23a064681','Cornelius','Hansjakob'),
 (3,1,'thomas','ef6e65efc188e7dffd7335b646a85a21','Thomas','Schedler'),
 (4,1,'berndhep','01c2310a3cc00933589d3e2f694343d8','Bernd','Hepberger'),
 (5,1,'kate','29ddc288099264c17b07baf44d3f0adc','Kate','Dobler'),
 (6,1,'daniel','c0fc127fbd8824f509beb47eef03010d','Daniel','Rotter'),
 (7,1,'flo','7e1e91156f7c4e1bd0831cf008ad5fdf','Florian','Mathis');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`validators`
--

DROP TABLE IF EXISTS `zoolu`.`validators`;
CREATE TABLE  `zoolu`.`validators` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`validators`
--

/*!40000 ALTER TABLE `validators` DISABLE KEYS */;
LOCK TABLES `validators` WRITE;
INSERT INTO `zoolu`.`validators` VALUES  (1,'Alnum'),
 (2,'Alpha'),
 (3,'Barcode'),
 (4,'Between'),
 (5,'Ccnum'),
 (6,'Date'),
 (7,'Digits'),
 (8,'EmailAddress'),
 (9,'Float'),
 (10,'GreaterThan'),
 (11,'Hex'),
 (12,'Hostname'),
 (13,'InArray'),
 (14,'Int'),
 (15,'Ip'),
 (16,'LessThan'),
 (17,'NotEmpty'),
 (18,'Regex'),
 (19,'StringLength');
UNLOCK TABLES;
/*!40000 ALTER TABLE `validators` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`videoTypes`
--

DROP TABLE IF EXISTS `zoolu`.`videoTypes`;
CREATE TABLE  `zoolu`.`videoTypes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`videoTypes`
--

/*!40000 ALTER TABLE `videoTypes` DISABLE KEYS */;
LOCK TABLES `videoTypes` WRITE;
INSERT INTO `zoolu`.`videoTypes` VALUES  (1,'Vimeo'),
 (2,'Youtube');
UNLOCK TABLES;
/*!40000 ALTER TABLE `videoTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`virtualFolderTypes`
--

DROP TABLE IF EXISTS `zoolu`.`virtualFolderTypes`;
CREATE TABLE  `zoolu`.`virtualFolderTypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`virtualFolderTypes`
--

/*!40000 ALTER TABLE `virtualFolderTypes` DISABLE KEYS */;
LOCK TABLES `virtualFolderTypes` WRITE;
INSERT INTO `zoolu`.`virtualFolderTypes` VALUES  (1,'shallow'),
 (2,'deep');
UNLOCK TABLES;
/*!40000 ALTER TABLE `virtualFolderTypes` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widgetInstanceProperties`
--

DROP TABLE IF EXISTS `zoolu`.`widgetInstanceProperties`;
CREATE TABLE  `zoolu`.`widgetInstanceProperties` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `property` varchar(60) NOT NULL,
  `value` varchar(255) NOT NULL,
  `idWidgetInstances` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `widgetInstanceProperties_ibfk_1` (`idWidgetInstances`),
  CONSTRAINT `widgetInstanceProperties_ibfk_1` FOREIGN KEY (`idWidgetInstances`) REFERENCES `widgetInstances` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widgetInstanceProperties`
--

/*!40000 ALTER TABLE `widgetInstanceProperties` DISABLE KEYS */;
LOCK TABLES `widgetInstanceProperties` WRITE;
INSERT INTO `zoolu`.`widgetInstanceProperties` VALUES  (2,'pagination','5',9);
UNLOCK TABLES;
/*!40000 ALTER TABLE `widgetInstanceProperties` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widgetInstanceTitles`
--

DROP TABLE IF EXISTS `zoolu`.`widgetInstanceTitles`;
CREATE TABLE  `zoolu`.`widgetInstanceTitles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `widgetInstanceId` varchar(32) NOT NULL,
  `idLanguages` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `idUsers` bigint(20) NOT NULL,
  `creator` bigint(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `changed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widgetInstanceTitles`
--

/*!40000 ALTER TABLE `widgetInstanceTitles` DISABLE KEYS */;
LOCK TABLES `widgetInstanceTitles` WRITE;
INSERT INTO `zoolu`.`widgetInstanceTitles` VALUES  (1,'4b0c15627f26e',1,'Testblog',6,6,'2009-11-24 18:18:26','0000-00-00 00:00:00',1),
 (2,'4b45a5e8e06af',1,'asdf',6,6,'2010-01-07 10:14:17','0000-00-00 00:00:00',1),
 (3,'4b45ac1b49c62',1,'Blog',6,6,'2010-01-07 10:40:43','0000-00-00 00:00:00',1),
 (4,'4b599d1b4c5c3',1,'Blog',6,6,'2010-01-22 13:42:03','0000-00-00 00:00:00',1),
 (5,'4b599d44392fb',1,'Blog',6,6,'2010-01-22 13:42:44','0000-00-00 00:00:00',1),
 (6,'4b599d71e4c37',1,'Blog',6,6,'2010-01-22 13:43:29','0000-00-00 00:00:00',1),
 (7,'4b6859e978367',1,'Blog',6,6,'2010-02-09 11:26:26','2010-02-09 11:26:26',1),
 (8,'4b7136808d851',1,'asdf',6,6,'2010-02-09 11:18:40','0000-00-00 00:00:00',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `widgetInstanceTitles` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widgetInstances`
--

DROP TABLE IF EXISTS `zoolu`.`widgetInstances`;
CREATE TABLE  `zoolu`.`widgetInstances` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idGenericForms` bigint(20) DEFAULT NULL,
  `sortPosition` bigint(20) NOT NULL,
  `idParent` bigint(20) NOT NULL,
  `idParentTypes` bigint(20) NOT NULL,
  `idUsers` bigint(20) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `changed` timestamp NULL DEFAULT NULL,
  `published` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `idStatus` bigint(20) NOT NULL,
  `sortTimestamp` timestamp NULL DEFAULT NULL,
  `creator` bigint(20) NOT NULL,
  `publisher` bigint(20) DEFAULT NULL,
  `idWidgets` bigint(20) NOT NULL,
  `widgetInstanceId` varchar(32) NOT NULL,
  `version` int(11) NOT NULL,
  `showInNavigation` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `widgetInstances_ibfk_1` (`idWidgets`),
  CONSTRAINT `widgetInstances_ibfk_1` FOREIGN KEY (`idWidgets`) REFERENCES `widgets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widgetInstances`
--

/*!40000 ALTER TABLE `widgetInstances` DISABLE KEYS */;
LOCK TABLES `widgetInstances` WRITE;
INSERT INTO `zoolu`.`widgetInstances` VALUES  (9,15,2,1,1,6,'2010-02-09 11:26:26','2010-02-09 11:26:26','2010-02-09 11:26:26',2,'2010-02-02 17:59:21',6,NULL,1,'4b6859e978367',1,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `widgetInstances` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widgetProperties`
--

DROP TABLE IF EXISTS `zoolu`.`widgetProperties`;
CREATE TABLE  `zoolu`.`widgetProperties` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `key` varchar(60) NOT NULL,
  `value` varchar(255) NOT NULL,
  `idWidgets` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `widgetProperties_ibfk_1` (`idWidgets`),
  CONSTRAINT `widgetProperties_ibfk_1` FOREIGN KEY (`idWidgets`) REFERENCES `widgets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widgetProperties`
--

/*!40000 ALTER TABLE `widgetProperties` DISABLE KEYS */;
LOCK TABLES `widgetProperties` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `widgetProperties` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widgetTable`
--

DROP TABLE IF EXISTS `zoolu`.`widgetTable`;
CREATE TABLE  `zoolu`.`widgetTable` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `idGenericForms` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `zoolu`.`widgetTable`
--

/*!40000 ALTER TABLE `widgetTable` DISABLE KEYS */;
LOCK TABLES `widgetTable` WRITE;
INSERT INTO `zoolu`.`widgetTable` VALUES  (1,'widget_BlogEntries',16);
UNLOCK TABLES;
/*!40000 ALTER TABLE `widgetTable` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widget_BlogEntries`
--

DROP TABLE IF EXISTS `zoolu`.`widget_BlogEntries`;
CREATE TABLE  `zoolu`.`widget_BlogEntries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `subwidgetId` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widget_BlogEntries`
--

/*!40000 ALTER TABLE `widget_BlogEntries` DISABLE KEYS */;
LOCK TABLES `widget_BlogEntries` WRITE;
INSERT INTO `zoolu`.`widget_BlogEntries` VALUES  (1,'asdf','','4b71166822532'),
 (2,'jklö','','4b71169b65ac3'),
 (3,'','','4b7118948e132'),
 (4,'asdfsgsegs','','4b71197e46e1d');
UNLOCK TABLES;
/*!40000 ALTER TABLE `widget_BlogEntries` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widget_BlogEntryComments`
--

DROP TABLE IF EXISTS `zoolu`.`widget_BlogEntryComments`;
CREATE TABLE  `zoolu`.`widget_BlogEntryComments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `idWidget_BlogEntries` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `widget_BlogEntryComments` (`idWidget_BlogEntries`),
  CONSTRAINT `widget_BlogEntryComments` FOREIGN KEY (`idWidget_BlogEntries`) REFERENCES `widget_BlogEntries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widget_BlogEntryComments`
--

/*!40000 ALTER TABLE `widget_BlogEntryComments` DISABLE KEYS */;
LOCK TABLES `widget_BlogEntryComments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `widget_BlogEntryComments` ENABLE KEYS */;


--
-- Definition of table `zoolu`.`widgets`
--

DROP TABLE IF EXISTS `zoolu`.`widgets`;
CREATE TABLE  `zoolu`.`widgets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text,
  `version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zoolu`.`widgets`
--

/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
LOCK TABLES `widgets` WRITE;
INSERT INTO `zoolu`.`widgets` VALUES  (1,'blog','ich',NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
