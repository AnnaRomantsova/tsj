# Host: localhost  (Version: 5.1.40-community)
# Date: 2013-05-22 14:50:48
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
# Source for table "vendor"
#

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE `vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sort` int(10) unsigned DEFAULT '1',
  `fio` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `pass_text` varchar(30) NOT NULL,
  `act_category` varchar(255) DEFAULT NULL,
  `inn` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `image1` varchar(400) DEFAULT NULL,
  `about` text,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=cp1251 ROW_FORMAT=DYNAMIC;

#
# Data for table "vendor"
#

/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;
INSERT INTO `vendor` VALUES (42,NULL,'250343a4b5785ebaa4a49cbd4c59097f:WvCISuPVT5xvRYdoF6wl7RuHfLgarRiF','����',2,'������','+7 903 4011582','��. �����-��, 2','7',NULL,NULL,NULL,NULL,NULL),(43,'email','c81e728d9d4c2f636f067f89cc14862c','���� � ������1',1,'������','6545','','2','������� �������','654654 654654 ','',NULL,NULL),(53,'a@m.ru','33','abjhvf',1,'','','','182be0c5cdcd5072bb1864cdee4d3d','','','','',''),(54,'b@m.ru','e4da3b7fbbce2345d7772b0674a318d5','������',1,'','','','5','','','','',''),(55,'c@m.ru','c4ca4238a0b923820dcc509a6f75849b','��� �����',1,'����','26554','','1','����','111111111','','','');
/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;

#
# Source for table "zakupki"
#

DROP TABLE IF EXISTS `zakupki`;
CREATE TABLE `zakupki` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) DEFAULT NULL,
  `date_begin` int(11) DEFAULT NULL,
  `date_end` int(11) DEFAULT NULL,
  `status` tinyint(3) DEFAULT NULL,
  `id_house` int(11) DEFAULT NULL,
  `about` text,
  `preview` text,
  `watch` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=cp1251;

#
# Data for table "zakupki"
#

/*!40000 ALTER TABLE `zakupki` DISABLE KEYS */;
INSERT INTO `zakupki` VALUES (1,'������� �������          ',1358542800,1369252800,1,14,'<p>erter</p>\r\n',' ������ ��������� 50 ��.',14),(12,'������� �����',2205,1605,1,15,'',NULL,0),(13,'������� ������������',2305,2305,0,14,'<p>������ ����� �� �������� ������ ���������� �� ����� ������� &mdash; �� ����� ������ �������, �� �� ��� ������ �� ������ �� ��������. ��� ���������� � ��������. ������ � ������� ��� �� ��������, � ��� ����� ������� �� ����� � ����� �� ������� �����.</p>\r\n\r\n<p>���������� �� �������� ����������� ����� ������������ �� ������� ���������� � ������� ������� ���������. � ���, ��� �������� ��������� &laquo;��������������� ���������&raquo; � ����� ����������� � ����� ������ ��������������� ������������������ ������������ ��� ������� �������, ������ �������� ����� � ������� ��������.</p>\r\n',NULL,1);
/*!40000 ALTER TABLE `zakupki` ENABLE KEYS */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
