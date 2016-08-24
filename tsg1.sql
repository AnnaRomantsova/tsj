# Host: localhost  (Version: 5.1.40-community)
# Date: 2013-06-05 16:51:14
# Generator: MySQL-Front 5.3  (Build 2.53)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

#
# Source for table "rek"
#

DROP TABLE IF EXISTS `rek`;
CREATE TABLE `rek` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image1` varchar(255) DEFAULT NULL,
  `pabl` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251;

#
# Data for table "rek"
#

/*!40000 ALTER TABLE `rek` DISABLE KEYS */;
INSERT INTO `rek` VALUES (1,'/_files/Moduls/rek/images/T_rek_F_image1_I_1_v4.jpg',1),(2,'/_files/Moduls/rek/images/T_rek_F_image1_I_2_v2.jpg',0),(4,'/_files/Moduls/rek/images/T_rek_F_image1_I_4_v1.jpg',0);
/*!40000 ALTER TABLE `rek` ENABLE KEYS */;

#
# Source for table "reklama_city"
#

DROP TABLE IF EXISTS `reklama_city`;
CREATE TABLE `reklama_city` (
  `id_reklama` int(11) NOT NULL,
  `id_city` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;

#
# Data for table "reklama_city"
#

/*!40000 ALTER TABLE `reklama_city` DISABLE KEYS */;
INSERT INTO `reklama_city` VALUES (1,1),(7,1),(7,4),(7,2),(8,2),(8,1),(4,1),(4,4),(4,2),(2,1),(2,4),(2,2);
/*!40000 ALTER TABLE `reklama_city` ENABLE KEYS */;

#
# Source for table "reklama_page"
#

DROP TABLE IF EXISTS `reklama_page`;
CREATE TABLE `reklama_page` (
  `id_reklama` int(11) NOT NULL,
  `id_page` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;

#
# Data for table "reklama_page"
#

/*!40000 ALTER TABLE `reklama_page` DISABLE KEYS */;
INSERT INTO `reklama_page` VALUES (1,156),(1,152),(1,144),(1,143),(8,156),(8,137),(1,142),(1,141),(1,138),(1,137),(4,156),(4,152),(4,144),(4,143),(4,142),(4,141),(4,138),(4,137),(2,156),(2,152),(2,144),(2,143),(2,142),(2,141),(2,138),(2,137);
/*!40000 ALTER TABLE `reklama_page` ENABLE KEYS */;

#
# Source for table "site_tree"
#

DROP TABLE IF EXISTS `site_tree`;
CREATE TABLE `site_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) unsigned DEFAULT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  `section` enum('0','1') DEFAULT '0',
  `fix` enum('0','1') DEFAULT '0',
  `pabl` enum('0','1') DEFAULT '1',
  `page` varchar(255) DEFAULT NULL,
  `menu` enum('0','1') DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `shablon` tinyint(6) DEFAULT NULL,
  `main_section` varchar(255) DEFAULT NULL,
  `section1` varchar(255) DEFAULT NULL,
  `section2` varchar(255) DEFAULT NULL,
  `section3` varchar(255) DEFAULT NULL,
  `section4` varchar(255) DEFAULT NULL,
  `section5` varchar(255) DEFAULT NULL,
  `section6` varchar(255) DEFAULT NULL,
  `FieldName` tinyint(3) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=cp1251;

#
# Data for table "site_tree"
#

/*!40000 ALTER TABLE `site_tree` DISABLE KEYS */;
INSERT INTO `site_tree` VALUES (1,0,0,'1','1','1','���������','1','���������',1,NULL,'modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'','',''),(2,0,1,'1','1','1','p_1208089819','1','�������',1,'page=10;modul=49;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,NULL,'',''),(3,0,2,'0','0','1','index','1','�������',1,'page=10;modul=49;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'','',''),(16,59,1,'1','1','1','error404','0','Error404',1,NULL,'modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'','',''),(17,1,16,'0','1','1','error404','1','Error404',1,NULL,'modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'','',''),(48,1,1,'1','0','1','law','1','����������������',1,'modul=43;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'����������������','',''),(98,7,1,'1','1','1','p_1366380925','1','�������',1,NULL,'modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'�������','',''),(99,1,98,'0','1','1','news','1','�������',1,'page=2;modul=40;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'','',''),(114,0,48,'0','0','1','law','1','����������������',1,'page=1;modul=43;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'��������','',''),(130,65,1,'1','1','1','auth','0','�����������',1,'modul=22;','modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'�����������','',''),(131,56,130,'0','1','1','auth','0','�����������',1,'modul=22;','modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'�����������','',''),(133,66,1,'1','0','1',NULL,'1','������������',1,NULL,'modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'������������','',''),(134,67,133,'0','0','1','consult','1','������������',1,'page=9;modul=50;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'������������','',''),(135,68,1,'1','0','1',NULL,NULL,'���������� � ���',2,NULL,'modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,NULL,'',''),(136,69,135,'0','0','1','about','1','� ���',2,'page=98;modul=45;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'� ���','',''),(137,70,135,'0','0','1','manage','1','���������',2,'page=99;modul=46;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'���������','',''),(138,71,135,'0','0','1','service','1','������ �������',2,'page=100;modul=47;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'������ �������','',''),(139,72,135,'0','0','1','reports','1','����������',2,'page=101;modul=48;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'����������','',''),(141,75,135,'0','0','1','tsjnews','1','������� � �������',2,'page=103;modul=51;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'������� � �������','',''),(142,76,135,'0','0','1','opros','1','������ � �����������',2,'page=104;modul=53;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'������ � �����������','',''),(143,77,135,'0','0','1','galery','1','����������',2,'page=105;modul=52;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'����������','',''),(144,74,135,'0','0','1','zakupki_u','1','�������',2,'page=106;modul=59;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'�������','',''),(145,78,1,'1','0','1',NULL,NULL,'������ ����',1,NULL,'modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'������ ����','',''),(146,79,145,'0','0','1','project','1','� �������',1,'page=108;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'� �������','',''),(147,80,145,'0','0','1','commerce','1','�������',1,'page=109;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'�������','',''),(148,81,145,'0','0','1','userguide','1','���������������� ����������',1,'page=110;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'���������������� ����������','',''),(149,82,145,'1','0','1','feedb','1','�������� �����',1,NULL,'modul=42;',NULL,NULL,NULL,NULL,NULL,NULL,'�������� �����','',''),(150,83,145,'0','0','1','howto','0','��� �������� �������',1,'page=112;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'��� �������� �������','',''),(151,0,149,'0','0','1','feedb','1','�������� �����',1,'page=111;modul=56;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'�������� �����','',''),(152,1,135,'0','0','1','lk','1','��� �������',2,'modul=57;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,NULL,NULL,NULL),(153,84,1,'1','0','1',NULL,'1','�������',1,NULL,NULL,NULL,'page=3;',NULL,NULL,NULL,NULL,'�������','',''),(154,85,153,'0','0','1','zakupki','1','�������',1,'page=115;modul=59;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'�������','',''),(156,73,135,'0','0','1','tarif','1','������',2,'page=120;modul=62;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `site_tree` ENABLE KEYS */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
