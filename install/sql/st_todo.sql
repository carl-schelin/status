-- MySQL dump 10.14  Distrib 5.5.68-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: status
-- ------------------------------------------------------
-- Server version	5.5.68-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `st_todo`
--

DROP TABLE IF EXISTS `st_todo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `st_todo` (
  `todo_id` int(10) NOT NULL AUTO_INCREMENT,
  `todo_name` char(255) NOT NULL DEFAULT '',
  `todo_class` int(10) NOT NULL DEFAULT '0',
  `todo_project` int(10) NOT NULL DEFAULT '0',
  `todo_group` int(10) NOT NULL DEFAULT '0',
  `todo_save` int(10) NOT NULL DEFAULT '0',
  `todo_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `todo_due` int(10) NOT NULL DEFAULT '0',
  `todo_day` int(10) NOT NULL DEFAULT '0',
  `todo_time` int(10) NOT NULL DEFAULT '0',
  `todo_completed` int(10) NOT NULL DEFAULT '0',
  `todo_user` int(10) NOT NULL DEFAULT '0',
  `todo_priority` int(10) NOT NULL DEFAULT '0',
  `todo_status` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`todo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-28  1:03:26
