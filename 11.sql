# Host: localhost  (Version: 5.1.40-community)
# Date: 2013-05-27 09:55:16
# Generator: MySQL-Front 5.3  (Build 2.53)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

#
# Source for table "site_moduls"
#

DROP TABLE IF EXISTS `site_moduls`;
CREATE TABLE `site_moduls` (
  `Id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `command` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=137 DEFAULT CHARSET=cp1251;

#
# Data for table "site_moduls"
#

/*!40000 ALTER TABLE `site_moduls` DISABLE KEYS */;
INSERT INTO `site_moduls` VALUES (10,'Каталог','/catalog/front.php',NULL),(11,'Каталог-главная','/catalog/panel.php',NULL),(12,'Каталог-меню','/catalog/menu.php',NULL),(14,'Авторизация-панель','/auth/front.php',NULL),(15,'Авторизация-вход','/auth/front.php',NULL),(16,'Поиск-панель','/search/panel.php',NULL),(17,'Личный кабинет','/cabinet/front.php',NULL),(18,'Корзина','/basket/front.php',NULL),(19,'Поиск','/search/front.php',NULL),(20,'Курсы валют','/catalog/kurs.php',NULL),(21,'Оформление заказа','/catalog/order.php',NULL),(22,'авторизация-главная','/auth/messages.php',NULL),(23,'всплыв в каталоге','/auth/pupup.php',NULL),(40,'Новости','/news/front.php',NULL),(41,'Новости','/news/panel.php',NULL),(42,'дом панель','/house/panel.php',NULL),(43,'Законодательство','/law/front.php',NULL),(44,'левое меню','/menu/menu_sub.php',NULL),(45,'о нас','/about/front.php',NULL),(46,'правление','/manage/front.php',NULL),(47,'Службы сервиса','/service/front.php',NULL),(48,'Отчеты','/reports/front.php',NULL),(49,'новости на главной','/news/panel.php',NULL),(50,'консультациии','/consult/front.php',NULL),(51,'новости тсж','/tsjnews/front.php',NULL),(52,'Фотогаллерея','/gallery/front.php',NULL),(53,'Опрос','/opros/front.php',NULL),(54,'ниж меню','/menu/menu_buttom.php',NULL),(55,'нижнее меню','/reklama/front.php',NULL),(56,'обр связь','/feedb/front.php',NULL),(57,'лич. каб поставщиков','/vendor/front.php',NULL),(58,'автор. поставщика','/vendor/messages.php',NULL),(59,'закупки','/zakupki/front.php',NULL),(60,'меню поставщ','/vendor/menu_vendor.php',NULL),(61,'закупки поставщика','/zakupki/vendor.php',NULL);
/*!40000 ALTER TABLE `site_moduls` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=cp1251;

#
# Data for table "site_tree"
#

/*!40000 ALTER TABLE `site_tree` DISABLE KEYS */;
INSERT INTO `site_tree` VALUES (1,0,0,'1','1','1','Структура','1','Структура',1,NULL,'modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'','',''),(2,0,1,'1','1','1','p_1208089819','1','Главная',1,'page=10;modul=49;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,NULL,'',''),(3,0,2,'0','0','1','index','1','Главная',1,'page=10;modul=49;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'','',''),(16,59,1,'1','1','1','error404','0','Error404',1,NULL,'modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'','',''),(17,1,16,'0','1','1','error404','1','Error404',1,NULL,'modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'','',''),(48,1,1,'1','0','1','law','1','Законодательство',1,'modul=43;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'Законодательство','',''),(98,7,1,'1','1','1','p_1366380925','1','Новости',1,NULL,'modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'Новости','',''),(99,1,98,'0','1','1','news','1','Новости',1,'page=2;modul=40;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'','',''),(114,0,48,'0','0','1','law','1','Законодательство',1,'page=1;modul=43;','modul=42;',NULL,'modul=54;','modul=55;','','',NULL,'Компания','',''),(130,65,1,'1','1','1','auth','0','Авторизация',1,'modul=22;','modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'Авторизация','',''),(131,56,130,'0','1','1','auth','0','Авторизация',1,'modul=22;','modul=42;','modul=44;','modul=54;','modul=55;','','',NULL,'Авторизация','',''),(133,66,1,'1','0','1',NULL,'1','Консультации',1,NULL,'modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Консультации','',''),(134,67,133,'0','0','1','consult','1','Консультации',1,'page=9;modul=50;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Консультации','',''),(135,68,1,'1','0','1',NULL,NULL,'Информация о ТСЖ',2,NULL,'modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,NULL,'',''),(136,69,135,'0','0','1','about','1','О нас',2,'page=98;modul=45;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'О нас','',''),(137,70,135,'0','0','1','manage','1','Правление',2,'page=99;modul=46;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'Правление','',''),(138,71,135,'0','0','1','service','1','Службы сервиса',2,'page=100;modul=47;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'Службы сервиса','',''),(139,72,135,'0','0','1','reports','1','Отчетность',2,'page=101;modul=48;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'Отчетность','',''),(141,75,135,'0','0','1','tsjnews','1','События и новости',2,'page=103;modul=51;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'События и новости','',''),(142,76,135,'0','0','1','opros','1','Опросы и голосования',2,'page=104;modul=53;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,'Опросы и голосования','',''),(143,77,135,'0','0','1','galery','1','Фотоальбом',2,'page=105;modul=52;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'Фотоальбом','',''),(144,74,135,'0','0','1','zakupki','1','Закупки',2,'page=106;modul=59;','modul=42;','modul=44;','modul=54;',NULL,NULL,NULL,NULL,'Закупки','',''),(145,78,1,'1','0','1',NULL,NULL,'Нижнее меню',1,NULL,'modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Нижнее меню','',''),(146,79,145,'0','0','1','project','1','О проекте',1,'page=108;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'О проекте','',''),(147,80,145,'0','0','1','commerce','1','Реклама',1,'page=109;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Реклама','',''),(148,81,145,'0','0','1','userguide','1','Пользовательское соглашение',1,'page=110;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Пользовательское соглашение','',''),(149,82,145,'1','0','1','feedb','1','Обратная связь',1,NULL,'modul=42;',NULL,NULL,NULL,NULL,NULL,NULL,'Обратная связь','',''),(150,83,145,'0','0','1','howto','0','Как заказать рекламу',1,'page=112;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Как заказать рекламу','',''),(151,0,149,'0','0','1','feedb','1','Обратная связь',2,'page=111;modul=56;','modul=42;',NULL,'modul=54;','modul=55;',NULL,NULL,NULL,'Обратная связь','',''),(152,1,135,'0','0','1','lk','1','Личный кабинет',2,'modul=57;','modul=42;','modul=44;','modul=54;','modul=55;',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `site_tree` ENABLE KEYS */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
